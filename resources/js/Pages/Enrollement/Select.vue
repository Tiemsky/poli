<template>
    <AuthenticatedLayout :user="auth.user">
        <div class="max-w-5xl mx-auto text-center py-12 px-4">
            <Link :href="urlPrev || route('dashboard')"
                  class="inline-flex items-center text-[#00664F] hover:text-[#005542] font-semibold mb-8 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Retour
            </Link>

            <div class="mb-12">
                <h1 class="text-4xl font-extrabold text-gray-900 mb-4">Choisissez votre mode d'enrôlement</h1>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Sélectionnez le type de compte que vous souhaitez créer sur la plateforme POLI.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">

                <Link :href="route('enrollment.showForm', ['coursier'])"
                      class="flex flex-col items-center justify-center p-8 bg-white border-2 border-transparent hover:border-secondary rounded-3xl shadow-xl transition-all duration-300 hover:scale-105 group relative overflow-hidden">
                    <div class="w-20 h-20 bg-secondary/20 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-secondary transition-colors">
                        <span class="text-4xl">🛵</span>
                    </div>
                    <h3 class="text-2xl font-bold text-primary mb-2">Coursier</h3>
                    <p class="text-sm text-gray-500 text-center">Inscrivez-vous pour commencer à livrer des colis dès aujourd'hui.</p>

                    <span class="absolute top-4 right-4 bg-green-100 text-green-700 text-[10px] font-bold px-2 py-1 rounded-full uppercase">
                        Disponible
                    </span>
                </Link>

                <div class="flex flex-col items-center justify-center p-8 bg-gray-50 border-2 border-dashed border-gray-200 rounded-3xl opacity-75 cursor-not-allowed relative group">
                    <div class="w-20 h-20 bg-gray-200 rounded-2xl flex items-center justify-center mb-6 grayscale">
                        <span class="text-4xl">🏢</span>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-400 mb-2">Agence de Poste</h3>
                    <p class="text-sm text-gray-400 text-center">Gérez vos flottes et vos opérations logistiques de manière centralisée.</p>

                    <div class="mt-6 px-4 py-1.5 bg-gray-200 text-gray-500 rounded-full text-xs font-bold uppercase tracking-widest">
                        Bientôt disponible
                    </div>

                    <div class="absolute inset-0 flex items-center justify-center bg-gray-900/10 opacity-0 group-hover:opacity-100 transition-opacity rounded-3xl">
                        <span class="bg-gray-800 text-white text-[10px] px-3 py-1 rounded-md">Option non disponible</span>
                    </div>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const page = usePage();

// Récupération de la prop partagée depuis le middleware
const urlPrev = computed(() => page.props.urlPrev);

defineProps({
    auth: Object
});
</script>
