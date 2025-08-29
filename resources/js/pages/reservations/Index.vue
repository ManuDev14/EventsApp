<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Event } from '@/models/Event';
import { Reservation } from '@/models/Reservation';
import { Room } from '@/models/Room';
import { type BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import CancelEventByName from './partials/CancelEventByName.vue';
import CreateReservationModal from './partials/CreateReservationModal.vue';
import Filters from './partials/Filters.vue';
import ReservationTable from './partials/ReservationTable.vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Reservations',
        href: '/',
    },
];

const props = defineProps<{
    reservations: { data: Reservation[]; links: any[]; total: number };
    events: Event[];
    rooms: Room[];
    filters: {
        status: string;
    };
    statusOptions: { label: string; value: string }[];
}>();

const selectedStatus = ref(props.filters.status ?? 'none');

watch(selectedStatus, (value) => {
    router.get(
        route('reservations.index'),
        { status: value },
        {
            preserveScroll: true,
            replace: true,
        },
    );
});
</script>

<template>
    <Head title="Reservations" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="mx-2 my-6 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <h1 class="text-2xl font-bold">Reservations</h1>
            <Filters v-model="selectedStatus" :status="selectedStatus" :options="statusOptions" />
            <div class="flex flex-col gap-2 md:flex-row md:items-center md:gap-4">
                <CreateReservationModal :events="events" :rooms="rooms" />
                <CancelEventByName />
            </div>
        </div>

        <div class="mx-5">
            <ReservationTable :reservations="reservations" />
        </div>
    </AppLayout>
</template>
