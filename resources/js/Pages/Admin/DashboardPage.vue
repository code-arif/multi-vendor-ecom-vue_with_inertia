<script setup>
import AdminLayout from '../../Layouts/AdminLayout.vue'
import { Head, usePage } from '@inertiajs/vue3';
const authUser = usePage().props.auth.user;
</script>

<template>

    <Head>
        <title>Mini Shop || Admin Dashboard</title>
    </Head>
    <AdminLayout>
        <div class="container-fluid pt-4 px-4">
            <div class="bg-light rounded-top p-4">
                <div class="row">
                    <div class="col-12 col-sm-6 text-center text-sm-start">
                        <h4 class="mb-0 p-0">Welcome <span class="text-primary">{{ authUser.name }}</span></h4>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<style scoped></style>
