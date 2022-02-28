<template>
  <div class="h-screen grid grid-cols-1 grid-rows-3">
    <Header class="self-start"/>
    <main class="self-center mx-4">
      <slot></slot>
    </main>
    <Footer class="self-end"/>
  </div>
</template>

<script>
import Header from './PageHeader.vue';
import Footer from './PageFooter.vue';

export default {
  name: 'BaseLayout',
  components: {
    Header,
    Footer,
  },
};
</script>

<style lang="css" scoped>
</style>
