<template>
  <BaseLayout>
    <form action="api/sign_in.php" method="post" class="grid grid-cols-2">
      <h2 class="text-2xl xl:text-3xl text-center col-span-2 mb-10 font-chat-heading tracking-tight select-none">Enter a nickname <br>to start chatting</h2>
      <label for="username" class="sr-only">Enter your desired nickname:</label>
      <div class="relative max-w-xs m-auto col-span-2 grid grid-cols-2-auto auto-rows-min">
        <input type="text" @input="updateNickname" v-model="nickname" name="nickname" id="username" required autocomplete="nickname" placeholder="e.g. snarlinger" :class="`w-${inputWidth} self-center xl:text-lg border-b-2 border-blue-300 font-chat-body px-1 py-0.5 hover:border-blue-400 focus:border-blue-500 focus:outline-none focus:ring focus:ring-blue-300`">
        <input type="submit" name="submit" value="Go" :disabled="!isNicknameValid" class="p-1 justify-self-start self-start px-4 ml-2 text-lg xl:text-xl text-center rounded font-chat-body bg-pink-400 disabled:bg-pink-200 hover:bg-pink-500 focus:bg-pink-500 text-white tracking-tight focus:outline-none focus:ring focus:ring-pink-200 hover:cursor-pointer disabled:cursor-not-allowed">
        <!-- Accessible form errors -->
        <ul role="alert" aria-relevant="all" :class="`absolute w-${inputWidth} top-10 select-none space-y-2`">
          <transition name="fade">
            <li v-if="isErrorVisible">{{ errorMsg }}</li>
          </transition>
        </ul>
      </div>
    </form>
  </BaseLayout>
</template>

<script>
import BaseLayout from './../layout/BaseLayout.vue';
import StringFunc from './../../string_func.js';

export default {
  name: 'SignIn',
  components: {
    BaseLayout,
  },
  data: function() {
    return {
      // nickname config
      nickname: '',
      maxNickLength: 20,
      // binds the width of nickname input and errors list
      inputWidth: 52,
      // error state
      errorID: null,
      errorIDs: {'invalidCharacters' : 0,
                 'invalidLength' : 1,
                 'taken' : 2,
                 'unknown' : 3},
      errorMsg: '',
      errorMsgs: null,
    }
  },
  created() {
    // set up error messages
    this.errorMsgs =
      {[this.errorIDs.invalidCharacters] : 'We don\'t accept spaces or special characters in nicknames. Sorry!',
       [this.errorIDs.invalidLength] : 'Nicknames can\'t have more than 20 characters. Sorry about that.',
       [this.errorIDs.taken] : 'That nickname is taken.',
       [this.errorIDs.unknown] : 'Unknown error.'};
  },
  methods: {
    /**
     * Set the current errorID, and by extension, the current error shown
     * on screen.
     *
     * We include this method because with simple property assignment of
     * this.errorID, it's not immediately clear that the assignment has
     * side-effects.
     *
     * @param Number errorID The new error ID. Should match to ID in errorIDs.
     */
    setError(errorID) {
      this.errorID = errorID;
    },

    /**
     * Update the (nickname) input value with a sanitised nickname.
     *
     * (Using StringFunc.isCharacterValid.)
     *
     * @param InputEvent e
     */
    updateNickname(e) {
      // go through each character in the final nickname
      let finalNick = '';
      for (let code of e.target.value) {
        // only keep character if it's valid
        if (StringFunc.isCharacterValid(code)) {
          finalNick += code;
        } else {
          this.setError(this.errorIDs.invalidCharacters);
        }
      }
      this.nickname = finalNick;
      if (this.nickname.length > this.maxNickLength) {
        this.setError(this.errorIDs.invalidLength);
        this.nickname = this.nickname.substring(0, 20);
      }
    },
  },
  watch: {
    /**
     * When errorID is changed, update errorMsg to match.
     *
     * We don't do this through a computed property, because we can't always
     * return a new value for the current error message. When the error message
     * switches to null, we need the message not to change to ensure the error
     * fades out smoothly.
     *
     * @param Number newID The new error ID. Should match to ID in errorIDs.
     */
    errorID(newID) {
      if (newID !== null) {
        this.errorMsg = this.errorMsgs[this.errorID];
      }
    }
  },
  computed: {
    /**
     * Returns whether the current nickname is valid or not.
     */
    isNicknameValid() {
      return StringFunc.isNicknameValid(this.nickname);
    },

    /**
     * Returns whether an error should be visible or not.
     */
    isErrorVisible() {
      return this.errorID !== null;
    },
  },
}
</script>

<style scoped>
  .fade-enter-active,
  .fade-leave-active {
    transition: opacity 0.5s ease;
  }

  .fade-enter-from,
  .fade-leave-to {
    opacity: 0;
  }
</style>
