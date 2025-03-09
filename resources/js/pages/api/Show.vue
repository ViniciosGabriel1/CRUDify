<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <!-- Botão de edição -->
            <div class="flex justify-end">
                <a :href="`/panel/api/edit/${api.id}`" class="rounded-md bg-blue-600 px-4 py-2 text-white shadow hover:bg-blue-700">
                    Editar API
                </a>
            </div>

            <!-- Detalhes da API -->
            <div class="relative rounded-xl border border-sidebar-border/70 p-4 dark:border-sidebar-border">
                <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Detalhes da API</h1>
                <p class="mt-2 text-gray-600 dark:text-gray-300">
                    <strong>Nome da API:</strong> {{ api.api_name }}
                </p>
                <p class="mt-2 text-gray-600 dark:text-gray-300">
                    <strong>Criado por:</strong> {{ api.user.name }}
                </p>
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
                        <tr v-for="(column, index) in api.columns" :key="index">
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
                            <td class="border border-gray-300 p-2 dark:border-gray-600">
                                <ul>
                                    <li v-for="(value, key) in parseData(item.data)" :key="key">
                                        <strong>{{ key }}:</strong> {{ value }}
                                    </li>
                                </ul>
                            </td>
                            <td class="border border-gray-300 p-2 dark:border-gray-600">{{ formatDate(item.created_at) }}</td>
                        </tr>
                    </tbody>
                </table>

                <!-- Paginação -->
                <div class="mt-4 flex items-center justify-between">
                    <button
                        @click="fetchPage(data.prev_page_url)"
                        :disabled="!data.prev_page_url"
                        class="rounded-md bg-gray-500 px-4 py-2 text-white disabled:opacity-50"
                    >
                        Anterior
                    </button>

                    <span>Página {{ data.current_page }} de {{ data.last_page }}</span>

                    <button
                        @click="fetchPage(data.next_page_url)"
                        :disabled="!data.next_page_url"
                        class="rounded-md bg-gray-500 px-4 py-2 text-white disabled:opacity-50"
                    >
                        Próxima
                    </button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { router } from '@inertiajs/vue3';
import { defineProps } from 'vue';

interface BreadcrumbItem {
    title: string;
    href: string;
}

interface Api {
    id: number;
    api_name: string;
    user: {
        name: string;
    };
    columns: Array<{
        id: number;
        name: string;
        type: string;
    }>;
}

interface PaginatedData {
    current_page: number;
    data: Array<{
        id: number;
        data: string; // JSON string
        created_at: string;
    }>;
    first_page_url: string;
    from: number;
    last_page: number;
    last_page_url: string;
    next_page_url: string | null;
    path: string;
    per_page: number;
    prev_page_url: string | null;
    to: number;
    total: number;
}

// Define as propriedades recebidas do Inertia
const props = defineProps<{
    api: Api;
    data: PaginatedData;
}>();

console.log("🚀 ~ api:", props.api);
console.log("🚀 ~ data:", props.data);

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

// Decodifica o JSON
const parseData = (dataString: string): Record<string, any> => {
    try {
        return JSON.parse(dataString);
    } catch (error) {
        console.error("Erro ao decodificar JSON:", error);
        return {};
    }
};

// Formata a data
const formatDate = (dateString: string): string => {
    return new Date(dateString).toLocaleString();
};

// Navega para a página anterior ou próxima
const fetchPage = (url: string | null): void => {
    if (!url) return;

    router.visit(url, {
        preserveState: true,
        onSuccess: (page) => {
            console.log("Página carregada com sucesso:", page);
        },
    });
};
</script>