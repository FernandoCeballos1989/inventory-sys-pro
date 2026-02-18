<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { type BreadcrumbItem } from '@/types';
import products from '@/routes/products';
import ProductForm from '@/components/Product/Form.vue';

type Product = {
    id: number;
    sku: string;
    name: string;
    selling_price: number;
    current_stock: number;
    min_stock: number;
    warehouse_location?: string | null;
};

const props = defineProps<{
    product: Product;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Products',
        href: products.index().url,
    },
];
</script>

<template>
    <Head title="Edit Product" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <div class="w-1/3">
                <Link :href="products.index().url">
                    <Button>Back</Button>
                </Link>
            </div>

            <div class="w-12/12 justify-center">
                <ProductForm
                    :updating="true"
                    :product="{ ...props.product, warehouse_location: props.product.warehouse_location ?? '' }"
                />
            </div>
        </div>
    </AppLayout>
</template>
