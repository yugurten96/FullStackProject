import { FunctionComponent, useState } from "react";
import Link from "next/link";
import { useRouter } from "next/router";
import { fetch } from "../../utils/dataAccess";
import ReferenceLinks from "../common/ReferenceLinks";
import { Property } from "../../types/Property";

interface Props {
  property: Property;
}

export const Show: FunctionComponent<Props> = ({ property }) => {
  const [error, setError] = useState(null);
  const router = useRouter();

  const handleDelete = async () => {
    if (!window.confirm("Are you sure you want to delete this item?")) return;

    try {
      await fetch(property["@id"], { method: "DELETE" });
      router.push("/properties");
    } catch (error) {
      setError("Error when deleting the resource.");
      console.error(error);
    }
  };

  return (
    <div>
      <h1>{`Show Property ${property["@id"]}`}</h1>
      <table className="table table-responsive table-striped table-hover">
        <thead>
          <tr>
            <th>Field</th>
            <th>Value</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <th scope="row">region</th>
            <td>{property["region"]}</td>
          </tr>
          <tr>
            <th scope="row">surface</th>
            <td>{property["surface"]}</td>
          </tr>
          <tr>
            <th scope="row">price</th>
            <td>{property["price"]}</td>
          </tr>
          <tr>
            <th scope="row">sellDay</th>
            <td>{property["sellDay"]}</td>
          </tr>
          <tr>
            <th scope="row">sellMonth</th>
            <td>{property["sellMonth"]}</td>
          </tr>
          <tr>
            <th scope="row">sellYear</th>
            <td>{property["sellYear"]}</td>
          </tr>
          <tr>
            <th scope="row">count</th>
            <td>{property["count"]}</td>
          </tr>
          <tr>
            <th scope="row">sellDate</th>
            <td>{property["sellDate"]}</td>
          </tr>
        </tbody>
      </table>
      {error && (
        <div className="alert alert-danger" role="alert">
          {error}
        </div>
      )}
      <Link href="/properties">
        <a className="btn btn-primary">Back to list</a>
      </Link>{" "}
      <Link href={`${property["@id"]}/edit`}>
        <a className="btn btn-warning">Edit</a>
      </Link>
      <button className="btn btn-danger" onClick={handleDelete}>
        <a>Delete</a>
      </button>
    </div>
  );
};
