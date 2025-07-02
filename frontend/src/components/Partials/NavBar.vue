<script setup>
import { RouterLink } from 'vue-router'
</script>

<template>
  <header>
    <div class="navbar bg-base-100 shadow-sm px-4">
      <div class="flex-1">
        <a class="btn btn-ghost text-xl">daisyUI</a>
      </div>

      <div class="flex-none">
        <input type="text" placeholder="Search" class="input input-bordered w-32 md:w-48" />
      </div>

      <div class="flex-none ml-4">
        <nav class="flex gap-4">
          <RouterLink to="/" class="btn btn-link">Home</RouterLink>
          <RouterLink to="/about" class="btn btn-link">About</RouterLink>
        </nav>
      </div>
    </div>
  </header>
</template>
