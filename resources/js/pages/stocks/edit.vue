<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { type BreadcrumbItem } from '@/types';
import stocks from '@/routes/stocks';
import StockForm from '@/components/Stock/Form.vue';

type Product = {
    id: number;
    name: string;
};

type Provider = {
    id: number;
    company_name: string;
};

type Client = {
    id: number;
    name: string;
};

type Stock = {
    id: number;
    product_id: number | null;
    provider_id: number | null;
    client_id: number | null;
    type: string;
    quantity: number;
    price: number | null;
    remarks?: string | null;
};

const props = defineProps<{
    stock: Stock;
    products: Product[];
    providers: Provider[];
    clients: Client[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Stocks',
        href: stocks.index().url,
    },
];
</script>

<template>
    <Head title="Edit Stock" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <div class="w-1/3">
                <Link :href="stocks.index().url">
                    <Button>Back</Button>
                </Link>
            </div>

            <div class="w-12/12 justify-center">
                <StockForm
                    :updating="true"
                    :products="props.products"
                    :providers="props.providers"
                    :clients="props.clients"
                    :stock="props.stock"
                />
            </div>
        </div>
    </AppLayout>
</template>
