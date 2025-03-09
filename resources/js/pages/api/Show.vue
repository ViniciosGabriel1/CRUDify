<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { router } from '@inertiajs/vue3';
import { defineProps, ref } from 'vue';

const props = defineProps({
    api: Object,
    data: Object,
    columns: Array,
});

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
    {
        title: 'Show',
        href: 'panel/api/show',
    },
];

const paginatedData = ref(props.data);

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleString();
};

const fetchPage = (url) => {
    if (!url) return;

    router.get(
        url,
        {},
        {
            preserveState: true,
            onSuccess: (page) => {
                paginatedData.value = page.props.data;
            },
        },
    );
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <!-- Botão de edição -->
            <div class="flex justify-end">
                <a :href="`/panel/api/edit/${api.id}`" class="rounded-md bg-blue-600 px-4 py-2 text-white shadow hover:bg-blue-700"> Editar API </a>
            </div>

            <!-- Detalhes da API -->
            <div class="relative rounded-xl border border-sidebar-border/70 p-4 dark:border-sidebar-border">
                <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Detalhes da API</h1>
                <p class="mt-2 text-gray-600 dark:text-gray-300"><strong>Nome da API:</strong> {{ api.api_name }}</p>
                <p class="mt-2 text-gray-600 dark:text-gray-300"><strong>Criado por:</strong> {{ api.user.name }}</p>
            </div>

            <!-- Colunas da API -->
            <div class="relative rounded-xl border border-sidebar-border/70 p-4 dark:border-sidebar-border">
                <h3 class="mb-2 text-lg font-semibold text-gray-700 dark:text-gray-300">Colunas</h3>
                <table class="w-full border-collapse border border-gray-300 dark:border-gray-600">
                    <thead class="bg-gray-100 dark:bg-gray-700">
                        <tr>
                            <th class="border border-gray-300 p-2 dark:border-gray-600">Nome</th>
                            <th class="border border-gray-300 p-2 dark:border-gray-600">Tipo</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(column, index) in columns" :key="index">
                            <td class="border border-gray-300 p-2 dark:border-gray-600">{{ column.name }}</td>
                            <td class="border border-gray-300 p-2 dark:border-gray-600">{{ column.type }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Dados Armazenados -->
            <div class="relative rounded-xl border border-sidebar-border/70 p-4 dark:border-sidebar-border">
                <h3 class="mb-2 text-lg font-semibold text-gray-700 dark:text-gray-300">Dados Armazenados</h3>
                <table class="w-full border-collapse border border-gray-300 dark:border-gray-600">
                    <thead class="bg-gray-100 dark:bg-gray-700">
                        <tr>
                            <th class="border border-gray-300 p-2 dark:border-gray-600">ID</th>
                            <th class="border border-gray-300 p-2 dark:border-gray-600">Dados</th>
                            <th class="border border-gray-300 p-2 dark:border-gray-600">Criado em</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in data.data" :key="index">
                            <td class="border border-gray-300 p-2 dark:border-gray-600">{{ item.id }}</td>
                            <td class="border border-gray-300 p-2 dark:border-gray-600">{{ JSON.stringify(item.data) }}</td>
                            <td class="border border-gray-300 p-2 dark:border-gray-600">{{ formatDate(item.created_at) }}</td>
                        </tr>
                    </tbody>
                </table>

                <!-- Paginação -->
                <div class="mt-4 flex items-center justify-between">
                    <button
                        @click="fetchPage(paginatedData.prev_page_url)"
                        :disabled="!paginatedData.prev_page_url"
                        class="rounded-md bg-gray-500 px-4 py-2 text-white disabled:opacity-50"
                    >
                        Anterior
                    </button>

                    <span>Página {{ paginatedData.current_page }} de {{ paginatedData.last_page }}</span>

                    <button
                        @click="fetchPage(paginatedData.next_page_url)"
                        :disabled="!paginatedData.next_page_url"
                        class="rounded-md bg-gray-500 px-4 py-2 text-white disabled:opacity-50"
                    >
                        Próxima
                    </button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
