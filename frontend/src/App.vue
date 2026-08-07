<template>
  <div class="min-h-screen bg-black text-white">
    <router-view />
  </div>
</template>

<script setup>
// Tidak perlu logic di sini, cukup styling global dark mode
</script>

<style>
/* Opsional: Scrollbar dark */
::-webkit-scrollbar {
  width: 8px;
}
::-webkit-scrollbar-thumb {
  background-color: #444;
  border-radius: 4px;
}
::-webkit-scrollbar-thumb:hover {
  background-color: #666;
}

/* Buat transisi halus antar halaman */
body, html {
  transition: background-color 0.3s, color 0.3s;
}
</style>