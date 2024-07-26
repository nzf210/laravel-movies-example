<script setup lang="ts">
import SearchIcon from "@/Icons/SearchIcon.vue";

</script>

<template>
    <div class="flex flex-col md:flex-row items-center">
        <div class="md:ml-4 mt-3 md:mt-0">
            <input type="text" 
                class="bg-gray-700 rounded-full w-64 px-4 pl-8 py-1 focus:outline-none focus:shadow-outline" 
                placeholder="Search"/>
                <div class="absolute top-6">
                    <SearchIcon/>
                </div>
        </div>
        <div class="md:ml-4 mt-3 md:mt-0">
            <a href="#">
                <img src="https://movies.andredemos.ca/img/avatar.jpg" alt="avatar" class="rounded-full w-8 h-8">
            </a>
        </div>
    </div>
</template>