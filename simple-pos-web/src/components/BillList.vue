<template>
  <div class="p-6 max-w-6xl mx-auto">
    <h1 class="text-2xl font-bold mb-4">Bills</h1>
    <button
        @click="showModal = true"
        class="mb-4 px-4 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700"
    >
      + New Bill
    </button>
    <div v-if="bills.length">
      <table class="min-w-full bg-white rounded shadow overflow-hidden">
        <thead class="bg-gray-100">
        <tr>
          <th class="text-left p-3">Customer</th>
          <th class="text-left p-3">Status</th>
          <th class="text-left p-3">Currency</th>
          <th class="text-left p-3">Discount %</th>
          <th class="text-left p-3">Issued At</th>
          <th class="text-left p-3">Due At</th>
          <th class="text-right p-3">Total</th>
          <th class="text-right p-3">Discount</th>
          <th class="text-right p-3">Discounted Total</th>
          <th class="text-right p-3">Actions</th>
        </tr>
        </thead>
        <tbody>
        <tr
            v-for="bill in bills"
            :key="bill.id"
            class="border-t hover:bg-gray-50"
        >
          <td class="p-3">{{ bill.attributes.customerName }}</td>
          <td class="p-3">{{ bill.attributes.status }}</td>
          <td class="p-3">{{ bill.attributes.currency }}</td>
          <td class="p-3">{{ bill.attributes.discount }} %</td>
          <td class="p-3">{{ formatDate(bill.attributes.issuedAt) }}</td>
          <td class="p-3">{{ formatDate(bill.attributes.dueAt) }}</td>
          <td class="p-3 text-right">
            {{ formatCurrency(bill.attributes.totalAmount, bill.attributes.currency) }}
          </td>
          <td class="p-3 text-right">
            {{ formatCurrency(bill.attributes.discountAmount, bill.attributes.currency) }}
          </td>
          <td class="p-3 text-right">
            {{ formatCurrency(bill.attributes.discountedTotalAmount, bill.attributes.currency) }}
          </td>
          <!-- ✅ Action buttons -->
          <td class="p-3 text-right whitespace-nowrap space-x-2">
            <button @click="viewBill(bill)" class="text-blue-600 hover:underline text-sm">View</button>
            <button @click="editBill(bill)" class="text-yellow-600 hover:underline text-sm">Edit</button>
            <button @click="deleteBill(bill)" class="text-red-600 hover:underline text-sm">Delete</button>

            <button
                v-if="bill.attributes.status === 'draft'"
                @click="updateStatus(bill, 'sent')"
                class="text-indigo-600 hover:underline text-sm"
            >
              Send
            </button>

            <button
                v-if="bill.attributes.status === 'sent'"
                @click="updateStatus(bill, 'paid')"
                class="text-green-600 hover:underline text-sm"
            >
              Pay
            </button>
          </td>
        </tr>
        </tbody>
      </table>
    </div>

    <!-- Modal -->
    <div v-if="showModal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
      <div class="bg-white w-full max-w-3xl p-6 rounded shadow-lg relative">
        <h2 class="text-lg font-semibold mb-4">Create New Bill</h2>

        <!-- Bill Fields -->
        <div class="grid grid-cols-2 gap-4 mb-4">
          <input v-model="form.customerName" type="text" placeholder="Customer Name" class="input" />
          <input v-model="form.currency" type="text" placeholder="Currency (e.g. USD)" class="input" />
          <input v-model="form.discount" type="number" step="0.01" placeholder="Discount %" class="input" />
          <input v-model="form.issuedAt" type="datetime-local" class="input" />
          <input v-model="form.dueAt" type="datetime-local" class="input" />
        </div>

        <!-- Articles -->
        <h3 class="font-semibold mb-2">Articles</h3>
        <div v-for="(article, i) in form.articles" :key="i" class="grid grid-cols-3 gap-2 mb-2">
          <input v-model="article.description" placeholder="Description" class="input" />
          <input v-model.number="article.quantity" type="number" min="1" placeholder="Qty" class="input" />
          <input v-model.number="article.unitPrice" type="number" step="0.01" placeholder="Unit Price" class="input" />
        </div>
        <button @click="addArticle" class="text-blue-600 text-sm hover:underline mb-4">+ Add Article</button>

        <!-- Modal Actions -->
        <div class="flex justify-end gap-2">
          <button @click="showModal = false" class="text-gray-600 hover:underline">Cancel</button>
          <button @click="submitBill" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Create</button>
        </div>
      </div>
    </div>


    <p v-else class="text-gray-600">Loading...</p>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import api from '../lib/api'
import { useRouter } from 'vue-router'
const router = useRouter()

const bills = ref<any[]>([])

onMounted(async () => {
  const res = await api.get('/bills?include=articles')
  bills.value = res.data.data
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

function viewBill(bill: any) {
  router.push({ name: 'bill.show', params: { id: bill.id } })
}

function editBill(bill: any) {
  alert(`Editing bill ID ${bill.id}`)
}

async function deleteBill(bill: any) {
  if (!confirm(`Are you sure you want to delete bill #${bill.id}?`)) return
  try {
    await api.delete(`/bills/${bill.id}`)
    bills.value = bills.value.filter(b => b.id !== bill.id)
  } catch (error) {
    console.error(error)
    alert('Failed to delete bill.')
  }
}

async function updateStatus(bill: any, newStatus: 'sent' | 'paid') {
  try {
    const res = await api.patch(`/bills/${bill.id}`, {
      data: {
        type: 'bills',
        id: bill.id,
        attributes: {
          status: newStatus,
        }
      }
    })
    const updated = res.data.data
    const index = bills.value.findIndex(b => b.id === bill.id)
    if (index !== -1) bills.value[index] = updated
  } catch (error) {
    console.error(error)
    alert(`Failed to mark bill as ${newStatus}.`)
  }
}

const showModal = ref(false)

const form = ref({
  customerName: '',
  currency: 'USD',
  discount: 0,
  issuedAt: new Date().toISOString().slice(0, 16),
  dueAt: new Date(Date.now() + 7 * 86400000).toISOString().slice(0, 16),
  articles: [
    { description: '', quantity: 1, unitPrice: 0 }
  ]
})

function addArticle() {
  form.value.articles.push({ description: '', quantity: 1, unitPrice: 0 })
}

async function submitBill() {
  try {
    // Step 1: Create the bill
    const billRes = await api.post('/bills', {
      data: {
        type: 'bills',
        attributes: {
          customerName: form.value.customerName,
          currency: form.value.currency,
          discount: form.value.discount,
          status: 'draft',
          issuedAt: new Date(form.value.issuedAt).toISOString(),
          dueAt: new Date(form.value.dueAt).toISOString(),
        }
      }
    })

    const billId = billRes.data.data.id

    // Step 2: Create articles for that bill
    for (const article of form.value.articles) {
      await api.post('/articles', {
        data: {
          type: 'articles',
          attributes: {
            description: article.description,
            quantity: article.quantity,
            unitPrice: article.unitPrice,
          },
          relationships: {
            bill: {
              data: { type: 'bills', id: billId }
            }
          }
        }
      })
    }

    // Refresh list
    const newBill = await api.get(`/bills/${billId}?include=articles`)
    bills.value.unshift(newBill.data.data)

    // Close modal and reset
    showModal.value = false
    window.location.reload()
  } catch (e: any) {
    if (e.response && e.response.data && e.response.data.errors) {
      console.error('Validation errors:', e.response.data.errors)
      alert(
          e.response.data.errors.map((err: any) => err.detail).join('\n')
      )
    } else {
      console.error(e)
      alert('Unexpected error.')
    }
  }
}


</script>
