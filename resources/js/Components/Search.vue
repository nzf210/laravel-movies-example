<script setup lang="ts">
    import SearchIcon from "@/Icons/SearchIcon.vue";
    import { ref, watch } from "vue";

    const props = defineProps<{
        data: any
    }>();
    const search = ref<string>('');
    const isOpen = ref<boolean>(true);
    const searchResults = ref<Array<any>>(props.data);
    const loading = ref(false);

    const focusSearch = (event: KeyboardEvent) => {
    if (event.key === '191') {
        event.preventDefault();
    }
    };

    watch(search, (newVal) => {
        if (search.value === '') {
            searchResults.value = [];
        } else {
            loading.value = true;
            console.log('search', newVal);
            
            }}
        );
</script>

<template>
    <div class="flex flex-col md:flex-row items-center">
        <div class="md:ml-4 mt-3 md:mt-0">
            <input type="text" 
                class="bg-gray-700 rounded-full w-64 px-4 pl-8 py-1 focus:outline-none focus:shadow-outline" 
                placeholder="Search (Press '/' to focus)"
                v-model="search"
                @keydown.window="focusSearch"
                @focus="isOpen = true"
                @keydown="isOpen = true"
                @keydown.escape.window="isOpen = false"
                @keydown.shift.tab="isOpen = false"
                />
                <div class="absolute top-6">
                    <SearchIcon/>
                </div>
            </div>
            <div class="spinner top-0 right-0 mr-4 mt-3"></div>
        <div class="md:ml-4 mt-3 md:mt-0">
            <a href="#">
                <img src="https://movies.andredemos.ca/img/avatar.jpg" alt="avatar" class="rounded-full w-8 h-8">
            </a>
        </div>
        <div
            class="z-50 absolute bg-gray-800 text-sm rounded w-64 mt-4"
            v-if="search.length >= 2 && isOpen"
        >
                <ul v-if="searchResults.length > 0">
                        <!-- <li class="border-b border-gray-700">
                            <a
                                href="{{ route('movies.show', $result['id']) }}" class="block hover:bg-gray-700 px-3 py-3 flex items-center transition ease-in-out duration-150"
                                @if ($loop->last) @keydown.tab="isOpen = false" @endif
                            >
                            @if ($result['poster_path'])
                                <img src="https://image.tmdb.org/t/p/w92/{{ $result['poster_path'] }}" alt="poster" class="w-8">
                            @else
                                <img src="https://via.placeholder.com/50x75" alt="poster" class="w-8">
                            @endif
                            <span class="ml-4">{{ $result['title'] }}</span>
                        </a>
                        </li> -->
                </ul>
            
                <div v-else class="px-3 py-3">No results for "{{ search }}"</div>
            
        </div>
    </div>
</template>