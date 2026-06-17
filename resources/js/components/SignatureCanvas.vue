<script setup lang="ts">
import { ref, onMounted, onBeforeUnmount } from 'vue';
import SignaturePad from 'signature_pad';

const emit = defineEmits<{
    (e: 'save', base64Data: string): void;
    (e: 'cancel'): void;
}>();

const canvasRef = ref<HTMLCanvasElement | null>(null);
let signaturePadInstance: SignaturePad | null = null;

// Calibración de resolución y pixeles estables
const resizeCanvas = () => {
    if (canvasRef.value) {
        const canvas = canvasRef.value;
        const ratio = Math.max(window.devicePixelRatio || 1, 1);
        
        // Guardamos temporalmente la firma por si el usuario gira la pantalla del celular
        const data = signaturePadInstance?.toData();
        
        canvas.width = canvas.offsetWidth * ratio;
        canvas.height = canvas.offsetHeight * ratio;
        
        const ctx = canvas.getContext("2d");
        if (ctx) {
            ctx.scale(ratio, ratio);
        }
        
        // Limpiamos y reasignamos el fondo blanco nativo de la librería
        signaturePadInstance?.clear();
        
        // Si había un trazo previo al redimensionar, lo restauramos
        if (data) {
            signaturePadInstance?.fromData(data);
        }
    }
};

onMounted(() => {
    if (canvasRef.value) {
        // 🎯 CONFIGURACIÓN CORRECTA: Seteamos el fondo blanco directamente en la inicialización
        signaturePadInstance = new SignaturePad(canvasRef.value, {
            backgroundColor: 'rgb(255, 255, 255)', // <-- Obliga a que el fondo del PNG sea blanco nativo
            penColor: 'rgb(0, 0, 0)',               // Trazo negro institucional
            throttle: 16
        });
        
        resizeCanvas();
        window.addEventListener('resize', resizeCanvas);
    }
});

onBeforeUnmount(() => {
    window.removeEventListener('resize', resizeCanvas);
});

// Limpieza absoluta del lienzo sincronizada con la instancia
const clearCanvas = () => {
    signaturePadInstance?.clear(); // Al tener el backgroundColor seteado arriba, clear() vuelve automáticamente a blanco sólido
};

// Despacho del Base64 corregido al formulario padre
const handleSave = () => {
    // Ahora isEmpty() funcionará perfectamente porque ya no hay interferencia del contexto 2D manual
    if (signaturePadInstance && !signaturePadInstance.isEmpty()) {
        const base64Data = signaturePadInstance.toDataURL('image/png');
        emit('save', base64Data); 
    } else {
        alert('Por favor, plasma tu firma en el lienzo antes de continuar.');
    }
};
</script>

<template>
    <div class="space-y-4">
        <div class="border border-neutral-200 dark:border-neutral-700 rounded-xl bg-white overflow-hidden relative shadow-inner">
            <canvas ref="canvasRef" class="w-full h-44 cursor-crosshair touch-none block bg-white"></canvas>
            <div class="absolute bottom-2 left-3 text-[10px] uppercase font-mono text-neutral-400 select-none pointer-events-none no-print">
                Área de trazo manual
            </div>
        </div>

        <div class="flex justify-between items-center">
            <button type="button" @click="clearCanvas" class="px-3 py-1.5 rounded-lg text-xs font-semibold border border-neutral-300 dark:border-neutral-700 text-neutral-700 dark:text-neutral-300 hover:bg-neutral-100 dark:hover:bg-neutral-800 transition-colors">
                Limpiar Lienzo
            </button>
            
            <div class="flex gap-2">
                <button type="button" @click="emit('cancel')" class="px-3 py-1.5 rounded-lg text-xs font-medium text-neutral-500 hover:text-neutral-700 dark:text-neutral-400 dark:hover:text-neutral-200">
                    Cancelar
                </button>
                <button type="button" @click="handleSave" class="px-4 py-1.5 rounded-lg text-xs font-bold text-white bg-neutral-900 hover:bg-neutral-800 dark:bg-neutral-50 dark:text-neutral-900 dark:hover:bg-neutral-200 transition-colors shadow-sm">
                    Confirmar Trazo y Firmar
                </button>
            </div>
        </div>
    </div>
</template>