<template>
    <AuthenticatedLayout :user="auth.user">
        <div class="space-y-6">
            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <Card class="border-l-4 border-l-primary">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Total Coursiers</p>
                            <p class="text-3xl font-bold text-gray-900">{{ stats.totalCouriers }}</p>
                        </div>
                        <div class="w-12 h-12 bg-primary/10 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                    </div>
                </Card>

                <Card class="border-l-4 border-l-secondary">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Livraisons Actives</p>
                            <p class="text-3xl font-bold text-gray-900">{{ stats.activeDeliveries }}</p>
                        </div>
                        <div class="w-12 h-12 bg-secondary/20 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-secondary-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                    </div>
                </Card>

                <Card class="border-l-4 border-l-accent">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">En Attente</p>
                            <p class="text-3xl font-bold text-gray-900">{{ stats.pending }}</p>
                        </div>
                        <div class="w-12 h-12 bg-accent/10 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </Card>

                <Card class="border-l-4 border-l-green-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Terminées</p>
                            <p class="text-3xl font-bold text-gray-900">{{ stats.completed }}</p>
                        </div>
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </Card>
            </div>

            <!-- Actions & Recent -->
         <div class="overflow-hidden bg-white shadow-sm border border-gray-100 rounded-lg">
        <div class="px-6 py-5 flex items-start justify-between gap-x-12">
            <div>
                <h3 class="text-base font-bold text-gray-950">Applications</h3>
                <p class="text-sm font-normal text-gray-500">{{ enrollements.length }} demande(s)</p>
            </div>

            <div class="flex items-center gap-x-3.5">
                <button class="p-1.5 text-gray-400 hover:text-gray-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                </button>

                <div class="relative w-48">
                    <select v-model="selectedStatus" class="w-full px-4 py-2 pr-10 text-xs border border-gray-200 rounded-md appearance-none focus:ring-1 focus:ring-[#00664F]">
                        <option value="">Tous les statuts</option>
                        <option value="pending">En attente</option>
                        <option value="approved">Approuvée</option>
                        <option value="rejected">Refusée</option>
                    </select>
                </div>

                <div class="relative w-64">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </span>
                    <input v-model="searchQuery" type="search" placeholder="Recherche" class="w-full pl-10 pr-4 py-2 text-xs border border-gray-200 rounded-md focus:ring-1 focus:ring-[#00664F]">
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="border-b border-gray-100">
                    <tr class="text-gray-400 text-[11px] uppercase tracking-wider">
                        <th class="px-6 py-3 text-left font-medium">Nom</th>
                        <th class="px-6 py-3 text-left font-medium">Service</th>
                        <th class="px-6 py-3 text-left font-medium">Sous-service</th>
                        <th class="px-6 py-3 text-left font-medium">Agence</th>
                        <th class="px-6 py-3 text-left font-medium">Date de soumission</th>
                        <th class="px-6 py-3 text-left font-medium">Statut</th>
                        <th class="px-6 py-3 text-left font-medium">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <tr v-if="enrollements.length === 0">
                        <td colspan="7" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center">
                                <svg class="w-16 h-16 text-gray-200 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                <p class="text-gray-600 font-medium">Aucune demande</p>
                                <p class="text-gray-400 text-sm">Ajustez vos filtres ou lancez une nouvelle recherche.</p>
                            </div>
                        </td>
                    </tr>
                    <tr v-for="enrollement in enrollements" :key="enrollement.id" class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4 text-sm font-semibold text-gray-900">{{ enrollement.user.last_name }} {{ enrollement.user.first_name }} </td>
                        <td class="px-6 py-4 text-sm text-gray-600">Enrôlement</td>
                        <td class="px-6 py-4 text-sm text-gray-600">Coursier professionnel</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{enrollement.working_for }} </td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{enrollement.created_at  }} </td>
                        <td class="px-6 py-4">
                            <span :class="[
                                'px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide',
                                enrollement.status === 'pending' ? 'bg-orange-100 text-orange-700' :
                                enrollement.status === 'approved' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'
                            ]">
                                {{ statusLabel(enrollement.status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <Link :href="route('enrollement.show', enrollement.key)" class="text-[#00664F] hover:underline font-bold text-sm">Voir</Link>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Card from '@/Components/Card.vue';

const props = defineProps({
    auth: Object,
    stats: Object,
    applications: Array,
    enrollements: Object
});

const statusLabel = (status) => {
    const labels = {
        pending: 'En attente',
        approved: 'Approuvé',
        rejected: 'Rejeté'
    };
    return labels[status] || status;
};
</script>
