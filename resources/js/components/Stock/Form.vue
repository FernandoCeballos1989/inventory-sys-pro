<script setup lang="ts">
import { computed } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import stocks from '@/routes/stocks';

type StockFormData = {
    id?: number;
    product_id: number | null;
    provider_id: number | null;
    client_id: number | null;
    type: string;
    quantity: number;
    price: number | null;
    remarks?: string | null;
};

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

const props = withDefaults(defineProps<{
    updating: boolean;
    products: ProductOption[];
    providers: ProviderOption[];
    clients: ClientOption[];
    stock?: StockFormData | null;
}>(), {
    stock: null,
    products: () => [],
    providers: () => [],
    clients: () => [],
});

const form = useForm({
    product_id: props.stock?.product_id ?? null,
    provider_id: props.stock?.provider_id ?? null,
    client_id: props.stock?.client_id ?? null,
    type: props.stock?.type ?? 'in',
    quantity: props.stock?.quantity ?? 1,
    price: props.stock?.price ?? 0,
    remarks: props.stock?.remarks ?? '',
});

const submitLabel = computed(() => (props.updating ? 'Update' : 'Create'));

const submit = () => {
    if (props.updating && props.stock?.id) {
        form.put(stocks.update(props.stock.id).url);
        return;
    }

    form.post(stocks.store().url);
};
</script>

<template>
    <form class="space-y-4 rounded-lg border p-4" @submit.prevent="submit">
        <div class="space-y-2">
            <Label for="product_id">Product</Label>
            <select
                id="product_id"
                v-model="form.product_id"
                class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-xs transition-[color,box-shadow] outline-none disabled:pointer-events-none disabled:cursor-not-allowed disabled:opacity-50"
            >
                <option :value="null" disabled>Select a product</option>
                <option
                    v-for="product in props.products"
                    :key="product.id"
                    :value="product.id"
                >
                    {{ product.name }}
                </option>
            </select>
            <p v-if="form.errors.product_id" class="text-sm text-red-500">{{ form.errors.product_id }}</p>
        </div>
        <div class="space-y-2">
            <Label for="provider_id">Provider</Label>
            <select
                id="provider_id"
                v-model="form.provider_id"
                class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-xs transition-[color,box-shadow] outline-none disabled:pointer-events-none disabled:cursor-not-allowed disabled:opacity-50"
            >
                <option :value="null">No provider</option>
                <option
                    v-for="provider in props.providers"
                    :key="provider.id"
                    :value="provider.id"
                >
                    {{ provider.company_name }}
                </option>
            </select>
            <p v-if="form.errors.provider_id" class="text-sm text-red-500">{{ form.errors.provider_id }}</p>
        </div>
        <div class="space-y-2">
            <Label for="client_id">Client</Label>
            <select
                id="client_id"
                v-model="form.client_id"
                class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-xs transition-[color,box-shadow] outline-none disabled:pointer-events-none disabled:cursor-not-allowed disabled:opacity-50"
            >
                <option :value="null">No client</option>
                <option
                    v-for="client in props.clients"
                    :key="client.id"
                    :value="client.id"
                >
                    {{ client.name }}
                </option>
            </select>
            <p v-if="form.errors.client_id" class="text-sm text-red-500">{{ form.errors.client_id }}</p>
        </div>
        <div class="space-y-2">
            <Label for="type">Type</Label>
            <select
                id="type"
                v-model="form.type"
                class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-xs transition-[color,box-shadow] outline-none disabled:pointer-events-none disabled:cursor-not-allowed disabled:opacity-50"
            >
                <option value="in">In</option>
                <option value="out">Out</option>
                <option value="adjustment">Adjustment</option>
            </select>
            <p v-if="form.errors.type" class="text-sm text-red-500">{{ form.errors.type }}</p>
        </div>
        <div class="space-y-2">
            <Label for="quantity">Quantity</Label>
            <Input id="quantity" v-model="form.quantity" type="number"
                min="1"
                placeholder="Quantity" />
            <p v-if="form.errors.quantity" class="text-sm text-red-500">{{ form.errors.quantity }}</p>
        </div>
        <div class="space-y-2">
            <Label for="price">Price</Label>
            <Input id="price" v-model="form.price" type="number"
                step="0.01"
                min="0"
                placeholder="Operation price" />
            <p v-if="form.errors.price" class="text-sm text-red-500">{{ form.errors.price }}</p>
        </div>
        <div class="space-y-2">
            <Label for="remarks">Remarks</Label>
            <Input id="remarks" v-model="form.remarks" type="text"
                placeholder="Optional remarks" />
            <p v-if="form.errors.remarks" class="text-sm text-red-500">{{ form.errors.remarks }}</p>
        </div>
        <div class="flex justify-end">
            <Button type="submit" :disabled="form.processing">
                {{ form.processing ? 'Saving...' : submitLabel }}
            </Button>
        </div>
    </form>
</template>
