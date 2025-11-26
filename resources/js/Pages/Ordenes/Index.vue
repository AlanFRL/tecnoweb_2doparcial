<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

const props = defineProps({
    ordenes: Object,
    filtros: Object,
});

// Estado de filtros
const filtroNro = ref(props.filtros.nro || '');
const filtroCliente = ref(props.filtros.cliente || '');
const filtroEstado = ref(props.filtros.estado || '');
const filtroFormaPago = ref(props.filtros.forma_pago || '');
const filtroFechaDesde = ref(props.filtros.fecha_desde || '');
const filtroFechaHasta = ref(props.filtros.fecha_hasta || '');

// Aplicar filtros
const aplicarFiltros = () => {
    router.get(route('ordenes.index'), {
        nro: filtroNro.value,
        cliente: filtroCliente.value,
        estado: filtroEstado.value,
        forma_pago: filtroFormaPago.value,
        fecha_desde: filtroFechaDesde.value,
        fecha_hasta: filtroFechaHasta.value,
    }, {
        preserveState: true,
        preserveScroll: true,
    });
};

// Limpiar filtros
const limpiarFiltros = () => {
    filtroNro.value = '';
    filtroCliente.value = '';
    filtroEstado.value = '';
    filtroFormaPago.value = '';
    filtroFechaDesde.value = '';
    filtroFechaHasta.value = '';
    aplicarFiltros();
};

// Badges de estado
const getEstadoBadge = (estado) => {
    const badges = {
        'PENDIENTE': 'bg-yellow-100 text-yellow-800',
        'LISTA': 'bg-blue-100 text-blue-800',
        'ENTREGADA': 'bg-green-100 text-green-800',
        'PAGADA': 'bg-purple-100 text-purple-800',
    };
    return badges[estado] || 'bg-gray-100 text-gray-800';
};

const getFormaPagoBadge = (forma) => {
    return forma === 'CONTADO' 
        ? 'bg-green-100 text-green-800' 
        : 'bg-orange-100 text-orange-800';
};
</script>

<template>
    <Head title="Gestión de Órdenes" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    🧾 Gestión de Órdenes
                </h2>
                <Link :href="route('ordenes.create')">
                    <PrimaryButton>
                        ➕ Nueva Orden
                    </PrimaryButton>
                </Link>
            </div>
        </template>

        <div class="py-6">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                
                <!-- Panel de Filtros -->
                <div class="mb-6 bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold mb-4">🔍 Filtros de Búsqueda</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        <!-- Número de Orden -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Nro. Orden
                            </label>
                            <TextInput 
                                v-model="filtroNro" 
                                placeholder="BEL-000001"
                                class="w-full"
                                @keyup.enter="aplicarFiltros"
                            />
                        </div>

                        <!-- Cliente -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Cliente
                            </label>
                            <TextInput 
                                v-model="filtroCliente" 
                                placeholder="Nombre del cliente"
                                class="w-full"
                                @keyup.enter="aplicarFiltros"
                            />
                        </div>

                        <!-- Estado -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Estado
                            </label>
                            <select 
                                v-model="filtroEstado"
                                class="w-full border-gray-300 rounded-md shadow-sm"
                            >
                                <option value="">Todos</option>
                                <option value="PENDIENTE">Pendiente</option>
                                <option value="LISTA">Lista</option>
                                <option value="ENTREGADA">Entregada</option>
                            </select>
                        </div>

                        <!-- Forma de Pago -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Forma de Pago
                            </label>
                            <select 
                                v-model="filtroFormaPago"
                                class="w-full border-gray-300 rounded-md shadow-sm"
                            >
                                <option value="">Todas</option>
                                <option value="CONTADO">Contado</option>
                                <option value="CREDITO">Crédito</option>
                            </select>
                        </div>

                        <!-- Fecha Desde -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Fecha Desde
                            </label>
                            <input 
                                type="date"
                                v-model="filtroFechaDesde"
                                class="w-full border-gray-300 rounded-md shadow-sm"
                            />
                        </div>

                        <!-- Fecha Hasta -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Fecha Hasta
                            </label>
                            <input 
                                type="date"
                                v-model="filtroFechaHasta"
                                class="w-full border-gray-300 rounded-md shadow-sm"
                            />
                        </div>
                    </div>

                    <!-- Botones de Filtro -->
                    <div class="flex gap-2">
                        <PrimaryButton @click="aplicarFiltros">
                            🔍 Buscar
                        </PrimaryButton>
                        <button
                            @click="limpiarFiltros"
                            class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-md text-gray-700 transition"
                        >
                            🗑️ Limpiar
                        </button>
                    </div>
                </div>

                <!-- Tabla de Órdenes -->
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                    Nro. Orden
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                    Cliente
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                    Fecha
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                    Estado
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                    Forma Pago
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">
                                    Total
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">
                                    Saldo
                                </th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">
                                    Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr 
                                v-for="orden in ordenes.data" 
                                :key="orden.nro"
                                class="hover:bg-gray-50 transition"
                            >
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ orden.nro }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ orden.cliente.nombre }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        📞 {{ orden.cliente.telefono }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ orden.fecha_recepcion }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span 
                                        class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full"
                                        :class="getEstadoBadge(orden.estado)"
                                    >
                                        {{ orden.estado }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span 
                                        class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full"
                                        :class="getFormaPagoBadge(orden.forma_pago)"
                                    >
                                        {{ orden.forma_pago }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-semibold">
                                    Bs. {{ parseFloat(orden.total).toFixed(2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right">
                                    <span 
                                        :class="orden.saldo_pendiente > 0 ? 'text-red-600 font-semibold' : 'text-green-600'"
                                    >
                                        Bs. {{ parseFloat(orden.saldo_pendiente).toFixed(2) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                                    <Link 
                                        :href="route('ordenes.show', orden.nro)"
                                        class="text-blue-600 hover:text-blue-900 font-medium"
                                    >
                                        👁️ Ver
                                    </Link>
                                </td>
                            </tr>

                            <!-- Sin resultados -->
                            <tr v-if="ordenes.data.length === 0">
                                <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                                    <div class="text-4xl mb-2">📭</div>
                                    <p class="text-lg">No se encontraron órdenes</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Paginación -->
                    <div class="bg-gray-50 px-6 py-4 border-t border-gray-200" v-if="ordenes.links.length > 3">
                        <div class="flex justify-between items-center">
                            <div class="text-sm text-gray-700">
                                Mostrando 
                                <span class="font-medium">{{ ordenes.from }}</span>
                                a
                                <span class="font-medium">{{ ordenes.to }}</span>
                                de
                                <span class="font-medium">{{ ordenes.total }}</span>
                                resultados
                            </div>
                            <div class="flex gap-2">
                                <template v-for="(link, index) in ordenes.links" :key="index">
                                    <Link
                                        v-if="link.url"
                                        :href="link.url"
                                        :class="[
                                            'px-3 py-2 rounded-md text-sm',
                                            link.active 
                                                ? 'bg-blue-600 text-white' 
                                                : 'bg-white text-gray-700 hover:bg-gray-100'
                                        ]"
                                        v-html="link.label"
                                    ></Link>
                                    <span
                                        v-else
                                        :class="[
                                            'px-3 py-2 rounded-md text-sm',
                                            'bg-gray-100 text-gray-400 cursor-not-allowed'
                                        ]"
                                        v-html="link.label"
                                    ></span>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>
