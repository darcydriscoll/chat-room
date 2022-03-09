<template lang="html">
  <div ref="focusTarget">
    <VueSkipTo to="#main" label="Skip to main content" />
    <VueAnnouncer class="sr-only" />
    <BaseLayout>
      <!-- TODO: what does the v-slot value mean? -->
      <router-view v-slot="{ Component, route }">
        <transition :name="route.meta.transitionName" mode="out-in">
          <component :is="Component" :headlineLevel="2" :key="route.path" />
        </transition>
      </router-view>
    </BaseLayout>
  </div>
</template>

<script>
import 'tailwindcss/tailwind.css';
import BaseLayout from './components/layout/BaseLayout.vue';

export default {
  name: 'App',
  components: {
    BaseLayout,
  },
  watch: {
    $route () {
      this.$nextTick(() => {
        // focus on router-view on route transition
        // (based on recommendation: accessible-app.com/pattern/vue/routing)
        const focusTarget = this.$refs.focusTarget;
        focusTarget.setAttribute('tabindex', '-1');
        focusTarget.focus();
        focusTarget.removeAttribute('tabindex');
      });
    },
  },
};
</script>

<style>
  :root {
    --dissolve-duration: 0.3s;
    --dissolve-delay: 0s;
  }

  /** Enter: dissolve, Leave: dissolve down **/
  .dissolve-in-dissolve-down-out-enter-from {
    opacity: 0;
  }

  .dissolve-in-dissolve-down-out-enter-active {
    transition: opacity var(--dissolve-duration) ease var(--dissolve-delay);
  }

  .dissolve-in-dissolve-down-out-enter-to {
    opacity: 1;
  }

  .dissolve-in-dissolve-down-out-leave-from {
    opacity: 1;
    transform: translateY(0);
  }

  .dissolve-in-dissolve-down-out-leave-active {
    transition: opacity var(--dissolve-duration) ease var(--dissolve-delay),
      transform var(--dissolve-duration) ease var(--dissolve-delay);
  }

  .dissolve-in-dissolve-down-out-leave-to {
    opacity: 0;
    transform: translateY(1rem);
  }

  /** Enter: dissolve up, Leave: dissolve **/
  .dissolve-up-in-dissolve-out-enter-from {
    opacity: 0;
    transform: translateY(1rem);
  }

  .dissolve-up-in-dissolve-out-enter-active {
    transition: opacity var(--dissolve-duration) ease var(--dissolve-delay),
      transform var(--dissolve-duration) ease var(--dissolve-delay);
  }

  .dissolve-up-in-dissolve-out-enter-to {
    opacity: 1;
    transform: translateY(0);
  }

  .dissolve-up-in-dissolve-out-leave-from {
    opacity: 1;
  }

  .dissolve-up-in-dissolve-out-leave-active {
    transition: opacity var(--dissolve-duration) ease var(--dissolve-delay);
  }

  .dissolve-up-in-dissolve-out-leave-to {
    opacity: 0;
  }

  /** Dissolve **/
  .dissolve-enter-active,
  .dissolve-leave-active {
    transition: opacity var(--dissolve-duration) ease var(--dissolve-delay);
  }

  .dissolve-enter-from,
  .dissolve-leave-to {
    opacity: 0;
  }

  /** Dissolve up */
  .dissolve-up-enter-active,
  .dissolve-up-leave-active {
    transition: opacity var(--dissolve-duration) ease var(--dissolve-delay),
      transform var(--dissolve-duration) ease var(--dissolve-delay);
  }

  .dissolve-up-enter-from,
  .dissolve-up-leave-to {
    opacity: 0;
    transform: translateY(1rem);
  }

  @media (prefers-reduced-motion) {
    .dissolve-in-dissolve-down-out-enter-active,
    .dissolve-in-dissolve-down-out-leave-active,
    .dissolve-up-in-dissolve-out-enter-active,
    .dissolve-up-in-dissolve-out-leave-active,
    .dissolve-enter-active,
    .dissolve-leave-active,
    .dissolve-up-enter-active,
    .dissolve-up-leave-active {
      transition: none;
    }
  }
</style>
