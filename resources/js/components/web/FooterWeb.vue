<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { MapPin, Phone, Smartphone, Mail,Images } from '@lucide/vue';

// Miniaturas para el footer
const footerGallery = [
    '/img/Galeria/DSC04150.webp',
    '/img/Galeria/DSC04240.webp',
    '/img/Galeria/DSC04306.webp',
];
</script>

<template>
    <footer class="bg-neutral-900 border-t border-neutral-800 text-neutral-400 py-12 text-xs mt-auto font-sans">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-10">
            
            <!-- Columna 1: Enlaces Rápidos -->
            <div class="space-y-4">
                <h4 class="text-sm font-black uppercase tracking-wider text-white border-l-2 border-[#ae0c26] pl-2.5">
                    Enlaces Rápidos
                </h4>
                <ul class="space-y-2.5 text-neutral-300">
                    <li>
                        <Link href="/mision-vision" class="hover:text-white hover:underline transition-all">
                            Nosotros
                        </Link>
                    </li>
                    <li>
                        <Link href="/admision" class="hover:text-white hover:underline transition-all">
                            Proceso de Admisión
                        </Link>
                    </li>
                    <li>
                        <a href="/libro-de-reclamaciones" target="_blank" class="hover:text-white hover:underline transition-all">
                            Libro de Reclamaciones
                        </a>
                    </li>
                    <li>
                        <Link href="/trabaja-nosotros" class="hover:text-white hover:underline transition-all">
                            Trabaja con Nosotros
                        </Link>
                    </li>
                    <li>
                        <Link href="/social" class="hover:text-white hover:underline transition-all">
                            Responsabilidad Social
                        </Link>
                    </li>
                </ul>
            </div>

            <!-- Columna 2: Contacto & Redes Sociales -->
            <div class="space-y-4">
                <h4 class="text-sm font-black uppercase tracking-wider text-white border-l-2 border-[#ae0c26] pl-2.5">
                    Contacto
                </h4>
                <div class="space-y-2 text-neutral-300">
                    <p class="flex items-center gap-2">
                        <MapPin class="size-4 text-[#ae0c26] shrink-0" />
                        <span>Jr. Guido 512, Huancayo - Perú</span>
                    </p>
                    <p class="flex items-center gap-2">
                        <Phone class="size-4 text-[#ae0c26] shrink-0" />
                        <span>064 232521</span>
                        <span class="text-neutral-500">|</span>
                        <Smartphone class="size-4 text-[#ae0c26] shrink-0" />
                        <span>944 688 081</span>
                    </p>
                    <p class="flex items-center gap-2">
                        <Mail class="size-4 text-[#ae0c26] shrink-0" />
                        <a href="mailto:secretaria@andino.edu.pe" class="hover:text-white transition-colors">
                            secretaria@andino.edu.pe
                        </a>
                    </p>
                </div>

                <!-- 4 Bolitas de Redes Sociales -->
                <div class="pt-2">
                    <div class="flex items-center gap-2.5">
                        <!-- Facebook -->
                        <a href="https://www.facebook.com/profile.php?id=61564440576138" target="_blank" rel="noopener noreferrer"
                            aria-label="Facebook Colegio Andino"
                            class="size-8 rounded-full bg-neutral-800 text-white flex items-center justify-center hover:bg-[#ae0c26] hover:scale-110 transition-all duration-300 shadow-sm">
                            <svg class="size-3.5 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                        </a>

                        <!-- YouTube -->
                        <a href="https://www.youtube.com/@colegioandino1575" target="_blank" rel="noopener noreferrer"
                            aria-label="YouTube Colegio Andino"
                            class="size-8 rounded-full bg-neutral-800 text-white flex items-center justify-center hover:bg-[#ae0c26] hover:scale-110 transition-all duration-300 shadow-sm">
                            <svg class="size-3.5 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.016 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                            </svg>
                        </a>

                        <!-- Instagram -->
                        <a href="https://www.instagram.com/colegioandinohyo/" target="_blank" rel="noopener noreferrer"
                            aria-label="Instagram Colegio Andino"
                            class="size-8 rounded-full bg-neutral-800 text-white flex items-center justify-center hover:bg-[#ae0c26] hover:scale-110 transition-all duration-300 shadow-sm">
                            <svg class="size-3.5 stroke-current fill-none stroke-2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" xmlns="http://www.w3.org/2000/svg">
                                <rect width="20" height="20" x="2" y="2" rx="5" ry="5"/>
                                <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/>
                                <line x1="17.5" x2="17.51" y1="6.5" y2="6.5"/>
                            </svg>
                        </a>

                        <!-- TikTok -->
                        <a href="https://www.tiktok.com/@colegioandinohyo" target="_blank" rel="noopener noreferrer"
                            aria-label="TikTok Colegio Andino"
                            class="size-8 rounded-full bg-neutral-800 text-white flex items-center justify-center hover:bg-[#ae0c26] hover:scale-110 transition-all duration-300 shadow-sm">
                            <svg class="size-3 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-5.2 1.74 2.89 2.89 0 0 1 2.31-4.64 2.93 2.83 0 0 1 .88.13V9.4a6.84 6.84 0 0 0-1-.05A6.33 6.33 0 0 0 3 15.68a6.34 6.33 0 0 0 10.86 4.43v-7a8.16 8.16 0 0 0 4.77 1.52v-3.4a4.85 4.85 0 0 1-1-.05z"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Columna 3: Galería (Nueva) -->
            <div class="space-y-4">
                <div class="flex items-center justify-between border-l-2 border-[#ae0c26] pl-2.5">
                    <h4 class="text-sm font-black uppercase tracking-wider text-white">
                        Galería
                    </h4>
                    <Link href="/galeria" class="text-[10px] text-[#ae0c26] hover:underline font-bold uppercase tracking-wider">
                        Ver todas
                    </Link>
                </div>

                <!-- Grid de 6 fotos pequeñas -->
                <div class="grid grid-cols-3 gap-2">
                    <Link v-for="(photo, index) in footerGallery" :key="index" href="/galeria"
                        class="aspect-square rounded-lg overflow-hidden bg-neutral-800 border border-neutral-700/60 block group relative">
                        <img :src="photo" alt="Galería Colegio Andino" loading="lazy"
                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300" />
                        <div class="absolute inset-0 bg-[#ae0c26]/20 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    </Link>
                </div>
            </div>
            
            <!-- Columna 4: Colegio Andino -->
            <div class="space-y-4">
                <h4 class="text-sm font-black uppercase tracking-wider text-white border-l-2 border-[#ae0c26] pl-2.5">
                    Colegio Andino
                </h4>
                <p class="leading-relaxed opacity-80 text-justify">
                    112 años de excelencia educativa formando jóvenes líderes con firmes principios humanistas y cristianos en la región central del país.
                </p>
            </div>
        </div>

        <!-- Copyright -->
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 mt-10 pt-6 border-t border-neutral-800/60 text-center opacity-60">
            &copy; {{ new Date().getFullYear() }} Colegio Particular Andino. Todos los derechos reservados.
        </div>
    </footer>
</template>