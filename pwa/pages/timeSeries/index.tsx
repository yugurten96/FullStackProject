import TimeSeries from "../../components/timeSeries/timeSeries";
import {GetStaticProps} from "next";
import {fetch} from "../../utils/dataAccess";
import Layout from "../../components/layout";
import Head from "next/head";

const Page = ({data}) => {
  return (
    <div>
      <Layout title={
        <Head>
          <title>TimeSeries</title>
          <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css"/>
          <script src='https://use.fontawesome.com/releases/v5.15.4/js/all.js' data-auto-a11y='true'/>
          <script src="https://d3js.org/d3.v7.min.js"/>
        </Head>
      } main={
        <div>
          <div>
            <p className="title has-text-centered">Prix moyen du m² selon l'année</p>
          </div>
          <div id="timeSeries">
            <TimeSeries data={data}/>
          </div>
        </div>
      }/>
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
