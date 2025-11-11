<!-- components/ProductImageUploader.vue -->
<template>
  <v-dialog v-model="open" max-width="550" persistent>
    <v-card rounded="l">
      <v-card-title  class=" d-flex align-center justify-space-between product-header">
        <div class="text-h6">Product images</div>
        <button  @click="onCancel"  type="button" class="float-right  v-btn v-btn--icon v-theme--light v-btn--density-comfortable v-btn--size-default v-btn--variant-text">
          <i  class="mdi-close mdi v-icon  v-theme--light v-icon--size-default" aria-hidden="true"></i>
        </button>
      </v-card-title>

      <v-divider/>

      <v-card-text class="pt-4">
        <!-- Info banner -->
        <v-alert type="info" variant="tonal" class="mb-6" border="start">
          Accepted image formats: PNG, JPG. Max file size: {{ maxSizeMB }} MB.
        </v-alert>

        <!-- MAIN IMAGE TILE -->
        <div class="text-subtitle-2 mb-2">Main image</div>
        <div
            class="drop-zone d-flex align-center justify-center mb-6 mx-auto"
            @dragover.prevent
            @drop.prevent="handleDrop($event, true)"
            @click="pickFiles(true)"
        >
          <template v-if="mainTileSrc">
            <v-img :src="mainTileSrc" aspect-ratio="1" cover class="rounded-lg upload-image"/>
          </template>
          <template v-else>
            <div class="tile-placeholder">
              <div class="font-medium mb-2">Your product image here</div>
              <v-icon size="20">mdi-tray-arrow-up</v-icon>
            </div>
          </template>
        </div>

        <!-- ALL IMAGES -->
        <div class="text-subtitle-2 mb-2">All images</div>
        <v-row dense>
          <!-- Upload tile -->
          <v-col cols="12" sm="6" md="4" lg="3">
            <div
                class="tile-upload d-flex flex-column align-center justify-center"
                @dragover.prevent
                @drop.prevent="handleDrop($event)"
                @click="pickFiles(false)"
            >
              <div class="font-medium">Upload another image</div>
              <v-icon size="20" class="mb-2">mdi-tray-arrow-up</v-icon>
            </div>
          </v-col>

          <!-- Existing images -->
          <v-col
              v-for="img in existingLocal"
              :key="'ex-' + img.id"
              cols="12" sm="6" md="4" lg="3"
          >
            <v-card class="thumb-card" variant="outlined">
              <!-- Image container with relative position -->
              <div class="image-wrapper">
                <v-img class="upload-image" :src="img.thumb || img.url" aspect-ratio="1" cover/>

                <!-- Remove button in top-left corner -->
                <v-btn
                    icon
                    size="small"
                    variant="text"
                    color="error"
                    @click="removeExisting(img.id)"
                    class="remove-btn"
                >
                  <v-icon size="20">bx-bxs-x-circle</v-icon>
                </v-btn>
                <v-btn
                    icon
                    size="small"
                    variant="text"
                    color="primary"
                    @click="editImage(img.id)"
                    class="edit-btn"
                >
                  <v-icon size="20">mdi-pencil</v-icon>
                </v-btn>
              </div>

              <!-- Star button centered at bottom -->
<!--              <v-card-actions class="d-flex align-center justify-center">-->
<!--                <v-btn-->
<!--                    size="small"-->
<!--                    variant="text"-->
<!--                    :color="isMainExisting(img.id) ? 'primary' : ''"-->
<!--                    @click="setAsMainExisting(img.id)"-->
<!--                >-->
<!--                  <v-icon size="16" class="mr-1" :icon="isMainExisting(img.id) ? 'bx-star' : 'bx-star-outline'"/>-->
<!--                </v-btn>-->
<!--              </v-card-actions>-->
            </v-card>
          </v-col>

          <!-- Newly selected (not yet uploaded) -->
          <v-col
              v-for="(src, i) in newPreviews"
              :key="'new-' + i"
              cols="12" sm="6" md="4" lg="3"
          >
            <v-card class="thumb-card" variant="outlined">
              <div class="image-wrapper">
              <v-img style="width: 120px;height: 120px" class="upload-image" :src="src" aspect-ratio="1" cover/>

              <v-btn
                  icon
                  size="small"
                  variant="text"
                  color="primary"
                  @click="editNewImage(i)"
                  class="edit-btn"
              >
                <v-icon size="20">mdi-pencil</v-icon>
              </v-btn>
              </div>
<!--              <v-card-actions class="d-flex align-center justify-center">-->
<!--                <v-btn-->
<!--                    size="small"-->
<!--                    variant="text"-->
<!--                    :color="isMainNew(i) ? 'primary' : ''"-->
<!--                    @click="setAsMainNew(i)"-->
<!--                >-->
<!--                  <v-icon size="16" class="mr-1" :icon="isMainNew(i) ? 'bx-star' : 'bx-star-outline'"/>-->
<!--                </v-btn>-->

<!--                <v-btn size="small" variant="text" color="error" @click="removeNew(i)">-->
<!--                  <v-icon size="16" class="mr-1">bx-bxs-x-circle</v-icon>-->
<!--                </v-btn>-->
<!--              </v-card-actions>-->
            </v-card>
          </v-col>
        </v-row>

        <!-- Validation errors -->
        <div v-if="errors.length" class="mt-4">
          <v-alert
              v-for="(err, idx) in errors"
              :key="idx"
              type="warning"
              variant="tonal"
              class="mb-2"
          >
            {{ err }}
          </v-alert>
        </div>

        <!-- hidden input -->
        <input
            ref="fileInput"
            type="file"
            :accept="accept"
            multiple
            class="d-none"
            @change="onFilePicked"
        />
      </v-card-text>

      <v-divider/>

      <v-card-actions class="px-4 py-3">
        <v-spacer/>
        <v-btn variant="text" @click="onCancel">Cancel</v-btn>
        <v-btn color="primary" :disabled="!canSave" @click="onSave">Save</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script setup lang="ts">
import {computed, onBeforeUnmount, ref, watch} from 'vue'

type ExistingImage = {
  id: number | string
  url: string
  thumb?: string | null
  is_main?: boolean
}

const props = withDefaults(defineProps<{
  modelValue: boolean
  accept?: string
  maxSizeMB?: number
  /** Existing images from server */
  existing?: ExistingImage[]
}>(), {
  accept: 'image/png,image/jpeg',
  maxSizeMB: 10,
  existing: () => [],
})

const emit = defineEmits<{
  (e: 'update:modelValue', v: boolean): void
  (e: 'save', payload: {
    files: File[]
    main: { type: 'existing' | 'new', id?: number | string, newIndex?: number }
    removedExistingIds: Array<number | string>
  }): void
}>()

const open = computed({
  get: () => props.modelValue,
  set: v => emit('update:modelValue', v),
})

/** Local copy of existing images so the user can remove/set-main before saving */
const existingLocal = ref<ExistingImage[]>([])
const removedExistingIds = ref<Array<number | string>>([])

/** New files not uploaded yet */
const newFiles = ref<File[]>([])
const newBlobUrls = ref<string[]>([])
const fileInput = ref<HTMLInputElement | null>(null)

const errors = ref<string[]>([])
const MAX = computed(() => props.maxSizeMB * 1024 * 1024)

/** Main selection can point to an existing image or a new file */
const mainSel = ref<{ type: 'existing' | 'new' | null, id?: number | string, newIndex?: number }>({type: null})

// ---------- INIT / RESET ----------
const resetState = () => {
  existingLocal.value = (props.existing || []).map(x => ({...x}))
  removedExistingIds.value = []
  newFiles.value = []
  newBlobUrls.value.forEach(u => URL.revokeObjectURL(u))
  newBlobUrls.value = []
  errors.value = []

  // pick initial main
  const pre = existingLocal.value.find(x => x.is_main)
  if (pre) {
    mainSel.value = {type: 'existing', id: pre.id}
  } else if (existingLocal.value.length) {
    mainSel.value = {type: 'existing', id: existingLocal.value[0].id}
  } else {
    mainSel.value = {type: null}
  }
}

watch(() => props.modelValue, v => {
  if (v) resetState()
})
watch(() => props.existing, () => {
  if (open.value) resetState()
}, {deep: true})

onBeforeUnmount(() => newBlobUrls.value.forEach(u => URL.revokeObjectURL(u)))

// ---------- COMPUTED ----------
const newPreviews = computed(() => newBlobUrls.value)

const mainTileSrc = computed(() => {
  if (mainSel.value.type === 'existing') {
    const it = existingLocal.value.find(x => x.id === mainSel.value.id)
    return it ? (it.thumb || it.url) : null
  }
  if (mainSel.value.type === 'new' && mainSel.value.newIndex != null) {
    return newPreviews.value[mainSel.value.newIndex]
  }
  return null
})

const canSave = computed(() =>
    existingLocal.value.length + newFiles.value.length - removedExistingIds.value.length > 0
)

// ---------- HELPERS ----------
const clearErrors = () => (errors.value = [])

const validateAndCollect = (list: FileList | File[], pickAsMain = false) => {
  clearErrors()
  const acceptSet = new Set(props.accept.split(',').map(s => s.trim().toLowerCase()))

  const add: File[] = []
  ;[...Array.from(list as any as FileList)].forEach(f => {
    const type = (f.type || '').toLowerCase()
    if (!acceptSet.has(type)) {
      errors.value.push(`${f.name}: unsupported type`)
      return
    }
    if (f.size > MAX.value) {
      errors.value.push(`${f.name}: over ${props.maxSizeMB} MB`)
      return
    }
    const exists = newFiles.value.some(x => x.name === f.name && x.size === f.size)
    if (!exists) add.push(f)
  })

  if (add.length) {
    newFiles.value = [...newFiles.value, ...add]
    const urls = add.map(f => URL.createObjectURL(f))
    newBlobUrls.value.push(...urls)

    if (pickAsMain || mainSel.value.type === null) {
      mainSel.value = {type: 'new', newIndex: newFiles.value.length - add.length}
    }
  }
}

const pickFiles = (pickAsMain = false) => {
  ;(fileInput.value as any).__pickAsMain = pickAsMain
  fileInput.value?.click()
}

const onFilePicked = (e: Event) => {
  const input = e.target as HTMLInputElement
  if (!input?.files?.length) return
  const pickAsMain = Boolean((input as any).__pickAsMain)
  validateAndCollect(input.files, pickAsMain)
  input.value = ''
}

const handleDrop = (e: DragEvent, pickAsMain = false) => {
  if (e.dataTransfer?.files?.length) {
    validateAndCollect(e.dataTransfer.files, pickAsMain)
  }
}

// existing
const setAsMainExisting = (id: number | string) => {
  mainSel.value = {type: 'existing', id}
}
const isMainExisting = (id: number | string) =>
    mainSel.value.type === 'existing' && mainSel.value.id === id

const removeExisting = (id: number | string) => {
  const idx = existingLocal.value.findIndex(x => x.id === id)
  if (idx === -1) return
  existingLocal.value.splice(idx, 1)
  removedExistingIds.value.push(id)

  if (isMainExisting(id)) {
    if (existingLocal.value.length) {
      mainSel.value = {type: 'existing', id: existingLocal.value[0].id}
    } else if (newFiles.value.length) {
      mainSel.value = {type: 'new', newIndex: 0}
    } else {
      mainSel.value = {type: null}
    }
  }
}

const editImage = (index) => {
  console.log('Edit new image:', index)
  // Implement your edit logic for new images
}
// new
const setAsMainNew = (i: number) => {
  if (i < 0 || i >= newFiles.value.length) return
  mainSel.value = {type: 'new', newIndex: i}
}
const isMainNew = (i: number) =>
    mainSel.value.type === 'new' && mainSel.value.newIndex === i

const removeNew = (i: number) => {
  if (i < 0 || i >= newFiles.value.length) return
  newFiles.value.splice(i, 1)

  const url = newBlobUrls.value[i]
  URL.revokeObjectURL(url)
  newBlobUrls.value.splice(i, 1)

  if (isMainNew(i)) {
    if (existingLocal.value.length) {
      mainSel.value = {type: 'existing', id: existingLocal.value[0].id}
    } else if (newFiles.value.length) {
      mainSel.value = {type: 'new', newIndex: 0}
    } else {
      mainSel.value = {type: null}
    }
  } else if (mainSel.value.type === 'new' && (mainSel.value.newIndex as number) > i) {
    mainSel.value = {type: 'new', newIndex: (mainSel.value.newIndex as number) - 1}
  }
}

const onCancel = () => {
  open.value = false
}

const onSave = () => {
  emit('save', {
    files: newFiles.value,
    main: mainSel.value.type
        ? {...mainSel.value}
        : {type: 'existing', id: existingLocal.value[0]?.id}, // safety fallback
    removedExistingIds: removedExistingIds.value,
  })
  open.value = false
}
</script>

<style scoped>
.drop-zone {
  border: 2px dashed rgba(var(--v-theme-primary), 0.3);
  border-radius: 16px;
  max-height: 120px;
  width: 120px;
  height: 120px;
  cursor: pointer;
  transition: border-color .15s ease;
  position: relative;
}

.drop-zone:hover {
  border-color: rgb(var(--v-theme-primary));
}

.tile-placeholder,
.tile-upload {
  border-radius: 10px;
  background:  #0047A3;
  color: white;
  text-align: center;
  width: 120px;
  height: 120px;
  user-select: none;
  padding: 5px;
}

.tile-upload {
  cursor: pointer;
}

.thumb-card :deep(.v-img__img) {
  border-radius: 12px 12px 0 0;

}
.thumb-card{
  width: 120px !important;
  position: relative;
  height: 120px !important;
  overflow: visible !important
}

.upload-image{
  width: 120px;
  height: 120px;
}

.image-wrapper {
  position: relative;
  overflow: visible !important
}

.remove-btn {
  position: absolute;
  top: -16px;
  right: -8px;
  z-index: 2;
}

.remove-btn:hover {
  background-color: rgba(255, 255, 255, 1) !important;
}

/* Position edit button in top-right corner (hidden by default) */
.edit-btn {
  position: absolute;
  top: -8px;
  left: -8px;
  z-index: 10;
  background-color: rgba(255, 255, 255, 0.95) !important;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
  border: 1px solid rgba(0, 0, 0, 0.1);
  opacity: 0;
  visibility: hidden;
  transition: all 0.3s ease;
  transform: scale(0.8);
}

/* Show edit button on hover */
.image-wrapper:hover .edit-btn {
  opacity: 1;
  visibility: visible;
  transform: scale(1);
}

.edit-btn:hover {
  background-color: rgba(255, 255, 255, 1) !important;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
  transform: scale(1.1);
}
.product-header{
  background-color: #016FFF;

}
.product-header .text-h6{
  color: #ffffff !important;
}
</style>
