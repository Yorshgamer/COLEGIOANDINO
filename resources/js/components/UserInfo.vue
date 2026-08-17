<script setup lang="ts">
import { computed } from 'vue';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { useInitials } from '@/composables/useInitials';
import type { User } from '@/types';

type Props = {
    // 🎯 Corrección 1: Hacemos que la prop acepte null o undefined para visitas públicas
    user?: User | null; 
    showEmail?: boolean;
};

const props = withDefaults(defineProps<Props>(), {
    user: null, // Valor por defecto seguro
    showEmail: false,
});

const { getInitials } = useInitials();

// 🎯 Corrección 2: Añadimos encadenamiento opcional (?.) para evitar colapsos
const showAvatar = computed(
    () => props.user?.avatar && props.user.avatar !== '',
);
</script>

<template>
    <!-- ── CASO A: USUARIO AUTENTICADO (Muestra avatar y datos) ── -->
    <template v-if="user">
        <Avatar class="h-8 w-8 overflow-hidden rounded-lg">
            <AvatarImage v-if="showAvatar" :src="user.avatar!" :alt="user.name" />
            <AvatarFallback class="rounded-lg text-black dark:text-white">
                {{ getInitials(user.name) }}
            </AvatarFallback>
        </Avatar>

        <div class="grid flex-1 text-left text-sm leading-tight">
            <span class="truncate font-medium">{{ user.name }}</span>
            <span v-if="showEmail" class="truncate text-xs text-muted-foreground">
                {{ user.email }}
            </span>
        </div>
    </template>

    <!-- ── CASO B: VISITANTE ANÓNIMO (Web Pública / Modo Invitado) ── -->
    <template v-else>
        <Avatar class="h-8 w-8 overflow-hidden rounded-lg bg-neutral-100 dark:bg-neutral-800">
            <AvatarFallback class="rounded-lg text-neutral-400 dark:text-neutral-500">
                👤
            </AvatarFallback>
        </Avatar>

        <div class="grid flex-1 text-left text-sm leading-tight">
            <span class="truncate font-medium text-neutral-500 dark:text-neutral-400">Portal Público</span>
            <span v-if="showEmail" class="truncate text-xs text-neutral-400">Colegio Andino</span>
        </div>
    </template>
</template>