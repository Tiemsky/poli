<template>
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div v-for="(step, index) in steps" :key="index" class="flex items-center flex-1">
                <div class="flex flex-col items-center">
                    <div :class="[
                        'w-10 h-10 rounded-full flex items-center justify-center font-semibold transition-all duration-300',
                        currentStep > index ? 'bg-primary text-white' : '',
                        currentStep === index ? 'bg-secondary text-primary ring-4 ring-secondary/30' : '',
                        currentStep < index ? 'bg-gray-200 text-gray-500' : ''
                    ]">
                        <span v-if="currentStep <= index">{{ index + 1 }}</span>
                        <svg v-else class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <span :class="[
                        'mt-2 text-sm font-medium',
                        currentStep >= index ? 'text-gray-900' : 'text-gray-500'
                    ]">{{ step }}</span>
                </div>
                <div v-if="index < steps.length - 1" :class="[
                    'flex-1 h-1 mx-4 rounded',
                    currentStep > index ? 'bg-primary' : 'bg-gray-200'
                ]"></div>
            </div>
        </div>
    </div>
</template>

<script setup>
defineProps({
    steps: {
        type: Array,
        required: true
    },
    currentStep: {
        type: Number,
        required: true
    }
});
</script>
