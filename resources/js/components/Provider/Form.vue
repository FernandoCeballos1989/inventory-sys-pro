<script setup lang="ts">
import { computed } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import providers from '@/routes/providers';

type ProviderFormData = {
    id?: number;
    company_name: string;
    contact_name?: string | null;
    phone_number?: string | null;
    email?: string | null;
    address?: string | null;
};

const props = withDefaults(defineProps<{
    updating: boolean;
    provider?: ProviderFormData | null;
}>(), {
    provider: null,
});

const form = useForm({
    company_name: props.provider?.company_name ?? '',
    contact_name: props.provider?.contact_name ?? '',
    phone_number: props.provider?.phone_number ?? '',
    email: props.provider?.email ?? '',
    address: props.provider?.address ?? '',
});

const submitLabel = computed(() => (props.updating ? 'Update' : 'Create'));

const submit = () => {
    if (props.updating && props.provider?.id) {
        form.put(providers.update(props.provider.id).url);
        return;
    }

    form.post(providers.store().url);
};
</script>

<template>
    <form class="space-y-4 rounded-lg border p-4" @submit.prevent="submit">
        <div class="space-y-2">
            <Label for="company_name">Company Name</Label>
            <Input id="company_name" v-model="form.company_name" type="text"
                placeholder="Company name of the provider" />
            <p v-if="form.errors.company_name" class="text-sm text-red-500">{{ form.errors.company_name }}</p>
        </div>
        <div class="space-y-2">
            <Label for="contact_name">Contact Name</Label>
            <Input id="contact_name" v-model="form.contact_name" type="text"
                placeholder="Contact name of the provider" />
            <p v-if="form.errors.contact_name" class="text-sm text-red-500">{{ form.errors.contact_name }}</p>
        </div>
        <div class="space-y-2">
            <Label for="phone_number">Phone Number</Label>
            <Input id="phone_number" v-model="form.phone_number" type="text"
                placeholder="Phone number of the provider" />
            <p v-if="form.errors.phone_number" class="text-sm text-red-500">{{ form.errors.phone_number }}</p>
        </div>
        <div class="space-y-2">
            <Label for="email">Email</Label>
            <Input id="email" v-model="form.email" type="email" placeholder="Email of the provider" />
            <p v-if="form.errors.email" class="text-sm text-red-500">{{ form.errors.email }}</p>
        </div>
        <div class="space-y-2">
            <Label for="address">Address</Label>
            <Input id="address" v-model="form.address" type="text" placeholder="Address of the provider" />
            <p v-if="form.errors.address" class="text-sm text-red-500">{{ form.errors.address }}</p>
        </div>
        <div class="flex justify-end">
            <Button type="submit" :disabled="form.processing">
                {{ form.processing ? 'Saving...' : submitLabel }}
            </Button>
        </div>
    </form>
</template>
