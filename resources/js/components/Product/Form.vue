<script setup lang="ts">
import { computed } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import products from '@/routes/products';

type ProductFormData = {
    id?: number;
    category_id: number | null;
    sku: string;
    name: string;
    selling_price: number;
    current_stock: number;
    min_stock: number;
    warehouse_location?: string;
};

type CategoryOption = {
    id: number;
    name: string;
};

const props = withDefaults(defineProps<{
    updating: boolean;
    categories: CategoryOption[];
    product?: ProductFormData | null;
}>(), {
    product: null,
    categories: () => [],
});

const form = useForm({
    category_id: props.product?.category_id ?? null,
    sku: props.product?.sku ?? '',
    name: props.product?.name ?? '',
    selling_price: props.product?.selling_price ?? 0,
    current_stock: props.product?.current_stock ?? 0,
    min_stock: props.product?.min_stock ?? 0,
    warehouse_location: props.product?.warehouse_location ?? '',
});

const submitLabel = computed(() => (props.updating ? 'Update' : 'Create'));

const submit = () => {
    if (props.updating && props.product?.id) {
        form.put(products.update(props.product.id).url);
        return;
    }

    form.post(products.store().url);
};
</script>

<template>
    <form class="space-y-4 rounded-lg border p-4" @submit.prevent="submit">
        <div class="space-y-2">
            <Label for="category_id">Category</Label>
            <select
                id="category_id"
                v-model="form.category_id"
                class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-xs transition-[color,box-shadow] outline-none disabled:pointer-events-none disabled:cursor-not-allowed disabled:opacity-50"
            >
                <option :value="null" disabled>Select a category</option>
                <option
                    v-for="category in props.categories"
                    :key="category.id"
                    :value="category.id"
                >
                    {{ category.name }}
                </option>
            </select>
            <p v-if="form.errors.category_id" class="text-sm text-red-500">{{ form.errors.category_id }}</p>
        </div>
        <div class="space-y-2">
            <Label for="sku">SKU</Label>
            <Input id="sku" v-model="form.sku" type="text"
                placeholder="SKU of the product" />
            <p v-if="form.errors.sku" class="text-sm text-red-500">{{ form.errors.sku }}</p>
        </div>
        <div class="space-y-2">
            <Label for="name">Name</Label>
            <Input id="name" v-model="form.name" type="text"
                placeholder="Name of the product" />
            <p v-if="form.errors.name" class="text-sm text-red-500">{{ form.errors.name }}</p>
        </div>
        <div class="space-y-2">
            <Label for="selling_price">Selling Price</Label>
            <Input id="selling_price" v-model="form.selling_price" type="number"
                step="0.01"
                min="0"
                placeholder="Selling price of the product" />
            <p v-if="form.errors.selling_price" class="text-sm text-red-500">{{ form.errors.selling_price }}</p>
        </div>
        <div class="space-y-2">
            <Label for="current_stock">Current Stock</Label>
            <Input id="current_stock" v-model="form.current_stock" type="number"
                placeholder="Current stock of the product" />
            <p v-if="form.errors.current_stock" class="text-sm text-red-500">{{ form.errors.current_stock }}</p>
        </div>
        <div class="space-y-2">
            <Label for="min_stock">Minimum Stock</Label>
            <Input id="min_stock" v-model="form.min_stock" type="number"
                placeholder="Minimum stock of the product" />
            <p v-if="form.errors.min_stock" class="text-sm text-red-500">{{ form.errors.min_stock }}</p>
        </div>
        <div class="space-y-2">
            <Label for="warehouse_location">Warehouse Location</Label>
            <Input id="warehouse_location" v-model="form.warehouse_location" type="text"
                placeholder="Warehouse location of the product" />
            <p v-if="form.errors.warehouse_location" class="text-sm text-red-500">{{ form.errors.warehouse_location }}</p>
        </div>
        <div class="flex justify-end">
            <Button type="submit" :disabled="form.processing">
                {{ form.processing ? 'Saving...' : submitLabel }}
            </Button>
        </div>
    </form>
</template>
