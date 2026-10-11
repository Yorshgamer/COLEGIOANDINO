<script setup lang="ts">
import WebLayout from '@/layouts/web/WebLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { onMounted, onUnmounted, ref } from 'vue';
import { X, ChevronLeft, ChevronRight, ZoomIn, Images } from '@lucide/vue'

defineProps<{
    title?: string;
}>();

const sectionRef = ref<HTMLElement | null>(null);

// Lista de fotos de la galería institucional
const galleryImages = [
    '/img/Galeria/DSC03895.webp',
    '/img/Galeria/DSC03944.webp',
    '/img/Galeria/DSC03982.webp',
    '/img/Galeria/DSC04116.webp',
    '/img/Galeria/DSC04150.webp',
    '/img/Galeria/DSC04240.webp',
    '/img/Galeria/DSC04306.webp',
    '/img/Galeria/DSC05021.webp',
    '/img/Galeria/DSC05051.webp',
    '/img/Galeria/DSC05306.webp',
    '/img/Galeria/DSC05341.webp',
    '/img/Galeria/DSC05363.webp',
    '/img/Galeria/DSC05398.webp',
    '/img/Galeria/DSC05417.webp',
    '/img/Galeria/DSC05467.webp',
    '/img/Galeria/DSC05482.webp',
    '/img/Galeria/DSC05522.webp',
    '/img/Galeria/DSC05612.webp',
    '/img/Galeria/DSC05637.webp',
    '/img/Galeria/DSC05650.webp',
    '/img/Galeria/DSC05659.webp',
    '/img/Galeria/DSC06254.webp',
];

// Estado de Lightbox interactivo
const isLightboxOpen = ref(false);
const currentIndex = ref(0);

const openLightbox = (index: number) => {
    currentIndex.value = index;
    isLightboxOpen.value = true;
    document.body.style.overflow = 'hidden';
};

const closeLightbox = () => {
    isLightboxOpen.value = false;
    document.body.style.overflow = 'auto';
};

const nextImage = () => {
    currentIndex.value = (currentIndex.value + 1) % galleryImages.length;
};

const prevImage = () => {
    currentIndex.value = (currentIndex.value - 1 + galleryImages.length) % galleryImages.length;
};

// Navegación con teclado (ESC, Izquierda, Derecha)
const handleKeyDown = (e: KeyboardEvent) => {
    if (!isLightboxOpen.value) return;

    if (e.key === 'Escape') closeLightbox();
    if (e.key === 'ArrowRight') nextImage();
    if (e.key === 'ArrowLeft') prevImage();
};

onMounted(() => {
    window.addEventListener('keydown', handleKeyDown);

    const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    if (prefersReduced || !sectionRef.value) return;

    const targets = sectionRef.value.querySelectorAll('[data-reveal]');
    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry, i) => {
                if (entry.isIntersecting) {
                    setTimeout(() => entry.target.classList.add('is-visible'), (i % 6) * 70);
                    observer.unobserve(entry.target);
                }
            });
        },
        { threshold: 0.1 }
    );

    targets.forEach((el) => observer.observe(el));
});

onUnmounted(() => {
    window.removeEventListener('keydown', handleKeyDown);
    document.body.style.overflow = 'auto';
});
</script>

<template>
    <Head :title="title || 'Galería - Colegio Andino'" />

    <WebLayout>
        <!-- Header con Breadcrumb -->
        <section class="relative bg-gradient-to-b from-neutral-100 to-white py-14 md:py-20 border-b border-neutral-200/60 overflow-hidden font-sans">
            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-24 h-1 bg-gradient-to-r from-[#1a1d56] to-[#ae0c26] rounded-full"></div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <span class="text-xs font-bold uppercase tracking-[0.25em] text-[#ae0c26]/80 block mb-3">
                    Momentos y Experiencias
                </span>
                <h1 class="font-display text-4xl md:text-6xl font-black text-[#1a1d56] tracking-tight mb-4 uppercase">
                    Galería
                </h1>
                <nav aria-label="Breadcrumb" class="flex justify-center items-center gap-2 text-sm font-semibold">
                    <Link href="/" class="text-[#ae0c26] hover:underline transition-all">Inicio</Link>
                    <span class="text-neutral-400">/</span>
                    <span class="text-neutral-500">Galería</span>
                </nav>
            </div>
        </section>

        <!-- Sección de Galería Masonry -->
        <section ref="sectionRef" class="py-16 md:py-24 bg-white font-sans">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                
                <!-- Título introductorio -->
                <div class="text-center mb-12 md:mb-16" data-reveal>
                    <span class="text-xs font-black uppercase tracking-[0.2em] text-[#ae0c26] block mb-2">
                        Vida Estudiantil
                    </span>
                    <h2 class="font-display text-3xl md:text-5xl font-black text-[#1a1d56]">
                        Nuestra Comunidad en Imágenes
                    </h2>
                    <div class="w-20 h-1 bg-[#1a1d56] mx-auto rounded-full mt-4"></div>
                </div>

                <!-- Masonry Layout con columnas CSS -->
                <div class="columns-1 sm:columns-2 lg:columns-3 gap-6 space-y-6">
                    <div
                        v-for="(img, index) in galleryImages"
                        :key="index"
                        @click="openLightbox(index)"
                        data-reveal
                        class="break-inside-avoid relative rounded-2xl overflow-hidden shadow-md hover:shadow-2xl border border-neutral-100/80 group cursor-pointer bg-neutral-100 transition-all duration-300 transform hover:-translate-y-1">
                        
                        <img
                            :src="img"
                            alt="Galería Colegio Andino"
                            loading="lazy"
                            class="w-full h-auto object-cover group-hover:scale-105 transition-transform duration-500 block"
                        />

                        <!-- Overlay al pasar el mouse -->
                        <div class="absolute inset-0 bg-gradient-to-t from-[#1a1d56]/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end justify-between p-5 text-white">
                            <span class="text-xs font-bold uppercase tracking-wider flex items-center gap-1.5">
                                <Images class="size-4 text-[#ae0c26]" />
                                Colegio Andino
                            </span>
                            <div class="size-9 rounded-full bg-white/20 backdrop-blur-xs flex items-center justify-center text-white">
                                <ZoomIn class="size-5" />
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section>

        <!-- Lightbox Modal -->
        <Teleport to="body">
            <div
                v-if="isLightboxOpen"
                @click="closeLightbox"
                class="fixed inset-0 z-[100] bg-black/95 backdrop-blur-md flex items-center justify-center p-4 sm:p-8 select-none">
                
                <!-- Botón Cerrar -->
                <button
                    @click.stop="closeLightbox"
                    class="absolute top-6 right-6 z-[110] size-12 rounded-full bg-white/10 hover:bg-[#ae0c26] text-white flex items-center justify-center transition-colors shadow-lg"
                    title="Cerrar (Esc)">
                    <X class="size-6" />
                </button>

                <!-- Botón Anterior -->
                <button
                    @click.stop="prevImage"
                    class="absolute left-4 sm:left-8 top-1/2 -translate-y-1/2 z-[110] size-12 sm:size-14 rounded-full bg-white/10 hover:bg-[#ae0c26] text-white flex items-center justify-center transition-all shadow-lg"
                    title="Anterior (Flecha izquierda)">
                    <ChevronLeft class="size-7 sm:size-8" />
                </button>

                <!-- Contenedor de la Imagen Activa -->
                <div @click.stop class="relative max-w-5xl max-h-[85vh] flex flex-col items-center justify-center">
                    <img
                        :src="galleryImages[currentIndex]"
                        alt="Vista ampliada - Colegio Andino"
                        class="max-w-full max-h-[80vh] w-auto h-auto object-contain rounded-2xl shadow-2xl"
                    />
                    
                    <!-- Contador inferior -->
                    <div class="mt-4 px-4 py-1.5 rounded-full bg-white/10 backdrop-blur-md text-white text-xs font-bold tracking-wider">
                        {{ currentIndex + 1 }} / {{ galleryImages.length }}
                    </div>
                </div>

                <!-- Botón Siguiente -->
                <button
                    @click.stop="nextImage"
                    class="absolute right-4 sm:right-8 top-1/2 -translate-y-1/2 z-[110] size-12 sm:size-14 rounded-full bg-white/10 hover:bg-[#ae0c26] text-white flex items-center justify-center transition-all shadow-lg"
                    title="Siguiente (Flecha derecha)">
                    <ChevronRight class="size-7 sm:size-8" />
                </button>
            </div>
        </Teleport>
    </WebLayout>
</template>

<style scoped>
.font-display,
.font-sans {
    font-family: 'Gilroy', ui-sans-serif, system-ui, sans-serif;
}

[data-reveal] {
    opacity: 0;
    transform: translateY(16px);
    transition: opacity 0.6s ease-out, transform 0.6s ease-out;
}

[data-reveal].is-visible {
    opacity: 1;
    transform: translateY(0);
}

@media (prefers-reduced-motion: reduce) {
    [data-reveal] {
        opacity: 1;
        transform: none;
        transition: none;
    }
}
</style>