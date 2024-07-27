<script setup lang="ts">
    import { ref, defineProps, onUnmounted, onMounted } from 'vue';
    import Header from "@/Components/Head.vue";
    import Layout from "@/Pages/Index.vue";
    import { Link, router } from "@inertiajs/vue3";
    import { useIntersectionObserver } from '@vueuse/core';

    const props = defineProps<{
        data: any;
    }>();

    const popularActors = ref(props.data.popularActors);
    const nextPage = ref(props.data.next);
    
    const el = ref<HTMLElement | null>(null);
    const noMorePosts = ref(false);
    const isLoading = ref(false);
    const initialRender = ref(true);

    const loadMoreActors = async () => {
        if (isLoading.value || !nextPage.value || noMorePosts.value) return;
        try {    
            isLoading.value = true;
            router.get(route('actors.page', { page: (nextPage.value) }),{},
                        {preserveState: true, preserveScroll: true,
                            onSuccess: (page:any) => {
                                popularActors.value.push(...page.props.data.popularActors);
                                nextPage.value = page.props.data.next;
                                if (!page.props.data.next) {
                                    noMorePosts.value = true; 
                                    stop();
                                }
                            },
                            onFinish: () => {
                                isLoading.value = false;
                            }
                        });
            
        } catch (error) {
            console.error('Error fetching more actors:', error);
        }
    };
    

const { stop } = useIntersectionObserver(
    el,
    async ([{ isIntersecting }]) => {
        if (initialRender.value) {
            initialRender.value = false; 
            return;
            }
        if (isIntersecting) await loadMoreActors();
    },
    {threshold: 0.5}
);

const checkScrollTop = () => {
  if (window.scrollY === 0) {
    router.replace(route('actors.index'));    
  }
};

onMounted(() => {
  window.addEventListener('scroll', checkScrollTop);
});

onUnmounted(() => {
  window.removeEventListener('scroll', checkScrollTop);
});
</script>

<template>
    <Header title="Actors - The Movies"/>
    <Layout>
    <div class="container mx-auto px-4 py-16">
        <div class="popular-actors">
            <h2 class="uppercase tracking-wider text-orange-500 text-lg font-semibold">Popular Actors</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-8">
                    <div class="actor mt-8" v-if="popularActors.length > 0" v-for="actor in popularActors">
                        <Link :href="route('actors.show', actor.id)">
                            <img :src="actor.profile_path" :alt="actor.name" class="hover:opacity-75 transition ease-in-out duration-150">
                        </Link>
                        <div class="mt-2">
                            <Link :href="route('actors.show', actor.id)" class="text-lg hover:text-gray-300">{{ actor.name }}</Link>
                            <div class="text-sm truncate text-gray-400">{{ actor.known_for }}</div>
                        </div>
                    </div>
                    <div ref="el" class="infinite-scroll-sizer"></div>
            </div>
        </div> <!-- end popular-actors -->

        <button v-if="isLoading" type="button" class="text-white flex p-2 rounded-md" disabled>
             <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
            Loading ...
        </button>
    </div>
    </Layout>
</template>


