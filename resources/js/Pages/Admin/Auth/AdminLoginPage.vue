<script setup>
import GuestLayout from '../../../Layouts/GuestLayout.vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';

// Flash message and form setup
const message = usePage();
const form = useForm({
    email: '',
    password: '',
});

// Admin login function
function adminLogin() {
    form.post(route('admin.login'), {
        onSuccess: () => {
            if (message.props.flash.status === true) {
                successToast(message.props.flash.message);
                form.reset();
            } else {
                errorToast(message.props.flash.message);
            }
        },

        onError: (errors) => {
            // console.log(errors); // Log errors for debugging
            if (errors.email) {
                errorToast(errors.email);
            } else if (errors.password) {
                errorToast(errors.password);
            } else {
                errorToast(message.props.flash.message);
            }
        }
    });
}
</script>

<template>

    <Head>
        <title>Mini Shop || Login</title>
    </Head>
    <div class="position-relative bg-white d-flex p-0">
        <div class="container-fluid">
            <div class="row h-100 align-items-center justify-content-center" style="min-height: 100vh;">
                <div class="col-12 col-sm-8 col-md-6 col-lg-5 col-xl-4">
                    <div class="bg-light rounded p-4 p-sm-5 my-4 mx-3">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <a href="" class="">
                                <h4 class="text-primary"><i class="fa fa-shopping-cart me-2"></i>Mini Shop</h4>
                            </a>
                            <h4>Admin/Vendor</h4>
                        </div>
                        <form @submit.prevent="adminLogin()">
                            <!-- Email Field -->
                            <div class="form-floating mb-3">
                                <input v-model="form.email" type="email" class="form-control" id="floatingInput"
                                    placeholder="name@example.com" :class="{ 'is-invalid': form.errors.email }">
                                <label for="floatingInput">Email address</label>
                                <div v-if="form.errors.email" class="invalid-feedback">
                                    {{ form.errors.email }}
                                </div>
                            </div>

                            <!-- Password Field -->
                            <div class="form-floating mb-4">
                                <input v-model="form.password" type="password" class="form-control"
                                    id="floatingPassword" placeholder="Password"
                                    :class="{ 'is-invalid': form.errors.password }">
                                <label for="floatingPassword">Password</label>
                                <div v-if="form.errors.password" class="invalid-feedback">
                                    {{ form.errors.password }}
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="btn btn-primary py-3 w-100 mb-4">Sign In</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.invalid-feedback {
    color: red;
    font-size: 0.9rem;
    margin-top: 0.25rem;
}
</style>
