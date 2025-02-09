<script setup>
import AdminLayout from '../../../Layouts/AdminLayout.vue';
import { Head } from '@inertiajs/vue3';
import ProductDetailsComponent from '../../../Components/Admin/Product/ProductDetailsComponent.vue';
</script>

<template>

    <Head>
        <title>Mini Shop || Products Details</title>
    </Head>

    <AdminLayout>
        <!-- bradecrum  start -->
        <div class="container-fluid pt-4 px-4">
            <div class="bg-light rounded-top p-4">
                <div class="row">
                    <div class="col-12 col-sm-6 text-center text-sm-start">
                        <h4 class="mb-0 p-0"><span class="text-primary">Product</span> Details </h4>
                    </div>
                </div>
            </div>
        </div>
        <!-- bradecrum end -->

        <ProductDetailsComponent />

    </AdminLayout>
</template>

<style scoped></style>

