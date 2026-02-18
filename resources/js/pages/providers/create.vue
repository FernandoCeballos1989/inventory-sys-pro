<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import ProviderForm from '@/components/Provider/Form.vue';
import { type BreadcrumbItem } from '@/types';
import providers from '@/routes/providers';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Providers',
        href: providers.create().url,
    },
];
</script>

<template>

    <Head title="Create Provider" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <div class="w-1/3">
                <Link :href="providers.index().url">
                    <Button>Back</Button>
                </Link>
            </div>

            <div class="w-10/12 justify-center">
                <ProviderForm :updating="false" />
            </div>
        </div>
    </AppLayout>
</template>
