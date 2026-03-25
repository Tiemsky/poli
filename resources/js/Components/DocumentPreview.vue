<script setup>
import { ref, computed } from 'vue';

const props = defineProps({
    label: { type: String, required: true },
    url: { type: String, default: null },
    required: { type: Boolean, default: false }
});

const emit = defineEmits(['preview', 'error']);

const isLoading = ref(false);
const hasError = ref(false);

// Type de fichier
const fileType = computed(() => {
    if (!props.url) return 'none';
    if (props.url.match(/\.(jpg|jpeg|png|webp|gif)$/i)) return 'image';
    if (props.url.match(/\.pdf$/i)) return 'pdf';
    return 'other';
});

// Ouvrir le document
const openDocument = (download = false) => {
    if (!props.url) return;

    isLoading.value = true;

    try {
        // Au lieu de concaténer "/download", on utilise le paramètre GET que ton contrôleur écoute déjà
        const separator = props.url.includes('?') ? '&' : '?';
        const finalUrl = download ? `${props.url}${separator}download=1` : props.url;


        console.log('Tentative d\'ouverture de :', finalUrl);
        // On laisse le navigateur gérer l'ouverture.
        // Si c'est un 404, le nouvel onglet affichera l'erreur proprement au lieu de rediriger l'app.
        window.open(finalUrl, '_blank', 'noopener,noreferrer');

    } catch (error) {
        console.error('Error:', error);
        alert(' Impossible d\'ouvrir le document.');
    } finally {
        isLoading.value = false;
    }
};

const handleImageError = () => {
    hasError.value = true;
    emit('error', { label: props.label, reason: 'load_error' });
};
</script>

<template>
    <div class="relative group">
        <div
            class="aspect-square rounded-xl border-2 border-dashed flex flex-col items-center justify-center p-3 transition-all duration-200"
            :class="[
                url && !hasError
                    ? 'border-primary/30 bg-primary/5 hover:border-primary hover:bg-primary/10 cursor-pointer'
                    : 'border-gray-200 bg-gray-50',
                isLoading ? 'opacity-50 pointer-events-none' : ''
            ]"
            @click="url && !hasError && openDocument(false)"
        >
            <!-- Loading -->
            <div v-if="isLoading" class="absolute inset-0 flex items-center justify-center bg-white/80 rounded-xl z-10">
                <svg class="animate-spin h-6 w-6 text-primary" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>

            <template v-if="url && !hasError">
                <!-- Image -->
                <img
                    v-if="fileType === 'image'"
                    :src="url"
                    :alt="label"
                    class="w-full h-full object-cover rounded-lg transition-opacity hover:opacity-90"
                    @click.stop="emit('preview', url)"
                    @error="handleImageError"
                    loading="lazy"
                />

                <!-- PDF -->
                <div v-else-if="fileType === 'pdf'" class="text-center p-2">
                    <div class="w-12 h-12 mx-auto mb-2 bg-red-100 rounded-lg flex items-center justify-center">
                        <svg class="w-7 h-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <span class="text-xs font-semibold text-gray-700">PDF</span>
                    <span class="text-[10px] text-gray-400 block mt-1">Cliquez pour ouvrir</span>
                </div>

                <!-- Other -->
                <div v-else class="text-center p-2">
                    <div class="w-12 h-12 mx-auto mb-2 bg-gray-100 rounded-lg flex items-center justify-center">
                        <svg class="w-7 h-7 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <span class="text-xs font-medium text-gray-600">Document</span>
                </div>
            </template>

            <!-- No Document / Error -->
            <template v-else>
                <div class="text-center p-2">
                    <svg class="w-10 h-10 mx-auto mb-2" :class="hasError ? 'text-red-400' : 'text-gray-300'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path v-if="hasError" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    <span class="text-xs text-center block" :class="hasError ? 'text-red-500 font-medium' : 'text-gray-400'">
                        {{ hasError ? 'Inaccessible' : 'Non fourni' }}
                    </span>
                </div>
            </template>
        </div>

        <!-- Label -->
        <div class="mt-3 text-center">
            <p class="text-xs font-medium text-gray-700 truncate px-2">{{ label }}</p>
            <span v-if="required && !url" class="text-[10px] font-semibold text-red-500 bg-red-50 px-2 py-0.5 rounded-full mt-1 inline-block">Requis ⚠️</span>
            <span v-else-if="url && !hasError" class="text-[10px] font-semibold text-green-600 bg-green-50 px-2 py-0.5 rounded-full mt-1 inline-block">✓ Fourni</span>
        </div>
    </div>
</template>
