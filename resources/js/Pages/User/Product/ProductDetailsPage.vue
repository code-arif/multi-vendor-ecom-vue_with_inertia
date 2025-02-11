<script setup>
import { Head } from '@inertiajs/vue3';
import GuestLayout from '../../../Layouts/GuestLayout.vue';
import ProductDetailsComponent from '../../../Components/User/Product/ProductDetailsComponent.vue';
import FeaturedProductComponent from '../../../Components/User/Home/FeaturedProductComponent.vue';
</script>

<template>

    <Head>
        <title>Mini Shop || Product Details</title>
    </Head>
    <GuestLayout>
        <ProductDetailsComponent />
        <!-- <FeaturedProductComponent /> -->
    </GuestLayout>
</template>

<style scoped></style>
