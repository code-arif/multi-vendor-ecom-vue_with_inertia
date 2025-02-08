<script setup>
import { Head } from '@inertiajs/vue3';
import GuestLayout from '../../../Layouts/GuestLayout.vue';
import ProductsComponent from '../../../Components/User/Product/ProductsComponent.vue';
</script>

<template>

    <Head>
        <title>Mini Shop || Products</title>
    </Head>
    <GuestLayout>
        <ProductsComponent />
    </GuestLayout>
</template>

<style scoped></style>
