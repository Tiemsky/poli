<!-- resources/js/Components/DocumentUpload.vue -->
<script setup>
import { ref } from 'vue';

const props = defineProps({
    modelValue: [File, null],
    label: {
        type: String,
        required: true
    },
    hint: {
        type: String,
        default: ''
    },
    error: {
        type: String,
        default: ''
    },
    required: {
        type: Boolean,
        default: true
    },
    accept: {
        type: String,
        default: 'image/*,.pdf'
    }
});

const emit = defineEmits(['update:modelValue', 'change']);

const fileInput = ref(null);
const fileName = ref('');
const isDragging = ref(false);

// Formatage de la taille du fichier
const formatFileSize = (bytes) => {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
};

// Gestion du clic sur la zone de drop
const triggerFileInput = () => {
    fileInput.value?.click();
};

// Gestion de la sélection de fichier
const handleFileSelect = (event) => {
    const file = event.target.files?.[0];
    if (file) {
        fileName.value = file.name;
        emit('update:modelValue', file);
        emit('change', file);
    }
};

// Gestion du drag & drop
const handleDragOver = (event) => {
    event.preventDefault();
    isDragging.value = true;
};

const handleDragLeave = (event) => {
    event.preventDefault();
    isDragging.value = false;
};

const handleDrop = (event) => {
    event.preventDefault();
    isDragging.value = false;

    const file = event.dataTransfer?.files?.[0];
    if (file) {
        fileName.value = file.name;
        emit('update:modelValue', file);
        emit('change', file);
    }
};

// Supprimer le fichier sélectionné
const removeFile = () => {
    fileName.value = '';
    emit('update:modelValue', null);
    emit('change', null);
    if (fileInput.value) {
        fileInput.value.value = '';
    }
};
</script>

<template>
    <div class="space-y-2">
        <label class="block text-sm font-medium text-gray-700">
            {{ label }}
            <span v-if="required" class="text-red-500">*</span>
        </label>

        <!-- Zone de upload -->
        <div
            @click="triggerFileInput"
            @dragover="handleDragOver"
            @dragleave="handleDragLeave"
            @drop="handleDrop"
            class="relative border-2 border-dashed rounded-lg p-4 cursor-pointer transition-all duration-200"
            :class="[
                isDragging ? 'border-primary bg-primary/5' : 'border-gray-300 hover:border-primary/50',
                error ? 'border-red-500 bg-red-50' : '',
                fileName ? 'bg-gray-50' : 'bg-white'
            ]"
        >
            <input
                ref="fileInput"
                type="file"
                :accept="accept"
                @change="handleFileSelect"
                class="hidden"
            />

            <div v-if="!fileName" class="text-center py-4">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                </svg>
                <p class="mt-2 text-sm text-gray-600">
                    <span class="font-medium text-primary hover:text-primary-dark">Cliquez pour uploader</span>
                    <span class="text-gray-400"> ou glissez-déposez</span>
                </p>
                <p v-if="hint" class="text-xs text-gray-400 mt-1">{{ hint }}</p>
            </div>

            <!-- Fichier sélectionné -->
            <div v-else class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-primary/10 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900 truncate max-w-[150px]">{{ fileName }}</p>
                        <p class="text-xs text-gray-500">{{ formatFileSize(modelValue?.size) }}</p>
                    </div>
                </div>
                <button
                    type="button"
                    @click.stop="removeFile"
                    class="p-1 text-gray-400 hover:text-red-500 transition-colors"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Message d'erreur -->
        <p v-if="error" class="text-sm text-red-500">{{ error }}</p>
    </div>
</template>
