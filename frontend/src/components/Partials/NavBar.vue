<script setup>
import { ref } from 'vue'
import { RouterLink } from 'vue-router'
const term = ref('')
</script>

<template>
  <header>
    <div class="navbar bg-base-100 shadow-sm px-4">
      <div class="flex-1">
        <a class="btn btn-ghost text-xl" href="/">daisyUI</a>
      </div>

      <div class="flex-none" v-if="$route.path === '/'">
        <input
          type="text"
          placeholder="Search"
          class="input input-bordered w-32 md:w-48"
          v-model="term"
          @keyup.enter="$emit('search', term)"
        />
      </div>

      <div class="flex-none ml-4">
        <nav class="flex gap-4">
          <RouterLink to="/" class="btn btn-link">Home</RouterLink>
          <RouterLink to="/create" class="btn btn-link">Create</RouterLink>
        </nav>
      </div>
    </div>
  </header>
</template>
