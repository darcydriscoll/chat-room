<template>
  <div class="h-screen grid grid-cols-1" style="grid-template-rows: auto minmax(0, 1fr) auto;">
    <Header class="self-start"/>
    <main id="main" class="self-center h-full flex flex-col justify-center">
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
