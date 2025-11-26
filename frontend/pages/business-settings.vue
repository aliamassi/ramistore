<script setup lang="ts">
import {reactive, ref, computed, inject, onMounted, onBeforeUnmount, watch} from 'vue'
import {useDisplay} from 'vuetify'
import { useEditor, EditorContent } from '@tiptap/vue-3'
import StarterKit from '@tiptap/starter-kit'
import Placeholder from '@tiptap/extension-placeholder'
import TextAlign from '@tiptap/extension-text-align'
import Underline from '@tiptap/extension-underline'
import Link from '@tiptap/extension-link'

const {user} = useSanctum()
import {useSanctumFetch} from '#imports'

const setAlert = inject<(msg: string, type?: 'success' | 'error' | 'info') => void>('setAlert')
type CountryItem = {
  label: string;
  code: string;
  dial: string;
  flag: string
}

const settings = ref<AnyObj[]>([])

const currencies = ref<AnyObj[]>([])
const languages = ['English', 'العربية']
const $sf = useSanctumFetch<AnyObj>

// Form model
const form = reactive({
  businessName: '',
  currency: 'JOD',
  address: '',
  phoneNumber: '',
  contactEmail: '',
  tags: [] as string[],
  aboutUs: '',
})

const loading = ref(false)
// const error = ref<string | null>(null)

// Tiptap Editor
const editor = useEditor({
  content: form.aboutUs,
  extensions: [
    StarterKit,
    Placeholder.configure({
      placeholder: 'Tell your customers about your business, your story, what makes you special...',
    }),
    TextAlign.configure({
      types: ['heading', 'paragraph'],
    }),
    Underline,
    Link.configure({
      openOnClick: false,
    }),
  ],
  onUpdate: ({ editor }) => {
    form.aboutUs = editor.getHTML()
  },
})

// Update editor content when form.aboutUs changes (e.g. after fetch)
watch(() => form.aboutUs, (newValue) => {
  if (editor.value && newValue !== editor.value.getHTML()) {
    editor.value.commands.setContent(newValue, { emitUpdate: false })
  }
})

// Simple rules
const req = (v: any) => (!!v && String(v).trim().length > 0) || 'Required'
// Fake save

const onSave = async () => {
  loading.value = true;
  // error.value = null
  try {
    const payload = {
      business_name: form.businessName,
      currency: form.currency,
      phone_number: form.phoneNumber,
      address: form.address,
      contact_email: form.contactEmail,
      tags: form.tags,
      about_us: form.aboutUs,
      key: 'business_settings',
    }
    const res = await $sf('/panel/settings', {
      method: 'POST',
      body: payload,
    })
    // const newProduct = normItem(res)

    setAlert?.('Settings saved successfully!', 'success')
  } catch (e) {
    // error.value = e?.message || 'Failed to save'
    // setAlert?.(error.value, 'error')
  } finally {
    loading.value = false
  }
}

const fetchSettings = async () => {
  loading.value = true;
  // error.value = null
  try {
    const res = await $sf('/panel/settings?key=business_settings')

    settings.value = res.settings
    currencies.value = res.currency
    form.currency = res.setting.currency.value
    form.address = res.setting?.address?.value
    form.contactEmail = res.setting?.contact_email?.value
    form.phoneNumber = res.setting?.phone_number?.value
    form.businessName = res.setting?.business_name?.value
    form.tags = res.setting.tags?.value || []
    form.aboutUs = res.setting?.about_us?.value || ''
    
    // Update editor content
    if (editor.value) {
      editor.value.commands.setContent(form.aboutUs, { emitUpdate: false })
    }

  } catch (e) {
    // error.value = e?.message || 'Failed to fetch settings'
    // setAlert?.(error.value, 'error')

  } finally {
    loading.value = false
  }
}
onMounted(async () => {
  await fetchSettings()
})

onBeforeUnmount(() => {
  editor.value?.destroy()
})

</script>

<template>
  <VForm @submit.prevent="onSave">
    <VCard class="mx-4">
      <VCardTitle class="py-4">
        <div class="d-flex align-center ga-3">
          <VIcon size="28" class="text-medium-emphasis">bx-store-alt</VIcon>
          <span class="text-h5 font-weight-600">Business Information</span>
        </div>
      </VCardTitle>

      <VDivider/>

      <VCardText class="pt-6">

        <!-- Business Name -->
        <VTextField
            v-model="form.businessName"
            label="Business Name"
            variant="outlined"
            :rules="[req]"
            class="mb-4"
        />

        <!-- Currency -->
        <VSelect
            v-model="form.currency"
            :items="currencies"
            label="Currency"
            variant="outlined"
            :rules="[req]"
            class="mb-4"
        />

        <!-- Tags -->
        <VCombobox
            v-model="form.tags"
            label="Tags"
            placeholder="Breakfast, Falafel, Arabic"
            chips
            multiple
            closable-chips
            clearable
            variant="outlined"
            :rules="[req]"
            class="mb-4"
        />

        <!--        &lt;!&ndash; Language &ndash;&gt;-->
        <!--        <VSelect-->
        <!--          v-model="form.language"-->
        <!--          :items="languages"-->
        <!--          label="Language"-->
        <!--          variant="outlined"-->
        <!--          class="mb-6"-->
        <!--        />-->


      </VCardText>
    </VCard>
    <VCard class="mt-5 mx-4">
      <VCardTitle class="py-4">
        <div class="d-flex align-center ga-3">
          <VIcon size="28" class="text-medium-emphasis">mdi-card-account-phone-outline</VIcon>
          <span class="text-h5 font-weight-600">Contact Information</span>
        </div>
      </VCardTitle>

      <VCardText class="pt-6">

        <VTextField
            v-model="form.phoneNumber"
            label="Phone Number"
            variant="outlined"
            class="mb-4"
        />
        <VTextField
            v-model="form.contactEmail"
            label="Contact Email"
            variant="outlined"
            class="mb-4"
        />

        <VTextField
            v-model="form.address"
            label="Address"
            variant="outlined"
            class="mb-4"
        />
        <!--        &lt;!&ndash; Language &ndash;&gt;-->
        <!--        <VSelect-->
        <!--          v-model="form.language"-->
        <!--          :items="languages"-->
        <!--          label="Language"-->
        <!--          variant="outlined"-->
        <!--          class="mb-6"-->
        <!--        />-->


      </VCardText>
    </VCard>

    <!-- About Us Section -->
    <VCard class="mt-5 mx-4">
      <VCardTitle class="py-4">
        <div class="d-flex align-center ga-3">
          <VIcon size="28" class="text-medium-emphasis">mdi-information-outline</VIcon>
          <span class="text-h5 font-weight-600">About Us</span>
        </div>
      </VCardTitle>

      <VDivider/>

      <VCardText class="pt-6">
        <div class="mb-2">
          <span class="text-body-2 text-medium-emphasis">
            Write a description about your business that will be displayed on the About page.
          </span>
        </div>

        <div class="editor-container mb-4">
          <!-- Editor Toolbar -->
          <div v-if="editor" class="editor-toolbar d-flex flex-wrap align-center ga-1 pa-2 border-b">

            <!-- Text Formatting -->
            <VBtn size="x-small" variant="text" :color="editor.isActive('bold') ? 'primary' : undefined" @click="editor.chain().focus().toggleBold().run()">
              <VIcon>mdi-format-bold</VIcon>
            </VBtn>
            <VBtn size="x-small" variant="text" :color="editor.isActive('italic') ? 'primary' : undefined" @click="editor.chain().focus().toggleItalic().run()">
              <VIcon>mdi-format-italic</VIcon>
            </VBtn>
            <VBtn size="x-small" variant="text" :color="editor.isActive('underline') ? 'primary' : undefined" @click="editor.chain().focus().toggleUnderline().run()">
              <VIcon>mdi-format-underline</VIcon>
            </VBtn>
            <VBtn size="x-small" variant="text" :color="editor.isActive('strike') ? 'primary' : undefined" @click="editor.chain().focus().toggleStrike().run()">
              <VIcon>mdi-format-strikethrough</VIcon>
            </VBtn>

            <VDivider vertical class="mx-1" />

            <!-- Headings -->
            <VBtn size="x-small" variant="text" :color="editor.isActive('heading', { level: 2 }) ? 'primary' : undefined" @click="editor.chain().focus().toggleHeading({ level: 2 }).run()">
              <VIcon>mdi-format-header-2</VIcon>
            </VBtn>
            <VBtn size="x-small" variant="text" :color="editor.isActive('heading', { level: 3 }) ? 'primary' : undefined" @click="editor.chain().focus().toggleHeading({ level: 3 }).run()">
              <VIcon>mdi-format-header-3</VIcon>
            </VBtn>

            <VDivider vertical class="mx-1" />

            <!-- Lists -->
            <VBtn size="x-small" variant="text" :color="editor.isActive('bulletList') ? 'primary' : undefined" @click="editor.chain().focus().toggleBulletList().run()">
              <VIcon>mdi-format-list-bulleted</VIcon>
            </VBtn>
            <VBtn size="x-small" variant="text" :color="editor.isActive('orderedList') ? 'primary' : undefined" @click="editor.chain().focus().toggleOrderedList().run()">
              <VIcon>mdi-format-list-numbered</VIcon>
            </VBtn>

            <VDivider vertical class="mx-1" />

            <!-- Alignment -->
            <VBtn size="x-small" variant="text" :color="editor.isActive({ textAlign: 'left' }) ? 'primary' : undefined" @click="editor.chain().focus().setTextAlign('left').run()">
              <VIcon>mdi-format-align-left</VIcon>
            </VBtn>
            <VBtn size="x-small" variant="text" :color="editor.isActive({ textAlign: 'center' }) ? 'primary' : undefined" @click="editor.chain().focus().setTextAlign('center').run()">
              <VIcon>mdi-format-align-center</VIcon>
            </VBtn>
            <VBtn size="x-small" variant="text" :color="editor.isActive({ textAlign: 'right' }) ? 'primary' : undefined" @click="editor.chain().focus().setTextAlign('right').run()">
              <VIcon>mdi-format-align-right</VIcon>
            </VBtn>

            <VSpacer />

            <!-- Undo/Redo -->
            <VBtn size="x-small" variant="text" :disabled="!editor.can().undo()" @click="editor.chain().focus().undo().run()">
              <VIcon>mdi-undo</VIcon>
            </VBtn>
            <VBtn size="x-small" variant="text" :disabled="!editor.can().redo()" @click="editor.chain().focus().redo().run()">
              <VIcon>mdi-redo</VIcon>
            </VBtn>

          </div>

          <!-- Editor Content -->
          <EditorContent :editor="editor" class="editor-content pa-4" />
        </div>

        <div class="d-flex flex-wrap ga-2 mb-4">
          <VChip size="small" color="primary" variant="tonal">
            <VIcon start size="16">mdi-lightbulb-outline</VIcon>
            Tip: Keep it engaging and authentic
          </VChip>
          <VChip size="small" color="secondary" variant="tonal">
            <VIcon start size="16">mdi-format-text</VIcon>
            Use clear, simple language
          </VChip>
          <VChip size="small" color="success" variant="tonal">
            <VIcon start size="16">mdi-check-circle-outline</VIcon>
            Highlight what makes you unique
          </VChip>
        </div>

        <!-- Preview Section -->
        <VExpansionPanels v-if="form.aboutUs" class="mb-4">
          <VExpansionPanel>
            <VExpansionPanelTitle>
              <div class="d-flex align-center ga-2">
                <VIcon>mdi-eye-outline</VIcon>
                <span>Preview</span>
              </div>
            </VExpansionPanelTitle>
            <VExpansionPanelText>
                <div class="preview-content pa-4" v-html="form.aboutUs"></div>
            </VExpansionPanelText>
          </VExpansionPanel>
        </VExpansionPanels>

      </VCardText>
    </VCard>

    <!-- Save Button -->
    <VCard class="mt-5 mx-4">
      <VCardText class="pt-6">
        <div class="d-flex align-center ga-3">
          <VBtn type="submit" color="primary" :loading="loading">
            Save
          </VBtn>
          <!--          <span v-if="error" class="text-error text-body-2">{{ error }}</span>-->
        </div>
      </VCardText>
    </VCard>

  </VForm>
</template>

<style scoped>
/* Subtle rounded look like the screenshot */
.v-card {
  border-radius: 16px;
}

.preview-content {
  background: #f8f9fa;
  border-radius: 12px;
  border: 1px solid #e0e0e0;
}

.preview-content p {
  color: #475569;
  font-size: 1.05rem;
  margin: 0;
}

/* Editor Styles */
.editor-container {
  border: 1px solid #9e9e9e;
  border-radius: 8px;
  overflow: hidden;
  transition: border-color 0.3s;
}

.editor-container:focus-within {
  border-color: var(--v-theme-primary);
  border-width: 2px;
  margin: -1px; /* To prevent layout shift from border width change */
}

.editor-toolbar {
  background: #f5f5f5;
}

.editor-content {
  min-height: 200px;
  max-height: 500px;
  overflow-y: auto;
  outline: none;
}

/* ProseMirror specific styles */
:deep(.ProseMirror) {
  outline: none;
  min-height: 200px;
}

:deep(.ProseMirror p.is-editor-empty:first-child::before) {
  color: #adb5bd;
  content: attr(data-placeholder);
  float: left;
  height: 0;
  pointer-events: none;
}
</style>
