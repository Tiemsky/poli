<!-- resources/js/Components/PinInput.vue -->
<script setup>
import { ref, watch } from 'vue';

const props = defineProps({
    modelValue: {
        type: String,
        default: ''
    },
    label: {
        type: String,
        required: true
    },
    error: {
        type: String,
        default: ''
    },
    showToggle: {
        type: Boolean,
        default: true
    }
});

const emit = defineEmits(['update:modelValue']);

const pin = ref(props.modelValue);
const showPin = ref(false);
const showKeypad = ref(false);
const keypadPin = ref('');

const digits = [1, 2, 3, 4, 5, 6, 7, 8, 9, '', 0, 'delete'];

watch(() => props.modelValue, (newValue) => {
    pin.value = newValue;
    keypadPin.value = newValue;
});

watch(keypadPin, (newValue) => {
    if (newValue.length <= 4) {
        pin.value = newValue;
        emit('update:modelValue', newValue);
    }
});

const handleNumberClick = (number) => {
    if (number === 'delete') {
        keypadPin.value = keypadPin.value.slice(0, -1);
    } else if (keypadPin.value.length < 4) {
        keypadPin.value += number;
    }
    // IMPORTANT: Ne pas soumettre automatiquement
};

const togglePinVisibility = () => {
    showPin.value = !showPin.value;
};

const openKeypad = () => {
    keypadPin.value = pin.value;
    showKeypad.value = true;
};

const closeKeypad = () => {
    showKeypad.value = false;
};

const saveKeypad = () => {
    pin.value = keypadPin.value;
    emit('update:modelValue', keypadPin.value);
    showKeypad.value = false;
};

// Empêcher la soumission sur Enter
const handleKeydown = (event) => {
    if (event.key === 'Enter') {
        event.preventDefault();
        event.stopPropagation();
        return false;
    }
};
</script>

<template>
    <div class="space-y-2">
        <label class="block text-sm font-semibold text-gray-800">
            {{ label }}
            <span class="text-red-500">*</span>
        </label>

        <!-- PIN Display -->
        <div class="relative">
            <div
                @click="openKeypad"
                @keydown.enter.prevent
                tabindex="0"
                class="flex items-center justify-center space-x-3 p-4 bg-gray-50 border-2 rounded-xl cursor-pointer transition-all duration-200 hover:border-primary/50 focus:outline-none focus:ring-2 focus:ring-primary/20"
                :class="error ? 'border-red-500' : 'border-gray-200'"
            >
                <div
                    v-for="(digit, index) in 4"
                    :key="index"
                    class="w-12 h-12 rounded-full flex items-center justify-center text-xl font-bold transition-all duration-200"
                    :class="index < pin.length ? 'bg-primary text-white shadow-lg' : 'bg-white border-2 border-gray-300 text-gray-300'"
                >
                    <span v-if="showPin && pin[index]">{{ pin[index] }}</span>
                    <span v-else-if="!showPin && pin[index]">•</span>
                </div>
            </div>

            <!-- Toggle Visibility -->
            <button
                v-if="showToggle && pin.length > 0"
                @click.stop="togglePinVisibility"
                type="button"
                class="absolute right-3 top-1/2 -translate-y-1/2 p-2 text-gray-500 hover:text-primary transition-colors focus:outline-none focus:ring-2 focus:ring-primary/20 rounded-full"
            >
                <svg v-if="!showPin" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                </svg>
            </button>
        </div>

        <p class="text-sm text-gray-500 text-center">Saisissez votre code PIN à 4 chiffres</p>

        <!-- Error -->
        <p v-if="error" class="text-sm text-red-500 text-center">{{ error }}</p>

        <!-- Mobile Keypad Modal -->
        <Transition name="fade">
            <div v-if="showKeypad" class="fixed inset-0 z-50 flex items-end sm:items-center justify-center bg-black/50 backdrop-blur-sm p-4" @keydown.enter.prevent>
                <div class="bg-white rounded-t-3xl sm:rounded-3xl w-full max-w-sm overflow-hidden shadow-2xl animate-slide-up">
                    <!-- Keypad Header -->
                    <div class="flex items-center justify-between p-4 border-b border-gray-200">
                        <button @click="closeKeypad" type="button" class="text-gray-500 hover:text-gray-700 font-medium">
                            Annuler
                        </button>
                        <h3 class="font-semibold text-gray-800">Code PIN</h3>
                        <button @click="saveKeypad" type="button" class="text-primary font-semibold hover:text-primary-dark">
                            OK
                        </button>
                    </div>

                    <!-- PIN Preview -->
                    <div class="p-6 bg-gray-50">
                        <div class="flex justify-center space-x-3">
                            <div
                                v-for="index in 4"
                                :key="index"
                                class="w-10 h-10 rounded-full flex items-center justify-center text-lg font-bold"
                                :class="index <= keypadPin.length ? 'bg-primary text-white' : 'bg-white border-2 border-gray-300'"
                            >
                                {{ keypadPin[index - 1] || '' }}
                            </div>
                        </div>
                    </div>

                    <!-- Number Pad -->
                    <div class="p-4 bg-white">
                        <div class="grid grid-cols-3 gap-3">
                            <button
                                v-for="digit in digits"
                                :key="digit"
                                @click="digit !== '' && digit !== 'delete' ? handleNumberClick(digit) : digit === 'delete' ? handleNumberClick('delete') : null"
                                type="button"
                                class="h-16 rounded-2xl flex items-center justify-center text-2xl font-semibold transition-all duration-200 active:scale-95 focus:outline-none focus:ring-2 focus:ring-primary/20"
                                :class="[
                                    digit === '' ? 'invisible' : '',
                                    digit === 'delete' ? 'text-gray-600 hover:bg-gray-100' : 'bg-gray-50 hover:bg-gray-100 text-gray-800'
                                ]"
                            >
                                <span v-if="digit === 'delete'">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2M3 12l6.414 6.414a2 2 0 001.414.586H19a2 2 0 002-2V7a2 2 0 00-2-2h-8.172a2 2 0 00-1.414.586L3 12z" />
                                    </svg>
                                </span>
                                <span v-else>{{ digit }}</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </Transition>
    </div>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s ease;
}
.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}

@keyframes slide-up {
    from { transform: translateY(100%); }
    to { transform: translateY(0); }
}
.animate-slide-up {
    animation: slide-up 0.3s ease-out;
}
</style>
