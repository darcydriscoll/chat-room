<template>
  <div class="border-2 rounded-md border-blue-300 shadow-md lg:shadow-lg text-sm md:text-base h-full my-8 md:my-16 lg:my-20 xl:my-12 2xl:my-20 mx-3 md:mx-16 lg:mx-72 xl:mx-96 2xl:mx-0 2xl:self-center 2xl:w-160 grid grid-cols-1">
    <!-- message box -->
    <section class="self-start p-1.5">
      <h2 class="sr-only">Messages</h2>
      <!-- Message 1 -->
      <article>
        <h3 class="sr-only">Message</h3>
        <p>Message contents</p>
      </article>
      <!-- Message 2 -->
      <article>
        <h3 class="sr-only">Message</h3>
        <p>Message contents</p>
      </article>
    </section>
    <!-- send message -->
    <form @submit.prevent="" class="self-end border-t-2 border-blue-300 grid" style="grid-template-columns: minmax(0, 1fr) auto">
      <!-- TODO: sr-only - change to something togglable for hard-of-sight people??? -->
      <!-- Message box -->
      <label for="chatbox-message" class="sr-only">Type your message</label>
      <input id="chatbox-message" type="message" name="message" placeholder="Type your message..." class="self-center px-3 w-full h-full rounded-bl-md hover:border-blue-400 focus:border-blue-500 focus:outline-none focus:ring focus:ring-blue-300" value="">
      <!-- Submit button -->
      <label for="chatbox-submit" class="sr-only">Submit your message</label>
      <input id="chatbox-submit" type="submit" name="submit" value=" " class="self-center justify-self-end w-14 h-14 rounded-br bg-no-repeat bg-pink-400 disabled:bg-pink-200 hover:bg-pink-500 bg-submit-msg bg-50% bg-center-l-offset">
    </form>
  </div>
</template>

<script>
import FetchFunc from './../../fetch_func.js';
import ErrorCodes from './../../error_func.js';

export default {
  name: 'ChatRoom',

  data: function () {
    return {
      // error state
      eCodes: null,
    };
  },

  created () {
    // initialise ErrorCodes class instance
    this.eCodes = new ErrorCodes();
    this.eCodes.init()
      .then(() => {
        // see if we're signed in
        this.verifyAccess();
      })
      .catch(e => {
        console.error(e);
      });
  },

  methods: {
    /**
     * Query the server to check if we're already signed in.
     * If we're not, go back to the home page.
     */
    verifyAccess () {
      const url = 'api/auto_sign_in.php';
      FetchFunc.fetchJSON(url)
        .then(data => {
          if (!data.bool && data.msg === null) {
            this.$router.push({ name: 'home' });
          } else if (!data.bool) {
            console.error(`An unknown error was passed from the server: ${data.msg}`);
          }
        })
        .catch(e => {
          console.error(e);
        });
    },
  },

};
</script>

<style lang="css" scoped>
</style>
