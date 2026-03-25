<template>
    <div class="space-y-2">
        <label v-if="label" class="block text-sm font-medium text-gray-700">
            {{ label }}
            <span v-if="required" class="text-red-500 ml-1">*</span>
        </label>
        <input
            :type="type"
            :value="modelValue"
            @input="$emit('update:modelValue', $event.target.value)"
            :placeholder="placeholder"
            :required="required"
            :disabled="disabled"
            :class="[
                'w-full px-4 py-3 rounded-lg border transition-all duration-200',
                'border-gray-300 focus:border-primary focus:ring-2 focus:ring-primary/20',
                'disabled:bg-gray-100 disabled:cursor-not-allowed',
                error ? 'border-red-500 focus:border-red-500 focus:ring-red-200' : '',
            ]"
        />
        <p v-if="error" class="text-sm text-red-500">{{ error }}</p>
    </div>
</template>

<script setup>
defineProps({
    modelValue: [String, Number],
    label: String,
    type: {
        type: String,
        default: 'text'
    },
    placeholder: String,
    required: Boolean,
    disabled: Boolean,
    error: String,
    class: String
});

defineEmits(['update:modelValue']);
</script>
