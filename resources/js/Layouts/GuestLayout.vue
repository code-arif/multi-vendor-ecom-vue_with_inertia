<script setup>
import Topbar from '../Includes/User/Topbar.vue';
import Header from '../Includes/User/Header.vue';
import Footer from '../Includes/User/Footer.vue';
</script>
<template>
    <Topbar />
    <Header />
    <slot>

    </slot>
    <Footer />
</template>
