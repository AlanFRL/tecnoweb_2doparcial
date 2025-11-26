<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
import { useTheme } from '@/Composables/useTheme';
import { onMounted } from 'vue';

const { applyTheme } = useTheme();

onMounted(() => {
    applyTheme();
});
</script>

<template>
    <div class="guest-container min-h-screen flex flex-col items-center justify-center px-4 py-12 sm:px-6 lg:px-8">
        <div class="guest-card w-full max-w-md">
            <slot />
        </div>
    </div>
</template>

<style scoped>
.guest-container {
    background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-secondary) 100%);
    font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.guest-card {
    background: white;
    border-radius: 1.5rem;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
    padding: 2.5rem;
    animation: slideIn 0.4s ease-out;
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@media (max-width: 640px) {
    .guest-card {
        padding: 1.5rem;
    }
}
</style>
