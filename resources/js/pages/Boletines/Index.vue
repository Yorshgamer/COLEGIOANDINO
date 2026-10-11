<script setup lang="ts">
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import {
    Plus,
    FileText,
    Trash2,
    ExternalLink,
    Calendar,
    Upload,
    X,
    ChevronLeft,
    ChevronRight,
    Pencil
} from '@lucide/vue';

interface BoletinItem {
    id: number;
    numero: string;
    titulo: string;
    descripcion: string | null;
    fecha_publicacion: string;
    archivo_pdf: string;
    is_active: boolean;
}

interface PaginatedData<T> {
    data: T[];
    links: {
        url: string | null;
        label: string;
        active: boolean;
    }[];
    current_page: number;
    last_page: number;
    prev_page_url: string | null;
    next_page_url: string | null;
}

defineProps<{
    boletines: PaginatedData<BoletinItem>;
}>();

const showCreateModal = ref(false);
const showDeleteModal = ref(false);
const showEditModal = ref(false);
const editFileInput = ref<HTMLInputElement | null>(null);
const selectedBoletin = ref<BoletinItem | null>(null);
const fileInput = ref<HTMLInputElement | null>(null);

const form = useForm({
    numero: '',
    titulo: '',
    descripcion: '',
    fecha_publicacion: new Date().toISOString().split('T')[0],
    pdf_file: null as File | null,
});

const handleFileChange = (e: Event) => {
    const target = e.target as HTMLInputElement;
    if (target.files && target.files[0]) {
        form.pdf_file = target.files[0];
    }
};

const openCreateModal = () => {
    form.reset();
    form.clearErrors();
    form.fecha_publicacion = new Date().toISOString().split('T')[0];
    showCreateModal.value = true;
};

const submitCreate = () => {
    form.post('/boletinesadm', {
        preserveScroll: true,
        forceFormData: true,
        onSuccess: () => {
            showCreateModal.value = false;
            form.reset();
            if (fileInput.value) fileInput.value.value = '';
        },
    });
};

const confirmDelete = (boletin: BoletinItem) => {
    selectedBoletin.value = boletin;
    showDeleteModal.value = true;
};

const executeDelete = () => {
    if (!selectedBoletin.value) return;

    router.delete(`/boletinesadm/${selectedBoletin.value.id}`, {
        preserveScroll: true,
        onSuccess: () => {
            showDeleteModal.value = false;
            selectedBoletin.value = null;
        },
    });
};

const formatDate = (dateStr: string) => {
    if (!dateStr) return '';
    const date = new Date(dateStr + 'T00:00:00');
    return date.toLocaleDateString('es-PE', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
    });
};

const editForm = useForm({
    _method: 'PUT',
    id: 0,
    numero: '',
    titulo: '',
    descripcion: '',
    fecha_publicacion: '',
    pdf_file: null as File | null,
});
const openEditModal = (boletin: BoletinItem) => {
    editForm.clearErrors();
    editForm.id = boletin.id;
    editForm.numero = boletin.numero;
    editForm.titulo = boletin.titulo;
    editForm.descripcion = boletin.descripcion || '';

    const cleanDate = boletin.fecha_publicacion.includes('T')
        ? boletin.fecha_publicacion.split('T')[0]
        : boletin.fecha_publicacion;
    editForm.fecha_publicacion = cleanDate;
    editForm.pdf_file = null;

    if (editFileInput.value) editFileInput.value.value = '';
    showEditModal.value = true;
};

const handleEditFileChange = (e: Event) => {
    const target = e.target as HTMLInputElement;
    if (target.files && target.files[0]) {
        editForm.pdf_file = target.files[0];
    }
};

const submitUpdate = () => {
    editForm.post(`/boletinesadm/${editForm.id}`, {
        preserveScroll: true,
        forceFormData: true,
        onSuccess: () => {
            showEditModal.value = false;
            editForm.reset();
        },
    });
};
const filterEditionNumber = (event: Event, targetForm: typeof form | typeof editForm, maxLength: number = 4) => {
    const input = event.target as HTMLInputElement;
    const cleanValue = input.value.replace(/\D/g, '').slice(0, maxLength);
    targetForm.numero = cleanValue;
    input.value = cleanValue;
};
</script>

<template>

    <Head title="Gestión de Boletines - Colegio Andino" />

    <div class="min-h-screen bg-neutral-100/70 p-4 sm:p-6 lg:p-10 font-sans text-neutral-800">
        <div class="max-w-7xl mx-auto space-y-6">

            <!-- Cabecera Principal -->
            <div
                class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-neutral-200/80 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <span
                        class="px-3 py-1 rounded-lg bg-[#1a1d56]/10 text-[#1a1d56] font-bold text-xs uppercase tracking-wider">
                        Sistema Institucional
                    </span>
                    <h1 class="font-display text-2xl sm:text-3xl font-black text-[#1a1d56] mt-2">
                        Gestión de Boletines Semanales
                    </h1>
                    <p class="text-neutral-500 text-sm mt-1">
                        Sube y administra los archivos PDF que se visualizarán en el portal web público.
                    </p>
                </div>

                <button @click="openCreateModal"
                    class="inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-2xl bg-[#ae0c26] hover:bg-[#8f091f] text-white font-bold text-sm uppercase tracking-wider shadow-md hover:shadow-lg transition-all shrink-0">
                    <Plus class="size-5" />
                    <span>Nuevo Boletín</span>
                </button>
            </div>

            <!-- Tabla de Boletines -->
            <div class="bg-white rounded-3xl shadow-sm border border-neutral-200/80 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-neutral-600">
                        <thead
                            class="bg-neutral-50 border-b border-neutral-200 text-xs font-bold uppercase text-neutral-500 tracking-wider">
                            <tr>
                                <th class="px-6 py-4">Edición / Título</th>
                                <th class="px-6 py-4">Fecha de Publicación</th>
                                <th class="px-6 py-4">Archivo PDF</th>
                                <th class="px-6 py-4 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-100">
                            <tr v-for="boletin in boletines.data" :key="boletin.id"
                                class="hover:bg-neutral-50/70 transition-colors">

                                <td class="px-6 py-4">
                                    <div class="flex items-start gap-3">
                                        <div
                                            class="w-10 h-10 rounded-xl bg-[#1a1d56]/10 text-[#1a1d56] flex items-center justify-center font-bold text-xs shrink-0 mt-0.5">
                                            #{{ boletin.numero }}
                                        </div>
                                        <div>
                                            <span class="font-bold text-neutral-900 text-base block font-display">
                                                {{ boletin.titulo }}
                                            </span>
                                            <span class="text-xs text-neutral-500 line-clamp-1 mt-0.5">
                                                {{ boletin.descripcion || 'Sin descripción adicional' }}
                                            </span>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-1.5 text-neutral-600">
                                        <Calendar class="size-4 text-[#ae0c26]" />
                                        <span>{{ formatDate(boletin.fecha_publicacion) }}</span>
                                    </div>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a :href="'/' + boletin.archivo_pdf" target="_blank"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-neutral-100 hover:bg-neutral-200 text-[#1a1d56] text-xs font-bold transition-colors">
                                        <FileText class="size-4 text-[#ae0c26]" />
                                        <span>Ver Documento</span>
                                        <ExternalLink class="size-3 opacity-60 ml-0.5" />
                                    </a>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        <!-- Botón Editar -->
                                        <button @click="openEditModal(boletin)"
                                            class="p-2 rounded-xl text-neutral-400 hover:text-[#1a1d56] hover:bg-[#1a1d56]/10 transition-colors"
                                            title="Editar Boletín">
                                            <Pencil class="size-4" />
                                        </button>

                                        <!-- Botón Eliminar -->
                                        <button @click="confirmDelete(boletin)"
                                            class="p-2 rounded-xl text-neutral-400 hover:text-[#ae0c26] hover:bg-[#ae0c26]/10 transition-colors"
                                            title="Eliminar Boletín">
                                            <Trash2 class="size-4" />
                                        </button>
                                    </div>
                                </td>

                            </tr>

                            <tr v-if="boletines.data.length === 0">
                                <td colspan="4" class="px-6 py-12 text-center text-neutral-400">
                                    <FileText class="size-10 mx-auto mb-2 opacity-40" />
                                    <p class="font-bold text-base text-neutral-600">No se encontraron boletines
                                        registrados</p>
                                    <p class="text-xs text-neutral-400 mt-0.5">Haz clic en "Nuevo Boletín" para publicar
                                        el primero.</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Paginación -->
                <div v-if="boletines.last_page > 1"
                    class="px-6 py-4 bg-neutral-50 border-t border-neutral-200 flex items-center justify-between">
                    <span class="text-xs text-neutral-500">
                        Página {{ boletines.current_page }} de {{ boletines.last_page }}
                    </span>

                    <div class="flex items-center gap-2">
                        <Link v-if="boletines.prev_page_url" :href="boletines.prev_page_url"
                            class="px-3 py-1.5 rounded-xl border border-neutral-200 bg-white hover:bg-neutral-100 text-xs font-bold transition-colors flex items-center gap-1">
                            <ChevronLeft class="size-3.5" />
                            <span>Anterior</span>
                        </Link>
                        <Link v-if="boletines.next_page_url" :href="boletines.next_page_url"
                            class="px-3 py-1.5 rounded-xl border border-neutral-200 bg-white hover:bg-neutral-100 text-xs font-bold transition-colors flex items-center gap-1">
                            <span>Siguiente</span>
                            <ChevronRight class="size-3.5" />
                        </Link>
                    </div>
                </div>
            </div>

        </div>

        <!-- MODAL: Editar Boletín -->
        <div v-if="showEditModal"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs">
            <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl space-y-6">
                <div class="flex items-center justify-between border-b border-neutral-100 pb-4">
                    <div>
                        <h3 class="font-display font-black text-xl text-[#1a1d56]">Editar Boletín</h3>
                        <p class="text-neutral-500 text-xs mt-0.5">Modifica los detalles o reemplaza el PDF actual</p>
                    </div>
                    <button @click="showEditModal = false"
                        class="p-2 rounded-xl text-neutral-400 hover:bg-neutral-100 hover:text-neutral-600 transition-colors">
                        <X class="size-5" />
                    </button>
                </div>

                <form @submit.prevent="submitUpdate" class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold uppercase text-neutral-600 mb-1.5">
                                Número de Edición *
                            </label>
                            <input :value="editForm.numero" @input="filterEditionNumber($event, editForm, 4)"
                                type="text" inputmode="numeric" pattern="[0-9]*" maxlength="4" required
                                placeholder="Ej. 19"
                                class="w-full bg-neutral-50 border border-neutral-200 rounded-xl px-4 py-2.5 text-sm focus:bg-white focus:ring-2 focus:ring-[#ae0c26] focus:outline-none transition-all font-mono" />
                            <span v-if="editForm.errors.numero" class="text-xs text-[#ae0c26] mt-1 block font-semibold">
                                {{ editForm.errors.numero }}
                            </span>
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase text-neutral-600 mb-1.5">Fecha de
                                Publicación *</label>
                            <input v-model="editForm.fecha_publicacion" type="date" required
                                class="w-full bg-neutral-50 border border-neutral-200 rounded-xl px-4 py-2.5 text-sm focus:bg-white focus:ring-2 focus:ring-[#ae0c26] focus:outline-none transition-all" />
                            <span v-if="editForm.errors.fecha_publicacion"
                                class="text-xs text-[#ae0c26] mt-1 block font-semibold">{{
                                    editForm.errors.fecha_publicacion }}</span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase text-neutral-600 mb-1.5">Título del Boletín
                            *</label>
                        <input v-model="editForm.titulo" type="text" required
                            class="w-full bg-neutral-50 border border-neutral-200 rounded-xl px-4 py-2.5 text-sm focus:bg-white focus:ring-2 focus:ring-[#ae0c26] focus:outline-none transition-all" />
                        <span v-if="editForm.errors.titulo" class="text-xs text-[#ae0c26] mt-1 block font-semibold">{{
                            editForm.errors.titulo }}</span>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase text-neutral-600 mb-1.5">Descripción o
                            Resumen</label>
                        <textarea v-model="editForm.descripcion" rows="3"
                            class="w-full bg-neutral-50 border border-neutral-200 rounded-xl px-4 py-2.5 text-sm focus:bg-white focus:ring-2 focus:ring-[#ae0c26] focus:outline-none transition-all resize-none"></textarea>
                        <span v-if="editForm.errors.descripcion"
                            class="text-xs text-[#ae0c26] mt-1 block font-semibold">{{ editForm.errors.descripcion
                            }}</span>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase text-neutral-600 mb-1.5">Reemplazar Archivo PDF
                            (Opcional)</label>
                        <div
                            class="border-2 border-dashed border-neutral-300 hover:border-[#ae0c26] rounded-2xl p-4 text-center transition-colors bg-neutral-50">
                            <input ref="editFileInput" type="file" accept="application/pdf"
                                @change="handleEditFileChange" class="hidden" id="edit-boletin-pdf" />
                            <label for="edit-boletin-pdf" class="cursor-pointer flex flex-col items-center gap-1.5">
                                <Upload class="size-6 text-[#ae0c26]" />
                                <span class="text-xs font-bold text-[#1a1d56]">
                                    {{ editForm.pdf_file ? editForm.pdf_file.name : 'Seleccionar nuevo PDF si deseas cambiarlo' }}
                                </span>
                                <span class="text-[10px] text-neutral-400">Deja este campo vacío si mantendrás el PDF
                                    actual</span>
                            </label>
                        </div>
                        <span v-if="editForm.errors.pdf_file" class="text-xs text-[#ae0c26] mt-1 block font-semibold">{{
                            editForm.errors.pdf_file }}</span>
                    </div>

                    <div class="pt-4 border-t border-neutral-100 flex items-center justify-end gap-3">
                        <button type="button" @click="showEditModal = false"
                            class="px-5 py-2.5 rounded-xl text-neutral-600 hover:bg-neutral-100 font-bold text-xs uppercase tracking-wider transition-colors">
                            Cancelar
                        </button>
                        <button type="submit" :disabled="editForm.processing"
                            class="px-6 py-2.5 rounded-xl bg-[#1a1d56] hover:bg-[#13153f] text-white font-bold text-xs uppercase tracking-wider transition-all shadow-md disabled:opacity-50 flex items-center gap-2">
                            <span>{{ editForm.processing ? 'Guardando...' : 'Guardar Cambios' }}</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal: Subir Boletín -->
        <div v-if="showCreateModal"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs">
            <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl space-y-6">
                <div class="flex items-center justify-between border-b border-neutral-100 pb-4">
                    <div>
                        <h3 class="font-display font-black text-xl text-[#1a1d56]">Subir Nuevo Boletín</h3>
                        <p class="text-neutral-500 text-xs mt-0.5">Se almacenará en <code
                                class="bg-neutral-100 px-1 py-0.5 rounded text-neutral-700 font-mono">public/Boletines</code>
                        </p>
                    </div>
                    <button @click="showCreateModal = false"
                        class="p-2 rounded-xl text-neutral-400 hover:bg-neutral-100 hover:text-neutral-600 transition-colors">
                        <X class="size-5" />
                    </button>
                </div>

                <form @submit.prevent="submitCreate" class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold uppercase text-neutral-600 mb-1.5">
                                Número de Edición *
                            </label>
                            <input :value="form.numero" @input="filterEditionNumber($event, form, 4)" type="text"
                                inputmode="numeric" pattern="[0-9]*" maxlength="4" required placeholder="Ej. 19"
                                class="w-full bg-neutral-50 border border-neutral-200 rounded-xl px-4 py-2.5 text-sm focus:bg-white focus:ring-2 focus:ring-[#ae0c26] focus:outline-none transition-all font-mono" />
                            <span v-if="form.errors.numero" class="text-xs text-[#ae0c26] mt-1 block">
                                {{ form.errors.numero }}
                            </span>
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase text-neutral-600 mb-1.5">Fecha de
                                Publicación *</label>
                            <input v-model="form.fecha_publicacion" type="date" required
                                class="w-full bg-neutral-50 border border-neutral-200 rounded-xl px-4 py-2.5 text-sm focus:bg-white focus:ring-2 focus:ring-[#ae0c26] focus:outline-none transition-all" />
                            <span v-if="form.errors.fecha_publicacion" class="text-xs text-[#ae0c26] mt-1 block">{{
                                form.errors.fecha_publicacion }}</span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase text-neutral-600 mb-1.5">Título del Boletín
                            *</label>
                        <input v-model="form.titulo" type="text" required placeholder="Ej. BENEFICIOS DE LA LECTURA"
                            class="w-full bg-neutral-50 border border-neutral-200 rounded-xl px-4 py-2.5 text-sm focus:bg-white focus:ring-2 focus:ring-[#ae0c26] focus:outline-none transition-all" />
                        <span v-if="form.errors.titulo" class="text-xs text-[#ae0c26] mt-1 block">{{ form.errors.titulo
                            }}</span>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase text-neutral-600 mb-1.5">Descripción o
                            Resumen</label>
                        <textarea v-model="form.descripcion" rows="3"
                            placeholder="Breve texto sobre el tema del boletín..."
                            class="w-full bg-neutral-50 border border-neutral-200 rounded-xl px-4 py-2.5 text-sm focus:bg-white focus:ring-2 focus:ring-[#ae0c26] focus:outline-none transition-all resize-none"></textarea>
                        <span v-if="form.errors.descripcion" class="text-xs text-[#ae0c26] mt-1 block">{{
                            form.errors.descripcion }}</span>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase text-neutral-600 mb-1.5">Archivo PDF *</label>
                        <div
                            class="border-2 border-dashed border-neutral-300 hover:border-[#ae0c26] rounded-2xl p-4 text-center transition-colors bg-neutral-50">
                            <input ref="fileInput" type="file" accept="application/pdf" required
                                @change="handleFileChange" class="hidden" id="boletin-pdf" />
                            <label for="boletin-pdf" class="cursor-pointer flex flex-col items-center gap-1.5">
                                <Upload class="size-6 text-[#ae0c26]" />
                                <span class="text-xs font-bold text-[#1a1d56]">
                                    {{ form.pdf_file ? form.pdf_file.name : 'Haz clic para seleccionar el PDF' }}
                                </span>
                                <span class="text-[10px] text-neutral-400">Archivos PDF hasta 20MB</span>
                            </label>
                        </div>
                        <span v-if="form.errors.pdf_file" class="text-xs text-[#ae0c26] mt-1 block">{{
                            form.errors.pdf_file }}</span>
                    </div>

                    <div class="pt-4 border-t border-neutral-100 flex items-center justify-end gap-3">
                        <button type="button" @click="showCreateModal = false"
                            class="px-5 py-2.5 rounded-xl text-neutral-600 hover:bg-neutral-100 font-bold text-xs uppercase tracking-wider transition-colors">
                            Cancelar
                        </button>
                        <button type="submit" :disabled="form.processing"
                            class="px-6 py-2.5 rounded-xl bg-[#ae0c26] hover:bg-[#8f091f] text-white font-bold text-xs uppercase tracking-wider transition-all shadow-md disabled:opacity-50 flex items-center gap-2">
                            <Upload class="size-4" />
                            <span>{{ form.processing ? 'Subiendo...' : 'Publicar' }}</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal: Confirmar Eliminación -->
        <div v-if="showDeleteModal"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs">
            <div class="bg-white rounded-3xl max-w-sm w-full p-6 sm:p-8 shadow-2xl text-center space-y-4">
                <div class="w-14 h-14 bg-red-100 text-[#ae0c26] rounded-full flex items-center justify-center mx-auto">
                    <Trash2 class="size-7" />
                </div>
                <h3 class="font-display font-black text-xl text-neutral-900">¿Eliminar Boletín?</h3>
                <p class="text-neutral-500 text-xs leading-relaxed">
                    Esta acción eliminará el registro y el archivo PDF asociado en <code
                        class="font-mono">public/Boletines</code>.
                </p>
                <div class="pt-2 flex items-center justify-center gap-3">
                    <button @click="showDeleteModal = false"
                        class="px-5 py-2.5 rounded-xl text-neutral-600 hover:bg-neutral-100 font-bold text-xs uppercase tracking-wider transition-colors">
                        Cancelar
                    </button>
                    <button @click="executeDelete"
                        class="px-5 py-2.5 rounded-xl bg-[#ae0c26] hover:bg-[#8f091f] text-white font-bold text-xs uppercase tracking-wider transition-colors shadow-md">
                        Eliminar
                    </button>
                </div>
            </div>
        </div>

    </div>
</template>

<style scoped>
.font-display,
.font-sans {
    font-family: 'Gilroy', ui-sans-serif, system-ui, sans-serif;
}
</style>