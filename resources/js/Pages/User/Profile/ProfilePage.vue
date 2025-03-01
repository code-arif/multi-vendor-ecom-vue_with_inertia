<script setup>
import { Head } from '@inertiajs/vue3';
import GuestLayout from '../../../Layouts/GuestLayout.vue';
import ProfileComponent from '../../../Components/User/Profile/ProfileComponent.vue';
</script>

<template>

    <Head>
        <title>Mini Shop || Profile</title>
    </Head>
    <GuestLayout>
        <ProfileComponent />
    </GuestLayout>
</template>

<style scoped></style>
