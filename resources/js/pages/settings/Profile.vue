<script setup lang="ts">
import { computed, ref } from 'vue';
import { Head, usePage, useForm, Link, Form } from '@inertiajs/vue3';
import ProfileController from '@/actions/App/Http/Controllers/Settings/ProfileController';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import SignatureCanvas from '@/components/SignatureCanvas.vue'; // 🎯 Importamos tu nuevo componente modular
import { edit } from '@/routes/profile';
import { send } from '@/routes/verification';
import { PenTool, CheckCircle2, X } from '@lucide/vue';

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Configuración del perfil',
                href: edit(),
            },
        ],
    },
});

const page = usePage();

interface AuthUser {
    id: number;
    name: string;
    email: string;
    email_verified_at: string | null;
    signature_path: string | null;
}

const user = computed(() => (page.props.auth as any).user as AuthUser);

// --- CONTROL DEL MODAL DE FIRMA ---
const isModalOpen = ref(false);

const signatureForm = useForm({
    signature: '',
});

const openSignatureModal = () => {
    isModalOpen.value = true;
};

const closeSignatureModal = () => {
    isModalOpen.value = false;
};

// 🎯 CAPTURAMOS EL STRING BASE64 QUE EMITE EL COMPONENTE HIJO
const saveSignature = (base64Data: string) => {
    signatureForm.signature = base64Data;

    signatureForm.post('/profile/signature', {
        preserveScroll: true,
        onSuccess: () => {
            closeSignatureModal();
        },
    });
};
</script>

<template>

    <Head title="Configuración del perfil" />

    <h1 class="sr-only">Configuración del perfil</h1>

    <div class="flex flex-col space-y-10">
        <div class="space-y-6">
            <Heading variant="small" title="Información del Perfil"
                description="Actualiza tu nombre completo y dirección de correo electrónico institucional." />

            <Form v-bind="ProfileController.update.form()" class="space-y-6" v-slot="{ errors, processing }">
                <div class="grid gap-2">
                    <Label for="name">Nombre Completo</Label>
                    <Input id="name" class="mt-1 block w-full" name="name" :default-value="user.name" required disabled
                        placeholder="Nombres y apellidos" />
                    <p class="text-xs text-neutral-400">El nombre está sincronizado con tu ficha de personal.</p>
                    <InputError class="mt-2" :message="errors.name" />
                </div>

                <div class="grid gap-2">
                    <Label for="email">Correo Electrónico</Label>
                    <Input id="email" type="email" class="mt-1 block w-full" name="email" :default-value="user.email"
                        required placeholder="correo@colegioandino.edu.pe" />
                    <InputError class="mt-2" :message="errors.email" />
                </div>

                <div v-if="page.props.mustVerifyEmail && !user.email_verified_at">
                    <p class="-mt-4 text-sm text-muted-foreground">
                        Tu dirección de correo no está verificada.
                        <Link :href="send()" as="button"
                            class="text-foreground underline decoration-neutral-300 underline-offset-4 transition-colors duration-300 hover:decoration-current!">
                            Haz clic aquí para reenviar el correo de verificación.
                        </Link>
                    </p>
                </div>

                <div class="flex items-center gap-4">
                    <Button :disabled="processing">Guardar Cambios</Button>
                </div>
            </Form>
        </div>

        <div class="space-y-6 pt-4 border-t border-neutral-100 dark:border-neutral-800">
            <Heading variant="small" title="Firma Digital para Boletas"
                description="Registra o actualiza tu firma digital autorizada para la conformidad de tus boletas y CTS." />

            <div class="max-w-xl">
                <div
                    class="border border-neutral-200 dark:border-neutral-800 rounded-lg p-4 bg-white dark:bg-neutral-900 flex flex-col sm:flex-row items-center justify-between gap-4">

                    <div class="flex items-center gap-3">
                        <div class="p-2 rounded-full"
                            :class="user.signature_path ? 'bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600' : 'bg-amber-50 dark:bg-amber-500/10 text-amber-600'">
                            <component :is="user.signature_path ? CheckCircle2 : PenTool" class="h-6 w-6" />
                        </div>

                        <div class="flex flex-col">
                            <span class="text-sm font-semibold text-neutral-900 dark:text-neutral-100">
                                {{ user.signature_path ? 'Firma Lista y Configurada' : 'Firma Pendiente de Registro' }}
                            </span>
                            <span class="text-xs text-neutral-400">
                                {{ user.signature_path ? 'Tu firma se incrustará de forma automática en tus boletas.' :
                                    'Necesitas registrar una firma para poder firmar tus boletas.' }}
                            </span>
                        </div>
                    </div>

                    <Button type="button" size="sm" @click="openSignatureModal" class="shrink-0">
                        <PenTool class="h-4 w-4 mr-1.5" />
                        {{ user.signature_path ? 'Cambiar Firma' : 'Dibujar Firma' }}
                    </Button>
                </div>

                <div v-if="user.signature_path"
                    class="mt-3 p-4 border border-dashed border-neutral-200 dark:border-neutral-800 rounded-lg bg-neutral-50/50 dark:bg-neutral-950/50 flex flex-col items-center">

                    <span class="text-[10px] font-bold uppercase tracking-wider text-neutral-400 mb-2">
                        Vista previa de firma activa
                    </span>

                    <div
                        class="bg-white p-2 rounded-lg shadow-inner flex items-center justify-center border border-neutral-200 min-w-[180px]">
                        <img src="/profile/signature/image" alt="Firma Registrada Segura"
                            class="max-h-[60px] object-contain block" />
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div v-if="isModalOpen"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4 animate-in fade-in duration-200">
        <div
            class="bg-white dark:bg-neutral-900 rounded-xl shadow-xl border border-neutral-200 dark:border-neutral-800 w-full max-w-lg overflow-hidden animate-in zoom-in-95 duration-200">

            <div
                class="px-6 py-4 border-b border-neutral-100 dark:border-neutral-800 flex justify-between items-center bg-neutral-50/50 dark:bg-neutral-950/20">
                <div>
                    <h3 class="text-base font-bold text-neutral-900 dark:text-neutral-100">Registrar Trazo Oficial</h3>
                    <p class="text-xs text-neutral-400">Usa el mouse o tu pantalla táctil para dibujar dentro del
                        recuadro blanco.</p>
                </div>
                <button @click="closeSignatureModal"
                    class="p-1 rounded-md text-neutral-400 hover:bg-neutral-100 dark:hover:bg-neutral-800 transition-colors">
                    <X class="h-5 w-5" />
                </button>
            </div>

            <div class="p-6 bg-neutral-100 dark:bg-neutral-950">
                <SignatureCanvas @save="saveSignature" @cancel="closeSignatureModal" />
            </div>

        </div>
    </div>
</template>