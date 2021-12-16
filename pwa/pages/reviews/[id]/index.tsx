import { NextComponentType, NextPageContext } from "next";
import { Show } from "../../../components/review/Show";
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
          <title>{`Show Review ${review["@id"]}`}</title>
        </Head>
      </div>
      <Show review={review} />
    </div>
  );
};

Page.getInitialProps = async ({ asPath }: NextPageContext) => {
  const review = await fetch(asPath);

  return { review };
};

export default Page;
