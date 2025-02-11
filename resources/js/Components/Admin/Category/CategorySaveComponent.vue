<script setup>
import { ref } from "vue";
import { Link, usePage, useForm } from "@inertiajs/vue3";
const list = usePage();
const sections = list.props.sections || [];
const parent_category = list.props.parent_category || [];
const category = list.props.category || [];
// ===================== Add / Edit category ===================== //
// Form for Add & Edit
const form = useForm({
    id: category.id || null,
    parent_id: category.parent_id || "",
    section_id: category.section_id || "",
    name: category.name || "",
    url: category.url || "",
    discount: category.discount || "",
    description: category.description || "",
    meta_title: category.meta_title || "",
    meta_description: category.meta_description || "", // FIXED
    meta_keywords: category.meta_keywords || "",
    status: category.status || "",
    image: category.image || "",
});

// Reactive property for dynamic image preview
const imagePreview = ref(
    category.image ? `/storage/${category.image}` : 'https://skala.or.id/wp-content/uploads/2024/01/dummy-post-square-1-1.jpg'
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

//===================Function to Add or Update Section============================//
const ChangeCategory = () => {
    const routeName = form.id ? "update.category" : "add.category";
    const requestData = form.id ? { id: form.id } : {};

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
            if (errors.parent_id) {
                errorToast(errors.parent_id);
            } else if (errors.section_id) {
                errorToast(errors.section_id);
            } else if (errors.name) {
                errorToast(errors.name);
            } else if (errors.discount) {
                errorToast(errors.discount);
            } else if (errors.description) {
                errorToast(errors.description);
            } else if (errors.meta_title) {
                errorToast(errors.meta_title);
            } else if (errors.meta_description) {
                errorToast(errors.meta_description);
            } else if (errors.meta_keywords) {
                errorToast(errors.meta_keywords);
            } else if (errors.status) {
                errorToast(errors.status);
            } else if (errors.image) {
                errorToast(errors.image);
            } else {
                errorToast(list.props.flash.message);
            }
        }
    });
};

</script>

<template>
    <!-- category add/edit form manage -->
    <div class="container-fluid pt-4 px-4">
        <div class="row g-4 mb-3">
            <div class="col-sm-12 col-xl-6">
                <div class="bg-light rounded p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="mb-1">{{ form.id ? "Edit Category" : "Add Category" }}</h6>
                        </div>
                        <div>
                            <Link class="btn btn-sm btn-primary" :href="route('show.category')"> Back to list</Link>
                        </div>
                    </div>
                    <hr>
                    <form @submit.prevent="ChangeCategory">
                        <div class="form-floating mb-3">
                            <input v-model="form.name" type="text" class="form-control" id="cat_name"
                                placeholder="Category Name" :class="{ 'is-invalid': form.errors.name }">
                            <label for="cat_name">Category Name</label>
                            <div v-if="form.errors.name" class="invalid-feedback">
                                {{ form.errors.name }}
                            </div>
                        </div>

                        <!-- Select Section -->
                        <div class="form-floating mb-3">
                            <select v-model="form.section_id" class="form-select" id="floatingSec"
                                :class="{ 'is-invalid': form.errors.section_id }">
                                <option value="">Select Section</option>
                                <option v-for="(section, index) in sections" :key="index" :value="section.id">
                                    {{ section?.sec_name }}
                                </option>
                            </select>
                            <label for="floatingSec">Select Section</label>
                            <div v-if="form.errors.section_id" class="invalid-feedback">
                                {{ form.errors.section_id }}
                            </div>
                        </div>

                        <!-- select parent category -->
                        <div class="form-floating mb-3">
                            <select v-model="form.parent_id" class="form-select" id="floatingP_id"
                                :class="{ 'is-invalid': form.errors.parent_id }">
                                <!-- <option>Select Parent Category</option> -->
                                <option v-for="(p_cateogy, index) in parent_category" :value="p_cateogy.id"
                                    :key="index">
                                    {{ p_cateogy?.name }}
                                </option>
                            </select>
                            <label for="floatingP_id">Select Parent Category</label>
                            <div v-if="form.errors.parent_id" class="invalid-feedback">
                                {{ form.errors.parent_id }}
                            </div>
                        </div>

                        <!-- category status -->
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

                        <!-- category description -->
                        <div class="form-floating mb-3">
                            <textarea v-model="form.description" class="form-control" placeholder="Description"
                                id="floatingDes" style="height: 100px;"
                                :class="{ 'is-invalid': form.errors.description }"></textarea>
                            <label for="floatingDes"> Description </label>
                            <div v-if="form.errors.description" class="invalid-feedback">
                                {{ form.errors.description }}
                            </div>
                        </div>

                        <!-- category discount -->
                        <div class="form-floating mb-3">
                            <input v-model="form.discount" type="text" class="form-control" id="floatingDis"
                                placeholder="Discount" :class="{ 'is-invalid': form.errors.discount }">
                            <label for="floatingDis">Discount</label>
                            <div v-if="form.errors.discount" class="invalid-feedback">
                                {{ form.errors.discount }}
                            </div>
                        </div>

                        <!-- meta title -->
                        <div class="form-floating mb-3">
                            <input v-model="form.meta_title" type="text" class="form-control" id="floatingMTitle"
                                placeholder="Meta Title" :class="{ 'is-invalid': form.errors.meta_title }">
                            <label for="floatingMTitle">Meta Title</label>
                            <div v-if="form.errors.meta_title" class="invalid-feedback">
                                {{ form.errors.meta_title }}
                            </div>
                        </div>

                        <!-- meta description -->
                        <div class="form-floating mb-3">
                            <textarea v-model="form.meta_description" class="form-control"
                                placeholder="Meta Description" id="floatingMDes" style="height: 100px;"
                                :class="{ 'is-invalid': form.errors.meta_description }"></textarea>
                            <label for="floatingMDes">Meta Description </label>
                            <div v-if="form.errors.meta_description" class="invalid-feedback">
                                {{ form.errors.meta_description }}
                            </div>
                        </div>

                        <!-- meta keywords -->
                        <div class="form-floating mb-3">
                            <input v-model="form.meta_keywords" type="text" class="form-control" id="floatingMkeywords"
                                placeholder="Meta Keywords" :class="{ 'is-invalid': form.errors.meta_keywords }">
                            <label for="floatingMkeywords"> Meta Keywords</label>
                            <div v-if="form.errors.meta_keywords" class="invalid-feedback">
                                {{ form.errors.meta_keywords }}
                            </div>
                        </div>

                        <!-- Category Image Field -->
                        <div class="">
                            <div class="mb-3"
                                style="width: 100px; height: 100px; overflow: hidden; background: #f8f9fa;">
                                <img :src="imagePreview" alt="Image Preview" class="img-fluid"
                                    style="width: 100%; height: 100%; object-fit: cover; border: 2px solid rgb(0 156 255 / 36%);">
                            </div>
                        </div>
                        <div class="mb-3">
                            <input type="file" class="form-control" id="productImage" @input="handleImageInput">
                            <progress v-if="form.progress" :value="form.progress.percentage" max="100">
                                {{ form.progress.percentage }}%
                            </progress>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            {{ form.id ? "Update Category" : "Save Category" }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.invalid-feedback {
    color: red;
    font-size: 0.9rem;
    margin-top: 0.25rem;
}
</style>
