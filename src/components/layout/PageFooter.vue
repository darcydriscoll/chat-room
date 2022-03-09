<template>
  <footer class="opacity-90 p-2 select-none text-xs md:text-sm">
    <p class="font-chat-body">Made with 🍩 by <span><a href="https://github.com/darcydriscoll/chat-room" class="underline text-green-700">Darcy D.</a></span></p>
    <p v-if="attributions" class="font-chat-body">Attribution: {{ attributions }}</p>
  </footer>
</template>

<script>
import { store as attrStore, formatAttributions }
  from './../../attributions.js';

export default {
  name: 'PageFooter',

  computed: {
    attributions () {
      return formatAttributions(attrStore.attributions);
    },
  },
};
</script>

<style scoped>
</style>
