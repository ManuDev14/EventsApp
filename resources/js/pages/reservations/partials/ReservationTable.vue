<script setup lang="ts">
import Pagination from '@/components/common/Pagination.vue';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Reservation } from '@/models/Reservation';

defineProps<{ reservations: { data: Reservation[]; links: any[]; total: number } }>();
</script>

<template>
    <Table>
        <TableHeader>
            <TableRow>
                <TableHead>Event</TableHead>
                <TableHead>Room</TableHead>
                <TableHead>Start Time</TableHead>
                <TableHead>End Time</TableHead>
                <TableHead>Date</TableHead>
                <TableHead>Status</TableHead>
                <TableHead>Actions</TableHead>
            </TableRow>
        </TableHeader>
        <TableBody>
            <TableRow v-for="(reservation, index) in reservations.data" :key="index">
                <TableCell>{{ reservation.event_name }}</TableCell>
                <TableCell>{{ reservation.room_name }}</TableCell>
                <TableCell>{{ reservation.start_time }}</TableCell>
                <TableCell>{{ reservation.end_time }}</TableCell>
                <TableCell>{{ reservation.event_date_human }}</TableCell>
                <TableCell>{{ reservation.status }}</TableCell>
                <TableCell>
                    <Button>Editar</Button>
                </TableCell>
            </TableRow>
        </TableBody>
    </Table>

    <Pagination v-if="reservations.total > 10" :links="reservations.links" class="mt-4" />
</template>
