<script setup lang="ts">
definePageMeta({ guest: true, layout: 'blank' })

import AuthProvider from '@/views/pages/authentication/AuthProvider.vue'
import logo from '@images/logo.jpg'
import authV1BottomShape from '@images/svg/auth-v1-bottom-shape.svg?url'
import authV1TopShape from '@images/svg/auth-v1-top-shape.svg?url'
import {ref} from "vue";

const isPasswordVisible = ref(false)
const remember = ref(false)
const email = ref('')
const password = ref('')
const loading = ref(false)
const alertMessage = ref('')
const alertType = ref<'error' | 'success'>('error')

const { login, isLoggedIn, errors, processing } = useSanctum()
const alertMsg = ref('')


const handleLogin = async () => {
  loading.value = true
  alertMsg.value = ''
  try {
    await login({ email: email.value, password: password.value, remember: remember.value })
    if (isLoggedIn) {                 // 👈 no `.value`
      await navigateTo('/products')   // or '/'
    }
  } catch (e: any) {
    alertMsg.value = e?.data?.message || 'Login failed'
  } finally {
    loading.value = false
  }
}

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

      <!-- 👉 Auth Card -->
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
<!--              class="d-flex"-->
<!--              v-html="logo"-->
<!--            />-->
            <img style="width: 100px" :src="logo" alt="Logo" class="d-flex" />
<!--            <h1 class="app-logo-title">-->
<!--              scoop-->
<!--            </h1>-->
          </NuxtLink>
        </VCardItem>

        <VCardText>
          <h4 class="text-h4 mb-1">
            Welcome to Scoop! 👋🏻
          </h4>
          <p class="mb-0">
            Please sign-in to your account and start the adventure
          </p>
        </VCardText>

        <VCardText>
          <!-- Error / Success Alert -->
          <v-alert
            v-if="alertMessage"
            :type="alertType"
            class="mb-4"
            dense
            border="start"
          >
            {{ alertMessage }}
          </v-alert>

          <VForm @submit.prevent="handleLogin">
            <VRow>
              <!-- email -->
              <VCol cols="12">
                <VTextField
                  :id="useId()"
                  v-model="email"
                  autofocus
                  label="Email"
                  type="email"
                  required
                />
              </VCol>

              <!-- password -->
              <VCol cols="12">
                <VTextField
                  :id="useId()"
                  v-model="password"
                  label="Password"
                  :type="isPasswordVisible ? 'text' : 'password'"
                  autocomplete="password"
                  :append-inner-icon="isPasswordVisible ? 'bx-hide' : 'bx-show'"
                  @click:append-inner="isPasswordVisible = !isPasswordVisible"
                  required
                />

                <!-- remember me checkbox -->
                <div class="d-flex align-center justify-space-between flex-wrap my-6">
                  <VCheckbox
                    :id="useId()"
                    v-model="remember"
                    label="Remember me"
                  />

                  <a
                    class="text-primary"
                    href="javascript:void(0)"
                  >
                    Forgot Password?
                  </a>
                </div>

                <!-- login button -->
                <VBtn
                  block
                  type="submit"
                  :loading="loading"
                >
                  Login
                </VBtn>
              </VCol>

              <!-- create account -->
              <VCol
                cols="12"
                class="text-body-1 text-center"
              >
                <span class="d-inline-block">
                  New on our platform?
                </span>
                <NuxtLink
                  class="text-primary ms-1 d-inline-block text-body-1"
                  to="/register"
                >
                  Create an account
                </NuxtLink>
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
