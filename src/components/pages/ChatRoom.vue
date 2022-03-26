<template>
  <div class="relative border-2 rounded-md border-blue-300 shadow-md lg:shadow-lg text-sm md:text-base h-5/6 mx-3 md:mx-16 lg:mx-72 xl:mx-96 2xl:mx-0 2xl:self-center 2xl:w-160 grid grid-cols-1" style="grid-template-rows: minmax(0, 1fr) auto">
    <!-- message box -->
    <section class="self-end h-full p-1.5 pr-4 pb-3 overflow-y-auto">
      <DynamicHeadline :level="headlineLevel" class="sr-only">Messages</DynamicHeadline>
      <div class="grid auto-rows-auto content-end space-y-3 min-h-full overflow-y-hidden">
        <TransitionGroup name="dissolve-message-up-in-dissolve-out">
          <ChatMessage v-for="message in messages" :key="message.id"
            :headlineLevel="headlineLevel + 1"
            :nickname="message.nickname"
            :timestamp="message.timestamp"
            :message="message.msg"
          ></ChatMessage>
        </TransitionGroup>
      </div>
    </section>
    <!-- send message -->
    <form @submit.prevent="" class="border-t-2 border-blue-300 grid gap-x-0.75" style="grid-template-columns: minmax(0, 1fr) auto">
      <!-- TODO: sr-only - change to something togglable for hard-of-sight people??? -->
      <!-- Message box -->
      <label for="chatbox-message" class="sr-only">Type your message</label>
      <input id="chatbox-message" type="message" name="message" placeholder="Type your message..." class="self-center px-3 w-full h-full focus:outline-none focus:ring focus:ring-blue-600 rounded-bl-md" value="">
      <!-- Submit button -->
      <label for="chatbox-submit" class="sr-only">Submit your message</label>
      <input id="chatbox-submit" type="submit" name="submit" value=" " aria-label="Send message" class="self-center justify-self-end w-14 h-14 focus:outline-none focus:ring focus:ring-blue-600 rounded-br bg-no-repeat bg-pink-400 disabled:bg-pink-200 hover:bg-pink-500 bg-submit-msg bg-50% bg-center-l-offset">
    </form>
  </div>
</template>

<script>
import FetchFunc from './../../fetch_func.js';
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
      // messages
      messages: [],
      getMessageDelay: 1000,
    };
  },

  created () {
    // initialise ErrorCodes class instance
    this.eCodes = new ErrorCodes();
    this.eCodes.init()
      .then(() => {
        // get new message each this.getMessageDelay
        this.getNewMessage();
      });
    // initialise attribution store
    attrStore.updateAttributions(
      createAttr('Send Message', 'Shovy Rahman', 'NounProject.com')
    );
  },

  methods: {
    /**
     * See if there's a new message from the server. If there is, push it into
     * this.messages.
     */
    getNewMessage () {
      const url = '/api/get_new_messages.php';
      return FetchFunc.fetchJSON(url)
        .then(data => {
          if (data.bool) {
            this.messages = [data.msg];
          }
          this.lastId = data.msg.id;
          setTimeout(this.getNewMessage, this.getMessageDelay);
        });
    },
  },

};
</script>

<style lang="css" scoped>

/** Enter: dissolve up, Leave: dissolve **/
.dissolve-message-up-in-dissolve-out-enter-from {
  opacity: 0;
  transform: translateY(10rem);
}

.dissolve-message-up-in-dissolve-out-enter-active {
  transition: opacity var(--dissolve-duration) cubic-bezier(0, 0, 0, 1.0) var(--dissolve-delay),
    transform var(--dissolve-duration) cubic-bezier(0, 0, 0, 1.0) var(--dissolve-delay);
  }

.dissolve-message-up-in-dissolve-out-enter-to {
  opacity: 1;
  transform: translateY(0);
}

.dissolve-message-up-in-dissolve-out-leave-from {
  opacity: 1;
}

.dissolve-message-up-in-dissolve-out-leave-active {
  transition: opacity var(--dissolve-duration) ease var(--dissolve-delay);
}

.dissolve-message-up-in-dissolve-out-leave-to {
  opacity: 0;
}

@media (prefers-reduced-motion) {
  .dissolve-message-up-in-dissolve-out-enter-active,
  .dissolve-message-up-in-dissolve-out-leave-active {
    transition: none;
  }
}

</style>
