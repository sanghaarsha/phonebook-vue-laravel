<script setup>
import NavBar from '@/components/Partials/NavBar.vue'
import { ref } from 'vue'
const name = ref('')
const number = ref('')
const success = ref(false)
const error = ref('')

const handleSubmit = async () => {
  success.value = false
  error.value = ''
  try {
    const res = await fetch('/api/contact', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        name: name.value,
        number: number.value,
      }),
    })
    if (!res.ok) {
      const data = await res.json()
      throw new Error(data.message || 'Failed to create contact')
    }
    name.value = ''
    number.value = ''
    success.value = true
  } catch (err) {
    error.value = err.message
  }
}
</script>

<template>
  <NavBar />
  <div class="flex justify-center items-center">
    <form @submit.prevent="handleSubmit" class="w-full max-w-md p-8">
      <div class="form-control mb-6">
        <label class="label font-semibold text-lg">
          <span class="label-text">Name</span>
        </label>
        <input
          v-model="name"
          type="text"
          placeholder="Enter name"
          class="input input-bordered w-full px-4 py-2"
          required
        />
      </div>
      <div class="form-control mb-6">
        <label class="label font-semibold text-lg">
          <span class="label-text">Phone Number</span>
        </label>
        <input
          v-model="number"
          type="text"
          placeholder="Enter phone number"
          class="input input-bordered"
          required
        />
      </div>
      <button type="submit" class="btn btn-primary">Create</button>
      <div v-if="success" class="mt-5 bg-blue-400 text-white">Contact created successfully!</div>
      <div v-if="error" class="mt-5 bg-red-500 text-white">
        {{ error }}
      </div>
    </form>
  </div>
</template>
