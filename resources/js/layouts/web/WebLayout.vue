<script setup lang="ts">
import NavbarWeb from '@/components/web/NavbarWeb.vue';
import FooterWeb from '@/components/web/FooterWeb.vue';

</script>

<template>
    <div class="min-h-screen flex flex-col bg-neutral-50 text-neutral-800 antialiased selection:bg-[#af0c27] selection:text-white">
        <NavbarWeb />
        <main class="grow">
            <slot />
        </main>
        <FooterWeb />
    </div>
</template>