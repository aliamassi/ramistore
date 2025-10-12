export default defineNuxtRouteMiddleware(async (to) => {
    // skip guest routes
    if (to.meta.guest === true || to.path === '/login') return

    const api =
        (useRuntimeConfig().public?.laravelSanctum?.apiUrl as string | undefined)?.replace(/\/$/, '') ||
        'http://localhost:8000'

    const headers: Record<string, string> = {
        Accept: 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
    }
    if (process.server) {
        const h = useRequestHeaders(['cookie'])
        if (h?.cookie) headers.cookie = String(h.cookie)
    }

    try {
        await $fetch(`${api}/panel/user`, { credentials: 'include', headers })
    } catch {
        return navigateTo('/login?redirect=' + encodeURIComponent(to.fullPath), { replace: true })
    }
})
