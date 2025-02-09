<script setup>
import { Link, usePage, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
const list = usePage();


//show product image, name and price
const product = list.props.product || {};
const product_specifications = list.props.product_specifications || {};

const imagePreview = ref(
    product?.image ? `/storage/${product?.image}` : 'https://skala.or.id/wp-content/uploads/2024/01/dummy-post-square-1-1.jpg'
);

//==================product sepcification====================//
// Form data using Inertia's useForm
const form = useForm({
    product_id: product?.id || '',
    additional_price: product_specifications?.additional_price || '',
    specifications: product_specifications?.specifications || [{ attribute: "", value: "" }],
});
// Function to add new specification input fields
function addSpecification() {
    form.specifications.push({ attribute: "", value: "" });
}

// Function to remove a specification field
function removeSpecification(index) {
    form.specifications.splice(index, 1);
}

// Function to submit form data
function saveSpecification() {
    form.post(route('save.product.specification'), {
        onSuccess: () => {
            if (list.props.flash.status === true) {
                successToast(list.props.flash.message);
                form.reset();
            } else {
                errorToast(list.props.flash.message);
            }
        },
        onError: (errors) => {
            if (errors.product_id) {
                errorToast(errors.product_id);
            } else if (errors.additional_price) {
                errorToast(errors.additional_price);
            } else if (errors.specifications) {
                errorToast(errors.specifications);
            } else {
                errorToast('Failed to save product specification');
            }
        },
    });
}


// ========================Add Image ==========================//
const img_form = useForm({
    product_id: product?.id || '',
    image_path: '',
});

// File Handling
const extraImagePreviews = ref([]);

function handleFileInput(event, type) {
    const files = Array.from(event.target.files);
    if (type === "image_path") {
        img_form.image_path = files;
        extraImagePreviews.value = files.map(file => URL.createObjectURL(file));
    }
}

function removeFile(index, type) {
    if (type === "image_path") {
        img_form.image_path.splice(index, 1);
        extraImagePreviews.value.splice(index, 1);
    }
}

// Submit Form
function saveProductImage() {
    img_form.post(route('save.product.image'), {
        onSuccess: () => {
            img_form.reset(); // Clear form
            extraImagePreviews.value = []; // Clear preview
            if (list.props.flash.status === true) {
                successToast(list.props.flash.message)
            } else {
                errorToast('An error occurred while saving product image')
            }
        },
        onError: (errors) => {
            if (errors.product_id) {
                errorToast(errors.product_id);
            }
            if (errors.image_path) {
                errorToast(errors.image_path);
            }
            if (errors["image_path.0"]) {
                errorToast(errors["image_path.0"]);
            }
            if (errors["image_path.1"]) {
                errorToast(errors["image_path.1"]);
            }
        }
    });
}

//========================submit product video==========================//

</script>

<template>
    <div class="container-fluid pt-4 px-4">
        <div class="row g-4 mb-3">
            <div class="col-sm-12 col-xl-7">
                <div class="bg-light rounded p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <h6 class="mb-1"> Add Product Specifications</h6>
                        <Link class="btn btn-sm btn-primary" :href="route('show.product')">Back to list</Link>
                    </div>
                    <hr>
                    <div class="card">
                        <div style="display: flex; align-items: center; gap: 15px;">
                            <img :src="imagePreview" alt="Product Image" width="150">
                            <div>
                                <h3 style="margin-bottom: 5px;">{{ product?.product_name }}</h3>
                                <p style="margin: 0;">Price(৳): {{ product?.price }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Form to Add/Edit Product Specifications -->
                    <form @submit.prevent="saveSpecification">
                        <input type="hidden" v-model="form.product_id">

                        <div class="mb-3 mt-3">
                            <label for="additional_price">Additional Price</label>
                            <input type="number" id="additional_price" class="form-control"
                                v-model="form.additional_price" :class="{ 'is-invalid': form.errors.additional_price }">
                            <div v-if="form.errors.additional_price" class="invalid-feedback">
                                {{ form.errors.additional_price }}
                            </div>
                        </div>

                        <!-- Specifications Section -->
                        <div class="mb-3">
                            <label style="margin-right: 10px;">Product Specifications</label>
                            <div v-for="(spec, index) in form.specifications" :key="index" class="d-flex gap-2 mb-2">
                                <input v-model="spec.attribute" class="form-control w-50"
                                    placeholder="Attribute (e.g., Color, Size)" required
                                    :class="{ 'is-invalid': form.errors.attribute }" />
                                <input v-model="spec.value" class="form-control w-50" placeholder="Value (e.g., Red, L)"
                                    required :class="{ 'is-invalid': form.errors.value }" />
                                <button type="button" class="btn btn-outline-danger btn-sm"
                                    @click="removeSpecification(index)">×</button>

                                <div v-if="form.errors.attribute" class="invalid-feedback">
                                    {{ form.errors.attribute }}
                                </div>
                                <div v-if="form.errors.value" class="invalid-feedback"> {{ form.errors.value }}</div>
                            </div>
                            <button type="button" class="btn btn-sm btn-primary" @click="addSpecification">+ Add
                                Specification</button>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-primary w-100"> Submit </button>
                    </form>
                </div>
            </div>

            <div class="col-sm-12 col-xl-5">
                <div class="bg-light rounded p-4 mb-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <h6 class="mb-1">Product Images</h6>
                        <Link class="btn btn-sm btn-primary" :href="route('show.product')">Back to list</Link>
                    </div>
                    <hr>

                    <form @submit.prevent="saveProductImage">
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label class="mb-2">Product Images</label>
                                <input type="file" class="form-control" multiple
                                    @change="handleFileInput($event, 'image_path')"
                                    :class="{ 'is-invalid': img_form.errors.image_path }">

                                <div v-if="img_form.errors.image_path" class="invalid-feedback">
                                    {{img_form.errors.image_path }}
                                </div>

                                <div class="d-flex flex-wrap mt-2">
                                    <div v-for="(preview, index) in extraImagePreviews" :key="index"
                                        class="position-relative me-2">
                                        <img :src="preview" alt="Image Preview" class="rounded border mb-2"
                                            style="width: 80px; height: 80px; object-fit: cover;">
                                        <button type="button"
                                            class="btn btn-sm text-danger position-absolute top-0 end-0"
                                            style="transform: translate(50%, -50%);"
                                            @click="removeFile(index, 'image_path')">×</button>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <progress v-if="img_form.progress" :value="img_form.progress.percentage" max="100">
                                        {{ img_form.progress.percentage }}%
                                    </progress>
                                </div>
                            </div>
                        </div>
                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-primary w-100"> Submit </button>
                    </form>

                </div>
                <div class="bg-light rounded p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <h6 class="mb-1">Product Videos <span class="text-danger">(Not Functional)</span></h6>
                        <Link class="btn btn-sm btn-primary" :href="route('show.product')">Back to list</Link>
                    </div>
                    <hr>
                    <div class="row mb-3">
                        <form @submit.prevent="saveProductVideo">
                            <div class="col-md-12">
                                <label class="mb-2">Product Video (MP4 format, max 10MB)</label>
                                <input type="file" class="form-control" @change="productVideoUpload">
                                <hr>

                                <div class="custom-control custom-checkbox">
                                    <label class="custom-control-label mb-2" for="shipto" data-bs-toggle="collapse"
                                        data-bs-target="#video_url">Or Enter Video URL</label>
                                </div>
                                <div class="collapse" id="video_url">
                                    <div class="row">
                                        <div class="col-md-12 form-group mb-3">
                                            <input class="form-control" type="text"
                                                placeholder="Video Url: e.g https://youtube.com">
                                        </div>
                                    </div>
                                </div>

                                <!-- <div class="mt-2">
                                    <div v-for="(preview, index) in videoPreviews" :key="index" class="mb-2">
                                        <video :src="preview" controls width="100"></video>
                                    </div>
                                </div> -->
                            </div>
                            <!-- Submit Button -->
                            <button type="submit" class="btn btn-primary w-100"> Submit </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped></style>
