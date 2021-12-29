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
              <label className="form-control-label" htmlFor="_sellDay">
                sellDay
              </label>
              <input
                name="sellDay"
                id="_sellDay"
                value={values.sellDay ?? ""}
                type="text"
                placeholder=""
                className={`form-control${
                  errors.sellDay && touched.sellDay ? " is-invalid" : ""
                }`}
                aria-invalid={errors.sellDay && touched.sellDay}
                onChange={handleChange}
                onBlur={handleBlur}
              />
            </div>
            <ErrorMessage
              className="text-danger"
              component="div"
              name="sellDay"
            />
            <div className="form-group">
              <label className="form-control-label" htmlFor="_sellMonth">
                sellMonth
              </label>
              <input
                name="sellMonth"
                id="_sellMonth"
                value={values.sellMonth ?? ""}
                type="text"
                placeholder=""
                className={`form-control${
                  errors.sellMonth && touched.sellMonth ? " is-invalid" : ""
                }`}
                aria-invalid={errors.sellMonth && touched.sellMonth}
                onChange={handleChange}
                onBlur={handleBlur}
              />
            </div>
            <ErrorMessage
              className="text-danger"
              component="div"
              name="sellMonth"
            />
            <div className="form-group">
              <label className="form-control-label" htmlFor="_sellYear">
                sellYear
              </label>
              <input
                name="sellYear"
                id="_sellYear"
                value={values.sellYear ?? ""}
                type="text"
                placeholder=""
                className={`form-control${
                  errors.sellYear && touched.sellYear ? " is-invalid" : ""
                }`}
                aria-invalid={errors.sellYear && touched.sellYear}
                onChange={handleChange}
                onBlur={handleBlur}
              />
            </div>
            <ErrorMessage
              className="text-danger"
              component="div"
              name="sellYear"
            />
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
              <label className="form-control-label" htmlFor="_sellDate">
                sellDate
              </label>
              <input
                name="sellDate"
                id="_sellDate"
                value={values.sellDate ?? ""}
                type="text"
                placeholder=""
                className={`form-control${
                  errors.sellDate && touched.sellDate ? " is-invalid" : ""
                }`}
                aria-invalid={errors.sellDate && touched.sellDate}
                onChange={handleChange}
                onBlur={handleBlur}
              />
            </div>
            <ErrorMessage
              className="text-danger"
              component="div"
              name="sellDate"
            />

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
