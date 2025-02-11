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

//================== Product Specification Form ====================//
const form = useForm({
    product_id: product?.id || '',
    size: product_specifications?.size || '',
    color: product_specifications?.color || '',
    material: product_specifications?.material || '',
    weight: product_specifications?.weight || '',
    weight_unit: product_specifications?.weight_unit || 'kg',
    length: product_specifications?.length || '',
    length_unit: product_specifications?.length_unit || 'cm',
    width: product_specifications?.width || '',
    width_unit: product_specifications?.width_unit || 'cm',
    height: product_specifications?.height || '',
    height_unit: product_specifications?.height_unit || 'cm',
    volume: product_specifications?.volume || '',
    volume_unit: product_specifications?.volume_unit || 'cm3',
    additional_price: product_specifications?.additional_price || '',
});

// Function to submit form data
function saveSpecification() {
    const routeName = product_specifications?.id ? "update.product.specification" : "save.product.specification";

    const requestData = form.product_id ? { product_id: form.product_id } : {};
    form.post(route(routeName, requestData), {
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
            } else if (errors.size) {
                errorToast(errors.size);
            } else if (errors.color) {
                errorToast(errors.color);
            } else if (errors.material) {
                errorToast(errors.material);
            } else if (errors.weight) {
                errorToast(errors.weight);
            } else if (errors.weight_unit) {
                errorToast(errors.weight_unit);
            } else if (errors.length) {
                errorToast(errors.length);
            } else if (errors.length_unit) {
                errorToast(errors.length_unit);
            } else if (errors.width) {
                errorToast(errors.width);
            } else if (errors.width_unit) {
                errorToast(errors.width_unit);
            } else if (errors.height) {
                errorToast(errors.height);
            } else if (errors.height_unit) {
                errorToast(errors.height_unit);
            } else if (errors.volume) {
                errorToast(errors.volume);
            } else if (errors.volume_unit) {
                errorToast(errors.volume_unit);
            } else if (errors.additional_price) {
                errorToast(errors.additional_price);
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
                    <div class="card mb-3">
                        <div style="display: flex; align-items: center; gap: 15px;">
                            <img :src="imagePreview" alt="Product Image" width="150">
                            <div>
                                <h3 style="margin-bottom: 5px;">{{ product?.product_name }}</h3>
                                <p style="margin: 0;">Price(৳): {{ product?.price }}</p>
                            </div>
                        </div>
                    </div>

                    <hr>
                    <!-- Form to Add/Edit Product Specifications -->
                    <form @submit.prevent="saveSpecification">
                        <input type="hidden" v-model="form.product_id">

                        <div class="mb-3">
                            <label for="size">Size</label>
                            <input type="text" id="size" class="form-control" v-model="form.size">
                        </div>

                        <div class="mb-3">
                            <label for="color">Color</label>
                            <input type="text" id="color" class="form-control" v-model="form.color">
                        </div>

                        <div class="mb-3">
                            <label for="material">Material</label>
                            <input type="text" id="material" class="form-control" v-model="form.material">
                        </div>

                        <div class="mb-3">
                            <label for="weight">Weight</label>
                            <input type="number" step="0.01" id="weight" class="form-control" v-model="form.weight">
                            <select v-model="form.weight_unit" class="form-select mt-1">
                                <option value="kg">kg</option>
                                <option value="g">g</option>
                                <option value="lb">lb</option>
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="length">Length</label>
                                <input type="number" step="0.01" id="length" class="form-control" v-model="form.length">
                                <select v-model="form.length_unit" class="form-select mt-1">
                                    <option value="cm">cm</option>
                                    <option value="m">m</option>
                                    <option value="in">in</option>
                                    <option value="ft">ft</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="width">Width</label>
                                <input type="number" step="0.01" id="width" class="form-control" v-model="form.width">
                                <select v-model="form.width_unit" class="form-select mt-1">
                                    <option value="cm">cm</option>
                                    <option value="m">m</option>
                                    <option value="in">in</option>
                                    <option value="ft">ft</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="height">Height</label>
                                <input type="number" step="0.01" id="height" class="form-control" v-model="form.height">
                                <select v-model="form.height_unit" class="form-select mt-1">
                                    <option value="cm">cm</option>
                                    <option value="m">m</option>
                                    <option value="in">in</option>
                                    <option value="ft">ft</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="volume">Volume</label>
                                <input type="number" step="0.01" id="volume" class="form-control" v-model="form.volume">
                                <select v-model="form.volume_unit" class="form-select mt-1">
                                    <option value="cm3">cm³</option>
                                    <option value="m3">m³</option>
                                    <option value="L">L</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="additional_price">Additional Price</label>
                            <input type="number" step="0.01" id="additional_price" class="form-control"
                                v-model="form.additional_price">
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-primary w-100">Submit</button>
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
                                    {{ img_form.errors.image_path }}
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
