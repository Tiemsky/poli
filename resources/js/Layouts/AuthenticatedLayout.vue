<template>
    <div class="min-h-screen bg-gray-100 flex flex-col lg:flex-row">

        <div
            v-if="isMobileMenuOpen"
            @click="isMobileMenuOpen = false"
            class="fixed inset-0 bg-black/50 z-30 lg:hidden"
        ></div>

        <aside
            :class="[
                'fixed inset-y-0 left-0 w-72 lg:w-64 bg-[#00664F] text-white flex flex-col z-40 transform transition-transform duration-300 ease-in-out lg:translate-x-0',
                isMobileMenuOpen ? 'translate-x-0' : '-translate-x-full'
            ]"
        >
            <div class="p-6 flex items-center justify-between">
                <img src="/images/logo.jpeg" alt="POLI Logo" class="w-full max-w-[120px] lg:max-w-[150px] h-auto rounded" />
                <button @click="isMobileMenuOpen = false" class="lg:hidden p-2 text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <nav class="flex-1 px-4 mt-4 overflow-y-auto">
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4 px-2">Navigation</p>
                <ul class="space-y-1">
                    <li>
                        <Link :href="route('dashboard')"
                              :class="[
                                  'flex items-center px-4 py-3 rounded-md transition-all duration-200 group',
                                  route().current('dashboard') ? 'bg-[#197561] shadow-inner text-white' : 'hover:bg-[#005542] text-gray-100'
                              ]">
                            <svg class="w-5 h-5 mr-3 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                            <span class="text-sm font-medium">Accueil</span>
                        </Link>
                    </li>
                    <li>
                        <Link :href="route('enrollment.selection')"
                              :class="[
                                  'flex items-center px-4 py-3 rounded-md transition-all duration-200 group',
                                  route().current('enrollment.selection') ? 'bg-[#197561] shadow-inner text-white' : 'hover:bg-[#005542] text-gray-100'
                              ]">
                            <svg class="w-5 h-5 mr-3 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <span class="text-sm font-medium">Enrôlement</span>
                        </Link>
                    </li>
                </ul>
            </nav>

            <div class="p-4 border-t border-[#197561] bg-[#005542]/50">
                <div class="flex items-center space-x-3 mb-4">
                    <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center text-white font-bold shrink-0 uppercase">
                        {{ user.first_name[0] }}{{ user.last_name[0] }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold truncate leading-none mb-1">{{ user.first_name }} {{ user.last_name }}</p>
                        <p class="text-[10px] text-gray-300 truncate">{{ user.phone }}</p>
                    </div>
                </div>
                <Link :href="route('logout')" method="post" as="button"
                      class="flex items-center justify-center w-full px-4 py-2 text-xs font-bold bg-white/10 hover:bg-white/20 rounded-md transition-all duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    Déconnexion
                </Link>
            </div>
        </aside>

        <main class="flex-1 lg:pl-64 flex flex-col transition-all duration-300">

            <header class="lg:hidden bg-[#00664F] text-white p-4 flex items-center justify-between sticky top-0 z-30">
                <img src="/images/logo.jpeg" alt="Logo" class="h-8 w-auto rounded" />
                <button @click="isMobileMenuOpen = true" class="p-2 bg-[#197561] rounded-md">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"/></svg>
                </button>
            </header>

            <div class="relative w-full h-48 lg:h-64 overflow-hidden shrink-0">
                <img src="/images/poli-banner.png"
                     alt="Bannière POLI"
                     class="w-full h-full object-cover" />
                <div class="absolute inset-0 bg-gradient-to-b from-black/20 to-transparent"></div>
            </div>

            <div class="px-4 sm:px-6 lg:px-8 -mt-10 lg:-mt-12 relative z-10 pb-12 w-full max-w-7xl mx-auto">
                <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-4 sm:p-6 lg:p-8">
                    <slot />
                </div>
            </div>
        </main>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

const page = usePage();
const user = computed(() => page.props.auth.user);
const isMobileMenuOpen = ref(false);
</script>

<style scoped>
/* Empêcher le scroll du body quand le menu mobile est ouvert */
@media (max-width: 1023px) {
    body.menu-open {
        overflow: hidden;
    }
}
</style>
