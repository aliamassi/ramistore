
<script setup lang="ts">
// definePageMeta({ middleware: ['require-auth'] })
// definePageMeta({ middleware: ['require-auth'] })
import {ref, computed, watch, onMounted} from 'vue'
import {useProduct} from '~/composables/useProduct'
import ProductEditDrawer from '~/components/ProductEditDrawer.vue'
import DeleteConfirmDialog from '~/components/DeleteConfirmDialog.vue'

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
  addProduct,
  addCategory,
  updateProduct,
  deleteProduct,
  deleteCategory,
  updateCategory,
  fetchProductImages,
  duplicateProduct
} = useProduct()
const { user } = useSanctum()

// Local state
const expanded = ref(true)
const expandedCategories = ref(new Set()) // Track which categories are expanded
const showError = ref(false)
const showDrawer = ref(false)
const selectedProduct = ref(null)


const bannerImage = ref('https://images.unsplash.com/photo-1563805042-7684c019e1cb?w=1200')
const logoImage = ref('') // Set to empty for placeholder
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
    await updateProduct(productData.id, productData)
    console.log('Product updated successfully')
  } catch (err) {
    console.error('Failed to update product:', err)
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
const handleDeleteProduct = (id) => {
  deleteDialog.value = {
    title: 'Delete Product',
    message: 'Are you sure you want to delete this product? This action cannot be undone.',
    itemId: id,
    itemType: 'product'
  }
  showDeleteDialog.value = true
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
      await deleteProduct(deleteDialog.value.itemId)
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
          height="200"
          cover
          class="banner-image"
      >
        <!-- Get Domain Button -->
        <v-btn
            prepend-icon="bx bx-link"
            color="white"
            class="ma-4 text-none"
            elevation="2"
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
                width="140"
                height="140"
            >
              <v-img
                  v-if="logoImage"
                  :src="logoImage"
                  cover
                  height="100%"
              ></v-img>
              <div v-else class="d-flex align-center justify-center fill-height">
                <i class='bx bx-store' style="font-size: 64px; color: #9e9e9e;"></i>
              </div>

              <!-- Camera Button for Logo -->
              <v-btn
                  icon
                  color="primary"
                  size="small"
                  class="logo-camera-btn"
                  elevation="3"
              >
                <i class='bx bx-camera' style="font-size: 18px;"></i>
              </v-btn>
            </v-card>
          </v-col>

          <!-- Business Name Input -->
          <v-col class="ml-4">
            <div class="text-caption text-grey-darken-1 mb-1">Business</div>
            <v-text-field
                variant="underlined"
                density="compact"
                hide-details
                class="business-name-field"
                :counter="45"
                maxlength="45"
            >
              <template v-slot:append>
                <div class="text-caption text-grey">{{ user?.name?.length }}/45</div>
              </template>
            </v-text-field>
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
          <v-col cols="auto" >
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
            <v-tabs
                v-model="tab"
                align-tabs="center"
                color="deep-purple-accent-4"
            >
              <v-tab v-for="category in categories" :value="category.id">{{category.name}}</v-tab>
            </v-tabs>
          </v-col>
        </v-row>

      </div>
      <v-card
          v-for="category in categories"
          :key="category.id"
          elevation="1"
          class="bg-grey-lighten-4 rounded-lg mb-3"
      >
        <!-- Category Header -->
        <v-card-text class="pa-4">
          <v-row align="center" no-gutters>
            <!-- Drag Handle -->
            <v-col cols="auto" class="mr-3">
              <i class='bx bx-menu text-grey' style="font-size: 20px;"></i>
            </v-col>

            <!-- Category Info -->
            <v-col>
              <v-text-field
                  v-model="category.name"
                  @blur="handleUpdateCategory(category.id, category.name)"
                  label="Category name"
                  variant="plain"
              ></v-text-field>
              <v-divider class="mt-2" style="max-width: 500px;"></v-divider>
            </v-col>

            <!-- Product Count Badge -->
            <v-col cols="auto" class="mr-3">
              <v-chip variant="outlined" size="large">
                {{ category.products ? category.products.length : 0 }}
              </v-chip>
            </v-col>

            <!-- Add Product Button -->
            <v-col cols="auto" class="mr-2">
              <v-btn
                  color="primary"
                  variant="outlined"
                  size="large"
                  rounded="lg"
                  class="text-none px-6"
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
                  <v-list-item @click="editCategory(category.id)">
                    <template v-slot:prepend>
                      <i class='bx bx-edit' style="font-size: 20px; margin-right: 12px;"></i>
                    </template>
                    <v-list-item-title>Edit Category</v-list-item-title>
                  </v-list-item>
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
                </v-list>
              </v-menu>
            </v-col>

            <!-- Collapse Toggle -->
            <v-col cols="auto">
              <v-btn
                  icon
                  variant="text"
                  @click="toggleCategory(category.id)"
              >
                <i
                    :class="isCategoryExpanded(category.id) ? 'bx bx-chevron-up' : 'bx bx-chevron-down'"
                    style="font-size: 24px;"
                ></i>
              </v-btn>
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
              <v-card-text class="pa-4">
                <v-row align="center" no-gutters>
                  <!-- Drag Handle -->
                  <v-col cols="auto" class="mr-3">
                    <i class='bx bx-menu text-grey' style="font-size: 20px;"></i>
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
                      <img v-else :src="product.image" :alt="product.name"/>
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
                      {{ product.currency || 'JD' }} {{ product.price }}
                    </div>
                  </v-col>

                  <!-- View Button -->
                  <v-col cols="auto" class="mr-2">
                    <v-btn icon color="primary" @click="viewProduct(product.id)">
                      <i class='bx bx-show' style="font-size: 20px;"></i>
                    </v-btn>
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
                        <v-list-item @click="handleEditProduct(product.id)">
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
                        <v-list-item @click="handleDeleteProduct(product.id)">
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
      />
      <!-- Delete Confirmation Dialog -->
      <DeleteConfirmDialog
          v-model="showDeleteDialog"
          :title="deleteDialog.title"
          :message="deleteDialog.message"
          @confirm="confirmDelete"
          @cancel="cancelDelete"
      />
    </div>
    <div v-if="categoryCount == 0" class="d-flex justify-center">
      <v-btn
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
  margin: 0 16px 16px 16px;
  border: 1px solid #e0e0e0;
  border-radius: 8px;
  overflow: hidden;
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
}

.product-name-link:hover {
  color: #1976d2;
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
  bottom: 8px;
  right: 8px;
}

.business-name-field {
  font-size: 24px;
  font-weight: 500;
}

.business-name-field :deep(.v-field__input) {
  font-size: 24px;
  font-weight: 500;
  padding: 0;
  min-height: auto;
}

.business-name-field :deep(.v-field__underlay) {
  display: none;
}

.business-name-field :deep(.v-field__outline) {
  display: none;
}

</style>
