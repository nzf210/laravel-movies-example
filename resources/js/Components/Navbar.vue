<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import IconMovie from "@/Icons/Movie.vue";
import Search from "@/Components/Search.vue";
</script>

<template>
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