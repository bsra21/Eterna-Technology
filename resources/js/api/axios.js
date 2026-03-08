import axios from "axios";

const instance = axios.create({
    baseURL: "http://127.0.0.1:8000/api",
    headers: {
        Accept: "application/json",
    },
});
instance.interceptors.request.use((config) => {
    const token = localStorage.getItem("token");

    if (token) {
        config.headers.Authorization = `Bearer ${token}`;
    }

    config.headers.Accept = "application/json";

    config.withCredentials = true;

    return config;
});
instance.interceptors.response.use(
    (res) => res,
    (err) => {
        if (err.response?.status === 401) {
            localStorage.removeItem("token");
            window.location.href = "/";
        }

        return Promise.reject(err);
    },
);

export const setAuthToken = (token) => {
    if (token) {
        instance.defaults.headers.common["Authorization"] = `Bearer ${token}`;
    } else {
        delete instance.defaults.headers.common["Authorization"];
    }
};

export default instance;
