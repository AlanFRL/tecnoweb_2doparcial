<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref, computed, watch } from 'vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import TextInput from '@/Components/TextInput.vue';

const props = defineProps({
    clientes: Array,
    servicios: Array,
    fragancias: Array,
    numeroOrden: String,
});

// Form principal
const form = useForm({
    cliente_id: '',
    forma_pago: 'CONTADO',
    fecha_vencimiento: '',
    observaciones: '',
    detalles: [],
});

// Detalle temporal para agregar servicio
const detalleTemp = ref({
    servicio_id: '',
    peso_kg: '',
    cantidad: null,
    fragancia: '',
    notas: '',
});

// Servicio seleccionado
const servicioSeleccionado = computed(() => {
    if (!detalleTemp.value.servicio_id) return null;
    return props.servicios.find(s => s.id === parseInt(detalleTemp.value.servicio_id));
});

// Reset detalle temporal cuando cambia el servicio
watch(() => detalleTemp.value.servicio_id, (newVal) => {
    if (newVal) {
        detalleTemp.value.peso_kg = '';
        detalleTemp.value.cantidad = null;
        detalleTemp.value.fragancia = '';
        detalleTemp.value.notas = '';
    }
});

// Agregar servicio a la lista
const agregarServicio = () => {
    if (!detalleTemp.value.servicio_id) {
        alert('Debe seleccionar un servicio');
        return;
    }

    const servicio = servicioSeleccionado.value;

    // Validar según tipo de cobro
    if (servicio.tipo_cobro === 'KILO') {
        if (!detalleTemp.value.peso_kg || detalleTemp.value.peso_kg <= 0) {
            alert('Debe ingresar un peso válido');
            return;
        }
    } else { // PIEZA
        if (!detalleTemp.value.cantidad || detalleTemp.value.cantidad <= 0) {
            alert('Debe ingresar una cantidad válida');
            return;
        }
    }

    // Calcular subtotal
    let subtotal = 0;
    if (servicio.tipo_cobro === 'KILO') {
        subtotal = parseFloat(detalleTemp.value.peso_kg) * parseFloat(servicio.precio_unitario);
    } else {
        subtotal = parseInt(detalleTemp.value.cantidad) * parseFloat(servicio.precio_unitario);
    }

    // Calcular descuento si hay promoción
    let descuento = 0;
    if (servicio.promocion) {
        descuento = subtotal * (parseFloat(servicio.promocion.descuento) / 100);
    }

    const totalLinea = subtotal - descuento;

    // Agregar al array
    form.detalles.push({
        servicio_id: servicio.id,
        servicio_nombre: servicio.nombre,
        tipo_cobro: servicio.tipo_cobro,
        precio_unitario: servicio.precio_unitario,
        peso_kg: servicio.tipo_cobro === 'KILO' ? detalleTemp.value.peso_kg : null,
        cantidad: servicio.tipo_cobro === 'PIEZA' ? detalleTemp.value.cantidad : null,
        fragancia: detalleTemp.value.fragancia || null,
        notas: detalleTemp.value.notas || null,
        subtotal: subtotal,
        descuento: descuento,
        total_linea: totalLinea,
        promocion: servicio.promocion,
    });

    // Reset form temporal
    detalleTemp.value = {
        servicio_id: '',
        peso_kg: null,
        cantidad: null,
        fragancia: '',
        notas: '',
    };
};

// Eliminar servicio
const eliminarServicio = (index) => {
    form.detalles.splice(index, 1);
};

// Calcular totales
const subtotalOrden = computed(() => {
    return form.detalles.reduce((sum, det) => sum + parseFloat(det.subtotal), 0);
});

const descuentoOrden = computed(() => {
    return form.detalles.reduce((sum, det) => sum + parseFloat(det.descuento), 0);
});

const totalOrden = computed(() => {
    return subtotalOrden.value - descuentoOrden.value;
});

// Enviar formulario
const submit = () => {
    if (form.detalles.length === 0) {
        alert('Debe agregar al menos un servicio');
        return;
    }

    form.post(route('ordenes.store'), {
        preserveScroll: true,
        onError: (errors) => {
            console.error('Errores de validación:', errors);
        },
    });
};

// Cancelar
const cancelar = () => {
    if (confirm('¿Está seguro de cancelar? Se perderán todos los datos ingresados.')) {
        window.history.back();
    }
};
</script>

<template>
    <Head title="Nueva Orden" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                ➕ Nueva Orden - {{ numeroOrden }}
            </h2>
        </template>

        <div class="py-6">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                
                <form @submit.prevent="submit">
                    
                    <!-- Datos Generales -->
                    <div class="mb-6 bg-white rounded-lg shadow-sm p-6">
                        <h3 class="text-lg font-semibold mb-4">📋 Datos Generales</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <!-- Cliente -->
                            <div>
                                <InputLabel for="cliente_id" value="Cliente *" />
                                <select
                                    id="cliente_id"
                                    v-model="form.cliente_id"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                                    required
                                >
                                    <option value="">Seleccione un cliente</option>
                                    <option 
                                        v-for="cliente in clientes" 
                                        :key="cliente.id" 
                                        :value="cliente.id"
                                    >
                                        {{ cliente.nombre }} - {{ cliente.telefono }}
                                    </option>
                                </select>
                                <InputError class="mt-2" :message="form.errors.cliente_id" />
                            </div>

                            <!-- Forma de Pago -->
                            <div>
                                <InputLabel for="forma_pago" value="Forma de Pago *" />
                                <select
                                    id="forma_pago"
                                    v-model="form.forma_pago"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                                    required
                                >
                                    <option value="CONTADO">CONTADO</option>
                                    <option value="CREDITO">CRÉDITO</option>
                                </select>
                                <InputError class="mt-2" :message="form.errors.forma_pago" />
                            </div>

                            <!-- Fecha de Vencimiento (solo si es CREDITO) -->
                            <div v-if="form.forma_pago === 'CREDITO'">
                                <InputLabel for="fecha_vencimiento" value="Fecha de Vencimiento *" />
                                <input
                                    type="date"
                                    id="fecha_vencimiento"
                                    v-model="form.fecha_vencimiento"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                                    :min="new Date().toISOString().split('T')[0]"
                                    required
                                />
                                <InputError class="mt-2" :message="form.errors.fecha_vencimiento" />
                            </div>

                            <!-- Observaciones -->
                            <div :class="form.forma_pago === 'CREDITO' ? '' : 'md:col-span-2'">
                                <InputLabel for="observaciones" value="Observaciones" />
                                <textarea
                                    id="observaciones"
                                    v-model="form.observaciones"
                                    rows="3"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                                    placeholder="Notas adicionales sobre la orden..."
                                ></textarea>
                                <InputError class="mt-2" :message="form.errors.observaciones" />
                            </div>

                        </div>
                    </div>

                    <!-- Agregar Servicios -->
                    <div class="mb-6 bg-white rounded-lg shadow-sm p-6">
                        <h3 class="text-lg font-semibold mb-4">🧺 Agregar Servicios a la Orden</h3>
                        
                        <div class="space-y-4">
                            
                            <!-- Fila 1: Servicio -->
                            <div>
                                <InputLabel value="Servicio *" class="mb-2" />
                                <select
                                    v-model="detalleTemp.servicio_id"
                                    class="block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                >
                                    <option value="">-- Seleccione un servicio --</option>
                                    <option 
                                        v-for="servicio in servicios" 
                                        :key="servicio.id" 
                                        :value="servicio.id"
                                    >
                                        {{ servicio.nombre }} - Bs. {{ servicio.precio_unitario }}
                                        <template v-if="servicio.promocion">
                                            🎉 {{ servicio.promocion.descuento }}% OFF
                                        </template>
                                    </option>
                                </select>
                            </div>

                            <!-- Info de Promoción -->
                            <div v-if="servicioSeleccionado && servicioSeleccionado.promocion" class="p-4 bg-yellow-50 border border-yellow-200 rounded-md">
                                <p class="text-sm text-yellow-800 flex items-center gap-2">
                                    <span class="text-2xl">🎉</span>
                                    <span>
                                        <strong>¡Promoción Activa!</strong> 
                                        {{ servicioSeleccionado.promocion.nombre }} 
                                        - <strong>{{ servicioSeleccionado.promocion.descuento }}%</strong> de descuento
                                    </span>
                                </p>
                            </div>

                            <!-- Fila 2: Peso/Cantidad + Fragancia (solo si hay servicio seleccionado) -->
                            <div v-if="servicioSeleccionado" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                
                                <!-- Peso (solo si es KILO) -->
                                <div v-if="servicioSeleccionado.tipo_cobro === 'KILO'">
                                    <InputLabel value="Peso en Kilogramos *" class="mb-2" />
                                    <div class="relative">
                                        <TextInput
                                            type="number"
                                            step="0.01"
                                            min="0.01"
                                            v-model="detalleTemp.peso_kg"
                                            placeholder="Ej: 5.50"
                                            class="w-full pr-12"
                                        />
                                        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 font-medium">
                                            Kg
                                        </span>
                                    </div>
                                </div>

                                <!-- Cantidad (solo si es PIEZA) -->
                                <div v-if="servicioSeleccionado.tipo_cobro === 'PIEZA'">
                                    <InputLabel value="Cantidad de Piezas *" class="mb-2" />
                                    <div class="relative">
                                        <TextInput
                                            type="number"
                                            min="1"
                                            v-model="detalleTemp.cantidad"
                                            placeholder="Ej: 2"
                                            class="w-full pr-20"
                                        />
                                        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 font-medium">
                                            pieza(s)
                                        </span>
                                    </div>
                                </div>

                                <!-- Fragancia -->
                                <div>
                                    <InputLabel value="Fragancia (Opcional)" class="mb-2" />
                                    <select
                                        v-model="detalleTemp.fragancia"
                                        class="block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    >
                                        <option value="">-- Sin fragancia --</option>
                                        <option v-for="frag in fragancias" :key="frag" :value="frag">
                                            {{ frag }}
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <!-- Fila 3: Notas -->
                            <div v-if="servicioSeleccionado">
                                <InputLabel value="Notas Adicionales (Opcional)" class="mb-2" />
                                <TextInput
                                    v-model="detalleTemp.notas"
                                    placeholder="Ej: Ropa delicada, manchas difíciles, etc."
                                    class="w-full"
                                />
                            </div>

                            <!-- Botón Agregar -->
                            <div v-if="servicioSeleccionado" class="flex justify-end">
                                <button
                                    type="button"
                                    @click="agregarServicio"
                                    class="inline-flex items-center gap-2 px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition shadow-sm"
                                >
                                    <span class="text-xl">➕</span>
                                    <span>Agregar Servicio a la Orden</span>
                                </button>
                            </div>

                        </div>
                    </div>

                    <!-- Lista de Servicios Agregados -->
                    <div class="mb-6 bg-white rounded-lg shadow-sm p-6">
                        <h3 class="text-lg font-semibold mb-4">📝 Servicios Agregados</h3>
                        
                        <div v-if="form.detalles.length === 0" class="text-center py-8 text-gray-500">
                            <div class="text-4xl mb-2">📭</div>
                            <p>No se han agregado servicios aún</p>
                        </div>

                        <div v-else class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            Servicio
                                        </th>
                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">
                                            Cantidad/Peso
                                        </th>
                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">
                                            Fragancia
                                        </th>
                                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">
                                            Subtotal
                                        </th>
                                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">
                                            Descuento
                                        </th>
                                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">
                                            Total
                                        </th>
                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">
                                            Acción
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="(detalle, index) in form.detalles" :key="index">
                                        <td class="px-4 py-3">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ detalle.servicio_nombre }}
                                            </div>
                                            <div v-if="detalle.promocion" class="text-xs text-green-600">
                                                🎉 {{ detalle.promocion.nombre }}
                                            </div>
                                            <div v-if="detalle.notas" class="text-xs text-gray-500 italic">
                                                {{ detalle.notas }}
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-center text-sm">
                                            <span v-if="detalle.tipo_cobro === 'KILO'">
                                                {{ detalle.peso_kg }} Kg
                                            </span>
                                            <span v-else>
                                                {{ detalle.cantidad }} pieza(s)
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-center text-sm text-gray-500">
                                            {{ detalle.fragancia || '-' }}
                                        </td>
                                        <td class="px-4 py-3 text-right text-sm">
                                            Bs. {{ detalle.subtotal.toFixed(2) }}
                                        </td>
                                        <td class="px-4 py-3 text-right text-sm text-red-600">
                                            - Bs. {{ detalle.descuento.toFixed(2) }}
                                        </td>
                                        <td class="px-4 py-3 text-right text-sm font-semibold">
                                            Bs. {{ detalle.total_linea.toFixed(2) }}
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <button
                                                type="button"
                                                @click="eliminarServicio(index)"
                                                class="text-red-600 hover:text-red-900 font-medium"
                                            >
                                                🗑️
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Totales -->
                        <div v-if="form.detalles.length > 0" class="mt-6 border-t pt-4">
                            <div class="flex justify-end">
                                <div class="w-full md:w-1/3 space-y-2">
                                    <div class="flex justify-between text-sm">
                                        <span>Subtotal:</span>
                                        <span>Bs. {{ subtotalOrden.toFixed(2) }}</span>
                                    </div>
                                    <div class="flex justify-between text-sm text-red-600">
                                        <span>Descuentos:</span>
                                        <span>- Bs. {{ descuentoOrden.toFixed(2) }}</span>
                                    </div>
                                    <div class="flex justify-between text-lg font-bold border-t pt-2">
                                        <span>TOTAL:</span>
                                        <span>Bs. {{ totalOrden.toFixed(2) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Botones de Acción -->
                    <div class="flex justify-end gap-4">
                        <SecondaryButton type="button" @click="cancelar">
                            ❌ Cancelar
                        </SecondaryButton>
                        <PrimaryButton 
                            :disabled="form.processing || form.detalles.length === 0"
                            :class="{ 'opacity-50 cursor-not-allowed': form.processing || form.detalles.length === 0 }"
                        >
                            <span v-if="form.processing">Guardando...</span>
                            <span v-else>💾 Guardar Orden</span>
                        </PrimaryButton>
                    </div>

                </form>

            </div>
        </div>
    </AuthenticatedLayout>
</template>
