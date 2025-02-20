<script setup>
import GuestLayout from '../../../Layouts/GuestLayout.vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';

const page = usePage();

const form = useForm({
    otp: '',
})

function verifyOTP() {
    form.post(route('verify.otp'), {
        preserveScroll: true,
        onSuccess: () => {
            if (page.props.flash.status === true) {
                successToast(page.props.flash.message);
                form.reset();
            } else {
                errorToast(page.props.flash.message);
            }
        },
        onError: (errors) => {
            if (errors.otp) {
                errorToast(errors.otp);
            } else {
                errorToast('Something went wrong');
            }
        }
    })
}
</script>

<template>

    <Head>
        <title>Mini Shop || OTP Verification</title>
    </Head>
    <GuestLayout>
        <div class="position-relative bg-white d-flex p-0">
            <div class="container-fluid">
                <div class="row h-100 align-items-center justify-content-center" style="min-height: 70vh;">
                    <div class="col-12 col-sm-8 col-md-6 col-lg-5 col-xl-4">
                        <div class="bg-light rounded p-4 p-sm-5 my-4 mx-3">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <a href="" class="">
                                    <h3 class="text-info"><i class="fa fa-shopping-cart me-2"></i>Mini Shop</h3>
                                </a>
                                <h3>OTP</h3>
                            </div>
                            <form @submit.prevent="verifyOTP()">
                                <div class="form-floating mb-3">
                                    <input type="otp" class="form-control" id="otp" placeholder="name@example.com"
                                        v-model="form.otp">
                                    <label for="otp">Enter OTP</label>
                                </div>
                                <button type="submit" class="btn btn-info w-100 mb-4">Verify</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </GuestLayout>
</template>

<style scoped></style>
