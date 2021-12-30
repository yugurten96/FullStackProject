import Head from "next/head";
import LineChart from "../../components/lineChart/lineChart";
import {GetStaticProps} from "next";
import {fetch} from "../../utils/dataAccess";

const Page = ({data}) => {
  return (
    <div>
      <div>
        <Head>
          <title>Prix moyen du m²</title>
          <script src="https://d3js.org/d3.v7.min.js"/>
        </Head>
      </div>
      <div id="lineChart" style={{width: "50%", margin: "auto"}}>
        <LineChart data={data}/>
      </div>
    </div>
  )
}

export const getStaticProps: GetStaticProps = async (context) => {
  const collection = await fetch("/property/average");
  return {
    props: {
      data: collection.data,
    }
  }
}

export default Page;
