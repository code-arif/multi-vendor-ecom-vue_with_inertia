<script setup>
import { Head } from '@inertiajs/vue3';
import GuestLayout from '../../../Layouts/GuestLayout.vue';
import CheckoutComponent from '../../../Components/User/Checkout/CheckoutComponent.vue';
</script>

<template>

    <Head>
        <title>Mini Shop || Checkout</title>
    </Head>
    <GuestLayout>
        <CheckoutComponent />
    </GuestLayout>
</template>

<style scoped></style>

