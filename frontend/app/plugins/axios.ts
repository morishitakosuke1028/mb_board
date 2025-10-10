import axios from "axios"

export default defineNuxtPlugin(() => {
  const api = axios.create({
    baseURL: "http://localhost/api",
    withCredentials: true,
    headers: {
      "Content-Type": "application/json",
      "Accept": "application/json",
    },
  })
  return { provide: { api } }
})
