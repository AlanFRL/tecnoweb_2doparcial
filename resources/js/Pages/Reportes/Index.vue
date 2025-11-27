<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head } from '@inertiajs/vue3'
import { computed } from 'vue'

// Chart.js + vue-chartjs
import { Bar, Line } from 'vue-chartjs'
import {
  Chart as ChartJS,
  Title,
  Tooltip,
  Legend,
  BarElement,
  LineElement,
  CategoryScale,
  LinearScale,
  PointElement,
} from 'chart.js'

ChartJS.register(
  Title,
  Tooltip,
  Legend,
  BarElement,
  LineElement,
  CategoryScale,
  LinearScale,
  PointElement,
)

const props = defineProps({
  totalPagos: { type: Number, default: 0 },
  totalEfvo: { type: Number, default: 0 },
  totalQr: { type: Number, default: 0 },
  pagosPorMetodo: { type: Array, default: () => [] },
  pagosPorDia: { type: Array, default: () => [] },
  topOrdenes: { type: Array, default: () => [] },
  pagos: { type: Array, default: () => [] },
})

const formatMoney = (val) => {
  if (val == null) return '0.00'
  const num = typeof val === 'number' ? val : parseFloat(val)
  if (Number.isNaN(num)) return '0.00'
  return num.toFixed(2)
}

// === Datos para gráfico de barras (total por método) ===
const metodoBarData = computed(() => ({
  labels: props.pagosPorMetodo.map((m) => m.metodo),
  datasets: [
    {
      label: 'Total (Bs)',
      data: props.pagosPorMetodo.map((m) => m.total),
      backgroundColor: ['#22c55e', '#6366f1', '#f97316', '#a855f7'],
    },
  ],
}))

const metodoBarOptions = {
  responsive: true,
  maintainAspectRatio: false,
  scales: {
    y: { beginAtZero: true },
  },
  plugins: {
    legend: { display: false },
  },
}

// === Datos para gráfico de línea (total diario) ===
const diaLineData = computed(() => ({
  labels: props.pagosPorDia.map((d) => d.fecha),
  datasets: [
    {
      label: 'Total diario (Bs)',
      data: props.pagosPorDia.map((d) => d.total),
      borderColor: '#22c55e',
      backgroundColor: 'rgba(34,197,94,0.2)',
      tension: 0.2,
      fill: true,
    },
  ],
}))

const diaLineOptions = {
  responsive: true,
  maintainAspectRatio: false,
  scales: {
    y: { beginAtZero: true },
  },
}
</script>

<template>
  <AuthenticatedLayout>
    <Head title="Reportes de Pagos" />

    <template #header>
      <h2 class="text-xl font-semibold text-gray-800">
        Reportes y estadísticas de pagos
      </h2>
    </template>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
      <!-- Tarjetas resumen -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white shadow-sm rounded-lg p-4">
          <p class="text-xs font-medium text-gray-500 uppercase">
            Total cobrado
          </p>
          <p class="mt-2 text-2xl font-bold text-gray-800">
            Bs {{ formatMoney(totalPagos) }}
          </p>
        </div>

        <div class="bg-white shadow-sm rounded-lg p-4">
          <p class="text-xs font-medium text-gray-500 uppercase">
            Total en efectivo
          </p>
          <p class="mt-2 text-2xl font-bold text-emerald-700">
            Bs {{ formatMoney(totalEfvo) }}
          </p>
        </div>

        <div class="bg-white shadow-sm rounded-lg p-4">
          <p class="text-xs font-medium text-gray-500 uppercase">
            Total por QR
          </p>
          <p class="mt-2 text-2xl font-bold text-indigo-700">
            Bs {{ formatMoney(totalQr) }}
          </p>
        </div>
      </div>

      <!-- 🔹 GRÁFICOS -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Barras por método -->
        <div class="bg-white shadow-sm rounded-lg p-4 h-80">
          <h3 class="font-semibold text-gray-800 mb-2">
            Total cobrado por método
          </h3>
          <div class="h-64">
            <Bar :data="metodoBarData" :options="metodoBarOptions" />
          </div>
        </div>

        <!-- Línea por día -->
        <div class="bg-white shadow-sm rounded-lg p-4 h-80">
          <h3 class="font-semibold text-gray-800 mb-2">
            Evolución diaria de pagos
          </h3>
          <div class="h-64">
            <Line :data="diaLineData" :options="diaLineOptions" />
          </div>
        </div>
      </div>

      <!-- Tablas: métodos y top órdenes -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Tabla por método -->
        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
          <div class="p-4 border-b">
            <h3 class="font-semibold text-gray-800">Pagos por método</h3>
          </div>
          <div class="p-4">
            <table class="min-w-full text-sm text-left">
              <thead>
                <tr class="border-b bg-gray-50 text-gray-700">
                  <th class="px-3 py-2">Método</th>
                  <th class="px-3 py-2 text-right">Cantidad</th>
                  <th class="px-3 py-2 text-right">Total (Bs)</th>
                </tr>
              </thead>
              <tbody>
                <tr
                  v-for="m in pagosPorMetodo"
                  :key="m.metodo"
                  class="border-b hover:bg-gray-50"
                >
                  <td class="px-3 py-2">
                    <span
                      class="inline-flex px-2 py-1 rounded-full text-xs font-semibold"
                      :class="m.metodo === 'EFECTIVO'
                        ? 'bg-emerald-100 text-emerald-700'
                        : 'bg-indigo-100 text-indigo-700'"
                    >
                      {{ m.metodo }}
                    </span>
                  </td>
                  <td class="px-3 py-2 text-right">
                    {{ m.cantidad }}
                  </td>
                  <td class="px-3 py-2 text-right">
                    {{ formatMoney(m.total) }}
                  </td>
                </tr>
                <tr v-if="pagosPorMetodo.length === 0">
                  <td colspan="3" class="px-3 py-4 text-center text-gray-500">
                    No hay pagos registrados.
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Top órdenes -->
        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
          <div class="p-4 border-b">
            <h3 class="font-semibold text-gray-800">
              Órdenes con mayor monto pagado
            </h3>
          </div>
          <div class="p-4 overflow-x-auto">
            <table class="min-w-full text-sm text-left">
              <thead>
                <tr class="border-b bg-gray-50 text-gray-700">
                  <th class="px-3 py-2">Orden</th>
                  <th class="px-3 py-2 text-right">Pagos</th>
                  <th class="px-3 py-2 text-right">Total pagado (Bs)</th>
                </tr>
              </thead>
              <tbody>
                <tr
                  v-for="t in topOrdenes"
                  :key="t.orden_nro"
                  class="border-b hover:bg-gray-50"
                >
                  <td class="px-3 py-2 font-mono text-xs">
                    {{ t.orden_nro }}
                  </td>
                  <td class="px-3 py-2 text-right">
                    {{ t.cantidad_pagos }}
                  </td>
                  <td class="px-3 py-2 text-right">
                    {{ formatMoney(t.total_pagado) }}
                  </td>
                </tr>
                <tr v-if="topOrdenes.length === 0">
                  <td colspan="3" class="px-3 py-4 text-center text-gray-500">
                    No hay datos para mostrar.
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Tabla de pagos recientes -->
      <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <div class="p-4 border-b">
          <h3 class="font-semibold text-gray-800">
            Últimos pagos registrados
          </h3>
          <p class="text-xs text-gray-500">
            Se muestran los últimos 50 pagos (semilla + nuevos registros).
          </p>
        </div>
        <div class="p-4 overflow-x-auto">
          <table class="min-w-full text-sm text-left">
            <thead>
              <tr class="border-b bg-gray-50 text-gray-700">
                <th class="px-3 py-2">Fecha</th>
                <th class="px-3 py-2">Orden</th>
                <th class="px-3 py-2">Método</th>
                <th class="px-3 py-2 text-right">Monto (Bs)</th>
                <th class="px-3 py-2">Referencia</th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="p in pagos"
                :key="p.id"
                class="border-b hover:bg-gray-50"
              >
                <td class="px-3 py-2">
                  {{ p.fecha }}
                </td>
                <td class="px-3 py-2 font-mono text-xs">
                  {{ p.orden_nro }}
                </td>
                <td class="px-3 py-2">
                  <span
                    class="inline-flex px-2 py-1 rounded-full text-xs font-semibold"
                    :class="p.metodo === 'EFECTIVO'
                      ? 'bg-emerald-100 text-emerald-700'
                      : 'bg-indigo-100 text-indigo-700'"
                  >
                    {{ p.metodo }}
                  </span>
                </td>
                <td class="px-3 py-2 text-right">
                  {{ formatMoney(p.monto) }}
                </td>
                <td class="px-3 py-2">
                  {{ p.referencia ?? '—' }}
                </td>
              </tr>
              <tr v-if="pagos.length === 0">
                <td colspan="5" class="px-3 py-4 text-center text-gray-500">
                  No hay pagos registrados aún.
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
