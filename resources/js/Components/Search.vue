<script setup lang="ts">
    import SearchIcon from "@/Icons/SearchIcon.vue";
    import { ref, watch } from "vue";
    import { Link, router } from "@inertiajs/vue3";

    const search = ref<string>('');
    const isOpen = ref<boolean>(true);
    const searchResults = ref<Array<any>>([]);
    const loading = ref(false);

    const focusSearch = (event: KeyboardEvent) => {
        if (event.key === '191') {
            event.preventDefault();
        }
    };

    const props = defineProps<{
        page:any;
    }>();
    
        let debounceTimeout: any = null;

        const searchs = (e: any) => {
            if (debounceTimeout) {
                clearTimeout(debounceTimeout);
            }
            debounceTimeout = setTimeout( async () => {
                loading.value = true;
                try {
                    await router.get(route('movie.search', { qry: e.target.value }), {},{
                    preserveState: true, preserveScroll: true,
                    onSuccess: (page:any) => {
                        searchResults.value =[...page.props.search.data];
                        loading.value = false;
                        console.log('search data', page.props.search.data );
                    }
                }); 
            } catch (error) {
                    searchResults.value = [];
                    loading.value = false;
                }
            }, 700);
            };
</script>

<template>
    <div class="flex flex-col md:flex-row items-center">
        <div class="md:ml-4 mt-3 md:mt-0">
            <input type="text" 
                @input="searchs"
                class="bg-gray-700 rounded-full w-64 px-4 pl-8 py-1 focus:outline-none focus:shadow-outline" 
                placeholder="Search (Press '/' to focus)"
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
            v-if="searchResults.length > 0"
        >
                <ul v-if="searchResults.length > 0 && isOpen">
                        <li class="border-b border-gray-700" v-for="$result in searchResults">
                            <!-- <a
                                href="{{ route('movies.show', $result['id']) }}" class="block hover:bg-gray-700 px-3 py-3 flex items-center transition ease-in-out duration-150"
                                @if ($loop->last) @keydown.tab="isOpen = false" @endif
                            >
                            @if ($result['poster_path'])
                                <img src="https://image.tmdb.org/t/p/w92/{{ $result['poster_path'] }}" alt="poster" class="w-8">
                            @else
                                <img src="https://via.placeholder.com/50x75" alt="poster" class="w-8">
                            @endif 
                        </a>
                        -->
                        <span class="ml-4">{{ $result.original_title || 'Uji Coba' }}</span>
                        </li>
                </ul>
            
                <div v-else class="py-3 pl-5">No results for "{{ search }}"</div>
            
        </div>
    </div>
</template>