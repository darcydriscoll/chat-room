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
/**
 * State, logic, and view for a chat message.
 *
 * @author Darcy Driscoll <darcy.driscoll@outlook.com>
 */

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
      // unix timestamp
      type: Number,
      require: true,
    },
    message: {
      type: String,
      require: true,
    },
  },

  data () {
    return {
      msgDate: new Date(this.timestamp * 1000),
      formattedTime: null,
    };
  },

  created () {
    this.formatTime();
  },

  methods: {
    /**
     * Format the imtestamp at different points in the message's lifespan.
     */
    formatTime () {
      // initial format of time
      this.setFormattedTime(this.formatTimestamp({
        hour12: false, hour: '2-digit', minute: '2-digit',
      }));
      // get time until next day
      const tmrwTime = new Date(this.msgDate.getFullYear(),
        this.msgDate.getMonth(), this.msgDate.getDate() + 1);
      const timeUntilNextDay = Math.max(0,
        tmrwTime.getTime() - this.msgDate.getTime());
      // set date format timeout
      setTimeout(() => {
        this.setFormattedTime(this.formatTimestamp({
          month: '2-digit', day: '2-digit',
        }));
        // get time until next year
        const nextYearTime = new Date(this.msgDate.getFullYear() + 1, 0, 1);
        const timeUntilNextYear = Math.max(0,
          nextYearTime.getTime() - this.msgDate.getTime());
        // set year format timeout
        setTimeout(() => {
          this.setFormattedTime(this.formatTimestamp({ year: 'numeric' }));
        }, timeUntilNextYear);
      }, timeUntilNextDay);
    },

    setFormattedTime (newTime) {
      this.formattedTime = newTime;
    },

    /**
     * Format a UNIX timestamp given a set of options.
     *
     * @param Object options Defines what shows up in the formatted string.
     *
     * @return String A formatted string that represents the timestamp
     *  according to the user's locale.
     */
    formatTimestamp (options) {
      return this.msgDate.toLocaleString(undefined, options);
    },
  },
};
</script>

<style lang="css" scoped>
</style>
