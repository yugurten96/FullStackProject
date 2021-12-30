import Head from "next/head";
import BarChart from "../../components/barChart/barChart";
import {GetStaticProps} from "next";
import {fetch} from "../../utils/dataAccess";

const Page = ({data}) => {
  return (
    <div>
      <div>
        <Head>
          <title>Nombre total de ventes</title>
          <script src="https://cdnjs.cloudflare.com/ajax/libs/d3/3.5.17/d3.min.js"/>
        </Head>
      </div>
      <div id="barChart">
        <BarChart data={data}/>
      </div>
    </div>
  )
}

export const getStaticProps: GetStaticProps = async (context) => {
  const collection = await fetch("/property/count/year/1-1-2017/1-10-2020");
  return {
    props: {
      data: collection.data,
    }
  }
}

export default Page;
