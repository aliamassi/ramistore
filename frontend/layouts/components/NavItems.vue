<script lang="ts" setup>
import VerticalNavSectionTitle from "@/@layouts/components/VerticalNavSectionTitle.vue";
import VerticalNavGroup from "@layouts/components/VerticalNavGroup.vue";
import VerticalNavLink from "@layouts/components/VerticalNavLink.vue";
</script>

<template>
  <!-- 👉 Dashboards -->
  <VerticalNavLink
    :item="{
      title: 'Dashboards',
      icon: 'bx-home-smile',
      to: '/dashboard',
    }"
  />

  <!-- 👉 Menu Pages -->
  <VerticalNavGroup
    :item="{
      title: 'Menu',
      icon: 'bx-file',
    }"
  >
    <VerticalNavLink
      :item="{
        title: 'Product page',
        to: '/products',
        href: '/products',
      }"
    />
    <VerticalNavLink
      :item="{
        title: 'Welcome page',
        href: 'https://demos.themeselection.com/sneat-vuetify-nuxtjs-admin-template/demo-1/front-pages/pricing',
        target: '_blank',
      }"
    />
  </VerticalNavGroup>

  <VerticalNavGroup
    :item="{
      title: 'Settings',
      icon: 'bx-cog',
    }"
  >
    <VerticalNavLink
      :item="{
        title: 'Business Information',
        to: '/business-settings',
        // href: '/order-settings',
      }"
    />
  </VerticalNavGroup>

<!--  <VerticalNavGroup-->
<!--    :item="{-->
<!--      title: 'Front Pages',-->
<!--      icon: 'bx-file',-->
<!--      badgeContent: 'Pro',-->
<!--      badgeClass: 'bg-light-primary text-primary',-->
<!--    }"-->
<!--  >-->
<!--    <VerticalNavLink-->
<!--      :item="{-->
<!--        title: 'Landing',-->
<!--        href: 'https://demos.themeselection.com/sneat-vuetify-nuxtjs-admin-template/demo-1/front-pages/landing-page',-->
<!--        target: '_blank',-->
<!--      }"-->
<!--    />-->
<!--    <VerticalNavLink-->
<!--      :item="{-->
<!--        title: 'Pricing',-->
<!--        href: 'https://demos.themeselection.com/sneat-vuetify-nuxtjs-admin-template/demo-1/front-pages/pricing',-->
<!--        target: '_blank',-->
<!--      }"-->
<!--    />-->
<!--    <VerticalNavLink-->
<!--      :item="{-->
<!--        title: 'Payment',-->
<!--        href: 'https://demos.themeselection.com/sneat-vuetify-nuxtjs-admin-template/demo-1/front-pages/payment',-->
<!--        target: '_blank',-->
<!--      }"-->
<!--    />-->
<!--    <VerticalNavLink-->
<!--      :item="{-->
<!--        title: 'Checkout',-->
<!--        href: 'https://demos.themeselection.com/sneat-vuetify-nuxtjs-admin-template/demo-1/front-pages/checkout',-->
<!--        target: '_blank',-->
<!--      }"-->
<!--    />-->
<!--    <VerticalNavLink-->
<!--      :item="{-->
<!--        title: 'Help Center',-->
<!--        href: 'https://demos.themeselection.com/sneat-vuetify-nuxtjs-admin-template/demo-1/front-pages/help-center',-->
<!--        target: '_blank',-->
<!--      }"-->
<!--    />-->
<!--  </VerticalNavGroup>-->

<!--  &lt;!&ndash; 👉 Apps & Pages &ndash;&gt;-->
<!--  <VerticalNavSectionTitle-->
<!--    :item="{-->
<!--      heading: 'Apps & Pages',-->
<!--    }"-->
<!--  />-->
<!--  <VerticalNavLink-->
<!--    :item="{-->
<!--      title: 'Email',-->
<!--      icon: 'bx-envelope',-->
<!--      href: 'https://demos.themeselection.com/sneat-vuetify-nuxtjs-admin-template/demo-1/apps/email',-->
<!--      target: '_blank',-->
<!--      badgeContent: 'Pro',-->
<!--      badgeClass: 'bg-light-primary text-primary',-->
<!--    }"-->
<!--  />-->
<!--  <VerticalNavLink-->
<!--    :item="{-->
<!--      title: 'Chat',-->
<!--      icon: 'bx-chat',-->
<!--      href: 'https://demos.themeselection.com/sneat-vuetify-nuxtjs-admin-template/demo-1/apps/chat',-->
<!--      target: '_blank',-->
<!--      badgeContent: 'Pro',-->
<!--      badgeClass: 'bg-light-primary text-primary',-->
<!--    }"-->
<!--  />-->
<!--  <VerticalNavLink-->
<!--    :item="{-->
<!--      title: 'Calendar',-->
<!--      icon: 'bx-calendar',-->
<!--      href: 'https://demos.themeselection.com/sneat-vuetify-nuxtjs-admin-template/demo-1/apps/calendar',-->
<!--      target: '_blank',-->
<!--      badgeContent: 'Pro',-->
<!--      badgeClass: 'bg-light-primary text-primary',-->
<!--    }"-->
<!--  />-->
<!--  <VerticalNavLink-->
<!--    :item="{-->
<!--      title: 'Kanban',-->
<!--      icon: 'bx-grid',-->
<!--      href: 'https://demos.themeselection.com/sneat-vuetify-nuxtjs-admin-template/demo-1/apps/kanban',-->
<!--      target: '_blank',-->
<!--      badgeContent: 'Pro',-->
<!--      badgeClass: 'bg-light-primary text-primary',-->
<!--    }"-->
<!--  />-->

<!--  <VerticalNavLink-->
<!--    :item="{-->
<!--      title: 'Account Settings',-->
<!--      icon: 'bx-user',-->
<!--      to: '/account-settings',-->
<!--    }"-->
<!--  />-->

<!--  <VerticalNavLink-->
<!--    :item="{-->
<!--      title: 'Login',-->
<!--      icon: 'bx-log-in',-->
<!--      to: '/login',-->
<!--    }"-->
<!--  />-->
<!--  <VerticalNavLink-->
<!--    :item="{-->
<!--      title: 'Register',-->
<!--      icon: 'bx-user-plus',-->
<!--      to: '/register',-->
<!--    }"-->
<!--  />-->

<!--  &lt;!&ndash; 👉 User Interface &ndash;&gt;-->
<!--  <VerticalNavSectionTitle-->
<!--    :item="{-->
<!--      heading: 'User Interface',-->
<!--    }"-->
<!--  />-->
<!--  <VerticalNavLink-->
<!--    :item="{-->
<!--      title: 'Typography',-->
<!--      icon: 'bx-text',-->
<!--      to: '/typography',-->
<!--    }"-->
<!--  />-->
<!--  <VerticalNavLink-->
<!--    :item="{-->
<!--      title: 'Icons',-->
<!--      icon: 'bx-package',-->
<!--      to: '/icons',-->
<!--    }"-->
<!--  />-->
<!--  <VerticalNavLink-->
<!--    :item="{-->
<!--      title: 'Cards',-->
<!--      icon: 'bx-credit-card',-->
<!--      to: '/cards',-->
<!--    }"-->
<!--  />-->

<!--  &lt;!&ndash; 👉 Forms & Tables &ndash;&gt;-->
<!--  <VerticalNavSectionTitle-->
<!--    :item="{-->
<!--      heading: 'Forms & Tables',-->
<!--    }"-->
<!--  />-->
<!--  <VerticalNavLink-->
<!--    :item="{-->
<!--      title: 'Form Layouts',-->
<!--      icon: 'bx-layout',-->
<!--      to: '/form-layouts',-->
<!--    }"-->
<!--  />-->
<!--  <VerticalNavLink-->
<!--    :item="{-->
<!--      title: 'Form Validation',-->
<!--      icon: 'bx-check-circle',-->
<!--      href: 'https://demos.themeselection.com/sneat-vuetify-nuxtjs-admin-template/demo-1/forms/form-validation',-->
<!--      target: '_blank',-->
<!--      badgeContent: 'Pro',-->
<!--      badgeClass: 'bg-light-primary text-primary',-->
<!--    }"-->
<!--  />-->
<!--  <VerticalNavLink-->
<!--    :item="{-->
<!--      title: 'Form Wizard',-->
<!--      icon: 'bx-align-middle',-->
<!--      href: 'https://demos.themeselection.com/sneat-vuetify-nuxtjs-admin-template/demo-1/forms/form-wizard-numbered',-->
<!--      target: '_blank',-->
<!--      badgeContent: 'Pro',-->
<!--      badgeClass: 'bg-light-primary text-primary',-->
<!--    }"-->
<!--  />-->
<!--  <VerticalNavLink-->
<!--    :item="{-->
<!--      title: 'Tables',-->
<!--      icon: 'bx-table',-->
<!--      to: '/tables',-->
<!--    }"-->
<!--  />-->

<!--  &lt;!&ndash; 👉 Others &ndash;&gt;-->
<!--  <VerticalNavSectionTitle-->
<!--    :item="{-->
<!--      heading: 'Others',-->
<!--    }"-->
<!--  />-->
<!--  <VerticalNavLink-->
<!--    :item="{-->
<!--      title: 'Access Control',-->
<!--      icon: 'bx-command',-->
<!--      href: 'https://demos.themeselection.com/sneat-vuetify-nuxtjs-admin-template/demo-1/access-control',-->
<!--      target: '_blank',-->
<!--      badgeContent: 'Pro',-->
<!--      badgeClass: 'bg-light-primary text-primary',-->
<!--    }"-->
<!--  />-->
<!--  <VerticalNavLink-->
<!--    :item="{-->
<!--      title: 'Documentation',-->
<!--      icon: 'bx-file',-->
<!--      href: 'https://demos.themeselection.com/sneat-vuetify-vuejs-admin-template/documentation/',-->
<!--      target: '_blank',-->
<!--    }"-->
<!--  />-->
<!--  <VerticalNavLink-->
<!--    :item="{-->
<!--      title: 'Raise Support',-->
<!--      href: 'https://github.com/themeselection/sneat-vuetify-nuxtjs-admin-template-free/issues',-->
<!--      icon: 'bx-phone',-->
<!--      target: '_blank',-->
<!--    }"-->
<!--  />-->
</template>
