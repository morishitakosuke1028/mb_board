import axios from "axios"

export function useAuth() {
  const { $api } = useNuxtApp()
  const user = ref<any>(null)

  // ログイン
  const login = async (
    type: "user" | "owner" | "admin",
    email: string,
    password: string
  ) => {
    try {
      const res = await $api.post(`/${type}/login`, { email, password })
      const token = res.data.access_token

      localStorage.setItem(`${type}_token`, token)
      localStorage.setItem("login_type", type)

      $api.defaults.headers.common["Authorization"] = `Bearer ${token}`

      user.value = res.data
      console.log(`${type} login success`, res.data)
      return true
    } catch (error: any) {
      console.error("Login failed:", error.response?.data || error)
      return false
    }
  }

  // ログイン中ユーザーを取得
  const fetchUser = async () => {
    try {
      const type = localStorage.getItem("login_type")
      const token = localStorage.getItem(`${type}_token`)
      if (!type || !token) return null

      const res = await $api.get(`/${type}/me`, {
        headers: { Authorization: `Bearer ${token}` },
      })
      user.value = res.data
      return res.data
    } catch (error) {
      console.error("Failed to fetch user:", error)
      user.value = null
      return null
    }
  }

  // ログアウト
  const logout = async () => {
    try {
      const type = localStorage.getItem("login_type")
      const token = localStorage.getItem(`${type}_token`)
      if (!type || !token) return

      await $api.post(
        `/${type}/logout`,
        {},
        { headers: { Authorization: `Bearer ${token}` } }
      )

      localStorage.removeItem(`${type}_token`)
      localStorage.removeItem("login_type")
      delete $api.defaults.headers.common["Authorization"]
      user.value = null
      console.log(`${type} logout success`)
    } catch (error) {
      console.error("Logout failed:", error)
    }
  }

  return { user, login, fetchUser, logout }
}
