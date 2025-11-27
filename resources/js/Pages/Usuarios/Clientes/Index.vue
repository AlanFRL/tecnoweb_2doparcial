<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link } from '@inertiajs/vue3'

const props = defineProps({
  clientes: {
    type: Array,
    default: () => [],
  },
})
</script>

<template>
  <AuthenticatedLayout>
    <Head title="Gestión de Clientes" />

    <template #header>
      <div class="flex items-center justify-between">
        <h2 class="text-xl font-semibold text-gray-800">
          Gestión de Clientes
        </h2>

        <Link
          :href="route('usuarios.clientes.create')"
          class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-md hover:bg-blue-700"
        >
          + Nuevo Cliente
        </Link>
      </div>
    </template>

    <div class="py-6">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
          <div class="p-4 sm:p-6">
            <div class="overflow-x-auto">
              <table class="min-w-full text-sm text-left">
                <thead>
                  <tr class="border-b bg-gray-50 text-gray-700">
                    <th class="px-3 py-2">#</th>
                    <th class="px-3 py-2">Nombre</th>
                    <th class="px-3 py-2">Email</th>
                    <th class="px-3 py-2">Teléfono</th>
                    <th class="px-3 py-2">Dirección</th>
                    <th class="px-3 py-2 text-center">Acciones</th>
                  </tr>
                </thead>
                <tbody>
                  <tr
                    v-for="c in clientes"
                    :key="c.id"
                    class="border-b hover:bg-gray-50"
                  >
                    <td class="px-3 py-2">{{ c.id }}</td>
                    <td class="px-3 py-2">{{ c.nombre }}</td>
                    <td class="px-3 py-2">{{ c.email ?? '-' }}</td>
                    <td class="px-3 py-2">{{ c.telefono ?? '-' }}</td>
                    <td class="px-3 py-2">{{ c.direccion ?? '-' }}</td>
                    <td class="px-3 py-2 text-center space-x-2">
                      <Link
                        :href="route('usuarios.clientes.edit', c.id)"
                        class="text-blue-600 hover:underline text-xs"
                      >
                        Editar
                      </Link>

                      <Link
                        :href="route('usuarios.clientes.destroy', c.id)"
                        method="delete"
                        as="button"
                        class="text-red-600 hover:underline text-xs"
                      >
                        Eliminar
                      </Link>
                    </td>
                  </tr>

                  <tr v-if="clientes.length === 0">
                    <td colspan="6" class="px-3 py-4 text-center text-gray-500">
                      No hay clientes registrados.
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
