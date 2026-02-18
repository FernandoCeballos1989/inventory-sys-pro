<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button'
import { type BreadcrumbItem } from '@/types';
import categories from '@/routes/categories';
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
        title: 'Categories',
        href: categories.index().url,
    },
];

type Category = {
    id: number;
    name: string;
    description: string | null;
    created_at: string;
};

type CategoryPagination = {
    data: Category[];
    current_page: number;
    last_page: number;
    prev_page_url: string | null;
    next_page_url: string | null;
};

const categoryProps = defineProps<{
    categories: CategoryPagination;
}>();

const formatCreatedAt = (value: string) => {
    const date = new Date(value);

    if (Number.isNaN(date.getTime())) {
        return value;
    }

    const day = String(date.getDate()).padStart(2, '0');
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const year = String(date.getFullYear());

    return `${day}-${month}-${year}`;
};

const confirmDelete = (event: MouseEvent) => {
    if (!window.confirm('Are you sure you want to delete this category?')) {
        event.preventDefault();
    }
};

</script>

<template>

    <Head title="Categories" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <div class="w-1/3">
                <Link :href="categories.create().url">
                    <Button>Create Category</Button>
                </Link>
            </div>
            <div class="w-10/12 justify-center">
                <Table>
                    <TableCaption>A list of your recent Categories.</TableCaption>
                    <TableHeader>
                        <TableRow>
                            <TableHead class="w-[100px]">
                                    Category
                            </TableHead>
                            <TableHead>Name</TableHead>
                            <TableHead>Created at</TableHead>
                            <TableHead class="text-right">
                                Actions
                            </TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="category in categoryProps.categories.data" :key="category.id">
                            <TableCell class="font-medium">{{ category.id }}</TableCell>
                            <TableCell>{{ category.name }}</TableCell>
                            <TableCell>{{ formatCreatedAt(category.created_at) }}</TableCell>
                            <TableCell class="text-right">
                                <Link :href="categories.edit(category.id).url">
                                    <Button variant="outline">Edit</Button>
                                </Link>
                                <Link :href="categories.destroy(category.id).url" method="delete" class="ml-2" @click="confirmDelete">
                                    <Button variant="destructive">Delete</Button>
                                </Link>
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
                <div class="mt-4 flex items-center justify-between">
                    <p class="text-sm text-muted-foreground">
                        Página {{ categoryProps.categories.current_page }} de {{ categoryProps.categories.last_page }}
                    </p>
                    <div class="flex gap-2">
                        <Link v-if="categoryProps.categories.prev_page_url" :href="categoryProps.categories.prev_page_url">
                            <Button variant="outline">Previous</Button>
                        </Link>
                        <Link v-if="categoryProps.categories.next_page_url" :href="categoryProps.categories.next_page_url">
                            <Button variant="outline">Next</Button>
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
