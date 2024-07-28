<script setup lang="ts">
import Header from "@/Components/Head.vue";
import Layout from "@/Pages/Index.vue";
import MainPage from "./MainPage.vue";

const props = defineProps<{
    canLogin?: boolean;
    canRegister?: boolean;
    laravelVersion: string;
    phpVersion: string;
    data: any;
}>();

function handleImageError() {
    document.getElementById('screenshot-container')?.classList.add('!hidden');
    document.getElementById('docs-card')?.classList.add('!row-span-1');
    document.getElementById('docs-card-content')?.classList.add('!flex-row');
    document.getElementById('background')?.classList.add('!hidden');
}
</script>

<template>
    <Header title="Welcome - The Movies"/>
    <Layout>
        <MainPage :data="data"/>
    </Layout>
</template>
