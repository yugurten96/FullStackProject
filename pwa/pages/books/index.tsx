import { NextComponentType, NextPageContext } from "next";
import { List } from "../../components/book/List";
import { PagedCollection } from "../../types/Collection";
import { Book } from "../../types/Book";
import { fetch } from "../../utils/dataAccess";
import Head from "next/head";

interface Props {
  collection: PagedCollection<Book>;
}

const Page: NextComponentType<NextPageContext, Props, Props> = ({
  collection,
}) => (
  <div>
    <div>
      <Head>
        <title>Book List</title>
      </Head>
    </div>
    <List books={collection["hydra:member"]} />
  </div>
);

Page.getInitialProps = async () => {
  const collection = await fetch("/books");

  return { collection };
};

export default Page;
