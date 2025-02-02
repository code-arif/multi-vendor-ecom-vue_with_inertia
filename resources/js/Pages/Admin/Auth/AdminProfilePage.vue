<script setup>
import AdminLayout from '../../../Layouts/AdminLayout.vue'
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
const message = usePage();

//================profile update approach===================//
const admin = usePage().props.admin;

const form = useForm({
    email: admin.email, // Read-only
    name: admin.name || '',
    type: admin.type || '',
    mobile: admin.mobile || '',
    status: admin.status || '',
    zip: admin.zip || '',
    address: admin.address || '',
    image: admin.image || ''
});

// Reactive property for dynamic image preview
const imagePreview = ref(
    admin.image ? `/storage/${admin.image}` : 'https://skala.or.id/wp-content/uploads/2024/01/dummy-post-square-1-1.jpg'
);

// Update image preview dynamically
function handleImageInput(event) {
    const file = event.target.files[0];
    if (file) {
        form.image = file;
        const reader = new FileReader();
        reader.onload = (e) => {
            imagePreview.value = e.target.result;
        };
        reader.readAsDataURL(file);
    }
}

const updateAdminProfile = () => {
    form.post(route('update.admin.profile'), {
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
            } else if (errors.mobile) {
                errorToast(errors.mobile);
            } else if (errors.status) {
                errorToast(errors.status);
            } else if (errors.zip) {
                errorToast(errors.zip);
            } else if (errors.address) {
                errorToast(errors.address);
            }
            else {
                errorToast(message.props.flash.message);
            }
        }
    });
};

//================password update approach==================//
const p_form = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

function UpdatePassword() {
    p_form.post(route('update.admin.password'), {
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
            if (errors.current_password) {
                errorToast(errors.current_password);
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
        <title>Mini Shop || Admin Profile</title>
    </Head>
    <AdminLayout>
        <!-- bradecrum  start -->
        <div class="container-fluid pt-4 px-4">
            <div class="bg-light rounded-top p-4">
                <div class="row">
                    <div class="col-12 col-sm-6 text-center text-sm-start">
                        <h4 class="mb-0 p-0">Welcome <span class="text-primary">{{ admin.name }}</span></h4>
                    </div>
                </div>
            </div>
        </div>
        <!-- bradecrum end -->

        <!-- form start -->
        <div class="container-fluid pt-4 px-4">
            <div class="row g-4">
                <div class="col-sm-12 col-xl-7">
                    <div class="bg-light rounded h-100 p-4">
                        <h6 class="mb-1">Profile Update</h6>
                        <hr>

                        <div class="d-flex justify-content-center">
                            <div class="mb-5"
                                style="width: 150px; height: 150px; overflow: hidden; border-radius: 50%; border: 10px solid rgb(0 156 255 / 16%); display: flex; justify-content: center; align-items: center; background: #f8f9fa;">
                                <img :src="imagePreview" alt="Image Preview" class="img-fluid"
                                    style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%; border: 10px solid rgb(0 156 255 / 36%);">
                            </div>
                        </div>

                        <form action="" @submit.prevent="updateAdminProfile()">
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
                                <input v-model="form.type" type="text" class="form-control" id="floatingType"
                                    placeholder="admin type" readonly>
                                <label for="floatingType">Type</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input v-model="form.mobile" type="tel" class="form-control" id="floatingMobile"
                                    placeholder="Mobile" :class="{ 'is-invalid': form.errors.mobile }">
                                <label for="floatingMobile">Mobile</label>
                                <div v-if="form.errors.mobile" class="invalid-feedback">
                                    {{ form.errors.mobile }}
                                </div>
                            </div>

                            <div class="form-floating mb-3">
                                <select v-model="form.status" class="form-select" id="floatingSelect"
                                    :class="{ 'is-invalid': form.errors.status }">
                                    <option>Select Status</option>
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                                <label for="floatingSelect">Status</label>
                                <div v-if="form.errors.status" class="invalid-feedback">
                                    {{ form.errors.status }}
                                </div>
                            </div>

                            <div class="form-floating mb-3">
                                <input v-model="form.zip" type="text" class="form-control" id="floatingZip"
                                    placeholder="Zip" :class="{ 'is-invalid': form.errors.zip }">
                                <label for="floatingZip">Zip</label>
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

                            <!-- Image Field -->
                            <div class="mb-3">
                                <label for="profileImage" class="form-label">Profile Image</label>
                                <input type="file" class="form-control" id="profileImage" @input="handleImageInput" />
                            </div>

                            <button type="submit" class="btn btn-primary w-100">Save</button>
                        </form>
                    </div>
                </div>

                <!-- update password -->
                <div class="col-sm-12 col-xl-5">
                    <div class="bg-light rounded p-4">
                        <!-- {{ p_form }} -->
                        <h6 class="mb-1">Update Password</h6>
                        <hr>
                        <form @submit.prevent="UpdatePassword">
                            <div class="form-floating mb-3">
                                <input v-model="p_form.current_password" type="password" class="form-control"
                                    id="floatingCurrentPass" placeholder="Password"
                                    :class="{ 'is-invalid': p_form.errors.current_password }">
                                <label for="floatingCurrentPass">Current Password</label>
                                <div v-if="p_form.errors.current_password" class="invalid-feedback">
                                    {{ p_form.errors.current_password }}
                                </div>
                            </div>
                            <div class="form-floating mb-3">
                                <input v-model="p_form.password" type="password" class="form-control"
                                    id="floatingNewPassword" placeholder="New Password"
                                    :class="{ 'is-invalid': p_form.errors.password }">
                                <label for="floatingNewPassword"> New Password</label>
                                <div v-if="p_form.errors.password" class="invalid-feedback">
                                    {{ p_form.errors.password }}
                                </div>
                            </div>
                            <div class="form-floating mb-3">
                                <input v-model="p_form.password_confirmation" type="password" class="form-control"
                                    id="floatingConfirmPassword" placeholder="Confirm Password"
                                    :class="{ 'is-invalid': p_form.errors.password_confirmation }">
                                <label for="floatingConfirmPassword"> Confirm Password</label>
                                <div v-if="p_form.errors.password_confirmation" class="invalid-feedback">
                                    {{ p_form.errors.password_confirmation }}
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
