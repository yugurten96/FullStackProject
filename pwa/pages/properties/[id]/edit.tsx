import { NextComponentType, NextPageContext } from "next";
import { Form } from "../../../components/property/Form";
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
          <title>{property && `Edit Property ${property["@id"]}`}</title>
        </Head>
      </div>
      <Form property={property} />
    </div>
  );
};

Page.getInitialProps = async ({ asPath }: NextPageContext) => {
  const property = await fetch(asPath.replace("/edit", ""));

  return { property };
};

export default Page;
