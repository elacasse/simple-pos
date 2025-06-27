<template>
  <div class="p-6 max-w-4xl mx-auto">
    <div v-if="bill" class="bg-white p-6 rounded shadow">
      <h1 class="text-2xl font-bold mb-4">Bill #{{ bill.id }}</h1>
      <p><strong>Customer:</strong> {{ bill.attributes.customerName }}</p>
      <p><strong>Status:</strong> {{ bill.attributes.status }}</p>
      <p><strong>Currency:</strong> {{ bill.attributes.currency }}</p>
      <p><strong>Discount %:</strong> {{ bill.attributes.discount }}%</p>
      <p><strong>Issued At:</strong> {{ formatDate(bill.attributes.issuedAt) }}</p>
      <p><strong>Due At:</strong> {{ formatDate(bill.attributes.dueAt) }}</p>
      <p><strong>Total:</strong> {{ formatCurrency(bill.attributes.totalAmount, bill.attributes.currency) }}</p>
      <p><strong>Discount:</strong> {{ formatCurrency(bill.attributes.discountAmount, bill.attributes.currency) }}</p>
      <p><strong>Discounted total:</strong> {{ formatCurrency(bill.attributes.discountedTotalAmount, bill.attributes.currency) }}</p>

      <div class="mt-6">
        <h2 class="text-lg font-semibold mb-2">Articles</h2>
        <table class="min-w-full border bg-white">
          <thead class="bg-gray-100">
          <tr>
            <th class="text-left p-2">Description</th>
            <th class="text-right p-2">Quantity</th>
            <th class="text-right p-2">Unit Price</th>
            <th class="text-right p-2">Total</th>
          </tr>
          </thead>
          <tbody>
          <tr
              v-for="article in articles"
              :key="article.id"
              class="border-t"
          >
            <td class="p-2">{{ article.attributes.description }}</td>
            <td class="p-2 text-right">{{ article.attributes.quantity }}</td>
            <td class="p-2 text-right">{{ formatCurrency(article.attributes.unitPrice, bill.attributes.currency) }}</td>
            <td class="p-2 text-right">
              {{ formatCurrency(article.attributes.quantity * article.attributes.unitPrice, bill.attributes.currency) }}
            </td>
          </tr>
          </tbody>
        </table>
      </div>
    </div>
    <p v-else class="text-gray-600">Loading...</p>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import api from '../lib/api'

const route = useRoute()
const bill = ref<any>(null)
const articles = ref<any[]>([])

onMounted(async () => {
  const res = await api.get(`/bills/${route.params.id}?include=articles`)
  bill.value = res.data.data
  articles.value = res.data.included?.filter((i: any) => i.type === 'articles') ?? []
})

function formatDate(value: string): string {
  return new Date(value).toLocaleDateString()
}

function formatCurrency(amount: number, currency: string): string {
  return amount ? new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency,
  }).format(amount) : '—'
}
</script>
