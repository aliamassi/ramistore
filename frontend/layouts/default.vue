<script lang="ts" setup>
import DefaultLayoutWithVerticalNav from './components/DefaultLayoutWithVerticalNav.vue'
import { ref, provide } from 'vue'

const alertMessage = ref('')
const alertType = ref('success')

// ✅ provide a global alert setter
function setAlert(message, type = 'success') {
  alertMessage.value = message
  alertType.value = type

  // auto-clear after 4s (optional)
  setTimeout(() => {
    alertMessage.value = ''
  }, 4000)
}

provide('setAlert', setAlert)

</script>

<template>
  <DefaultLayoutWithVerticalNav>
    <!-- Global Alert -->
    <v-alert
        v-if="alertMessage"
        :type="alertType"
        class="mb-4 top-alert"
        dense
        border="start"
    >
      {{ alertMessage }}
    </v-alert>
    <slot />
  </DefaultLayoutWithVerticalNav>
</template>

<style lang="scss">
// As we are using `layouts` plugin we need its styles to be imported
@use "@layouts/styles/default-layout";
.top-alert{
  position: fixed !important;
  top: 16px;
  left: 80%;
  transform: translateX(-50%);
  width: min(520px, calc(100vw - 32px));
  z-index: 2147483647;      /* higher than any Vuetify overlay */
  pointer-events: none;     /* clicks pass through except the alert */
}
.top-alert :deep(.v-alert){
  pointer-events: auto;
}
</style>
