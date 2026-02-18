<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button'
import { type BreadcrumbItem } from '@/types';
import clients from '@/routes/clients';
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
        title: 'Clients',
        href: clients.index().url,
    },
];

type Client = {
    id: number;
    name: string;
    email: string;
    phone: string;
};

type ClientPagination = {
    data: Client[];
    current_page: number;
    last_page: number;
    prev_page_url: string | null;
    next_page_url: string | null;
};

const clientProps = defineProps<{
    clients: ClientPagination;
}>();


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
                <Link :href="clients.create().url">
                    <Button>Create Client</Button>
                </Link>
            </div>
            <div class="w-10/12 justify-center">
                <Table>
                    <TableCaption>A list of your recent Clients.</TableCaption>
                    <TableHeader>
                        <TableRow>
                            <TableHead class="w-[100px]">
                                    Name    
                            </TableHead>
                            <TableHead>Email</TableHead>
                            <TableHead>Phone</TableHead>
                            <TableHead class="text-right">
                                Actions
                            </TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="client in clientProps.clients.data" :key="client.id">
                            <TableCell class="font-medium">{{ client.name }}</TableCell>
                            <TableCell>{{ client.email }}</TableCell>
                            <TableCell>{{ client.phone }}</TableCell>
                            <TableCell class="text-right">
                                <Link :href="clients.edit(client.id).url">
                                    <Button variant="outline">Edit</Button>
                                </Link>
                                <Link :href="clients.destroy(client.id).url" method="delete" class="ml-2" @click="confirmDelete">
                                    <Button variant="destructive">Delete</Button>
                                </Link>
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
                <div class="mt-4 flex items-center justify-between">
                    <p class="text-sm text-muted-foreground">
                        Página {{ clientProps.clients.current_page }} de {{ clientProps.clients.last_page }}
                    </p>
                    <div class="flex gap-2">
                        <Link v-if="clientProps.clients.prev_page_url" :href="clientProps.clients.prev_page_url">
                            <Button variant="outline">Anterior</Button>
                        </Link>
                        <Link v-if="clientProps.clients.next_page_url" :href="clientProps.clients.next_page_url">
                            <Button variant="outline">Siguiente</Button>
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
