<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link, useForm } from '@inertiajs/vue3'

const props = defineProps({
  cliente: {
    type: Object,
    required: true,
  },
})

const form = useForm({
  nombre: props.cliente.nombre ?? '',
  direccion: props.cliente.direccion ?? '',
  telefono: props.cliente.telefono ?? '',
})

const submit = () => {
  form.put(route('usuarios.clientes.update', props.cliente.id))
}
</script>

<template>
  <AuthenticatedLayout>
    <Head title="Editar Cliente" />

    <template #header>
      <div class="flex items-center justify-between">
        <h2 class="text-xl font-semibold text-gray-800">
          Editar Cliente
        </h2>

        <Link
          :href="route('usuarios.clientes.index')"
          class="text-sm text-gray-600 hover:text-gray-900"
        >
          Volver al listado
        </Link>
      </div>
    </template>

    <div class="py-6">
      <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm rounded-lg p-6">
          <form @submit.prevent="submit" class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700">
                Nombre
              </label>
              <input
                v-model="form.nombre"
                type="text"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
              />
              <p v-if="form.errors.nombre" class="text-xs text-red-600 mt-1">
                {{ form.errors.nombre }}
              </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700">
                  Teléfono
                </label>
                <input
                  v-model="form.telefono"
                  type="text"
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                />
                <p v-if="form.errors.telefono" class="text-xs text-red-600 mt-1">
                  {{ form.errors.telefono }}
                </p>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700">
                  Dirección
                </label>
                <input
                  v-model="form.direccion"
                  type="text"
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                />
                <p v-if="form.errors.direccion" class="text-xs text-red-600 mt-1">
                  {{ form.errors.direccion }}
                </p>
              </div>
            </div>

            <div class="pt-4">
              <button
                type="submit"
                class="px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-md hover:bg-blue-700"
                :disabled="form.processing"
              >
                Actualizar
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
