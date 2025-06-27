import { createRouter, createWebHistory } from 'vue-router'
import BillList from './components/BillList.vue'
import BillDetail from './components/BillDetail.vue'

const routes = [
    { path: '/', name: 'bills', component: BillList },
    { path: '/bills/:id', name: 'bill.show', component: BillDetail, props: true },
]

export const router = createRouter({
    history: createWebHistory(),
    routes,
})
