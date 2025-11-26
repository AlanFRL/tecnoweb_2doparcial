<script setup>
import { useTheme } from '@/Composables/useTheme';

const {
    theme,
    mode,
    fontSize,
    highContrast,
    setTheme,
    toggleMode,
    setFontSize,
    toggleHighContrast,
} = useTheme();

const themes = [
    { value: 'kids', label: 'Kids 🧒', description: 'Divertido y colorido' },
    { value: 'young', label: 'Young 🎮', description: 'Moderno y tech' },
    { value: 'classic', label: 'Classic 👔', description: 'Profesional' },
];

const fontSizes = [
    { value: 'normal', label: 'Normal' },
    { value: 'large', label: 'Grande' },
    { value: 'xlarge', label: 'Muy grande' },
];
</script>

<template>
    <div class="theme-selector p-6 card rounded-lg">
        <h2 class="text-2xl font-bold mb-6">⚙️ Configuración de Apariencia</h2>

        <!-- Selección de Tema -->
        <div class="mb-6">
            <label class="block text-sm font-semibold mb-3">Tema</label>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <button
                    v-for="t in themes"
                    :key="t.value"
                    @click="setTheme(t.value)"
                    :class="[
                        'p-4 border-2 rounded-lg text-left transition-all',
                        theme === t.value
                            ? 'border-current shadow-lg scale-105'
                            : 'border-gray-300 hover:border-gray-400'
                    ]"
                >
                    <div class="font-bold text-lg">{{ t.label }}</div>
                    <div class="text-sm text-secondary">{{ t.description }}</div>
                </button>
            </div>
        </div>

        <!-- Modo Claro/Oscuro -->
        <div class="mb-6">
            <label class="block text-sm font-semibold mb-3">Modo</label>
            <button
                @click="toggleMode"
                class="btn-primary px-6 py-3 rounded-lg font-semibold transition-all hover:scale-105"
            >
                {{ mode === 'light' ? '☀️ Modo Claro' : '🌙 Modo Oscuro' }}
            </button>
        </div>

        <!-- Tamaño de Fuente -->
        <div class="mb-6">
            <label class="block text-sm font-semibold mb-3">Tamaño de texto</label>
            <div class="flex gap-3">
                <button
                    v-for="size in fontSizes"
                    :key="size.value"
                    @click="setFontSize(size.value)"
                    :class="[
                        'px-4 py-2 border-2 rounded-lg transition-all',
                        fontSize === size.value
                            ? 'btn-primary border-transparent'
                            : 'border-gray-300 hover:border-gray-400'
                    ]"
                >
                    {{ size.label }}
                </button>
            </div>
        </div>

        <!-- Alto Contraste -->
        <div class="mb-6">
            <label class="flex items-center gap-3 cursor-pointer">
                <input
                    type="checkbox"
                    :checked="highContrast"
                    @change="toggleHighContrast"
                    class="w-5 h-5 rounded"
                />
                <span class="font-semibold">🎨 Alto contraste (accesibilidad)</span>
            </label>
        </div>

        <!-- Vista previa -->
        <div class="mt-6 p-4 card rounded-lg border">
            <h3 class="font-bold mb-2">Vista previa</h3>
            <p class="text-secondary">
                Este es un texto de ejemplo con el tema <strong>{{ theme }}</strong> en modo <strong>{{ mode }}</strong>.
            </p>
            <button class="btn-primary mt-3 px-4 py-2 rounded">Botón de ejemplo</button>
        </div>
    </div>
</template>

<style scoped>
.theme-selector {
    max-width: 800px;
}
</style>
