<script setup>
import ContactIcon from '@/components/Icons/ContactIcon.vue'
import NavBar from '@/components/Partials/NavBar.vue'
import PaginationView from '@/components/Partials/PaginationView.vue'
import { onMounted, ref } from 'vue'

const data = ref([])
const current_page = ref(null)
const prev_page_url = ref(null)
const next_page_url = ref(null)
const showAddNumberModal = ref(false)
const showEditModal = ref(false)
const addNumberContactId = ref(null)
const newPhoneNumber = ref('')
const editContact = ref({ id: null, name: '', old_number: '', new_number: '' })

const fetchContacts = async (url = '/api/contact') => {
  const res = await fetch(url)
  const parsed_res = await res.json()
  data.value = parsed_res.data

  current_page.value = parsed_res.current_page
  prev_page_url.value = parsed_res.prev_page_url
  next_page_url.value = parsed_res.next_page_url
}

onMounted(fetchContacts)

const handleAddNumber = (contactId) => {
  addNumberContactId.value = contactId
  newPhoneNumber.value = ''
  showAddNumberModal.value = true
}

const handleEdit = (contactId, number) => {
  const contact = data.value.find((c) => c.id === contactId)

  if (contact) {
    editContact.value = {
      id: contact.id,
      name: contact.name,
      old_number: number.number,
      new_number: number.number,
    }
    showEditModal.value = true
  }
}

const submitAddNumber = async () => {
  const url = `/api/contact/${addNumberContactId.value}/add`

  const res = await fetch(url, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ number: newPhoneNumber.value }),
  })

  if (res.ok) {
    showAddNumberModal.value = false
    await fetchContacts()
  }
}

const submitEdit = async () => {
  const url = `/api/contact/${editContact.value.id}`
  const res = await fetch(url, {
    method: 'PUT',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
      name: editContact.value.name,
      old_number: editContact.value.old_number,
      new_number: editContact.value.new_number,
    }),
  })
  if (res.ok) {
    showEditModal.value = false
    await fetchContacts()
  }
}

const handleDelete = async (id) => {
  const url = '/api/contact/' + id
  const res = await fetch(url, { method: 'DELETE' })
  await fetchContacts()
  console.log(res)
}

const handleSearch = (term) => {
  const url = '/api/contact?search=' + term
  fetchContacts(url)
}
</script>

<template>
  <NavBar @search="handleSearch" />
  <main class="flex flex-col items-center justify-center max-h-700px">
    <div v-for="item in data" :key="item.id" class="card card-border bg-base-100 w-96">
      <div class="card-body items-center text-center mb-2">
        <h2 class="card-title">{{ item.name }}</h2>
        <ul>
          <li
            v-for="number in item.phone_number"
            :key="number.id"
            class="flex items-center gap-2 mt-2"
          >
            <ContactIcon />
            <h2>{{ number.number }}</h2>
            <button @click="handleEdit(item.id, number)" class="btn btn-xs btn-primary ml-2">
              Edit
            </button>
          </li>
        </ul>
        <div class="card-actions justify-center">
          <button @click="handleAddNumber(item.id)" class="btn btn-primary">
            Add Phone Number
          </button>
          <button @click="handleDelete(item.id)" class="btn btn-secondary">Delete</button>
        </div>
      </div>
    </div>

    <div v-if="showAddNumberModal" class="modal modal-open">
      <div class="modal-box">
        <h3 class="font-bold text-lg">Add Phone Number</h3>
        <div class="py-2">
          <label>Phone Number:</label>
          <input v-model="newPhoneNumber" class="input input-bordered w-full" />
        </div>
        <div class="modal-action">
          <button class="btn" @click="showAddNumberModal = false">Cancel</button>
          <button class="btn btn-accent" @click="submitAddNumber">Add</button>
        </div>
      </div>
    </div>

    <div v-if="showEditModal" class="modal modal-open">
      <div class="modal-box">
        <h3 class="font-bold text-lg">Edit Contact</h3>
        <div class="py-2">
          <label>Name:</label>
          <input v-model="editContact.name" class="input input-bordered w-full" />
        </div>
        <div class="py-2">
          <label>Old Number:</label>
          <input v-model="editContact.old_number" class="input input-bordered w-full" disabled />
        </div>
        <div class="py-2">
          <label>New Number:</label>
          <input v-model="editContact.new_number" class="input input-bordered w-full" />
        </div>
        <div class="modal-action">
          <button class="btn" @click="showEditModal = false">Cancel</button>
          <button class="btn btn-primary" @click="submitEdit">Save</button>
        </div>
      </div>
    </div>
  </main>

  <div class="flex justify-center mt-5">
    <PaginationView
      :current_page="current_page"
      :prev_url="prev_page_url"
      :next_url="next_page_url"
    />
  </div>
</template>
