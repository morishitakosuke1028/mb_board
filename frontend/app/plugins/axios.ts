import axios from "axios"

export default defineNuxtPlugin(() => {
  const api = axios.create({
    baseURL: "http://localhost/api",
    headers: {
      "Content-Type": "application/json",
      "Accept": "application/json",
    },
    timeout: 10000,
  })

  api.interceptors.request.use((config) => {
    const type = process ? localStorage.getItem("login_type") : null
    const token = type ? localStorage.getItem(`${type}_token`) : null

    if (token) {
      config.headers.Authorization = `Bearer ${token}`
    }

    return config
  })

  api.interceptors.response.use(
    (response) => response,
    (error) => {
      if (process.client && error.response?.status === 401) {
        localStorage.removeItem("login_type")
        window.location.href = "/login"
      }
      return Promise.reject(error)
    }
  )

  return { provide: { api } }
})
