<template>
  <v-navigation-drawer scrollable
                       v-model="drawer"
                       location="right"
                       temporary
                       width="440"
                       class="product-drawer"
  >
    <!-- Header -->
    <v-toolbar color="white" elevation="0" height="50">
      <v-toolbar-title class="text-h6 font-weight-medium text-black">
        Edit product
      </v-toolbar-title>
      <v-spacer></v-spacer>
      <v-btn icon variant="text">
        <i class='bx bx-dots-vertical-rounded' style="font-size: 20px;"></i>
      </v-btn>
      <v-btn icon variant="text" @click="drawer = false">
        <i class='bx bx-x' style="font-size: 24px;"></i>
      </v-btn>
    </v-toolbar>

    <v-divider></v-divider>
    <!-- Content -->
    <v-container fluid>
      <!-- Product Image and Name -->
      <v-row>
        <v-col cols="12" md="4">
          <v-card @click="openUploader()"
                  flat
                  class="upload-card d-flex flex-column align-center justify-center"
                  height="100"
                  width="100"
          >
            <div v-if="product.image" class="relative">
              <img :src="product.image" width="120" height="120" alt="">
              <v-btn size="small" color="primary" class="upload-btn" density="compact" icon="mdi-camera"></v-btn>
            </div>

            <div v-else class="text-center">
              <div class="text-white mb-2">
                <div class="font-weight-bold">Upload</div>
                <div class="font-weight-bold">pictures</div>
              </div>
              <v-icon class="v-icon--size-default text-white" size="20" color="white">mdi-tray-arrow-up</v-icon>
            </div>
          </v-card>
        </v-col>
        <v-col>
          <v-text-field
              v-model="product.name"
              label="Name"
              variant="outlined"
              density="compact"
              hide-details
              @blur="saveProduct"
              size="small"
          ></v-text-field>
          <v-textarea
              v-model="product.description"
              label="Description"
              variant="outlined"
              density="compact"
              rows="3"
              class="mt-3"
              hide-details
              @blur="saveProduct"
          ></v-textarea>
        </v-col>
      </v-row>

      <div class="product-detail-divider my-6 v-row"></div>
      <!-- Price Section -->
      <div class="mb-4">
        <div class="d-flex align-center mb-3">
          <h3 class="text-h6">Price(s)</h3>
          <v-spacer></v-spacer>
          <div class="ios-segmented-wrapper">

          <v-btn-toggle
              v-model="priceTypeModel"
              mandatory
              divided
              class="ios-segmented">
            <v-btn
                value="simple"
                variant="flat"
                :ripple="false"
                class="price-type-btn"
                @click="productType('simple')"
            >
              Simple
            </v-btn>

            <v-btn
                value="variants"
                variant="flat"
                :ripple="false"
                class="price-type-btn"
                @click="productType('variants')"
            >
              <span>Variants</span>
              <span v-if="product.variants_count" class="count-badge">{{ product.variants_count }}</span>
            </v-btn>
          </v-btn-toggle>
          </div>
        </div>
        <div v-if="product.type === 'simple'">
          <v-text-field
              v-model="product.price"
              label="Price"
              placeholder="0.00"
              variant="outlined"
              @blur="saveProduct"
              style="width:50%"
          ></v-text-field>

          <v-row class="align-center mt-1">
            <!-- Available Dropdown -->
            <v-col cols="auto">
              <v-menu>
                <template v-slot:activator="{ props }">
                  <v-btn
                      v-bind="props"
                      rounded="pill"
                      variant="flat"
                      append-icon="mdi-chevron-down"
                      class="product-availability product-options"
                      color=""
                  >
                    Available
                  </v-btn>
                </template>
                <v-list>
                  <v-list-item>Available</v-list-item>
                  <v-list-item>Out of Stock</v-list-item>
                  <v-list-item>All</v-list-item>
                </v-list>
              </v-menu>
            </v-col>

            <!-- Discount Button -->
            <v-col cols="auto">
              <v-btn
                  variant="outlined"
                  rounded="lg"
                  color="default"
                  prepend-icon="mdi-plus"
                  class="product-options"
              >
                Discount
              </v-btn>
            </v-col>

            <!-- Cost Button -->
            <v-col cols="auto">
              <v-btn
                  variant="outlined"
                  rounded="lg"
                  color="default"
                  prepend-icon="mdi-plus"
                  class="product-options"
              >
                Cost
              </v-btn>
            </v-col>

            <!-- Packaging Button -->
            <v-col cols="auto">
              <v-btn
                  variant="outlined"
                  rounded="lg"
                  color="default"
                  prepend-icon="mdi-plus"
                  class="product-options"
              >
                Packaging
              </v-btn>
            </v-col>
          </v-row>
        </div>
        <div v-if="product.type === 'variants'">
          <v-text-field
              v-model="product.priceNote"
              placeholder="Tell my clients"
              variant="outlined"
              density="compact"
              hide-details
              class="mb-4"
              @blur="saveProduct"
          ></v-text-field>


          <!-- Variants List -->
          <div v-for="(variant, index) in product.variants" :key="index" class="mb-3">
            <v-card elevation="0" style="border: 1px solid #e0e0e0; border-radius: 8px;">
              <v-card-text class="pa-1 variant-card" @click="toggleVariant(index)">
                <v-row align="center" no-gutters>
                  <v-col cols="auto" class="mr-2">
                    <i class='bx bx-menu text-grey' style="font-size: 18px;"></i>
                  </v-col>
                  <v-col>
                    <div class="font-weight-medium">{{ variant.name }}</div>
                  </v-col>
                  <v-col cols="auto" class="mr-2">
                    <span class="font-weight-semibold">{{
                        settings.currency.value
                      }} {{ variant.price }}</span>
                  </v-col>
                  <v-col cols="auto" class="mr-2">
                    <v-menu>
                      <template v-slot:activator="{ props }">

                        <v-btn  v-bind="props" icon variant="text">
                          <i class='bx bx-dots-vertical-rounded' style="font-size: 20px;"></i>
                        </v-btn>

                      </template>
                      <v-list>
                        <v-list-item  @click="delVariant(variant.id)" title="Delete" class="variant-list-item">
                            <template v-slot:prepend>
                              <v-btn
                                  class="text-red"
                                  icon="mdi-trash-can-outline"
                                  variant="text"
                              ></v-btn>
                            </template>
                        </v-list-item>
                      </v-list>
                    </v-menu>
                  </v-col>
                  <v-col cols="auto">
                    <v-btn icon size="small" variant="text">
                      <i :class="variant.expanded ? 'bx bx-chevron-up' : 'bx bx-chevron-down'"
                         style="font-size: 20px;"></i>
                    </v-btn>
                  </v-col>
                </v-row>

                <!-- Expanded Variant Details -->
                <v-expand-transition>
                  <div v-show="variant.expanded" class="mt-3">
                    <v-text-field
                        v-model="variant.name"
                        label="Variant Name"
                        variant="outlined"
                        density="compact"
                        hide-details
                        class="mb-2"
                        @blur="saveProductVariant"
                    ></v-text-field>
                    <v-text-field
                        v-model.number="variant.price"
                        label="Price"
                        variant="outlined"
                        density="compact"
                        type="number"
                        :prefix="settings.currency.value "
                        hide-details
                        @blur="saveProductVariant"
                    ></v-text-field>
                  </div>
                </v-expand-transition>
              </v-card-text>
            </v-card>
          </div>

          <!-- Add Variant Button -->
          <v-btn
              variant="outlined"
              density="default"
              color="primary"
              class="text-none add-variant-btn"
              @click="addProductVariant"
          >
            <i class='bx bx-plus' style="font-size: 20px; margin-right: 8px;"></i>
            Add Variant
          </v-btn>
        </div>
      </div>

      <div class="product-detail-divider my-6 v-row"></div>
      <!-- Stock Control -->
      <div class="d-flex align-center mb-4">
        <div class="d-flex align-center">
          <h3 class="text-h6 mr-2">Stock Control</h3>
          <v-icon size="small" color="grey">
            <i class='bx bx-info-circle'></i>
          </v-icon>
        </div>
        <v-spacer></v-spacer>
        <v-switch
            v-model="product.stockControl"
            hide-details
            color="primary"
            density="compact"
        ></v-switch>
      </div>

      <div class="product-detail-divider my-6 v-row"></div>
      <!-- Add Modifier -->
      <v-card
          elevation="0"
          style="border: 1px solid #e0e0e0; border-radius: 8px;"
          class="mb-4"
      >
        <v-card-text class="pa-4">
          <v-row align="center" no-gutters>
            <v-col>
              <div class="d-flex align-center">
                <h3 class="text-h6 mr-2">Add Modifier</h3>
                <v-chip size="x-small">{{ product.modifiers.length }}</v-chip>
              </div>
              <div class="text-caption text-grey mt-1">Ingredients, flavors, cutlery...</div>
            </v-col>
            <v-col cols="auto">
              <v-btn
                  icon
                  color="primary"
                  variant="outlined"
                  @click="addModifier"
              >
                <i class='bx bx-plus' style="font-size: 20px;"></i>
              </v-btn>
            </v-col>
          </v-row>
        </v-card-text>
      </v-card>

      <div class="product-detail-divider my-6 v-row"></div>
      <!-- Kitchen -->
      <div class="mb-4">
        <div class="d-flex align-center mb-3">
          <h3 class="text-h6 mr-2">Kitchen</h3>
          <v-icon size="small" color="grey">
            <i class='bx bx-info-circle'></i>
          </v-icon>
        </div>
        <div class="text-caption text-grey mb-2">
          Select the area where you prepare your product (optional).
        </div>
        <v-select
            v-model="product.kitchen"
            :items="kitchenOptions"
            variant="outlined"
            density="comfortable"
            hide-details
        ></v-select>
      </div>

      <!-- Action Buttons -->
      <v-row class="mt-6">
        <v-col>
          <v-btn
              block
              size="large"
              variant="outlined"
              class="text-none"
              @click="drawer = false"
          >
            Cancel
          </v-btn>
        </v-col>
        <!--        <v-col>-->
        <!--          <v-btn-->
        <!--              block-->
        <!--              size="large"-->
        <!--              color="primary"-->
        <!--              class="text-none"-->
        <!--              @click="saveProduct"-->
        <!--          >-->
        <!--            Save-->
        <!--          </v-btn>-->
        <!--        </v-col>-->
      </v-row>
    </v-container>
  </v-navigation-drawer>

  <ProductImageUploader
      v-model="showUploader"
      :existing="productImages"
      :max-size-m-b="10"
      @save="onSaveImages"
  />

  <ChangePriceConfirmDialog
      v-model="showConfirmPriceDialog"
      :title="changePriceDialog.title"
      :message="changePriceDialog.message"
      @confirm="confirmChangePrice"
      @cancel="cancelChangePrice"
  />
</template>

<script setup lang="ts">
import {ref, watch} from 'vue'
import ProductImageUploader from '@/components/ProductImageUploader.vue'
import {useProduct} from '@/composables/useProduct'
import ChangePriceConfirmDialog from "@/components/ChangePriceConfirmDialog.vue";
import DeleteConfirmDialog from "@/components/DeleteConfirmDialog.vue";

const showConfirmPriceDialog = ref(false)
const changePriceDialog = ref({
  title: 'Change Price',
  message: ' The data will be lost when changing.',
  itemId: null,
  itemType: 'simple' // or 'category'
})

const showUploader = ref(false)
const confirmChangePriceStatus = ref(false)
let currentProductId = 1 // pass your product id
const {uploadProductImages, updateProductWithImages, fetchProductImages, productImages, categories, settings} = useProduct()
const onSaveImages = async ({files, mainIndex}: {
  files: File[],
  mainIndex: number
}) => {
  // 1) Editing an existing product: upload to server
  const product = await uploadProductImages(currentProductId, files)
  emit("productUpdated", product)
  // If your API doesn’t have /product/:id/images, you can do:
  // await updateProductWithImages(currentProductId, { main_index: mainIndex }, files)

  // 2) If you’re creating a new product instead:
  // await addProductWithImages({ name: form.name, ... }, files)
}

const props = defineProps({
  modelValue: {
    type: Boolean,
    default: false
  },
  productData: {
    type: Object,
    default: () => ({})
  }
})
console.log('productData', props.productData);
const emit = defineEmits(['update:modelValue', 'save', 'productUpdated'])

const drawer = ref(props.modelValue)
const priceType = ref<'simple' | 'variants'>('simple')


// computed v-model used by v-btn-toggle
const priceTypeModel = computed({
  get() {
    return priceType.value
  },
  set(val: 'simple' | 'variants') {
    confirmChangePriceStatus.value = false;
    console.log('priceTypeModel', val);
    console.log('canChangeType::: not yet', canChangeType());
    if (canChangeType()) {
      console.log('canChangeType::: yes');

      priceType.value = val
    } else {
      // do nothing -> toggle stays on same button
    }
  },
})

// your custom condition
function canChangeType() {
  return confirmChangePriceStatus.value
}

const existingImages = ref(productImages);
const product = ref({
  name: 'Product 1',
  translated_name: 'Product 1',
  description: '',
  translated_description: '',
  priceNote: '',
  variants: [
    {id: 1, name: 'Small', price: 0.00, expanded: false},
    {id: 2, name: 'Medium', price: 0.00, expanded: false}
  ],
  stockControl: false,
  modifiers: [],
  kitchen: 'Main kitchen'
})

const kitchenOptions = [
  'Main kitchen',
  'Grill station',
  'Dessert station',
  'Bar'
]

watch(drawer, (val) => {
  emit('update:modelValue', val)
})

watch(() => props.modelValue, (val) => {
  drawer.value = val
})


// Watch for productData changes and update product
watch(() => props.productData, (newData) => {
  console.log('newData', newData);
  if (newData && Object.keys(newData).length > 0) {
    console.log('productData received:', newData);
    product.value = {
      ...product.value,
      ...newData,
    }
    currentProductId = newData.id
    priceType.value = newData.type
  }
}, {immediate: true, deep: true})


const toggleVariant = (index) => {
  product.value.variants[index].expanded = !product.value.variants[index].expanded
}
const delVariant = (id) => {
  console.log('idididididididididid',id);
  handleDeleteVariant(id)
}

const addProductVariant = () => {

  handleAddProductVariant()
}
const saveProductVariant = () => {

  handleSaveProductVariant()
}
const productType = (type) => {
  changePriceDialog.value = {
    title: 'Change Price',
    message: ' The data will be lost when changing.',
    itemId: null,
    itemType: type
  }
  showConfirmPriceDialog.value = true
}

const openUploader = async () => {
  if (!currentProductId) return
  try {
    await fetchProductImages(currentProductId)
  } catch (e) {
    console.error(e)
  }
  showUploader.value = true
}

const addModifier = () => {
  console.log('Add modifier')
}

const confirmChangePrice = async () => {
  console.log('changePriceDialog', changePriceDialog.value.itemType);
  confirmChangePriceStatus.value = true
  priceType.value = changePriceDialog.value.itemType
  product.value.type = priceType.value;
  if(changePriceDialog.value.itemType == 'variants'){
    product.value.variants = [
      {id: 1, name: 'Small', price: 0.00, expanded: false},
      {id: 2, name: 'Medium', price: 0.00, expanded: false}
    ];
    console.log(' product.value', product.value);
  }
  saveProductType()
}

const cancelChangePrice = async () => {
  confirmChangePriceStatus.value = false
}

const saveProduct = () => {
  emit('save', product.value)
  // drawer.value = false
}
const saveProductType = () => {
  emit('updateProductType', product.value)
  // drawer.value = false
}
const handleAddProductVariant = () => {
  emit('addVariant', {
    product_id: product.value.id,
    name: `Variant ${product.value.variants.length + 1}`,
    price: 0.00,
    expanded: false
  })
}
const handleDeleteVariant = (id) => {
  emit('deleteVariant', {id:id})
}
const handleSaveProductVariant = () => {
  emit('saveVariant', product.value)
}
</script>

<style scoped>
.product-drawer {
  box-shadow: -2px 0 8px rgba(0, 0, 0, 0.1);
}

i.bx {
  display: inline-flex;
  align-items: center;
  justify-content: center;
}

:deep(.v-field__input) {
  padding-top: 8px;
  padding-bottom: 8px;
}

.upload-card {
  background-color: #0047A3;
}

.v-btn-group--density-compact.v-btn-group {
  height: 32px !important;
}

:deep(.custom-toggle .v-btn.v-btn--active) {
  color: rgb(0, 111, 255) !important; /* Active text color */
}

.product-detail-divider {
  height: 8px;
  background-color: #d6d6d6;
  width: 100% !important;
}

.product-availability:hover {
  background: #bcf1ca !important;
}

.product-availability {
  border-radius: 24px;
  background: #bcf1ca !important;
}

.product-options {
  font-size: 12px;
  font-weight: 500;
  width: auto;
  height: 24px;
  padding: 0px 5px;
}

.upload-btn {
  position: absolute;
  right: 0;
  bottom: 1px;
  background-color: #fff !important;
  color: #006EFF !important;
}

.upload-btn:hover {
  background-color: #F3F3F4 !important;
}

body .v-btn-group.v-btn-toggle.v-btn-group {
  align-items: center;
  border: 1px solid rgba(var(--v-border-color), var(--v-border-opacity));
  border-radius: 0.5rem;
  block-size: 32px !important;
}

.ios-segmented-wrapper {
  border: 1px solid #d1d5db;
  border-radius: 10px;
  overflow: hidden;
  box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
  background: white;
}

.ios-segmented {
  border: none !important;
  border-radius: 0 !important;
  box-shadow: none !important;
  background: transparent !important;
}

.ios-segmented :deep(.v-btn-group) {
  box-shadow: none !important;
}

.ios-segmented :deep(.v-btn) {
  text-transform: none !important;
  font-size: 15px !important;
  font-weight: 400 !important;
  letter-spacing: 0 !important;
  color: #000 !important;
  height: 32px !important;
  min-width: 114px !important;
  padding: 0 40px !important;
  background: #f9fafb !important;
  border-radius: 0 !important;
  box-shadow: none !important;
  position: relative !important;
  border: none !important;
  border-right: 1px solid #d1d5db !important;
}

.ios-segmented :deep(.v-btn:last-child) {
  border-right: none !important;
}

.ios-segmented :deep(.v-btn__overlay) {
  display: none !important;
}

.ios-segmented :deep(.v-btn__content) {
  display: flex !important;
  align-items: center !important;
  gap: 8px !important;
}

.ios-segmented :deep(.v-btn--active) {
  background: white !important;
  color: #2563eb !important;
  font-weight: 500 !important;
  box-shadow: inset 0 -2px 0 0 #2563eb !important;

}

/* Blue bottom border for active button */
.ios-segmented :deep(.v-btn--active::after) {
  content: '';
  position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
  height: 2px;
  background: #2563eb !important;
  z-index: 1;
}

.count-badge {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  min-width: 22px;
  height: 22px;
  padding: 0 7px;
  background: #e5e7eb;
  color: #6b7280;
  font-size: 13px;
  font-weight: 500;
  border-radius: 11px;
  margin-left: 8px;
}

.ios-segmented :deep(.v-btn--active) .count-badge {
  background: #dbeafe;
  color: #2563eb;
}
.add-variant-btn:hover{
  background-color: #F6F9FF !important;
}
.add-variant-btn:hover,.add-variant-btn:focus {
  background-color: #F6F9FF !important;
}

.add-variant-btn:hover :deep(.v-btn__content),.add-variant-btn:focus :deep(.v-btn__content) {
  color: #0370FF !important;
}
.text-red{
  color: #FE5F56 !important;
}

.variant-list-item >>> .v-list-item-title {
  color: #FE5F56 !important;
}
.variant-card:hover{
  cursor: pointer !important;
}
</style>
