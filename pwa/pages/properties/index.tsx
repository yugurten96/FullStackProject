import { NextComponentType, NextPageContext } from "next";
import { List } from "../../components/property/List";
import { PagedCollection } from "../../types/Collection";
import { Property } from "../../types/Property";
import { fetch } from "../../utils/dataAccess";
import Head from "next/head";

interface Props {
  collection: PagedCollection<Property>;
}

const Page: NextComponentType<NextPageContext, Props, Props> = ({
  collection,
}) => (
  <div>
    <div>
      <Head>
        <title>Property List</title>
      </Head>
    </div>
    <List properties={collection["hydra:member"]} />
  </div>
);

Page.getInitialProps = async () => {
  const collection = await fetch("/properties");

  return { collection };
};

export default Page;
