import Head from "next/head";
import D3 from "../../components/graphD3/lineChart";

const Page = () => {
  return (
    <div>
      <div>
        <Head>
          <title>LineGraph exemple</title>
          <script src="https://d3js.org/d3.v7.min.js"/>
        </Head>
      </div>
      <div id="my_dataviz">
        <D3/>
      </div>
    </div>
  )
}

export default Page;
