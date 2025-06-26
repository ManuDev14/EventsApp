<script setup lang="ts">
import { Select, SelectContent, SelectGroup, SelectItem, SelectLabel, SelectTrigger, SelectValue } from '@/components/ui/select';

interface StatusOption {
    value: string;
    label: string;
}

const model = defineModel<string>('modelValue');

defineProps<{
    options: StatusOption[];
}>();
</script>

<template>
    <div class="max-w-xs">
        <label for="status-select" class="mb-1 block font-medium">Filter by Status</label>
        <Select v-model="model" id="status-select">
            <SelectTrigger class="w-full">
                <SelectValue placeholder="Select status..." />
            </SelectTrigger>
            <SelectContent>
                <SelectGroup>
                    <SelectLabel>Status</SelectLabel>
                    <SelectItem v-for="option in options" :key="option.value" :value="option.value">
                        {{ option.label }}
                    </SelectItem>
                </SelectGroup>
            </SelectContent>
        </Select>
    </div>
</template>
