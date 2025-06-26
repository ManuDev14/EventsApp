<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import { toast } from 'vue-sonner';

import { Button } from '@/components/ui/button';
import { Popover, PopoverContent, PopoverTrigger } from '@/components/ui/popover';

const open = ref(false);
const form = useForm({
    event_name: '',
});

function submit() {
    if (!form.event_name.trim()) {
        toast.error('Please enter an event name');
        return;
    }

    form.delete(route('reservations.cancel-by-event'), {
        onSuccess: () => {
            toast.success(`Event "${form.event_name}" cancelled successfully.`);
            form.reset();
            open.value = false;
        },
        onError: () => toast.error('Failed to cancel event.'),
    });
}
</script>

<template>
    <Popover v-model:open="open" class="inline-block">
        <PopoverTrigger as-child>
            <Button variant="destructive">Delete by Name</Button>
        </PopoverTrigger>

        <PopoverContent class="w-64 p-4">
            <label for="event_name" class="mb-2 block text-sm font-medium text-foreground">Event Name</label>
            <input
                id="event_name"
                type="text"
                v-model="form.event_name"
                placeholder="Enter event name"
                class="w-full rounded border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-primary focus:outline-none"
            />
            <Button variant="destructive" class="mt-4 w-full" :disabled="!form.event_name.trim()" @click="submit"> Cancel Event </Button>
        </PopoverContent>
    </Popover>
</template>
