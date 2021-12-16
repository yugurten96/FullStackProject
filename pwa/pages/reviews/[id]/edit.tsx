import { NextComponentType, NextPageContext } from "next";
import { Form } from "../../../components/review/Form";
import { Review } from "../../../types/Review";
import { fetch } from "../../../utils/dataAccess";
import Head from "next/head";

interface Props {
  review: Review;
}

const Page: NextComponentType<NextPageContext, Props, Props> = ({ review }) => {
  return (
    <div>
      <div>
        <Head>
          <title>{review && `Edit Review ${review["@id"]}`}</title>
        </Head>
      </div>
      <Form review={review} />
    </div>
  );
};

Page.getInitialProps = async ({ asPath }: NextPageContext) => {
  const review = await fetch(asPath.replace("/edit", ""));

  return { review };
};

export default Page;
