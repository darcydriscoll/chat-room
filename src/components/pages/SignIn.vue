<template>
  <BaseLayout>
    <form action="api/sign_in.php" method="post">
      <h2>Enter a nickname to start chatting</h2>
      <label for="username">Enter your desired nickname:</label>
      <input type="text" name="nickname" id="username" required autocomplete="nickname">
      <input type="submit" name="submit" value="Enter">
      <!-- Accessible form errors -->
      <ul role="alert" aria-relevant="all"></ul>
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
}
</script>

<style scoped>
</style>
