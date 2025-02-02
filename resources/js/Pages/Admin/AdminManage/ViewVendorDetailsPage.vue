<script setup>
import AdminLayout from '../../../Layouts/AdminLayout.vue';
import { Head, Link, usePage, useForm } from '@inertiajs/vue3';

//================profile update approach===================//
const vendor = usePage().props.vendor_details;

const form = useForm({
    email: vendor.vendor_personal.email || '',
    name: vendor.vendor_personal.name || '',
    phone: vendor.vendor_personal.phone || '',
    address: vendor.vendor_personal.address || '',
    city: vendor.vendor_personal.city || '',
    state: vendor.vendor_personal.state || '',
    country: vendor.vendor_personal.country || '',
    zip: vendor.vendor_personal.zip || '',
    pin: vendor.vendor_personal.pin || '',
    status: vendor.vendor_personal.status || ''
});

//===========================vendor bank details update approach==========================//
const bank_form = useForm({
    bank_name: vendor.vendor_bank.bank_name || '',
    account_number: vendor.vendor_bank.account_number || '',
    account_holder_name: vendor.vendor_bank.account_holder_name || '',
    branch_name: vendor.vendor_bank.branch_name || '',
    ifsc_code: vendor.vendor_bank.ifsc_code || '',
    swift_code: vendor.vendor_bank.swift_code || '',
    bank_address: vendor.vendor_bank.bank_address || '',
});

//================vendor business details update approach===================//
const business_form = useForm({
    shop_name: vendor.vendor_business.shop_name || '',
    shop_address: vendor.vendor_business.shop_address || '',
    shop_zip: vendor.vendor_business.shop_zip || '',
    shop_city: vendor.vendor_business.shop_city || '',
    shop_mobile: vendor.vendor_business.shop_mobile || '',
    shop_email: vendor.vendor_business.shop_email || '',
    shop_website: vendor.vendor_business.shop_website || '',
    shop_pin: vendor.vendor_business.shop_pin || '',
    shop_license: vendor.vendor_business.shop_license || '',
    shop_pan: vendor.vendor_business.shop_pan || '',
    shop_gst: vendor.vendor_business.shop_gst || '',
    shop_description: vendor.vendor_business.shop_description || '',
    shop_image: vendor.vendor_business.shop_image || ''
});
</script>

<template>

    <Head>
        <title>Mini Shop || Vendor Details</title>
    </Head>
    <AdminLayout>
        <!-- bradecrum  start -->
        <div class="container-fluid pt-4 px-4">
            <div class="bg-light rounded-top p-4">
                <div class="row">
                    <div class="col-12 text-center text-sm-start d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0 p-0"><span class="text-primary">{{ vendor.name }}</span> Details </h4>
                        </div>
                        <div>
                            <Link :href="route('admin.manage', { type: 'vendor' })" class="btn btn-sm btn-primary">Back
                            To List</Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- bradecrum end -->

        <div class="container-fluid pt-4 px-4 mb-3">
            <div class="row g-4">
                <!-- vendor personal details -->
                <div class="col-sm-12 col-xl-4">
                    <div class="bg-light rounded p-4">
                        <h6 class="mb-1">Vendor Profile Details</h6>
                        <hr>
                        <form>
                            <div class="form-floating mb-3">
                                <input v-model="form.email" type="email" class="form-control" id="floatingEmail"
                                    placeholder="name@example.com" readonly>
                                <label for="floatingEmail">Email address</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input v-model="form.name" type="text" class="form-control" id="floatingName"
                                    placeholder="Name">
                                <label for="floatingName">Name</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input v-model="form.phone" type="tel" class="form-control" id="floatingType"
                                    placeholder="phone">
                                <label for="floatingType">Mobile</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input v-model="form.city" type="text" class="form-control" id="floatingMobile"
                                    placeholder="City">
                                <label for="floatingMobile">City</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input v-model="form.state" type="text" class="form-control" id="floatingMobile"
                                    placeholder="State">
                                <label for="floatingMobile">State</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input v-model="form.country" type="text" class="form-control" id="floatingMobile"
                                    placeholder="Country">
                                <label for="floatingMobile">Country</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input v-model="form.pin" type="text" class="form-control" id="floatingMobile"
                                    placeholder="Pin" readonly>
                                <label for="floatingMobile">Pin</label>
                            </div>
                            <div class="form-floating mb-3">
                                <select v-model="form.status" class="form-select" id="floatingSelect">
                                    <option>Select Status</option>
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                                <label for="floatingSelect">Status</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input v-model="form.zip" type="number" class="form-control" id="floatingMobile"
                                    placeholder="Zip">
                                <label for="floatingMobile">Zip</label>
                            </div>
                            <div class="form-floating mb-3">
                                <textarea v-model="form.address" class="form-control" placeholder="Leave a comment here"
                                    id="floatingAddress" style="height: 100px; resize: none;"></textarea>
                                <label for="floatingAddress">Address</label>
                            </div>
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
                                    id="floatingBank_name" placeholder="Bank Name">
                                <label for="floatingBank_name"> Bank Name</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input v-model="bank_form.account_number" type="text" class="form-control"
                                    id="floatingAcc" placeholder="Account Number">
                                <label for="floatingAcc"> Account Number</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input v-model="bank_form.account_holder_name" type="text" class="form-control"
                                    id="floatingAcch" placeholder="Account Holder Name">
                                <label for="floatingAcch"> Account Holder Name</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input v-model="bank_form.branch_name" type="text" class="form-control"
                                    id="floatingBranch" placeholder="Branch Name">
                                <label for="floatingBranch"> Branch Name</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input v-model="bank_form.ifsc_code" type="text" class="form-control" id="floatingIFSC"
                                    placeholder="IFSC Code">
                                <label for="floatingIFSC"> IFSC Code</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input v-model="bank_form.swift_code" type="text" class="form-control"
                                    id="floatingSWIFT" placeholder="Swift Code">
                                <label for="floatingSWIFT"> Swift Code</label>
                            </div>
                            <div class="form-floating mb-3">
                                <textarea v-model="bank_form.bank_address" class="form-control"
                                    placeholder="Bank Address" id="floatingBankAddress"
                                    style="height: 100px; resize: none;"></textarea>
                                <label for="floatingBankAddress">Bank Address</label>
                            </div>
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
                                    id="floatingName" placeholder="shop name">
                                <label for="floatingName">Shop Name</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input v-model="business_form.shop_email" type="email" class="form-control"
                                    id="floatingEmail" placeholder="shop email">
                                <label for="floatingEmail">Shop Email</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input v-model="business_form.shop_mobile" type="tel" class="form-control"
                                    id="floatingMobile" placeholder="shop mobile">
                                <label for="floatingMobile">Shop Mobile</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input v-model="business_form.shop_website" type="text" class="form-control"
                                    id="floatingWeb" placeholder="Shop Website">
                                <label for="floatingWeb">Shop Website</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input v-model="business_form.shop_pin" type="text" class="form-control"
                                    id="floatingPin" placeholder="Pin">
                                <label for="floatingPin">PIN</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input v-model="business_form.shop_license" type="text" class="form-control"
                                    id="floatingLic" placeholder="Pin" readonly>
                                <label for="floatingLic">Shop Licence</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input v-model="business_form.shop_pan" type="text" class="form-control"
                                    id="floatingPan" placeholder="Pan">
                                <label for="floatingPan">Shop Pan Code</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input v-model="business_form.shop_gst" type="text" class="form-control"
                                    id="floatingGst" placeholder="Pin">
                                <label for="floatingGst">PIN</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input v-model="business_form.shop_zip" type="text" class="form-control"
                                    id="floatingZIP" placeholder="ZIP">
                                <label for="floatingZIP">ZIP</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input v-model="business_form.shop_city" type="text" class="form-control"
                                    id="floatingCity" placeholder="city">
                                <label for="floatingCity">Shop City</label>
                            </div>
                            <div class="form-floating mb-3">
                                <textarea v-model="business_form.shop_address" class="form-control"
                                    placeholder="Shop Address" id="floatingAddress" style="height: 100px; resize: none;"></textarea>
                                <label for="floatingAddress"> Shop Address</label>
                            </div>
                            <div class="form-floating mb-3">
                                <textarea v-model="business_form.shop_description" class="form-control"
                                    placeholder="Shop Description" id="floatingDescription"
                                    style="height: 100px; resize: none;"></textarea>
                                <label for="floatingDescription"> Shop Description</label>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<style scoped></style>
