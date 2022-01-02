import Head from "next/head";
import CircularDiagram from "../../components/circularDiagram/circularDiagram";
import {GetStaticProps} from "next";
import {fetch} from "../../utils/dataAccess";
import Layout from "../../components/layout";

const Page = ({data}) => {
  return (
    <div>
      <div>
        <Layout title={
          <Head>
            <title>CircularDiagram</title>
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css"/>
            <script src='https://use.fontawesome.com/releases/v5.15.4/js/all.js' data-auto-a11y='true'/>
            <script src="https://d3js.org/d3.v7.min.js"/>
          </Head>
        } main={
          <div>
            <div className="bar-chart">
              <div className="columns">
                <div className="column">
                  <div>
                    <p className="title has-text-centered">Nombre de ventes par régions selon l'année</p>
                  </div>
                </div>
              </div>
              <div className="columns">
                <div className="column">
                  <div className="bar-chart">
                    <div id="circularDiagram">
                      <CircularDiagram data={data}/>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        }/>
      </div>
    </div>
  )
}

export const getStaticProps: GetStaticProps = async () => {
  const collection = await fetch("/property/sell/2017");
  return {
    props: {
      data: collection.data,
    }
  }
}

export default Page;
