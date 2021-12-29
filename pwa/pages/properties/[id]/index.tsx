import { NextComponentType, NextPageContext } from "next";
import { Show } from "../../../components/property/Show";
import { Property } from "../../../types/Property";
import { fetch } from "../../../utils/dataAccess";
import Head from "next/head";

interface Props {
  property: Property;
}

const Page: NextComponentType<NextPageContext, Props, Props> = ({
  property,
}) => {
  return (
    <div>
      <div>
        <Head>
          <title>{`Show Property ${property["@id"]}`}</title>
        </Head>
      </div>
      <Show property={property} />
    </div>
  );
};

Page.getInitialProps = async ({ asPath }: NextPageContext) => {
  const property = await fetch(asPath);

  return { property };
};

export default Page;
