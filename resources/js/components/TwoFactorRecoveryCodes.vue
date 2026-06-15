<script setup lang="ts">
import { Form } from '@inertiajs/vue3';
import { Eye, EyeOff, LockKeyhole, RefreshCw } from '@lucide/vue';
import { nextTick, onMounted, ref, useTemplateRef } from 'vue';
import AlertError from '@/components/AlertError.vue';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { useTwoFactorAuth } from '@/composables/useTwoFactorAuth';
import { regenerateRecoveryCodes } from '@/routes/two-factor';

const { recoveryCodesList, fetchRecoveryCodes, errors } = useTwoFactorAuth();
const isRecoveryCodesVisible = ref<boolean>(false);
const recoveryCodeSectionRef = useTemplateRef('recoveryCodeSectionRef');

const toggleRecoveryCodesVisibility = async () => {
    if (!isRecoveryCodesVisible.value && !recoveryCodesList.value.length) {
        await fetchRecoveryCodes();
    }

    isRecoveryCodesVisible.value = !isRecoveryCodesVisible.value;

    if (isRecoveryCodesVisible.value) {
        await nextTick();
        recoveryCodeSectionRef.value?.scrollIntoView({ behavior: 'smooth' });
    }
};

onMounted(async () => {
    if (!recoveryCodesList.value.length) {
        await fetchRecoveryCodes();
    }
});
</script>

<template>
    <Card class="w-full">
        <CardHeader>
            <CardTitle class="flex gap-3 text-sm font-bold uppercase tracking-wider">
                <LockKeyhole class="size-4 text-amber-500" /> Códigos de recuperación para emergencias
            </CardTitle>
            <CardDescription>
                Los códigos de recuperación te permiten recuperar el acceso a tu cuenta si pierdes, 
                te roban o cambias tu teléfono móvil. Guárdalos en un administrador de contraseñas seguro.
            </CardDescription>
        </CardHeader>
        <CardContent>
            <div
                class="flex flex-col gap-3 select-none sm:flex-row sm:items-center sm:justify-between"
            >
                <!-- Botón Mostrar / Ocultar -->
                <Button @click="toggleRecoveryCodesVisibility" variant="outline" class="w-fit text-xs">
                    <component
                        :is="isRecoveryCodesVisible ? EyeOff : Eye"
                        class="size-4 mr-1.5"
                    />
                    {{ isRecoveryCodesVisible ? 'Ocultar' : 'Visualizar' }} códigos de respaldo
                </Button>

                <!-- Botón Regenerar nuevos códigos -->
                <Form
                    v-if="isRecoveryCodesVisible && recoveryCodesList.length"
                    v-bind="regenerateRecoveryCodes.form()"
                    method="post"
                    :options="{ preserveScroll: true }"
                    @success="fetchRecoveryCodes"
                    #default="{ processing }"
                >
                    <Button
                        variant="secondary"
                        type="submit"
                        :disabled="processing"
                        class="text-xs"
                    >
                        <RefreshCw class="size-3.5 mr-1.5 animate-hover" /> Regenerar nuevos códigos
                    </Button>
                </Form>
            </div>

            <!-- Panel desplegable de códigos -->
            <div
                :class="[
                    'relative overflow-hidden transition-all duration-300',
                    isRecoveryCodesVisible
                        ? 'h-auto opacity-100'
                        : 'h-0 opacity-0',
                ]"
            >
                <div v-if="errors?.length" class="mt-6">
                    <AlertError :errors="errors" />
                </div>
                <div v-else class="mt-3 space-y-3">
                    <div
                        ref="recoveryCodeSectionRef"
                        class="grid grid-cols-2 gap-2 rounded-lg bg-muted p-4 font-mono text-sm text-center tracking-wider border"
                    >
                        <!-- Efecto esqueleto de carga -->
                        <div v-if="!recoveryCodesList.length" class="col-span-2 space-y-2">
                            <div
                                v-for="n in 4"
                                :key="n"
                                class="h-4 animate-pulse rounded bg-muted-foreground/20"
                            ></div>
                        </div>
                        
                        <!-- Listado de códigos -->
                        <div
                            v-else
                            v-for="(code, index) in recoveryCodesList"
                            :key="index"
                            class="p-1 bg-background/50 dark:bg-neutral-900/50 rounded border border-neutral-200/50 select-text font-semibold text-neutral-800 dark:text-neutral-200"
                        >
                            {{ code }}
                        </div>
                    </div>
                    <p class="text-[11px] text-muted-foreground select-none">
                        Cada código de recuperación se puede usar **una sola vez** para iniciar sesión en tu cuenta y se 
                        invalidará automáticamente después de su uso. Si necesitas más códigos, haz clic en el botón 
                        <span class="font-bold">Regenerar nuevos códigos</span> de la parte superior.
                    </p>
                </div>
            </div>
        </CardContent>
    </Card>
</template>