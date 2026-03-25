<template>
    <div class="min-h-screen bg-gray-100 flex">
        <aside class="fixed inset-y-0 left-0 w-64 bg-[#00664F] text-white flex flex-col z-20">
            <div class="p-6">
                <img src="/images/logo.jpeg" alt="POLI Logo" class="w-full max-w-[150px]" />
            </div>

            <nav class="flex-1 px-4 mt-4">
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4 px-2">Navigation</p>
                <ul class="space-y-1">
                    <li>
                        <Link :href="route('dashboard')"
                              :class="[
                                  'flex items-center px-4 py-3 rounded-md transition-all duration-200',
                                  route().current('dashboard') ? 'bg-[#197561] shadow-inner' : 'hover:bg-[#005542]'
                              ]">
                            <span class="mr-3"></span> <span class="text-sm font-medium">Accueil</span>
                        </Link>
                    </li>
                    <li>
                        <Link :href="route('enrollment.selection')"
                              :class="[
                                  'flex items-center px-4 py-3 rounded-md transition-all duration-200',
                                  route().current('enrollment.selection') ? 'bg-[#197561] shadow-inner' : 'hover:bg-[#005542]'
                              ]">
                            <span class="mr-3"></span> <span class="text-sm font-medium">Enrollement</span>
                        </Link>
                    </li>
                    </ul>
            </nav>

            <div class="p-4 border-t border-[#197561] bg-[#005542]/50">
                <div class="flex items-center space-x-3 mb-4">
                    <div class="w-10 h-10 rounded-full bg-gray-300 flex items-center justify-center text-[#00664F] font-bold">
                        {{ user.first_name }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold truncate">{{ user.first_name }} {{ user.last_name }}</p>
                        <p class="text-[10px] text-gray-300 truncate">Utilisateur</p>
                        <p class="text-[10px] text-gray-400 truncate">{{ user.phone }}</p>
                    </div>
                </div>
                <Link :href="route('logout')" method="post" as="button"
                      class="flex items-center px-4 py-3 rounded-md transition-all duration-200">
                    <span class="mr-2"></span> Déconnexion
                </Link>
            </div>
        </aside>

        <main class="flex-1 pl-64">
            <div class="relative w-full overflow-hidden">
                <img src="/images/poli-banner.png"
                     alt="Bannière POLI"
                     class="w-full h-auto object-cover" />

                <div class="absolute inset-0 bg-transparent"></div>
            </div>

            <div class="px-8 -mt-4 relative z-10 pb-12">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
                    <slot />
                </div>
            </div>
        </main>
    </div>
</template>

<script setup>
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

const page = usePage();
const user = computed(() => page.props.auth.user);

console.log(user)
</script>
