<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import ProductForm from '@/components/Product/Form.vue';
import { type BreadcrumbItem } from '@/types';
import products from '@/routes/products';

type Category = {
    id: number;
    name: string;
};

const props = defineProps<{
    categories: Category[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Products',
        href: products.create().url,
    },
];
</script>

<template>

    <Head title="Create Product" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <div class="w-1/3">
                <Link :href="products.index().url">
                    <Button>Back</Button>
                </Link>
            </div>

            <div class="w-10/12 justify-center">
                <ProductForm :updating="false" :categories="props.categories" />
            </div>
        </div>
    </AppLayout>
</template>
