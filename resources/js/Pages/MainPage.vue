<script setup lang="ts">
    import MovieCard from '@/Components/Movie/Card.vue';
    import { onMounted, onUnmounted, ref } from 'vue';
    import { router } from '@inertiajs/vue3';
    import { useIntersectionObserver } from '@vueuse/core';

    const props =defineProps<{
        data: any;
    }>();    
    const nowPlayingMovies = ref(props.data.nowPlayingMovies);

    const el = ref<HTMLElement | null>(null);
    const noMorePosts = ref(false);
    const isLoading = ref(false);
    const initialRender = ref(true);
    const nextpage = ref(2);

    const loadMoreMovie = async () => {
        if (isLoading.value || noMorePosts.value) return;
        try {    
            isLoading.value = true;
            router.get(route('movies.load', { page: (nextpage.value) }),{},
                        {preserveState: true, preserveScroll: true,
                            onSuccess: (page:any) => {
                                nowPlayingMovies.value.push(...page.props.data.nowPlayingMovies);
                                if (nextpage.value >= 5001) {
                                    noMorePosts.value = true; 
                                    stop();
                                }
                            },
                            onFinish: () => {
                                isLoading.value = false;
                                nextpage.value = nextpage.value + 1;
                            }
                        });
        } catch (error) {
            console.error('Error fetching more movies:', error);
        }
    };

    const { stop } = useIntersectionObserver(
        el,
        async ([{ isIntersecting }]) => {
            if (initialRender.value) {
                initialRender.value = false; 
                return;
                }
            if (isIntersecting) await loadMoreMovie();
        },
        {threshold: 0.5}
    );

    const checkScrollTop = () => {
        if (window.scrollY === 0) { router.replace(route('movies.index')); }
    };

    onMounted(() => { window.addEventListener('scroll', checkScrollTop); });
    onUnmounted(() => { window.removeEventListener('scroll', checkScrollTop); });
</script>

<template>
    <div class="container mx-auto px-4 pt-6">
        <div class="popular-movies">
            <h2 class="uppercase tracking-wider text-orange-500 text-lg font-semibold">Popular Movies</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-8">
                <MovieCard v-for="movie in props.data.popularMovies" :key="movie.id" :movie="movie"/>
            </div>
        </div>
        <div class="now-playing">
            <h2 class="uppercase tracking-wider text-orange-500 text-lg font-semibold pt-4 pb-2 border-b-2 border-slate-600">Now Playing</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-8">
                <MovieCard v-for="movie in nowPlayingMovies" :key="movie.id" :movie="movie"/>
            </div>
            <div ref="el" class="infinite-scroll-sizer"></div>
        </div>
        <div v-if="isLoading" class="text-white flex p-2 rounded-md" disabled>
            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
                Loading ...
        </div>
    </div>
</template>