<script setup lang="ts">
import { defineProps } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps({
    api: Object,
    data: Array,
    columns: Array
});

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleString();
};
</script>

<template>
    <AppLayout :breadcrumbs="[{ label: 'APIs', url: '/panel/api/list' }, { label: api.api_name }]">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            
            <!-- Detalhes da API -->
            <div class="relative p-4 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Detalhes da API</h1>
                <p class="mt-2 text-gray-600 dark:text-gray-300"><strong>Nome da API:</strong> {{ api.api_name }}</p>
                <p class="mt-2 text-gray-600 dark:text-gray-300"><strong>Criado por:</strong> {{ api.user.name }}</p>
            </div>

            <!-- Colunas da API -->
            <div class="relative p-4 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-2">Colunas</h3>
                <table class="w-full border-collapse border border-gray-300 dark:border-gray-600">
                    <thead class="bg-gray-100 dark:bg-gray-700">
                        <tr>
                            <th class="border border-gray-300 dark:border-gray-600 p-2">Nome</th>
                            <th class="border border-gray-300 dark:border-gray-600 p-2">Tipo</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(column, index) in columns" :key="index">
                            <td class="border border-gray-300 dark:border-gray-600 p-2">{{ column.name }}</td>
                            <td class="border border-gray-300 dark:border-gray-600 p-2">{{ column.type }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Dados Armazenados -->
            <div class="relative p-4 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-2">Dados Armazenados</h3>
                <table class="w-full border-collapse border border-gray-300 dark:border-gray-600">
                    <thead class="bg-gray-100 dark:bg-gray-700">
                        <tr>
                            <th class="border border-gray-300 dark:border-gray-600 p-2">ID</th>
                            <th class="border border-gray-300 dark:border-gray-600 p-2">Dados</th>
                            <th class="border border-gray-300 dark:border-gray-600 p-2">Criado em</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in data" :key="index">
                            <td class="border border-gray-300 dark:border-gray-600 p-2">{{ item.id }}</td>
                            <td class="border border-gray-300 dark:border-gray-600 p-2">{{ JSON.stringify(item.data) }}</td>
                            <td class="border border-gray-300 dark:border-gray-600 p-2">{{ formatDate(item.created_at) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Botão de edição -->
            <div class="flex justify-end">
                <a :href="`/panel/api/edit/${api.id}`" class="bg-blue-600 text-white py-2 px-4 rounded-md shadow hover:bg-blue-700">
                    Editar API
                </a>
            </div>
        </div>
    </AppLayout>
</template>
