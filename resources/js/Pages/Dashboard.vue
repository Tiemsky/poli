<script setup>
import { ref, watch } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Card from '@/Components/Card.vue';
import debounce from 'lodash/debounce'; // Optionnel : npm install lodash si tu veux limiter les requêtes

const props = defineProps({
    auth: Object,
    stats: Object,
    enrollements: Array,
    filters: Object
});

// --- ÉTAT DES FILTRES ---
const searchQuery = ref(props.filters.search || '');
const selectedStatus = ref(props.filters.status || '');

// --- ÉTAT DU MODAL DE SUPPRESSION ---
const isDeleting = ref(false);
const showDeleteModal = ref(false);
const itemToDelete = ref(null);

// --- LOGIQUE DE RECHERCHE & FILTRE (Serveur) ---
// Utilisation d'un watcher pour mettre à jour l'URL sans recharger la page
watch([searchQuery, selectedStatus], debounce(([search, status]) => {
    router.get(
        route('dashboard'),
        { search, status },
        { preserveState: true, replace: true, preserveScroll: true }
    );
}, 300));

// --- LOGIQUE DE SUPPRESSION ---
const confirmDelete = (enrollement) => {
    itemToDelete.value = enrollement;
    showDeleteModal.value = true;
};

const closeModal = () => {
    showDeleteModal.value = false;
    itemToDelete.value = null;
};

const handleDelete = () => {
    if (!itemToDelete.value) return;

    isDeleting.value = true;
    router.delete(route('enrollement.delete', itemToDelete.value.key), {
        onSuccess: () => {
            closeModal();
            // Optionnel: Ajouter un toast de succès ici
        },
        onFinish: () => isDeleting.value = false,
    });
};

// --- HELPERS ---
const statusLabel = (status) => {
    const labels = {
        pending: 'En attente',
        approved: 'Approuvé',
        rejected: 'Rejeté'
    };
    return labels[status] || status;
};

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString('fr-FR', {
        day: '2-digit',
        month: 'short',
        year: 'numeric'
    });
};
</script>

<template>
    <AuthenticatedLayout :user="auth.user">
        <div class="space-y-6  mx-auto">

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <!-- <Card class="border-l-4 border-l-primary shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Total Coursiers</p>
                            <p class="text-3xl font-black text-gray-900">{{ stats.totalCouriers }}</p>
                        </div>
                        <div class="w-12 h-12 bg-primary/10 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                    </div>
                </Card>

                <Card class="border-l-4 border-l-secondary shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Livraisons Actives</p>
                            <p class="text-3xl font-black text-gray-900">{{ stats.activeDeliveries }}</p>
                        </div>
                        <div class="w-12 h-12 bg-secondary/20 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-secondary-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                    </div>
                </Card> -->

                <Card class="border-l-4 border-l-orange-500 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Enrôlements Attente</p>
                            <p class="text-3xl font-black text-gray-900">{{ stats.pending }}</p>
                        </div>
                        <div class="w-12 h-12 bg-orange-50 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </Card>

                <Card class="border-l-4 border-l-green-500 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Approuvés</p>
                            <p class="text-3xl font-black text-gray-900">{{ stats.completed }}</p>
                        </div>
                        <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </Card>
            </div>

            <div class="bg-white shadow-sm border border-gray-100 rounded-xl overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-50 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Demandes d'enrôlement</h3>
                        <p class="text-sm font-medium text-gray-500">{{ enrollements.length }} résultat(s) trouvé(s)</p>
                    </div>

                    <div class="flex flex-wrap items-center gap-3">
                        <div class="relative min-w-[160px]">
                            <select v-model="selectedStatus" class="w-full pl-3 pr-10 py-2 text-sm border-gray-200 rounded-lg focus:ring-primary focus:border-primary transition-all">
                                <option value="">Tous les statuts</option>
                                <option value="pending">En attente</option>
                                <option value="approved">Approuvée</option>
                                <option value="rejected">Refusée</option>
                            </select>
                        </div>

                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </span>
                            <input v-model="searchQuery" type="search" placeholder="Recherche par clé ou agence..."
                                   class="pl-10 pr-4 py-2 text-sm border-gray-200 rounded-lg w-full sm:w-64 focus:ring-primary focus:border-primary">
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full whitespace-nowrap">
                        <thead class="bg-gray-50/50">
                            <tr class="text-gray-400 text-[10px] uppercase font-bold tracking-widest border-b border-gray-100">
                                <th class="px-6 py-4 text-left">Référence</th>
                                <th class="px-6 py-4 text-left">Candidat</th>
                                <th class="px-6 py-4 text-left">Agence / Client</th>
                                <th class="px-6 py-4 text-left">Date</th>
                                <th class="px-6 py-4 text-left">Statut</th>
                                <th class="px-6 py-4 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <tr v-if="enrollements.length === 0">
                                <td colspan="6" class="px-6 py-20 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4 text-gray-200">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        </div>
                                        <p class="text-gray-500 font-medium">Aucun enrôlement trouvé</p>
                                        <p class="text-gray-400 text-xs mt-1">Essayez d'ajuster vos filtres de recherche.</p>
                                    </div>
                                </td>
                            </tr>
                            <tr v-for="enrollement in enrollements" :key="enrollement.id" class="hover:bg-gray-50/80 transition-colors group">
                                <td class="px-6 py-4 text-xs font-mono font-bold text-primary">{{ enrollement.key }}</td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-bold text-gray-900">{{ enrollement.user.last_name }} {{ enrollement.user.first_name }}</div>
                                    <div class="text-[10px] text-gray-400">ID: #{{ enrollement.id }}</div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600 font-medium">{{ enrollement.working_for }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ formatDate(enrollement.created_at) }}</td>
                                <td class="px-6 py-4">
                                    <span :class="[
                                        'px-2.5 py-1 rounded-md text-[10px] font-black uppercase tracking-wider shadow-sm',
                                        enrollement.status === 'pending' ? 'bg-orange-100 text-orange-700' :
                                        enrollement.status === 'approved' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'
                                    ]">
                                        {{ statusLabel(enrollement.status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right space-x-3">
                                    <Link :href="route('enrollement.show', enrollement.key)"
                                          class="inline-flex items-center px-3 py-1 bg-white border border-gray-200 text-[#00664F] rounded-md text-xs font-bold hover:bg-gray-50 transition-all">
                                        Détails
                                    </Link>
                                    <button @click="confirmDelete(enrollement)"
                                            class="inline-flex items-center px-3 py-1 bg-white border border-red-100 text-red-600 rounded-md text-xs font-bold hover:bg-red-50 transition-all">
                                        Supprimer
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <Transition enter-active-class="ease-out duration-300" enter-from-class="opacity-0" enter-to-class="opacity-100" leave-active-class="ease-in duration-200" leave-from-class="opacity-100" leave-to-class="opacity-0">
            <div v-if="showDeleteModal" class="fixed inset-0 z-[60] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" @click="closeModal"></div>

                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                    <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                    <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                    </svg>
                                </div>
                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                    <h3 class="text-lg leading-6 font-black text-gray-900" id="modal-title">Supprimer l'enrôlement ?</h3>
                                    <div class="mt-2">
                                        <p class="text-sm text-gray-500">
                                            Voulez-vous vraiment supprimer l'enrôlement <span class="font-bold text-red-600">{{ itemToDelete?.key }}</span> ? Cette action supprimera également tous les fichiers associés et est irréversible.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button @click="handleDelete"
                                    :disabled="isDeleting"
                                    class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-bold text-white hover:bg-red-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50">
                                {{ isDeleting ? 'Suppression...' : 'Confirmer la suppression' }}
                            </button>
                            <button @click="closeModal"
                                    class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-bold text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Annuler
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </Transition>

    </AuthenticatedLayout>
</template>

<style scoped>
/* Ajout d'une ombre douce personnalisée pour le look Fintech */
.Card {
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
}
</style>
