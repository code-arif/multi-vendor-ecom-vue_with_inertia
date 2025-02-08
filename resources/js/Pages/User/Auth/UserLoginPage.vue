<script setup>
import GuestLayout from '../../../Layouts/GuestLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>

    <Head>
        <title>Mini Shop || Login</title>
    </Head>
    <GuestLayout>
        <div class="position-relative bg-white d-flex p-0">
            <div class="container-fluid">
                <div class="row h-100 align-items-center justify-content-center" style="min-height: 100vh;">
                    <div class="col-12 col-sm-8 col-md-6 col-lg-5 col-xl-4">
                        <div class="bg-light rounded p-4 p-sm-5 my-4 mx-3">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <a href="" class="">
                                    <h3 class="text-info"><i class="fa fa-hashtag me-2"></i>Mini Shop</h3>
                                </a>
                                <h3>Sign In</h3>
                            </div>
                            <form action="">
                                <div class="form-floating mb-3">
                                    <input type="email" class="form-control" id="floatingInput"
                                        placeholder="name@example.com">
                                    <label for="floatingInput">Email address</label>
                                </div>
                                <div class="form-floating mb-4">
                                    <input type="password" class="form-control" id="floatingPassword"
                                        placeholder="Password">
                                    <label for="floatingPassword">Password</label>
                                </div>
                                <div class="d-flex align-items-center justify-content-between mb-4">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="exampleCheck1">
                                        <label class="form-check-label" for="exampleCheck1">Remember me</label>
                                    </div>
                                    <a href="" class="text-danger">Forgot Password</a>
                                </div>
                                <button type="submit" class="btn btn-info w-100 mb-4">Sign In</button>
                            </form>
                            <p class="text-center mb-0">Don't have an Account? <a href="">Sign Up</a></p>
                            <div class="d-flex justify-content-between mt-3">
                                <div style="background: #0dcaf0; height: 1.5px; width: 40%; margin: 10px 0;"></div>
                                <div>
                                    <p class="text-center">OR</p>
                                </div>
                                <div style="background: #0dcaf0; height: 1.5px; width: 40%; margin: 10px 0;"></div>
                            </div>
                            <div class="d-flex justify-content-center">
                                <a href="" class="btn btn-outline-info py-2 px-4">
                                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/c/c1/Google_%22G%22_logo.svg/768px-Google_%22G%22_logo.svg.png"
                                        alt="" width="20px" height="20px" class="me-1 mb-1 d-inline">
                                    Login with Google</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </GuestLayout>
</template>

<style scoped></style>
