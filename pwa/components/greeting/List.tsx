import { FunctionComponent } from "react";
import Link from "next/link";
import ReferenceLinks from "../../components/common/ReferenceLinks";
import { Greeting } from "../../types/Greeting";

interface Props {
  greetings: Greeting[];
}

export const List: FunctionComponent<Props> = ({ greetings }) => (
  <div>
    <h1>Greeting List</h1>
    <Link href="/greetings/create">
      <a className="btn btn-primary">Create</a>
    </Link>
    <table className="table table-responsive table-striped table-hover">
      <thead>
        <tr>
          <th>id</th>
          <th>name</th>
          <th>id</th>
          <th />
        </tr>
      </thead>
      <tbody>
        {greetings &&
          greetings.length !== 0 &&
          greetings.map((greeting) => (
            <tr key={greeting["@id"]}>
              <th scope="row">
                <ReferenceLinks items={greeting["@id"]} type="greeting" />
              </th>
              <td>{greeting["name"]}</td>
              <td>{greeting["id"]}</td>
              <td>
                <ReferenceLinks
                  items={greeting["@id"]}
                  type="greeting"
                  useIcon={true}
                />
              </td>
              <td>
                <Link href={`${greeting["@id"]}/edit`}>
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
