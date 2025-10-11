export default defineNuxtRouteMiddleware((to, from) => {
  if (process) return

  const loginType = window.localStorage.getItem("login_type")
  const token = loginType
    ? window.localStorage.getItem(`${loginType}_token`)
    : null

  const publicPages = ["/login", "/"]

  if (!token && !publicPages.includes(to.path)) {
    return navigateTo("/login")
  }
})
