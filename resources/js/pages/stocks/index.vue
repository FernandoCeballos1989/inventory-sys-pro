<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button'
import { type BreadcrumbItem } from '@/types';
import stocks from '@/routes/stocks';
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
        title: 'stocks',
        href: stocks.index().url,
    },
];

type Stock = {
    id: number;
    type: string;
    quantity: number;
    price: string | number | null;
    product: {
        id: number;
        sku: string;
        name: string;
        current_stock: number;
    } | null;
    provider: {
        id: number;
        company_name: string;
    } | null;
    client: {
        id: number;
        name: string;
    } | null;
};

type StockPagination = {
    data: Stock[];
    current_page: number;
    last_page: number;
    prev_page_url: string | null;
    next_page_url: string | null;
};

const stockProps = defineProps<{
    stocks: StockPagination;
}>();


const confirmDelete = (event: MouseEvent) => {
    if (!window.confirm('Are you sure you want to delete this Stock?')) {
        event.preventDefault();
    }
};

</script>

<template>

    <Head title="Stocks" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <div class="w-1/3">
                <Link :href="stocks.create().url">
                    <Button>Create Stock</Button>
                </Link>
            </div>
            <div class="w-10/12 justify-center">
                <Table>
                    <TableCaption>A list of your recent Stocks.</TableCaption>
                    <TableHeader>
                        <TableRow>
                            <TableHead>Product</TableHead>
                            <TableHead>Type</TableHead>
                            <TableHead>Quantity</TableHead>
                            <TableHead>Price</TableHead>
                            <TableHead>Provider</TableHead>
                            <TableHead>Client</TableHead>
                            <TableHead class="text-right">
                                Actions
                            </TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="stock in stockProps.stocks.data" :key="stock.id">
                            <TableCell class="font-medium">
                                {{ stock.product ? `${stock.product.sku} - ${stock.product.name}` : 'N/A' }}
                            </TableCell>
                            <TableCell>{{ stock.type }}</TableCell>
                            <TableCell>{{ stock.quantity }}</TableCell>
                            <TableCell>{{ stock.price ?? 'N/A' }}</TableCell>
                            <TableCell>{{ stock.provider?.company_name ?? 'N/A' }}</TableCell>
                            <TableCell>{{ stock.client?.name ?? 'N/A' }}</TableCell>
                            <TableCell class="text-right">
                                <Link :href="stocks.edit(stock.id).url">
                                    <Button variant="outline">Edit</Button>
                                </Link>
                                <Link :href="stocks.destroy(stock.id).url" method="delete" class="ml-2"
                                    @click="confirmDelete">
                                    <Button variant="destructive">Delete</Button>
                                </Link>
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
                <div class="mt-4 flex items-center justify-between">
                    <p class="text-sm text-muted-foreground">
                        Página {{ stockProps.stocks.current_page }} de {{ stockProps.stocks.last_page }}
                    </p>
                    <div class="flex gap-2">
                        <Link v-if="stockProps.stocks.prev_page_url" :href="stockProps.stocks.prev_page_url">
                            <Button variant="outline">Previous</Button>
                        </Link>
                        <Link v-if="stockProps.stocks.next_page_url" :href="stockProps.stocks.next_page_url">
                            <Button variant="outline">Next</Button>
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
