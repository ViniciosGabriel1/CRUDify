<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import FormApi from '@/Components/FormApi.vue';
import { ref } from 'vue';

const breadcrumbs = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Create', href: 'panel/api/create' },
];

import { useAlert } from "@/composables/useAlert";

const { success,errors } = useAlert();

// const handleSuccess = () => {

//   // Passando a mensagem do input para o `success`
//   success("Api Criada!");
// };


</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <FormApi :isEditing="false" @onSuccess="handleSuccess" />
    </AppLayout>
</template>
