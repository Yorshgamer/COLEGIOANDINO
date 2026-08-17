<script setup lang="ts">
// Estático por ahora, sin lógica pesada externa
</script>

<template>
    <footer class="bg-neutral-900 border-t border-neutral-800 text-neutral-400 py-12 text-xs mt-auto">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="space-y-3">
                <h4 class="text-sm font-black uppercase tracking-wider text-white border-l-2 border-[#af0c27] pl-2.5">Contacto</h4>
                <p>📍 Jr. Guido 512, Huancayo - Perú</p>
                <p>📞 064 232521 | 📱 944 688 081</p>
                <p>✉️ secretaria@andino.edu.pe</p>
            </div>
            <div class="space-y-3">
                <h4 class="text-sm font-black uppercase tracking-wider text-white border-l-2 border-[#af0c27] pl-2.5">Enlaces Rápidos</h4>
                <ul class="space-y-2">
                    <li><a href="#" class="hover:text-white transition-colors">Proceso de Admisión</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Libro de Reclamaciones</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Trabaja con Nosotros</a></li>
                </ul>
            </div>
            <div class="space-y-3">
                <h4 class="text-sm font-black uppercase tracking-wider text-white border-l-2 border-[#af0c27] pl-2.5">Colegio Andino</h4>
                <p class="leading-relaxed opacity-80">112 años de excelencia educativa formando jóvenes líderes con firmes principios humanistas en la región central del país.</p>
            </div>
        </div>
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 mt-10 pt-6 border-t border-neutral-800/60 text-center opacity-60">
            &copy; {{ new Date().getFullYear() }} Colegio Particular Andino. Todos los derechos reservados.
        </div>
    </footer>
</template>