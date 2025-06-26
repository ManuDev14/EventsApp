<script setup lang="ts">
import { Dialog, DialogContent, DialogFooter, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog';
import { toast } from 'vue-sonner';

import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';

import { Event } from '@/models/Event';
import { Room } from '@/models/Room';
import { useForm } from '@inertiajs/vue3';
import dayjs from 'dayjs';
import { ref } from 'vue';

const props = defineProps<{
    events: Event[];
    rooms: Room[];
}>();

const open = ref(false);

const form = useForm({
    event_id: '',
    room_id: '',
    event_date: dayjs().format('YYYY-MM-DD'),
    start_time: '',
    end_time: '',
});

function submit() {
    form.post(route('reservations.store'), {
        preserveScroll: true,
        onSuccess: () => {
            toast.success('Reservation created successfully!', {
                position: 'top-right',
            });
            open.value = false;
            form.reset();
        },
        onError: (errors) => {
            console.log(`Error ${errors}`);

            Object.values(errors).forEach((message) => {
                toast.error(String(message), {
                    position: 'top-right',
                });
            });
        },
    });
}
</script>

<template>
    <Dialog v-model:open="open">
        <DialogTrigger as-child>
            <Button>Create Reservation</Button>
        </DialogTrigger>

        <DialogContent class="sm:max-w-md">
            <DialogHeader>
                <DialogTitle>New Reservation</DialogTitle>
            </DialogHeader>

            <form @submit.prevent="submit" class="space-y-4">
                <div>
                    <label class="mb-1 block">Event</label>
                    <Select v-model="form.event_id">
                        <SelectTrigger class="w-full">
                            <SelectValue placeholder="Select an event" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem v-for="event in props.events" :key="event.id" :value="event.id.toString()">
                                {{ event.name }}
                            </SelectItem>
                        </SelectContent>
                    </Select>
                    <p v-if="form.errors.event_id" class="text-sm text-red-500">{{ form.errors.event_id }}</p>
                </div>

                <div>
                    <label class="mb-1 block">Room</label>
                    <Select v-model="form.room_id">
                        <SelectTrigger class="w-full">
                            <SelectValue placeholder="Select a room" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem v-for="room in props.rooms" :key="room.id" :value="room.id.toString()">
                                {{ room.name }}
                            </SelectItem>
                        </SelectContent>
                    </Select>
                    <p v-if="form.errors.room_id" class="text-sm text-red-500">{{ form.errors.room_id }}</p>
                </div>

                <div>
                    <label class="mb-1 block">Event Date</label>
                    <Input type="date" v-model="form.event_date" />
                    <p v-if="form.errors.event_date" class="text-sm text-red-500">{{ form.errors.event_date }}</p>
                </div>

                <div class="flex gap-4">
                    <div class="flex-1">
                        <label class="mb-1 block">Start Time</label>
                        <Input type="time" v-model="form.start_time" />
                        <p v-if="form.errors.start_time" class="text-sm text-red-500">{{ form.errors.start_time }}</p>
                    </div>

                    <div class="flex-1">
                        <label class="mb-1 block">End Time</label>
                        <Input type="time" v-model="form.end_time" />
                        <p v-if="form.errors.end_time" class="text-sm text-red-500">{{ form.errors.end_time }}</p>
                    </div>
                </div>

                <DialogFooter>
                    <Button type="submit" :disabled="form.processing">Save</Button>
                </DialogFooter>
            </form>
        </DialogContent>
    </Dialog>
</template>
