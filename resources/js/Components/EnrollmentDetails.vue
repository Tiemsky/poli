<!-- resources/js/Components/EnrollementDetails.vue -->
<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import DetailItem from '@/Components/DetailItem.vue';
import DocumentPreview from '@/Components/DocumentPreview.vue'

const props = defineProps({
    enrollement: {
        type: Object,
        required: true
    },
    showActions: {
        type: Boolean,
        default: false
    },

});


const emit = defineEmits(['approve', 'reject', 'edit']);

// Configuration des statuts
const statusConfig = {
    pending: {
        label: 'En attente',
        color: 'bg-yellow-100 text-yellow-800 border-yellow-200',
    },
    in_review: {
        label: 'En vérification',
        color: 'bg-blue-100 text-blue-800 border-blue-200',
    },
    approved: {
        label: 'Approuvé',
        color: 'bg-green-100 text-green-800 border-green-200',
    },
    rejected: {
        label: 'Rejeté',
        color: 'bg-red-100 text-red-800 border-red-200',
    }
};

const status = computed(() => statusConfig[props.enrollement.status] || statusConfig.pending);

// Formatage des dates
const formatDate = (date) => {
    if (!date) return '-';
    return new Date(date).toLocaleDateString('fr-FR', {
        day: '2-digit',
        month: 'long',
        year: 'numeric'
    });
};

// Formatage du numéro d'immatriculation
const formatVehicleNumber = (number) => {
    if (!number) return '-';
    return number.toUpperCase().replace(/(.{2})(\d{4})(.{2})/, '$1-$2-$3');
};

// Mapping des valeurs affichées
const getLabel = (field, value) => {
    const labels = {
        gender: { M: 'Masculin', F: 'Féminin' },
        working_for: {
            independent: 'À mon compte',
            company: 'Une entreprise',
            platform: 'Une plateforme',
            none: 'Je ne travaille pas'
        },
        vehicle_type: {
            moto: 'Moto',
            velo: 'Vélo',
            velo_electrique: 'Vélo électrique',
            scooter: 'Scooter',
            voiture: 'Voiture',
            camionnette: 'Camionnette'
        },
        licence_type: {
            A: 'Permis A (Moto)',
            B: 'Permis B (Voiture)',
            C: 'Permis C (Poids lourd)',
            AB: 'Permis A+B',
            none: 'Aucun permis'
        }
    };
    return labels[field]?.[value] || value || '-';
};

</script>

<template>
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">
                        Dossier #{{ enrollement.key }}
                    </h3>
                    <p class="text-sm text-gray-500">
                        Soumis le {{ enrollement.created_at }}
                    </p>
                </div>
            </div>

            <!-- Status Badge -->
            <div :class="['inline-flex items-center px-3 py-1 rounded-full text-sm font-medium border', status.color]">
                <span class="mr-2">{{ status.icon }}</span>
                {{ status.label }}
            </div>
        </div>

        <div class="p-6">
            <!-- Section: Informations Personnelles -->
            <div class="mb-8">
                <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4 flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Informations personnelles
                </h4>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <DetailItem label="Nom complet" :value="enrollement.full_name" />
                    <DetailItem label="Genre" :value="getLabel('gender', enrollement.gender)" />
                    <DetailItem label="Nationalité" :value="enrollement.nationality" />
                    <DetailItem label="Date de naissance" :value="formatDate(enrollement.birth_date)" />
                    <DetailItem label="Lieu de naissance" :value="enrollement.birth_place" />
                    <DetailItem label="Ville" :value="enrollement?.city || '-'" />
                    <DetailItem label="Commune" :value="enrollement?.commune || '-'" />
                    <DetailItem label="Téléphone" :value="enrollement.phone" />
                    <DetailItem label="Contact d'urgence" :value="`${enrollement.emergency_name} (${enrollement.emergency_phone})`" />
                    <DetailItem label="Niveau d'étude" :value="enrollement.education_level" />
                    <DetailItem label="Dernier diplôme" :value="enrollement.last_diploma" />
                </div>
            </div>

            <!-- Section: Informations Professionnelles -->
            <div class="mb-8">
                <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4 flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    Informations professionnelles
                </h4>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <DetailItem label="Expérience depuis" :value="formatDate(enrollement.experience_start)" />
                    <DetailItem label="Travaille pour" :value="getLabel('working_for', enrollement.working_for)" />
                    <DetailItem
                        v-if="enrollement.used_platform"
                        label="Plateforme utilisée"
                        :value="enrollement.platform_name"
                    />
                    <DetailItem label="Type de véhicule" :value="getLabel('vehicle_type', enrollement.vehicle_type)" />
                    <DetailItem
                        label="Propriétaire du véhicule"
                        :value="enrollement.is_vehicle_owner ? 'Oui' : 'Non'"
                    />
                    <DetailItem
                        v-if="enrollement.is_vehicle_registered !== null"
                        label="Véhicule immatriculé"
                        :value="enrollement.is_vehicle_registered ? 'Oui' : 'Non'"
                    />
                    <DetailItem
                        v-if="enrollement.vehicle_number"
                        label="Immatriculation"
                        :value="formatVehicleNumber(enrollement.vehicle_number)"
                    />
                    <DetailItem
                        label="Permis de conduire"
                        :value="getLabel('licence_type', enrollement.licence_type)"
                    />
                </div>
            </div>

            <!-- Section: Documents -->
            <div class="mb-8">
                <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4 flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Documents fournis
                </h4>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    <DocumentPreview
                        label="Photo d'identité"
                        :url="enrollement.documents.photo_id"
                        :required="true"
                    />
                    <DocumentPreview
                        label="CNI - Recto"
                        :url="enrollement.documents.doc_id_recto"
                        :required="true"
                    />
                    <DocumentPreview
                        label="CNI - Verso"
                        :url="enrollement.documents.doc_id_verso"
                        :required="true"
                    />
                    <DocumentPreview
                        label="Permis de conduire"
                        :url="enrollement.documents.driving_licence"
                    />
                    <DocumentPreview
                        label="Assurance véhicule"
                        :url="enrollement.documents.insurance"
                    />
                    <DocumentPreview
                        label="Carte grise"
                        :url="enrollement.documents.gray_card"
                    />
                    <DocumentPreview
                        label="CMU / Assurance maladie"
                        :url="enrollement.documents.cmu"
                    />
                </div>
            </div>

            <!-- Motif de rejet (si applicable) -->
            <div v-if="enrollement.status === 'rejected' && enrollement.rejection_reason"
                 class="mb-8 p-4 bg-red-50 border border-red-200 rounded-lg">
                <h5 class="font-medium text-red-800 mb-2">Motif du rejet</h5>
                <p class="text-sm text-red-700">{{ enrollement.rejection_reason }}</p>
            </div>
        </div>
    </div>
</template>
