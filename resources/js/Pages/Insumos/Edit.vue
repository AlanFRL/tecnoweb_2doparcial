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
  codigo: props.insumo.codigo,
  nombre: props.insumo.nombre,
  cantidad: props.insumo.cantidad,
  unidad_medida: props.insumo.unidad_medida,
  stock: props.insumo.stock,
  stock_min: props.insumo.stock_min,
  stock_max: props.insumo.stock_max,
  costo_promedio: props.insumo.costo_promedio,
  estado: props.insumo.estado,
})

const submit = () => {
  form.put(route('insumos.update', props.insumo.codigo))
}
</script>

<template>
  <AuthenticatedLayout>
    <Head title="Editar Insumo" />

    <template #header>
      <div class="flex items-center justify-between">
        <h2 class="text-xl font-semibold text-gray-800">Editar Insumo</h2>
        <Link
          :href="route('insumos.index')"
          class="text-sm text-gray-600 hover:text-gray-900"
        >
          Volver al listado
        </Link>
      </div>
    </template>

    <!-- Reutiliza el mismo formulario de Create.vue, solo que usando este form y submit -->
  </AuthenticatedLayout>
</template>
