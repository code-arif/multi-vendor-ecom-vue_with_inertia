<script setup>
import AdminLayout from '../../../Layouts/AdminLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

</script>

<template>

    <Head>
        <title>Mini Shop || Desclaimer</title>
    </Head>

    <AdminLayout>
        <!-- Yellow Message Div -->
        <div class="container-fluid mt-3">
            <div class="row">
                <div class="col-sm-12 col-xl-12">
                    <div class="alert alert-warning d-flex align-items-center" role="alert">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                            class="bi bi-exclamation-triangle-fill me-2" viewBox="0 0 16 16">
                            <path
                                d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                        </svg>
                        <div>
                            Your account is disabled. So you cannot see your product.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Back to Home Button -->
        <div class="container-fluid mt-3">
            <div class="row">
                <div class="col-sm-12 col-xl-12 text-center">
                    <Link :href="route('show.admin.dashboard')" class="btn btn-primary">Back to Home</Link>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<style scoped></style>
