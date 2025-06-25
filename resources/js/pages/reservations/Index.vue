<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Reservations',
        href: '/',
    },
];
</script>

<template>
    <Head title="Reservations" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <h1>Holaaaaaaaaaaa</h1>
    </AppLayout>
</template>
