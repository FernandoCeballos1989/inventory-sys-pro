<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { dashboard } from '@/routes';
import { Badge } from '@/components/ui/badge';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import { ArrowDownRight, ArrowUpRight, Boxes, PackageSearch, TrendingUp, Warehouse } from 'lucide-vue-next';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
];

type Kpis = {
    total_products: number;
    total_providers: number;
    total_clients: number;
    low_stock_count: number;
    inventory_units: number;
    inventory_value: number;
    incoming_units_month: number;
    outgoing_units_month: number;
    period: {
        start: string;
        end: string;
    };
};

type ProductMovement = {
    id: number | null;
    sku: string;
    name: string;
    current_stock: number;
    min_stock: number;
    moved_units: number;
};

type LowStockProduct = {
    id: number;
    sku: string;
    name: string;
    current_stock: number;
    min_stock: number;
};

const props = defineProps<{
    kpis: Kpis;
    top_moved_products: ProductMovement[];
    low_stock_products: LowStockProduct[];
}>();

const formatCurrency = (value: number) =>
    new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
        maximumFractionDigits: 2,
    }).format(value);

const formatNumber = (value: number) => new Intl.NumberFormat('en-US').format(value);

const netFlowMonth = props.kpis.incoming_units_month - props.kpis.outgoing_units_month;
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                <Card>
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardDescription>Total inventory value</CardDescription>
                        <Warehouse class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <CardTitle class="text-2xl">{{ formatCurrency(props.kpis.inventory_value) }}</CardTitle>
                        <p class="text-xs text-muted-foreground">
                            {{ formatNumber(props.kpis.inventory_units) }} units in stock
                        </p>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardDescription>Products under minimum</CardDescription>
                        <PackageSearch class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <CardTitle class="text-2xl">{{ formatNumber(props.kpis.low_stock_count) }}</CardTitle>
                        <p class="text-xs text-muted-foreground">
                            From {{ formatNumber(props.kpis.total_products) }} active products
                        </p>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardDescription>Monthly inbound / outbound</CardDescription>
                        <TrendingUp class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <CardTitle class="text-2xl">
                            {{ formatNumber(props.kpis.incoming_units_month) }} / {{ formatNumber(props.kpis.outgoing_units_month) }}
                        </CardTitle>
                        <p class="text-xs text-muted-foreground">
                            Period {{ props.kpis.period.start }} to {{ props.kpis.period.end }}
                        </p>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardDescription>Net flow this month</CardDescription>
                        <Boxes class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <CardTitle class="text-2xl">{{ formatNumber(netFlowMonth) }} units</CardTitle>
                        <div class="mt-1 flex items-center gap-2">
                            <Badge :variant="netFlowMonth >= 0 ? 'default' : 'destructive'">
                                {{ netFlowMonth >= 0 ? 'Positive' : 'Negative' }}
                            </Badge>
                            <span class="text-xs text-muted-foreground">
                                {{ formatNumber(props.kpis.total_providers) }} providers / {{ formatNumber(props.kpis.total_clients) }} clients
                            </span>
                        </div>
                    </CardContent>
                </Card>
            </div>

            <div class="grid gap-4 lg:grid-cols-2">
                <Card>
                    <CardHeader>
                        <CardTitle>Top moved products</CardTitle>
                        <CardDescription>Products with highest cumulative stock movements</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <Table>
                            <TableHeader>
                                <TableRow>
                                    <TableHead>SKU</TableHead>
                                    <TableHead>Product</TableHead>
                                    <TableHead>Moved units</TableHead>
                                    <TableHead>Current stock</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                <TableRow v-for="product in props.top_moved_products" :key="product.sku">
                                    <TableCell class="font-medium">{{ product.sku }}</TableCell>
                                    <TableCell>{{ product.name }}</TableCell>
                                    <TableCell>{{ formatNumber(product.moved_units) }}</TableCell>
                                    <TableCell>
                                        <div class="flex items-center gap-2">
                                            <span>{{ formatNumber(product.current_stock) }}</span>
                                            <ArrowDownRight
                                                v-if="product.current_stock <= product.min_stock"
                                                class="h-3.5 w-3.5 text-destructive"
                                            />
                                            <ArrowUpRight v-else class="h-3.5 w-3.5 text-emerald-600" />
                                        </div>
                                    </TableCell>
                                </TableRow>
                                <TableRow v-if="!props.top_moved_products.length">
                                    <TableCell colspan="4" class="text-center text-muted-foreground">
                                        No stock movement data available.
                                    </TableCell>
                                </TableRow>
                            </TableBody>
                        </Table>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader>
                        <CardTitle>Low stock alert</CardTitle>
                        <CardDescription>Products at or below minimum stock</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <Table>
                            <TableHeader>
                                <TableRow>
                                    <TableHead>SKU</TableHead>
                                    <TableHead>Product</TableHead>
                                    <TableHead class="text-right">Stock</TableHead>
                                    <TableHead class="text-right">Minimum</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                <TableRow v-for="product in props.low_stock_products" :key="product.id">
                                    <TableCell class="font-medium">{{ product.sku }}</TableCell>
                                    <TableCell>{{ product.name }}</TableCell>
                                    <TableCell class="text-right">
                                        <Badge variant="destructive">{{ formatNumber(product.current_stock) }}</Badge>
                                    </TableCell>
                                    <TableCell class="text-right">{{ formatNumber(product.min_stock) }}</TableCell>
                                </TableRow>
                                <TableRow v-if="!props.low_stock_products.length">
                                    <TableCell colspan="4" class="text-center text-muted-foreground">
                                        Great, no low stock products right now.
                                    </TableCell>
                                </TableRow>
                            </TableBody>
                        </Table>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
 </template>
