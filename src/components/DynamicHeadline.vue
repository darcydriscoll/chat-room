<template lang="html">
  <component :is="headlineElement">
    <slot></slot>
  </component>
</template>

<script>
/**
 * Dynamically creates a headline component with the provided level.
 */
export default {
  props: {
    level: {
      type: Number,
      require: true,
    },
  },

  computed: {
    headlineElement () {
      return 'h' + this.level;
    },
  },
};
</script>

<style lang="css" scoped>
</style>
