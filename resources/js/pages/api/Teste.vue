<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { useForm } from '@inertiajs/vue3'; // Importa useForm do Inertia
import { defineProps, ref } from 'vue';

const method = ref('GET'); // Estado para armazenar o método selecionado
const url = '/panel/api/teste_store'; // URL do backend


const props = defineProps({
    api: Object,
    columns: Array,
});
const form = useForm({
    columns: props.columns.map(col => ({ name: col.name, value: '' })), // Inicializa as colunas com valores vazios
});


const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
    {
        title: 'Teste',
        href: 'panel/api/teste',
    },
];

const submitForm = () => {
    // Verifique se as colunas estão preenchidas corretamente antes de enviar
    const columnsData = form.columns.map((column) => ({
        name: column.name,
        value: column.value, // Valor preenchido pelo usuário
    }));
    console.log("🚀 ~ columnsData ~ columnsData:", columnsData)

    // Atualize a propriedade 'columns' do formulário com os valores preenchidos
    form.columns = columnsData;

    // Envia os dados para o backend
    form[method.value.toLowerCase()](url, {

        onSuccess: () => {
            console.log('Requisição bem-sucedida!');
        },
        onError: (errors) => {
            console.error('Erros retornados do backend:', errors);
        },
    });
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <!-- <div class="relative p-4 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
            <label for="api_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nome da API</label>
            <input v-model="form.api_name" type="text" id="api_name" name="api_name"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:text-white p-2"
                placeholder="Digite o nome da API">
        </div> -->
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <form @submit.prevent="submitForm">
                <!-- Método HTTP -->
                <div class="w-full rounded-xl border border-sidebar-border/70 p-2 dark:border-sidebar-border">
                    <label for="method" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Método</label>
                    <select
                        id="method"
                        v-model="method"
                        class="rounded-md border border-gray-300 p-3 shadow-sm focus:ring focus:ring-blue-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:focus:ring-gray-500"
                    >
                        <option value="GET">GET</option>
                        <option value="POST">POST</option>
                        <option value="PUT">PUT</option>
                        <option value="PATCH">PATCH</option>
                        <option value="DELETE">DELETE</option>
                    </select>
                </div>

                <!-- Corpo da Requisição -->
                <!-- <div>
                    <label for="body" class="mt-7 block text-sm font-medium text-gray-700 dark:text-gray-300">Corpo da requisição</label>
                    <textarea
                        id="body"
                        v-model="form.body"
                        class="w-full rounded-lg border p-3 shadow-sm focus:ring focus:ring-red-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:focus:ring-gray-500"
                        spellcheck="false"
                        rows="4"
                        value="{
                        
                        
}"
                        placeholder="Digite os dados (se necessário)..."
                    ></textarea>
                </div> -->

                <div class="relative rounded-xl border border-sidebar-border/70 p-4 dark:border-sidebar-border">
                    <h3 class="mb-2 text-lg font-semibold text-gray-700 dark:text-gray-300">Colunas</h3>
                    <table class="w-full border-collapse border border-gray-300 dark:border-gray-600">
                        <thead class="bg-gray-100 dark:bg-gray-700">
                            <tr>
                                <th class="border border-gray-300 p-2 dark:border-gray-600">Nome</th>
                                <th class="border border-gray-300 p-2 dark:border-gray-600">Tipo</th>
                                <th class="border border-gray-300 p-2 dark:border-gray-600">Envio</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(column, index) in columns" :key="index">
                                <td class="border border-gray-300 p-2 dark:border-gray-600">{{ column.name }}</td>
                                <td class="border border-gray-300 p-2 dark:border-gray-600">{{ column.type }}</td>
                                <input
                                    class="w-full  border border-gray-300 p-3 shadow-sm focus:ring focus:ring-blue-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:focus:ring-gray-500"
                                    v-model="form.columns[index].value"
                                    type="text"
                                />
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Botão de Envio -->
                <div class="flex justify-end">
                    <button
                        type="submit"
                        class="rounded-md bg-green-600 px-4 py-2 text-white shadow-md transition-all duration-300 hover:bg-green-700 focus:outline-none focus:ring focus:ring-green-300 dark:focus:ring-green-500"
                    >
                        Enviar
                    </button>
                </div>
            </form>
            <!-- Detalhes da API -->
            <div class="relative rounded-xl border border-sidebar-border/70 p-4 dark:border-sidebar-border">
                <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Detalhes da API</h1>
                <p class="mt-2 text-gray-600 dark:text-gray-300"><strong>Nome da API:</strong> ShowTeste</p>
                <p class="mt-2 text-gray-600 dark:text-gray-300"><strong>Criado por:</strong> ShowTeste</p>
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
                        <tr>
                            <td class="border border-gray-300 p-2 dark:border-gray-600">ShowTeste</td>
                            <td class="border border-gray-300 p-2 dark:border-gray-600">ShowTeste</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AppLayout>
</template>
