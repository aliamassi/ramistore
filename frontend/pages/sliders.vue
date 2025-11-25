<template>
  <div>
    <h1 class="text-h4 mb-6">Slider Management</h1>
    
    <v-card>
      <v-card-title class="d-flex justify-space-between align-center">
        <span>Sliders</span>
        <v-btn color="primary" @click="openAddDialog">
          <v-icon left>mdi-plus</v-icon>
          Add Slider
        </v-btn>
      </v-card-title>
      
      <v-card-text>
        <v-data-table
          :headers="headers"
          :items="sliders"
          :loading="loading"
          class="elevation-1"
        >
          <template #item.image_url="{ item }">
            <v-img
              :src="item.image_url"
              width="100"
              height="60"
              cover
              class="my-2 rounded"
            />
          </template>
          
          <template #item.is_active="{ item }">
            <v-switch
              v-model="item.is_active"
              color="primary"
              hide-details
              @change="toggleActive(item)"
            />
          </template>
          
          <template #item.actions="{ item }">
            <v-btn
              icon
              size="small"
              @click="openEditDialog(item)"
            >
              <v-icon>mdi-pencil</v-icon>
            </v-btn>
            <v-btn
              icon
              size="small"
              color="error"
              @click="deleteSlider(item)"
            >
              <v-icon>mdi-delete</v-icon>
            </v-btn>
          </template>
        </v-data-table>
      </v-card-text>
    </v-card>

    <!-- Add/Edit Dialog -->
    <v-dialog v-model="dialog" max-width="600px">
      <v-card>
        <v-card-title>
          {{ editMode ? 'Edit Slider' : 'Add Slider' }}
        </v-card-title>
        
        <v-card-text>
          <v-form ref="form">
            <v-text-field
              v-model="formData.title"
              label="Title (Optional)"
              outlined
              dense
            />
            
            <v-file-input
              v-model="formData.image"
              label="Slider Image"
              accept="image/*"
              outlined
              dense
              prepend-icon="mdi-camera"
              @change="previewImage"
            />
            
            <v-img
              v-if="imagePreview"
              :src="imagePreview"
              max-height="200"
              class="my-4 rounded"
            />
            
            <v-switch
              v-model="formData.is_active"
              label="Active"
              color="primary"
            />
          </v-form>
        </v-card-text>
        
        <v-card-actions>
          <v-spacer />
          <v-btn text @click="closeDialog">Cancel</v-btn>
          <v-btn color="primary" @click="saveSlider" :loading="saving">
            Save
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, inject } from 'vue'
import { useSanctumFetch } from '#imports'

const $sf = useSanctumFetch<AnyObj>
const setAlert = inject<(msg: string, type?: 'success' | 'error' | 'info') => void>('setAlert')

const sliders = ref([])
const loading = ref(false)
const dialog = ref(false)
const editMode = ref(false)
const saving = ref(false)
const imagePreview = ref('')
const form = ref(null)

const formData = ref({
  id: null,
  title: '',
  image: null,
  is_active: true
})

const headers = [
  { title: 'Image', key: 'image_url', sortable: false },
  { title: 'Title', key: 'title' },
  { title: 'Order', key: 'order' },
  { title: 'Active', key: 'is_active', sortable: false },
  { title: 'Actions', key: 'actions', sortable: false },
]

onMounted(() => {
  fetchSliders()
})

async function fetchSliders() {
  loading.value = true
  try {
    const response = await $sf('/panel/slider')
    sliders.value = response
  } catch (error) {
    console.error('Error fetching sliders:', error)
    setAlert?.('Failed to load sliders', 'error')
  } finally {
    loading.value = false
  }
}

function openAddDialog() {
  editMode.value = false
  formData.value = {
    id: null,
    title: '',
    image: null,
    is_active: true
  }
  imagePreview.value = ''
  dialog.value = true
}

function openEditDialog(item) {
  editMode.value = true
  formData.value = {
    id: item.id,
    title: item.title || '',
    image: null,
    is_active: item.is_active
  }
  imagePreview.value = item.image_url
  dialog.value = true
}

function closeDialog() {
  dialog.value = false
  imagePreview.value = ''
}

function previewImage(event) {
  const file = Array.isArray(event) ? event[0] : event
  if (file) {
    const reader = new FileReader()
    reader.onload = (e) => {
      imagePreview.value = e.target.result
    }
    reader.readAsDataURL(file)
  }
}

async function saveSlider() {
  if (!editMode.value && !formData.value.image) {
    setAlert?.('Please select an image', 'error')
    return
  }

  saving.value = true

  try {
    const data = new FormData()
    if (formData.value.image) {
      data.append('image', formData.value.image)
    }
    if (formData.value.title) {
      data.append('title', formData.value.title)
    }
    data.append('is_active', formData.value.is_active ? '1' : '0')

    if (editMode.value) {
      data.append('_method', 'PUT')
      await $sf(`/panel/slider/${formData.value.id}`, {
        method: 'PUT',
        body: data
      })
      setAlert?.('Slider updated successfully', 'success')
    } else {
      await $sf('/panel/slider', {
        method: 'POST',
        body: data
      })
      setAlert?.('Slider created successfully', 'success')
    }

    closeDialog()
    await fetchSliders()
  } catch (error) {
    console.error('Error saving slider:', error)
    setAlert?.('Failed to save slider', 'error')
  } finally {
    saving.value = false
  }
}

async function toggleActive(item) {
  try {
    const data = new FormData()
    data.append('is_active', item.is_active ? '1' : '0')
    data.append('_method', 'PUT')
    
    await $sf(`/panel/slider/${item.id}`, {
      method: 'POST',
      body: data
    })
    setAlert?.('Slider status updated', 'success')
  } catch (error) {
    console.error('Error updating slider:', error)
    item.is_active = !item.is_active // Revert on error
    setAlert?.('Failed to update slider', 'error')
  }
}

async function deleteSlider(item) {
  if (!confirm(`Are you sure you want to delete this slider?`)) {
    return
  }

  try {
    await $sf(`/panel/slider/${item.id}`, { method: 'DELETE' })
    setAlert?.('Slider deleted successfully', 'success')
    await fetchSliders()
  } catch (error) {
    console.error('Error deleting slider:', error)
    setAlert?.('Failed to delete slider', 'error')
  }
}
</script>

<style scoped>
.v-data-table {
  background: white;
}
</style>
