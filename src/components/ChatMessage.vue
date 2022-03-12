<template lang="html">
  <article tabindex="0">
    <DynamicHeadline :level="headlineLevel"><span class="sr-only">Message from </span>{{ nickname }}</DynamicHeadline>
    <p>{{ message }}</p>
    <footer>
      <p><span class="sr-only">Message posted at </span>{{ formattedTime }}</p>
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
      return 'hh:mm'; // TODO:
    },
  },
};
</script>

<style lang="css" scoped>
</style>
