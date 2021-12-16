import { NextComponentType, NextPageContext } from "next";
import { Show } from "../../../components/book/Show";
import { Book } from "../../../types/Book";
import { fetch } from "../../../utils/dataAccess";
import Head from "next/head";

interface Props {
  book: Book;
}

const Page: NextComponentType<NextPageContext, Props, Props> = ({ book }) => {
  return (
    <div>
      <div>
        <Head>
          <title>{`Show Book ${book["@id"]}`}</title>
        </Head>
      </div>
      <Show book={book} />
    </div>
  );
};

Page.getInitialProps = async ({ asPath }: NextPageContext) => {
  const book = await fetch(asPath);

  return { book };
};

export default Page;
