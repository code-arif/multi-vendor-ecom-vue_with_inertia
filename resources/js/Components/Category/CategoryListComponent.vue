<script setup>
import { ref, computed } from "vue";
import { Link, usePage, useForm, router } from "@inertiajs/vue3";

// ===================== Section List ===================== //
const list = usePage();
const sections = list.props.sections || [];

const Header = [
    { text: "No", value: "no", width: 8 },
    { text: "Image", value: "image", width: 20 },
    { text: "Name", value: "name", sortable: true, width: 30 },
    { text: "Parent Category", value: "p_category", sortable: true, width: 70 },
    { text: "Section", value: "section", sortable: true, width: 50 },
    { text: "Status", value: "status", width: 30 },
    { text: "Action", value: "number", width: 10 },
];

// Transform data for table
const Item = computed(() => {
    return list.props.categories?.map((category, index) => ({
        no: index + 1,
        image: category.image,
        name: category.name,
        p_category: category.parent?.name || "N/A",  // Optional chaining used
        section: category.section?.sec_name || "N/A",
        url: category.url,
        status: category.status == 1 ? "Active" : "Inactive",
        id: category.id,
        section_id: category.section_id,
        parent_id: category.parent_id,
        description: category.description,
        discount: category.discount,
        meta_title: category.meta_title,
        meta_description: category.meta_description,
        meta_keyword: category.meta_keyword,
    }));
});

// Search functionality
const searchValue = ref("");
const searchField = ref(["name", "p_category", "section", "url", "status"]);

//====================change status functionality============================//
const toggleStatus = (id, currentStatus) => {
    if (confirm("Are you sure you want to change status?")) {
        const newStatus = currentStatus === "Active" ? 0 : 1; // Toggle status

        router.post(route("change.category.status", { id: id }), { status: newStatus }, {
            preserveScroll: true,
            onSuccess: () => {
                successToast(list.props.flash.message);
            },
            onError: () => {
                errorToast(list.props.flash.message);
            }
        });
    }
}

// ===================== Add / Edit category ===================== //
// Form for Add & Edit
const form = useForm({
    id: null, // For update
    parent_id: "",
    section_id: "",
    name: "",
    url: "",
    discount: "",
    description: "",
    meta_title: "",
    meta_description: "",
    meta_keywords: "",
    status: "",
    image: ""
});

// Function to set form data when editing
const setEditCategory = (category) => {
    form.id = category.id;
    form.parent_id = category.parent_id;
    form.section_id = category.section_id;
    form.name = category.name;
    form.url = category.url;
    form.discount = category.discount;
    form.description = category.description;
    form.meta_title = category.meta_title;
    form.meta_description = category.meta_description;
    form.meta_keywords = category.meta_keywords;
    form.image = category.image;
    form.status = category.status === "Active" ? "1" : "0";
};

//===================Function to Add or Update Section============================//
const ChangeCategory = () => {
    if (form.id) {
        // Update Section
        form.post(route("update.category", { id: form.id }), {
            onSuccess: () => {
                if (list.props.flash.status === true) {
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
                } else if (errors.url) {
                    errorToast(errors.url);
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
    } else {
        // Add New Section
        form.post(route("add.category"), {
            onSuccess: () => {
                if (list.props.flash.status === true) {
                    successToast(list.props.flash.message);
                    form.reset();
                } else {
                    errorToast(list.props.flash.message);
                }
            },
            onError: (errors) => {
                // console.log(errors); // Log errors for debugging
                if (errors.parent_id) {
                    errorToast(errors.parent_id);
                } else if (errors.section_id) {
                    errorToast(errors.section_id);
                } else if (errors.name) {
                    errorToast(errors.name);
                } else if (errors.url) {
                    errorToast(errors.url);
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
    }
};

//=========================section delete========================//
const selectedCatId = ref(null);

//brand delete functionality
function showDeleteModal(id) {
    selectedCatId.value = id;
    $('#catDltModal').modal('show');
}

function deleteCategory() {
    if (!selectedCatId.value) return;

    router.delete(`/category/delete/${selectedCatId.value}`, {
        onSuccess: () => {
            if (list.props.flash.status === true) {
                successToast(list.props.flash.message);
            } else {
                errorToast(list.props.flash.message);
            }
            $('#list').click();
            $('#catDltModal').modal('hide');
            selectedCatId.value = null;
        },
        onError: () => {
            errorToast("Failed to delete the category. Please try again.");
        },
    });
}
</script>

<template>
    <div class="container-fluid pt-4 px-4">
        <div class="row g-4 mb-3">

            <!-- category list manage -->
            <div class="col-sm-12 col-xl-8">
                <div class="bg-light rounded h-100 p-4">
                    <div class="d-flex justify-content-between mb-3 align-item-center">
                        <div>
                            <h6>All categories is here</h6>
                        </div>
                        <div>
                            <input placeholder="Search..." class="form-control w-auto form-control-sm" type="text"
                                v-model="searchValue">
                        </div>
                    </div>

                    <div class="table-responsive">
                        <EasyDataTable buttons-pagination alternating :headers="Header" :items="Item" border-cell
                            theme-color="#1d90ff" :rows-per-page="15" :search-field="searchField"
                            :search-value="searchValue">
                            <template #item-image="{ image }">
                                <img :src="image ? `/storage/${image}` : 'https://skala.or.id/wp-content/uploads/2024/01/dummy-post-square-1-1.jpg'"
                                    alt="Category image" style="width: 50px; height: 50px; object-fit: cover;"
                                    class="p-1">
                            </template>
                            <template #item-status="{ status, id }">
                                <button @click="toggleStatus(id, status)"
                                    :class="status === 'Active' ? 'btn btn-sm btn-primary' : 'btn btn-sm btn-outline-danger'"
                                    class="btn btn-sm">
                                    {{ status }}
                                </button>
                            </template>
                            <template
                                #item-number="{ id, name, status, parent_id, section_id, discount, url, description, meta_title, meta_description, meta_keywords }">
                                <div class="d-flex align-items-center">
                                    <button class="btn btn-sm btn-outline-primary me-2"
                                        @click="setEditCategory({ id, name, status, parent_id, section_id, discount, url, description, meta_title, meta_description, meta_keywords })">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger" @click="showDeleteModal(id)">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </div>
                            </template>
                        </EasyDataTable>
                    </div>
                </div>
            </div>

            <!-- category add/edit form manage -->
            <div class="col-sm-12 col-xl-4">
                <div class="bg-light rounded p-4">
                    <h6 class="mb-1">{{ form.id ? "Edit Category" : "Add Category" }}</h6>
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
                                <option value="Null">Main Category</option>
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
                                id="floatingDes" style="height: 100px; resize: none;"
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

                        <!-- URL -->
                        <div class="form-floating mb-3">
                            <input v-model="form.url" type="text" class="form-control" id="floatingUrl"
                                placeholder="Url" :class="{ 'is-invalid': form.errors.url }">
                            <label for="floatingUrl">URL</label>
                            <div v-if="form.errors.url" class="invalid-feedback">
                                {{ form.errors.url }}
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
                                placeholder="Meta Description" id="floatingMDes" style="height: 100px; resize: none;"
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
                        <div class="mb-3"> <input type="file" class="form-control" id="productImage"
                                @input="form.image = $event.target.files[0]">
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

        <!-- delete modal -->
        <div class="modal animated zoomIn" id="catDltModal" tabindex="-1" role="dialog" aria-bs-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
            <div class="modal-content" style="padding: 10px">
                <div class="modal-header justify-content-center">
                    <h4 class="text-warning d-flex align-items-center justify-content-center" id="catDltModalLabel"
                        style="width: 50px; height: 50px; border: 2px solid #ffc107; border-radius: 50%; font-size: 24px;">
                        <i class="fa fa-exclamation" aria-hidden="true"></i>
                    </h4>
                </div>

                <div class="modal-body">
                    <p>Are you sure you want to delete this category? </p>

                    <div class="d-flex align-item-right justify-content-end">
                        <div> <button type="button" class="btn btn-outline-primary btn-sm me-2" data-bs-dismiss="modal" id="close"
                                aria-label="Close">NO</button></div>
                        <div><button type="button" @click.prevent="deleteCategory()"
                                class="btn btn-danger btn-sm">YES</button></div>
                    </div>
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
