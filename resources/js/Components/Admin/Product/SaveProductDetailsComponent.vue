<script setup>
import { ref, computed, watch } from "vue";
import { useForm, usePage, Link } from "@inertiajs/vue3";

// Initialize form data
const list = usePage();
const product = list.props.product || {};
const productDetails = list.props.productDetails || {};

const form = useForm({
    product_id: product.id || null,
    long_description: productDetails.long_description || "",
});

// Save product details
function saveProductDetails() {
    const routeName = productDetails?.id ? "update.product.details" : "save.product.details";
    const requestData = form.product_id ? { product_id: form.product_id } : {};
    form.post(route(routeName, requestData), {
        onSuccess: () => {
            if (list.props.flash.status) {
                successToast(list.props.flash.message);
                form.reset();
            } else {
                errorToast(list.props.flash.message);
            }
        },
        onError: (errors) => {
            if (errors.product_id) {
                errorToast(errors.product_id);
            } else if (errors.long_description) {
                errorToast(errors.long_description);
            } else {
                errorToast(list.props.flash.message)
            }
        },
    });
}

</script>

<template>
    <div class="container-fluid pt-4 px-4">
        <div class="row g-4 mb-3">
            <div class="col-sm-12 col-xl-12">
                <div class="bg-light rounded p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <h6 class="mb-1">Product Details</h6>
                        <Link class="btn btn-sm btn-primary" :href="route('show.product')">Back to list</Link>
                    </div>
                    <hr>
                    <div class="card mb-3">
                        <div style="display: flex; align-items: center; gap: 15px;">
                            <img :src="product.image ? `/storage/${product.image}` : 'https://skala.or.id/wp-content/uploads/2024/01/dummy-post-square-1-1.jpg'" alt="Product Image" width="150">
                            <div>
                                <h3 style="margin-bottom: 5px;">{{ product?.product_name }}</h3>
                                <p style="margin: 0;">Price(৳): {{ product?.price }}</p>
                            </div>
                        </div>
                    </div>

                    <form @submit.prevent="saveProductDetails">
                        <!-- Product Description -->
                        <div class="mb-3">
                            <label for="long_description" class="mb-2">Product Description</label>
                            <textarea v-model="form.long_description" class="form-control" id="long_description"
                                rows="15" style="resize: none;"></textarea>
                            <div v-if="form.errors.long_description" class="invalid-feedback">{{
                                form.errors.long_description }}</div>
                        </div>

                        <!-- Policies -->
                        <!-- <div class="mb-3">
                            <label style="margin-right: 10px;">Product Policies</label>
                            <div v-for="(policy, index) in form.policies" :key="index" class="d-flex gap-2 mb-2">
                                <input v-model="policy.key" class="form-control w-50" placeholder="Policy Key"
                                    required />
                                <input v-model="policy.value" class="form-control w-50" placeholder="Policy Value"
                                    required />
                                <button type="button" class="btn btn-outline-danger btn-sm"
                                    @click="removePolicy(index)">×</button>
                            </div>
                            <button type="button" class="btn btn-sm btn-primary" @click="addPolicy">+ Add
                                Policy</button>
                        </div> -->

                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-primary w-100">{{ form.product_id ? 'Update Details' :
                            'Add Details' }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>
