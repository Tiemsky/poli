<!-- resources/js/Pages/Enrollment/Show.vue -->
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import EnrollmentDetails from '@/Components/EnrollmentDetails.vue';
import { Head, router } from '@inertiajs/vue3';

const props = defineProps({
    auth: Object,
    enrollement: Object,
    // can: Object // { edit: boolean, approve: boolean }
});


const handleApprove = (enrollement) => {
    // if (confirm('Confirmer l\'approbation de ce dossier ?')) {
    //     router.post(route('enrollments.status', enrollement.id), {
    //         status: 'approved'
    //     });
    // }
};

const handleReject = (enrollement) => {
    const reason = prompt('Motif du rejet (obligatoire) :');
    // if (reason) {
    //     router.post(route('enrollments.status', enrollement.id), {
    //         status: 'rejected',
    //         rejection_reason: reason
    //     });
    // }
};

const handleEdit = (enrollement) => {
    // router.get(route('enrollments.edit', enrollement.id));
};
</script>

<template>
    <Head :title="`Dossier #${enrollement.key}`" />

    <AuthenticatedLayout :user="auth.user">
        <div class="max-w-6xl mx-auto py-8 px-4">
            <!-- Breadcrumb -->
            <nav class="flex mb-6 text-sm text-gray-500">
                <Link :href="route('dashboard')" class="hover:text-primary">Accueil</Link>
                <span class="mx-2">/</span>
                <!-- <Link :href="route('enrollments.index')" class="hover:text-primary">Mes enrôlements</Link> -->
                <span class="mx-2">/</span>
                <span class="text-gray-900">#{{ enrollement.key }}</span>
            </nav>

            <!-- Details Component -->
            <EnrollmentDetails
                :enrollement="enrollement"
                :show-actions="true"
                :can-edit="true"
                :can-approve="true"
                @approve="handleApprove"
                @reject="handleReject"
                @edit="handleEdit"
            />
        </div>
    </AuthenticatedLayout>
</template>
