import axios from "axios";

const API = `${process.env.REACT_APP_BACKEND_URL}/api`;

export const api = {
  products: (params) => axios.get(`${API}/products`, { params }).then((r) => r.data),
  product: (id) => axios.get(`${API}/products/${id}`).then((r) => r.data),
  createOrder: (payload) => axios.post(`${API}/orders`, payload).then((r) => r.data),
  contact: (payload) => axios.post(`${API}/contact`, payload).then((r) => r.data),
  newsletter: (email) => axios.post(`${API}/newsletter`, { email }).then((r) => r.data),
};
