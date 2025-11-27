<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link, useForm } from '@inertiajs/vue3'

const props = defineProps({
  insumo: {
    type: Object,
    required: true,
  },
})

const form = useForm({
  tipo: 'ENTRADA',      // ENTRADA / SALIDA
  fecha: new Date().toISOString().slice(0, 10),
  cantidad: 0,
  costo_unitario: 0,
  referencia: '',
  usuario_id: null,
  proveedor_id: null,
})

const submit = () => {
  form.post(route('insumos.inventario.store', props.insumo.codigo))
}
</script>

<template>
  <AuthenticatedLayout>
    <Head :title="`Nuevo movimiento - ${insumo.nombre}`" />

    <template #header>
      <div class="flex items-center justify-between">
        <div>
          <h2 class="text-xl font-semibold text-gray-800">
            Nuevo movimiento de inventario
          </h2>
          <p class="text-sm text-gray-500">
            Insumo: {{ insumo.nombre }} ({{ insumo.codigo }})
          </p>
        </div>
        <Link
          :href="route('insumos.inventario.index', insumo.codigo)"
          class="text-sm text-gray-600 hover:text-gray-900"
        >
          Volver al kardex
        </Link>
      </div>
    </template>

    <div class="py-6 max-w-3xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white shadow-sm rounded-lg p-6">
        <form @submit.prevent="submit" class="space-y-4">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700">
                Tipo
              </label>
              <select
                v-model="form.tipo"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
              >
                <option value="ENTRADA">ENTRADA</option>
                <option value="SALIDA">SALIDA</option>
              </select>
              <p v-if="form.errors.tipo" class="text-xs text-red-600 mt-1">
                {{ form.errors.tipo }}
              </p>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700">
                Fecha
              </label>
              <input
                v-model="form.fecha"
                type="date"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
              />
              <p v-if="form.errors.fecha" class="text-xs text-red-600 mt-1">
                {{ form.errors.fecha }}
              </p>
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700">
                Cantidad
              </label>
              <input
                v-model.number="form.cantidad"
                type="number"
                min="0"
                step="0.01"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
              />
              <p v-if="form.errors.cantidad" class="text-xs text-red-600 mt-1">
                {{ form.errors.cantidad }}
              </p>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700">
                Costo unitario (Bs)
              </label>
              <input
                v-model.number="form.costo_unitario"
                type="number"
                min="0"
                step="0.01"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
              />
              <p
                v-if="form.errors.costo_unitario"
                class="text-xs text-red-600 mt-1"
              >
                {{ form.errors.costo_unitario }}
              </p>
            </div>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">
              Referencia / Observación
            </label>
            <input
              v-model="form.referencia"
              type="text"
              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
            />
            <p
              v-if="form.errors.referencia"
              class="text-xs text-red-600 mt-1"
            >
              {{ form.errors.referencia }}
            </p>
          </div>

          <div class="pt-4">
            <button
              type="submit"
              class="px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-md hover:bg-blue-700"
              :disabled="form.processing"
            >
              Guardar movimiento
            </button>
          </div>
        </form>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
