<script setup lang="ts">
    import MovieCard from '@/Components/Movie/Card.vue';
    const props =defineProps<{
        data: any;
    }>();    
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
            <h2 class="uppercase tracking-wider text-orange-500 text-lg font-semibold">Now Playing</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-8">
                <MovieCard v-for="movie in props.data.nowPlayingMovies" :key="movie.id" :movie="movie"/>
            </div>
        </div>
    </div>
</template>