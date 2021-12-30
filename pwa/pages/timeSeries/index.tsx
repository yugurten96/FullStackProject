import Head from "next/head";
import TimeSeries from "../../components/timeSeries/timeSeries";
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
      <div id="timeSeries" style={{width: "50%", margin: "auto"}}>
        <TimeSeries data={data}/>
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
