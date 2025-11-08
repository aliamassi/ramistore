<template>
  <v-navigation-drawer
      v-model="drawer"
      location="right"
      temporary
      width="600"
      class="product-edit-drawer"
  >
    <!-- Header -->
    <v-toolbar flat color="white">
      <v-toolbar-title class="text-h6">Edit product</v-toolbar-title>
      <v-spacer></v-spacer>
      <v-btn icon variant="text" @click="drawer = false">
        <v-icon>mdi-star-outline</v-icon>
      </v-btn>
      <v-btn icon variant="text" @click="drawer = false">
        <v-icon>mdi-dots-vertical</v-icon>
      </v-btn>
      <v-btn icon variant="text" @click="drawer = false">
        <v-icon>mdi-close</v-icon>
      </v-btn>
    </v-toolbar>

    <v-divider></v-divider>

    <!-- Content -->
    <v-container fluid class="pa-6">
      <!-- Image Upload and Basic Info -->
      <v-row>
        <v-col cols="12" md="4">
          <v-card
              flat
              class="upload-card d-flex flex-column align-center justify-center"
              color="primary"
              height="180"
          >
            <v-icon size="40" color="white">mdi-upload</v-icon>
            <div class="text-white text-center mt-2">
              <div class="font-weight-bold">Upload</div>
              <div class="font-weight-bold">pictures</div>
            </div>
          </v-card>
        </v-col>
        <v-col cols="12" md="8">
          <v-text-field
              v-model="product.name"
              label="Name"
              variant="outlined"
              density="comfortable"
              class="mb-3"
          ></v-text-field>
          <v-textarea
              v-model="product.description"
              label="Description"
              variant="outlined"
              density="comfortable"
              rows="5"
              no-resize
          ></v-textarea>
        </v-col>
      </v-row>

      <v-divider class="my-6"></v-divider>

      <!-- Price Section -->
      <div class="mb-4">
        <div class="d-flex align-center justify-space-between mb-4">
          <h3 class="text-h6">Price(s)</h3>
          <v-btn-toggle
              v-model="priceType"
              mandatory
              density="comfortable"
              color="primary"
              variant="outlined"
          >
            <v-btn value="simple" size="large">Simple</v-btn>
            <v-btn value="variants" size="large">Variants</v-btn>
          </v-btn-toggle>
        </div>

        <v-text-field
            v-model="product.price"
            label="Price"
            variant="outlined"
            density="comfortable"
            prefix="JOD"
            class="mb-3"
        ></v-text-field>

        <!-- Action Buttons -->
        <div class="d-flex flex-wrap ga-2 mb-3">
          <v-menu offset-y>
            <template v-slot:activator="{ props }">
              <v-btn
                  v-bind="props"
                  color="success"
                  variant="tonal"
                  append-icon="mdi-menu-down"
              >
                Available
              </v-btn>
            </template>
            <v-list>
              <v-list-item @click="product.availability = 'available'">
                <v-list-item-title>Available</v-list-item-title>
              </v-list-item>
              <v-list-item @click="product.availability = 'unavailable'">
                <v-list-item-title>Unavailable</v-list-item-title>
              </v-list-item>
            </v-list>
          </v-menu>

          <v-btn variant="outlined" prepend-icon="mdi-plus">
            Discount
          </v-btn>
          <v-btn variant="outlined" prepend-icon="mdi-plus">
            Cost
          </v-btn>
          <v-btn variant="outlined" prepend-icon="mdi-plus">
            Packaging
          </v-btn>
        </div>

        <v-btn variant="outlined" prepend-icon="mdi-plus">
          SKU
        </v-btn>
      </div>

      <v-divider class="my-6"></v-divider>

      <!-- Stock Control -->
      <div class="d-flex align-center justify-space-between mb-4">
        <div class="d-flex align-center">
          <h3 class="text-h6 mr-2">Stock Control</h3>
          <v-icon size="20" color="primary">mdi-information-outline</v-icon>
        </div>
        <v-switch
            v-model="product.stockControl"
            hide-details
            color="primary"
            inset
        ></v-switch>
      </div>

      <v-divider class="my-6"></v-divider>

      <!-- Add Modifier -->
      <div class="d-flex align-center justify-space-between mb-4">
        <div>
          <div class="d-flex align-center mb-1">
            <h3 class="text-h6 mr-2">Add Modifier</h3>
            <v-chip size="small" color="grey-lighten-2">0</v-chip>
          </div>
          <div class="text-caption text-grey">
            Ingredients, flavors, cutlery...
          </div>
        </div>
        <v-btn
            icon
            size="large"
            variant="outlined"
            color="primary"
        >
          <v-icon>mdi-plus</v-icon>
        </v-btn>
      </div>

      <v-divider class="my-6"></v-divider>

      <!-- Kitchen Selection -->
      <div>
        <div class="d-flex align-center mb-2">
          <h3 class="text-h6 mr-2">Kitchen</h3>
          <v-icon size="20" color="primary">mdi-information-outline</v-icon>
        </div>
        <div class="text-caption text-grey mb-3">
          Select the area where you prepare your product (optional).
        </div>
        <v-select
            v-model="product.kitchen"
            :items="kitchenOptions"
            variant="outlined"
            density="comfortable"
        ></v-select>
      </div>
    </v-container>
  </v-navigation-drawer>
</template>

<script setup>
import { ref } from 'vue'

const drawer = ref(true)
const priceType = ref('simple')

const product = ref({
  name: 'Product 3',
  description: '',
  price: '0.00',
  availability: 'available',
  stockControl: false,
  kitchen: 'Main kitchen'
})

const kitchenOptions = ref([
  'Main kitchen',
  'Secondary kitchen',
  'Prep station',
  'Bar'
])
</script>

<style scoped>
.product-edit-drawer {
  overflow-y: auto;
}

.upload-card {
  cursor: pointer;
  border-radius: 8px;
}

.upload-card:hover {
  opacity: 0.9;
}
</style>
