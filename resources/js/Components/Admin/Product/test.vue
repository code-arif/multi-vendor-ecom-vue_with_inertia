<script setup>
import { ref } from "vue";
import { useForm, usePage, Link } from "@inertiajs/vue3";

const list = usePage();
const productDetails = list.props.productDetails || {};

const form = useForm({
    product_id: productDetails.product_id || null,
    extra_images: [],
    videos: [],
    video_links: [],
    long_description: productDetails.long_description || "",
    policies: productDetails.policies ? [...productDetails.policies] : [],
});


function addPolicy() {
    form.policies.push({ key: "", value: "" });
}

function removePolicy(index) {
    form.policies.splice(index, 1);
}

const extraImagePreviews = ref([
    'https://skala.or.id/wp-content/uploads/2024/01/dummy-post-square-1-1.jpg'
]);
const videoPreviews = ref([]);


function handleExtraImageInput(event) {
    form.extra_images = Array.from(event.target.files);
    extraImagePreviews.value = form.extra_images.map(file => URL.createObjectURL(file));
}

function handleVideoInput(event) {
    form.videos = Array.from(event.target.files);
    videoPreviews.value = form.videos.map(file => URL.createObjectURL(file));
}

function saveProductDetails() {
    form.post(route("store.product.details"), {
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
            } else if (errors.extra_images) {
                errorToast(errors.extra_images);
            } else if (errors.videos) {
                errorToast(errors.videos);
            } else if (errors.video_links) {
                errorToast(errors.video_links);
            } else if (errors.long_description) {
                errorToast(errors.long_description);
            } else {
                errorToast("An error occurred while saving product details.");
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
                        <div>
                            <h6 class="mb-1"> Product Details </h6>
                        </div>
                        <div>
                            <Link class="btn btn-sm btn-primary" :href="route('show.product')"> Back to list</Link>
                        </div>
                    </div>
                    <hr>
                    <form @submit.prevent="saveProductDetails">

                        <div class="mb-3">
                            <label class="mb-2" for="long_description">Product Description</label>
                            <textarea v-model="form.long_description" class="form-control" id="long_description"
                                rows="10"></textarea>
                            <div v-if="form.errors.long_description" class="invalid-feedback">
                                {{ form.errors.long_description }}
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="form-group col-md-6">
                                <label class="mb-2">Product Images</label>
                                <input type="file" class="form-control" multiple @change="handleExtraImageInput">
                                <div v-if="form.errors.extra_images" class="invalid-feedback">
                                    {{ form.errors.extra_images }}
                                </div>
                                <div class="d-flex flex-wrap mt-2">
                                    <div v-for="(preview, index) in extraImagePreviews" :key="index" class="me-2 mb-2"
                                        style="width: 80px; height: 80px; overflow: hidden; background: #f8f9fa;">
                                        <img :src="preview" alt="Image Preview" class="img-fluid"
                                            style="width: 100%; height: 100%; object-fit: cover; border: 2px solid rgb(0 156 255 / 36%);">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                <label class="mb-2">Product Video(s) or Links</label>
                                <input type="file" class="form-control" multiple @change="handleVideoInput">
                                <div v-if="form.errors.videos" class="invalid-feedback">
                                    {{ form.errors.videos }}
                                </div>
                                <progress v-if="form.progress" :value="form.progress.percentage" max="100">
                                    {{ form.progress.percentage }}%
                                </progress>
                                <div class="mt-2">
                                    <div v-for="(preview, index) in videoPreviews" :key="index" class="mb-2">
                                        <template v-if="preview.startsWith('http')">
                                            <a :href="preview" target="_blank">External Video Link
                                                {{ index + 1 }}</a>
                                        </template>
                                        <template v-else>
                                            <video :src="preview" controls width="100"></video>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Policies Input Fields -->
                        <div class="mb-3">
                            <label class="pr-3">Product Policies &nbsp; &nbsp; </label>
                            <div v-for="(policy, index) in form.policies" :key="index" class="d-flex gap-2 mb-2">
                                <input v-model="policy.key" class="form-control w-50"
                                    placeholder="Policy Key (e.g. Warranty)" required />
                                <input v-model="policy.value" class="form-control w-50"
                                    placeholder="Policy Value (e.g. 1 Year)" required />
                                <button type="button" class="btn btn-danger"
                                    @click="removePolicy(index)">Remove</button>
                            </div>
                            <button type="button" class="btn btn-sm btn-primary" @click="addPolicy">+ Add
                                Policy</button>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            {{ form.product_id ? 'Update Details' : 'Add Details' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped></style>
