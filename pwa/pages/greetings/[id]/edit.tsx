import { NextComponentType, NextPageContext } from "next";
import { Form } from "../../../components/greeting/Form";
import { Greeting } from "../../../types/Greeting";
import { fetch } from "../../../utils/dataAccess";
import Head from "next/head";

interface Props {
  greeting: Greeting;
}

const Page: NextComponentType<NextPageContext, Props, Props> = ({
  greeting,
}) => {
  return (
    <div>
      <div>
        <Head>
          <title>{greeting && `Edit Greeting ${greeting["@id"]}`}</title>
        </Head>
      </div>
      <Form greeting={greeting} />
    </div>
  );
};

Page.getInitialProps = async ({ asPath }: NextPageContext) => {
  const greeting = await fetch(asPath.replace("/edit", ""));

  return { greeting };
};

export default Page;
