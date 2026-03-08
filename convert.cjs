const postmanToOpenApi = require("postman-to-openapi");

const input = "./Eterna.postman_collection.json";
const output = "./openapi.yaml";

postmanToOpenApi(input, output, { defaultTag: "General" })
    .then(() => console.log("OpenAPI file created: openapi.yaml"))
    .catch((err) => console.error(err));
