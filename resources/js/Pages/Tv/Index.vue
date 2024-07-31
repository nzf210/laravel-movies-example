<script setup lang="ts">
    import Header from "@/Components/Head.vue";
    import Layout from "@/Pages/Index.vue";
    import Card from "@/Components/Tv/Card.vue";
    import { useIntersectionObserver } from '@vueuse/core';
    import { onMounted, onUnmounted, ref } from "vue";
    import { router } from '@inertiajs/vue3';

    const props =defineProps<{
        data: any;
    }>();    
    const topRatedTv = ref(props.data.topRatedTv);

    const el = ref<HTMLElement | null>(null);
    const noMorePosts = ref(false);
    const isLoading = ref(false);
    const initialRender = ref(true);
    const nextpage = ref(2);

    const loadMoreMovie = async () => {
        if (isLoading.value || noMorePosts.value) return;
        try {    
            isLoading.value = true;
            router.get(route('tv.load', { page: (nextpage.value) }),{},
                        {preserveState: true, preserveScroll: true,
                            onSuccess: (page:any) => {
                                topRatedTv.value.push(...page.props.data.topRatedTv);                                
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
        if (window.scrollY === 0) { router.replace(route('tv.index')); }
    };

    onMounted(() => { window.addEventListener('scroll', checkScrollTop); });
    onUnmounted(() => { window.removeEventListener('scroll', checkScrollTop); });  
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
                <Card  v-for="tvshow in topRatedTv" :tv="tvshow" />
            </div>
            <div ref="el" class="infinite-scroll-sizer"></div>
        </div> <!-- end top-rated-shows -->
        <div v-if="isLoading" class="text-white flex p-2 rounded-md" disabled>
            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
                Loading ...
        </div>
    </div>
    </Layout>
</template>