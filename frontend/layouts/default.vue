<!--<script lang="ts" setup>-->
<!--import DefaultLayoutWithVerticalNav from './components/DefaultLayoutWithVerticalNav.vue'-->
<!--import {ref, provide} from 'vue'-->

<!--const alertMessage = ref('')-->
<!--const alertType = ref('success')-->

<!--// ✅ provide a global alert setter-->
<!--function setAlert(message, type = 'success') {-->
<!--  alertMessage.value = message-->
<!--  alertType.value = type-->

<!--  // auto-clear after 4s (optional)-->
<!--  setTimeout(() => {-->
<!--    alertMessage.value = ''-->
<!--  }, 4000)-->
<!--}-->

<!--provide('setAlert', setAlert)-->

<!--</script>-->

<!--<template>-->
<!--  <DefaultLayoutWithVerticalNav>-->
<!--    &lt;!&ndash; Global Alert &ndash;&gt;-->
<!--    <v-alert-->
<!--        v-if="alertMessage"-->
<!--        :type="alertType"-->
<!--        class="mb-4 top-alert"-->
<!--        dense-->
<!--        border="start"-->
<!--    >-->
<!--      {{ alertMessage }}-->
<!--    </v-alert>-->
<!--    <slot/>-->
<!--  </DefaultLayoutWithVerticalNav>-->
<!--</template>-->

<!--<style lang="scss">-->
<!--// As we are using `layouts` plugin we need its styles to be imported-->
<!--@use "@layouts/styles/default-layout";-->

<!--.top-alert {-->
<!--  position: fixed !important;-->
<!--  top: 16px;-->
<!--  left: 80%;-->
<!--  transform: translateX(-50%);-->
<!--  width: min(520px, calc(100vw - 32px));-->
<!--  z-index: 2147483647; /* higher than any Vuetify overlay */-->
<!--  pointer-events: none; /* clicks pass through except the alert */-->
<!--}-->

<!--.top-alert :deep(.v-alert) {-->
<!--  pointer-events: auto;-->
<!--}-->

<!--.v-application__wrap {-->
<!--  background-color: #ffffff !important;-->
<!--}-->

<!--.category-card {-->
<!--  border: thin solid rgba(0, 0, 0, .12) !important;-->
<!--  border-radius: 8px !important;-->
<!--  margin-bottom: 16px !important;-->
<!--}-->

<!--.category-card-header {-->
<!--  background-color: #F0F0F0 !important;-->
<!--}-->

<!--.category-card-header:hover {-->
<!--  cursor: pointer !important;-->
<!--}-->
<!--</style>-->
<style>
.v-list {
  background-color: unset;
}

.top-alert {
  position: fixed !important;
  top: 16px;
  left: 80%;
  width: min(520px, calc(100vw - 32px));
  z-index: 2147483647; /* higher than any Vuetify overlay */
  pointer-events: none; /* clicks pass through except the alert */
}

.top-alert :deep(.v-alert) {
  pointer-events: auto;
}

.v-application__wrap {
  background-color: #ffffff !important;
}

.category-card {
  border: thin solid rgba(0, 0, 0, .12) !important;
  border-radius: 8px !important;
  margin-bottom: 16px !important;
}

.category-card-header {
  background-color: #F0F0F0 !important;
}

.category-card-header:hover {
  cursor: pointer !important;
}

.v-navigation-drawer__content .v-list-item-title, .v-navigation-drawer__content .v-list-item-title a {
  color: #ffffff !important;
}

.bg-white {
  background-color: #ffffff !important;
}
</style>
<template>
  <v-app>
    <AppHeader @toggle-drawer="drawer = !drawer"  :drawer-open="drawer"/>


    <AppSidebar v-model="drawer"/>

    <!-- You can ignore <v-main>; it's just a placeholder -->
    <v-main class="bg-background">
      <v-alert
          v-if="alertMessage"
          :type="alertType"
          class="mb-4 top-alert"
          dense
          border="start"
      >
        {{ alertMessage }}
      </v-alert>

      <div class=" text-medium-emphasis bg-white">
        <slot/>
      </div>

    </v-main>
  </v-app>
</template>

<script setup lang="ts">
import AppHeader from "@/layouts/components/AppHeader.vue";
import AppSidebar from "@/layouts/components/AppSidebar.vue";
import {ref, provide} from 'vue'

const drawer = ref(true)
const alertMessage = ref('')
const alertType = ref('success')

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


