<script setup>
import { ref, computed } from "vue";
import { usePage, useForm, router } from "@inertiajs/vue3";

// ===================== brand List ===================== //
const list = usePage();
const products = list.props.products || [];

const Header = [{ text: "No", value: "no" },
{ text: "Image", value: "image" },
{ text: "Title", value: "title", sortable: true },
{ text: "Price", value: "price" },
{ text: "Product Name", value: "product_name" },
{ text: "Status", value: "status" },
{ text: "Action", value: "number" },
];

// Transform data for table
const Item = computed(() => {
    return list.props.slider_list?.map((slider, index) => ({
        no: index + 1,
        image: slider.image,
        title: slider.title,
        price: slider.price,
        product_name: slider.product.product_name,
        status: slider.status == 1 ? "Active" : "Inactive",
        id: slider.id,
        short_description: slider.short_description,
        product_id: slider.product_id,
    }));
});

// Search functionality
const searchValue = ref("");
const searchField = ref(["title"]);

//====================change status functionality============================//
const toggleStatus = (id, currentStatus) => {
    if (confirm("Are you sure you want to change status?")) {
        const newStatus = currentStatus === "Active" ? 0 : 1; // Toggle status

        router.post(route("change.product.slider.status", {
            id: id
        }), {
            status: newStatus
        }, {
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

// ===================== Add / Edit brand ===================== //
// Form for Add & Edit
const form = useForm({
    id: null, // For update
    title: "",
    price: "",
    status: "",
    product_id: "",
    image: "",
    description: "",
    logo: null,
    short_description: "",
});

// Function to set form data when editing
const setEditSlider = (slider) => {
    form.id = slider.id;
    form.title = slider.title;
    form.price = slider.price;
    form.status = slider.status === "Active" ? "1" : "0";
    form.product_id = slider.product_id || (slider.product ? slider.product.id : "");
    form.image = slider.image;
    form.short_description = slider.short_description;
};

//===================Function to Add or Update Section============================//
const SaveSlider = () => {
    const routeName = form.id ? "update.product.slider" : "save.product.slider";
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
            if (errors.product_id) {
                errorToast(errors.product_id);
            } else if (errors.title) {
                errorToast(errors.title);
            } else if (errors.price) {
                errorToast(errors.price);
            } else if (errors.status) {
                errorToast(errors.status);
            } else if (errors.image) {
                errorToast(errors.image);
            } else if (errors.short_description) {
                errorToast(errors.short_description);
            }
            else {
                errorToast(list.props.flash.message);
            }
        }
    });
};

//=========================section delete========================//
const selectedSliderId = ref(null);

//brand delete functionality
function showDeleteModal(id) {
    selectedSliderId.value = id;
    $('#sliderDltModal').modal('show');
}

function deleteSlider() {
    if (!selectedSliderId.value) return;

    router.delete(`/product/slider/delete/${selectedSliderId.value}`, {
        onSuccess: () => {
            if (list.props.flash.status === true) {
                successToast(list.props.flash.message);
            } else {
                errorToast(list.props.flash.message);
            }
            $('#list').click();
            $('#sliderDltModal').modal('hide');
            selectedSliderId.value = null;
        },
        onError: () => {
            errorToast("Failed to delete the slider. Please try again.");
        },
    });
}
</script>



<template>
    <div class="container-fluid pt-4 px-4">
        <div class="row g-4 mb-3">
            <div class="col-sm-12 col-xl-7">
                <div class="bg-light rounded h-100 p-4">
                    <div class="d-flex justify-content-between mb-3 align-item-center">
                        <div>
                            <h6>All Slider is here</h6>
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
                                <img :src="image ? `/storage/${image}` :
                                    'https://skala.or.id/wp-content/uploads/2024/01/dummy-post-square-1-1.jpg'"
                                    alt="Slider Image" style="width: 50px; height: 50px; object-fit: cover;"
                                    class="p-1">
                            </template>
                            <template #item-status="{ status, id }">
                                <button @click="toggleStatus(id, status)"
                                    :class="status === 'Active' ? 'btn btn-sm btn-primary' : 'btn btn-sm btn-outline-danger'"
                                    class="btn btn-sm">
                                    {{ status }}
                                </button>
                            </template>
                            <template #item-number="{ id, title, price, status, short_description, product_id }">
                                <div class="d-inline-flex gap-2">
                                    <button class="btn btn-sm btn-outline-primary"
                                        @click="setEditSlider({ id, title, price, status, short_description, product_id })">
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

            <!-- brand create or edit form -->
            <div class="col-sm-12 col-xl-5">
                <div class="bg-light rounded p-4">
                    <h6 class="mb-1">{{ form.id ? 'Edit Brand' : 'Add Brand' }}</h6>
                    <hr>
                    <form @submit.prevent="SaveSlider">
                        <!-- slider title -->
                        <div class="form-floating mb-3">
                            <input v-model="form.title" type="text" class="form-control" placeholder="Slider Title"
                                :class="{ 'is-invalid': form.errors.title }">
                            <label>Slider Title</label>
                            <div v-if="form.errors.title" class="invalid-feedback">
                                {{ form.errors.title }}
                            </div>
                        </div>

                        <!-- slider price -->
                        <div class="form-floating mb-3">
                            <input v-model="form.price" type="number" class="form-control" placeholder="Slider Price"
                                :class="{ 'is-invalid': form.errors.price }">
                            <label>Slider Price</label>
                            <div v-if="form.errors.price" class="invalid-feedback">
                                {{ form.errors.price }}
                            </div>
                        </div>

                        <!-- status -->
                        <div class="form-floating mb-3">
                            <select v-model="form.status" class="form-select"
                                :class="{ 'is-invalid': form.errors.status }">
                                <option value="">Select Status</option>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                            <label>Status</label>
                            <div v-if="form.errors.status" class="invalid-feedback">
                                {{ form.errors.status }}
                            </div>
                        </div>

                        <!-- select product id -->
                        <div class="form-floating mb-3">
                            <select v-model="form.product_id" class="form-select"
                                :class="{ 'is-invalid': form.errors.product_id }">
                                <option value="">Select Product</option>
                                <option v-for="product in products" :key="product.id" :value="product.id">
                                    {{ product.product_name }}
                                </option>
                            </select>
                            <label>Select Product</label>
                            <div v-if="form.errors.product_id" class="invalid-feedback">
                                {{ form.errors.product_id }}
                            </div>
                        </div>

                        <!-- short description -->
                        <div class="form-floating mb-3">
                            <textarea v-model="form.short_description" class="form-control"
                                placeholder="Short Description" style="height: 100px;"
                                :class="{ 'is-invalid': form.errors.short_description }"></textarea>
                            <label>Short Description</label>
                            <div v-if="form.errors.short_description" class="invalid-feedback">
                                {{ form.errors.short_description }}
                            </div>
                        </div>

                        <!-- slider image -->
                        <div class="mb-3">
                            <input type="file" class="form-control" @change="form.image = $event.target.files[0]">
                            <progress v-if="form.progress" :value="form.progress.percentage" max="100">
                                {{ form.progress.percentage }}%
                            </progress>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            {{ form.id ? 'Update Slider' : 'Save Slider' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- delete modal -->
    <div class="modal animated zoomIn" id="sliderDltModal" tabindex="-1" role="dialog" aria-bs-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
            <div class="modal-content" style="padding: 10px">
                <div class="modal-header justify-content-center">
                    <h4 class="text-warning d-flex align-items-center justify-content-center" id="sliderDltModalLabel"
                        style="width: 50px; height: 50px; border: 2px solid #ffc107; border-radius: 50%; font-size: 24px;">
                        <i class="fa fa-exclamation" aria-hidden="true"></i>
                    </h4>
                </div>

                <div class="modal-body">
                    <p>Are you sure you want to delete this slider? </p>

                    <div class="d-flex align-item-right justify-content-end">
                        <div> <button type="button" class="btn btn-outline-primary btn-sm me-2" data-bs-dismiss="modal"
                                id="close" aria-label="Close">NO</button></div>
                        <div><button type="button" @click.prevent="deleteSlider()"
                                class="btn btn-danger btn-sm">YES</button>
                        </div>
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
