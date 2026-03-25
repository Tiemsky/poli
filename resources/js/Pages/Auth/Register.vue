<!-- resources/js/Pages/Auth/Register.vue -->
<script setup>
import { ref, watch } from 'vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import PinInput from '@/Components/PinInput.vue';

const activeTab = ref('register');

const communes = ref({});

const props = defineProps({
    cities: Array,
});
const form = useForm({
  is_company: false,
  last_name: '',
  first_name: '',
  city_id: '',
  commune_id: '',
  phone: '',
  password: '',
  password_confirmation: '',
});




const fetchCommunes = async () => {
  // Vider la liste et l'ID sélectionné dès qu'on change de ville
  communes.value = [];
  form.commune_id = '';

  if (!form.city_id) return;

  try {
    const response = await axios.get(`/cities/${form.city_id}/communes`);
    // On accède à response.data (Axios) puis .data (ton JSON)
    if (response.data.success) {
      communes.value = response.data.data;
    }
  } catch (error) {
    console.error('Erreur communes:', error);
  }
};

watch(() => form.city_id, () => {
  fetchCommunes();
});

const phone = ref('');
const password = ref('');
const passwordConfirmation = ref('');

const submit = () => {
  form.phone = phone.value;
  form.password = password.value;
  form.password_confirmation = passwordConfirmation.value;

  form.post(route('register'), {
    onFinish: () => {
      password.value = '';
      passwordConfirmation.value = '';
    },
  });
};
</script>

<template>
  <GuestLayout>

    <Head title="Inscription" />

    <!-- Tabs -->
    <div class="flex rounded-xl bg-gray-100 p-1 mb-8">
      <Link :href="route('login')" :class="[
        'flex-1 py-3 px-4 rounded-lg text-sm font-semibold transition-all duration-200 text-center',
        activeTab === 'login'
          ? 'bg-white text-gray-900 shadow-sm'
          : 'text-gray-500 hover:text-gray-700'
      ]">
        Connexion
      </Link>
      <button @click="activeTab = 'register'" :class="[
        'flex-1 py-3 px-4 rounded-lg text-sm font-semibold transition-all duration-200',
        activeTab === 'register'
          ? 'bg-white text-gray-900 shadow-sm'
          : 'text-gray-500 hover:text-gray-700'
      ]">
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
        <select v-model="form.is_company"
          class="w-full px-4 py-4 bg-gray-50 border-2 border-gray-200 rounded-xl focus:border-primary focus:bg-white focus:outline-none transition-all duration-200 appearance-none cursor-pointer"
          required>
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
        <input id="last_name" v-model="form.last_name" type="text" placeholder="Votre nom"
          class="w-full px-4 py-4 bg-gray-50 border-2 border-gray-200 rounded-xl focus:border-primary focus:bg-white focus:outline-none transition-all duration-200"
          required autocomplete="family-name" />
        <InputError :message="form.errors.last_name" class="mt-2" />
      </div>

      <!-- First Name -->
      <div>
        <label for="first_name" class="block text-sm font-semibold text-gray-800 mb-2">
          Prénoms
          <span class="text-red-500">*</span>
        </label>
        <input id="first_name" v-model="form.first_name" type="text" placeholder="Vos prénoms"
          class="w-full px-4 py-4 bg-gray-50 border-2 border-gray-200 rounded-xl focus:border-primary focus:bg-white focus:outline-none transition-all duration-200"
          required autocomplete="given-name" />
        <InputError :message="form.errors.first_name" class="mt-2" />
      </div>

      <!-- City -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">
          Ville de résidence <span class="text-red-500">*</span>
        </label>
        <select v-model="form.city_id" @change="fetchCommunes"
          class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-primary focus:ring-2 focus:ring-primary/20">
          <option value="">Sélectionnez votre ville</option>
          <option v-for="city in cities" :key="city.id" :value="city.id">{{ city.name }}</option>
        </select>
        <p v-if="form.errors.city_id" class="text-sm text-red-500 mt-1">{{ form.errors.city_id }}</p>
      </div>

      <!-- Commune -->
      <div v-if="communes.length > 0" class="transition-all duration-300">
        <label class="block text-sm font-medium text-gray-700 mb-2">
          Commune de résidence <span class="text-red-500">*</span>
        </label>

        <select v-model="form.commune_id"
          class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-primary focus:ring-2 focus:ring-primary/20">
          <option value="">Sélectionnez votre commune</option>
          <option v-for="commune in communes" :key="commune.id" :value="commune.id">
            {{ commune.name }}
          </option>
        </select>

        <p v-if="form.errors.commune_id" class="text-sm text-red-500 mt-1">
          {{ form.errors.commune_id }}
        </p>
      </div>

      <!-- Phone Number -->
      <div>
        <label for="phone" class="block text-sm font-semibold text-gray-800 mb-2">
          Numéro de téléphone
          <span class="text-red-500">*</span>
        </label>
        <input id="phone" v-model="phone" type="tel" placeholder="07 00 00 00 00"
          class="w-full px-4 py-4 bg-gray-50 border-2 border-gray-200 rounded-xl focus:border-primary focus:bg-white focus:outline-none transition-all duration-200 text-lg"
          required autocomplete="tel" />
        <InputError :message="form.errors.phone" class="mt-2" />
      </div>

      <!-- PIN -->
      <PinInput v-model="password" label="Mot de passe" :error="form.errors.password" />

      <!-- Confirm PIN -->
      <PinInput v-model="passwordConfirmation" label="Confirmer le mot de passe"
        :error="form.errors.password_confirmation" />

      <!-- Submit Button -->
      <button type="submit" :disabled="form.processing"
        class="w-full py-4 px-6 bg-[#FACC15] hover:bg-[#EAB308] text-gray-900 font-bold rounded-xl shadow-lg shadow-yellow-500/30 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed transform active:scale-[0.98] mt-8">
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
