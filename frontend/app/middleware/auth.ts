export default defineNuxtRouteMiddleware((to, from) => {
  const isServer = process.server
  const isClient = process.client

  let token: string | null = null
  let type: string | null = null

  if (isClient) {
    type = localStorage.getItem("login_type")
    token = type ? localStorage.getItem(`${type}_token`) : null
    console.log("🔍 [auth.global] login_type:", type)
    console.log("🔍 [auth.global] token:", token)
  } else if (isServer) {
    const event = useRequestEvent()
    const cookieHeader = event?.req?.headers?.cookie || ""
    const matchType = cookieHeader.match(/login_type=([^;]+)/)
    if (matchType) type = matchType[1] ?? null
  }

  if (to.path.startsWith("/admin")) {
    if (type !== "admin") {
      console.warn("非管理者による管理画面アクセスをブロック")
      return navigateTo("/dashboard", { replace: true })
    }
  }

  if (!token && to.path !== "/login") {
    console.warn("未認証アクセス。ログインへリダイレクト")
    return navigateTo("/login", { replace: true })
  }

  if (token && to.path === "/login") {
    console.log("ログイン済みのためダッシュボードへ")
    return navigateTo("/dashboard", { replace: true })
  }
})
