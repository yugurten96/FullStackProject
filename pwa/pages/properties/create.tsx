import { NextComponentType, NextPageContext } from "next";
import { Form } from "../../components/property/Form";
import Head from "next/head";

const Page: NextComponentType<NextPageContext> = () => (
  <div>
    <div>
      <Head>
        <title>Create Property </title>
      </Head>
    </div>
    <Form />
  </div>
);

export default Page;
