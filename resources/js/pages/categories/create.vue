<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button'
import { type BreadcrumbItem } from '@/types';
import categories from '@/routes/categories';


const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Categories',
        href: categories.create().url,
    },
];



</script>

<template>

    <Head title="Categories" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <div class="w-1/3">
                <Link :href="categories.index().url">
                    <Button>Back</Button>
                </Link>
            </div>
            <div class="w-10/12 justify-center">

            </div>
        </div>
    </AppLayout>
</template>
