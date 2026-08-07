<template>
  <div class="h-screen flex items-center justify-center bg-gradient-to-b from-black via-gray-900 to-black text-gray-100">
    <div class="backdrop-blur-xl bg-gray-900/60 p-8 rounded-2xl shadow-2xl border border-gray-800 w-80 transition-all hover:shadow-blue-700/30">
      <h1 class="text-3xl font-extrabold mb-6 text-center bg-gradient-to-r from-blue-400 to-purple-500 text-transparent bg-clip-text">
        SendPick Login
      </h1>

      <input
        v-model="username"
        type="text"
        placeholder="👤 Username"
        class="w-full p-2 mb-4 rounded-lg bg-gray-800 border border-gray-700 text-gray-100 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all"
      />

      <button
        @click="login"
        class="w-full py-2 rounded-lg bg-gradient-to-r from-blue-500 to-purple-600 font-semibold text-white shadow-md hover:from-blue-400 hover:to-purple-500 hover:shadow-lg transition-all"
      >
        Login
      </button>

      <p class="text-center text-sm text-gray-400 mt-4">
        © 2025 <span class="text-blue-400">SendPick</span>
      </p>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()
const username = ref('')

function login() {
  if (!username.value) return alert('Masukkan username!')
  localStorage.setItem('user', username.value)
  router.push('/dashboard')
}
</script>