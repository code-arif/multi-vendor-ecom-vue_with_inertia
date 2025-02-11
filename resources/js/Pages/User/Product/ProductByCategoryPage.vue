<script setup>
import { Head } from '@inertiajs/vue3';
import GuestLayout from '../../../Layouts/GuestLayout.vue';
import ProductByCategoryComponent from '../../../Components/User/Product/ProductByCategoryComponent.vue';
</script>

<template>

    <Head>
        <title>Mini Shop || Products By Category</title>
    </Head>
    <GuestLayout>
        <ProductByCategoryComponent />
    </GuestLayout>
</template>

<style scoped></style>
