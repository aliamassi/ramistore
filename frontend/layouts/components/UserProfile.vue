<script setup lang="ts">
import { useSanctum } from '#imports'
import avatar1 from '@images/avatars/avatar-1.png'
const { logout,user } = useSanctum()
const router = useRouter()
const loggingOut = ref(false)
const handleLogout = async () => {
  if (loggingOut.value) return
  loggingOut.value = true
  try {
    await logout()                            // POST /panel/logout (+ CSRF)
    // optional: clear any local stores here
    await router.replace('/login')            // reliable programmatic nav
    // ultimate fallback if something intercepts:
    // window.location.href = '/login'
  } catch (e:any) {
    console.error('Logout failed', e?.data || e)
  } finally {
    loggingOut.value = false
  }
}
</script>
<template>
  <VBadge
    dot
    location="bottom right"
    offset-x="3"
    offset-y="3"
    color="success"
    bordered
  >
    <VAvatar
      class="cursor-pointer"
      color="primary"
      variant="tonal"
    >
      <VImg :src="avatar1" />

      <!-- SECTION Menu -->
      <VMenu
        activator="parent"
        width="230"
        location="bottom end"
        offset="14px"
      >
        <VList>
          <!-- 👉 User Avatar & Name -->
          <VListItem>
            <template #prepend>
              <VListItemAction start>
                <VBadge
                  dot
                  location="bottom right"
                  offset-x="3"
                  offset-y="3"
                  color="success"
                >
                  <VAvatar
                    color="primary"
                    variant="tonal"
                  >
                    <VImg :src="avatar1" />
                  </VAvatar>
                </VBadge>
              </VListItemAction>
            </template>

            <VListItemTitle class="font-weight-semibold">
              {{  user?.name }}
            </VListItemTitle>
            <VListItemSubtitle>Admin</VListItemSubtitle>
          </VListItem>
          <VDivider class="my-2" />

          <!-- 👉 Profile -->
          <VListItem link>
            <template #prepend>
              <VIcon
                class="me-2"
                icon="bx-user"
                size="22"
              />
            </template>

            <VListItemTitle>Profile</VListItemTitle>
          </VListItem>

          <!-- 👉 Settings -->
          <VListItem link href="/business-settings">
            <template #prepend>
              <VIcon
                class="me-2"
                icon="bx-cog"
                size="22"
              />
            </template>

            <VListItemTitle>Settings</VListItemTitle>
          </VListItem>


          <!-- Divider -->
          <VDivider class="my-2" />

          <!-- 👉 Logout -->

          <VListItem
              role="button"
              tabindex="0"
              :disabled="loggingOut"
              @click="handleLogout"
          >
            <template #prepend>
              <VIcon class="me-2" icon="bx-log-out" size="22" />
            </template>
            <VListItemTitle>Logout</VListItemTitle>
          </VListItem>
        </VList>
      </VMenu>
      <!-- !SECTION -->
    </VAvatar>
  </VBadge>
</template>
