<template>
  <v-navigation-drawer
      v-model="model"
      :width="224"
      flat
      :border="0"
      class="sidebar text-white"
      color="primary"
      style="position:fixed;"
  >
    <div class="h-100 d-flex flex-column">


      <!-- ===== LIST ===== -->
      <v-list nav color="primary" class="text-white" v-model:opened="openedGroups">

        <v-divider class="mx-4 my-2 divider-light" />


        <!-- Menu (expanded with children) -->
        <v-list-group
            value="Menu"
            class="group is-open mt-1"
            :expand-icon="'mdi-chevron-down'"
            :collapse-icon="'mdi-chevron-up'"
        >
          <template #activator="{ props }">
            <v-list-item v-bind="props" class="group-activator">
              <template #prepend>
                <div class="menu-square">
                  <v-icon size="14">mdi-silverware-fork-knife</v-icon>
                </div>
              </template>
              <v-list-item-title class="text-white ">
                Menu
              </v-list-item-title>
            </v-list-item>
          </template>

          <!-- Children -->
          <v-list-item
              class="child-item"
              :class="activeKey === 'product' ? 'active-child' : ''"
              @click="activeKey = 'product'"
          >
            <v-list-item-title>
              <nuxt-link to="/products">
                Product page
              </nuxt-link>

            </v-list-item-title>
          </v-list-item>

          <v-list-item
              class="child-item"
              :class="activeKey === 'welcome' ? 'active-child' : ''"
              @click="activeKey = 'welcome'"
          >
            <v-list-item-title>Welcome page</v-list-item-title>
          </v-list-item>

          <v-list-item
              class="child-item"
              :class="activeKey === 'ordering' ? 'active-child' : ''"
              @click="activeKey = 'ordering'"
          >
            <v-list-item-title>Ordering settings</v-list-item-title>
          </v-list-item>

          <v-list-item
              class="child-item"
              :class="activeKey === 'sliders' ? 'active-child' : ''"
              @click="activeKey = 'sliders'"
          >
            <v-list-item-title>
              <nuxt-link to="/sliders">
                Sliders
              </nuxt-link>
            </v-list-item-title>
          </v-list-item>
        </v-list-group>
        <v-list-group
            value="Settings"
            class="group is-open mt-1"
            :expand-icon="'mdi-chevron-down'"
            :collapse-icon="'mdi-chevron-up'"
        >
          <template #activator="{ props }">
            <v-list-item v-bind="props" class="group-activator">
              <template #prepend>
                <v-icon size="20">mdi-cog</v-icon>
              </template>
              <v-list-item-title class="text-white font-weight-bold">
                Settings
              </v-list-item-title>
            </v-list-item>
          </template>

          <!-- Children -->
          <v-list-item
              class="child-item"
              :class="activeKey === 'business-settings' ? 'active-child' : ''"
              @click="activeKey = 'business-settings'"
          >
            <v-list-item-title>
              <nuxt-link to="/business-settings">
                Business Information
              </nuxt-link>

            </v-list-item-title>
          </v-list-item>

        </v-list-group>
      </v-list>

      <!-- Footer buttons (kept for layout balance, optional) -->

    </div>
  </v-navigation-drawer>
</template>

<script setup lang="ts">
const model = defineModel<boolean>({ default: true })
const route = useRoute()

// Use ref instead of reactive for better v-model binding
const openedGroups = ref<string[]>([])

const activeKey = ref<'product' | 'welcome' | 'ordering'| 'business-settings' | 'sliders'>('product')

// Watch route and open menu group if on a menu-related page
watch(() => route.path, (newPath) => {
  if (newPath.includes('/products')) {
    activeKey.value = 'product'
    openedGroups.value = ['Menu']
  } else if (newPath.includes('/welcome')) {
    activeKey.value = 'welcome'
    openedGroups.value = ['Menu']
  } else if (newPath.includes('/ordering')) {
    activeKey.value = 'ordering'
    openedGroups.value = ['Menu']
  } else if (newPath.includes('/business-settings')) {
    activeKey.value = 'business-settings'
    openedGroups.value = ['Settings']
  }
}, { immediate: true })


</script>

<style scoped>
/* --- Drawer background & general look --- */
.sidebar :deep(.v-navigation-drawer__content) {
  color: #fff;
}

.v-list-group__items{
  display: block !important;
}
/* dashed "progress" placeholder to match top of your crop */
.progress-box {
  border: 2px dashed rgba(255,255,255,.6);
  height: 30px;
  border-radius: 10px;
  position: relative;
  overflow: hidden;
}
.progress-box .bar {
  position: absolute; top: 3px; left: 18px; right: 18px; height: 6px;
  background: rgba(255,255,255,.75);
  border-radius: 4px;
}

/* light divider under Orders POS */
.divider-light {
  opacity: .25;
  border-color: rgba(255,255,255,.35) !important;
}

/* Common icon container */
.icon-box {
  width: 28px; height: 28px;
  display: grid; place-items: center;
  border-radius: 6px;
  background: transparent;
  position: relative;
}

/* Small orange dot on Orders POS */
.orders-pos .icon-box .dot {
  width: 12px; height: 12px; border-radius: 50%;
  background: #FF9F1A;               /* orange */
  position: absolute; left: -8px; top: 12px;
  box-shadow: 0 0 0 2px rgba(255,255,255,.25);
}

/* Group headers */
.group-activator {
  color: #fff !important;
}
.group :deep(.v-list-item__append .v-icon) {
  color: #fff;
  opacity: .9;
}

/* Menu square icon (white outlined box) */
.menu-square {
  width: 20px; height: 20px;
  display: grid; place-items: center;
  border: 2px solid rgba(255,255,255,.9);
  border-radius: 6px;
  margin-right: 5px;
}

/* Child items styling (indent + subtle divider) */
.child-item {
  color: #ffffff;
  padding-left: 32px !important;
  min-height: 42px;
  position: relative;
}
.child-item + .child-item {
  border-top: 1px solid rgba(255,255,255,.08);
}

/* Active child: bold & cyan left indicator */
.active-child {
  font-weight: 700;
}
.active-child::before {
  content: "";
  position: absolute;
  left: 12px;
  top: 6px;
  bottom: 6px;
  width: 3px;
  border-radius: 3px;
  background: #26E0D6; /* cyan */
}

/* Hover state to feel like the app */
.nav-item:hover,
.group-activator:hover,
.child-item:hover {
  background: rgba(255,255,255,.06);
}
.v-list-item--link:hover {
  background: linear-gradient(90deg, #0047a3, #006eff);
}
.v-list-item-title {
  font-size: 14px !important;
  font-weight: 400  !important;
}
</style>
