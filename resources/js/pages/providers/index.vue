<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button'
import { type BreadcrumbItem } from '@/types';
import providers from '@/routes/providers';
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
        title: 'providers',
        href: providers.index().url,
    },
];

type Provider = {
    id: number;
    company_name: string;
    contact_name: string | null;
    phone_number: string | null;
};

type ProviderPagination = {
    data: Provider[];
    current_page: number;
    last_page: number;
    prev_page_url: string | null;
    next_page_url: string | null;
};

const providerProps = defineProps<{
    providers: ProviderPagination;
}>();


const confirmDelete = (event: MouseEvent) => {
    if (!window.confirm('Are you sure you want to delete this Provider?')) {
        event.preventDefault();
    }
};

</script>

<template>

    <Head title="Providers" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <div class="w-1/3">
                <Link :href="providers.create().url">
                    <Button>Create Provider</Button>
                </Link>
            </div>
            <div class="w-10/12 justify-center">
                <Table>
                    <TableCaption>A list of your recent Providers.</TableCaption>
                    <TableHeader>
                        <TableRow>
                            <TableHead class="w-[100px]">
                                Name
                            </TableHead>
                            <TableHead>Contact</TableHead>
                            <TableHead>Phone</TableHead>
                            <TableHead class="text-right">
                                Actions
                            </TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="provider in providerProps.providers.data" :key="provider.id">
                            <TableCell class="font-medium">{{ provider.company_name }}</TableCell>
                            <TableCell>{{ provider.contact_name }}</TableCell>
                            <TableCell>{{ provider.phone_number }}</TableCell>
                            <TableCell class="text-right">
                                <Link :href="providers.edit(provider.id).url">
                                    <Button variant="outline">Edit</Button>
                                </Link>
                                <Link :href="providers.destroy(provider.id).url" method="delete" class="ml-2"
                                    @click="confirmDelete">
                                    <Button variant="destructive">Delete</Button>
                                </Link>
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
                <div class="mt-4 flex items-center justify-between">
                    <p class="text-sm text-muted-foreground">
                        Página {{ providerProps.providers.current_page }} de {{ providerProps.providers.last_page }}
                    </p>
                    <div class="flex gap-2">
                        <Link v-if="providerProps.providers.prev_page_url"
                            :href="providerProps.providers.prev_page_url">
                            <Button variant="outline">Previous</Button>
                        </Link>
                        <Link v-if="providerProps.providers.next_page_url"
                            :href="providerProps.providers.next_page_url">
                            <Button variant="outline">Next</Button>
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
