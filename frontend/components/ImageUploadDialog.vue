<script setup lang="ts">
import { ref, onBeforeUnmount } from 'vue'

const props = defineProps<{
  title?: string
  accept?: string
  maxSizeMB?: number
  capture?: string | boolean
  // Optional built-in uploader (see option 2)
  uploader?: (file: File) => Promise<any>
  uploadButtonText?: string
  closeOnUploadSuccess?: boolean
}>()

const emit = defineEmits<{
  (e: 'selected', payload: {
    file: File, url: string, width: number, height: number,
    type: string, fileName: string, size: number
  }): void
  (e: 'open'): void
  (e: 'close'): void
  (e: 'error', error: unknown): void
  (e: 'uploaded', result: any): void
}>()

const open = ref(false)
const fileInput = ref<HTMLInputElement | null>(null)
const previewUrl = ref<string>('')
const imgMeta = ref<{ w: number; h: number } | null>(null)
const pickedFile = ref<File | null>(null)
const errorMsg = ref<string>('')
const uploading = ref(false)

const title = props.title ?? 'Upload image'
const accept = props.accept ?? 'image/*'
const maxSizeMB = props.maxSizeMB ?? 10
const uploadButtonText = props.uploadButtonText ?? 'Upload'
const closeOnUploadSuccess = props.closeOnUploadSuccess ?? true

let lastObjectUrl: string | null = null

function pick() { fileInput.value?.click() }
function close() { onCancel() }
defineExpose({ pick, close })

function revokeLastUrl() {
  if (lastObjectUrl) { URL.revokeObjectURL(lastObjectUrl); lastObjectUrl = null }
}

async function onFilePicked(e: Event) {
  try {
    errorMsg.value = ''
    const file = (e.target as HTMLInputElement).files?.[0]
    if (!file) return

    if (!file.type.startsWith('image/')) throw new Error('Please select an image file.')
    if (file.size > maxSizeMB * 1024 * 1024) throw new Error(`Image is larger than ${maxSizeMB} MB.`)

    pickedFile.value = file
    revokeLastUrl()
    const url = URL.createObjectURL(file)
    lastObjectUrl = url
    previewUrl.value = url

    // get dimensions
    imgMeta.value = null
    await new Promise<void>((resolve, reject) => {
      const img = new Image()
      img.onload = () => { imgMeta.value = { w: img.naturalWidth, h: img.naturalHeight }; resolve() }
      img.onerror = reject
      img.src = url
    })

    open.value = true
    emit('open')

  } catch (err: any) {
    errorMsg.value = err?.message || 'Failed to load image.'
    emit('error', err)
  } finally {
    if (fileInput.value) fileInput.value.value = ''
  }
}

function onUseImage() {
  if (!pickedFile.value || !previewUrl.value || !imgMeta.value) return
  const f = pickedFile.value
  emit('selected', {
    file: f,
    url: previewUrl.value,
    width: imgMeta.value.w,
    height: imgMeta.value.h,
    type: f.type,
    fileName: f.name,
    size: f.size,
  })
  // keep dialog open; parent might still want to press Upload via slot
}

async function onUploadInternal() {
  if (!props.uploader || !pickedFile.value) return
  uploading.value = true
  try {
    const res = await props.uploader(pickedFile.value)
    emit('uploaded', res)
    if (closeOnUploadSuccess) onCancel()
  } catch (err) {
    emit('error', err)
  } finally {
    uploading.value = false
  }
}

function replaceFile() { fileInput.value?.click() }

function onCancel() {
  open.value = false
  emit('close')
  pickedFile.value = null
  imgMeta.value = null
  previewUrl.value = ''
  errorMsg.value = ''
  revokeLastUrl()
}

onBeforeUnmount(() => { revokeLastUrl() })
</script>

<template>
  <!-- Hidden file input -->
  <input
      ref="fileInput"
      type="file"
      :accept="accept"
      :capture="capture"
      class="hidden"
      @change="onFilePicked"
  />

  <!-- Dialog -->
  <v-dialog v-model="open" persistent max-width="720">
    <v-card class="rounded-xl overflow-hidden">
      <v-toolbar density="comfortable" color="primary" class="text-white">
        <v-toolbar-title class="text-subtitle-1 font-medium">{{ title }}</v-toolbar-title>
        <v-spacer />
        <v-btn icon variant="text" @click="onCancel">
          <i class="bx bx-x text-white" style="font-size:18px;"></i>
        </v-btn>
      </v-toolbar>

      <v-card-text class="py-6">
        <div v-if="errorMsg" class="mb-4 text-red-600 text-sm">{{ errorMsg }}</div>
        <div v-if="previewUrl" class="preview-wrap">
          <img :src="previewUrl" alt="Selected" class="preview-img" />
          <div class="meta">
            <div><strong>File:</strong> {{ pickedFile?.name }}</div>
            <div><strong>Type:</strong> {{ pickedFile?.type }}</div>
            <div><strong>Size:</strong> {{ (pickedFile?.size || 0) / 1024 | 0 }} KB</div>
            <div v-if="imgMeta"><strong>Dimensions:</strong> {{ imgMeta.w }} × {{ imgMeta.h }}</div>
          </div>
        </div>
        <div v-else class="text-medium-emphasis text-sm">
          No image selected yet. Click <em>Replace</em> to choose one.
        </div>
      </v-card-text>

      <v-card-actions class="justify-between gap-2 px-6 pb-6">
        <div class="flex items-center gap-2">
          <v-btn size="small" variant="tonal" @click="replaceFile">Replace</v-btn>
        </div>

        <!-- ACTIONS SLOT: inject your own buttons here -->
        <div class="flex items-center gap-2">
          <slot name="actions" :file="pickedFile" :url="previewUrl" :useImage="onUseImage" :close="onCancel">
            <!-- Default actions if no slot provided -->
            <v-btn variant="text" @click="onCancel">Cancel</v-btn>
            <!-- If an uploader prop is given, also show built-in Upload -->
            <v-btn
                v-if="uploader"
                color="primary"
                :disabled="!pickedFile || uploading"
                :loading="uploading"
                @click="onUploadInternal"
            >{{ uploadButtonText }}</v-btn>
          </slot>
        </div>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<style scoped>
.preview-wrap { display: grid; grid-template-columns: 1fr 280px; gap: 16px; align-items: start; }
.preview-img { width: 100%; max-height: 48vh; object-fit: contain; border-radius: 12px; border: 1px solid #eee; }
.meta { font-size: 0.875rem; line-height: 1.2rem; color: rgba(0,0,0,.7); display: grid; gap: 6px; }
.rounded-xl { border-radius: 16px; }
.overflow-hidden { overflow: hidden; }
</style>
