<template>
  <BaseLayout>
    <!-- TODO: what does the v-slot value mean? -->
    <router-view v-slot="{ Component, route }">
      <Transition :name="route.meta.transition">
        <component :is="Component" :key="route.path" />
      </Transition>
    </router-view>
  </BaseLayout>
</template>

<script>
import "tailwindcss/tailwind.css";
import BaseLayout from "./components/layout/BaseLayout"

export default {
  name: 'App',
  components: {
    BaseLayout,
  },
}
</script>

<style>
  .signin-enter-active,
  .signin-leave-active {
    transition: opacity 0.5s ease 0.25s;
  }

  .signin-enter-from,
  .signin-leave-to {
    opacity: 0;
  }
</style>
