import Head from "next/head";
import BarChart from "../../components/barChart/barChart";
import {GetStaticProps} from "next";
import Layout from "../../components/layout";
import {fetch} from "../../utils/dataAccess";


const Page = ({data}) => {
  return (
    <div>
      <div>
        <Layout title={
          <Head>
            <title>Nombre total de ventes</title>
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css"/>
            <script src='https://use.fontawesome.com/releases/v5.15.4/js/all.js' data-auto-a11y='true'/>
            <script src="https://d3js.org/d3.v7.min.js"/>
          </Head>
        } main={
          <div id="barchart">
            <BarChart data={data}/>
          </div>
        }/>
      </div>

    </div>
  )
}

export const getStaticProps: GetStaticProps = async (context) => {
  const collection = await fetch("/property/count/year/1-1-2017/1-4-2021");
  return {
    props: {
      data: collection.data,
    }
  }
}

export default Page;
