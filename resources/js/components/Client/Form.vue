<script setup lang="ts">
import { computed } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import clients from '@/routes/clients';

type ClientFormData = {
    id?: number;
    name: string;
    fiscal_code?: string;
    email?: string | null;
    phone?: string | null;
    send_address?: string | null;
};

const props = withDefaults(defineProps<{
    updating: boolean;
    client?: ClientFormData | null;
}>(), {
    client: null,
});

const form = useForm({
    name: props.client?.name ?? '',
    fiscal_code: props.client?.fiscal_code ?? '',
    email: props.client?.email ?? '',
    phone: props.client?.phone ?? '',
    send_address: props.client?.send_address ?? '',
});

const submitLabel = computed(() => (props.updating ? 'Update' : 'Create'));

const submit = () => {
    if (props.updating && props.client?.id) {
        form.put(clients.update(props.client.id).url);
        return;
    }

    form.post(clients.store().url);
};
</script>

<template>
    <form class="space-y-4 rounded-lg border p-4" @submit.prevent="submit">
        <div class="space-y-2">
            <Label for="name">Name</Label>
            <Input id="name" v-model="form.name" type="text" placeholder="Name of the client" />
            <p v-if="form.errors.name" class="text-sm text-red-500">{{ form.errors.name }}</p>
        </div>
        <div class="space-y-2">
            <Label for="fiscal_code">Fiscal Code</Label>
            <Input id="fiscal_code" v-model="form.fiscal_code" type="text" placeholder="Fiscal code of the client" />
            <p v-if="form.errors.fiscal_code" class="text-sm text-red-500">{{ form.errors.fiscal_code }}</p>
        </div>
        <div class="space-y-2">
            <Label for="email">Email</Label>
            <Input id="email" v-model="form.email" type="email" placeholder="Email of the client" />
            <p v-if="form.errors.email" class="text-sm text-red-500">{{ form.errors.email }}</p>
        </div>

        <div class="space-y-2">
            <Label for="phone">Phone</Label>
            <Input id="phone" v-model="form.phone" type="text" placeholder="Phone of the client" />
            <p v-if="form.errors.phone" class="text-sm text-red-500">{{ form.errors.phone }}</p>
        </div>
        <div class="space-y-2">
            <Label for="address">Address</Label>
            <Input id="address" v-model="form.send_address" type="text" placeholder="Address of the client" />
            <p v-if="form.errors.send_address" class="text-sm text-red-500">{{ form.errors.send_address }}</p>
        </div>
        <div class="flex justify-end">
            <Button type="submit" :disabled="form.processing">
                {{ form.processing ? 'Saving...' : submitLabel }}
            </Button>
        </div>
    </form>
</template>
