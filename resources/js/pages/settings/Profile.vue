<script setup lang="ts">
import { Form, Head, usePage, useForm } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import { computed, ref, nextTick } from 'vue';
import ProfileController from '@/actions/App/Http/Controllers/Settings/ProfileController';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { edit } from '@/routes/profile';
import { send } from '@/routes/verification';
import { PenTool, Trash2, CheckCircle2, X } from '@lucide/vue'; // Añadimos el ícono X

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

// --- LÓGICA DEL MODAL Y CANVAS DE FIRMA ---
const isModalOpen = ref(false);
const canvasRef = ref<HTMLCanvasElement | null>(null);
let isDrawing = false;
let context: CanvasRenderingContext2D | null = null;

const signatureForm = useForm({
    signature: '',
});

// Abrir el modal y calibrar el lienzo de forma limpia
const openSignatureModal = async () => {
    isModalOpen.value = true;
    
    // Esperamos a que Vue renderice el Modal en el DOM antes de buscar el Canvas
    await nextTick();
    
    const canvas = canvasRef.value;
    if (canvas) {
        context = canvas.getContext('2d');
        if (context) {
            context.strokeStyle = '#000000'; // Trazo negro institucional permanente
            context.lineWidth = 3;           // Grosor nítido para PDF
            context.lineCap = 'round';
            context.lineJoin = 'round';
            
            // Forzamos el fondo del lienzo a ser blanco físicamente en los píxeles de la imagen
            context.fillStyle = '#FFFFFF';
            context.fillRect(0, 0, canvas.width, canvas.height);
        }
    }
};

const closeSignatureModal = () => {
    isModalOpen.value = false;
    clearCanvas();
};

// Lógica de captura de coordenadas corregida y calibrada para el Modal flotante
const startDrawing = (event: MouseEvent | TouchEvent) => {
    isDrawing = true;
    draw(event);
};

const stopDrawing = () => {
    isDrawing = false;
    if (context) {
        context.beginPath(); // Abre un nuevo camino limpio para el siguiente trazo
    }
};

const draw = (event: MouseEvent | TouchEvent) => {
    if (!isDrawing || !context || !canvasRef.value) return;

    const canvas = canvasRef.value;
    const rect = canvas.getBoundingClientRect();
    
    // Captura exacta sin importar el scroll o tamaño de la pantalla
    const clientX = 'touches' in event ? event.touches[0].clientX : event.clientX;
    const clientY = 'touches' in event ? event.touches[0].clientY : event.clientY;

    const x = clientX - rect.left;
    const y = clientY - rect.top;

    context.lineTo(x, y);
    context.stroke();
    context.beginPath();
    context.moveTo(x, y);
};

const clearCanvas = () => {
    if (context && canvasRef.value) {
        context.fillStyle = '#FFFFFF';
        context.fillRect(0, 0, canvasRef.value.width, canvasRef.value.height);
    }
};

const saveSignature = () => {
    if (!canvasRef.value) return;
    
    const dataUrl = canvasRef.value.toDataURL('image/png');
    signatureForm.signature = dataUrl;

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
            <Heading
                variant="small"
                title="Información del Perfil"
                description="Actualiza tu nombre completo y dirección de correo electrónico institucional."
            />

            <Form
                v-bind="ProfileController.update.form()"
                class="space-y-6"
                v-slot="{ errors, processing }"
            >
                <div class="grid gap-2">
                    <Label for="name">Nombre Completo</Label>
                    <Input
                        id="name"
                        class="mt-1 block w-full"
                        name="name"
                        :default-value="user.name"
                        required
                        disabled
                        placeholder="Nombres y apellidos"
                    />
                    <p class="text-xs text-neutral-400">El nombre está sincronizado con tu ficha de personal.</p>
                    <InputError class="mt-2" :message="errors.name" />
                </div>

                <div class="grid gap-2">
                    <Label for="email">Correo Electrónico</Label>
                    <Input
                        id="email"
                        type="email"
                        class="mt-1 block w-full"
                        name="email"
                        :default-value="user.email"
                        required
                        placeholder="correo@colegioandino.edu.pe"
                    />
                    <InputError class="mt-2" :message="errors.email" />
                </div>

                <div v-if="page.props.mustVerifyEmail && !user.email_verified_at">
                    <p class="-mt-4 text-sm text-muted-foreground">
                        Tu dirección de correo no está verificada.
                        <Link :href="send()" as="button" class="text-foreground underline decoration-neutral-300 underline-offset-4 transition-colors duration-300 hover:decoration-current!">
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
            <Heading
                variant="small"
                title="Firma Digital para Boletas"
                description="Registra o actualiza tu firma digital autorizada para la conformidad de tus boletas y CTS."
            />

            <div class="max-w-xl">
                <div class="border border-neutral-200 dark:border-neutral-800 rounded-lg p-4 bg-white dark:bg-neutral-900 flex flex-col sm:flex-row items-center justify-between gap-4">
                    
                    <div class="flex items-center gap-3">
                        <div v-if="user.signature_path" class="p-2 rounded-full bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600">
                            <CheckCircle2 class="h-6 w-6" />
                        </div>
                        <div v-else class="p-2 rounded-full bg-amber-50 dark:bg-amber-500/10 text-amber-600">
                            <PenTool class="h-6 w-6" />
                        </div>
                        
                        <div class="flex flex-col">
                            <span class="text-sm font-semibold text-neutral-900 dark:text-neutral-100">
                                {{ user.signature_path ? 'Firma Lista y Configurada' : 'Firma Pendiente de Registro' }}
                            </span>
                            <span class="text-xs text-neutral-400">
                                {{ user.signature_path ? 'Tu firma se incrustará de forma automática en tus boletas.' : 'Necesitas registrar una firma para poder firmar tus boletas.' }}
                            </span>
                        </div>
                    </div>

                    <Button type="button" size="sm" @click="openSignatureModal" class="shrink-0">
                        <PenTool class="h-4 w-4 mr-1.5" />
                        {{ user.signature_path ? 'Cambiar Firma' : 'Dibujar Firma' }}
                    </Button>
                </div>

                <div v-if="user.signature_path" class="mt-3 p-4 border border-dashed border-neutral-200 dark:border-neutral-800 rounded-lg bg-neutral-50/50 dark:bg-neutral-950/50 flex flex-col items-center">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-neutral-400 mb-2">Vista previa de firma activa</span>
                    <img :src="user.signature_path" alt="Firma Registrada" class="max-h-[60px] object-contain dark:bg-white dark:p-1 dark:rounded" />
                </div>
            </div>
        </div>
    </div>

    <div v-if="isModalOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4 animate-in fade-in duration-200">
        <div class="bg-white dark:bg-neutral-900 rounded-xl shadow-xl border border-neutral-200 dark:border-neutral-800 w-full max-w-lg overflow-hidden animate-in zoom-in-95 duration-200">
            
            <div class="px-6 py-4 border-b border-neutral-100 dark:border-neutral-800 flex justify-between items-center bg-neutral-50/50 dark:bg-neutral-950/20">
                <div>
                    <h3 class="text-base font-bold text-neutral-900 dark:text-neutral-100">Registrar Trazo Oficial</h3>
                    <p class="text-xs text-neutral-400">Usa el mouse o tu pantalla táctil para dibujar dentro del recuadro blanco.</p>
                </div>
                <button @click="closeSignatureModal" class="p-1 rounded-md text-neutral-400 hover:bg-neutral-100 dark:hover:bg-neutral-800 transition-colors">
                    <X class="h-5 w-5" />
                </button>
            </div>

            <div class="p-6 bg-neutral-100 dark:bg-neutral-950 flex justify-center">
                <div class="border border-neutral-300 shadow-sm rounded-lg overflow-hidden bg-white">
                    <canvas 
                        ref="canvasRef"
                        width="440"
                        height="180"
                        class="touch-none cursor-crosshair bg-white"
                        @mousedown="startDrawing"
                        @mouseup="stopDrawing"
                        @mousemove="draw"
                        @touchstart="startDrawing"
                        @touchend="stopDrawing"
                        @touchmove="draw"
                    ></canvas>
                </div>
            </div>

            <div class="px-6 py-4 bg-neutral-50 dark:bg-neutral-950/40 border-t border-neutral-100 dark:border-neutral-800 flex justify-between items-center">
                <Button type="button" variant="ghost" size="sm" @click="clearCanvas" class="text-neutral-500 hover:text-red-600 text-xs">
                    <Trash2 class="h-3.5 w-3.5 mr-1" />
                    Limpiar Lienzo
                </Button>
                
                <div class="flex items-center gap-2">
                    <Button type="button" variant="outline" size="sm" @click="closeSignatureModal" class="text-xs">
                        Cancelar
                    </Button>
                    <Button type="button" size="sm" @click="saveSignature" :disabled="signatureForm.processing" class="text-xs bg-neutral-900 text-white dark:bg-neutral-50 dark:text-neutral-900">
                        Guardar e Insertar
                    </Button>
                </div>
            </div>

        </div>
    </div>
</template>