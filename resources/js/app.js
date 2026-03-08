import "./bootstrap";
import "../css/app.css";
import { createApp } from "vue";
import App from "./App.vue";
import router from "./router";
import axios, { setAuthToken } from "./api/axios";

const token = localStorage.getItem("token");
if (token) {
    setAuthToken(token);
}
createApp(App).use(router).mount("#app");
