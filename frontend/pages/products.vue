<script setup lang="ts">
// definePageMeta({ middleware: ['require-auth'] })
// definePageMeta({ middleware: ['require-auth'] })
import {ref, computed, watch, onMounted, reactive, inject} from 'vue'
import {useProduct} from '~/composables/useProduct'
import ProductEditDrawer from '~/components/ProductEditDrawer.vue'
import DeleteConfirmDialog from '~/components/DeleteConfirmDialog.vue'
import {useSanctumFetch} from "#imports";

const selected = ref('favorites')
const $sf = useSanctumFetch<AnyObj>
import ImageUploadDialog from '@/components/ImageUploadDialog.vue'
import ProductImageUploader from "@/components/ProductImageUploader.vue";

const setAlert = inject<(msg: string, type?: 'success' | 'error' | 'info') => void>('setAlert')
const setting = reactive({
  currency: 'JOD',
  business_name: 'your business name',
})
const uploaderRef = ref<InstanceType<typeof ImageUploadDialog> | null>(null)

const logoPreview = ref<string>('')     // preview URL
const logoFile = ref<File | null>(null)


const copying = ref(false)
const copied = ref(false)

async function copyDomain(domain) {
  const text = domain
  console.log('domain', text);
  if (!text) return

  copying.value = true
  try {
    // Prefer modern API (works on https or localhost)
    await navigator.clipboard.writeText(text)
  } catch {
    // Fallback for http origins
    const ta = document.createElement('textarea')
    ta.value = text
    ta.style.position = 'fixed'
    ta.style.opacity = '0'
    document.body.appendChild(ta)
    ta.select()
    document.execCommand('copy')
    document.body.removeChild(ta)
  } finally {
    copying.value = false
    copied.value = true
    setTimeout(() => (copied.value = false), 1200)
  }
}

function openImagePicker() {
  uploaderRef.value?.pick()
}

function onImageSelected(p: {
  file: File,
  url: string,
  width: number,
  height: number,
  type: string,
  fileName: string,
  size: number
}) {
  logoPreview.value = p.url
  logoFile.value = p.file
}

// Your existing upload function
async function uploadLogo(file?: File) {
  const toSend = file ?? logoFile.value
  if (!toSend) return
  const fd = new FormData()
  fd.append('logo', toSend, toSend.name || 'logo.png')
  // Example:
  // await $fetch('/api/upload', { method: 'POST', body: fd })
  const res = await $sf('/panel/admin/upload', {
    method: 'POST',
    // IMPORTANT: do not set Content-Type for FormData
    body: fd,
  })
  logoImage.value = res.url;
  uploaderRef.value?.close();
  setAlert?.('Logo updaed successfully!', 'success')


}

const cfg = useRuntimeConfig()
// Props
const props = defineProps({
  categoryId: {
    type: Number,
    default: 1
  },
  categoryName: {
    type: String,
    default: 'Category name 1'
  }
})

// Composable
const {
  products,
  loading,
  error,
  categories,
  categoryCount,
  productCount,
  getProductById,
  getProductsByCategory,
  getProductByCategoryAndId,
  fetchCategories,
  saveSetting,
  addProduct,
  addCategory,
  updateProduct,
  addProductVariant,
  deleteProduct,
  deleteVariant,
  deleteCategory,
  updateCategory,
  fetchProductImages,
  changeProductVisibility,
  changeCategoryVisibility,
  fetchSettings,
  updateProductVariant,
  updateProductType,
  settings,
  duplicateProduct
} = useProduct()
const {user} = useSanctum()

// Local state
const expanded = ref(true)
const expandedCategories = ref(new Set()) // Track which categories are expanded
const showError = ref(false)
const showDrawer = ref(false)
const selectedProduct = ref(null)


const bannerImage = ref('https://images.unsplash.com/photo-1563805042-7684c019e1cb?w=1200')
const logoImage = ref() // Set to empty for placeholder
const isFavorite = ref(false)
const isActive = ref(true)

// Watch error to show snackbar
watch(error, (newError) => {
  if (newError) {
    showError.value = true
  }
})

onMounted(async () => {
  await fetchCategories()
    await fetchSettings()
  setting.currency = settings.value.currency.value
  setting.business_name = settings.value.business_name.value
  console.log(categoryCount.value)
})
// Methods


const toggleFavorite = () => {
  isFavorite.value = !isFavorite.value
}

const handleSettings = () => {
  console.log('Settings clicked')
}

const handlePreferences = () => {
  console.log('Preferences clicked')
}

// Toggle category expansion
const toggleCategory = (categoryId) => {
  if (expandedCategories.value.has(categoryId)) {
    expandedCategories.value.delete(categoryId)
  } else {
    expandedCategories.value.add(categoryId)
  }
}

// Check if category is expanded
const isCategoryExpanded = (categoryId) => {
  return expandedCategories.value.has(categoryId)
}
const openProductDrawer = (categoryId, productId) => {
  const product = getProductByCategoryAndId.value(categoryId, productId)
  fetchProductImages(productId)
  console.log('drwaerrrrr__', product)
  if (product) {
    selectedProduct.value = {...product}
    console.log('selectedProduct', selectedProduct);
    showDrawer.value = true
  }
}


// const handleImagesUpdated = (product) => {
//   // Update selectedProduct with new images
//   if (selectedProduct.value) {
//     selectedProduct.value = product
//
//   }
// }
const formatPrice = (price) => {
  return price.toFixed(2).replace('.', ',')
}

const handleAddProduct = async (categoryId) => {
  try {
    await addProduct({
      name: `Product ${productCount.value + 1}`,
      price: 0.00,
      category_id: categoryId
    })
  } catch (err) {
    console.error('Failed to add product:', err)
  }
}

const handleAddCategory = async () => {
  try {
    await addCategory({
      name: `Category ${categoryCount.value + 2}`,
    })
  } catch (err) {
    console.error('Failed to add category:', err)
  }
}

const handleEditProduct = async (id) => {
  openProductDrawer(id)
}

const handleSaveProduct = async (productData) => {
  try {
   const updateProductResponse =  await updateProduct(productData.id, productData)
    selectedProduct.value = updateProductResponse
    console.log('Product updated successfully')
  } catch (err) {
    console.error('Failed to update product:', err)
  }
}
const handleSaveProductVariant = async (productData) => {
  try {
    await updateProductVariant(productData.id, productData)
    console.log('Product variant updated successfully')
  } catch (err) {
    console.error('Failed to update product:', err)
  }
}
const handleSaveProductType = async (productData) => {
  try {
    const updateProductTypeResponse = await updateProductType(productData.id, productData)
    selectedProduct.value = updateProductTypeResponse
    console.log('Product variant updated successfully')
  } catch (err) {
    console.error('Failed to update product:', err)
  }
}
const handleSaveSetting = async (name) => {
  try {
    await saveSetting(setting.business_name)
  } catch (err) {
  }
}
const handleAddProductVariant = async (variantData) => {
  try {
    const addProductVariantResponse = await addProductVariant(variantData)
    selectedProduct.value = addProductVariantResponse
    console.log('Product variants updated successfully')
  } catch (err) {
    console.error('Failed to update product:', err)
  }
}
const handleDeleteVariant = async (variantData) => {
  try {
    const deleteVariantResponse = await deleteVariant(variantData.id)
    selectedProduct.value = deleteVariantResponse
    console.log('Product variants updated successfully')
  } catch (err) {
    console.error('Failed to update product:', err)
  }
}
const handleProductUpdated = async (productData) => {
  selectedProduct.value =  productData

}
const handleChangeProductVisibility = async (id, action) => {
  try {
    await changeProductVisibility(id, action)
    console.log('Product updated successfully')
  } catch (err) {
    console.error('Failed to update product:', err)
  }
}
const handleChangeCategoryVisibility = async (id, action) => {
  try {
    await changeCategoryVisibility(id, action)
    console.log('Category updated successfully')
  } catch (err) {
    console.error('Failed to update category:', err)
  }
}
const handleUpdateCategory = async (categoryId, categoryName) => {
  try {
    await updateCategory(categoryId, categoryName)
    console.log('Product updated successfully')
  } catch (err) {
    console.error('Failed to update product:', err)
  }
}

const handleDuplicateProduct = async (id) => {
  try {
    await duplicateProduct(id)
  } catch (err) {
    console.error('Failed to duplicate product:', err)
  }
}

const showDeleteDialog = ref(false)

const deleteDialog = ref({
  title: 'Delete Product',
  message: 'Are you sure you want to delete this product? This action cannot be undone.',
  itemId: null,
  itemType: 'product' // or 'category'
})

// Modified delete handlers
const handleDeleteProduct = (id, categoryId: any) => {
  deleteDialog.value = {
    title: 'Delete Product',
    message: 'Are you sure you want to delete this product? This action cannot be undone.',
    itemId: id,
    categoryId: categoryId,
    itemType: 'product'
  }
  showDeleteDialog.value = true
}
const showCropDialog = ref(false)
const handleAddCropImage = () => {

  showCropDialog.value = true
}

const handleDeleteCategory = (id) => {
  deleteDialog.value = {
    title: 'Delete Category',
    message: 'Are you sure you want to delete this category and all its products? This action cannot be undone.',
    itemId: id,
    itemType: 'category'
  }
  showDeleteDialog.value = true
}

const confirmDelete = async () => {
  try {
    if (deleteDialog.value.itemType === 'product') {
      console.log(deleteDialog.value.itemType, deleteDialog.value.itemId);
      await deleteProduct(deleteDialog.value.itemId, deleteDialog.value.categoryId)
      console.log('Product deleted successfully')
    } else if (deleteDialog.value.itemType === 'category') {
      await deleteCategory(deleteDialog.value.itemId)
      console.log('Delete category:', deleteDialog.value.itemId)
    }
  } catch (err) {
    console.error('Failed to delete:', err)
  }
}

const cancelDelete = () => {
  console.log('Delete cancelled')
}

// const handleDeleteProduct = async (id) => {
//   if (confirm('Are you sure you want to delete this product?')) {
//     try {
//       await deleteProduct(id)
//     } catch (err) {
//       console.error('Failed to delete product:', err)
//     }
//   }
// }

const viewProduct = (id) => {
  console.log('View product', id)
  // Navigate to product detail page or open dialog
}

const editCategory = () => {
  console.log('Edit category')
}

// const deleteCategory = () => {
//   console.log('Delete category')
// }

const duplicateCategory = () => {
  console.log('Duplicate category')
}
</script>
<template>
  <v-container fluid class="pa-0">
    <!-- Banner Image Section -->
    <v-card class="banner-card" elevation="0">
      <v-img
        :src="bannerImage"
        height="108"
        cover
        class="banner-image"
      >
        <!-- Get Domain Button -->
        <v-btn
          prepend-icon="bx bx-link"
          color="white"
          class="text-none copy-domain"
          :href="user?.domain"
          target="_blank"
        >
          Get your .com domain
        </v-btn>

        <!-- Camera Button for Banner -->
        <v-btn
          icon
          color="primary"
          class="banner-camera-btn"
          size="large"
          elevation="3"
        >
          <i class='bx bx-camera' style="font-size: 24px;"></i>
        </v-btn>

        <!-- Settings Menu -->
        <v-menu location="bottom end">
          <template v-slot:activator="{ props }">
            <v-btn
              icon
              color="white"
              class="settings-btn"
              v-bind="props"
              elevation="2"
            >
              <i class='bx bx-cog' style="font-size: 24px;"></i>
            </v-btn>
          </template>
          <v-list>
            <v-list-item @click="handleSettings">
              <v-list-item-title>Settings</v-list-item-title>
            </v-list-item>
            <v-list-item @click="handlePreferences">
              <v-list-item-title>Preferences</v-list-item-title>
            </v-list-item>
          </v-list>
        </v-menu>
      </v-img>

      <!-- Logo and Business Info Section -->
      <v-card-text class="business-info-section">
        <v-row align="end" no-gutters>
          <!-- Logo -->
          <v-col cols="auto" class="logo-wrapper">
            <v-card
              class="logo-card"
              elevation="4"
              width="100"
              height="100"
            >
              <v-img
                v-if="logoImage"
                :src="logoImage"
                cover
                height="100%"
              ></v-img>
              <v-img
                v-else-if="user?.logo"
                :src="user?.logo"
                cover
                height="100%"
              ></v-img>

              <div v-else class="d-flex align-center justify-center fill-height ">
                <i class='bx bx-store' style="font-size: 64px; color: #9e9e9e;"></i>
              </div>

              <!-- Camera Button for Logo -->
              <div class="relative">
                  <v-btn
                          @click="openImagePicker"
                          size="small"
                          color="primary"
                          class="logo-camera-btn"
                          density="compact"
                          icon="mdi-camera"
                          elevation="3"
                          height="32"
                          width="32"
                  ></v-btn>
              </div>
            </v-card>
          </v-col>

          <!-- Business Name Input -->
          <v-col class="ml-4">
            <div class="text-caption text-grey-darken-1 mb-1">Business</div>
            <div style="position: relative;">
              <v-text-field
                v-model="setting.business_name"
                variant="underlined"
                density="compact"
                class="business-name-field"
                :counter="45"
                maxlength="45"
                @blur="handleSaveSetting"
              >
              </v-text-field>
            </div>
          </v-col>

          <!-- Action Buttons -->
          <v-col cols="auto" class="ml-4 d-flex align-center">
            <!-- Search Button -->
            <v-btn
              icon
              variant="text"
              size="large"
              class="mr-2"
            >
              <i class='bx bx-search' style="font-size: 24px;"></i>
            </v-btn>

            <!-- Favorite Button -->
            <v-btn
              icon
              variant="text"
              size="large"
              class="mr-2"
              @click="toggleFavorite"
            >
              <i
                :class="isFavorite ? 'bx bxs-star' : 'bx bx-star'"
                style="font-size: 24px;"
                :style="{ color: isFavorite ? '#FFC107' : 'inherit' }"
              ></i>
            </v-btn>

            <!-- Toggle Switch -->
            <v-switch
              v-model="isActive"
              hide-details
              density="compact"
              color="primary"
              class="ml-2"
            ></v-switch>
          </v-col>
        </v-row>
      </v-card-text>
    </v-card>
  </v-container>
  <v-container fluid class="pa-4">
    <div v-if="categoryCount > 0">
      <div class="mb-4">
        <v-row>
          <v-col cols="auto">
            <v-menu>
              <template v-slot:activator="{ props }">
                <v-btn v-bind="props"
                       prepend-icon="bx-menu"
                >
                  <template v-slot:prepend>
                    <v-icon></v-icon>
                  </template>
                  Category
                </v-btn>
              </template>
              <v-list>
                <v-list-item @click="handleAddCategory">
                  <template v-slot:prepend>
                    <i class='bx bx-plus' style="font-size: 20px; margin-right: 12px;"></i>
                  </template>
                  <v-list-item-title>Add Category</v-list-item-title>
                </v-list-item>
                <v-list-item @click="handleDeleteCategory(categoryId)">
                  <template v-slot:prepend>
                    <i class='bx bx-caret-down' style="font-size: 20px; margin-right: 12px;"></i>
                  </template>
                  <v-list-item-title>Collapse all categories</v-list-item-title>
                </v-list-item>
                <v-list-item @click="duplicateCategory">
                  <template v-slot:prepend>
                    <i class='bx bx-caret-up' style="font-size: 20px; margin-right: 12px;"></i>
                  </template>
                  <v-list-item-title>Expand all categories</v-list-item-title>
                </v-list-item>
              </v-list>
            </v-menu>
          </v-col>
          <v-col cols="auto" style="max-width: 86%">
            <v-slide-group
              v-model="selected"
              class="custom-slide-group"
              show-arrows
            >
              <v-slide-group-item
                v-for="category in categories"
                :key="category.id"
                v-slot="{ isSelected, toggle }"
                :value="category.id"
              >
                <v-btn
                  :class="['slide-tab', { 'active': isSelected }]"
                  variant="text"
                  @click="toggle"
                >
                  {{ category.name }}
                </v-btn>
              </v-slide-group-item>
            </v-slide-group>

          </v-col>
        </v-row>

      </div>
      <v-card
        v-for="category in categories"
        :key="category.id"
        elevation="1"
        class="category-card"

      >
        <!-- Category Header -->
        <v-card-text class="pa-2 category-card-header" @click="toggleCategory(category.id)">
          <v-row align="center" no-gutters>
            <!-- Drag Handle -->
            <v-col cols="auto" class="mr-3">
              <i class='mdi-drag mdi v-icon notranslate v-theme--light v-icon--size-default'></i>
            </v-col>

            <!-- Category Info -->
            <v-col>
              <!--              <v-text-field-->
              <!--                  v-model="category.name"-->
              <!--                  @blur="handleUpdateCategory(category.id, category.name)"-->
              <!--                  label="Category name"-->
              <!--                  variant="plain"-->
              <!--                  density="compact"-->
              <!--                  hide-details-->
              <!--                  class="infield tf-hover pr-10"-->
              <!--                  :counter="30"-->
              <!--                  maxlength="30"-->
              <!--                  height="30px"-->
              <!--                  width="40%"-->
              <!--              >-->
              <!--                <template #append-inner>-->
              <!--                  <span class="infield-counter">{{ (category?.name?.length || 0) }}/30</span>-->
              <!--                </template>-->
              <!--              </v-text-field>-->

              <div
                class="v-input v-input--horizontal v-input--center-affix v-input--density-compact v-theme--light v-locale--is-ltr v-input--dirty v-text-field product-category__name"
                style="max-width: 268px;">
                <div class="v-input__control">
                  <div
                    class="v-field v-field--active v-field--appended v-field--center-affix v-field--dirty v-field--has-background v-field--variant-filled v-theme--light v-locale--is-ltr"
                    style="background-color: rgb(240, 240, 240); color: rgb(0, 0, 0); caret-color: rgb(0, 0, 0);">
                    <div class="v-field__overlay">
                    </div>
                    <div class="v-field__loader">
                      <div class="v-progress-linear v-theme--light v-locale--is-ltr" role="progressbar"
                           aria-hidden="true" aria-valuemin="0" aria-valuemax="100"
                           style="top: 0px; height: 0px; --v-progress-linear-height: 2px;">
                        <div class="v-progress-linear__background"></div>
                        <div class="v-progress-linear__buffer" style="width: 0%;">
                        </div>
                        <div class="v-progress-linear__indeterminate">
                          <div class="v-progress-linear__indeterminate long">
                          </div>
                          <div class="v-progress-linear__indeterminate short">
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="v-field__field" data-no-activator="">
                      <label class="v-label v-field-label v-field-label--floating" aria-hidden="true" for="input-49"
                             style="">
                        Category name
                      </label>
                      <label class="v-label v-field-label" for="input-49" style="">Category name</label>
                      <input v-model="category.name" @blur="handleUpdateCategory(category.id, category.name)" size="1"
                             type="text" id="input-49" aria-describedby="input-49-messages" maxlength="30"
                             class="v-field__input">
                    </div>
                    <div class="v-field__append-inner">
                      <div><span data-v-e58f4069="" class="text-caption" style="display: none;">7/30</span>
                        <div class="v-field__append-inner">
                          <span class="infield-counter">{{ (category?.name?.length || 0) }}/30</span></div>
                      </div>
                    </div>
                    <div class="v-field__outline">
                    </div>
                  </div>

                </div>
              </div>
            </v-col>

            <!-- Product Count Badge -->
            <v-col cols="auto" class="mr-3">


              <div class="d-flex align-center  ga-1 mr-2">
                <v-badge location="top right" class="black-text-badge" color="white"
                         :content=" category.products ? category.products.length : 0 ">
                </v-badge>

              </div>
            </v-col>


            <!-- Add Product Button -->
            <v-col cols="auto" class="mr-2">
              <v-btn
                color="primary"
                variant="outlined"
                size="small"
                rounded="lg"
                class="text-none"
                @click="handleAddProduct(category.id)"
                :loading="loading"
              >
                <i class='bx bx-plus' style="font-size: 20px; margin-right: 8px;"></i>
                Product
              </v-btn>
            </v-col>

            <!-- Category Menu -->
            <v-col cols="auto" class="mr-2">
              <v-menu>
                <template v-slot:activator="{ props }">
                  <v-btn icon variant="text" v-bind="props">
                    <i class='bx bx-dots-vertical-rounded' style="font-size: 20px;"></i>
                  </v-btn>
                </template>
                <v-list>
                  <v-list-item @click="handleDeleteCategory(category.id)">
                    <template v-slot:prepend>
                      <i class='bx bx-trash' style="font-size: 20px; margin-right: 12px;"></i>
                    </template>
                    <v-list-item-title>Delete Category</v-list-item-title>
                  </v-list-item>
                  <v-list-item @click="duplicateCategory(category.id)">
                    <template v-slot:prepend>
                      <i class='bx bx-copy' style="font-size: 20px; margin-right: 12px;"></i>
                    </template>
                    <v-list-item-title>Duplicate Category</v-list-item-title>
                  </v-list-item>
                  <v-list-item @click="handleChangeProductVisibility(category.id,'all')">
                    <template v-slot:prepend>
                      <i class='bx bx-show' style="font-size: 20px; margin-right: 12px;"></i>
                    </template>
                    <v-list-item-title>Change products to visible</v-list-item-title>
                  </v-list-item>
                </v-list>
              </v-menu>
            </v-col>

            <v-col cols="auto" class="mr-2">
              <button @click="handleChangeCategoryVisibility(category.id,'specific')" type="button"
                      class="v-btn v-btn--icon v-theme--light text-primary v-btn--density-compact elevation-0 v-btn--size-default v-btn--variant-text mr-3">
                <span class="v-btn__content">
                  <i
                    :class="[{'mdi-eye mdi':category.is_visible,'mdi mdi-eye-off-outline':!category.is_visible},'v-icon notranslate v-theme--light v-icon--size-default']"
                    aria-hidden="true"></i>
                </span>
              </button>

            </v-col>
            <!-- Collapse Toggle -->
            <v-col cols="auto">
              <button type="button"
                      class="v-btn v-btn--elevated v-btn--icon v-theme--light v-btn--density-comfortable v-btn--size-default v-btn--variant-elevated ml-2 bg-white">
                <span class="v-btn__overlay"></span><span class="v-btn__underlay"></span>
                <span class="v-btn__content">
                <i
                  :class="[{'mdi-chevron-down mdi':isCategoryExpanded(category.id)},{'mdi-chevron-up mdi':!isCategoryExpanded(category.id)},' v-icon notranslate v-theme--light v-icon--size-default']"
                  aria-hidden="true" style="color: rgb(152, 161, 174); caret-color: rgb(152, 161, 174);"></i>
              </span>
              </button>
            </v-col>

          </v-row>
        </v-card-text>

        <!-- Products List -->
        <v-expand-transition>
          <div v-show="isCategoryExpanded(category.id)" class="products-container">
            <v-card
              v-for="product in category.products"
              :key="product.id"
              elevation="0"
              class="product-card"
            >
              <v-card-text class="pa-2 product-card-item">
                <v-row align="center" no-gutters>
                  <!-- Drag Handle -->
                  <v-col cols="auto" class="mr-3">
                    <i class='mdi-drag mdi v-icon notranslate v-theme--light v-icon--size-default'></i>
                  </v-col>

                  <!-- Product Image -->
                  <v-col cols="auto" class="mr-4">
                    <v-avatar
                      size="30"
                      rounded="lg"
                      :class="product.image ? 'bg-white' : 'bg-grey-lighten-3'"
                      style="border: 1px solid #e0e0e0;"
                    >
                      <div v-if="!product.image" class="text-center">
                        <i class='bx bx-package text-grey' style="font-size: 32px;"></i>
                      </div>
                      <img v-else width="30" height="30" :src="product.image" :alt="product.name"/>
                    </v-avatar>
                  </v-col>

                  <!-- Product Name -->
                  <v-col>
                    <div
                      class="text-h6 font-weight-medium product-name-link"
                      @click="openProductDrawer(category.id,product.id)"
                    >
                      {{ product.name }}
                    </div>
                  </v-col>

                  <!-- Price -->
                  <v-col cols="auto" class="mr-3">
                    <div class="text-h6 font-weight-semibold">
                      {{ setting.currency || 'JD' }} {{ product.price }}
                    </div>
                  </v-col>

                  <!-- View Button -->
                  <v-col cols="auto" class="mr-2">
                    <button @click="handleChangeProductVisibility(product.id,'specific')" type="button"
                            class="v-btn v-btn--icon v-theme--light text-primary v-btn--density-compact elevation-0 v-btn--size-default v-btn--variant-text mr-3">
                      <span class="v-btn__content">
                        <i
                          :class="[{'mdi-eye mdi':product.is_visible,'mdi mdi-eye-off-outline':!product.is_visible},'v-icon notranslate v-theme--light v-icon--size-default']"
                          aria-hidden="true"></i>
                      </span>
                    </button>
                  </v-col>

                  <!-- Product Menu -->
                  <v-col cols="auto">
                    <v-menu>
                      <template v-slot:activator="{ props }">
                        <v-btn icon variant="text" v-bind="props">
                          <i class='bx bx-dots-vertical-rounded' style="font-size: 20px;"></i>
                        </v-btn>
                      </template>
                      <v-list>
                        <v-list-item @click="openProductDrawer(category.id,product.id)">
                          <template v-slot:prepend>
                            <i class='bx bx-edit' style="font-size: 20px; margin-right: 12px;"></i>
                          </template>
                          <v-list-item-title>Edit</v-list-item-title>
                        </v-list-item>
                        <v-list-item @click="handleDuplicateProduct(product.id)">
                          <template v-slot:prepend>
                            <i class='bx bx-copy' style="font-size: 20px; margin-right: 12px;"></i>
                          </template>
                          <v-list-item-title>Duplicate</v-list-item-title>
                        </v-list-item>
                        <v-list-item @click="handleDeleteProduct(product.id,product.category_id)">
                          <template v-slot:prepend>
                            <i class='bx bx-trash' style="font-size: 20px; margin-right: 12px;"></i>
                          </template>
                          <v-list-item-title>Delete</v-list-item-title>
                        </v-list-item>
                      </v-list>
                    </v-menu>
                  </v-col>
                </v-row>
              </v-card-text>
            </v-card>

            <!-- Empty State -->
            <div v-if="!category.products || category.products.length === 0" class="pa-8 text-center text-grey">
              <i class='bx bx-package' style="font-size: 48px;"></i>
              <div class="mt-2">No products yet</div>
            </div>
          </div>
        </v-expand-transition>
      </v-card>
      <!-- Loading Overlay -->
      <v-overlay :model-value="loading" class="align-center justify-center">
        <v-progress-circular indeterminate size="64"></v-progress-circular>
      </v-overlay>
      <!-- Error Snackbar -->
      <v-snackbar v-model="showError" color="error" timeout="3000">
        {{ error }}
      </v-snackbar>

      <!-- Product Edit Drawer -->
      <ProductEditDrawer
        v-model="showDrawer"
        :product-data="selectedProduct"
        @save="handleSaveProduct"
        @productUpdated="handleProductUpdated"
        @addVariant="handleAddProductVariant"
        @saveVariant="handleSaveProductVariant"
        @deleteVariant="handleDeleteVariant"
        @updateProductType="handleSaveProductType"
      />
      <!-- Delete Confirmation Dialog -->
      <DeleteConfirmDialog
        v-model="showDeleteDialog"
        :title="deleteDialog.title"
        :message="deleteDialog.message"
        @confirm="confirmDelete"
        @cancel="cancelDelete"
      />
            <ClientOnly>
              <ImageUploadDialog
                  ref="uploaderRef"
                  title="Upload product image"
                  accept="image/*"
                  :maxSizeMB="10"
                  @selected="onImageSelected"
              >
                <!-- 👇 Your UPLOAD BUTTON now lives INSIDE the dialog -->
                <template #actions="{ file, close, useImage }">
                  <v-btn variant="text" @click="close()">Cancel</v-btn>
                  <v-btn color="primary" :disabled="!file" @click="uploadLogo(file)">
                    Upload
                  </v-btn>
                </template>
              </ImageUploadDialog>
            </ClientOnly>

    </div>
    <div v-if="categoryCount == 0" class="d-flex justify-center">
      <v-btn @click="handleAddCategory"
             prepend-icon="bx-plus"
      >
        <template v-slot:prepend>
          <v-icon></v-icon>
        </template>
        Category

      </v-btn>
    </div>
  </v-container>
</template>


<style scoped>
.v-card {
  transition: all 0.3s ease;
}

.products-container {
  overflow: hidden;
}

.product-card-item:hover {
  background-color: #F1F7FF !important;
  color: #000000 !important;
}

.product-card {
  cursor: pointer;
  transition: all 0.2s ease;
  border-bottom: 1px solid #e0e0e0;
}

.product-card:last-child {
  border-bottom: none;
}

.product-card:hover {
  background-color: #f5f5f5;
  box-shadow: inset 0 0 0 1px #bdbdbd;
}

.product-name-link {
  cursor: pointer;
  transition: color 0.2s ease;
  font-size: 14px !important;
}

.body .v-btn .v-btn__content {
  font-size: 14px !important;
}

/* Ensure Boxicons are properly aligned */
i.bx {
  display: inline-flex;
  align-items: center;
  justify-content: center;
}

.banner-card {
  position: relative;
  background-color: #f5f5f5;
  border-radius: 0 0 12px 12px;
  overflow: visible;
}

.banner-image {
  position: relative;
}

.banner-camera-btn {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
}

.settings-btn {
  position: absolute;
  top: 16px;
  right: 16px;
}

.business-info-section {
  padding: 0 24px 24px 24px !important;
  background-color: white;
}

.logo-wrapper {
  margin-top: -70px;
  position: relative;
  z-index: 1;
}

.logo-card {
  border-radius: 12px;
  overflow: hidden;
  background-color: white;
  border: 3px solid white;
  position: relative;
}

.logo-camera-btn {
  position: absolute;
  right: 0;
  bottom: 1px;
  background-color: #fff !important;
  color: #006EFF !important;
}


/* base shape + default (non-hover) background */
.tf-hover :deep(.v-field) {
  position: relative;
}

.tf-hover :deep(.v-field__overlay) {
  border-radius: inherit;
  background: #eeeeee; /* light grey base */
  transition: background .15s ease, box-shadow .15s ease;
}

/* on hover: darker grey fill + subtle emphasis */
.tf-hover :deep(.v-field:hover .v-field__overlay) {
  background: #cfcfcf; /* darker grey like the screenshot */
}

/* black underline that appears on hover */
.tf-hover :deep(.v-field)::after {
  content: "";
  position: absolute;
  left: 12px;
  right: 12px;
  bottom: 2px;
  height: 3px; /* thicker underline */
  background: #000000;
  transform: scaleX(0.6);
  transform-origin: left;
  opacity: 0;
  transition: transform .15s ease, opacity .15s ease;
}

.tf-hover :deep(.v-field:hover)::after {
  transform: scaleX(1);
  opacity: 1;
}

/* make room for the counter inside the input */
.tf-hover :deep(.v-field__input) {
  padding-right: 64px;
}

/* counter style (top-right inside the field) */
.infield-counter {
  font-size: 16px;
  font-weight: 500;
  line-height: 1;
  margin-top: 2px;
  user-select: none;
  pointer-events: none;
  /* keep it visually higher like the screenshot */
  position: relative;
  top: -2px;
}

/* align append-inner nicely */
.tf-hover :deep(.v-field__append-inner) {
  align-items: flex-start;
}

.infield :deep(.v-field__input) {
  padding-right: 64px;
}

/* place the append content inside, aligned to the top (like your screenshot) */
.infield :deep(.v-field__append-inner) {
  align-items: flex-start; /* push counter up */
  padding-top: 4px; /* fine-tune vertical */
}

/* counter look */
.infield :deep(.infield-counter) {
  font-size: 14px;
  font-weight: 500;
  line-height: 1;
  opacity: .75;
  user-select: none;
  pointer-events: none;
}

.category-tab {
  color: #000000 !important;
}

.category-tab:hover {
  background-color: #F5F5F5 !important;
  color: #000000 !important;
}

.infield-counter {
  font-size: 12px !important;
}

.black-text-badge :deep(.v-badge__badge) {
  color: black !important;
}

.custom-slide-group {
  border-bottom: 1px solid #e0e0e0 !important;
}

.slide-tab {
  text-transform: none;
  font-size: 14px !important;
  font-weight: 500 !important;
  letter-spacing: 0;
  color: #000 !important;
  border-radius: 0;
  position: relative;
  padding: 12px 20px;
}

.slide-tab.active {
  color: #016FFF !important;
  font-weight: 500 !important;
  border-bottom: 2px solid #016FFF !important;
}

.slide-tab.active::after {
  content: '';
  position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
  height: 3px;
  background-color: #1976d2 !important;
}

.business-name-field :deep(.v-field--focused) {
  border-color: #000000 !important;
}

.business-name-field :deep(.v-field--focused .v-field__outline) {
  --v-field-border-opacity: 1;
  color: #000000 !important;
}

.business-name-field :deep(.v-counter) {
  position: absolute;
  top: 0px;
  right: 0;
}


.business-name-field :deep(.v-input__details) {
  min-height: 0;
  padding-top: 0;
}
.copy-domain{
  height:24px !important;
  border-radius: 24px;
  font-size: 12px;
  background-color: #DEEBFC !important;
  color:#006EFF !important;
  margin-top:  10px !important;
  margin-left: 10px !important;
  padding: 0 10px !important;
}
</style>
