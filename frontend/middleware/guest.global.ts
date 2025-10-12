// middleware/guest.global.ts
export default defineNuxtRouteMiddleware(async (to) => {
    // only run on guest pages
    const isGuestPage =
        to.meta.guest === true ||
        ['/login', '/register', '/forgot-password'].includes(to.path)
    if (!isGuestPage) return

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
        await $fetch(`${api}/panel/user`, {
            method: 'GET',
            credentials: 'include',
            headers,
        })
        // user is already logged in → send them somewhere useful
        const toAfter = (to.query.redirect as string) || '/products'
        return navigateTo(toAfter, { replace: true })
    } catch {
        // not logged in → stay on guest page
        return
    }
})
