<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import FormApi from '@/Components/FormApi.vue';
import { ref } from 'vue';


const props = defineProps({
    api: Object,
    columns: Array
});

const breadcrumbs = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Editar API', href: `/panel/api/edit/${props.api.id}` },
];

// function handleSuccess() {
//     alert('API editada com sucesso!');
// }

import { useAlert } from "@/composables/useAlert";

const { success,error } = useAlert();

const message = ref(''); // Mensagem dinâmica

// Acessando o método `success` do composable


// const handleSuccess = () => {
// console.log("🚀 ~ message:", message.value)

//   // Passando a mensagem do input para o `success`
//   success(message.value || "Registro Atualizado!");
// };

</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <FormApi :api="api" :columns="columns" :isEditing="true" @onSuccess="handleSuccess" />
    </AppLayout>
</template>
