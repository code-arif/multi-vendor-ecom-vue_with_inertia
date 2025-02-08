<script setup>
import { Head } from '@inertiajs/vue3';
import GuestLayout from '../../../Layouts/GuestLayout.vue';
import CarouselComponent from '../../../Components/User/Home/CarouselComponent.vue';
import FeatureComponent from '../../../Components/User/Home/FeatureComponent.vue';
import CategoryComponent from '../../../Components/User/Home/CategoryComponent.vue';
import FeaturedProductComponent from '../../../Components/User/Home/FeaturedProductComponent.vue';
import OfferComponent from '../../../Components/User/Home/OfferComponent.vue';
import RecentProductComponent from '../../../Components/User/Home/RecentProductComponent.vue';
// import BrandComponent from '../../../Components/User/Home/BrandComponent.vue';
</script>

<template>
    <Head>
        <title> Mini Shop || Home</title>
    </Head>

    <GuestLayout>
        <CarouselComponent />
        <FeatureComponent />
        <CategoryComponent />
        <FeaturedProductComponent />
        <OfferComponent />
        <RecentProductComponent />
        <!-- <BrandComponent /> -->
    </GuestLayout>
</template>

<style scoped></style>
