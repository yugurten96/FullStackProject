import { FunctionComponent } from "react";
import Link from "next/link";
import ReferenceLinks from "../../components/common/ReferenceLinks";
import { Property } from "../../types/Property";

interface Props {
  properties: Property[];
}

export const List: FunctionComponent<Props> = ({ properties }) => (
  <div>
    <h1>Property List</h1>
    <Link href="/properties/create">
      <a className="btn btn-primary">Create</a>
    </Link>
    <table className="table table-responsive table-striped table-hover">
      <thead>
        <tr>
          <th>id</th>
          <th>region</th>
          <th>surface</th>
          <th>price</th>
          <th>sellDay</th>
          <th>sellMonth</th>
          <th>sellYear</th>
          <th>count</th>
          <th>sellDate</th>
          <th />
        </tr>
      </thead>
      <tbody>
        {properties &&
          properties.length !== 0 &&
          properties.map((property) => (
            <tr key={property["@id"]}>
              <th scope="row">
                <ReferenceLinks items={property["@id"]} type="property" />
              </th>
              <td>{property["region"]}</td>
              <td>{property["surface"]}</td>
              <td>{property["price"]}</td>
              <td>{property["sellDay"]}</td>
              <td>{property["sellMonth"]}</td>
              <td>{property["sellYear"]}</td>
              <td>{property["count"]}</td>
              <td>{property["sellDate"]}</td>
              <td>
                <ReferenceLinks
                  items={property["@id"]}
                  type="property"
                  useIcon={true}
                />
              </td>
              <td>
                <Link href={`${property["@id"]}/edit`}>
                  <a>
                    <i className="bi bi-pen" aria-hidden="true" />
                    <span className="sr-only">Edit</span>
                  </a>
                </Link>
              </td>
            </tr>
          ))}
      </tbody>
    </table>
  </div>
);
