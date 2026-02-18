<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button'
import { type BreadcrumbItem } from '@/types';
import products from '@/routes/products';
import {
    Table,
    TableBody,
    TableCaption,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table'


const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'products',
        href: products.index().url,
    },
];

type Product = {
    id: number;
    sku: string;
    name: string;
    current_stock: string;
};

type ProductPagination = {
    data: Product[];
    current_page: number;
    last_page: number;
    prev_page_url: string | null;
    next_page_url: string | null;
};

const productProps = defineProps<{
    products: ProductPagination;
}>();


const confirmDelete = (event: MouseEvent) => {
    if (!window.confirm('Are you sure you want to delete this Product?')) {
        event.preventDefault();
    }
};

</script>

<template>

    <Head title="Products" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <div class="w-1/3">
                <Link :href="products.create().url">
                    <Button>Create Product</Button>
                </Link>
            </div>
            <div class="w-10/12 justify-center">
                <Table>
                    <TableCaption>A list of your recent Products.</TableCaption>
                    <TableHeader>
                        <TableRow>
                            <TableHead class="w-[100px]">
                                SKU
                            </TableHead>
                            <TableHead>Name</TableHead>
                            <TableHead>Current Stock</TableHead>
                            <TableHead class="text-right">
                                Actions
                            </TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="product in productProps.products.data" :key="product.id">
                            <TableCell class="font-medium">{{ product.sku }}</TableCell>
                            <TableCell>{{ product.name }}</TableCell>
                            <TableCell>{{ product.current_stock }}</TableCell>
                            <TableCell class="text-right">
                                <Link :href="products.edit(product.id).url">
                                    <Button variant="outline">Edit</Button>
                                </Link>
                                <Link :href="products.destroy(product.id).url" method="delete" class="ml-2"
                                    @click="confirmDelete">
                                    <Button variant="destructive">Delete</Button>
                                </Link>
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
                <div class="mt-4 flex items-center justify-between">
                    <p class="text-sm text-muted-foreground">
                        Página {{ productProps.products.current_page }} de {{ productProps.products.last_page }}
                    </p>
                    <div class="flex gap-2">
                        <Link v-if="productProps.products.prev_page_url"
                            :href="productProps.products.prev_page_url">
                            <Button variant="outline">Previous</Button>
                        </Link>
                        <Link v-if="productProps.products.next_page_url"
                            :href="productProps.products.next_page_url">
                            <Button variant="outline">Next</Button>
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
