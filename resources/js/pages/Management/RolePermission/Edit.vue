<script setup lang="ts">
import HeadingSmall from '@/components/HeadingSmall.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Combobox, ComboboxAnchor, ComboboxEmpty, ComboboxGroup, ComboboxInput, ComboboxItem, ComboboxList } from '@/components/ui/combobox';
import { TagsInput, TagsInputInput, TagsInputItem, TagsInputItemDelete, TagsInputItemText } from '@/components/ui/tags-input';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem, Permission, Role } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import { computed, PropType, ref } from 'vue';

const props = defineProps({
    permissions: {
        type: Array as PropType<Permission[]>,
        required: true,
    },
    role: {
        type: Object as PropType<Role>,
        required: true,
    },
});

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Perfis',
        href: '/administracao/perfis',
    },
];

breadcrumbs.push({
    title: `Atribuir Permissões - ${props.role?.name}`,
    href: '/dashboard',
});
const form = useForm({
    permissions: [],
});
const modelValue = ref<Permission[]>(props.role.permissions ?? []);

const open = ref(false);
const searchTerm = ref('');

const filteredPermissions = computed(() => {
    return props.permissions
        .filter((perm) => !modelValue.value.some((item) => item.id === perm.id))
        .filter((perm) => !searchTerm.value || perm.name.toLowerCase().includes(searchTerm.value.toLowerCase()));
});

const selectedIds = computed(() => modelValue.value.map((item) => item.id));

const submit = () => {
    form.permissions = selectedIds.value;
    form.patch(route('roles.permissions.update', props.role), {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="Dashboard" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col space-y-6 px-4 py-6">
            <div class="flex flex-row items-end justify-between gap-4">
                <HeadingSmall title="Permissões" description="Atribuindo permissões ao perfil" />
            </div>
            <div class="flex h-full flex-1 flex-col rounded-xl p-6">
                <form @submit.prevent="submit" class="mx-auto flex w-full max-w-2xl flex-col gap-6">
                    <Combobox v-model="modelValue" v-model:open="open" :ignore-filter="true">
                        <ComboboxAnchor as-child>
                            <TagsInput v-model="modelValue" class="w-full gap-2 px-2" :display-value="(item) => item.slug">
                                <div class="flex flex-wrap items-center gap-2">
                                    <TagsInputItem v-for="item in modelValue" :key="item.id" :value="item">
                                        <TagsInputItemText>
                                            {{ item.name }}
                                        </TagsInputItemText>
                                        <TagsInputItemDelete />
                                    </TagsInputItem>
                                </div>
                                <ComboboxInput v-model="searchTerm" as-child>
                                    <TagsInputInput
                                        placeholder="Permissões..."
                                        class="h-auto w-full min-w-[200px] border-none p-0 focus-visible:ring-0"
                                        @keydown.enter.prevent
                                    />
                                </ComboboxInput>
                            </TagsInput>
                            <ComboboxList class="w-full">
                                <ComboboxEmpty>Nenhuma permissão encontrada.</ComboboxEmpty>
                                <ComboboxGroup>
                                    <ComboboxItem
                                        v-for="perm in filteredPermissions"
                                        :key="perm.id"
                                        :value="perm"
                                        @select.prevent="
                                            () => {
                                                if (!modelValue.some((item) => item.id === perm.id)) {
                                                    modelValue.push(perm);
                                                    searchTerm = '';
                                                }
                                                if (filteredPermissions.length === 0) open = false;
                                            }
                                        "
                                    >
                                        {{ perm.name }}
                                    </ComboboxItem>
                                </ComboboxGroup>
                            </ComboboxList>
                        </ComboboxAnchor>
                    </Combobox>

                    <InputError class="mt-2" :message="form.errors.permissions" />
                    <Button :disabled="form.processing">Guardar</Button>

                    <Transition
                        enter-active-class="transition ease-in-out"
                        enter-from-class="opacity-0"
                        leave-active-class="transition ease-in-out"
                        leave-to-class="opacity-0"
                    >
                        <p v-show="form.recentlySuccessful" class="text-sm text-neutral-600">Guardado.</p>
                    </Transition>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
