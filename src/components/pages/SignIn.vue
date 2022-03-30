<template lang="html">
  <form @submit.prevent="submitNickname" class="grid grid-cols-2">
    <h2 class="text-2xl xl:text-3xl text-center col-span-2 mb-10 font-chat-heading tracking-tight select-none">Enter a nickname <br>to start chatting</h2>
    <div class="relative max-w-xs m-auto col-span-2 grid grid-cols-2-auto auto-rows-min">
      <label for="username" class="sr-only">Enter your desired nickname:</label>
      <input ref="nickInput" type="text" @input="updateNickname" v-model="nickname" name="nickname" id="username" required autocomplete="nickname" placeholder="e.g. snarlinger" :disabled="!dependenciesLoaded" :class="`w-${inputWidth} self-center xl:text-lg border-b-2 border-blue-300 font-chat-body px-1 py-0.5 hover:border-blue-400 focus:border-blue-500 focus:outline-none focus:ring focus:ring-blue-600 focus:ring-offset-2`">
      <div class="w-16 h-8">
        <!-- TODO: replace with <Suspense> component -->
        <Transition name="formicon">
          <ProgressBar ref="signInProgress" v-show="isProgressBarVisible" class="w-8 h-full select-none absolute ml-5"
            id="loading"
            :value="loadingValue"
            :loading="submittingNick"
            :loaded="nickApproved"
          />
        </Transition>
        <input v-show="!submittingNick && !nickApproved" type="submit" name="submit" value="Go" :disabled="!isNicknameValid" class="transform -translate-y-1/2 top-1/2 absolute p-1 justify-self-start self-start px-4 ml-2 text-lg xl:text-xl text-center rounded font-chat-body bg-pink-600 disabled:bg-pink-200 hover:bg-pink-500 focus:bg-pink-500 text-white tracking-tight focus:outline-none focus:ring focus:ring-blue-600 hover:cursor-pointer disabled:cursor-not-allowed">
      </div>
      <!-- Accessible form errors -->
      <ul :class="`absolute w-${inputWidth} top-10 select-none space-y-2`">
        <transition name="fade">
          <li v-if="isErrorVisible">{{ errorMsg }}</li>
        </transition>
      </ul>
    </div>
  </form>
</template>

<script>
import StringFunc from './../../string_func.js';
import FetchFunc from './../../fetch_func.js';
import ErrorCodes from './../../error_func.js';
import { store as attrStore, createAttribution as createAttr }
  from './../../attributions.js';

import ProgressBar from './../ProgressBar.vue';

export default {
  name: 'SignIn',
  components: {
    ProgressBar,
  },
  data: function () {
    return {
      // loading state
      dependenciesLoaded: false,
      // nickname config
      nickname: '',
      maxNickLength: 20,
      // binds the width of nickname input and errors list
      inputWidth: 52,
      // error state
      clientErrorCodes: [
        'SIGNIN_INVALIDLENGTH',
        'SIGNIN_INVALIDCHARS',
      ],
      e_codes: null,
      errorID: null,
      // error message
      errorMsg: '',
      errorMsgs: null,
      // submit input state
      submittingNick: false,
      nickApproved: false,
    };
  },
  created () {
    // initialise ErrorCodes class instance
    this.e_codes = new ErrorCodes(this.clientErrorCodes);
    this.e_codes.init()
      .then(() => {
        this.setDependenciesLoaded();
        // set up error messages
        this.errorMsgs = {
          [this.e_codes.get('SIGNIN_INVALIDCHARS')]:
          'We don\'t accept spaces or special characters in nicknames. Sorry!',
          [this.e_codes.get('SIGNIN_INVALIDLENGTH')]:
          'Nicknames can\'t have more than 20 characters. Sorry about that.',
          [this.e_codes.get('NICK_INVALID')]:
          'Sorry, nicknames can\'t have spaces, special characters, or be longer than 20 characters.',
          [this.e_codes.get('NICK_INSERTFAIL')]:
          'That nickname is taken, sorry.',
          [this.e_codes.get('SERVER_ERROR')]:
          'An unknown error occured. Please try again later.',
        };
        this.autoSignIn();
      })
      .catch(e => {
        console.error(e);
        this.setAndLogUnknownError();
      });
    // initialise attribution store
    attrStore.updateAttributions(
      createAttr('Tick', 'Landan Lloyd', 'NounProject.com'),
      createAttr('Loading spinner', 'Leif Michelsen', 'NounProject.com'),
      createAttr('Loading hourglass', 'Icons Producer', 'NounProject.com')
    );
  },
  methods: {
    /**
     * Set this.dependenciesLoaded = true.
     */
    setDependenciesLoaded () {
      this.dependenciesLoaded = true;
    },

    /**
     * Set the current errorID, and by extension, the current error shown
     * on screen.
     *
     * We include this method because with simple property assignment of
     * this.errorID, it's not immediately clear that the assignment has
     * side-effects.
     *
     * @param Number errorID The new error ID. Should match to ID in e_codes.
     */
    setError (errorID) {
      this.errorID = errorID;
    },

    /**
     * Set the current error ID to unknown, and log the passed error.
     *
     * @param Error e The error we're calling an 'unknown error'.
     */
    setAndLogUnknownError (e) {
      this.setError(this.e_codes.get('SERVER_ERROR'));
      console.error(e);
    },

    /**
     * Move the user into the chat room.
     */
    goToChatRoom () {
      this.$router.push({ name: 'chatroom' });
    },

    /**
     * Update the (nickname) input value with a sanitised nickname.
     *
     * (Using StringFunc.isCharacterValid.)
     *
     * @param InputEvent e
     */
    updateNickname (e) {
      // go through each character in the final nickname
      let finalNick = '';
      for (const code of e.target.value) {
        // only keep character if it's valid
        if (StringFunc.isCharacterValid(code)) {
          finalNick += code;
        } else {
          this.setError(this.e_codes.get('SIGNIN_INVALIDCHARS'));
        }
      }
      this.nickname = finalNick;
      if (this.nickname.length > this.maxNickLength) {
        this.setError(this.e_codes.get('SIGNIN_INVALIDLENGTH'));
        this.nickname = this.nickname.substring(0, 20);
      }
    },

    /**
     * Query the server to check if we're already signed in, then act
     * accordingly.
     */
    autoSignIn () {
      const url = 'api/auto_sign_in.php';
      FetchFunc.fetchJSON(url)
        .then(data => {
          if (data.bool) {
            this.goToChatRoom();
          } else if (data.msg === this.e_codes.get('SERVER_ERROR')) {
            this.setError(this.e_codes.get('SERVER_ERROR'));
          } else if (data.msg !== this.e_codes.get('USER_UNAUTHENTICATED')) {
            throw new Error(`Unexpected error code passed: ${data.msg}`); // TODO:
          }
        })
        .catch(e => {
          this.setAndLogUnknownError(e);
        });
    },

    /**
     * Query the server to check if the submitted nickname is valid.
     */
    submitNickname () {
      // TODO: I think there's a Vue feature to do this logic easier?
      if (!this.submittingNick) {
        // turn on loading icon
        this.submittingNick = true;
        // organise POST request
        const url = '/api/sign_in.php';
        const requestBody = `nickname=${this.nickname}`;
        const init = {
          method: 'POST',
          body: requestBody,
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
          },
        };
        const loadingDelay = 250;
        const successDelay = 250;
        // execute POST request
        FetchFunc.fetchJSON(url, init)
          .then(data => {
            console.log(data);
            setTimeout(d => {
              this.submittingNick = false;
              if (!d.bool) {
                this.setError(d.msg);
                this.$refs.nickInput.focus();
              } else {
                this.nickApproved = true;
                setTimeout(() => {
                  this.goToChatRoom();
                }, successDelay);
              }
            }, loadingDelay, data);
          })
          .catch(e => {
            this.setAndLogUnknownError(e);
            // reset vars
            this.submittingNick = false;
            this.nickApproved = false;
          });
      }
    },
  },
  watch: {
    /**
     * When errorID is changed, update error message to match.
     *
     * We don't do this through a computed property, because we can't always
     * return a new value for the current error message. When the error message
     * switches to null, we need the message not to change to ensure the error
     * fades out smoothly.
     *
     * @param Number newID The new error ID. Should match to ID in e_codes.
     */
    errorID (newID) {
      if (newID !== null) {
        this.errorMsg = this.errorMsgs[this.errorID];
        this.$announcer.polite(this.errorMsg);
      }
    },

    submittingNick (newValue) {
      if (newValue) {
        this.$refs.signInProgress.focusProgress();
      }
    },
  },
  computed: {
    /**
     * Returns whether the current nickname is valid or not.
     */
    isNicknameValid () {
      return StringFunc.isNicknameValid(this.nickname);
    },

    /**
     * Returns whether an error should be visible or not.
     */
    isErrorVisible () {
      return this.errorID !== null;
    },

    /**
     * Returns whether the progress bar should be visible.
     */
    isProgressBarVisible () {
      return this.submittingNick || this.nickApproved;
    },

    /**
     * Returns the current value of the progress bar.
     */
    loadingValue () {
      return this.nickApproved ? 1 : 0;
    },
  },
};
</script>

<style scoped>
  .formicon-enter-active,
  .formicon-leave-active {
    transition: opacity 0.1s ease;
  }

  .formicon-enter-from,
  .formicon-leave-to {
    opacity: 0;
  }

  .fade-enter-active,
  .fade-leave-active {
    transition: opacity 0.5s ease;
  }

  .fade-enter-from,
  .fade-leave-to {
    opacity: 0;
  }

  @media (prefers-reduced-motion) {
    .formicon-enter-active,
    .formicon-leave-active,
    .fade-enter-active,
    .fade-leave-active {
      transition: none;
    }
  }
</style>
