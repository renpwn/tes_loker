<template>
  <div class="min-h-screen bg-gradient-to-b from-black via-gray-900 to-black text-gray-100 p-6">
    <div class="max-w-6xl mx-auto space-y-8">
      <!-- Back -->
      <router-link
        to="/dashboard"
        class="inline-block text-blue-400 hover:text-blue-300 transition-colors mb-2"
      >
        ← Back
      </router-link>

      <!-- Header -->
      <div
        class="p-6 rounded-2xl border border-gray-800 bg-gray-900/60 backdrop-blur-md shadow-xl flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4"
      >
        <div>
          <h1
            class="text-4xl font-extrabold bg-gradient-to-r from-blue-400 to-green-400 text-transparent bg-clip-text drop-shadow-lg"
          >
            Job Order #{{ id }}
          </h1>
          <p class="text-gray-400 mt-1">Customer: {{ jobOrder.customer_name || '-' }}</p>
        </div>

        <!-- Track via Map -->
        <button
          @click="trackMap"
          :disabled="!jobOrder.origin || !jobOrder.destination"
          class="bg-gradient-to-r from-green-500 to-emerald-600 text-white font-semibold py-2 px-5 rounded-lg shadow-md hover:from-green-400 hover:to-emerald-500 transition-all disabled:opacity-40 disabled:cursor-not-allowed flex items-center gap-2"
        >
          🗺 Track via Map
        </button>
      </div>

      <!-- Job Info -->
      <div
        class="p-6 rounded-xl border border-gray-800 bg-gray-900/70 backdrop-blur-md shadow-lg"
      >
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
          <!-- Origin + Weather -->
          <div>
            <p class="text-sm text-gray-400">Origin</p>

            <div
              class="mt-4 p-4 rounded-xl border border-blue-700/30 bg-gradient-to-br from-blue-900/40 to-indigo-900/30 backdrop-blur-md shadow-lg"
            >
              <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <!-- Left: Weather icon + city + temperature -->
                <div class="flex flex-col gap-1">
                  <h3 class="text-lg font-semibold text-blue-300 flex items-center gap-1">
                    <span>{{ weather_org?.weather_description != null ? getWeatherIcon(weather_org.weather_description) : '' }}</span>
                    {{ jobOrder.origin || '--' }}
                  </h3>
                  <p class="text-2xl font-bold text-blue-400">
                    {{ weather_org?.temp != null ? weather_org.temp + '°C' : '--' }}
                  </p>
                  <p class="text-xs text-gray-400">
                    Feels like {{ weather_org?.feels_like != null ? weather_org.feels_like + '°C' : '--' }}
                  </p>
                </div>

                <!-- Right: Additional info -->
                <div class="text-gray-400 text-xs space-y-1 text-right">
                  <p>Humidity: {{ weather_org?.humidity != null ? weather_org.humidity + '%' : '--' }}</p>
                  <p>Precipitation: {{ weather_org?.precipitation != null ? weather_org.precipitation + '%' : '--' }}</p>
                  <p>
                    Wind: 
                    {{ weather_org?.wind_speed != null ? (weather_org.wind_speed * 3.6).toFixed(1) + ' km/h' : '--' }}
                    {{ weather_org?.wind_deg != null ? '(' + windDirection(weather_org.wind_deg) + ')' : '' }}
                  </p>
                </div>
              </div>
            </div>
          </div>

          <!-- Destination + Weather (tetap seperti sebelumnya) -->
          <div>
            <p class="text-sm text-gray-400">Destination</p>

            <div
              class="mt-4 p-4 rounded-xl border border-blue-700/30 bg-gradient-to-br from-blue-900/40 to-indigo-900/30 backdrop-blur-md shadow-lg"
            >
              <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <!-- Left: Weather icon + city + temperature -->
                <div class="flex flex-col gap-1">
                  <h3 class="text-lg font-semibold text-blue-300 flex items-center gap-1">
                    <span>{{ weather_des?.weather_description != null ? getWeatherIcon(weather_des.weather_description) : '' }}</span>
                    {{ jobOrder.destination || '--' }}
                  </h3>
                  <p class="text-2xl font-bold text-blue-400">
                    {{ weather_des?.temp != null ? weather_des.temp + '°C' : '--' }}
                  </p>
                  <p class="text-xs text-gray-400">
                    Feels like {{ weather_des?.feels_like != null ? weather_des.feels_like + '°C' : '--' }}
                  </p>
                </div>

                <!-- Right: Additional info -->
                <div class="text-gray-400 text-xs space-y-1 text-right">
                  <p>Humidity: {{ weather_des?.humidity != null ? weather_des.humidity + '%' : '--' }}</p>
                  <p>Precipitation: {{ weather_des?.precipitation != null ? weather_des.precipitation + '%' : '--' }}</p>
                  <p>
                    Wind: 
                    {{ weather_des?.wind_speed != null ? (weather_des.wind_speed * 3.6).toFixed(1) + ' km/h' : '--' }}
                    {{ weather_des?.wind_deg != null ? '(' + windDirection(weather_des.wind_deg) + ')' : '' }}
                  </p>
                </div>
              </div>
            </div>
          </div>

          <!-- Status -->
          <div class="flex flex-col items-center justify-center gap-1">
            <p class="text-sm text-gray-400">Status</p>
            <span
              :class="[
                'inline-block px-3 py-1 sm:px-5 sm:py-2 text-sm sm:text-base font-semibold rounded-full mt-1 text-center',
                jobOrder.status === 'Pending'
                  ? 'bg-yellow-500/20 text-yellow-400 border border-yellow-400/30'
                  : jobOrder.status === 'Ongoing'
                  ? 'bg-blue-500/20 text-blue-400 border border-blue-400/30'
                  : jobOrder.status === 'Delivered' || jobOrder.status === 'Completed'
                  ? 'bg-green-500/20 text-green-400 border border-green-400/30'
                  : 'bg-gray-500/20 text-gray-400 border border-gray-400/30'
              ]"
            >
              {{ jobOrder.status || '-' }}
            </span>
          </div>
        </div>
      </div>
      
      <!-- Shipping Cost -->
      <div class="mt-6 p-6 rounded-xl border border-gray-800 bg-gray-900/70 backdrop-blur-md shadow-lg">
        <button
          @click="showShippingForm = !showShippingForm"
          class="bg-green-500 hover:bg-green-400 text-white font-semibold py-2 px-4 rounded-lg shadow-md transition-colors mb-3"
        >
          {{ showShippingForm ? 'Hide Shipping Cost' : 'Check Shipping Cost' }}
        </button>

        <div v-if="showShippingForm">
          <!-- Form input berat -->
          <form @submit.prevent="fetchShippingCustom">
            <div class="flex gap-2 mb-3 items-center">
              <label class="text-gray-300">Weight (kg):</label>
              <input
                type="number"
                v-model.number="weight"
                min="0.1"
                step="0.1"
                class="p-2 rounded bg-gray-800 text-white w-24"
              />
              <button
                type="submit"
                class="bg-blue-500 hover:bg-blue-400 text-white font-semibold py-1 px-3 rounded transition-colors"
              >
                Get Cost
              </button>
            </div>
          </form>

          <!-- Loading & Results -->
          <div v-if="isLoadingShipping" class="text-gray-400">Loading...</div>
          <div v-else-if="shippingResults.length === 0" class="text-gray-400">No shipping data.</div>
          <div v-else class="space-y-3">
            <div v-for="ship in shippingResults" :key="ship.service" class="p-3 rounded-lg bg-gray-800/50 flex justify-between items-center">
              <div>
                <p class="font-semibold text-gray-200">{{ ship.name }} ({{ ship.code }})</p>
                <p class="text-gray-400 text-sm">{{ ship.service }} - {{ ship.description }}</p>
              </div>
              <div class="text-right">
                <p class="text-green-400 font-bold">Rp {{ ship.cost != null ? ship.cost.toLocaleString() : '-' }}</p>
                <p class="text-gray-400 text-xs">{{ ship.etd || '-' }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>


      <!-- Manifest Table -->
      <div
        class="overflow-x-auto rounded-lg border border-gray-800 bg-gray-900/80 backdrop-blur-md shadow-2xl"
      >
        <table class="min-w-full border-collapse text-sm">
          <thead class="bg-gray-800/80 text-gray-300 uppercase text-xs">
            <tr>
              <th class="p-3 text-left border-b border-gray-700">Item</th>
              <th class="p-3 text-left border-b border-gray-700">Quantity</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="item in manifests"
              :key="item.id"
              class="hover:bg-gray-800/60 even:bg-gray-900/40 transition-colors"
            >
              <td class="p-3 border-b border-gray-800">{{ item.item_name }}</td>
              <td class="p-3 border-b border-gray-800">{{ item.quantity }}</td>
            </tr>
          </tbody>
        </table>
      </div>

    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import axios from 'axios'

const route = useRoute()
const id = route.params.id

const manifests = ref([])
const jobOrder = ref({})
const shippingResults = ref([]) // array untuk hasil ongkir
const weather_des = ref(null)
const weather_org = ref(null)

const weatherMap = [
  { keywords: ["hujan", "rain"], icon: "🌧" },
  { keywords: ["berawan", "cloud"], icon: "⛅" },
  { keywords: ["cerah", "clear"], icon: "☀️" },
  { keywords: ["salju", "snow"], icon: "❄️" },
  { keywords: ["badai", "storm", "thunder"], icon: "🌩" },
];

const getWeatherIcon = (description) => {
  console.log(description)
  if (!description) return "❓";
  const desc = description.toLowerCase();

  const found = weatherMap.find(w => w.keywords.some(k => desc.includes(k)));
  return found ? found.icon : "🌤";
}

const windDirection = (deg) => {
  if (deg == null) return '--';

  const directions = [
    { label: "N", arrow: "↑" },
    { label: "NE", arrow: "↗" },
    { label: "E", arrow: "→" },
    { label: "SE", arrow: "↘" },
    { label: "S", arrow: "↓" },
    { label: "SW", arrow: "↙" },
    { label: "W", arrow: "←" },
    { label: "NW", arrow: "↖" },
  ];

  const index = Math.floor((deg + 22.5) / 45) % 8;
  const dir = directions[index];

  return `${dir.label} ${dir.arrow}`; // Contoh: "W ←"
};

const isLoadingShipping = ref(false);
const showShippingForm = ref(false)
const weight = ref(1) // default 1kg

const fetchShippingCustom = async () => {
  if (!jobOrder.value.origin || !jobOrder.value.destination) return;
  isLoadingShipping.value = true;
  try {
    const res = await axios.get("http://localhost:3000/api/shipping", {
      params: {
        origin: jobOrder.value.origin,
        destination: jobOrder.value.destination,
        weight: weight.value * 1000 // convert kg -> gram
      }
    });
    shippingResults.value = res.data.results || [];
    console.log("Shipping response:", shippingResults.value);
  } catch (err) {
    console.error("Failed to fetch shipping cost:", err);
  } finally {
    isLoadingShipping.value = false;
  }
};


const fetchManifests = async () => {
  try {
    const res = await axios.get(`http://localhost:3000/api/job-orders/${id}/manifests`)
    manifests.value = res.data
  } catch (err) {
    console.error('Error fetching manifests:', err)
  }
}

const fetchJobOrder = async () => {
  try {
    const res = await axios.get(`http://localhost:3000/api/job-orders/${id}`)
    jobOrder.value = res.data
    weather_des.value = await fetchWeather(jobOrder.value.destination)
    weather_org.value = await fetchWeather(jobOrder.value.origin)
    
    // Ambil shipping cost
    await fetchShipping()
  } catch (err) {
    console.error('Error fetching job order:', err)
  }
}

const fetchWeather = async (city) => {
  if (!jobOrder.value.destination) return;
  try {
    const res_d = await axios.get(
      `http://localhost:3000/api/weather/${encodeURIComponent(city)}`
    )
    console.log(res_d)
    return res_d.data
  } catch (err) {
    console.error('Failed to fetch weather:', err)
    return null
  }
}

const trackMap = () => {
  if (!jobOrder.value.origin || !jobOrder.value.destination) {
    alert('Data job order belum lengkap.')
    return
  }
  const origin = encodeURIComponent(jobOrder.value.origin)
  const dest = encodeURIComponent(jobOrder.value.destination)
  window.open(
    `https://www.google.com/maps/dir/?api=1&origin=${origin}&destination=${dest}`,
    '_blank'
  )
}

onMounted(() => {
  fetchManifests()
  fetchJobOrder()
})
</script>
