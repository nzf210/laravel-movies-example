<script setup lang="ts">
    import Navbar from '@/Components/Navbar.vue';
    import Footer from '@/Components/Footer.vue';
</script>

<template> 
        <body class="h-screen">
            <Navbar/>
            <div class="min-h-screen">
                <slot/>
            </div>
            <Footer/>
        </body>
</template>