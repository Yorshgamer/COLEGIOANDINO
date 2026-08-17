<script setup lang="ts">
import { PenTool, X, ShieldCheck } from '@lucide/vue';

defineProps<{
    open: boolean;
    title?: string;
    message?: string;
}>();

const emit = defineEmits(['close', 'confirm']);
</script>

<template>
    <div v-if="open" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-xs p-4 transition-all animate-in fade-in duration-200">
        <div class="w-full max-w-md shadow-2xl bg-white dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-800 rounded-2xl p-6 relative flex flex-col gap-4 animate-in zoom-in-95 duration-200">
            
            <button @click="emit('close')" type="button" class="absolute top-4 right-4 rounded-md p-1 text-neutral-400 hover:bg-neutral-100 hover:text-neutral-600 dark:hover:bg-neutral-800 dark:hover:text-neutral-200 transition-colors">
                <X class="size-4" />
            </button>

            <div class="flex items-start gap-3.5">
                <div class="p-2.5 bg-green-50 dark:bg-green-500/10 text-green-600 dark:text-green-400 rounded-xl shrink-0 shadow-2xs">
                    <PenTool class="size-5" />
                </div>
                <div class="space-y-1 pr-6">
                    <h3 class="text-base font-bold text-neutral-900 dark:text-neutral-50 tracking-tight uppercase">
                        {{ title ?? 'Declaración de Conformidad' }}
                    </h3>
                    <p class="text-xs text-neutral-500 dark:text-neutral-400 leading-relaxed pt-1">
                        {{ message ?? '¿Declaras tu absoluta conformidad con los montos y conceptos expuestos en este documento laboral y deseas estampar tu firma digital registrada?' }}
                    </p>
                </div>
            </div>

            <div class="rounded-xl border border-dashed border-neutral-200 dark:border-neutral-800 p-3 bg-neutral-50 dark:bg-neutral-950/40 flex items-center gap-2.5 text-neutral-500 dark:text-neutral-400">
                <ShieldCheck class="size-5 text-neutral-400 shrink-0" />
                <span class="text-[11px] font-medium leading-tight font-mono">
                    Este acto equivale a una firma manuscrita bajo las normativas de digitalización del Plantel.
                </span>
            </div>

            <div class="flex items-center justify-end gap-2 mt-2">
                <button 
                    @click="emit('close')" 
                    type="button" 
                    class="rounded-xl border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-800 px-4 py-2 text-xs font-bold text-neutral-700 dark:text-neutral-300 shadow-2xs hover:bg-neutral-50 dark:hover:bg-neutral-700/50 transition-colors"
                >
                    Cancelar
                </button>
                <button 
                    @click="emit('confirm')" 
                    type="button" 
                    class="rounded-xl bg-green-600 hover:bg-green-500 text-white px-4 py-2 text-xs font-bold shadow-sm transition-colors"
                >
                    Sí, Estampar Firma
                </button>
            </div>

        </div>
    </div>
</template>