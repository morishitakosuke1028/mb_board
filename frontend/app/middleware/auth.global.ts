export default defineNuxtRouteMiddleware((to, from) => {
  const isServer = process.server
  const isClient = process.client

  let token: string | null = null
  let type: string | null = null

  if (isClient) {
    type = localStorage.getItem("login_type")
    token = type ? localStorage.getItem(`${type}_token`) : null
  } else if (isServer) {
    const event = useRequestEvent()
    const cookieHeader = event?.req?.headers?.cookie || ""
    const match = cookieHeader.match(/login_type=([^;]+)/)
    if (match) type = match[1] ?? null
  }

  // 未認証 → loginへ
  if (!token && to.path !== "/login") {
    return navigateTo("/login", { replace: true })
  }

  // ログイン済みで loginページ → dashboardへ
  if (token && to.path === "/login") {
    return navigateTo("/dashboard", { replace: true })
  }
})
