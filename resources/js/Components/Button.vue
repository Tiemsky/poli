<template>
    <button
        :type="type"
        :class="[
            'px-6 py-3 rounded-lg font-medium transition-all duration-200 flex items-center justify-center space-x-2',
            variant === 'primary' ? 'bg-primary text-white hover:bg-primary-dark shadow-lg shadow-primary/30' : '',
            variant === 'secondary' ? 'bg-secondary text-primary hover:bg-secondary-dark shadow-lg shadow-secondary/30' : '',
            variant === 'outline' ? 'border-2 border-primary text-primary hover:bg-primary hover:text-white' : '',
            variant === 'ghost' ? 'text-gray-600 hover:bg-gray-100' : '',
            size === 'sm' ? 'px-4 py-2 text-sm' : '',
            size === 'lg' ? 'px-8 py-4 text-lg' : '',
            disabled ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer'
        ]"
        :disabled="disabled"
    >
        <slot />
    </button>
</template>

<script setup>
defineProps({
    type: {
        type: String,
        default: 'button'
    },
    variant: {
        type: String,
        default: 'primary',
        validator: (value) => ['primary', 'secondary', 'outline', 'ghost'].includes(value)
    },
    size: {
        type: String,
        default: 'md',
        validator: (value) => ['sm', 'md', 'lg'].includes(value)
    },
    disabled: {
        type: Boolean,
        default: false
    },
    class: {
        type: String,
        default: ''
    }
});
</script>
