import { NextComponentType, NextPageContext } from "next";
import { Form } from "../../../components/book/Form";
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
          <title>{book && `Edit Book ${book["@id"]}`}</title>
        </Head>
      </div>
      <Form book={book} />
    </div>
  );
};

Page.getInitialProps = async ({ asPath }: NextPageContext) => {
  const book = await fetch(asPath.replace("/edit", ""));

  return { book };
};

export default Page;
