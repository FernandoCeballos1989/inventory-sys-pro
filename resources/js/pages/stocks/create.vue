<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import StockForm from '@/components/Stock/Form.vue';
import { type BreadcrumbItem } from '@/types';
import stocks from '@/routes/stocks';

type ProductOption = {
    id: number;
    name: string;
};

type ProviderOption = {
    id: number;
    company_name: string;
};

type ClientOption = {
    id: number;
    name: string;
};


const props = defineProps<{
    products: ProductOption[];
    providers: ProviderOption[];
    clients: ClientOption[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Stocks',
        href: stocks.create().url,
    },
];
</script>

<template>

    <Head title="Create Stock" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <div class="w-1/3">
                <Link :href="stocks.index().url">
                    <Button>Back</Button>
                </Link>
            </div>

            <div class="w-10/12 justify-center">
                <StockForm :updating="false" :products="props.products" :providers="props.providers"
                    :clients="props.clients" />
            </div>
        </div>
    </AppLayout>
</template>
