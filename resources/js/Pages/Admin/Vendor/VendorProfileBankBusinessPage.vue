<script setup>
import AdminLayout from '../../../Layouts/AdminLayout.vue';
import { Head, usePage, useForm } from '@inertiajs/vue3';
const message = usePage();

//================profile update approach===================//
const vendor = usePage().props.vendor_profile;

const form = useForm({
    email: vendor.email, // Read-only
    name: vendor.name || '',
    phone: vendor.phone || '',
    address: vendor.address || '',
    city: vendor.city || '',
    state: vendor.state || '',
    country: vendor.country || '',
    zip: vendor.zip || '',
    pin: vendor.pin || '',
});

const updateVendorProfile = () => {
    form.post(route('update.vendor.profile'), {
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
            if (errors.name) {
                errorToast(errors.name);
            } else if (errors.phone) {
                errorToast(errors.phone);
            } else if (errors.status) {
                errorToast(errors.status);
            } else if (errors.zip) {
                errorToast(errors.zip);
            } else if (errors.address) {
                errorToast(errors.address);
            } else if (errors.city) {
                errorToast(errors.city);
            } else if (errors.country) {
                errorToast(errors.country);
            }
            else {
                errorToast(message.props.flash.message);
            }
        }
    });
};

//================vendor business details update approach===================//
const vendor_business = usePage().props.vendor_business;

const business_form = useForm({
    shop_name: vendor_business.shop_name || '',
    shop_address: vendor_business.shop_address || '',
    shop_zip: vendor_business.shop_zip || '',
    shop_city: vendor_business.shop_city || '',
    shop_mobile: vendor_business.shop_mobile || '',
    shop_email: vendor_business.shop_email || '',
    shop_website: vendor_business.shop_website || '',
    shop_pin: vendor_business.shop_pin || '',
    shop_license: vendor_business.shop_license || '',
    shop_pan: vendor_business.shop_pan || '',
    shop_gst: vendor_business.shop_gst || '',
    shop_description: vendor_business.shop_description || '',
    shop_image: vendor_business.shop_image || ''
});

const updateVendorBusiness = () => {
    business_form.post(route('update.vendor.business'), {
        onSuccess: () => {
            if (message.props.flash.status === true) {
                successToast(message.props.flash.message);
                business_form.reset();
            } else {
                errorToast(message.props.flash.message);
            }
        },
        onError: (errors) => {
            // console.log(errors); // Log errors for debugging
            if (errors.shop_name) {
                errorToast(errors.shop_name);
            } else if (errors.shop_address) {
                errorToast(errors.shop_address);
            } else if (errors.shop_zip) {
                errorToast(errors.shop_zip);
            } else if (errors.shop_city) {
                errorToast(errors.shop_city);
            } else if (errors.shop_mobile) {
                errorToast(errors.shop_mobile);
            } else if (errors.shop_email) {
                errorToast(errors.shop_email);
            } else if (errors.shop_website) {
                errorToast(errors.shop_website);
            } else if (errors.shop_pin) {
                errorToast(errors.shop_pin);
            } else if (errors.shop_license) {
                errorToast(errors.shop_license);
            } else if (errors.shop_pan) {
                errorToast(errors.shop_pan);
            } else if (errors.shop_gst) {
                errorToast(errors.shop_gst);
            } else if (errors.shop_description) {
                errorToast(errors.shop_description);
            }
            else {
                errorToast(message.props.flash.message);
            }
        }
    });
};

//===========================vendor bank details update approach==========================//
const vendor_bank = usePage().props.vendor_bank;

const bank_form = useForm({
    bank_name: vendor_bank.bank_name || '',
    account_number: vendor_bank.account_number || '',
    account_holder_name: vendor_bank.account_holder_name || '',
    branch_name: vendor_bank.branch_name || '',
    ifsc_code: vendor_bank.ifsc_code || '',
    swift_code: vendor_bank.swift_code || '',
    bank_address: vendor_bank.bank_address || '',
});

const updateVendorBank = () => {
    bank_form.post(route('update.vendor.bank'), {
        onSuccess: () => {
            if (message.props.flash.status === true) {
                successToast(message.props.flash.message);
                bank_form.reset();
            } else {
                errorToast(message.props.flash.message);
            }
        },
        onError: (errors) => {
            if (errors.bank_name) {
                errorToast(errors.bank_name);
            } else if (errors.account_number) {
                errorToast(errors.account_number);
            } else if (errors.account_holder_name) {
                errorToast(errors.account_holder_name);
            } else if (errors.branch_name) {
                errorToast(errors.branch_name);
            } else if (errors.ifsc_code) {
                errorToast(errors.ifsc_code);
            } else if (errors.swift_code) {
                errorToast(errors.swift_code);
            } else if (errors.bank_address) {
                errorToast(errors.bank_address);
            }
            else {
                errorToast(message.props.flash.message);
            }
        }
    });
};
</script>

<template>

    <Head>
        <title>Mini Shop || Vendor Profile</title>
    </Head>

    <AdminLayout>
        <!-- bradecrum  start -->
        <div class="container-fluid pt-4 px-4">
            <div class="bg-light rounded-top p-4">
                <div class="row">
                    <div class="col-12 col-sm-6 text-center text-sm-start">
                        <h4 class="mb-0 p-0"> <span class="text-primary">Vendor</span> Details</h4>
                    </div>
                </div>
            </div>
        </div>
        <!-- bradecrum end -->

        <!-- form start -->
        <div class="container-fluid pt-4 px-4 mb-3">
            <div class="row g-4">
                <div class="col-sm-12 col-xl-4">
                    <div class="bg-light rounded p-4">
                        <h6 class="mb-1">Vendor Profile Details</h6>
                        <hr>
                        <form action="" @submit.prevent="updateVendorProfile()">
                            <div class="form-floating mb-3">
                                <input v-model="form.email" type="email" class="form-control" id="floatingEmail"
                                    placeholder="name@example.com" readonly>
                                <label for="floatingEmail">Email address</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input v-model="form.name" type="text" class="form-control" id="floatingName"
                                    placeholder="Name" :class="{ 'is-invalid': form.errors.name }">
                                <label for="floatingName">Name</label>
                                <div v-if="form.errors.name" class="invalid-feedback">
                                    {{ form.errors.name }}
                                </div>
                            </div>
                            <div class="form-floating mb-3">
                                <input v-model="form.phone" type="tel" class="form-control" id="floatingType"
                                    placeholder="phone" :class="{ 'is-invalid': form.errors.phone }">
                                <label for="floatingType">Mobile</label>
                                <div v-if="form.errors.phone" class="invalid-feedback">
                                    {{ form.errors.phone }}
                                </div>
                            </div>
                            <div class="form-floating mb-3">
                                <input v-model="form.city" type="text" class="form-control" id="floatingMobile"
                                    placeholder="City" :class="{ 'is-invalid': form.errors.city }">
                                <label for="floatingMobile">City</label>
                                <div v-if="form.errors.city" class="invalid-feedback">
                                    {{ form.errors.city }}
                                </div>
                            </div>

                            <div class="form-floating mb-3">
                                <input v-model="form.state" type="text" class="form-control" id="floatingMobile"
                                    placeholder="State" :class="{ 'is-invalid': form.errors.state }">
                                <label for="floatingMobile">State</label>
                                <div v-if="form.errors.state" class="invalid-feedback">
                                    {{ form.errors.state }}
                                </div>
                            </div>

                            <div class="form-floating mb-3">
                                <input v-model="form.country" type="text" class="form-control" id="floatingMobile"
                                    placeholder="Country" :class="{ 'is-invalid': form.errors.country }">
                                <label for="floatingMobile">Country</label>
                                <div v-if="form.errors.country" class="invalid-feedback">
                                    {{ form.errors.country }}
                                </div>
                            </div>

                            <div class="form-floating mb-3">
                                <input v-model="form.pin" type="text" class="form-control" id="floatingMobile"
                                    placeholder="Pin" readonly>
                                <label for="floatingMobile">Pin</label>
                            </div>

                            <div class="form-floating mb-3">
                                <input v-model="form.zip" type="number" class="form-control" id="floatingMobile"
                                    placeholder="Zip" :class="{ 'is-invalid': form.errors.zip }">
                                <label for="floatingMobile">Zip</label>
                                <div v-if="form.errors.zip" class="invalid-feedback">
                                    {{ form.errors.zip }}
                                </div>
                            </div>

                            <div class="form-floating mb-3">
                                <textarea v-model="form.address" class="form-control" placeholder="Leave a comment here"
                                    id="floatingAddress" style="height: 100px; resize: none;"
                                    :class="{ 'is-invalid': form.errors.address }"></textarea>
                                <label for="floatingAddress">Address</label>
                                <div v-if="form.errors.address" class="invalid-feedback">
                                    {{ form.errors.address }}
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">Save</button>
                        </form>
                    </div>
                </div>

                <!-- vendor bank details -->
                <div class="col-sm-12 col-xl-4">
                    <div class="bg-light rounded p-4">
                        <h6 class="mb-1">Vendor Bank Details</h6>
                        <hr>
                        <form action="" @submit.prevent="updateVendorBank()">
                            <div class="form-floating mb-3">
                                <input v-model="bank_form.bank_name" type="text" class="form-control"
                                    id="floatingBank_name" placeholder="Bank Name"
                                    :class="{ 'is-invalid': bank_form.errors.bank_name }">
                                <label for="floatingBank_name"> Bank Name</label>
                                <div v-if="bank_form.errors.bank_name" class="invalid-feedback">
                                    {{ bank_form.errors.bank_name }}
                                </div>
                            </div>
                            <div class="form-floating mb-3">
                                <input v-model="bank_form.account_number" type="text" class="form-control"
                                    id="floatingAcc" placeholder="Account Number"
                                    :class="{ 'is-invalid': bank_form.errors.account_number }">
                                <label for="floatingAcc"> Account Number</label>
                                <div v-if="bank_form.errors.account_number" class="invalid-feedback">
                                    {{ bank_form.errors.account_number }}
                                </div>
                            </div>
                            <div class="form-floating mb-3">
                                <input v-model="bank_form.account_holder_name" type="text" class="form-control"
                                    id="floatingAcch" placeholder="Account Holder Name"
                                    :class="{ 'is-invalid': bank_form.errors.account_holder_name }">
                                <label for="floatingAcch"> Account Holder Name</label>
                                <div v-if="bank_form.errors.account_holder_name" class="invalid-feedback">
                                    {{ bank_form.errors.account_holder_name }}
                                </div>
                            </div>
                            <div class="form-floating mb-3">
                                <input v-model="bank_form.branch_name" type="text" class="form-control"
                                    id="floatingBranch" placeholder="Branch Name"
                                    :class="{ 'is-invalid': bank_form.errors.branch_name }">
                                <label for="floatingBranch"> Branch Name</label>
                                <div v-if="bank_form.errors.branch_name" class="invalid-feedback">
                                    {{ bank_form.errors.branch_name }}
                                </div>
                            </div>
                            <div class="form-floating mb-3">
                                <input v-model="bank_form.ifsc_code" type="text" class="form-control" id="floatingIFSC"
                                    placeholder="IFSC Code" :class="{ 'is-invalid': bank_form.errors.ifsc_code }">
                                <label for="floatingIFSC"> IFSC Code</label>
                                <div v-if="bank_form.errors.ifsc_code" class="invalid-feedback">
                                    {{ bank_form.errors.ifsc_code }}
                                </div>
                            </div>
                            <div class="form-floating mb-3">
                                <input v-model="bank_form.swift_code" type="text" class="form-control"
                                    id="floatingSWIFT" placeholder="Swift Code"
                                    :class="{ 'is-invalid': bank_form.errors.swift_code }">
                                <label for="floatingSWIFT"> Swift Code</label>
                                <div v-if="bank_form.errors.swift_code" class="invalid-feedback">
                                    {{ bank_form.errors.swift_code }}
                                </div>
                            </div>

                            <div class="form-floating mb-3">
                                <textarea v-model="bank_form.bank_address" class="form-control"
                                    placeholder="Bank Address" id="floatingBankAddress"
                                    style="height: 100px; resize: none;"
                                    :class="{ 'is-invalid': bank_form.errors.bank_address }"></textarea>
                                <label for="floatingBankAddress">Bank Address</label>
                                <div v-if="bank_form.errors.bank_address" class="invalid-feedback">
                                    {{ bank_form.errors.bank_address }}
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">Save</button>
                        </form>
                    </div>
                </div>

                <!-- vendor business details -->
                <div class="col-sm-12 col-xl-4">
                    <div class="bg-light rounded p-4">
                        <h6 class="mb-1">Vendor Business Details</h6>
                        <hr>
                        <form @submit.prevent="updateVendorBusiness()">
                            <div class="form-floating mb-3">
                                <input v-model="business_form.shop_name" type="text" class="form-control"
                                    id="floatingName" placeholder="shop name"
                                    :class="{ 'is-invalid': business_form.errors.shop_name }">
                                <label for="floatingName">Shop Name</label>
                                <div v-if="business_form.errors.shop_name" class="invalid-feedback">
                                    {{ business_form.errors.shop_name }}
                                </div>
                            </div>
                            <div class="form-floating mb-3">
                                <input v-model="business_form.shop_email" type="email" class="form-control"
                                    id="floatingEmail" placeholder="shop email"
                                    :class="{ 'is-invalid': business_form.errors.shop_email }">
                                <label for="floatingEmail">Shop Email</label>
                                <div v-if="business_form.errors.shop_email" class="invalid-feedback">
                                    {{ business_form.errors.shop_email }}
                                </div>
                            </div>
                            <div class="form-floating mb-3">
                                <input v-model="business_form.shop_mobile" type="tel" class="form-control"
                                    id="floatingMobile" placeholder="shop mobile"
                                    :class="{ 'is-invalid': business_form.errors.shop_mobile }">
                                <label for="floatingMobile">Shop Mobile</label>
                                <div v-if="business_form.errors.shop_mobile" class="invalid-feedback">
                                    {{ business_form.errors.shop_mobile }}
                                </div>
                            </div>
                            <div class="form-floating mb-3">
                                <input v-model="business_form.shop_website" type="text" class="form-control"
                                    id="floatingWeb" placeholder="Shop Website"
                                    :class="{ 'is-invalid': business_form.errors.shop_website }">
                                <label for="floatingWeb">Shop Website</label>
                                <div v-if="business_form.errors.shop_website" class="invalid-feedback">
                                    {{ business_form.errors.shop_website }}
                                </div>
                            </div>
                            <div class="form-floating mb-3">
                                <input v-model="business_form.shop_pin" type="text" class="form-control"
                                    id="floatingPin" placeholder="Pin"
                                    :class="{ 'is-invalid': business_form.errors.shop_pin }">
                                <label for="floatingPin">PIN</label>
                                <div v-if="business_form.errors.shop_pin" class="invalid-feedback">
                                    {{ business_form.errors.shop_pin }}
                                </div>
                            </div>
                            <div class="form-floating mb-3">
                                <input v-model="business_form.shop_license" type="text" class="form-control"
                                    id="floatingLic" placeholder="Licencer">
                                <label for="floatingLic">Shop Licence</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input v-model="business_form.shop_pan" type="text" class="form-control"
                                    id="floatingPan" placeholder="Pan"
                                    :class="{ 'is-invalid': business_form.errors.shop_pan }">
                                <label for="floatingPan">Shop Pan Code</label>
                                <div v-if="business_form.errors.shop_pan" class="invalid-feedback">
                                    {{ business_form.errors.shop_pan }}
                                </div>
                            </div>
                            <div class="form-floating mb-3">
                                <input v-model="business_form.shop_gst" type="text" class="form-control"
                                    id="floatingGst" placeholder="Pin"
                                    :class="{ 'is-invalid': business_form.errors.shop_gst }">
                                <label for="floatingGst">PIN</label>
                                <div v-if="business_form.errors.shop_gst" class="invalid-feedback">
                                    {{ business_form.errors.shop_gst }}
                                </div>
                            </div>
                            <div class="form-floating mb-3">
                                <input v-model="business_form.shop_zip" type="text" class="form-control"
                                    id="floatingZIP" placeholder="ZIP"
                                    :class="{ 'is-invalid': business_form.errors.shop_zip }">
                                <label for="floatingZIP">ZIP</label>
                                <div v-if="business_form.errors.shop_zip" class="invalid-feedback">
                                    {{ business_form.errors.shop_zip }}
                                </div>
                            </div>
                            <div class="form-floating mb-3">
                                <input v-model="business_form.shop_city" type="text" class="form-control"
                                    id="floatingCity" placeholder="city"
                                    :class="{ 'is-invalid': business_form.errors.shop_city }">
                                <label for="floatingCity">Shop City</label>
                                <div v-if="business_form.errors.shop_city" class="invalid-feedback">
                                    {{ business_form.errors.shop_city }}
                                </div>
                            </div>
                            <div class="form-floating mb-3">
                                <textarea v-model="business_form.shop_address" class="form-control"
                                    placeholder="Shop Address" id="floatingAddress" style="height: 100px; resize: none;"
                                    :class="{ 'is-invalid': business_form.errors.shop_address }"></textarea>
                                <label for="floatingAddress"> Shop Address</label>
                                <div v-if="business_form.errors.shop_address" class="invalid-feedback">
                                    {{ business_form.errors.shop_address }}
                                </div>
                            </div>

                            <div class="form-floating mb-3">
                                <textarea v-model="business_form.shop_description" class="form-control"
                                    placeholder="Shop Description" id="floatingDescription"
                                    style="height: 100px; resize: none;"
                                    :class="{ 'is-invalid': business_form.errors.shop_description }"></textarea>
                                <label for="floatingDescription"> Shop Description</label>
                                <div v-if="business_form.errors.shop_description" class="invalid-feedback">
                                    {{ business_form.errors.shop_description }}
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">Save</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<style scoped>
.invalid-feedback {
    color: red;
    font-size: 0.9rem;
    margin-top: 0.25rem;
}
</style>
