<script setup>
import { defineProps } from 'vue'
import { RouterLink } from 'vue-router'

defineProps({
  current_page: Number,
  prev_url: String,
  next_url: String,
})
</script>
<template>
  <div class="join">
    <button class="join-item btn" :disabled="!prev_url">
      <template v-if="prev_url">
        <RouterLink :to="prev_url">«</RouterLink>
      </template>
      <template v-else> « </template>
    </button>
    <button class="join-item btn">Page {{ current_page }}</button>
    <button class="join-item btn" :disabled="!next_url">
      <template v-if="next_url">
        <RouterLink :to="next_url">»</RouterLink>
      </template>
      <template v-else> » </template>
    </button>
  </div>
</template>
