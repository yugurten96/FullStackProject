import { NextComponentType, NextPageContext } from "next";
import { Form } from "../../components/greeting/Form";
import Head from "next/head";

const Page: NextComponentType<NextPageContext> = () => (
  <div>
    <div>
      <Head>
        <title>Create Greeting </title>
      </Head>
    </div>
    <Form />
  </div>
);

export default Page;
