<template>
    <div :class="[
        'bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden'
      ]">
        <div v-if="title || $slots.header" class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <slot name="header">
                <h3 class="text-lg font-semibold text-gray-900">{{ title }}</h3>
                <p v-if="subtitle" class="text-sm text-gray-500 mt-1">{{ subtitle }}</p>
            </slot>
        </div>
        <div class="p-6">
            <slot />
        </div>
    </div>
</template>

<script setup>
defineProps({
    title: String,
    subtitle: String,
    class: String
});
</script>
