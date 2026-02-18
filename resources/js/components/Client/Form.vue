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
    email?: string | null;
    phone?: string | null;
};

const props = withDefaults(defineProps<{
    updating: boolean;
    client?: ClientFormData | null;
}>(), {
    client: null,
});

const form = useForm({
    name: props.client?.name ?? '',
    email: props.client?.email ?? '',
    phone: props.client?.phone ?? '',
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
            <Label for="email">Email</Label>
            <Input id="email" v-model="form.email" type="email" placeholder="Email of the client" />
            <p v-if="form.errors.email" class="text-sm text-red-500">{{ form.errors.email }}</p>
        </div>

        <div class="space-y-2">
            <Label for="phone">Phone</Label>
            <Input id="phone" v-model="form.phone" type="text" placeholder="Phone of the client" />
            <p v-if="form.errors.phone" class="text-sm text-red-500">{{ form.errors.phone }}</p>
        </div>
        <div class="flex justify-end">
            <Button type="submit" :disabled="form.processing">
                {{ form.processing ? 'Saving...' : submitLabel }}
            </Button>
        </div>
    </form>
</template>
