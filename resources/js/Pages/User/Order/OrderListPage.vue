<script setup>
import { Head } from '@inertiajs/vue3';
import GuestLayout from '../../../Layouts/GuestLayout.vue';
import OrderListComponent from '../../../Components/User/Order/OrderListComponent.vue';
</script>

<template>

    <Head>
        <title>Mini Shop || My Order</title>
    </Head>
    <GuestLayout>
        <OrderListComponent />
    </GuestLayout>
</template>

<style scoped></style>
