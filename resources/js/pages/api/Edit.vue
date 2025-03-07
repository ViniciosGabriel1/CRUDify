<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import FormApi from '@/Components/FormApi.vue';

const props = defineProps({
    api: Object,
    columns: Array
});

const breadcrumbs = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Editar API', href: `/panel/api/edit/${props.api.id}` },
];

function handleSuccess() {
    alert('API editada com sucesso!');
}
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <FormApi :api="api" :columns="columns" :isEditing="true" @onSuccess="handleSuccess" />
    </AppLayout>
</template>
