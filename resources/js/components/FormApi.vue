<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { defineProps, defineEmits } from 'vue';
import { ref } from 'vue';

import { useAlert } from "@/composables/useAlert";

const { success,error } = useAlert();


// Definição das props
const props = defineProps({
    api: Object, // Passado para edição
    columns: Array,
    isEditing: Boolean // Define se é edição ou criação
});

// Evento para feedback
const emit = defineEmits(['onSuccess']);

// Inicialização do formulário
const form = useForm({
    api_name: props.api?.api_name || '',
    columns: props.columns ? [...props.columns] : []
});

// Adicionar uma nova coluna
function addColumn() {
    form.columns.push({ name: '', type: 'string' });
}

// Remover coluna
function removeColumn(index: number) {
    form.columns.splice(index, 1);
}

// Submeter formulário
function submitForm() {
    const url = props.isEditing ? `/panel/api/update/${props.api.id}` : '/panel/api/store';
    const method = props.isEditing ? 'put' : 'post';

    let message = '';  // Inicializa a variável message

    if (method === 'put') {
        message = 'API Atualizada';
    } else {
        message = 'Nova API Criada';  // Caso o método não seja 'put', outra mensagem pode ser definida
    }

    form[method](url, {
        onSuccess: () => {
            
            // emit('onSuccess');
              success(message); // Emite evento para redirecionamento ou feedback
        },
        onError: (errors) => {
           error('Erro');
        },
    });
}
</script>

<template>
    <form @submit.prevent="submitForm" class="grid auto-rows-min gap-4 md:grid-cols-3">
        <div class="relative p-4 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
            <label for="api_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nome da API</label>
            <input v-model="form.api_name" type="text" id="api_name" name="api_name"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:text-white p-2"
                placeholder="Digite o nome da API">
        </div>
        <div class="relative p-4 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border flex items-end">
            <button type="button" @click="addColumn"
                class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md shadow hover:bg-indigo-700">
                Adicionar Coluna
            </button>
        </div>
        <div class="relative p-4 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border flex items-end">
            <button type="submit"
                class="w-full bg-green-600 text-white py-2 px-4 rounded-md shadow hover:bg-green-700">
                {{ isEditing ? 'Salvar Alterações' : 'Criar API' }}
            </button>
        </div>
    </form>

    <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border md:min-h-min p-4">
        <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-2">Colunas</h3>
        <table class="w-full border-collapse border border-gray-300 dark:border-gray-600">
            <thead>
                <tr>
                    <th class="border border-gray-300 dark:border-gray-600 p-2">Nome</th>
                    <th class="border border-gray-300 dark:border-gray-600 p-2">Tipo</th>
                    <th class="border border-gray-300 dark:border-gray-600 p-2">Ações</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(column, index) in form.columns" :key="index">
                    <td class="border border-gray-300 dark:border-gray-600 p-2">
                        <input v-model="column.name" type="text"
                            class="w-full p-1 rounded-md border-gray-300 dark:bg-gray-800 dark:text-white"
                            placeholder="Nome da coluna">
                    </td>
                    <td class="border border-gray-300 dark:border-gray-600 p-2">
                        <select v-model="column.type"
                            class="w-full p-1 rounded-md border-gray-300 dark:bg-gray-800 dark:text-white">
                            <option value="string">String</option>
                            <option value="integer">Integer</option>
                            <option value="text">Text</option>
                            <option value="boolean">Boolean</option>
                            <option value="timestamp">Timestamp</option>
                        </select>
                    </td>
                    <td class="border border-gray-300 dark:border-gray-600 p-2 text-center">
                        <button type="button" @click="removeColumn(index)"
                            class="text-red-600 hover:text-red-800">Remover</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
