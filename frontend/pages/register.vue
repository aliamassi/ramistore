<script setup lang="ts">
import logo from '@images/logo.jpg'
import authV1BottomShape from '@images/svg/auth-v1-bottom-shape.svg?url'
import authV1TopShape from '@images/svg/auth-v1-top-shape.svg?url'
import {useAuth} from '~/composables/useAuth'
import {ref} from "vue";

const {register} = useAuth()
const loading = ref(false)
const err = ref<string | null>(null)
const isPasswordVisible = ref(false)
const alertMessage = ref<string | null>(null)
const alertType = ref<'error' | 'success'>('error')
const { login, isLoggedIn } = useSanctum()
const form = reactive({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
})

// OPTIONAL helper to format Laravel 422 errors
const formatLaravelError = (e: any): string => {
  // e.data?.errors is usually an object: { field: [msg, msg], ... }
  const errors = e?.data?.errors
  if (errors && typeof errors === 'object') {
    const list = Object.values(errors).flat().filter(Boolean) as string[]
    if (list.length) return list.join('\n')
  }
  return e?.data?.message || e?.message || 'Registration failed'
}

const handleRegister = async () => {
  if (loading.value) return
  loading.value = true
  err.value = null
  alertMessage.value = null

  try {
    let user = await register(form)
    await login({ email: form.email, password: form.password, remember: false })
    if (isLoggedIn) {                 // 👈 no `.value`
      await navigateTo('/products')   // or '/'
    }
  } catch (e: any) {
    console.error(e)
    const msg = formatLaravelError(e)
    alertMessage.value = msg
    // keep a short message near inputs
    err.value = typeof msg === 'string' ? msg : 'Please check your inputs'
  } finally {
    loading.value = false
  }
}

definePageMeta({guest: true, layout: 'blank'})
</script>

<template>
  <div class="auth-wrapper d-flex align-center justify-center pa-4">
    <div class="position-relative my-sm-16">
      <!-- 👉 Top shape -->
      <VImg
          :src="authV1TopShape"
          class="text-primary auth-v1-top-shape d-none d-sm-block"
      />

      <!-- 👉 Bottom shape -->
      <VImg
          :src="authV1BottomShape"
          class="text-primary auth-v1-bottom-shape d-none d-sm-block"
      />

      <!-- 👉 Auth card -->
      <VCard
          class="auth-card"
          max-width="460"
          :class="$vuetify.display.smAndUp ? 'pa-6' : 'pa-0'"
      >
        <VCardItem class="justify-center">
          <NuxtLink
              to="/"
              class="app-logo"
          >
            <!-- eslint-disable vue/no-v-html -->
<!--            <div-->
<!--                class="d-flex"-->
<!--                v-html="logo"-->
<!--            />-->
            <img style="width: 100px" :src="logo" alt="Logo" class="d-flex" />
<!--            <h1 class="app-logo-title">-->
<!--              reallyvoice-->
<!--            </h1>-->
          </NuxtLink>
        </VCardItem>

        <VCardText>
          <h4 class="text-h4 mb-1">
            Adventure starts here 🚀
          </h4>
          <p class="mb-0">
            Make your app management easy and fun!
          </p>
        </VCardText>

        <VCardText>
          <v-alert
              v-if="alertMessage"
              :type="alertType"
              class="mb-4"
              dense
              border="start"
          >
            {{ alertMessage }}
          </v-alert>

          <VForm @submit.prevent="handleRegister">
            <VRow>
              <!-- Username -->
              <VCol cols="12">
                <VTextField
                    :id="useId()"
                    v-model="form.name"
                    autofocus
                    label="Username"
                />
              </VCol>
              <!-- email -->
              <VCol cols="12">
                <VTextField
                    :id="useId()"
                    v-model="form.email"
                    label="Email"
                    type="email"
                />
              </VCol>

              <!-- password -->
              <VCol cols="12">
                <VTextField
                    :id="useId()"
                    v-model="form.password"
                    label="Password"
                    autocomplete="password"
                    placeholder="············"
                    :type="isPasswordVisible ? 'text' : 'password'"
                    :append-inner-icon="isPasswordVisible ? 'bx-hide' : 'bx-show'"
                    @click:append-inner="isPasswordVisible = !isPasswordVisible"
                />

              </VCol>
              <VCol cols="12">
                <VTextField
                    :id="useId()"
                    v-model="form.password_confirmation"
                    label="Password Confirmation"
                    autocomplete="password"
                    placeholder="············"
                    :type="isPasswordVisible ? 'text' : 'password'"
                    :append-inner-icon="isPasswordVisible ? 'bx-hide' : 'bx-show'"
                    @click:append-inner="isPasswordVisible = !isPasswordVisible"
                />


              </VCol>
              <VCol>
                <VBtn
                    block
                    type="submit"
                >
                  Sign up
                </VBtn>
              </VCol>
              <!-- login instead -->
              <VCol
                  cols="12"
                  class="text-center text-base"
              >
                <span>Already have an account?</span>
                <NuxtLink
                    class="text-primary ms-1"
                    to="/login"
                >
                  Sign in instead
                </NuxtLink>
              </VCol>

<!--              <VCol-->
<!--                  cols="12"-->
<!--                  class="d-flex align-center"-->
<!--              >-->
<!--                <VDivider/>-->
<!--                <span class="mx-4">or</span>-->
<!--                <VDivider/>-->
<!--              </VCol>-->

              <!-- auth providers -->
              <VCol
                  cols="12"
                  class="text-center"
              >
<!--                <AuthProvider/>-->
              </VCol>
            </VRow>
          </VForm>
        </VCardText>
      </VCard>
    </div>
  </div>
</template>

<style lang="scss">
@use "@core/scss/template/pages/page-auth";
</style>
