<script setup lang="ts">
import {reactive, ref, computed, inject, onMounted} from 'vue'
import {useDisplay} from 'vuetify'

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
})

const loading = ref(false)
// const error = ref<string | null>(null)


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
      key: 'business_settings',
    }
    const res = await $sf('/panel/settings', {
      method: 'POST',
      body: payload,
    })
    // const newProduct = normItem(res)

    setAlert?.('Product added successfully!', 'success')
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
</style>
