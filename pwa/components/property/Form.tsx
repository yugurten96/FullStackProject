import { FunctionComponent, useState } from "react";
import Link from "next/link";
import { useRouter } from "next/router";
import { ErrorMessage, Formik } from "formik";
import { fetch } from "../../utils/dataAccess";
import { Property } from "../../types/Property";

interface Props {
  property?: Property;
}

export const Form: FunctionComponent<Props> = ({ property }) => {
  const [error, setError] = useState(null);
  const router = useRouter();

  const handleDelete = async () => {
    if (!window.confirm("Are you sure you want to delete this item?")) return;

    try {
      await fetch(property["@id"], { method: "DELETE" });
      router.push("/properties");
    } catch (error) {
      setError(`Error when deleting the resource: ${error}`);
      console.error(error);
    }
  };

  return (
    <div>
      <h1>
        {property ? `Edit Property ${property["@id"]}` : `Create Property`}
      </h1>
      <Formik
        initialValues={property ? { ...property } : new Property()}
        validate={(values) => {
          const errors = {};
          // add your validation logic here
          return errors;
        }}
        onSubmit={async (values, { setSubmitting, setStatus, setErrors }) => {
          const isCreation = !values["@id"];
          try {
            await fetch(isCreation ? "/properties" : values["@id"], {
              method: isCreation ? "POST" : "PUT",
              body: JSON.stringify(values),
            });
            setStatus({
              isValid: true,
              msg: `Element ${isCreation ? "created" : "updated"}.`,
            });
            router.push("/properties");
          } catch (error) {
            setStatus({
              isValid: false,
              msg: `${error.defaultErrorMsg}`,
            });
            setErrors(error.fields);
          }
          setSubmitting(false);
        }}
      >
        {({
          values,
          status,
          errors,
          touched,
          handleChange,
          handleBlur,
          handleSubmit,
          isSubmitting,
        }) => (
          <form onSubmit={handleSubmit}>
            <div className="form-group">
              <label className="form-control-label" htmlFor="_region">
                region
              </label>
              <input
                name="region"
                id="_region"
                value={values.region ?? ""}
                type="text"
                placeholder=""
                className={`form-control${
                  errors.region && touched.region ? " is-invalid" : ""
                }`}
                aria-invalid={errors.region && touched.region}
                onChange={handleChange}
                onBlur={handleBlur}
              />
            </div>
            <ErrorMessage
              className="text-danger"
              component="div"
              name="region"
            />
            <div className="form-group">
              <label className="form-control-label" htmlFor="_surface">
                surface
              </label>
              <input
                name="surface"
                id="_surface"
                value={values.surface ?? ""}
                type="text"
                placeholder=""
                className={`form-control${
                  errors.surface && touched.surface ? " is-invalid" : ""
                }`}
                aria-invalid={errors.surface && touched.surface}
                onChange={handleChange}
                onBlur={handleBlur}
              />
            </div>
            <ErrorMessage
              className="text-danger"
              component="div"
              name="surface"
            />
            <div className="form-group">
              <label className="form-control-label" htmlFor="_price">
                price
              </label>
              <input
                name="price"
                id="_price"
                value={values.price ?? ""}
                type="text"
                placeholder=""
                className={`form-control${
                  errors.price && touched.price ? " is-invalid" : ""
                }`}
                aria-invalid={errors.price && touched.price}
                onChange={handleChange}
                onBlur={handleBlur}
              />
            </div>
            <ErrorMessage
              className="text-danger"
              component="div"
              name="price"
            />
            <div className="form-group">
              <label className="form-control-label" htmlFor="_day">
                day
              </label>
              <input
                name="day"
                id="_day"
                value={values.day ?? ""}
                type="text"
                placeholder=""
                className={`form-control${
                  errors.day && touched.day ? " is-invalid" : ""
                }`}
                aria-invalid={errors.day && touched.day}
                onChange={handleChange}
                onBlur={handleBlur}
              />
            </div>
            <ErrorMessage className="text-danger" component="div" name="day" />
            <div className="form-group">
              <label className="form-control-label" htmlFor="_month">
                month
              </label>
              <input
                name="month"
                id="_month"
                value={values.month ?? ""}
                type="text"
                placeholder=""
                className={`form-control${
                  errors.month && touched.month ? " is-invalid" : ""
                }`}
                aria-invalid={errors.month && touched.month}
                onChange={handleChange}
                onBlur={handleBlur}
              />
            </div>
            <ErrorMessage
              className="text-danger"
              component="div"
              name="month"
            />
            <div className="form-group">
              <label className="form-control-label" htmlFor="_year">
                year
              </label>
              <input
                name="year"
                id="_year"
                value={values.year ?? ""}
                type="text"
                placeholder=""
                className={`form-control${
                  errors.year && touched.year ? " is-invalid" : ""
                }`}
                aria-invalid={errors.year && touched.year}
                onChange={handleChange}
                onBlur={handleBlur}
              />
            </div>
            <ErrorMessage className="text-danger" component="div" name="year" />
            <div className="form-group">
              <label className="form-control-label" htmlFor="_count">
                count
              </label>
              <input
                name="count"
                id="_count"
                value={values.count ?? ""}
                type="text"
                placeholder=""
                className={`form-control${
                  errors.count && touched.count ? " is-invalid" : ""
                }`}
                aria-invalid={errors.count && touched.count}
                onChange={handleChange}
                onBlur={handleBlur}
              />
            </div>
            <ErrorMessage
              className="text-danger"
              component="div"
              name="count"
            />
            <div className="form-group">
              <label className="form-control-label" htmlFor="_date">
                date
              </label>
              <input
                name="date"
                id="_date"
                value={values.date ?? ""}
                type="text"
                placeholder=""
                className={`form-control${
                  errors.date && touched.date ? " is-invalid" : ""
                }`}
                aria-invalid={errors.date && touched.date}
                onChange={handleChange}
                onBlur={handleBlur}
              />
            </div>
            <ErrorMessage className="text-danger" component="div" name="date" />

            {status && status.msg && (
              <div
                className={`alert ${
                  status.isValid ? "alert-success" : "alert-danger"
                }`}
                role="alert"
              >
                {status.msg}
              </div>
            )}

            <button
              type="submit"
              className="btn btn-success"
              disabled={isSubmitting}
            >
              Submit
            </button>
          </form>
        )}
      </Formik>
      <Link href="/properties">
        <a className="btn btn-primary">Back to list</a>
      </Link>
      {property && (
        <button className="btn btn-danger" onClick={handleDelete}>
          <a>Delete</a>
        </button>
      )}
    </div>
  );
};
