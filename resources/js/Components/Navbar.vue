<script setup lang="ts">
    import { Link, router } from '@inertiajs/vue3';
    import IconMovie from "@/Icons/Movie.vue";
    import Search from "@/Components/Search.vue";
    import Spiner from "@/Components/Spiner.vue";
    import { ref } from 'vue';

    let loadingTimeout: ReturnType<typeof setTimeout> | null = null;
    let currentNavigationId: number | null = null;
    const loading_store = ref(false);

    router.on("start", () => {
    currentNavigationId = Date.now();
    const navigationId = currentNavigationId;
    loadingTimeout = setTimeout(() => {
                if (currentNavigationId === navigationId) {
                loading_store.value = true;
            }
        }, 300);
    });

    router.on("finish", () => {
    if (loadingTimeout) {
            clearTimeout(loadingTimeout);
            loadingTimeout = null;
        }
        loading_store.value = false;
        currentNavigationId = null;
    });
</script>

<template>
    <Spiner v-if="loading_store"/>
    <nav class="border-b border-gray-800">
        <div class="container mx-auto px-4 flex flex-col md:flex-row items-center justify-between py-6">
            <ul class="flex flex-col md:flex-row items-center">
                <li class="flex item-center">
                    <Link :href="route('movies.index')"><IconMovie/></Link>
                </li>
                <li class="md:ml-16 mt-3 md:mt-0">
                    <Link :href="route('movies.index')" class="hover:text-gray-300">Movies</Link>
                </li>
                <li class="md:ml-6 mt-3 md:mt-0">
                    <Link :href="route('tv.index')" class="hover:text-gray-300">TV Shows</Link>
                </li>
                <li class="md:ml-6 mt-3 md:mt-0">
                    <Link :href="route('actors.index')" class="hover:text-gray-300">Actors</Link>
                </li>
            </ul>
            <Search/>
        </div>
    </nav>
</template>