import React, {useEffect, useState} from "react";
import DatePicker from "./DatePicker";
import {fetch} from "../../utils/dataAccess";

const dim = {
  width : 800,
  height : 400
}

const getDateFormat = (donne) => {
  let l = donne.split('-').length
  if (l === 3)
    return "%d-%m-%Y";
  else if (l == 2)
    return "%m-%Y"
  return "%Y"
}

const formatDate = (data) => {
  let format = getDateFormat(data[0].key)
  return data.map(d => {
    return {key: d3.timeParse(format)(d.key), value: d.value}
  })
}
const BarChart = ({data}) => {
  const [donne, setDonne] = useState(data)
  const [startDate, setStartDate] = useState(new Date('2017-01-01'));
  const [endDate, setEndDate] = useState(new Date('2021-12-31'));
  const [period, setPeriod] = useState("year")

  const getXAxis = (arr) => {

    if (arr.length <= 40) {
      return d3.scaleBand()
        .range([0, dim.width])
        .domain(arr.map(d => d.key))
    }
    return d3.scaleTime()
      .domain(d3.extent(arr, (d) => d.key)).range([0, dim.width])

  }

  const displayXAxis = (svg, arr, x) => {
    if (arr.length <= 40) {
      svg.append("g")
        .attr("transform", "translate(0," + dim.height + ")")
        .call(d3.axisBottom(x))
        .selectAll("text")
        .attr("transform", "translate(-10,0)rotate(-45)")
        .style("text-anchor", "end")
        .style("font-size", "10px")
        .style("font-weight", "bold")
    } else {
      svg.append("g")
        .attr("transform", "translate(0," + dim.height + ")")
        .call(d3.axisBottom(x));
    }
  }

  useEffect(() => {
    const arr = donne.length > 40 ? formatDate(donne) : donne

    const svg = d3.select('#bar_chart').html("");
    svg
      .attr("viewBox", `0 0 ${dim.width} ${dim.height}`)
      .attr("preserveAspectRatio", "xMinYMin meet")
      .style("display", "block")
      .style('margin', 'auto')
      .style('overflow', 'visible')

    //Scalling for x-axis
    const x = getXAxis(arr)

    //display x-axis
    displayXAxis(svg, arr, x)

    //x-axis Label
    svg.append("text")
      .attr("transform", "translate(" + (dim.width / 2) + " ," + (dim.height + 60) + ")")

    //Scalling for y-axis
    const y = d3.scaleLinear()
      .domain([0, d3.max(arr, (d) => d.value)]).range([dim.height, 0]);

    //display y-axis
    svg.append("g")
      .style("font-size", "12px")
      .style("font-weight", "bold")
      .call(d3.axisLeft(y));

    //y-axis Label
    svg.append("text")
      .attr("transform", "rotate(-90)")
      .attr("x", 0 - (dim.height / 2))
      .attr("y", -100)

    const bar = svg.selectAll(".rect").data(arr)
    const info = d3.select(".circle-info")

    bar.enter()
      .append("rect")
      .attr("x", d => x(d.key))
      .attr("fill", "#69b3a2")
      .attr("width", 80)
      .attr("height", function(d) { return dim.height - y(0); }) // always equal to 0
      .attr("y", d => y(0))
      .attr("class", "rect")
      .on("mousemove", (d, i) => {
        d.target.classList.remove('rect')
        d.target.classList.add("rect-focus")
        const key = arr.length > 40 ? d3.timeFormat(getDateFormat(donne[0].key))(i.key) : i.key;
        info.html("Date : " + key + "<br/> Nombre de ventes :" + i.value)
          .style("visibility", "visible")
          .style('top', d.pageY - 12 + 'px')
          .style('left', d.pageX + 25 + 'px')
      })
      .on("mouseleave", (d, i) => {
        d.target.classList.remove('rect-focus')
        d.target.classList.add("rect")
        info.style("visibility", "hidden")
      })
      .on('mouseover', function (d, i) {
        d3.select(this).transition()
          .attr('opacity', '.85')
      })
      .on('mouseout', function (d, i) {
        d3.select(this).transition()
          .attr('opacity', '1')
      })
    bar.exit().remove()

    svg.selectAll("rect")
      .transition()
      .duration(800)
      .attr("y", d => y(d.value))
      .attr("height", d => dim.height - y(d.value))
      .delay(function(d,i){console.log(i) ; return(i*100)})
  }, [donne])

  const handleClick = async () => {
    const data = await fetch(`/property/count/${period}/${d3.timeFormat("%d-%m-%Y")(startDate)}/${d3.timeFormat("%d-%m-%Y")(endDate)}`)
    setDonne(data.data)
  }

   return (
    <div>
      <div className="bar-chart">
        <div style={{display: 'flex', width: '0%', justifyContent: 'space-between'}}>
          <DatePicker date={startDate} setDate={setStartDate} color="#5b8da9" minDate={undefined} type="start"/>
          <DatePicker date={endDate} setDate={setEndDate} color="#7c3d3d" minDate={startDate} type="end"/>
          <select className="form-select" onChange={event => {
            setPeriod(event.target.value)
          }}>
            <option value="year">Year</option>
            <option value="month">Month</option>
            <option value="day">Day</option>
          </select>
        </div>
        <button className="btn btn-outline-info" onClick={handleClick}>Load Date</button>
      </div>
      <div>
        <svg id="bar_chart"/>
      </div>
    </div>
  )
};

export default BarChart;
