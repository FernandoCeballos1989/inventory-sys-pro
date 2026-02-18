<script setup lang="ts">
import { computed } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import categories from '@/routes/categories';

type CategoryFormData = {
    id?: number;
    name: string;
    description: string | null;
};

const props = withDefaults(defineProps<{
    updating: boolean;
    category?: CategoryFormData | null;
}>(), {
    category: null,
});

const form = useForm({
    name: props.category?.name ?? '',
    description: props.category?.description ?? '',
});

const submitLabel = computed(() => (props.updating ? 'Update' : 'Create'));

const submit = () => {
    if (props.updating && props.category?.id) {
        form.put(categories.update(props.category.id).url);
        return;
    }

    form.post(categories.store().url);
};
</script>

<template>
    <form class="space-y-4 rounded-lg border p-4" @submit.prevent="submit">
        <div class="space-y-2">
            <Label for="name">Name</Label>
            <Input id="name" v-model="form.name" type="text" placeholder="Name of the category" />
            <p v-if="form.errors.name" class="text-sm text-red-500">{{ form.errors.name }}</p>
        </div>

        <div class="space-y-2">
            <Label for="description">Description</Label>
            <textarea
                id="description"
                v-model="form.description"
                rows="4"
                class="flex w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm shadow-xs outline-none ring-offset-background placeholder:text-muted-foreground focus-visible:ring-1 focus-visible:ring-ring"
                placeholder="Description of the category"
            />
            <p v-if="form.errors.description" class="text-sm text-red-500">{{ form.errors.description }}</p>
        </div>

        <div class="flex justify-end">
            <Button type="submit" :disabled="form.processing">
                {{ form.processing ? 'Saving...' : submitLabel }}
            </Button>
        </div>
    </form>
</template>
