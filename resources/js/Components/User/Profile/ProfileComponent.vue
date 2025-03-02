<script setup>
import { Link, useForm, usePage } from '@inertiajs/vue3';

const page = usePage();

const user = page.props.user || {};

const form = useForm({
    cus_name: user.cus_name || '',
    cus_address: user.cus_address || '',
    cus_country: user.cus_country || '',
    cus_city: user?.cus_city || '',
    cus_state: user?.cus_state || '',
    cus_zip: user?.cus_zip || '',
    cus_phone: user?.cus_phone || '',
    cus_fax: user?.cus_fax || '',
});

function saveProfile() {
    form.post(route('save.profile'), {
        preserveScroll: true,
        onSuccess: () => {
            successToast(page.props.flash.message)
        },
        onError: (errors) => {
            if (errors.cus_name) {
                errorToast(errors.cus_name)
            } else if (errors.cus_address) {
                errorToast(errors.cus_address)
            } else if (errors.cus_country) {
                errorToast(errors.cus_country)
            } else if (errors.cus_city) {
                errorToast(errors.cus_city)
            } else if (errors.cus_state) {
                errorToast(errors.cus_phone)
            } else{
                errorToast('Error')
            }
        }
    });
}

</script>

<template>
    <!-- Breadcrumb Start -->
    <div class="container-fluid">
        <div class="row px-xl-5">
            <div class="col-12">
                <nav class="breadcrumb bg-light mb-30 p-3">
                    <Link :href="route('show.home.page')" class="breadcrumb-item text-dark" href="#">Home</LInk>
                    <a class="breadcrumb-item text-dark" href="#">Profile</a>
                </nav>
            </div>
        </div>
    </div>
    <!-- Breadcrumb End -->

    <!-- Checkout Start -->
    <div class="container-fluid">
        <div class="row px-xl-5">

            <div class="col-lg-4">
                <h5 class="section-title position-relative text-uppercase mb-3"><span class="bg-light"
                        style="padding:3px 10px">Manage Account</span></h5>

                <div class="bg-light p-30 mb-5">

                    <div class="mb-3" style="text-align: center; padding: 3px 5px; border: 1px solid #ddd;">
                        <h6>{{ user.user?.email }}</h6>
                    </div>

                    <Link :href="route('show.profile')" class="mb-3 text-dark btn btn-info w-100 text-start">Profile
                    </Link>
                    <Link class="mb-3 text-dark btn btn-info w-100 text-start">My Order</Link>
                    <Link class="mb-3 text-dark btn btn-info w-100 text-start">Wishlist</Link>
                    <Link :href="route('show.shipping.address')" class="mb-3 text-dark btn btn-info w-100 text-start">Shipping Address</Link>
                </div>
            </div>

            <div class="col-lg-8">
                <h5 class="section-title position-relative text-uppercase mb-3"><span class="bg-light"
                        style="padding:3px 10px">Information</span></h5>
                <div class="bg-light p-30" style="margin-bottom: 15px;">
                    <form action="" @submit.prevent="saveProfile">
                        <div class="row">
                            <div class="col-md-6 form-group" style="margin-bottom: 5px;">
                                <label style="margin-bottom: 5px;">Full Name <span class="text-danger">*</span> </label>
                                <input class="form-control" type="text" placeholder="Full Name" v-model="form.cus_name">
                            </div>
                            <div class="col-md-6 form-group" style="margin-bottom: 5px;">
                                <label style="margin-bottom: 5px;">Country <span class="text-danger">*</span> </label>
                                <input class="form-control" type="text" placeholder="Country"
                                    v-model="form.cus_country">
                            </div>
                            <div class="col-md-6 form-group" style="margin-bottom: 5px;">
                                <label style="margin-bottom: 5px;">City <span class="text-danger">*</span> </label>
                                <input class="form-control" type="text" placeholder="City" v-model="form.cus_city">
                            </div>
                            <div class="col-md-6 form-group" style="margin-bottom: 5px;">
                                <label style="margin-bottom: 5px;">State</label>
                                <input class="form-control" type="text" placeholder="State" v-model="form.cus_state">
                            </div>
                            <div class="col-md-6 form-group" style="margin-bottom: 5px;">
                                <label style="margin-bottom: 5px;"> Zip Code</label>
                                <input class="form-control" type="text" placeholder="Zip Code" v-model="form.cus_zip">
                            </div>
                            <div class="col-md-6 form-group" style="margin-bottom: 5px;">
                                <label style="margin-bottom: 5px;"> Phone Number <span class=" text-danger">*</span>
                                </label>
                                <input class="form-control" type="text" placeholder="Phone Number"
                                    v-model="form.cus_phone">
                            </div>
                            <div class="col-md-6 form-group" style="margin-bottom: 5px;">
                                <label> Fax </label>
                                <input class="form-control" type="text" placeholder="Fax" v-model="form.cus_fax">
                            </div>
                            <div class="col-12 form-group" style="margin-bottom: 5px ;">
                                <label> Address <span class="text-danger">*</span> </label>
                                <textarea class="form-control" v-model="form.cus_address"> </textarea>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-info mt-3"> Save Change </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Checkout End -->
</template>

<style scoped></style>
