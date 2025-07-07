<script setup lang="ts">
import HeadingSmall from '@/components/HeadingSmall.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Combobox, ComboboxAnchor, ComboboxEmpty, ComboboxGroup, ComboboxInput, ComboboxItem, ComboboxList } from '@/components/ui/combobox';
import { TagsInput, TagsInputInput, TagsInputItem, TagsInputItemDelete, TagsInputItemText } from '@/components/ui/tags-input';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem, Role, User } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import { computed, PropType, ref } from 'vue';
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Utilizadores',
        href: '/administracao/utilizadores',
    },
    {
        title: 'Atribuir Perfis ao Utilizador',
        href: '/administracao/utilizadores',
    },
];

const props = defineProps({
    roles: {
        type: Array as PropType<Role[]>,
        required: true,
    },
    user: {
        type: Object as PropType<User>,
        required: true,
    },
});

const form = useForm({
    roles: [],
});
const modelValue = ref<Role[]>(props.user.roles ?? []);
console.log(props.user.roles);
const open = ref(false);
const searchTerm = ref('');

// Filter permissions: exclude already-selected, filter by name
const filteredRoles = computed(() => {
    return props.roles
        .filter((role) => !modelValue.value.some((item) => item.id === role.id))
        .filter((role) => !searchTerm.value || role.name.toLowerCase().includes(searchTerm.value.toLowerCase()));
});

const selectedIds = computed(() => modelValue.value.map((item) => item.id));

const submit = () => {
    form.roles = selectedIds.value;
    form.patch(route('user.roles.update', props.user), {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="Dashboard" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col space-y-6 px-4 py-6">
            <div class="flex flex-row items-end justify-between gap-4">
                <HeadingSmall title="Atribuir perfil ao utilizador" description="Escolher os perfis " />
            </div>
            <div class="flex h-full flex-1 flex-col rounded-xl p-6">
                <form @submit.prevent="submit" class="mx-auto flex w-full max-w-2xl flex-col gap-6">
                    <!-- Combobox always on top -->
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
                                        placeholder="Perfis..."
                                        class="h-auto w-full min-w-[200px] border-none p-0 focus-visible:ring-0"
                                        @keydown.enter.prevent
                                    />
                                </ComboboxInput>
                            </TagsInput>
                            <ComboboxList class="w-full">
                                <ComboboxEmpty>Nenhum perfil encontrado</ComboboxEmpty>
                                <ComboboxGroup>
                                    <ComboboxItem
                                        v-for="role in filteredRoles"
                                        :key="role.id"
                                        :value="role"
                                        @select.prevent="
                                            () => {
                                                if (!modelValue.some((item) => item.id === role.id)) {
                                                    modelValue.push(role);
                                                    searchTerm = '';
                                                }
                                                if (filteredRoles.length === 0) open = false;
                                            }
                                        "
                                    >
                                        {{ role.name }}
                                    </ComboboxItem>
                                </ComboboxGroup>
                            </ComboboxList>
                        </ComboboxAnchor>
                    </Combobox>

                    <!-- Error message and submit button below -->
                    <InputError class="mt-2" :message="form.errors.roles" />
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
