import { createRouter, createWebHistory } from 'vue-router'
import LoginPage from '../pages/LoginPage.vue'
import Dashboard from '../pages/Dashboard.vue'
import JobOrderDetail from '../pages/JobOrderDetail.vue'

const routes = [
  { path: '/', component: LoginPage },
  { path: '/dashboard', component: Dashboard },
  { path: '/job/:id', component: JobOrderDetail, props: true },
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

export default router