<script setup>
import { Head } from '@inertiajs/vue3';
import GuestLayout from '../../../Layouts/GuestLayout.vue';
import ShippingAddressComponent from '../../../Components/User/Profile/ShippingAddressComponent.vue';
</script>

<template>

    <Head>
        <title>Mini Shop || Shipping Address</title>
    </Head>
    <GuestLayout>
        <ShippingAddressComponent />
    </GuestLayout>
</template>

<style scoped></style>
