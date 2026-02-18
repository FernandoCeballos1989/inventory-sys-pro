<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import CategoryForm from '@/components/Category/Form.vue';
import { type BreadcrumbItem } from '@/types';
import categories from '@/routes/categories';

type Category = {
    id: number;
    name: string;
    description: string | null;
};

const props = defineProps<{
    category: Category;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Categories',
        href: categories.index().url,
    },
];
</script>

<template>
    <Head title="Edit Category" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <div class="w-1/3">
                <Link :href="categories.index().url">
                    <Button>Back</Button>
                </Link>
            </div>

            <div class="w-10/12 justify-center">
                <CategoryForm :updating="true" :category="props.category" />
            </div>
        </div>
    </AppLayout>
</template>
