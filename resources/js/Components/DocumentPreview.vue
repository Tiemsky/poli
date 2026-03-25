<!-- resources/js/Components/DocumentPreview.vue -->
<script setup>
defineProps({
    label: {
        type: String,
        required: true
    },
    url: {
        type: String,
        default: null
    },
    required: {
        type: Boolean,
        default: false
    }
});

defineEmits(['preview']);

// Méthode pour ouvrir un lien dans un nouvel onglet
const openInNewTab = (url) => {
    window.open(url, '_blank', 'noopener,noreferrer');
};
</script>

<template>
    <div class="relative group">
        <div
            class="aspect-square rounded-lg border-2 border-dashed flex flex-col items-center justify-center p-2 transition-all"
            :class="url ? 'border-primary/30 bg-primary/5 hover:border-primary' : 'border-gray-300 bg-gray-50'"
        >
            <template v-if="url">
                <!-- Image -->
                <img
                    v-if="url.match(/\.(jpg|jpeg|png|webp)$/i)"
                    :src="url"
                    :alt="label"
                    class="w-full h-full object-cover rounded-lg cursor-pointer hover:opacity-90 transition-opacity"
                    @click="$emit('preview', url)"
                />

                <!-- PDF ou autre fichier -->
                <div
                    v-else
                    class="text-center cursor-pointer hover:opacity-80 transition-opacity"
                    @click="openInNewTab(url)"
                >
                    <svg class="w-8 h-8 mx-auto text-primary mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span class="text-xs text-gray-600 font-medium">
                        {{ url.match(/\.pdf$/i) ? 'PDF' : 'Voir' }}
                    </span>
                </div>
            </template>

            <!-- Aucun document -->
            <template v-else>
                <svg class="w-6 h-6 text-gray-400 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                <span class="text-xs text-gray-400 text-center">Non fourni</span>
            </template>
        </div>

        <div class="mt-2 text-center">
            <p class="text-xs font-medium text-gray-700 truncate px-1">{{ label }}</p>
            <span v-if="required && !url" class="text-xs text-red-500 font-medium">Requis ⚠️</span>
            <span v-else-if="url" class="text-xs text-green-600 font-medium">✓ Fourni</span>
        </div>
    </div>
</template>
