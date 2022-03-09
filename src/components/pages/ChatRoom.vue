<template>
  <div class="relative border-2 rounded-md border-blue-300 shadow-md lg:shadow-lg text-sm md:text-base h-full my-8 md:my-16 lg:my-20 xl:my-12 2xl:my-20 mx-3 md:mx-16 lg:mx-72 xl:mx-96 2xl:mx-0 2xl:self-center 2xl:w-160 grid grid-cols-1">
    <!-- message box -->
    <section class="self-start p-1.5">
      <DynamicHeadline :level="headlineLevel" class="sr-only">Messages</DynamicHeadline>
      <!-- Message 1 -->
      <article>
        <DynamicHeadline :level="headlineLevel+1" class="sr-only">Message</DynamicHeadline>
        <p>Message contents</p>
      </article>
      <!-- Message 2 -->
      <article>
        <DynamicHeadline :level="headlineLevel+1" class="sr-only">Message</DynamicHeadline>
        <p>Message contents</p>
      </article>
    </section>
    <!-- send message -->
    <form @submit.prevent="" class="self-end border-t-2 border-blue-300 grid" style="grid-template-columns: minmax(0, 1fr) auto">
      <!-- TODO: sr-only - change to something togglable for hard-of-sight people??? -->
      <!-- Message box -->
      <label for="chatbox-message" class="sr-only">Type your message</label>
      <input id="chatbox-message" type="message" name="message" placeholder="Type your message..." class="self-center px-3 w-full h-full rounded-bl-md" value="">
      <!-- Submit button -->
      <label for="chatbox-submit" class="sr-only">Submit your message</label>
      <input id="chatbox-submit" type="submit" name="submit" value=" " aria-label="Send message" class="self-center justify-self-end w-14 h-14 rounded-br bg-no-repeat bg-pink-400 disabled:bg-pink-200 hover:bg-pink-500 bg-submit-msg bg-50% bg-center-l-offset">
    </form>
  </div>
</template>

<script>
import ErrorCodes from './../../error_func.js';
import { store as attrStore, createAttribution as createAttr }
  from './../../attributions.js';

import DynamicHeadline from './../DynamicHeadline.vue';

export default {
  name: 'ChatRoom',

  components: {
    DynamicHeadline,
  },

  props: {
    headlineLevel: {
      type: Number,
      require: true,
    },
  },

  data: function () {
    return {
      // error state
      eCodes: null,
    };
  },

  created () {
    // initialise ErrorCodes class instance
    this.eCodes = new ErrorCodes();
    this.eCodes.init();
    // initialise attribution store
    attrStore.updateAttributions(
      createAttr('Send Message', 'Shovy Rahman', 'NounProject.com')
    );
  },

};
</script>

<style lang="css" scoped>
</style>
