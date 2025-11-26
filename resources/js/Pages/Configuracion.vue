<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ThemeSelector from '@/Components/ThemeSelector.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Configuración" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight">
                Configuración de Apariencia
            </h2>
        </template>

        <div class="py-6">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <ThemeSelector />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
