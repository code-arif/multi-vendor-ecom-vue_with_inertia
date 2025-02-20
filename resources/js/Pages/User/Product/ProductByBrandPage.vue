<script setup>
import { Head } from '@inertiajs/vue3';
import GuestLayout from '../../../Layouts/GuestLayout.vue';
import ProductByBrandComponent from '../../../Components/User/Product/ProductByBrandComponent.vue';
</script>

<template>

    <Head>
        <title>Mini Shop || Products By Brand</title>
    </Head>
    <GuestLayout>
        <ProductByBrandComponent />
    </GuestLayout>
</template>

<style scoped></style>
