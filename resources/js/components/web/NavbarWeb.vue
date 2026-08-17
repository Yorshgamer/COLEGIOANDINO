<script setup lang="ts">
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import {
    Menu, X, LayoutDashboard, LogIn, ExternalLink, Landmark,
    ChevronDown, GraduationCap, BookOpen, Users, Phone, FileText,
    Building2, Globe
} from '@lucide/vue';

type DropdownKey = 'about' | 'orientation' | 'access';

interface MenuItem {
    label: string;
    routeName?: string; // usa route() si existe la ruta real
    href?: string;       // usa href plano si aún no hay ruta
}

// ---- FUENTE ÚNICA DE DATOS ----
// Cambia aquí un link y se actualiza automáticamente en desktop Y en el menú móvil.
const aboutItems: MenuItem[] = [
    { label: 'Presentación', href: '/presentacion' },
    { label: 'Iglesia Metodista', href: '/iglesia-metodista' },
    { label: 'Misión y Visión', href: '/mision-vision' },
    { label: 'Historia', href: '/historia' },
];

const orientationItems: MenuItem[] = [
    { label: 'Orientación Educativa', href: '/orientacion' },
    { label: 'Capellanía', href: '/capellania' },
    { label: 'Coordinación Académica', href: '/coordinacion' },
    { label: 'Departamento de Psicología', href: '/psicologia' },
    { label: 'Actividades', href: '/actividades' },
];

const directLinks = [
    { label: 'Admisión', icon: FileText, href: '/admision' },
    { label: 'Boletín', icon: BookOpen, href: '#', badge: 'NUEVO' },
    { label: 'Matrículas', icon: null, href: '/matricula' },
    { label: 'Servicio', icon: null, href: '/servicio' },
    { label: 'Contacto', icon: Phone, href: '/contacto' },
];

const externalAccess = [
    { label: 'INTRANET', shortLabel: 'SIEWEB', url: 'https://andino.sieweb.com.pe/sistema/login', icon: Building2, style: 'light' },
    { label: 'WEBMAIL', shortLabel: 'WEBMAIL', url: 'https://login.microsoftonline.com', icon: Globe, style: 'blue' },
];

// ---- ESTADO ----
const page = usePage();
const authUser = computed(() => (page.props.auth as any)?.user ?? null);
const dashboardUrl = '/dashboard';
const loginUrl = '/login';

const activeDropdown = ref<DropdownKey | null>(null);
const mobileMenuOpen = ref(false);
const scrolled = ref(false);

const toggleDropdown = (menu: DropdownKey) => {
    activeDropdown.value = activeDropdown.value === menu ? null : menu;
};

const closeAllMenus = () => {
    activeDropdown.value = null;
    mobileMenuOpen.value = false;
};

const handleScroll = () => {
    scrolled.value = window.scrollY > 10;
};

const handleClickOutside = (e: MouseEvent) => {
    const nav = document.getElementById('main-nav');
    if (nav && !nav.contains(e.target as Node)) {
        activeDropdown.value = null;
    }
};

// Helper para resolver el href de un MenuItem, con fallback si la ruta no existe
const resolveHref = (item: MenuItem): string => {
    if (item.routeName) {
        try {
            // @ts-ignore - route() viene de Ziggy
            return route(item.routeName);
        } catch {
            return '#';
        }
    }
    return item.href ?? '#';
};

onMounted(() => {
    window.addEventListener('scroll', handleScroll);
    document.addEventListener('click', handleClickOutside);
});

onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll);
    document.removeEventListener('click', handleClickOutside);
});
</script>

<template>
    <nav id="main-nav" :class="[
        'sticky top-0 z-50 w-full transition-all duration-300',
        scrolled
            ? 'bg-[#ae0c26] shadow-[0_4px_24px_rgba(0,0,0,0.28)]'
            : 'bg-[#ae0c26] shadow-[0_2px_12px_rgba(0,0,0,0.18)]'
    ]">
        <div class="h-[2px] w-full bg-gradient-to-r from-transparent via-white/20 to-transparent"></div>

        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between" :class="scrolled ? 'h-[68px]' : 'h-[76px]'">

                <!-- LOGO -->
                <Link href="/" @click="closeAllMenus"
                    class="flex-shrink-0 group flex items-center gap-3 transition-opacity hover:opacity-90">
                    <img :class="['w-auto object-contain transition-all duration-300', scrolled ? 'h-[60px]' : 'h-[90px]']"
                        src="/img/nuevo_logo.png" alt="Colegio Andino" />
                    <div class="hidden sm:block w-px h-9 bg-white/20 ml-1"></div>
                </Link>

                <!-- NAV LINKS (DESKTOP) -->
                <div class="hidden lg:flex items-center">
                    <div class="flex items-center gap-0.5 text-[11px] font-semibold tracking-wider">

                        <!-- Dropdown: ¿Quiénes Somos? -->
                        <div class="relative">
                            <button @click.stop="toggleDropdown('about')" type="button" :class="[
                                'group flex items-center gap-1.5 px-3.5 py-2 rounded-lg transition-all duration-150',
                                activeDropdown === 'about' ? 'bg-white/15 text-white' : 'text-white/85 hover:bg-white/10 hover:text-white'
                            ]">
                                <Users class="size-3.5 opacity-70" />
                                <span>¿Quiénes Somos?</span>
                                <ChevronDown :class="['size-3 opacity-60 transition-transform duration-200', activeDropdown === 'about' ? 'rotate-180' : '']" />
                            </button>

                            <Transition enter-active-class="transition-all duration-200 ease-out"
                                enter-from-class="opacity-0 translate-y-[-6px] scale-[0.97]"
                                enter-to-class="opacity-100 translate-y-0 scale-100"
                                leave-active-class="transition-all duration-150 ease-in"
                                leave-from-class="opacity-100 translate-y-0 scale-100"
                                leave-to-class="opacity-0 translate-y-[-4px] scale-[0.98]">
                                <div v-if="activeDropdown === 'about'"
                                    class="absolute left-0 mt-2.5 w-52 origin-top-left z-[60]">
                                    <div class="rounded-2xl bg-white shadow-[0_8px_32px_rgba(0,0,0,0.15),0_2px_8px_rgba(0,0,0,0.08)] border border-neutral-100/80 overflow-hidden">
                                        <div class="px-4 pt-3 pb-2 border-b border-neutral-100">
                                            <p class="text-[9px] font-black text-[#ae0c26] tracking-[0.12em] uppercase">Nuestra Institución</p>
                                        </div>
                                        <div class="p-1.5">
                                            <Link v-for="item in aboutItems" :key="item.label" :href="resolveHref(item)"
                                                @click="closeAllMenus"
                                                class="flex items-center gap-2.5 rounded-xl px-3 py-2.5 text-[11.5px] font-semibold text-neutral-600 hover:bg-red-50 hover:text-[#ae0c26] transition-colors group">
                                                <span class="w-1 h-1 rounded-full bg-neutral-300 group-hover:bg-[#ae0c26] transition-colors"></span>
                                                {{ item.label }}
                                            </Link>
                                        </div>
                                    </div>
                                </div>
                            </Transition>
                        </div>

                        <!-- Dropdown: Orientación Educativa -->
                        <div class="relative">
                            <button @click.stop="toggleDropdown('orientation')" type="button" :class="[
                                'group flex items-center gap-1.5 px-3.5 py-2 rounded-lg transition-all duration-150',
                                activeDropdown === 'orientation' ? 'bg-white/15 text-white' : 'text-white/85 hover:bg-white/10 hover:text-white'
                            ]">
                                <GraduationCap class="size-3.5 opacity-70" />
                                <span>Orientación</span>
                                <ChevronDown :class="['size-3 opacity-60 transition-transform duration-200', activeDropdown === 'orientation' ? 'rotate-180' : '']" />
                            </button>

                            <Transition enter-active-class="transition-all duration-200 ease-out"
                                enter-from-class="opacity-0 translate-y-[-6px] scale-[0.97]"
                                enter-to-class="opacity-100 translate-y-0 scale-100"
                                leave-active-class="transition-all duration-150 ease-in"
                                leave-from-class="opacity-100 translate-y-0 scale-100"
                                leave-to-class="opacity-0 translate-y-[-4px] scale-[0.98]">
                                <div v-if="activeDropdown === 'orientation'"
                                    class="absolute left-0 mt-2.5 w-60 origin-top-left z-[60]">
                                    <div class="rounded-2xl bg-white shadow-[0_8px_32px_rgba(0,0,0,0.15),0_2px_8px_rgba(0,0,0,0.08)] border border-neutral-100/80 overflow-hidden">
                                        <div class="px-4 pt-3 pb-2 border-b border-neutral-100">
                                            <p class="text-[9px] font-black text-[#ae0c26] tracking-[0.12em] uppercase">Área Educativa</p>
                                        </div>
                                        <div class="p-1.5">
                                            <Link v-for="item in orientationItems" :key="item.label" :href="resolveHref(item)"
                                                @click="closeAllMenus"
                                                class="flex items-center gap-2.5 rounded-xl px-3 py-2.5 text-[11.5px] font-semibold text-neutral-600 hover:bg-red-50 hover:text-[#ae0c26] transition-colors group">
                                                <span class="w-1 h-1 rounded-full bg-neutral-300 group-hover:bg-[#ae0c26] transition-colors"></span>
                                                {{ item.label }}
                                            </Link>
                                        </div>
                                    </div>
                                </div>
                            </Transition>
                        </div>

                        <!-- Links directos -->
                        <Link v-for="link in directLinks" :key="link.label" :href="link.href"
                            class="relative flex items-center gap-1.5 px-3.5 py-2 rounded-lg text-white/85 hover:bg-white/10 hover:text-white transition-all duration-150">
                            <component :is="link.icon" v-if="link.icon" class="size-3.5 opacity-70" />
                            <span class="relative">
                                {{ link.label }}
                                <span v-if="link.badge"
                                    class="absolute -top-3.5 left-1/2 -translate-x-1/2 flex items-center justify-center rounded-md bg-amber-400 px-1.5 py-[2px] text-[7px] font-black text-amber-900 tracking-wide leading-none whitespace-nowrap animate-bounce-badge pointer-events-none select-none">
                                    {{ link.badge }}
                                </span>
                            </span>
                        </Link>
                    </div>
                </div>

                <!-- BLOQUE DERECHO: Accesos + CTA -->
                <div class="hidden lg:flex items-center gap-2.5">
                    <div class="relative">
                        <button @click.stop="toggleDropdown('access')" type="button" :class="[
                            'inline-flex h-10 items-center gap-2 rounded-xl border transition-all duration-150 px-4 text-[11px] font-bold tracking-wide cursor-pointer focus:outline-none',
                            activeDropdown === 'access' ? 'border-white/40 bg-white/15 text-white' : 'border-white/25 bg-white/8 text-white/90 hover:border-white/40 hover:bg-white/15'
                        ]">
                            <Landmark class="size-3.5" />
                            <span>ACCESOS</span>
                            <ChevronDown :class="['size-3 opacity-60 transition-transform duration-200', activeDropdown === 'access' ? 'rotate-180' : '']" />
                        </button>

                        <Transition enter-active-class="transition-all duration-200 ease-out"
                            enter-from-class="opacity-0 translate-y-[-6px] scale-[0.97]"
                            enter-to-class="opacity-100 translate-y-0 scale-100"
                            leave-active-class="transition-all duration-150 ease-in"
                            leave-from-class="opacity-100 translate-y-0 scale-100"
                            leave-to-class="opacity-0 translate-y-[-4px] scale-[0.98]">
                            <div v-if="activeDropdown === 'access'"
                                class="absolute right-0 mt-2.5 w-56 origin-top-right z-[60]">
                                <div class="rounded-2xl bg-white shadow-[0_8px_32px_rgba(0,0,0,0.15),0_2px_8px_rgba(0,0,0,0.08)] border border-neutral-100/80 overflow-hidden">
                                    <div class="px-4 pt-3 pb-2 border-b border-neutral-100">
                                        <p class="text-[9px] font-black text-[#ae0c26] tracking-[0.12em] uppercase">Portales externos</p>
                                    </div>
                                    <div class="p-1.5">
                                        <a v-for="acc in externalAccess" :key="acc.label" :href="acc.url" target="_blank"
                                            class="flex items-center justify-between rounded-xl px-3 py-2.5 hover:bg-red-50 transition-colors group">
                                            <div class="flex items-center gap-2.5">
                                                <div :class="['w-6 h-6 rounded-lg flex items-center justify-center', acc.style === 'blue' ? 'bg-blue-50' : 'bg-[#ae0c26]/10']">
                                                    <component :is="acc.icon" :class="['size-3', acc.style === 'blue' ? 'text-blue-500' : 'text-[#ae0c26]']" />
                                                </div>
                                                <span class="text-[11.5px] font-bold text-neutral-700 group-hover:text-[#ae0c26]">{{ acc.label }}</span>
                                            </div>
                                            <ExternalLink class="size-3 text-neutral-300 group-hover:text-[#ae0c26]" />
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </Transition>
                    </div>

                    <Link :href="authUser ? dashboardUrl : loginUrl" @click="closeAllMenus"
                        class="inline-flex h-11 items-center gap-2 rounded-xl bg-white px-3 text-[11px] font-black tracking-wider text-[#ae0c26] shadow-[0_2px_8px_rgba(0,0,0,0.15)] hover:bg-neutral-50 transition-all duration-150 active:scale-[0.98]">
                        <LayoutDashboard v-if="authUser" class="size-3.5" />
                        <LogIn v-else class="size-3.5" />
                        <span>{{ authUser ? 'MI INTRANET' : 'ACCESO PERSONAL' }}</span>
                    </Link>
                </div>

                <!-- Botón Hamburguesa (Móvil) -->
                <button @click.stop="mobileMenuOpen = !mobileMenuOpen" type="button"
                    class="lg:hidden inline-flex items-center justify-center rounded-xl p-2.5 text-white hover:bg-white/10 transition-colors focus:outline-none">
                    <Transition enter-active-class="transition-all duration-150" enter-from-class="opacity-0 rotate-90"
                        enter-to-class="opacity-100 rotate-0" leave-active-class="transition-all duration-150"
                        leave-from-class="opacity-100 rotate-0" leave-to-class="opacity-0 -rotate-90" mode="out-in">
                        <Menu v-if="!mobileMenuOpen" class="size-5" key="open" />
                        <X v-else class="size-5" key="close" />
                    </Transition>
                </button>
            </div>
        </div>

        <!-- MENÚ MÓVIL -->
        <Transition enter-active-class="transition-all duration-300 ease-out"
            enter-from-class="opacity-0 -translate-y-2" enter-to-class="opacity-100 translate-y-0"
            leave-active-class="transition-all duration-200 ease-in" leave-from-class="opacity-100 translate-y-0"
            leave-to-class="opacity-0 -translate-y-2">
            <div v-if="mobileMenuOpen"
                class="lg:hidden border-t border-white/10 bg-[#980820] max-h-[calc(100vh-76px)] overflow-y-auto">
                <div class="px-4 py-4 space-y-1">

                    <Link href="/" @click="closeAllMenus"
                        class="flex items-center gap-2.5 rounded-xl px-4 py-3 text-sm font-bold text-white hover:bg-white/10 transition-colors">
                        Inicio
                    </Link>

                    <!-- Acordeón: ¿Quiénes Somos? -->
                    <div>
                        <button @click.stop="toggleDropdown('about')"
                            class="flex w-full items-center justify-between rounded-xl px-4 py-3 text-sm font-bold text-white hover:bg-white/10 transition-colors">
                            <span class="flex items-center gap-2.5">
                                <Users class="size-4 opacity-70" />
                                ¿Quiénes Somos?
                            </span>
                            <ChevronDown :class="['size-4 opacity-60 transition-transform duration-200', activeDropdown === 'about' ? 'rotate-180' : '']" />
                        </button>
                        <Transition enter-active-class="transition-all duration-200 ease-out"
                            enter-from-class="opacity-0 -translate-y-1" enter-to-class="opacity-100 translate-y-0"
                            leave-active-class="transition-all duration-150 ease-in"
                            leave-from-class="opacity-100 translate-y-0" leave-to-class="opacity-0 -translate-y-1">
                            <div v-if="activeDropdown === 'about'" class="ml-4 mt-1 mb-1 border-l-2 border-white/20 pl-3 space-y-0.5">
                                <Link v-for="item in aboutItems" :key="item.label" :href="resolveHref(item)" @click="closeAllMenus"
                                    class="block rounded-lg px-3 py-2 text-xs font-semibold text-white/80 hover:text-white hover:bg-white/10 transition-colors">
                                    {{ item.label }}
                                </Link>
                            </div>
                        </Transition>
                    </div>

                    <!-- Acordeón: Orientación Educativa -->
                    <div>
                        <button @click.stop="toggleDropdown('orientation')"
                            class="flex w-full items-center justify-between rounded-xl px-4 py-3 text-sm font-bold text-white hover:bg-white/10 transition-colors">
                            <span class="flex items-center gap-2.5">
                                <GraduationCap class="size-4 opacity-70" />
                                Orientación Educativa
                            </span>
                            <ChevronDown :class="['size-4 opacity-60 transition-transform duration-200', activeDropdown === 'orientation' ? 'rotate-180' : '']" />
                        </button>
                        <Transition enter-active-class="transition-all duration-200 ease-out"
                            enter-from-class="opacity-0 -translate-y-1" enter-to-class="opacity-100 translate-y-0"
                            leave-active-class="transition-all duration-150 ease-in"
                            leave-from-class="opacity-100 translate-y-0" leave-to-class="opacity-0 -translate-y-1">
                            <div v-if="activeDropdown === 'orientation'" class="ml-4 mt-1 mb-1 border-l-2 border-white/20 pl-3 space-y-0.5">
                                <Link v-for="item in orientationItems" :key="item.label" :href="resolveHref(item)" @click="closeAllMenus"
                                    class="block rounded-lg px-3 py-2 text-xs font-semibold text-white/80 hover:text-white hover:bg-white/10 transition-colors">
                                    {{ item.label }}
                                </Link>
                            </div>
                        </Transition>
                    </div>

                    <Link v-for="link in directLinks" :key="link.label" :href="link.href" @click="closeAllMenus"
                        class="flex items-center gap-2.5 rounded-xl px-4 py-3 text-sm font-bold text-white hover:bg-white/10 transition-colors">
                        <component :is="link.icon" v-if="link.icon" class="size-4 opacity-70" />
                        {{ link.label }}
                        <span v-if="link.badge" class="ml-1 rounded-md bg-amber-400 px-1.5 py-0.5 text-[8px] font-black text-amber-900">{{ link.badge }}</span>
                    </Link>

                    <!-- Accesos externos -->
                    <div class="pt-3 mt-3 border-t border-white/10">
                        <p class="px-4 pb-2 text-[9px] font-black text-white/40 tracking-widest uppercase">Accesos externos</p>
                        <div class="grid grid-cols-2 gap-2">
                            <a v-for="acc in externalAccess" :key="acc.label" :href="acc.url" target="_blank"
                                :class="['flex h-11 items-center justify-center gap-2 rounded-xl text-xs font-black shadow-sm', acc.style === 'blue' ? 'bg-[#1e2d5a] text-white' : 'bg-white text-[#ae0c26]']">
                                <component :is="acc.icon" class="size-3.5" /> {{ acc.shortLabel }}
                            </a>
                        </div>
                    </div>

                    <!-- Botón principal móvil -->
                    <div class="pt-2 pb-2">
                        <Link :href="authUser ? dashboardUrl : loginUrl" @click="closeAllMenus"
                            class="flex w-full h-12 items-center justify-center gap-2.5 rounded-xl bg-white text-sm font-black text-[#ae0c26] shadow-sm transition-all active:scale-[0.98]">
                            <LogIn class="size-4" />
                            <span>{{ authUser ? 'Panel Intranet' : 'Acceso Personal' }}</span>
                        </Link>
                    </div>
                </div>
            </div>
        </Transition>
    </nav>
</template>

<style scoped>
@keyframes badge-pulse {
    0%, 100% { transform: translateX(-50%) scale(1); }
    50% { transform: translateX(-50%) scale(1.18); }
}
.animate-bounce-badge {
    animation: badge-pulse 1.6s ease-in-out infinite;
}
</style>