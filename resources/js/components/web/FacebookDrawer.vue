<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue';
import { X, ExternalLink } from '@lucide/vue';

const isOpen = ref(false);
const facebookUrl = 'https://www.facebook.com/colegio.particular.andino';

const toggleDrawer = () => {
    isOpen.value = !isOpen.value;
    if (isOpen.value) {
        document.body.style.overflow = 'hidden';
    } else {
        document.body.style.overflow = 'auto';
    }
};

const closeDrawer = () => {
    isOpen.value = false;
    document.body.style.overflow = 'auto';
};

const handleKeyDown = (e: KeyboardEvent) => {
    if (e.key === 'Escape' && isOpen.value) {
        closeDrawer();
    }
};

onMounted(() => {
    window.addEventListener('keydown', handleKeyDown);
});

onUnmounted(() => {
    window.removeEventListener('keydown', handleKeyDown);
    document.body.style.overflow = 'auto';
});
</script>

<template>
    <div>
        <!-- Pestaña lateral flotante fija (Borde Derecho) -->
        <button
            @click="toggleDrawer"
            type="button"
            aria-label="Abrir Facebook del Colegio Andino"
            class="fixed right-0 top-1/2 -translate-y-1/2 z-40 flex items-center gap-2 bg-[#1877f2] hover:bg-[#166fe5] text-white px-3.5 py-3 rounded-l-2xl shadow-2xl transition-all duration-300 transform-gpu hover:-translate-x-1.5 focus:outline-none focus:ring-2 focus:ring-[#1877f2] focus:ring-offset-2 select-none group"
        >
            <!-- Ícono SVG Facebook -->
            <svg class="size-6 fill-current shrink-0 group-hover:scale-110 transition-transform" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
            </svg>

            <!-- Texto vertical -->
            <span class="[writing-mode:vertical-rl] rotate-180 font-display font-black text-xs uppercase tracking-widest leading-none py-1">
                Facebook
            </span>
        </button>

        <!-- Overlay de fondo oscuro -->
        <Transition
            enter-active-class="transition-opacity duration-300 ease-out"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition-opacity duration-300 ease-in"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                v-if="isOpen"
                @click="closeDrawer"
                class="fixed inset-0 z-50 bg-black/60 backdrop-blur-xs"
            ></div>
        </Transition>

        <!-- Panel Lateral Deslizable -->
        <Transition
            enter-active-class="transition-transform duration-300 ease-out"
            enter-from-class="translate-x-full"
            enter-to-class="translate-x-0"
            leave-active-class="transition-transform duration-300 ease-in"
            leave-from-class="translate-x-0"
            leave-to-class="translate-x-full"
        >
            <aside
                v-if="isOpen"
                class="fixed top-0 right-0 z-50 h-full w-full max-w-[420px] bg-white shadow-2xl flex flex-col font-sans"
            >
                <!-- Cabecera del Panel -->
                <div class="bg-[#1a1d56] p-5 text-white flex items-center justify-between shadow-md shrink-0">
                    <div class="flex items-center gap-3">
                        <div class="size-10 rounded-xl bg-[#1877f2] flex items-center justify-center text-white shadow-sm">
                            <svg class="size-5 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-display font-black text-base text-white leading-tight">Colegio Andino</h3>
                            <p class="text-xs text-neutral-300">Página oficial de Facebook</p>
                        </div>
                    </div>

                    <button
                        @click="closeDrawer"
                        type="button"
                        class="p-2 rounded-xl text-neutral-300 hover:text-white hover:bg-white/10 transition-colors"
                        title="Cerrar panel"
                    >
                        <X class="size-5" />
                    </button>
                </div>

                <!-- Contenido con el Feed embebido -->
                <div class="flex-1 overflow-y-auto bg-neutral-100 p-4 flex justify-center items-start">
                    <div class="w-full max-w-[360px] bg-white rounded-2xl shadow-sm overflow-hidden border border-neutral-200">
                        <iframe
                            :src="`https://www.facebook.com/plugins/page.php?href=${encodeURIComponent(facebookUrl)}&tabs=timeline&width=360&height=650&small_header=false&adapt_container_width=true&hide_cover=false&show_facepile=true`"
                            width="360"
                            height="650"
                            style="border:none;overflow:hidden"
                            scrolling="no"
                            frameborder="0"
                            allowfullscreen="true"
                            allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share"
                            class="w-full h-[650px]"
                        ></iframe>
                    </div>
                </div>

                <!-- Pie del Panel con botón para abrir en Facebook -->
                <div class="p-4 bg-white border-t border-neutral-200/80 shrink-0">
                    <a
                        :href="facebookUrl"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="w-full inline-flex items-center justify-center gap-2 bg-[#1877f2] hover:bg-[#166fe5] text-white font-bold py-3 px-4 rounded-xl text-xs uppercase tracking-wider shadow-md hover:shadow-lg transition-all"
                    >
                        <span>Visitar en Facebook</span>
                        <ExternalLink class="size-4" />
                    </a>
                </div>
            </aside>
        </Transition>
    </div>
</template>

<style scoped>
.font-display,
.font-sans {
    font-family: 'Gilroy', ui-sans-serif, system-ui, sans-serif;
}
</style>