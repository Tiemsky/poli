<script setup>
defineProps({
    label: {
        type: String,
        required: true
    },
    value: {
        type: [String, Number],
        default: '-'
    }
});
</script>

<template>
    <div class="p-3 bg-gray-50 rounded-lg">
        <dt class="text-xs font-medium text-gray-500 uppercase">{{ label }}</dt>
        <dd class="mt-1 text-sm font-semibold text-gray-900">{{ value }}</dd>
    </div>
</template>
