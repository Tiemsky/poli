<!-- resources/js/Layouts/AuthLayout.vue -->
<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="min-h-screen flex">
        <!-- Left Side - Form -->
        <div class="w-full lg:w-1/2 flex flex-col bg-white">
            <!-- Logo -->
            <div class="p-6 lg:p-8">
                <Link href="/">
                    <ApplicationLogo class="h-16 lg:h-20 w-auto" />
                </Link>
            </div>

            <!-- Form Container -->
            <div class="flex-1 flex items-center justify-center p-6 lg:p-12">
                <div class="w-full max-w-md">
                    <slot />
                </div>
            </div>
        </div>

        <!-- Right Side - Image -->
        <div class="hidden lg:flex w-1/2 bg-[#FACC15] relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-yellow-400 to-yellow-500"></div>

            <!-- Delivery Person Image -->
            <div class="relative z-10 flex-1 flex items-center justify-center p-12">
                <div class="relative w-full h-full">
                    <img
                        src="/images/delivery-perso.png"
                        alt="Livreur Poli"
                        class="w-full h-full object-cover"
                    />
                </div>
            </div>

            <!-- Text Content -->
            <div class="absolute bottom-0 left-0 right-0 p-12 text-white">
                <h2 class="text-3xl font-bold mb-3">Rejoignez l'équipe Poli</h2>
                <p class="text-lg opacity-90">Devenez livreur officiel de La Poste</p>
            </div>

            <!-- Decorative Elements -->
            <div class="absolute top-0 right-0 w-96 h-96 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2 blur-3xl"></div>
            <div class="absolute bottom-0 left-0 w-64 h-64 bg-primary/20 rounded-full translate-y-1/2 -translate-x-1/2 blur-2xl"></div>
        </div>
    </div>
</template>

<style scoped>
.primary {
    color: #0d7377;
}
</style>
