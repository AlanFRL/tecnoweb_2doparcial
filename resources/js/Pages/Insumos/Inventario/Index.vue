<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link } from '@inertiajs/vue3'

const props = defineProps({
  insumo: {
    type: Object,
    required: true,
  },
  movimientos: {
    type: Array,
    default: () => [],
  },
})
</script>

<template>
  <AuthenticatedLayout>
    <Head :title="`Inventario - ${insumo.nombre}`" />

    <template #header>
      <div class="flex items-center justify-between">
        <div>
          <h2 class="text-xl font-semibold text-gray-800">
            Inventario de: {{ insumo.nombre }}
          </h2>
          <p class="text-sm text-gray-500">
            Código: {{ insumo.codigo }} · Stock actual: {{ insumo.stock }}
          </p>
        </div>
        <div class="flex items-center gap-3">
          <Link
            :href="route('insumos.index')"
            class="text-sm text-gray-600 hover:text-gray-900"
          >
            Volver a Insumos
          </Link>
          <Link
            :href="route('insumos.inventario.create', insumo.codigo)"
            class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-md hover:bg-blue-700"
          >
            + Nuevo movimiento
          </Link>
        </div>
      </div>
    </template>

    <div class="py-6 max-w-6xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <div class="p-4">
          <h3 class="font-semibold text-gray-800">Kardex de Inventario</h3>
        </div>

        <div class="px-4 pb-4 overflow-x-auto">
          <table class="min-w-full text-sm text-left">
            <thead>
              <tr class="border-b bg-gray-50 text-gray-700">
                <th class="px-3 py-2">Fecha</th>
                <th class="px-3 py-2">Tipo</th>
                <th class="px-3 py-2">Cantidad</th>
                <th class="px-3 py-2">Costo unit.</th>
                <th class="px-3 py-2">Referencia</th>
                <th class="px-3 py-2 text-center">Acciones</th>
              </tr>
            </thead>

            <tbody>
              <tr
                v-for="m in movimientos"
                :key="m.id"
                class="border-b hover:bg-gray-50"
              >
                <td class="px-3 py-2">{{ m.fecha }}</td>
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
                <td class="px-3 py-2 text-center space-x-2">
                  <Link
                    :href="route('insumos.inventario.edit', [insumo.codigo, m.id])"
                    class="text-blue-600 hover:underline text-xs"
                  >
                    Editar
                  </Link>

                  <Link
                    :href="route('insumos.inventario.destroy', [insumo.codigo, m.id])"
                    method="delete"
                    as="button"
                    class="text-red-600 hover:underline text-xs"
                  >
                    Eliminar
                  </Link>
                </td>
              </tr>

              <tr v-if="movimientos.length === 0">
                <td colspan="6" class="px-3 py-4 text-center text-gray-500">
                  No hay movimientos registrados para este insumo.
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
