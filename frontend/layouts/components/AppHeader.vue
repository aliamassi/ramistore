<template>
  <v-app-bar style="position:fixed;" color="primary" height="56" density="comfortable" flat>
    <!-- Hamburger/Close icon that changes based on drawer state -->
    <v-btn
        icon
        variant="text"
        class="text-white ml-2 menu-btn"
        @click="$emit('toggle-drawer')"
    >
      <i
          :class="drawerOpen ? 'bx bx-dock-left' : 'bx bx-menu'"
          style="font-size: 24px;"
      ></i>
    </v-btn>

    <!-- Brand -->
    <div class="d-flex align-center ml-2">
      <!--      <v-img :src="logo" width="24" height="24" class="mr-2" />-->
      <span class="text-white text-subtitle-1 font-weight-bold">Reallyvoice</span>
    </div>

    <!-- Update plan pill -->
    <!--    <v-btn class="ml-4" size="small" color="white" variant="elevated" rounded="pill">-->
    <!--      <v-icon start>mdi-flash</v-icon>-->
    <!--      Update your Plan-->
    <!--    </v-btn>-->

    <v-spacer />

    <!-- Right-side actions -->
    <v-btn width="48" icon class="text-white" variant="text">
      <v-icon>mdi-bell-outline</v-icon>
    </v-btn>
    <v-btn width="48" icon class="text-white" variant="text">
      <v-icon>mdi-email-outline</v-icon>
    </v-btn>

    <!--    <v-divider inset vertical class="mx-2 opacity-50" />-->

    <v-btn variant="text" class="text-white text-body-2" prepend-icon="mdi-lifebuoy">
      Support
    </v-btn>

    <!--    <v-divider inset vertical class="mx-2 opacity-50" />-->

    <v-menu
        content-class="menu-content">
      <template v-slot:activator="{ props }">
        <v-btn variant="text" class="text-white" v-bind="props">
          <v-avatar size="28" class="mr-2" color="white">
            <span class="text-primary font-weight-bold">{{ user?.name.slice(0, 2) }}</span>
          </v-avatar>
          <span class="text-white mr-1">{{ user.name }}</span>
          <v-icon >mdi-chevron-down</v-icon>
        </v-btn>
      </template>

      <v-list >
        <v-list-item>
          <v-list-item-title>Profile</v-list-item-title>
        </v-list-item>
        <v-list-item>
          <v-list-item-title>Settings</v-list-item-title>
        </v-list-item>
        <v-list-item :disabled="loggingOut" @click="handleLogout">
          <v-list-item-title>
            Logout
          </v-list-item-title>
        </v-list-item>
      </v-list>
    </v-menu>
  </v-app-bar>
</template>

<script setup lang="ts">
import { useSanctum } from '#imports'
import logo from '@/assets/images/logo.jpg'

const props = defineProps<{
  drawerOpen: boolean
}>()

const { logout, user } = useSanctum()
const router = useRouter()
const loggingOut = ref(false)

const handleLogout = async () => {
  if (loggingOut.value) return
  loggingOut.value = true
  try {
    await logout()
    await router.replace('/login')
  } catch (e: any) {
    console.error('Logout failed', e?.data || e)
  } finally {
    loggingOut.value = false
  }
}

defineEmits<{ (e: 'toggle-drawer'): void }>()
</script>

<style scoped>
.v-toolbar {
  box-shadow: 0 2px 4px -1px var(--v-shadow-key-umbra-opacity, rgba(0, 0, 0, .2)),
  0 4px 5px 0 var(--v-shadow-key-penumbra-opacity, rgba(0, 0, 0, .14)),
  0 1px 10px 0 var(--v-shadow-key-ambient-opacity, rgba(0, 0, 0, .12));
}

.opacity-50 {
  opacity: .5;
}

::v-deep(.menu-content) {
  width: 200px !important;
}
::v-deep(.v-btn__overlay) {
  width: 200px !important;
}

</style>
