<script setup>
import { ref } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link } from '@inertiajs/vue3';

const showingNavigationDropdown = ref(false);
</script>

<template>
    <div>
        <div class="min-h-screen bg-gray-100">
            <!-- Sidebar Layout -->
            <div class="flex min-h-screen bg-gray-100">

                <!-- SIDEBAR -->
                <aside class="w-64 bg-blue-900 text-white flex flex-col">
                    <div class="p-4 text-xl font-bold border-b border-blue-800">
                        Lavandería Belén
                    </div>

                    <nav class="flex-1 p-4">
                        <ul class="space-y-2">

                            <li v-for="item in $page.props.menu" :key="item.id">

                                <!-- ÍTEM SIN SUBMENÚ -->
                                <template v-if="!item.hijos || item.hijos.length === 0">
                                    <Link
                                        :href="item.ruta"
                                        class="flex items-center space-x-3 p-2 rounded hover:bg-blue-800"
                                    >
                                        <i :class="item.icono"></i>
                                        <span>{{ item.nombre }}</span>
                                    </Link>
                                </template>

                                <!-- ÍTEM CON SUBMENÚ -->
                                <template v-else>
                                    <div class="p-2 font-semibold text-blue-200">
                                        {{ item.nombre }}
                                    </div>

                                    <ul class="ml-4 space-y-1">
                                        <li v-for="sub in item.hijos" :key="sub.id">
                                            <Link
                                                :href="sub.ruta"
                                                class="flex items-center space-x-3 p-2 rounded hover:bg-blue-800"
                                            >
                                                <i :class="sub.icono"></i>
                                                <span>{{ sub.nombre }}</span>
                                            </Link>
                                        </li>
                                    </ul>
                                </template>

                            </li>

                        </ul>
                    </nav>


                    <!-- USER & LOGOUT -->
                    <div class="p-4 border-t border-blue-800">
                        <div class="font-semibold">
                            {{ $page.props.auth.user.nombre }}
                        </div>
                        <div class="text-sm text-blue-200">
                            {{ $page.props.auth.user.email }}
                        </div>

                        <Link
                            :href="route('logout')"
                            method="post"
                            as="button"
                            class="w-full text-left text-red-300 hover:text-red-500"
                        >
                            Cerrar sesión
                        </Link>

                    </div>
                </aside>

                <!-- MAIN CONTENT -->
                <div class="flex-1">
                    <!-- Page Heading -->
                    <header class="bg-white shadow" v-if="$slots.header">
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
