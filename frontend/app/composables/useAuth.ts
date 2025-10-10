import axios from "axios"

export function useAuth() {
  const { $api } = useNuxtApp()
  const user = ref(null)

  const login = async (
    type: "user" | "owner" | "admin",
    email: string,
    password: string
  ) => {
    try {
      console.log("Getting CSRF cookie...")
      await axios.get("http://localhost/sanctum/csrf-cookie", {
        withCredentials: true,
      })
      console.log("CSRF cookie obtained. Logging in...")

      const xsrf = useCookie("XSRF-TOKEN").value
      const res = await $api.post(
        `/${type}/login`,
        { email, password },
        {
          headers: {
            "X-XSRF-TOKEN": xsrf,
          },
        }
      )

      localStorage.setItem(`${type}_token`, res.data.token)
      user.value = res.data
      console.log(`${type} login success`, res.data)
      return true
    } catch (error: any) {
      console.error("Login failed:", error.response?.data || error)
      return false
    }
  }

  return { user, login }
}
