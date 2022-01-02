import Head from "next/head";
import BarChart from "../../components/barChart/barChart";
import {GetStaticProps} from "next";
import Layout from "../../components/layout";
import {fetch} from "../../utils/dataAccess";

const Page = ({data}) => {
  return (
    <div>
      <Layout title={
        <Head>
          <title>BarChart</title>
          <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css"/>
          <script src='https://use.fontawesome.com/releases/v5.15.4/js/all.js' data-auto-a11y='true'/>
          <script src="https://d3js.org/d3.v7.min.js"/>
        </Head>
      } main={
        <div>
          <div>
            <p className="title has-text-centered">Nombre total de ventes selon la période sélectionnée</p>
          </div>
          <div id="barchart">
            <BarChart data={data}/>
          </div>
        </div>
      }/>
    </div>
  )
}

export const getStaticProps: GetStaticProps = async () => {
  const collection = await fetch("/property/count/year/1-1-2017/31-12-2021");
  return {
    props: {
      data: collection.data,
    }
  }
}

export default Page;
