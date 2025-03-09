<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';

defineProps({
    apis: Array,
});

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
    {
        title: 'APIs',
        href: '/api',
    },
];

import { useAlert } from "@/composables/useAlert";

const { success,displayErros } = useAlert();

const deleteApi = (id) => {
    if (confirm("Tem certeza que deseja deletar esta API?")) {
        router.delete(`/panel/api/delete/${id}`, {
            onSuccess: () => {
                success("API deletada com sucesso!");
            },
            onError: (errors) => {
                displayErros(errors)
            }
        });
    }
};
</script>

<template>  
    <Head title="Minhas APIs" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="m-12 mb-4 flex items-center justify-between">
            <h1 class="pr-2 text-2xl font-bold text-gray-700 dark:text-white">Minhas APIs</h1>
            <a href="/panel/api/create" class="rounded-md bg-indigo-600 px-3 py-2 text-white shadow hover:bg-indigo-700"> Criar Nova API </a>
        </div>

        <div class="m-3 grid gap-4 md:grid-cols-1">
            <div
                v-for="api in apis"
                :key="api.id"
                class="relative flex flex-col justify-between rounded-xl border border-gray-300 p-4 dark:border-gray-600"
            >
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <!-- Nome da API e Data -->
                    <div>
                        <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-300">{{ api.api_name }}</h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Criado em {{ new Date(api.created_at).toLocaleDateString() }}</p>
                    </div>

                    <!-- Botões -->
                    <div class="flex gap-2">
                        <a :href="`/panel/api/teste/${api.id}`" class="rounded-md bg-green-500 px-5 py-1 text-white shadow hover:bg-green-600">
                            Testar
                        </a>
                        <a :href="`/panel/api/show/${api.id}`" class="rounded-md bg-blue-500 px-5 py-1 text-white shadow hover:bg-blue-600">
                            Ver API
                        </a>
                        <button @click="deleteApi(api.id)" class="rounded-md bg-red-500 px-5 py-1 text-white shadow hover:bg-red-600">Deletar</button>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
