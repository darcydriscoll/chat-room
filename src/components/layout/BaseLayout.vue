<template>
  <Header/>
  <main>
    <slot></slot>
  </main>
  <footer></footer>
</template>

<script>
import Header from './Header.vue';

export default {
  name: 'BaseLayout',
  components: {
    Header,
  },
}
</script>

<style lang="css" scoped>
</style>
