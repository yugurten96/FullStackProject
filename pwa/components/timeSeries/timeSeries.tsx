import React, {useEffect} from "react";

const TimeSeries = ({data}) => {
  useEffect(() => {
    // Set the dimensions and margins of the graph
    const margin = {top: 60, right: 30, bottom: 30, left: 60},
      width = 800 - margin.left - margin.right,
      height = 400 - margin.top - margin.bottom;

    // Append the svg object to the body of the page
    const svg = d3.select("#timeSeries")
      .append("svg")
      .attr("width", width + margin.left + margin.right)
      .attr("height", height + margin.top + margin.bottom)
      .append("g")
      .attr("transform", `translate(${margin.left},${margin.top})`);

    // Reading data
    const arr = data.map(d => {
      return {key: d3.timeParse("%Y")(d.key), value: d.value}
    })

    // Add X axis
    const x = d3.scaleTime()
      .domain(d3.extent(arr,(d) => d.key))
      .range([0, width]);
    svg.append("g")
      .attr("transform", `translate(0, ${height})`)
      .call(d3.axisBottom(x).ticks(d3.timeYear));

    // Add Y axis
    const y = d3.scaleLinear()
      .domain([0, d3.max(arr, (d) => +d.value)])
      .range([height, 0]);
    svg.append("g")
      .call(d3.axisLeft(y));
    svg.append("text")
      .attr("x", (width / 2))
      .attr("y", 0 - (margin.top / 2))
      .attr("text-anchor", "middle")
      .style("font-size", "20px")
      .text("Prix moyen du m²");

    // Set the gradient
    svg.append("linearGradient")
      .attr("id", "line-gradient")
      .attr("gradientUnits", "userSpaceOnUse")
      .attr("x1", 0)
      .attr("y1", y(0))
      .attr("x2", 0)
      .attr("y2", y(d3.max(arr, (d) => +d.value)))
      .selectAll("stop")
      .data([
        {offset: "50%", color: "blue"},
        {offset: "100%", color: "red"}
      ])
      .enter().append("stop")
      .attr("offset", d => (d.offset))
      .attr("stop-color", d => d.color)

    // Add the line
    svg.append("path")
      .datum(arr)
      .attr("fill", "none")
      .attr("stroke", "url(#line-gradient)")
      .attr("stroke-width", 2)
      .attr("d", d3.line()
        .x(d => x(d.key))
        .y(d => y(d.value))
        .curve(d3.curveMonotoneX)
      )

    svg.selectAll(".circle")
      .data(arr)
      .enter()
      .append("circle")
      .attr("class","circle")
      .attr("cx",d => x(d.key))
      .attr("cy",d => y(d.value))
      .attr("r",3)
      .style("fill", "red")
  }, [])

  return (
    <svg id="timeSeries"/>
  )
}

export default TimeSeries;
