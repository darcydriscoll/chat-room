<template>
  <BaseLayout>
    <form action="api/sign_in.php" method="post" class="grid grid-cols-2">
      <h2 class="text-2xl xl:text-3xl text-center col-span-2 mb-10 font-chat-heading tracking-tight select-none">Enter a nickname <br>to start chatting</h2>
      <label for="username" class="sr-only">Enter your desired nickname:</label>
      <div class="relative max-w-xs m-auto col-span-2 grid grid-cols-2-auto auto-rows-min">
        <input type="text" name="nickname" id="username" required autocomplete="nickname" placeholder="e.g. snarlinger" maxlength="20" :class="`w-${inputWidth} self-center xl:text-lg border-b-2 border-blue-300 font-chat-body px-1 py-0.5 hover:border-blue-400 focus:border-blue-500 focus:outline-none focus:ring focus:ring-blue-300`">
        <input type="submit" name="submit" value="Go" class="p-1 justify-self-start self-start px-4 ml-2 text-lg xl:text-xl text-center rounded font-chat-body bg-pink-400 disabled:bg-pink-300 hover:bg-pink-500 focus:bg-pink-500 text-white tracking-tight focus:outline-none focus:ring focus:ring-pink-200 hover:cursor-pointer disabled:cursor-not-allowed">
        <!-- Accessible form errors -->
        <ul role="alert" aria-relevant="all" :class="`absolute w-${inputWidth} top-10 select-none space-y-2`">
          <li>That nickname's taken already. Why not try: snarlinger420</li>
        </ul>
      </div>
    </form>
  </BaseLayout>
</template>

<script>
import BaseLayout from './../layout/BaseLayout.vue';

export default {
  name: 'SignIn',
  components: {
    BaseLayout,
  },
  data: function() {
    return {
      // binds the width of nickname input and errors list
      inputWidth: 52,
    }
  }
}
</script>

<style scoped>
</style>
