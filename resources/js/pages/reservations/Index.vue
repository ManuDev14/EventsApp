<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { Event } from '../models/Event';
import { Reservation } from '../models/Reservation';
import { Room } from '../models/Room';
import CreateReservationModal from './partials/CreateReservationModal.vue';
import ReservationTable from './partials/ReservationTable.vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Reservations',
        href: '/',
    },
];

defineProps<{ reservations: Reservation[]; events: Event[]; rooms: Room[] }>();
</script>

<template>
    <Head title="Reservations" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="mx-2 my-6 flex items-center justify-between">
            <h1 class="text-2xl font-bold">Reservations</h1>
            <CreateReservationModal :events="events" :rooms="rooms" />
        </div>

        <ReservationTable :reservations="reservations" />
    </AppLayout>
</template>
