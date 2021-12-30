import Head from "next/head";
import TimeSeries from "../../components/timeSeries/timeSeries";
import {GetStaticProps} from "next";
import {fetch} from "../../utils/dataAccess";

const Page = ({data}) => {
  return (
    <div>
      <div>
        <Head>
          <title>Nombre de ventes par régions</title>
          <script src="https://d3js.org/d3.v7.min.js"/>
        </Head>
      </div>
      <div id="timeSeries">
        <TimeSeries data={data}/>
      </div>
    </div>
  )
}

export const getStaticProps: GetStaticProps = async (context) => {
  const collection = await fetch("/property/sell/2017");
  return {
    props: {
      data: collection.data,
    }
  }
}

export default Page;
