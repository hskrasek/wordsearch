<script setup>
// eslint-disable-next-line vue/require-prop-types
defineProps(['message']);
</script>

<template>
  <div v-show="message">
    <p class="text-sm text-red-600">
      {{ message }}
    </p>
  </div>
</template>
