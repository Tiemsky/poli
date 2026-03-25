<!-- resources/js/Pages/Enrollment/Create.vue -->
<template>
    <AuthenticatedLayout :user="auth.user">
        <div class="max-w-5xl mx-auto">
            <!-- Back Button -->
            <Link :href="urlPrev || route('dashboard')" class="inline-flex items-center text-primary hover:text-primary-dark mb-6">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Retour
            </Link>

            <!-- Stepper -->
            <Stepper :steps="['Informations personnelles', 'Informations professionnelles', 'Documents']" :currentStep="currentStep" />

            <!-- Form Card -->
            <Card>
                <form @submit.prevent="submitForm" enctype="multipart/form-data">

                    <!-- STEP 1: Personal Information -->
                    <div v-if="currentStep === 1" class="space-y-6">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">Informations personnelles</h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nationalité -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Nationalité <span class="text-red-500">*</span>
                                </label>
                                <select v-model="form.nationality"
                                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-primary focus:ring-2 focus:ring-primary/20">
                                    <option value="">Sélectionnez votre nationalité</option>
                                    <option v-for="country in countries" :key="country.id" :value="country.id">{{ country.name }}  </option>

                                </select>
                                <p v-if="form.errors.nationality" class="text-sm text-red-500 mt-1">{{ form.errors.nationality }}</p>
                            </div>

                            <!-- Genre -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Genre <span class="text-red-500">*</span>
                                </label>
                                <select v-model="form.gender"
                                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-primary focus:ring-2 focus:ring-primary/20">
                                    <option value="">Sélectionnez votre genre</option>
                                    <option value="M">Masculin</option>
                                    <option value="F">Féminin</option>
                                </select>
                                <p v-if="form.errors.gender" class="text-sm text-red-500 mt-1">{{ form.errors.gender }}</p>
                            </div>

                            <!-- Nom & Prénoms (disabled - vient du profil) -->
                            <Input
                                v-model="form.full_name"
                                label="Nom & Prénoms"
                                required
                                :disabled="true"
                            />

                            <!-- Date de naissance -->
                            <Input
                                v-model="form.birth_date"
                                label="Date de naissance"
                                type="date"
                                required
                                :error="form.errors.birth_date"
                            />

                            <!-- Lieu de naissance -->
                            <Input
                                v-model="form.birth_place"
                                label="Lieu de naissance"
                                required
                                :error="form.errors.birth_place"
                            />

                            <!-- Ville de résidence -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Ville de résidence <span class="text-red-500">*</span>
                                </label>
                                <select v-model="form.city_id"
                                        @change="fetchCommunes"
                                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-primary focus:ring-2 focus:ring-primary/20">
                                    <option value="">Sélectionnez votre ville</option>
                                    <option v-for="city in cities" :key="city.id" :value="city.id">{{ city.name }}</option>
                                </select>
                                <p v-if="form.errors.city_id" class="text-sm text-red-500 mt-1">{{ form.errors.city_id }}</p>
                            </div>

                            <!-- Commune de résidence -->
                            <div v-if="communes.length > 0" class="transition-all duration-300">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Commune de résidence <span class="text-red-500">*</span>
                                </label>

                                <select v-model="form.commune_id"
                                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-primary focus:ring-2 focus:ring-primary/20">
                                    <option value="">Sélectionnez votre commune</option>
                                    <option v-for="commune in communes"
                                            :key="commune.id"
                                            :value="commune.id">
                                        {{ commune.name }}
                                    </option>
                                </select>

                                <p v-if="form.errors.commune_id" class="text-sm text-red-500 mt-1">
                                    {{ form.errors.commune_id }}
                                </p>
                            </div>

                            <!-- Téléphone (disabled - vient du profil) -->
                            <Input
                                v-model="form.phone"
                                label="Numéro de Téléphone"
                                type="tel"
                                required
                                :disabled="true"
                            />

                            <!-- Contact d'urgence -->
                            <Input
                                v-model="form.emergency_phone"
                                label="Contact d'urgence (Téléphone)"
                                type="tel"
                                required
                                placeholder="Ex: 07 12 34 56 78"
                                :error="form.errors.emergency_phone"
                            />

                            <!-- Nom contact d'urgence -->
                            <Input
                                v-model="form.emergency_name"
                                label="Nom complet du contact d'urgence"
                                required
                                placeholder="Ex: Kouassi Marie Christine"
                                :error="form.errors.emergency_name"
                            />

                            <!-- Niveau d'étude -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Niveau d'étude</label>
                                <select v-model="form.education_level"
                                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-primary focus:ring-2 focus:ring-primary/20">
                                    <option value="Autre">Autre</option>
                                    <option value="CEPE">CEPE</option>
                                    <option value="BEPC">BEPC</option>
                                    <option value="BAC">BAC</option>
                                    <option value="BAC+2">BAC+2</option>
                                    <option value="BAC+3">BAC+3</option>
                                    <option value="BAC+5">BAC+5</option>
                                    <option value="BAC+8">BAC+8 et plus</option>
                                </select>
                                <p v-if="form.errors.education_level" class="text-sm text-red-500 mt-1">{{ form.errors.education_level }}</p>
                            </div>

                            <!-- Dernier diplôme -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Dernier diplôme obtenu</label>
                                <select v-model="form.last_diploma"
                                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-primary focus:ring-2 focus:ring-primary/20">
                                    <option value="">Sélectionnez un diplôme</option>
                                    <option value="CEPE">CEPE</option>
                                    <option value="BEPC">BEPC</option>
                                    <option value="BAC">Baccalauréat</option>
                                    <option value="BTS">BTS</option>
                                    <option value="DUT">DUT</option>
                                    <option value="Licence">Licence</option>
                                    <option value="Master">Master</option>
                                    <option value="Doctorat">Doctorat</option>
                                </select>
                                <p v-if="form.errors.last_diploma" class="text-sm text-red-500 mt-1">{{ form.errors.last_diploma }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- STEP 2: Professional Information -->
                    <div v-if="currentStep === 2" class="space-y-6">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">Informations professionnelles</h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Début expérience -->
                            <Input
                                v-model="form.experience_start"
                                label="Depuis quand êtes-vous coursier ?"
                                type="date"
                                :error="form.errors.experience_start"
                            />

                            <!-- Travail actuel -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Pour qui travaillez-vous actuellement ? <span class="text-red-500">*</span>
                                </label>
                                <select v-model="form.working_for"
                                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-primary focus:ring-2 focus:ring-primary/20">
                                    <option value="">Sélectionnez une option</option>
                                    <option value="independent">À mon compte (indépendant)</option>
                                    <option value="company">Une entreprise</option>
                                    <option value="platform">Une plateforme de livraison</option>
                                    <option value="none">Je ne travaille pas actuellement</option>
                                </select>
                                <p v-if="form.errors.working_for" class="text-sm text-red-500 mt-1">{{ form.errors.working_for }}</p>
                            </div>

                            <!-- Plateforme utilisée -->
                            <div v-if="form.working_for === 'platform'">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Nom de la plateforme
                                </label>
                                <input
                                    v-model="form.platform_name"
                                    type="text"
                                    placeholder="Ex: Glovo, Jumia Food, etc."
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-primary focus:ring-2 focus:ring-primary/20"
                                />
                                <p v-if="form.errors.platform_name" class="text-sm text-red-500 mt-1">{{ form.errors.platform_name }}</p>
                            </div>

                            <!-- Type de véhicule -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Type de véhicule <span class="text-red-500">*</span>
                                </label>
                                <select v-model="form.vehicle_type"
                                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-primary focus:ring-2 focus:ring-primary/20">
                                    <option value="">Sélectionnez un véhicule</option>
                                    <option value="moto">Moto</option>
                                    <option value="velo">Vélo</option>
                                    <option value="velo_electrique">Vélo électrique</option>
                                    <option value="scooter">Scooter</option>
                                    <option value="voiture">Voiture</option>
                                    <option value="camionnette">Camionnette</option>
                                </select>
                                <p v-if="form.errors.vehicle_type" class="text-sm text-red-500 mt-1">{{ form.errors.vehicle_type }}</p>
                            </div>

                            <!-- Propriétaire du véhicule -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Êtes-vous propriétaire du véhicule ? <span class="text-red-500">*</span>
                                </label>
                                <select v-model="form.is_vehicle_owner"
                                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-primary focus:ring-2 focus:ring-primary/20">
                                    <option value="">Sélectionnez</option>
                                    <option :value="true">Oui, je suis propriétaire</option>
                                    <option :value="false">Non, je loue/emprunte</option>
                                </select>
                                <p v-if="form.errors.is_vehicle_owner" class="text-sm text-red-500 mt-1">{{ form.errors.is_vehicle_owner }}</p>
                            </div>

                            <!-- Véhicule immatriculé -->
                            <div v-if="form.is_vehicle_owner || form.vehicle_type !== 'velo'">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Le véhicule est-il immatriculé ?
                                </label>
                                <select v-model="form.is_vehicle_registered"
                                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-primary focus:ring-2 focus:ring-primary/20">
                                    <option value="">Sélectionnez</option>
                                    <option :value="true">Oui</option>
                                    <option :value="false">Non</option>
                                </select>
                                <p v-if="form.errors.is_vehicle_registered" class="text-sm text-red-500 mt-1">{{ form.errors.is_vehicle_registered }}</p>
                            </div>

                            <!-- Numéro d'immatriculation -->
                            <div v-if="form.is_vehicle_registered">
                                <Input
                                    v-model="form.vehicle_number"
                                    label="Numéro d'immatriculation"
                                    placeholder="Ex: AB-1234-CD"
                                    :error="form.errors.vehicle_number"
                                />
                            </div>

                            <!-- Type de permis -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Type de permis de conduire
                                </label>
                                <select v-model="form.licence_type"
                                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-primary focus:ring-2 focus:ring-primary/20">
                                    <option value="">Sélectionnez un permis</option>
                                    <option value="A">Permis A (Moto)</option>
                                    <option value="B">Permis B (Voiture)</option>
                                    <option value="C">Permis C (Poids lourd)</option>
                                    <option value="AB">Permis A+B</option>
                                    <option value="none">Aucun permis</option>
                                </select>
                                <p v-if="form.errors.licence_type" class="text-sm text-red-500 mt-1">{{ form.errors.licence_type }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- STEP 3: Documents Upload -->
                    <div v-if="currentStep === 3" class="space-y-6">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">Documents requis</h2>

                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                            <div class="flex">
                                <svg class="w-5 h-5 text-yellow-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <p class="text-sm text-yellow-800">
                                    Formats acceptés : JPG, PNG, PDF. Taille maximale : 5MB par fichier.
                                </p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Photo d'identité -->
                            <DocumentUpload
                                v-model="form.photo_id"
                                label="Photo d'identité récente"
                                hint="Format : JPG/PNG, Max: 5MB"
                                :error="form.errors.photo_id"
                                @change="handleFileChange('photo_id', $event)"
                            />

                            <!-- CNI Recto -->
                            <DocumentUpload
                                v-model="form.doc_id_recto"
                                label="CNI - Recto"
                                hint="Format : JPG/PNG/PDF, Max: 5MB"
                                :error="form.errors.doc_id_recto"
                                @change="handleFileChange('doc_id_recto', $event)"
                            />

                            <!-- CNI Verso -->
                            <DocumentUpload
                                v-model="form.doc_id_verso"
                                label="CNI - Verso"
                                hint="Format : JPG/PNG/PDF, Max: 5MB"
                                :error="form.errors.doc_id_verso"
                                @change="handleFileChange('doc_id_verso', $event)"
                            />

                            <!-- Permis de conduire -->
                            <DocumentUpload
                                v-model="form.driving_licence"
                                label="Permis de conduire"
                                hint="Format : JPG/PNG/PDF, Max: 5MB"
                                :error="form.errors.driving_licence"
                                @change="handleFileChange('driving_licence', $event)"
                            />

                            <!-- Assurance véhicule -->
                            <DocumentUpload
                                v-model="form.insurance"
                                label="Attestation d'assurance véhicule"
                                hint="Format : JPG/PNG/PDF, Max: 5MB"
                                :error="form.errors.insurance"
                                @change="handleFileChange('insurance', $event)"
                            />

                            <!-- Carte grise -->
                            <DocumentUpload
                                v-model="form.gray_card"
                                label="Carte grise / Certificat d'immatriculation"
                                hint="Format : JPG/PNG/PDF, Max: 5MB"
                                :error="form.errors.gray_card"
                                @change="handleFileChange('gray_card', $event)"
                            />

                            <!-- CMU / Assurance maladie -->
                            <DocumentUpload
                                v-model="form.cmu"
                                label="CMU / Assurance maladie"
                                hint="Format : JPG/PNG/PDF, Max: 5MB (Optionnel)"
                                :error="form.errors.cmu"
                                @change="handleFileChange('cmu', $event)"
                                :required="false"
                            />
                        </div>

                        <!-- Preview des fichiers sélectionnés -->
                        <div v-if="hasFiles" class="mt-6 p-4 bg-gray-50 rounded-lg">
                            <h4 class="font-medium text-gray-900 mb-3">Fichiers sélectionnés</h4>
                            <ul class="space-y-2">
                                <li v-for="(file, key) in selectedFiles" :key="key" class="flex items-center justify-between text-sm">
                                    <span class="text-gray-600">{{ getDocumentLabel(key) }}</span>
                                    <span class="font-medium text-primary">{{ file.name }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="flex justify-between mt-8 pt-6 border-t border-gray-200">
                        <Button
                            v-if="currentStep > 1"
                            type="button"
                            variant="ghost"
                            @click="previousStep"
                        >
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                            Précédent
                        </Button>
                        <div v-else></div>

                        <Button
                            v-if="currentStep < 3"
                            type="button"
                            variant="secondary"
                            @click="nextStep"
                        >
                            Suivant
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </Button>

                        <Button
                            v-else
                            type="submit"
                            variant="secondary"
                            :disabled="form.processing"
                        >
                            <span v-if="form.processing">
                                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Enregistrement...
                            </span>
                            <span v-else>Soumettre la demande</span>
                        </Button>
                    </div>
                </form>
            </Card>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed, watch } from 'vue';
import { Link, useForm, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Card from '@/Components/Card.vue';
import Input from '@/Components/Input.vue';
import Button from '@/Components/Button.vue';
import Stepper from '@/Components/Stepper.vue';
import DocumentUpload from '@/Components/DocumentUpload.vue';

const props = defineProps({
    auth: Object,
  cities: Array,
    countries: Object,

    urlPrev: String
});

const currentStep = ref(1);
const selectedFiles = ref({});

const communes = ref({});

// Initialisation du formulaire avec useForm d'Inertia
const form = useForm({
    // === PERSONAL INFORMATION ===
    nationality: '',
    gender: '',
    full_name: props.auth.user.last_name + ' ' + props.auth.user.first_name ,           // Vient du profil utilisateur (disabled)
    birth_date: '',
    birth_place: '',
    city_id: '',
    commune_id: '',
    phone: props.auth.user.phone,               // Vient du profil utilisateur (disabled)
    emergency_phone: '',
    emergency_name: '',
    education_level: 'Autre',
    last_diploma: '',

    // === PROFESSIONAL INFORMATION ===
    experience_start: '',
    working_for: '',
    used_platform: false,
    platform_name: '',
    vehicle_type: '',
    is_vehicle_owner: null,
    is_vehicle_registered: null,
    vehicle_number: '',
    licence_type: '',

    // === DOCUMENTS (Files) ===
    photo_id: null,
    doc_id_recto: null,
    doc_id_verso: null,
    driving_licence: null,
    insurance: null,
    gray_card: null,
    cmu: null,
});

// Computed property pour vérifier si des fichiers sont sélectionnés
const hasFiles = computed(() => Object.keys(selectedFiles.value).length > 0);

// Gestion du changement de fichier
const handleFileChange = (fieldName, file) => {
    if (file) {
        // Validation côté client (optionnelle)
        const maxSize = 5 * 1024 * 1024; // 5MB
        const allowedTypes = ['image/jpeg', 'image/png', 'application/pdf'];

        if (file.size > maxSize) {
            alert(`Le fichier ${file.name} dépasse la taille maximale de 5MB`);
            return;
        }

        if (!allowedTypes.includes(file.type)) {
            alert(`Le format du fichier ${file.name} n'est pas accepté`);
            return;
        }

        // Stocker le fichier pour preview
        selectedFiles.value[fieldName] = file;

        // Assigner le fichier au form.data pour Inertia
        form[fieldName] = file;
    } else {
        // Supprimer le fichier si annulé
        delete selectedFiles.value[fieldName];
        form[fieldName] = null;
    }
};

// Récupération des communes selon la ville sélectionnée
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

// Optionnel : Déclencher le fetch dès que city_id change
watch(() => form.city_id, () => {
    fetchCommunes();
});

// Navigation entre les étapes avec validation
const nextStep = () => {
    if (validateStep(currentStep.value)) {
        currentStep.value++;
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
};

const previousStep = () => {
    currentStep.value--;
    window.scrollTo({ top: 0, behavior: 'smooth' });
};

// Validation par étape
const validateStep = (step) => {
    let isValid = true;

    if (step === 1) {
        // Validation Step 1: Personal Information
        const requiredFields = ['nationality', 'gender', 'birth_date', 'birth_place', 'city_id', 'emergency_phone', 'emergency_name'];

        for (const field of requiredFields) {
            if (!form[field]) {
                form.setError(field, 'Ce champ est requis');
                isValid = false;
            }
        }
    }

    if (step === 2) {
        // Validation Step 2: Professional Information
        const requiredFields = ['working_for', 'vehicle_type', 'is_vehicle_owner'];

        for (const field of requiredFields) {
            if (form[field] === null || form[field] === '') {
                form.setError(field, 'Ce champ est requis');
                isValid = false;
            }
        }

        // Validation conditionnelle
        if (form.is_vehicle_registered && !form.vehicle_number) {
            form.setError('vehicle_number', 'Le numéro d\'immatriculation est requis');
            isValid = false;
        }
    }

    if (step === 3) {
        // Validation Step 3: Documents (au moins les obligatoires)
        const requiredDocs = ['photo_id', 'doc_id_recto', 'doc_id_verso'];

        for (const doc of requiredDocs) {
            if (!form[doc]) {
                form.setError(doc, 'Ce document est requis');
                isValid = false;
            }
        }
    }

    return isValid;
};

// Soumission du formulaire
const submitForm = () => {
    if (!validateStep(3)) {
        return;
    }

    // Créer un FormData pour l'envoi des fichiers
    const formData = new FormData();

    // Ajouter tous les champs texte
    Object.keys(form.data).forEach(key => {
        const value = form.data[key];
        if (value instanceof File) {
            formData.append(key, value);
        } else if (value !== null && value !== '') {
            formData.append(key, value);
        }
    });

    // Soumettre via Inertia avec les options pour les fichiers
    form.post(route('enrollment.store'), {
        forceFormData: true,
        preserveScroll: true,
        onSuccess: () => {
            // Redirection ou message de succès
            router.visit(route('dashboard'), {
                preserveState: true,
                preserveScroll: true
            });
        },
        onError: (errors) => {
            // Les erreurs sont automatiquement gérées par Inertia
            console.error('Erreurs de validation:', errors);
        }
    });
};

// Helper pour afficher le label des documents
const getDocumentLabel = (key) => {
    const labels = {
        photo_id: 'Photo d\'identité',
        doc_id_recto: 'CNI Recto',
        doc_id_verso: 'CNI Verso',
        driving_licence: 'Permis de conduire',
        insurance: 'Assurance véhicule',
        gray_card: 'Carte grise',
        cmu: 'CMU / Assurance maladie'
    };
    return labels[key] || key;
};
</script>
