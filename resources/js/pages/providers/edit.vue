<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { type BreadcrumbItem } from '@/types';
import providers from '@/routes/providers';
import ProviderForm from '@/components/Provider/Form.vue';

type Provider = {
    id: number;
    company_name: string;
    contact_name?: string | null;
    phone_number?: string | null;
    email?: string | null;
    address?: string | null;
};

const props = defineProps<{
    provider: Provider;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Providers',
        href: providers.index().url,
    },
];
</script>

<template>
    <Head title="Edit Provider" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <div class="w-1/3">
                <Link :href="providers.index().url">
                    <Button>Back</Button>
                </Link>
            </div>

            <div class="w-12/12 justify-center">
                <ProviderForm :updating="true" :provider="props.provider" />
            </div>
        </div>
    </AppLayout>
</template>
