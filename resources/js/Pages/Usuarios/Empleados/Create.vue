<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { useForm, Link } from '@inertiajs/vue3';

const form = useForm({
    nombre: '',
    telefono: '',
    email: '',
    password: '',
    tipo_usuario: 'empleado',
});
</script>

<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold">Nuevo Empleado</h2>
        </template>

        <div class="max-w-xl bg-white p-6 rounded shadow mx-auto">
            <label class="block mt-3">Nombre</label>
            <input v-model="form.nombre" type="text" class="w-full border rounded p-2" />

            <label class="block mt-3">Teléfono</label>
            <input v-model="form.telefono" type="text" class="w-full border rounded p-2" />

            <label class="block mt-3">Email</label>
            <input v-model="form.email" type="email" class="w-full border rounded p-2" />

            <label class="block mt-3">Contraseña</label>
            <input v-model="form.password" type="password" class="w-full border rounded p-2" />

            <div class="mt-6 flex justify-between">
                <Link :href="route('usuarios.empleados.index')" class="text-gray-600">
                    Cancelar
                </Link>

                <button
                    @click="form.post(route('usuarios.empleados.store'))"
                    type="button"
                    class="px-4 py-2 bg-blue-600 text-white rounded"
                >
                    Guardar
                </button>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
