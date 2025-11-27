<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link } from '@inertiajs/vue3'

const props = defineProps({
  insumos: {
    type: Array,
    default: () => [],
  },
  movimientos: {
    type: Array,
    default: () => [],
  },
})

const getStockBadgeClass = (insumo) => {
  if (insumo.stock < insumo.stock_min) {
    return 'bg-red-100 text-red-700'
  }
  if (insumo.stock > insumo.stock_max) {
    return 'bg-yellow-100 text-yellow-700'
  }
  return 'bg-green-100 text-green-700'
}
</script>

<template>
  <AuthenticatedLayout>
    <Head title="Insumos e Inventario" />

    <template #header>
      <h2 class="text-xl font-semibold text-gray-800">
        Insumos e Inventario
      </h2>
    </template>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
      <!-- Tabla de Insumos -->
      <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <div class="p-4 flex items-center justify-between">
          <h3 class="font-semibold text-gray-800">Catálogo de Insumos</h3>
          <Link
            :href="route('insumos.create')"
            class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-md hover:bg-blue-700"
          >
            + Nuevo Insumo
          </Link>
        </div>

        <div class="px-4 pb-4 overflow-x-auto">
          <table class="min-w-full text-sm text-left">
            <thead>
              <tr class="border-b bg-gray-50 text-gray-700">
                <th class="px-3 py-2">Código</th>
                <th class="px-3 py-2">Nombre</th>
                <th class="px-3 py-2">Cant. unidad</th>
                <th class="px-3 py-2">Unidad</th>
                <th class="px-3 py-2">Stock</th>
                <th class="px-3 py-2">Mín</th>
                <th class="px-3 py-2">Máx</th>
                <th class="px-3 py-2">Costo prom. (Bs)</th>
                <th class="px-3 py-2">Estado</th>
                <th class="px-3 py-2 text-center">Acciones</th>
              </tr>
            </thead>

            <tbody>
              <tr
                v-for="i in insumos"
                :key="i.codigo"
                class="border-b hover:bg-gray-50"
              >
                <td class="px-3 py-2 font-mono text-xs">
                  {{ i.codigo }}
                </td>
                <td class="px-3 py-2">{{ i.nombre }}</td>
                <td class="px-3 py-2">{{ i.cantidad }}</td>
                <td class="px-3 py-2">{{ i.unidad_medida }}</td>

                <td class="px-3 py-2">
                  <span
                    class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold"
                    :class="getStockBadgeClass(i)"
                  >
                    {{ i.stock }}
                  </span>
                </td>
                <td class="px-3 py-2">{{ i.stock_min }}</td>
                <td class="px-3 py-2">{{ i.stock_max }}</td>
                <td class="px-3 py-2">
                  {{ i.costo_promedio.toFixed ? i.costo_promedio.toFixed(2) : i.costo_promedio }}
                </td>

                <td class="px-3 py-2">
                  <span
                    class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold"
                    :class="i.estado ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'"
                  >
                    {{ i.estado ? 'Activo' : 'Inactivo' }}
                  </span>
                </td>

                <td class="px-3 py-2 text-center space-x-2">
                  <Link
                    :href="route('insumos.edit', i.codigo)"
                    class="text-blue-600 hover:underline text-xs"
                  >
                    Editar
                  </Link>

                  <Link
                    :href="route('insumos.inventario.index', i.codigo)"
                    class="text-indigo-600 hover:underline text-xs"
                  >
                    Inventario
                  </Link>

                  <Link
                    :href="route('insumos.destroy', i.codigo)"
                    method="delete"
                    as="button"
                    class="text-red-600 hover:underline text-xs"
                  >
                    Eliminar
                  </Link>
                </td>
              </tr>

              <tr v-if="insumos.length === 0">
                <td colspan="10" class="px-3 py-4 text-center text-gray-500">
                  No hay insumos registrados.
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Movimientos recientes (solo vista) -->
      <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <div class="p-4">
          <h3 class="font-semibold text-gray-800">
            Movimientos de Inventario recientes
          </h3>
        </div>

        <div class="px-4 pb-4 overflow-x-auto">
          <table class="min-w-full text-sm text-left">
            <thead>
              <tr class="border-b bg-gray-50 text-gray-700">
                <th class="px-3 py-2">Fecha</th>
                <th class="px-3 py-2">Insumo</th>
                <th class="px-3 py-2">Tipo</th>
                <th class="px-3 py-2">Cantidad</th>
                <th class="px-3 py-2">Costo unit.</th>
                <th class="px-3 py-2">Referencia</th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="m in movimientos"
                :key="m.id"
                class="border-b hover:bg-gray-50"
              >
                <td class="px-3 py-2">
                  {{ m.fecha }}
                </td>
                <td class="px-3 py-2">
                  {{ m.insumo?.nombre ?? m.insumo_codigo }}
                </td>
                <td class="px-3 py-2">
                  <span
                    class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold"
                    :class="m.tipo === 'ENTRADA' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'"
                  >
                    {{ m.tipo }}
                  </span>
                </td>
                <td class="px-3 py-2">{{ m.cantidad }}</td>
                <td class="px-3 py-2">{{ m.costo_unitario }}</td>
                <td class="px-3 py-2">{{ m.referencia }}</td>
              </tr>

              <tr v-if="movimientos.length === 0">
                <td colspan="6" class="px-3 py-4 text-center text-gray-500">
                  No hay movimientos registrados.
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
