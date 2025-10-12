<!-- components/ProductImageUploader.vue -->
<template>
  <v-dialog v-model="open" max-width="860" persistent>
    <v-card rounded="xl">
      <v-card-title class="py-3">
        <div class="text-h6">Product images</div>
      </v-card-title>

      <v-divider />

      <v-card-text class="pt-4">
        <!-- Info banner -->
        <v-alert type="info" variant="tonal" class="mb-6" border="start">
          Accepted image formats: PNG, JPG. Max file size: {{ maxSizeMB }} MB.
        </v-alert>

        <!-- MAIN IMAGE TILE -->
        <div class="text-subtitle-2 mb-2">Main image</div>
        <div
            class="drop-zone d-flex align-center justify-center mb-6"
            @dragover.prevent
            @drop.prevent="handleDrop($event, true)"
            @click="pickFiles(true)"
        >
          <template v-if="mainTileSrc">
            <v-img :src="mainTileSrc" aspect-ratio="1" cover class="rounded-lg w-100" :max-height="260" />
          </template>
          <template v-else>
            <div class="tile-placeholder">
              <v-icon size="28" class="mb-2">bx-up-arrow-alt</v-icon>
              <div class="font-medium">Your product image here</div>
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
              <v-icon size="28" class="mb-2">bx-up-arrow-alt</v-icon>
              <div class="font-medium">Upload another image</div>
            </div>
          </v-col>

          <!-- Existing images -->
          <v-col
              v-for="img in existingLocal"
              :key="'ex-' + img.id"
              cols="12" sm="6" md="4" lg="3"
          >
            <v-card class="thumb-card" variant="outlined">
              <v-img :src="img.thumb || img.url" aspect-ratio="1" cover />
              <v-card-actions class="justify-space-between py-2">
                <v-btn
                    size="small"
                    variant="text"
                    :color="isMainExisting(img.id) ? 'primary' : ''"
                    @click="setAsMainExisting(img.id)"
                >
                  <v-icon size="16" class="mr-1" :icon="isMainExisting(img.id) ? 'bx-star' : 'bx-star-outline'" />
                  {{ isMainExisting(img.id) ? 'Main' : 'Set main' }}
                </v-btn>

                <v-btn
                    size="small"
                    variant="text"
                    color="error"
                    @click="removeExisting(img.id)"
                >
                  <v-icon size="16" class="mr-1">bx-bxs-x-circle</v-icon>
                  Remove
                </v-btn>
              </v-card-actions>
            </v-card>
          </v-col>

          <!-- Newly selected (not yet uploaded) -->
          <v-col
              v-for="(src, i) in newPreviews"
              :key="'new-' + i"
              cols="12" sm="6" md="4" lg="3"
          >
            <v-card class="thumb-card" variant="outlined">
              <v-img :src="src" aspect-ratio="1" cover />
              <v-card-actions class="justify-space-between py-2">
                <v-btn
                    size="small"
                    variant="text"
                    :color="isMainNew(i) ? 'primary' : ''"
                    @click="setAsMainNew(i)"
                >
                  <v-icon size="16" class="mr-1" :icon="isMainNew(i) ? 'bx-star' : 'bx-star-outline'" />
                  {{ isMainNew(i) ? 'Main' : 'Set main' }}
                </v-btn>

                <v-btn size="small" variant="text" color="error" @click="removeNew(i)">
                  <v-icon size="16" class="mr-1">bx-bxs-x-circle</v-icon>
                  Remove
                </v-btn>
              </v-card-actions>
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

      <v-divider />

      <v-card-actions class="px-4 py-3">
        <v-spacer />
        <v-btn variant="text" @click="onCancel">Cancel</v-btn>
        <v-btn color="primary" :disabled="!canSave" @click="onSave">Save</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script setup lang="ts">
import { computed, onBeforeUnmount, ref, watch } from 'vue'

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
const mainSel = ref<{ type: 'existing' | 'new' | null, id?: number | string, newIndex?: number }>({ type: null })

// ---------- INIT / RESET ----------
const resetState = () => {
  existingLocal.value = (props.existing || []).map(x => ({ ...x }))
  removedExistingIds.value = []
  newFiles.value = []
  newBlobUrls.value.forEach(u => URL.revokeObjectURL(u))
  newBlobUrls.value = []
  errors.value = []

  // pick initial main
  const pre = existingLocal.value.find(x => x.is_main)
  if (pre) {
    mainSel.value = { type: 'existing', id: pre.id }
  } else if (existingLocal.value.length) {
    mainSel.value = { type: 'existing', id: existingLocal.value[0].id }
  } else {
    mainSel.value = { type: null }
  }
}

watch(() => props.modelValue, v => { if (v) resetState() })
watch(() => props.existing, () => { if (open.value) resetState() }, { deep: true })

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
      mainSel.value = { type: 'new', newIndex: newFiles.value.length - add.length }
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
  mainSel.value = { type: 'existing', id }
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
      mainSel.value = { type: 'existing', id: existingLocal.value[0].id }
    } else if (newFiles.value.length) {
      mainSel.value = { type: 'new', newIndex: 0 }
    } else {
      mainSel.value = { type: null }
    }
  }
}

// new
const setAsMainNew = (i: number) => {
  if (i < 0 || i >= newFiles.value.length) return
  mainSel.value = { type: 'new', newIndex: i }
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
      mainSel.value = { type: 'existing', id: existingLocal.value[0].id }
    } else if (newFiles.value.length) {
      mainSel.value = { type: 'new', newIndex: 0 }
    } else {
      mainSel.value = { type: null }
    }
  } else if (mainSel.value.type === 'new' && (mainSel.value.newIndex as number) > i) {
    mainSel.value = { type: 'new', newIndex: (mainSel.value.newIndex as number) - 1 }
  }
}

const onCancel = () => {
  open.value = false
}

const onSave = () => {
  emit('save', {
    files: newFiles.value,
    main: mainSel.value.type
        ? { ...mainSel.value }
        : { type: 'existing', id: existingLocal.value[0]?.id }, // safety fallback
    removedExistingIds: removedExistingIds.value,
  })
  open.value = false
}
</script>

<style scoped>
.drop-zone {
  border: 2px dashed rgba(var(--v-theme-primary), 0.3);
  border-radius: 16px;
  min-height: 220px;
  cursor: pointer;
  padding: 12px;
  transition: border-color .15s ease;
}
.drop-zone:hover { border-color: rgb(var(--v-theme-primary)); }

.tile-placeholder,
.tile-upload {
  border-radius: 16px;
  background: rgb(var(--v-theme-primary));
  color: white;
  text-align: center;
  padding: 28px 18px;
  width: 100%;
  min-height: 160px;
  user-select: none;
}
.tile-upload { cursor: pointer; }
.thumb-card :deep(.v-img__img) { border-radius: 12px 12px 0 0; }
</style>
