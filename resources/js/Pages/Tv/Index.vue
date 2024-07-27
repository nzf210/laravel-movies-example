<script setup lang="ts">
    import Header from "@/Components/Head.vue";
    import Layout from "@/Pages/Index.vue";
    import Card from "@/Components/Tv/Card.vue";
    const props = defineProps<{
            data: any;
        }>();    
</script>

<template>
    <Header title="Tv - The Movies"/>
    <Layout>
    <div class="container mx-auto px-4 pt-16">
        <div class="popular-tv">
            <h2 class="uppercase tracking-wider text-orange-500 text-lg font-semibold">Popular Shows</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-8">
                    <Card  v-for="tvshow in data.popularTv" :tv="tvshow" />
            </div>
        </div> <!-- end popular-tv -->

        <div class="top-rated-shows py-24">
            <h2 class="uppercase tracking-wider text-orange-500 text-lg font-semibold">Top Rated Shows</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-8">
                <Card  v-for="tvshow in data.topRatedTv" :tv="tvshow" />
        </div>
        </div> <!-- end top-rated-shows -->
    </div>
    </Layout>
</template>