<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

defineProps<{
    links: Array<{
        url: string | null;
        label: string;
        active: boolean;
    }>;
}>();
</script>

<template>
    <div class="mt-4 flex flex-wrap items-center justify-center gap-2">
        <template v-for="(link, index) in links" :key="index">
            <Link
                v-if="link.url"
                :href="link.url"
                class="rounded px-3 py-1 text-sm transition-colors"
                :class="{
                    'bg-primary text-primary-foreground': link.active,
                    'text-muted-foreground hover:underline': !link.active,
                }"
                >{{ link.label }}</Link
            >
            <span v-else class="px-3 py-1 text-sm text-muted-foreground" v-html="link.label" />
        </template>
    </div>
</template>
