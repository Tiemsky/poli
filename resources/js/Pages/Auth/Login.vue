<!-- resources/js/Pages/Auth/Login.vue -->
<script setup>
import { ref } from 'vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import PinInput from '@/Components/PinInput.vue';

defineProps({
    status: {
        type: String,
    },
});

const activeTab = ref('login');
const phoneNumber = ref('');
const password = ref('');

const form = useForm({
    phone: '',
    password: '',
    remember: false,
});

const submit = () => {
    // Validation avant envoi
    if (!phoneNumber.value || phoneNumber.value.length < 10) {
        form.setError('phone', 'Numéro de téléphone invalide');
        return;
    }

    if (!password.value || password.value.length !== 4) {
        form.setError('password', 'Le code PIN doit contenir 4 chiffres');
        return;
    }

    // Assignation des valeurs au form
    form.phone = phoneNumber.value.replace(/\s/g, '');
    form.password = password.value;

    form.post(route('login'), {
        preserveScroll: true,
        onError: () => {
            // Garder le PIN pour que l'utilisateur puisse le corriger
            password.value = '';
        }
    });
};

// Empêcher la soumission sur Enter dans les champs
const preventSubmitOnEnter = (event) => {
    if (event.key === 'Enter') {
        event.preventDefault();
        return false;
    }
};
</script>

<template>
    <GuestLayout>
        <Head title="Connexion" />

        <!-- Tabs -->
        <div class="flex rounded-xl bg-gray-100 p-1 mb-8">
            <button
                @click="activeTab = 'login'"
                type="button"
                class="flex-1 py-3 px-4 rounded-lg text-sm font-semibold transition-all duration-200"
                :class="activeTab === 'login' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700'"
            >
                Connexion
            </button>
            <Link
                :href="route('register')"
                class="flex-1 py-3 px-4 rounded-lg text-sm font-semibold transition-all duration-200 text-center"
                :class="activeTab === 'register' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700'"
            >
                Inscription
            </Link>
        </div>

        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Connexion</h1>
            <p class="text-gray-600">Connectez-vous à votre compte</p>
        </div>

        <!-- Status Message -->
        <div v-if="status" class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl">
            <p class="text-sm font-medium text-green-700">{{ status }}</p>
        </div>

        <!-- Login Form -->
        <form @submit.prevent="submit" @keydown.enter.prevent class="space-y-6">
            <!-- Phone Number -->
            <div>
                <label for="phone" class="block text-sm font-semibold text-gray-800 mb-2">
                    Numéro de téléphone
                    <span class="text-red-500">*</span>
                </label>
                <input
                    id="phone"
                    v-model="phoneNumber"
                    type="tel"
                    placeholder="07 00 00 00 00"
                    class="w-full px-4 py-4 bg-gray-50 border-2 border-gray-200 rounded-xl focus:border-[#0d7377] focus:bg-white focus:outline-none transition-all duration-200 text-lg"
                    required
                    autocomplete="tel"
                    @keydown.enter.prevent
                    maxlength="10"
                />
                <InputError :message="form.errors.phone" class="mt-2" />
            </div>

            <!-- PIN Input -->
            <PinInput
                v-model="password"
                label="Code PIN"
                :error="form.errors.password"
                @keydown.enter.prevent
            />

            <!-- Forgot Password -->
            <div class="flex justify-end">
                <Link
                    :href="route('password.request')"
                    class="text-sm font-medium text-[#0d7377] hover:text-[#095053] transition-colors"
                >
                    Mot de passe oublié ?
                </Link>
            </div>

            <!-- Submit Button -->
            <button
                type="submit"
                :disabled="form.processing || !phoneNumber || password.length !== 4"
                class="w-full py-4 px-6 bg-[#FACC15] hover:bg-[#EAB308] text-gray-900 font-bold rounded-xl shadow-lg shadow-yellow-500/30 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed transform active:scale-[0.98]"
            >
                <span v-if="form.processing">
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-gray-900 inline" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Connexion...
                </span>
                <span v-else>Se connecter</span>
            </button>
        </form>

        <!-- Terms -->
        <p class="mt-8 text-center text-sm text-gray-500">
            En continuant, vous acceptez les
            <Link href="#" class="font-semibold text-[#0d7377] hover:text-[#095053] underline">
                Conditions d'utilisation
            </Link>
        </p>
    </GuestLayout>
</template>
