<script setup lang="ts">
    import { onBeforeUnmount, onMounted, ref } from "vue";
    import SpinIcon from "@/Icons/SpinIcon.vue";
    import SearchIcon from "@/Icons/SearchIcon.vue";
    import { Link, router } from "@inertiajs/vue3";

    const search = ref<string>('');
    const isOpen = ref<boolean>(false);
    const searchResults = ref<Array<any>>([]);
    const loading = ref(false);
    const searchInput = ref<HTMLInputElement | null>(null);
    const dropdown = ref<HTMLElement | null>(null);

    const focusSearch = (event: KeyboardEvent) => {
        if (event.key === '/') {
            event.preventDefault();
            searchInput.value?.focus();
        }
    };
    
        let debounceTimeout: any = null;

        const searchs = (e: any) => {
            if (debounceTimeout) { clearTimeout(debounceTimeout); }
            debounceTimeout = setTimeout( async () => {
                loading.value = true;
                try {
                    search.value = e.target.value;
                    if(e.target.value.length > 2){
                        await router.get(route('movie.search', { qry: e.target.value }), {},{
                            preserveState: true, preserveScroll: true,
                            onSuccess: (page:any) => {
                                searchResults.value =[...page.props.search.data];
                                if(page.props.search.data && page.props.search.data.length > 0){
                                    isOpen.value = true;
                                }
                                loading.value = false;
                            }
                        }); 
                    }
            } catch (error) {
                    searchResults.value = [];
                    loading.value = false;
                }
            }, 700);
            };
    
        onMounted(() => { 
            window.addEventListener('keydown', focusSearch);
            document.addEventListener('click', handleClickOutside);
        });
        onBeforeUnmount(() => { 
            window.removeEventListener('keydown', focusSearch);
            document.removeEventListener('click', handleClickOutside);
        });

        const handleClickOutside = (event: MouseEvent) => {
            if (dropdown.value && !dropdown.value.contains(event.target as Node)) {
                isOpen.value = false;
            }
        };
</script>

<template>
    <div class="flex flex-col md:flex-row items-center relative">
        <div class="md:ml-4 mt-3 md:mt-0" ref="dropdown">
                <input type="text"
                ref="searchInput"
                @input="searchs"
                class="bg-gray-700 rounded-full w-64 px-4 pl-8 py-1 focus:outline-none focus:shadow-outline" 
                placeholder="Search (Press '/' to focus)"
                @focus="isOpen = true"
                @keydown="isOpen = true"
                @keydown.escape.window="isOpen = false"
                @keydown.shift.tab="isOpen = false"
                />
                <div class="absolute md:top-0 top-3">
                    <SearchIcon/>
                </div>
                <div v-if="loading && search.length > 2" class="absolute top-5 md:top-2 -right-1 md:right-16">
                    <SpinIcon/>
                </div>
                <div v-if="searchResults && searchResults.length > 0 && isOpen && search.length > 2" class="z-50 absolute bg-gray-800 text-sm rounded w-64 mt-1 mx-auto align-bottom">
                    <ul>
                            <li class="border-b border-gray-700 flex flex-1" v-for="($result , index) in searchResults"
                                @keydown="($event) => { if ($event.key === 'Tab') {if(index === searchResults.length - 1){ isOpen = false } } }">
                                <Link  
                                    :href="route('movies.show', $result.id)" class=" hover:bg-gray-700 px-3 py-3 flex items-center transition ease-in-out duration-150">
                                    <img v-if="$result.poster_path" :src="'https://image.tmdb.org/t/p/w92/'+$result.poster_path" alt="poster" class="w-8">
                                    <img v-else src="https://via.placeholder.com/50x75" alt="poster" class="w-8">
                                    <span class="ml-4 w-52">
                                        {{ $result.original_title || 'No Title'}}
                                    </span>
                                </Link>
                            </li>
                    </ul>
                </div>
                <div v-if="searchResults && search.length > 2 && !loading && isOpen" class="py-3 pl-5 absolute w-64">No results for "{{ search }}"</div>
        </div>
            <div class="spinner top-0 right-0 mr-4 mt-3"></div>
        <div class="md:ml-4 mt-3 md:mt-0">
            <a href="#">
                <img src="https://movies.andredemos.ca/img/avatar.jpg" alt="avatar" class="rounded-full w-8 h-8">
            </a>
        </div>
    </div>
</template>