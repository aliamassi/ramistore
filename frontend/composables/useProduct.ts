// composables/useProduct.ts
import { ref, computed, inject } from 'vue'
import { useSanctumFetch } from '#imports'

type Id = number | string
type AnyObj = Record<string, any>

export const useProduct = () => {
    const setAlert = inject<(msg: string, type?: 'success' | 'error' | 'info') => void>('setAlert')

    // Sanctum-aware fetch (auto baseUrl, credentials, X-XSRF-TOKEN)
    const $sf = useSanctumFetch<AnyObj>

    // ---------------- State
    const products = ref<AnyObj[]>([])
    const productImages = ref<AnyObj[]>([])
    const categories = ref<AnyObj[]>([])
    const loading = ref(false)
    const error = ref<string | null>(null)

    // ---------------- Helpers
    const normList = (res: any): AnyObj[] =>
        Array.isArray(res?.data) ? res.data
            : Array.isArray(res?.categories?.data) ? res.categories.data
                : Array.isArray(res?.categories) ? res.categories
                    : Array.isArray(res) ? res
                        : []

    const normItem = (res: any): AnyObj =>
        (res?.data && typeof res.data === 'object') ? res.data
            : (res?.product && typeof res.product === 'object') ? res.product
                : (typeof res === 'object' ? res : {})

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

    // ---------------- Computed
    const productCount = computed(() => products.value.length)
    const categoryCount = computed(() => categories.value.length)

    const getProductById = computed(() => (id: Id) =>
        products.value.find(p => p?.id === id))

    const getProductsByCategory = computed(() => (categoryId: Id) =>
        products.value.filter(p => p?.categoryId === categoryId))

    const getProductByCategoryAndId = computed(() => (categoryId: Id, productId: Id) => {
        const cat = categories.value.find(c => c?.id === categoryId)
        return cat?.products?.find((p: any) => p?.id === productId) ?? null
    })

    // ---------------- Methods (all via $sf)

    const fetchProducts = async () => {
        loading.value = true; error.value = null
        try {
            const res = await $sf('/panel/product')
            products.value = normList(res)
        } catch (e) { onErr(e, 'Failed to fetch products') }
        finally { loading.value = false }
    }
   const fetchProductImages = async (id:Id) => {
        loading.value = true; error.value = null
        try {
            const res = await $sf(`/panel/product/${id}/images`)
            productImages.value = res.data.images
        } catch (e) { onErr(e, 'Failed to fetch product images') }
        finally { loading.value = false }
    }

    const fetchCategories = async () => {
        loading.value = true; error.value = null
        try {
            const res = await $sf('/panel/category')
            console.log('%%%%%%%%%%%%%%',res);
            if(res.message ==="Unauthenticated"){
                const route = useRoute()
                navigateTo(`/login?redirect=${encodeURIComponent(route.fullPath)}`, { replace: true })
            }
            categories.value = normList(res)
        } catch (e) {
            onErr(e, 'Failed to fetch categories')
            console.log(e,e.status);
            if (e?.status === 401) {
                const route = useRoute()
                navigateTo(`/login?redirect=${encodeURIComponent(route.fullPath)}`, { replace: true })
            }
        }
        finally { loading.value = false }
    }

    const addProduct = async (productData: AnyObj | FormData) => {
        loading.value = true; error.value = null
        try {
            const res = await $sf('/panel/product', {
                method: 'POST',
                // IMPORTANT: do not set Content-Type for FormData
                body: productData as any,
            })
            // const newProduct = normItem(res)

            // also reflect in categories list if present
            const catIdx = categories.value.findIndex(c => c?.id === (productData.category_id ?? (productData as any)?.category_id))
            if (catIdx !== -1) {
                const cat = categories.value[catIdx]
                cat.products = Array.isArray(cat.products) ? cat.products : []
                cat.products.push(productData)
            }

            setAlert?.('Product added successfully!', 'success')
            return productData
        } catch (e) { onErr(e, 'Failed to add product') }
        finally { loading.value = false }
    }

    const addCategory = async (categoryData: AnyObj | FormData) => {
        loading.value = true; error.value = null
        try {
            const res = await $sf('/panel/category', {
                method: 'POST',
                body: categoryData as any,
            })
            // const newCategory = normItem(res)
            categories.value.push(categoryData)
            setAlert?.('Category added successfully!', 'success')
            return categoryData
        } catch (e) { onErr(e, 'Failed to add category') }
        finally { loading.value = false }
    }

    const updateProduct = async (id: Id, updatedData: AnyObj | FormData) => {
        loading.value = true; error.value = null
        try {
            const res = await $sf(`/panel/product/${id}`, {
                method: 'PUT',
                body: updatedData as any,
            })
            const updated = normItem(res)

            // update in categories if we track nested products
            const catId = (updated as any)?.category_id ?? (updatedData as any)?.category_id
            const catIdx = categories.value.findIndex(c => c?.id === catId)
            if (catIdx !== -1) {
                const list = categories.value[catIdx].products = Array.isArray(categories.value[catIdx].products) ? categories.value[catIdx].products : []
                const pIdx = list.findIndex((p: any) => p?.id === id)
                if (pIdx !== -1) list.splice(pIdx, 1, { ...list[pIdx], ...updated })
            }

            // also update in flat products list if present
            const pIdxFlat = products.value.findIndex(p => p?.id === id)
            if (pIdxFlat !== -1) products.value.splice(pIdxFlat, 1, { ...products.value[pIdxFlat], ...updated })

            setAlert?.('Product updated successfully!', 'success')
            return updated
        } catch (e) { onErr(e, 'Failed to update product') }
        finally { loading.value = false }
    }

    // --- helpers ---
    const toFormData = (obj: AnyObj = {}, files?: File[] | FileList | null, key = 'images[]') => {
        const fd = new FormData()
        Object.entries(obj).forEach(([k, v]) => {
            if (v !== undefined && v !== null) fd.append(k, String(v))
        })
        if (files && (files as any).length) {
            ;[...Array.from(files as any as FileList)].forEach(f => fd.append(key, f))
        }
        return fd
    }
    const uploadProductImages = async (id: Id, files: File[] | FileList) => {
        loading.value = true; error.value = null
        try {
            // Try a dedicated images endpoint first:
            const res = await $sf(`/panel/product/${id}/images`, {
                method: 'POST',
                body: toFormData({}, files),
            })

            const updated = normItem(res)

            // reflect in flat list
            const pIdxFlat = products.value.findIndex(p => p?.id === id)
            if (pIdxFlat !== -1) products.value.splice(pIdxFlat, 1, { ...products.value[pIdxFlat], ...updated })

            // reflect in categories list if you keep nested products
            const catId = (updated as any)?.category_id
            const cIdx = categories.value.findIndex(c => c?.id === catId)
            if (cIdx !== -1 && Array.isArray(categories.value[cIdx].products)) {
                const list = categories.value[cIdx].products!
                const lp = list.findIndex((p: any) => p?.id === id)
                if (lp !== -1) list.splice(lp, 1, { ...list[lp], ...updated })
            }

            setAlert?.('Images uploaded!', 'success')
            return updated
        } catch (e: any) {
            // Fallback: some backends accept PUT on /panel/product/:id with images[] in body
            if (e?.status === 404 || e?.status === 405) {
                try {
                    const res2 = await $sf(`/panel/product/${id}`, {
                        method: 'POST', // Laravel-friendly override
                        body: (() => {
                            const fd = toFormData({}, files)
                            fd.append('_method', 'PUT')
                            return fd
                        })()
                    })
                    const updated = normItem(res2)
                    setAlert?.('Images uploaded!', 'success')
                    return updated
                } catch (e2) { onErr(e2, 'Failed to upload images') }
            } else {
                onErr(e, 'Failed to upload images')
            }
        } finally { loading.value = false }
    }

    const updateCategory = async (id: Id, name: string) => {
        loading.value = true; error.value = null
        try {
            await $sf(`/panel/category/${id}`, {
                method: 'PUT',
                body: { category_id: id, name:name },
            })
            const idx = categories.value.findIndex(c => c?.id === id)
            if (idx !== -1) categories.value[idx] = { ...categories.value[idx], name }
            setAlert?.('Category updated successfully!', 'success')
        } catch (e) { onErr(e, 'Failed to update category') }
        finally { loading.value = false }
    }
 const changeProductVisibility = async (id: Id, action: string) => {
        loading.value = true; error.value = null
        try {
           const res = await $sf(`/panel/product/${id}/visibility`, {
                method: 'PUT',
                body: { id: id, action:action },
            })
            const idx = categories.value.findIndex(c => c?.id === res.category.id)
            if (idx !== -1) categories.value[idx] = res.category
            setAlert?.('Category updated successfully!', 'success')
        } catch (e) { onErr(e, 'Failed to update category') }
        finally { loading.value = false }
    }

    const deleteProduct = async (id: Id) => {
        loading.value = true; error.value = null
        try {
            await $sf(`/panel/product/${id}`, { method: 'DELETE' })
            const idx = products.value.findIndex(p => p?.id === id)
            if (idx !== -1) {
                const deleted = products.value.splice(idx, 1)[0]
                setAlert?.('Product deleted successfully!', 'success')
                return deleted
            } else {
                throw new Error('Product not found')
            }
        } catch (e) { onErr(e, 'Failed to delete product') }
        finally { loading.value = false }
    }

    const deleteCategory = async (id: Id) => {
        loading.value = true; error.value = null
        try {
            await $sf(`/panel/category/${id}`, { method: 'DELETE' })
            const idx = categories.value.findIndex(c => c?.id === id)
            if (idx !== -1) {
                const deleted = categories.value.splice(idx, 1)[0]
                setAlert?.(`Category ${deleted?.name ?? ''} deleted successfully!`, 'success')
                return deleted
            }
        } catch (e) { onErr(e, 'Failed to delete category') }
        finally { loading.value = false }
    }

    const duplicateProduct = async (id: Id) => {
        // purely client-side duplication (no API)
        const source = products.value.find(p => p?.id === id)
        if (!source) throw new Error('Product not found')

        const copy = {
            ...source,
            id: crypto?.randomUUID ? crypto.randomUUID() : `${Date.now()}-${Math.random()}`,
            name: `${source.name ?? 'Product'} (Copy)`,
            sku: source.sku ? `${source.sku}-copy` : undefined,
        }
        products.value.push(copy)
        setAlert?.('Product duplicated successfully!', 'success')
        return copy
    }

    const reorderProducts = (oldIndex: number, newIndex: number) => {
        const moved = products.value.splice(oldIndex, 1)[0]
        products.value.splice(newIndex, 0, moved)
    }
    const updateProductWithImages = async (id: Id, updated: AnyObj, files?: File[] | FileList | null) => {
        const body = files && (files as any).length ? toFormData(updated, files) : updated
        return await updateProduct(id, body as any)
    }
    const addProductWithImages = async (productData: AnyObj, files?: File[] | FileList | null) => {
        const body = files && (files as any).length ? toFormData(productData, files) : productData
        return await addProduct(body as any) // uses your existing addProduct
    }
    return {
        // State
        products, categories, loading, error,
        // Computed
        productImages, categoryCount, productCount, getProductById, getProductsByCategory, getProductByCategoryAndId,setAlert,
        // Methods
        fetchProducts, fetchCategories,
        addCategory, addProduct,
        updateProduct, updateCategory,
        deleteProduct, deleteCategory,
        duplicateProduct, reorderProducts,
        uploadProductImages,updateProductWithImages,fetchProductImages,changeProductVisibility

    }
}
