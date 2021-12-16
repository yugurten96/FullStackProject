import { NextComponentType, NextPageContext } from "next";
import { List } from "../../components/greeting/List";
import { PagedCollection } from "../../types/Collection";
import { Greeting } from "../../types/Greeting";
import { fetch } from "../../utils/dataAccess";
import Head from "next/head";

interface Props {
  collection: PagedCollection<Greeting>;
}

const Page: NextComponentType<NextPageContext, Props, Props> = ({
  collection,
}) => (
  <div>
    <div>
      <Head>
        <title>Greeting List</title>
      </Head>
    </div>
    <List greetings={collection["hydra:member"]} />
  </div>
);

Page.getInitialProps = async () => {
  const collection = await fetch("/greetings");

  return { collection };
};

export default Page;
