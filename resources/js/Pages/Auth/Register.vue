<!-- resources/js/Pages/Auth/Register.vue -->
<script setup>
import { ref } from 'vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import PinInput from '@/Components/PinInput.vue';

const activeTab = ref('register');
const form = useForm({
    is_company: false,
    last_name: '',
    first_name: '',
    city: '',
    commune: '',
    phone: '',
    pin: '',
    pin_confirmation: '',
});

const phoneNumber = ref('');
const pin = ref('');
const pinConfirmation = ref('');

const submit = () => {
    form.phone = phoneNumber.value;
    form.pin = pin.value;
    form.pin_confirmation = pinConfirmation.value;

    form.post(route('register'), {
        onFinish: () => {
            pin.value = '';
            pinConfirmation.value = '';
        },
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Inscription" />

        <!-- Tabs -->
        <div class="flex rounded-xl bg-gray-100 p-1 mb-8">
            <Link
                :href="route('login')"
                :class="[
                    'flex-1 py-3 px-4 rounded-lg text-sm font-semibold transition-all duration-200 text-center',
                    activeTab === 'login'
                        ? 'bg-white text-gray-900 shadow-sm'
                        : 'text-gray-500 hover:text-gray-700'
                ]"
            >
                Connexion
            </Link>
            <button
                @click="activeTab = 'register'"
                :class="[
                    'flex-1 py-3 px-4 rounded-lg text-sm font-semibold transition-all duration-200',
                    activeTab === 'register'
                        ? 'bg-white text-gray-900 shadow-sm'
                        : 'text-gray-500 hover:text-gray-700'
                ]"
            >
                Inscription
            </button>
        </div>

        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Créer un compte</h1>
            <p class="text-gray-600">Rejoignez l'équipe des livreurs La Poste</p>
        </div>

        <!-- Registration Form -->
        <form @submit.prevent="submit" class="space-y-5">
            <!-- Is Company -->
            <div>
                <label class="block text-sm font-semibold text-gray-800 mb-2">
                    Êtes-vous une entreprise ?
                    <span class="text-red-500">*</span>
                </label>
                <select
                    v-model="form.is_company"
                    class="w-full px-4 py-4 bg-gray-50 border-2 border-gray-200 rounded-xl focus:border-primary focus:bg-white focus:outline-none transition-all duration-200 appearance-none cursor-pointer"
                    required
                >
                    <option :value="false">Non, je suis un particulier</option>
                    <option :value="true">Oui, je suis une entreprise</option>
                </select>
                <InputError :message="form.errors.is_company" class="mt-2" />
            </div>

            <!-- Last Name -->
            <div>
                <label for="last_name" class="block text-sm font-semibold text-gray-800 mb-2">
                    Nom
                    <span class="text-red-500">*</span>
                </label>
                <input
                    id="last_name"
                    v-model="form.last_name"
                    type="text"
                    placeholder="Votre nom"
                    class="w-full px-4 py-4 bg-gray-50 border-2 border-gray-200 rounded-xl focus:border-primary focus:bg-white focus:outline-none transition-all duration-200"
                    required
                    autocomplete="family-name"
                />
                <InputError :message="form.errors.last_name" class="mt-2" />
            </div>

            <!-- First Name -->
            <div>
                <label for="first_name" class="block text-sm font-semibold text-gray-800 mb-2">
                    Prénoms
                    <span class="text-red-500">*</span>
                </label>
                <input
                    id="first_name"
                    v-model="form.first_name"
                    type="text"
                    placeholder="Vos prénoms"
                    class="w-full px-4 py-4 bg-gray-50 border-2 border-gray-200 rounded-xl focus:border-primary focus:bg-white focus:outline-none transition-all duration-200"
                    required
                    autocomplete="given-name"
                />
                <InputError :message="form.errors.first_name" class="mt-2" />
            </div>

            <!-- City -->
            <div>
                <label for="city" class="block text-sm font-semibold text-gray-800 mb-2">
                    Ville
                    <span class="text-red-500">*</span>
                </label>
                <select
                    id="city"
                    v-model="form.city"
                    class="w-full px-4 py-4 bg-gray-50 border-2 border-gray-200 rounded-xl focus:border-primary focus:bg-white focus:outline-none transition-all duration-200 appearance-none cursor-pointer"
                    required
                >
                    <option value="">Sélectionnez votre ville</option>
                    <option value="Abidjan">Abidjan</option>
                    <option value="Bouaké">Bouaké</option>
                    <option value="Yamoussoukro">Yamoussoukro</option>
                    <option value="San-Pédro">San-Pédro</option>
                </select>
                <InputError :message="form.errors.city" class="mt-2" />
            </div>

            <!-- Commune -->
            <div>
                <label for="commune" class="block text-sm font-semibold text-gray-800 mb-2">
                    Commune
                    <span class="text-red-500">*</span>
                </label>
                <select
                    id="commune"
                    v-model="form.commune"
                    :disabled="!form.city"
                    class="w-full px-4 py-4 bg-gray-50 border-2 border-gray-200 rounded-xl focus:border-primary focus:bg-white focus:outline-none transition-all duration-200 appearance-none cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed"

                >
                    <option value="">{{ form.city ? 'Sélectionnez votre commune' : 'Sélectionnez d\'abord une ville' }}</option>
                    <option v-if="form.city === 'Abidjan'" value="Cocody">Cocody</option>
                    <option v-if="form.city === 'Abidjan'" value="Yopougon">Yopougon</option>
                    <option v-if="form.city === 'Abidjan'" value="Abobo">Abobo</option>
                    <option v-if="form.city === 'Abidjan'" value="Adjamé">Adjamé</option>
                    <option v-if="form.city === 'Abidjan'" value="Treichville">Treichville</option>
                </select>
                <InputError :message="form.errors.commune" class="mt-2" />
            </div>

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
                    class="w-full px-4 py-4 bg-gray-50 border-2 border-gray-200 rounded-xl focus:border-primary focus:bg-white focus:outline-none transition-all duration-200 text-lg"
                    required
                    autocomplete="tel"
                />
                <InputError :message="form.errors.phone" class="mt-2" />
            </div>

            <!-- PIN -->
            <PinInput
                v-model="pin"
                label="Mot de passe"
                :error="form.errors.pin"
            />

            <!-- Confirm PIN -->
            <PinInput
                v-model="pinConfirmation"
                label="Confirmer le mot de passe"
                :error="form.errors.pin_confirmation"
            />

            <!-- Submit Button -->
            <button
                type="submit"
                :disabled="form.processing"
                class="w-full py-4 px-6 bg-[#FACC15] hover:bg-[#EAB308] text-gray-900 font-bold rounded-xl shadow-lg shadow-yellow-500/30 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed transform active:scale-[0.98] mt-8"
            >
                <span v-if="form.processing">Inscription...</span>
                <span v-else>S'inscrire</span>
            </button>
        </form>

        <!-- Terms -->
        <p class="mt-8 text-center text-sm text-gray-500">
            En continuant, vous acceptez les
            <Link href="#" class="font-semibold text-primary hover:text-primary-dark underline">
                Conditions d'utilisation
            </Link>
        </p>
    </GuestLayout>
</template>
