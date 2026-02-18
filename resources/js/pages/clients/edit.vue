<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import ClientForm from '@/components/Client/Form.vue';
import { type BreadcrumbItem } from '@/types';
import clients from '@/routes/clients';

type Client = {
    id: number;
    name: string;
    email?: string | null;
    phone?: string | null;
};

const props = defineProps<{
    client: Client;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Clients',
        href: clients.index().url,
    },
];
</script>

<template>
    <Head title="Edit Client" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <div class="w-1/3">
                <Link :href="clients.index().url">
                    <Button>Back</Button>
                </Link>
            </div>

            <div class="w-10/12 justify-center">
                <ClientForm :updating="true" :client="props.client" />
            </div>
        </div>
    </AppLayout>
</template>
