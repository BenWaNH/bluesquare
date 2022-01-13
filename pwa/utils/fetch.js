import isObject from "lodash/isObject";
import { ENTRYPOINT } from "../config/entrypoint";
import SubmissionError from "../error/SubmissionError";
import { normalize } from "./hydra";

const MIME_TYPE = "application/ld+json";

const makeParamArray = (key, arr) =>
  arr.map((val) => `${key}[]=${val}`).join("&");

export function getCookie (name) {
  const value = `; ${document.cookie}`
  const parts = value.split(`; ${name}=`)
  if (parts.length === 2) { return parts.pop().split(';').shift() }
}

export function getToken () {
  if (process.client) {
    return getCookie('auth._token.local') ? decodeURIComponent(getCookie('auth._token.local')) : null
  }
}

export default function (id, options = {}) {
  if(process.client) {
    if ("undefined" === typeof options.headers) {
      options.headers = {};
    }

    if (!options.headers.hasOwnProperty("Accept")) {
      options.headers = {...options.headers, Accept: MIME_TYPE};
    }

    if (
      undefined !== options.body &&
      !(options.body instanceof FormData) &&
      !options.headers.hasOwnProperty("Content-Type")
    ) {
      options.headers = {...options.headers, "Content-Type": MIME_TYPE};
    }

    const token = getToken()

    if (token) {
      // options.headers.set('Authorization', token)
      options.headers = {...options.headers, "Authorization": token};
    } else {
      options.headers.delete('Authorization')
    }

    if (options.params) {
      const params = normalize(options.params);
      let queryString = Object.keys(params)
        .map((key) =>
          Array.isArray(params[key])
            ? makeParamArray(key, params[key])
            : `${key}=${params[key]}`
        )
        .join("&");
      id = `${id}?${queryString}`;
    }

    let entryPoint
    if (process.env.NODE_ENV !== 'production') {
      entryPoint = ENTRYPOINT + (ENTRYPOINT.endsWith("/") ? "" : "/");
    } else {
      const host = window.location.host
      const hostParts = host.split('.')
      if(hostParts[0] === 'admin-recette') {
        entryPoint = 'https://recette.qualifop.com'
      }
      if(hostParts[0] === 'admin-production') {
        entryPoint = 'https://qualifop.com'
      }
    }

    const payload = options.body && JSON.parse(options.body);
    if (isObject(payload) && payload["@id"])
      options.body = JSON.stringify(normalize(payload));

    return global.fetch(new URL(id, entryPoint), options).then((response) => {
      if (response.ok) return response;

      return response.json().then(
        (json) => {
          const error =
            json["hydra:description"] ||
            json["hydra:title"] ||
            "An error occurred.";

          if (!json.violations) throw Error(error);

          let errors = {_error: error};
          json.violations.forEach((violation) =>
            errors[violation.propertyPath]
              ? (errors[violation.propertyPath] +=
                "\n" + errors[violation.propertyPath])
              : (errors[violation.propertyPath] = violation.message)
          );

          throw new SubmissionError(errors);
        },
        () => {
          throw new Error(response.statusText || "An error occurred.");
        }
      );
    });
  }
}
