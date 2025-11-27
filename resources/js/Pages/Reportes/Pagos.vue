<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link } from '@inertiajs/vue3'

const props = defineProps({
  totalPagos: {
    type: Number,
    default: 0,
  },
  totalEfvo: {
    type: Number,
    default: 0,
  },
  totalQr: {
    type: Number,
    default: 0,
  },
  pagosPorMetodo: {
    type: Array,
    default: () => [],
  },
  pagosPorDia: {
    type: Array,
    default: () => [],
  },
  topOrdenes: {
    type: Array,
    default: () => [],
  },
})

const formatMoney = (val) => {
  if (val == null) return '0.00'
  const num = typeof val === 'number' ? val : parseFloat(val)
  if (Number.isNaN(num)) return '0.00'
  return num.toFixed(2)
}
</script>

<template>
  <AuthenticatedLayout>
    <Head title="Reportes de Pagos" />

    <template #header>
      <div class="flex items-center justify-between">
        <h2 class="text-xl font-semibold text-gray-800">
          Reportes y estadísticas de pagos
        </h2>
      </div>
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

      <!-- Distribución por método + Top órdenes -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Por método -->
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
                  <td
                    colspan="3"
                    class="px-3 py-4 text-center text-gray-500"
                  >
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
          <div class="p-4">
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
                  <td
                    colspan="3"
                    class="px-3 py-4 text-center text-gray-500"
                  >
                    No hay datos para mostrar.
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Evolución diaria -->
      <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <div class="p-4 border-b">
          <h3 class="font-semibold text-gray-800">
            Evolución diaria de pagos
          </h3>
          <p class="text-xs text-gray-500">
            Muestra cuántos pagos y qué monto total hubo por día.
          </p>
        </div>
        <div class="p-4 overflow-x-auto">
          <table class="min-w-full text-sm text-left">
            <thead>
              <tr class="border-b bg-gray-50 text-gray-700">
                <th class="px-3 py-2">Fecha</th>
                <th class="px-3 py-2 text-right">Cantidad de pagos</th>
                <th class="px-3 py-2 text-right">Total del día (Bs)</th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="d in pagosPorDia"
                :key="d.fecha"
                class="border-b hover:bg-gray-50"
              >
                <td class="px-3 py-2">{{ d.fecha }}</td>
                <td class="px-3 py-2 text-right">{{ d.cantidad }}</td>
                <td class="px-3 py-2 text-right">
                  {{ formatMoney(d.total) }}
                </td>
              </tr>

              <tr v-if="pagosPorDia.length === 0">
                <td
                  colspan="3"
                  class="px-3 py-4 text-center text-gray-500"
                >
                  No hay registros de pagos todavía.
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
