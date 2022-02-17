<template>
  <BaseLayout>
    <router-view></router-view>
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
</style>
