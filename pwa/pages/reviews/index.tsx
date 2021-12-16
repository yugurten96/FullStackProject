import { NextComponentType, NextPageContext } from "next";
import { List } from "../../components/review/List";
import { PagedCollection } from "../../types/Collection";
import { Review } from "../../types/Review";
import { fetch } from "../../utils/dataAccess";
import Head from "next/head";

interface Props {
  collection: PagedCollection<Review>;
}

const Page: NextComponentType<NextPageContext, Props, Props> = ({
  collection,
}) => (
  <div>
    <div>
      <Head>
        <title>Review List</title>
      </Head>
    </div>
    <List reviews={collection["hydra:member"]} />
  </div>
);

Page.getInitialProps = async () => {
  const collection = await fetch("/reviews");

  return { collection };
};

export default Page;
