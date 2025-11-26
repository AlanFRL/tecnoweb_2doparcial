<script setup>
import { ref, onMounted } from 'vue';
import { useTheme } from '@/Composables/useTheme';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link } from '@inertiajs/vue3';

const showingNavigationDropdown = ref(false);

// Inicializar sistema de temas
const { applyTheme } = useTheme();
onMounted(() => {
    applyTheme();
});
</script>

<template>
    <div>
        <div class="min-h-screen">
            <!-- Sidebar Layout -->
            <div class="flex min-h-screen">

                <!-- SIDEBAR -->
                <aside class="sidebar w-64 flex flex-col">
                    <div class="p-4 text-xl font-bold border-b text-white" style="border-color: rgba(255,255,255,0.2);">
                        🧺 Lavandería Belén
                    </div>

                    <nav class="flex-1 p-4">
                        <ul class="space-y-2">

                            <li v-for="item in $page.props.menu" :key="item.id">

                                <!-- ÍTEM SIN SUBMENÚ -->
                                <template v-if="!item.hijos || item.hijos.length === 0">
                                    <Link
                                        :href="item.ruta"
                                        class="sidebar-item flex items-center space-x-3 p-2 rounded"
                                    >
                                        <i :class="item.icono" class="text-white"></i>
                                        <span class="text-white">{{ item.nombre }}</span>
                                    </Link>
                                </template>

                                <!-- ÍTEM CON SUBMENÚ -->
                                <template v-else>
                                    <div class="p-2 font-semibold text-white opacity-90">
                                        <i :class="item.icono" class="mr-2"></i>
                                        {{ item.nombre }}
                                    </div>

                                    <ul class="ml-4 space-y-1">
                                        <li v-for="sub in item.hijos" :key="sub.id">
                                            <Link
                                                :href="sub.ruta"
                                                class="sidebar-item flex items-center space-x-3 p-2 rounded"
                                            >
                                                <i :class="sub.icono" class="text-white"></i>
                                                <span class="text-white">{{ sub.nombre }}</span>
                                            </Link>
                                        </li>
                                    </ul>
                                </template>

                            </li>

                        </ul>
                    </nav>


                    <!-- USER & LOGOUT -->
                    <div class="p-4 border-t text-white" style="border-color: rgba(255,255,255,0.2);">
                        <div class="font-semibold">
                            👤 {{ $page.props.auth.user.nombre }}
                        </div>
                        <div class="text-sm opacity-80">
                            {{ $page.props.auth.user.email }}
                        </div>

                        <Link
                            :href="route('logout')"
                            method="post"
                            as="button"
                            class="mt-3 w-full text-left px-3 py-2 bg-red-500 hover:bg-red-600 rounded text-white transition-colors"
                        >
                            🚪 Cerrar sesión
                        </Link>

                    </div>
                </aside>

                <!-- MAIN CONTENT -->
                <div class="flex-1">
                    <!-- Page Heading -->
                    <header class="shadow" v-if="$slots.header">
                        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                            <slot name="header" />
                        </div>
                    </header>

                    <!-- Page Content -->
                    <main class="p-6">
                        <slot />
                    </main>
                </div>
            </div>



            
        </div>
    </div>
</template>
