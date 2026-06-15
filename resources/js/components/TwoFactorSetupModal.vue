<script setup lang="ts">
import { Form } from '@inertiajs/vue3';
import { Check, Copy, ScanLine } from '@lucide/vue';
import { useClipboard } from '@vueuse/core';
import { computed, nextTick, ref, useTemplateRef, watch } from 'vue';
import AlertError from '@/components/AlertError.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import {
    InputOTP,
    InputOTPGroup,
    InputOTPSlot,
} from '@/components/ui/input-otp';
import { Spinner } from '@/components/ui/spinner';
import { useAppearance } from '@/composables/useAppearance';
import { useTwoFactorAuth } from '@/composables/useTwoFactorAuth';
import { confirm } from '@/routes/two-factor';
import type { TwoFactorConfigContent } from '@/types';

type Props = {
    requiresConfirmation: boolean;
    twoFactorEnabled: boolean;
};

const { resolvedAppearance } = useAppearance();

const props = defineProps<Props>();
const isOpen = defineModel<boolean>('isOpen');

const { copy, copied } = useClipboard();
const { qrCodeSvg, manualSetupKey, clearSetupData, fetchSetupData, errors } =
    useTwoFactorAuth();

const showVerificationStep = ref(false);
const code = ref<string>('');

const pinInputContainerRef = useTemplateRef('pinInputContainerRef');

// Configuración de los textos del Modal según la etapa del proceso
const modalConfig = computed<TwoFactorConfigContent>(() => {
    if (props.twoFactorEnabled) {
        return {
            title: 'Doble factor (2FA) activado con éxito',
            description:
                'La autenticación de dos factores está activa y protegida. Ya puedes escanear el código QR o ingresar la clave de configuración en tu aplicación de autenticación móvil.',
            buttonText: 'Entendido, Cerrar',
        };
    }

    if (showVerificationStep.value) {
        return {
            title: 'Verificar código de autenticación',
            description: 'Introduce el token PIN de 6 dígitos que muestra tu aplicación móvil para validar la sincronización.',
            buttonText: 'Confirmar y Activar',
        };
    }

    return {
        title: 'Configurar autenticación de doble factor',
        description:
            'Para finalizar la activación del doble factor (2FA), escanea el código QR inferior con la cámara de tu celular desde Google Authenticator o introduce la clave secreta manual.',
        buttonText: 'Siguiente, Verificar Token',
    };
});

const handleModalNextStep = () => {
    if (props.requiresConfirmation) {
        showVerificationStep.value = true;

        nextTick(() => {
            pinInputContainerRef.value?.querySelector('input')?.focus();
        });

        return;
    }

    clearSetupData();
    isOpen.value = false;
};

const resetModalState = () => {
    if (props.twoFactorEnabled) {
        clearSetupData();
    }

    showVerificationStep.value = false;
    code.value = '';
};

watch(
    isOpen,
    async (newVal) => {
        if (!newVal) {
            resetModalState();
            return;
        }

        if (!qrCodeSvg.value) {
            await fetchSetupData();
        }
    },
);
</script>

<template>
    <Dialog :open="isOpen" @update:open="isOpen = $event">
        <DialogContent class="sm:max-w-md">
            <DialogHeader class="flex items-center justify-center">
                <div class="mb-3 w-auto rounded-full border border-border bg-card p-0.5 shadow-sm">
                    <div class="relative overflow-hidden rounded-full border border-border bg-muted p-2.5">
                        <div class="absolute inset-0 grid grid-cols-5 opacity-50">
                            <div v-for="i in 5" :key="`col-${i}`" class="border-r border-border last:border-r-0" />
                        </div>
                        <div class="absolute inset-0 grid grid-rows-5 opacity-50">
                            <div v-for="i in 5" :key="`row-${i}`" class="border-b border-border last:border-b-0" />
                        </div>
                        <ScanLine class="relative z-20 size-6 text-foreground animate-pulse text-amber-500" />
                    </div>
                </div>
                <DialogTitle class="text-center text-lg font-bold">{{ modalConfig.title }}</DialogTitle>
                <DialogDescription class="text-center text-xs text-neutral-500 dark:text-neutral-400 mt-1">
                    {{ modalConfig.description }}
                </DialogDescription>
            </DialogHeader>

            <div class="relative flex w-auto flex-col items-center justify-center space-y-5">
                <!-- ETAPA 1: ESCANEAR CÓDIGO QR -->
                <template v-if="!showVerificationStep">
                    <AlertError v-if="errors?.length" :errors="errors" />

                    <template v-else>
                        <div class="relative mx-auto flex max-w-md items-center overflow-hidden">
                            <div
                                class="relative mx-auto aspect-square w-64 overflow-hidden rounded-lg border border-border bg-white p-3 flex items-center justify-center shadow-inner">
                                <div v-if="!qrCodeSvg"
                                    class="absolute inset-0 z-10 flex aspect-square h-auto w-full animate-pulse items-center justify-center bg-background">
                                    <Spinner class="size-6" />
                                </div>
                                <div v-else v-html="qrCodeSvg"
                                    class="flex aspect-square size-full items-center justify-center" :style="{
                                        filter:
                                            resolvedAppearance === 'dark'
                                                ? 'invert(1) brightness(1)'
                                                : undefined,
                                    }" />
                            </div>
                        </div>

                        <!-- Botón de Siguiente/Cerrar -->
                        <div class="flex w-full items-center space-x-5">
                            <Button class="w-full text-xs font-semibold" @click="handleModalNextStep">
                                {{ modalConfig.buttonText }}
                            </Button>
                        </div>

                        <!-- Separador de clave manual -->
                        <div class="relative flex w-full items-center justify-center">
                            <div class="absolute inset-0 top-1/2 h-px w-full bg-border" />
                            <span
                                class="relative bg-card px-2 py-1 text-[11px] text-neutral-400 font-medium uppercase tracking-wider">O
                                ingresa la clave manualmente</span>
                        </div>

                        <!-- Caja de texto con clave manual de configuración -->
                        <div class="flex w-full items-center justify-center space-x-2">
                            <div
                                class="flex w-full items-center h-10 overflow-hidden rounded-xl border border-border bg-muted/50">
                                <div v-if="!manualSetupKey"
                                    class="flex h-full w-full items-center justify-center bg-muted p-3">
                                    <Spinner />
                                </div>
                                <template v-else>
                                    <input type="text" readonly :value="manualSetupKey"
                                        class="h-full w-full bg-transparent px-3 py-1.5 font-mono text-sm md:text-base font-bold tracking-wider text-foreground focus:outline-none select-all" />
                                    <button @click="copy(manualSetupKey || '')"
                                        class="relative flex h-full items-center justify-center border-l border-border px-3 hover:bg-muted transition-colors"
                                        title="Copiar al portapapeles">
                                        <Check v-if="copied" class="w-4 text-emerald-500" />
                                        <Copy v-else class="w-4 text-neutral-500" />
                                    </button>
                                </template>
                            </div>
                        </div>
                    </template>
                </template>

                <!-- ETAPA 2: INGRESAR Y CONFIRMAR EL PIN TOKEN -->
                <template v-else>
                    <Form v-bind="confirm.form()" error-bag="confirmTwoFactorAuthentication" reset-on-error
                        @finish="code = ''" @success="isOpen = false" v-slot="{ errors: formErrors, processing }">
                        <input type="hidden" name="code" :value="code" />
                        <div ref="pinInputContainerRef" class="relative w-full space-y-4">
                            <div class="flex flex-col items-center justify-center space-y-3 py-2">
                                <!-- Slots Interactivos OTP de Shadcn/ui -->
                                <InputOTP id="otp" v-model="code" :maxlength="6" :disabled="processing" autofocus>
                                    <InputOTPGroup>
                                        <InputOTPSlot v-for="index in 6" :key="index" :index="index - 1"
                                            class="font-mono text-base font-bold shadow-sm" />
                                    </InputOTPGroup>
                                </InputOTP>
                                <InputError :message="formErrors?.code" class="mt-2" />
                            </div>

                            <!-- Botones de navegación del modal -->
                            <div class="flex w-full items-center space-x-3 pt-2">
                                <Button type="button" variant="outline" class="w-auto flex-1 text-xs"
                                    @click="showVerificationStep = false" :disabled="processing">
                                    Atrás
                                </Button>
                                <Button type="submit" class="w-auto flex-1 text-xs"
                                    :disabled="processing || code.length < 6">
                                    {{ modalConfig.buttonText }}
                                </Button>
                            </div>
                        </div>
                    </Form>
                </template>
            </div>
        </DialogContent>
    </Dialog>
</template>