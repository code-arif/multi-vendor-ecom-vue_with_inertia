<script setup>
import { ref, computed, watch } from "vue";
import { useForm, usePage, Link } from "@inertiajs/vue3";

// Initialize form data
const list = usePage();
const productDetails = list.props.productDetails || {};

const form = useForm({
    product_id: productDetails.product_id || null,
    long_description: productDetails.long_description || "",
    policies: productDetails.policies ? [...productDetails.policies] : [],
});

// Policy Management
function addPolicy() {
    form.policies.push({ key: "", value: "" });
}

function removePolicy(index) {
    form.policies.splice(index, 1);
}

// Watch for productDetails updates
watch(() => list.props.productDetails, (newData) => {
    if (newData) {
        form.product_id = newData.product_id;
        form.long_description = newData.long_description || "";
        form.policies = newData.policies ? [...newData.policies] : [];
    }
}, { deep: true });

// Save product details
function saveProductDetails() {
    form.post(route("save.product.details"), {
        onSuccess: () => {
            successToast(list.props.flash.message);
        },
        onError: () => {
            errorToast("An error occurred while saving.");
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
                    <form @submit.prevent="saveProductDetails">
                        <!-- Product Description -->
                        <div class="mb-3">
                            <label for="long_description" class="mb-2">Product Description</label>
                            <textarea v-model="form.long_description" class="form-control" id="long_description"
                                rows="10" style="resize: none;"></textarea>
                            <div v-if="form.errors.long_description" class="invalid-feedback">{{
                                form.errors.long_description }}</div>
                        </div>

                        <!-- Policies -->
                        <div class="mb-3">
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
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-primary w-100">{{ form.product_id ? 'Update Details' :
                            'Add Details' }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>
