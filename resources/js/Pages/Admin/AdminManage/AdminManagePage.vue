<script setup>
import AdminLayout from '../../../Layouts/AdminLayout.vue';
import AdminManageComponent from '../../../Components/AdminManage/AdminManageComponent.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>

    <Head>
        <title>Mini Shop || Admin Manage</title>
    </Head>
    <AdminLayout>
        <!-- bradecrum  start -->
        <div class="container-fluid pt-4 px-4">
            <div class="bg-light rounded-top p-4">
                <div class="row">
                    <div class="col-12 col-sm-6 text-center text-sm-start">
                        <h4 class="mb-0 p-0"><span class="text-primary">Admin</span> Management </h4>
                    </div>
                </div>
            </div>
        </div>
        <!-- bradecrum end -->

         <AdminManageComponent />
    </AdminLayout>
</template>

<style scoped></style>
