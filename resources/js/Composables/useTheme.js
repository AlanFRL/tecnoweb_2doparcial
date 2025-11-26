import { ref, watch, onMounted } from 'vue';

// Valores por defecto
const STORAGE_KEY = 'lavanderia-belen-theme';

const theme = ref('classic'); // kids, young, classic
const mode = ref('light'); // light, dark
const fontSize = ref('normal'); // normal, large, xlarge
const highContrast = ref(false);

// Cargar configuración guardada
function loadThemeFromStorage() {
    try {
        const saved = localStorage.getItem(STORAGE_KEY);
        if (saved) {
            const config = JSON.parse(saved);
            theme.value = config.theme || 'classic';
            mode.value = config.mode || 'light';
            fontSize.value = config.fontSize || 'normal';
            highContrast.value = config.highContrast || false;
        }
    } catch (e) {
        console.error('Error al cargar tema:', e);
    }
}

// Guardar configuración
function saveThemeToStorage() {
    const config = {
        theme: theme.value,
        mode: mode.value,
        fontSize: fontSize.value,
        highContrast: highContrast.value,
    };
    localStorage.setItem(STORAGE_KEY, JSON.stringify(config));
}

// Aplicar tema al body
function applyTheme() {
    // Remover todas las clases de tema anteriores
    document.body.classList.remove(
        'theme-kids', 'theme-young', 'theme-classic',
        'light', 'dark',
        'font-normal', 'font-large', 'font-xlarge',
        'high-contrast'
    );

    // Aplicar nuevas clases
    document.body.classList.add(`theme-${theme.value}`);
    document.body.classList.add(mode.value);
    document.body.classList.add(`font-${fontSize.value}`);
    
    if (highContrast.value) {
        document.body.classList.add('high-contrast');
    }

    saveThemeToStorage();
}

// Composable principal
export function useTheme() {
    onMounted(() => {
        loadThemeFromStorage();
        applyTheme();
    });

    // Watchers para aplicar cambios automáticamente
    watch([theme, mode, fontSize, highContrast], () => {
        applyTheme();
    });

    // Métodos para cambiar configuración
    const setTheme = (newTheme) => {
        theme.value = newTheme;
    };

    const toggleMode = () => {
        mode.value = mode.value === 'light' ? 'dark' : 'light';
    };

    const setMode = (newMode) => {
        mode.value = newMode;
    };

    const setFontSize = (newSize) => {
        fontSize.value = newSize;
    };

    const toggleHighContrast = () => {
        highContrast.value = !highContrast.value;
    };

    const setHighContrast = (value) => {
        highContrast.value = value;
    };

    // Detectar preferencia del sistema
    const detectSystemPreference = () => {
        if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
            mode.value = 'dark';
        } else {
            mode.value = 'light';
        }
    };

    return {
        // Estado
        theme,
        mode,
        fontSize,
        highContrast,
        
        // Métodos
        setTheme,
        toggleMode,
        setMode,
        setFontSize,
        toggleHighContrast,
        setHighContrast,
        detectSystemPreference,
        applyTheme,
    };
}
