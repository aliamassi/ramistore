// composables/useAuth.ts
import {useSanctumFetch} from "#imports";
import {inject, ref} from "vue";

export const useAuth = () => {
    const setAlert = inject<(msg: string, type?: 'success' | 'error' | 'info') => void>('setAlert')
    const config = useRuntimeConfig()
    const user  = useState<any>('auth:user', () => null)
    const $sf = useSanctumFetch<AnyObj>
    const loading = ref(false)
    const error = ref<string | null>(null)


    const register = async (payload: {
        name: string
        email: string
        password: string
        password_confirmation: string
    }) => {
        loading.value = true; error.value = null
        try {
            const res = await $sf('/panel/register', {
                method: 'POST',
                body:payload,
            })
            setAlert?.('User added successfully!', 'success')
            // const me = await fetchUser()      // your existing sanctum user fetch
            user.value = res.user
            return res.user
        } catch (e) {
            onErr(e, 'Failed to add user')
        }
        finally { loading.value = false }
    }

    const onErr = (e: any, fallback = 'Request failed') => {
        const msg =
            e?.data?.message ||
            e?.message ||
            (typeof e === 'string' ? e : null) ||
            fallback
        error.value = msg
        setAlert?.(msg, 'error')
        // rethrow if caller cares
        throw e
    }
    return { register, user }
}
