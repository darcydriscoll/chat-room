<template>
  <div class="relative border-2 rounded-md border-blue-300 shadow-md lg:shadow-lg text-sm md:text-base h-full my-8 md:my-16 lg:my-20 xl:my-12 2xl:my-20 mx-3 md:mx-16 lg:mx-72 xl:mx-96 2xl:mx-0 2xl:self-center 2xl:w-160 grid grid-cols-1" style="grid-template-rows: minmax(0, 1fr) auto">
    <!-- message box -->
    <section class="self-end p-1.5 pr-4">
      <DynamicHeadline :level="headlineLevel" class="sr-only">Messages</DynamicHeadline>
      <div class="space-y-3">
        <!-- Message 1 -->
        <ChatMessage
          :headlineLevel="headlineLevel + 1"
          nickname="Darcy"
          :timestamp="1647070853"
          message="Hey everyone. How's it all going?"
        ></ChatMessage>
        <ChatMessage
          :headlineLevel="headlineLevel + 1"
          nickname="Darcy"
          :timestamp="1647070853"
          message="Hey everyone. How's it all going?"
        ></ChatMessage>
        <!-- Message 2 -->
        <ChatMessage
          :headlineLevel="headlineLevel + 1"
          nickname="Sarah"
          :timestamp="1647070853"
          message="Pretty good Darcy. I just got done implementing the
            chat message generation feature of my web app. How are you doing?"
        ></ChatMessage>
      </div>
    </section>
    <!-- send message -->
    <form @submit.prevent="" class="border-t-2 mt-3 border-blue-300 grid" style="grid-template-columns: minmax(0, 1fr) auto">
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
import ChatMessage from './../ChatMessage.vue';

export default {
  name: 'ChatRoom',

  components: {
    DynamicHeadline,
    ChatMessage,
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
