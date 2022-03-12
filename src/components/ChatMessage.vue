<template lang="html">
  <article tabindex="0" class="grid grid-rows-1 font-chat-body" style="grid-template-columns: auto minmax(0px, 1fr);">
    <div class="order-last">
      <DynamicHeadline :level="headlineLevel" class="float-left mr-2 font-bold font-chat-heading relative top-px md:top-0"><span class="sr-only">Message from </span>{{ nickname }}</DynamicHeadline>
      <p class="pl-3">{{ message }}</p>
    </div>
    <footer class="order-first pr-2 text-2xs leading-4 opacity-60 w-10 text-right">
      <p class="relative top-0.75 md:top-1"><span class="sr-only">Message posted at </span>{{ formattedTime }}</p>
    </footer>
  </article>
</template>

<script>
import DynamicHeadline from './DynamicHeadline.vue';

export default {
  name: 'ChatMessage',

  components: {
    DynamicHeadline,
  },

  props: {
    headlineLevel: {
      type: Number,
      require: true,
    },
    // message attributes
    nickname: {
      type: String,
      require: true,
    },
    timestamp: {
      type: Number,
      require: true,
    },
    message: {
      type: String,
      require: true,
    },
  },

  computed: {
    formattedTime () {
      return '7:27'; // TODO:
    },
  },
};
</script>

<style lang="css" scoped>
</style>
