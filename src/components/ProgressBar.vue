<template lang="html">
  <div>
    <label :for="id" class="sr-only">Progress:</label>
    <progress
      :ref="id"
      :id="id"
      max=1
      :value="value"
      aria-valuemin=0
      aria-valuemax=1
      :aria-valuenow="value"
      role="progressbar"
      tabindex="-1"
      class="bg-transparent w-full h-full outline-none focus-visible:ring focus-visible:ring-blue-600 focus-visible:ring-offset-4"
    ></progress>
    <div
      class="bg-no-repeat bg-center bg-contain w-full h-full absolute top-0"
      :class="{ 'bg-loading-spinner motion-reduce:bg-loading-motion-reduce motion-safe:animate-spin' : loading,
                'bg-check-mark' : loaded, }"
      :style="{ 'filter: brightness(0) saturate(100%) invert(56%) sepia(97%) saturate(375%) hue-rotate(66deg) brightness(99%) contrast(96%)' : loaded }"
    />
  </div>
</template>

<script>
export default {
  name: 'ProgressBar',

  props: {
    id: {
      type: String,
      required: true,
    },
    value: {
      type: Number,
      required: true,
    },
    loading: {
      type: Boolean,
      required: true,
    },
    loaded: {
      type: Boolean,
      required: true,
    },
  },

  methods: {
    focusProgress () {
      this.$nextTick(() => {
        this.$refs[this.id].focus();
      });
    },
  },

};
</script>

<style lang="css" scoped>

/* TODO: merge into tailwindcss using JIT compiler */
progress[value]::-moz-progress-bar,
progress[value]::-webkit-progress-bar {
  background-color: transparent;
}

</style>
