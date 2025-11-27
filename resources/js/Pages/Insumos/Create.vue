<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link, useForm } from '@inertiajs/vue3'

const form = useForm({
  codigo: '',
  nombre: '',
  cantidad: 0,
  unidad_medida: 'GR',
  stock: 0,
  stock_min: 0,
  stock_max: 0,
  costo_promedio: 0,
  estado: true,
})

const submit = () => {
  form.post(route('insumos.store'))
}
</script>

<template>
  <AuthenticatedLayout>
    <Head title="Nuevo Insumo" />

    <template #header>
      <div class="flex items-center justify-between">
        <h2 class="text-xl font-semibold text-gray-800">Nuevo Insumo</h2>
        <Link
          :href="route('insumos.index')"
          class="text-sm text-gray-600 hover:text-gray-900"
        >
          Volver al listado
        </Link>
      </div>
    </template>

    <!-- formulario igual que te pasé antes (cant, stock, min, max, costo, estado) -->
    <!-- puedes reutilizar EXACTO el Create.vue que ya te di; no lo repito para que no se haga eterno -->
  </AuthenticatedLayout>
</template>
