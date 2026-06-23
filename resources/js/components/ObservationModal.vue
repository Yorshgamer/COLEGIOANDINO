<script setup lang="ts">
import {watch } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { AlertTriangle } from '@lucide/vue';

const props = defineProps<{
    boletaId: number | null;
    open: boolean;
}>();

const emit = defineEmits<{
    (e: 'close'): void;
    (e: 'success'): void;
}>();

const observeForm = useForm({
    observation_message: '',
});

// Sincroniza y limpia el formulario cada vez que se abre/cierra el modal
// Opción compacta directa
watch(() => props.open, (isOpen) => {
    if (isOpen) observeForm.reset();
});

const submitObservation = () => {
    if (!props.boletaId) return;
    
    observeForm.post(`/boletas/${props.boletaId}/observar`, {
        preserveScroll: true,
        onSuccess: () => {
            observeForm.reset();
            emit('success');
        }
    });
};
</script>

<template>
    <div v-if="open" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-xs p-4 animate-in fade-in duration-150">
        <div class="w-full max-w-md shadow-xl bg-white dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-800 rounded-xl p-6 animate-in zoom-in-95 duration-150">
            
            <div class="flex items-center gap-2 text-red-600 dark:text-red-400">
                <AlertTriangle class="size-5 shrink-0" />
                <h3 class="text-base font-bold uppercase tracking-tight">Sustento de Observación</h3>
            </div>
            
            <p class="text-xs text-neutral-400 mt-1">
                Detalla los motivos del reclamo de forma clara para que el área de Recursos Humanos pueda revisar tu caso a la brevedad.
            </p>
            
            <textarea 
                v-model="observeForm.observation_message" 
                placeholder="Ej: No se computaron mis horas extras de la primera semana o mi asignación familiar está desactualizada..." 
                rows="4" 
                :disabled="observeForm.processing"
                class="w-full rounded-md border border-neutral-200 dark:border-neutral-800 mt-4 p-3 text-xs focus:outline-none focus:ring-1 focus:ring-red-500 bg-background font-sans dark:text-white"
            ></textarea>
            
            <div class="flex justify-end gap-2 mt-4">
                <button 
                    type="button"
                    @click="emit('close')" 
                    :disabled="observeForm.processing" 
                    class="px-3 py-1.5 rounded-md border text-xs font-medium text-neutral-700 dark:text-neutral-300 bg-white dark:bg-neutral-800 hover:bg-neutral-50 transition-colors"
                >
                    Cancelar
                </button>
                <button 
                    type="button"
                    @click="submitObservation" 
                    :disabled="observeForm.processing || observeForm.observation_message.trim().length < 5" 
                    class="px-3 py-1.5 rounded-md text-xs font-bold text-white bg-red-600 hover:bg-red-700 disabled:opacity-50 transition-colors shadow-xs"
                >
                    {{ observeForm.processing ? 'Enviando...' : 'Enviar Observación' }}
                </button>
            </div>
        </div>
    </div>
</template>