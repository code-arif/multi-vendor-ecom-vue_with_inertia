<script setup>
import { ref, computed } from "vue";
import { usePage, useForm, router } from "@inertiajs/vue3";

// ===================== brand List ===================== //
const list = usePage();

const Header = [{ text: "No",value: "no"},
{text: "Logo",value: "logo"},
{text: "Name",value: "name",sortable: true},
{text: "Status",value: "status"},
{text: "Action",value: "number"},
];

// Transform data for table
const Item = computed(() => {
    return list.props.brand?.map((brand, index) => ({
        no: index + 1,
        logo: brand.logo,
        name: brand.name,
        status: brand.status == 1 ? "Active" : "Inactive",
        id: brand.id,
        description: brand.description,
    }));
});

// Search functionality
const searchValue = ref("");
const searchField = ref(["name"]);

//====================change status functionality============================//
const toggleStatus = (id, currentStatus) => {
    if (confirm("Are you sure you want to change status?")) {
        const newStatus = currentStatus === "Active" ? 0 : 1; // Toggle status

        router.post(route("change.brand.status", {
            id: id
        }), {
            status: newStatus
        }, {
            preserveScroll: true, // Keeps page position
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
    name: "",
    status: "",
    logo: null,
    description: "",

});

// Function to set form data when editing
const setEditBrand = (brand) => {
    form.id = brand.id;
    form.name = brand.name;
    form.status = brand.status === "Active" ? "1" : "0";
    form.logo = brand.logo;
    form.description = brand.description;
};

//===================Function to Add or Update Section============================//
const ChangeBrnad = () => {
    const routeName = form.id ? "update.brand" : "add.brand";
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
            // console.log(errors); // Log errors for debugging
            if (errors.name) {
                errorToast(errors.name);
            } else if (errors.status) {
                errorToast(errors.status);
            } else if (errors.logo) {
                errorToast(errors.logo);
            } else if (errors.description) {
                errorToast(errors.description);
            } else {
                errorToast(list.props.flash.message);
            }
        }

    });
};

//=========================section delete========================//
const selectedBrandId = ref(null);

//brand delete functionality
function showDeleteModal(id) {
    selectedBrandId.value = id;
    $('#brandDltModal').modal('show');
}

function deleteBrand() {
    if (!selectedBrandId.value) return;

    router.delete(`/brand/delete/${selectedBrandId.value}`, {
        onSuccess: () => {
            if (list.props.flash.status === true) {
                successToast(list.props.flash.message);
            } else {
                errorToast(list.props.flash.message);
            }
            $('#list').click();
            $('#brandDltModal').modal('hide');
            selectedBrandId.value = null;
        },
        onError: () => {
            errorToast("Failed to delete the brand. Please try again.");
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
                            <h6>All Brand is here</h6>
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
                            <template #item-logo="{ logo }">
                                <img :src="logo ? `/storage/${logo}` :
                                    'https://skala.or.id/wp-content/uploads/2024/01/dummy-post-square-1-1.jpg'"
                                    alt="Brand Logo" style="width: 50px; height: 50px; object-fit: cover;" class="p-1">
                            </template>
                            <template #item-status="{ status, id }">
                                <button @click="toggleStatus(id, status)"
                                    :class="status === 'Active' ? 'btn btn-sm btn-primary' : 'btn btn-sm btn-outline-danger'"
                                    class="btn btn-sm">
                                    {{ status }}
                                </button>
                            </template>
                            <template #item-number="{ id, name, status, description }">
                                <button class="btn btn-sm btn-outline-primary me-2"
                                    @click="setEditBrand({ id, name, status, description })">
                                    <i class="fa fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger" @click="showDeleteModal(id)">
                                    <i class="fa fa-trash"></i>
                                </button>
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
                    <form @submit.prevent="ChangeBrnad">
                        <div class="form-floating mb-3">
                            <input v-model="form.name" type="text" class="form-control" id="floatingName"
                                placeholder="Brand Name" :class="{ 'is-invalid': form.errors.name }">
                            <label for="floatingName">Brand Name</label>
                            <div v-if="form.errors.name" class="invalid-feedback">
                                {{ form.errors.name }}
                            </div>
                        </div>

                        <div class="form-floating mb-3">
                            <select v-model="form.status" class="form-select" id="floatingSelect"
                                :class="{ 'is-invalid': form.errors.status }">
                                <option>Select Status</option>
                                <option value=1>Active</option>
                                <option value=0>Inactive</option>
                            </select>
                            <label for="floatingSelect">Status</label>
                            <div v-if="form.errors.status" class="invalid-feedback">
                                {{ form.errors.status }}
                            </div>
                        </div>
                        <!-- brand description -->
                        <div class="form-floating mb-3">
                            <textarea v-model="form.description" class="form-control" placeholder="Description"
                                id="floatingDes" style="height: 100px;"
                                :class="{ 'is-invalid': form.errors.description }"></textarea>
                            <label for="floatingDes"> Description </label>
                            <div v-if="form.errors.description" class="invalid-feedback">
                                {{ form.errors.description }}
                            </div>
                        </div>

                        <div class="mb-3">
                            <input type="file" class="form-control" @input="form.logo = $event.target.files[0]">
                            <progress v-if="form.progress" :value="form.progress.percentage" max="100">
                                {{ form.progress.percentage }}%
                            </progress>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            {{ form.id ? 'Update Section' : 'Save Change' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- delete modal -->
    <div class="modal animated zoomIn" id="brandDltModal" tabindex="-1" role="dialog" aria-bs-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
            <div class="modal-content" style="padding: 10px">
                <div class="modal-header justify-content-center">
                    <h4 class="text-warning d-flex align-items-center justify-content-center" id="brandDltModalLabel"
                        style="width: 50px; height: 50px; border: 2px solid #ffc107; border-radius: 50%; font-size: 24px;">
                        <i class="fa fa-exclamation" aria-hidden="true"></i>
                    </h4>
                </div>

                <div class="modal-body">
                    <p>Are you sure you want to delete this brand? </p>

                    <div class="d-flex align-item-right justify-content-end">
                        <div> <button type="button" class="btn btn-outline-primary btn-sm me-2" data-bs-dismiss="modal"
                                id="close" aria-label="Close">NO</button></div>
                        <div><button type="button" @click.prevent="deleteBrand()"
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
