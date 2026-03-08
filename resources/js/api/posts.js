import axios from "./axios";

export const updatePost = (id, data) => {
    return axios.put(`/posts/${id}`, data);
};

export const deletePost = (id) => {
    return axios.delete(`/posts/${id}`);
};
