<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link } from '@inertiajs/vue3';

defineProps({
    usuarios: Array,
});
</script>

<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold">Gestión de Empleados</h2>
        </template>

        <div class="bg-white shadow p-4 rounded">
            <Link
                :href="route('usuarios.empleados.create')"
                class="px-4 py-2 bg-blue-600 text-white rounded"
            >
                + Nuevo Empleado
            </Link>

            <table class="w-full mt-4">
                <thead>
                    <tr class="border-b">
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Tipo</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    <tr v-for="u in usuarios" :key="u.id" class="border-b">
                        <td>{{ u.id }}</td>
                        <td>{{ u.nombre }}</td>
                        <td>{{ u.email }}</td>
                        <td>{{ u.tipo_usuario }}</td>
                        <td>
                            <span :class="u.estado ? 'text-green-600' : 'text-red-600'">
                                {{ u.estado ? 'Activo' : 'Inactivo' }}
                            </span>
                        </td>
                        <td class="space-x-2">
                            <Link
                                :href="route('usuarios.empleados.edit', u.id)"
                                class="text-blue-600"
                            >
                                Editar
                            </Link>

                            <Link
                                :href="route('usuarios.empleados.destroy', u.id)"
                                method="delete"
                                as="button"
                                class="text-red-600"
                            >
                                Eliminar
                            </Link>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </AuthenticatedLayout>
</template>
