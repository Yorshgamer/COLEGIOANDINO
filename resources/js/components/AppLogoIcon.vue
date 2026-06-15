<script setup lang="ts">
import type { HTMLAttributes } from 'vue';

defineOptions({
    inheritAttrs: false,
});

type Props = {
    className?: HTMLAttributes['class'];
};

defineProps<Props>();
</script>

<template>
    <div 
        :class="[
            'flex items-center justify-center p-0.5', 
            className
        ]"
        v-bind="$attrs"
    >
        <img
            src="/img/Andino_logo.jpg"
            alt="Logotipo Colegio Andino"
            class="h-auto w-full object-contain transition-transform duration-200 scale-180 rounded-xs"
        />
    </div>
</template>