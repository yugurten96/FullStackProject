import Head from "next/head";
import CircularDiagram from "../../components/circularDiagram/circularDiagram";
import {GetStaticProps} from "next";
import {fetch} from "../../utils/dataAccess";
import Layout from "../../components/layout";
import {Circus} from "@jest/types";
import {useState} from "react";


const Page = ({data}) => {
  return (
    <div>
      <div>
        <Layout title={
          <Head>
            <title>Nombre de ventes par régions</title>
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css"/>
            <script src='https://use.fontawesome.com/releases/v5.15.4/js/all.js' data-auto-a11y='true'/>
            <script src="https://d3js.org/d3.v7.min.js"/>
          </Head>
        } main={
          <div id="circularDiagram">
            <CircularDiagram data={data}/>
          </div>
        }/>
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
