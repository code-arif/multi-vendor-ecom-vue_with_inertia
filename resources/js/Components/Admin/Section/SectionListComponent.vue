<script setup>
import { ref, computed } from "vue";
import { Link, usePage, useForm, router } from "@inertiajs/vue3";

// ===================== Section List ===================== //
const list = usePage();

const Header = [
    { text: "No", value: "no" },
    { text: "Name", value: "name", sortable: true },
    { text: "Status", value: "status" },
    { text: "Action", value: "number" },
];

// Transform data for table
const Item = computed(() => {
    return list.props.sections?.map((section, index) => ({
        no: index + 1,
        name: section.sec_name,
        status: section.status == 1 ? "Active" : "Inactive",
        id: section.id,
    }));
});

// Search functionality
const searchValue = ref("");
const searchField = ref(["name"]);

//====================change status functionality============================//
const toggleStatus = (id, currentStatus) => {
    if (confirm("Are you sure you want to change status?")) {
        const newStatus = currentStatus === "Active" ? 0 : 1; // Toggle status

        router.post(route("change.section.status", { id: id }), { status: newStatus }, {
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

// Form for Add & Edit
const form = useForm({
    id: null, // For update
    sec_name: "",
    status: "",
});

// ===================== Add / Edit Section ===================== //
// Function to set form data when editing
const setEditSection = (section) => {
    form.id = section.id;
    form.sec_name = section.name;
    form.status = section.status === "Active" ? "1" : "0";
};

//===================Function to Add or Update Section============================//
const ChangeSection = () => {
    if (form.id) {
        // Update Section
        form.post(route("update.section", { id: form.id }), {
            preserveScroll: true,
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
                if (errors.sec_name) {
                    errorToast(errors.sec_name);
                } else if (errors.status) {
                    errorToast(errors.status);
                } else {
                    errorToast(list.props.flash.message);
                }
            }
        });
    } else {
        // Add New Section
        form.post(route("add.section"), {
            preserveScroll: true,
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
                if (errors.sec_name) {
                    errorToast(errors.sec_name);
                } else if (errors.status) {
                    errorToast(errors.status);
                } else {
                    errorToast(list.props.flash.message);
                }
            }
        });
    }
};

//=========================section delete========================//
const selectedSecId = ref(null);

//brand delete functionality
function showDeleteModal(id) {
    selectedSecId.value = id;
    $('#secDltModal').modal('show');
}

function deleteSection() {
    if (!selectedSecId.value) return;

    router.delete(`/section/delete/${selectedSecId.value}`, {
        onSuccess: () => {
            if (list.props.flash.status === true) {
                successToast(list.props.flash.message);
            } else {
                errorToast(list.props.flash.message);
            }
            $('#list').click();
            $('#secDltModal').modal('hide');
            selectedSecId.value = null;
        },
        onError: () => {
            errorToast("Failed to delete the section. Please try again.");
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
                            <h6>All sections is here</h6>
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
                            <template #item-status="{ status, id }">
                                <button @click="toggleStatus(id, status)"
                                    :class="status === 'Active' ? 'btn btn-sm btn-primary' : 'btn btn-sm btn-outline-danger'"
                                    class="btn btn-sm">
                                    {{ status }}
                                </button>
                            </template>
                            <template #item-number="{ id, name, status }">
                                <button class="btn btn-sm btn-outline-primary me-2"
                                    @click="setEditSection({ id, name, status })">
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

            <div class="col-sm-12 col-xl-5">
                <div class="bg-light rounded p-4">
                    <h6 class="mb-1">{{ form.id ? "Edit Section" : "Add Section" }}</h6>
                    <hr>
                    <form @submit.prevent="ChangeSection">
                        <div class="form-floating mb-3">
                            <input v-model="form.sec_name" type="text" class="form-control" id="floatingName"
                                placeholder="Section Name" :class="{ 'is-invalid': form.errors.sec_name }">
                            <label for="floatingName">Section Name</label>
                            <div v-if="form.errors.sec_name" class="invalid-feedback">
                                {{ form.errors.sec_name }}
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
                        <button type="submit" class="btn btn-primary w-100">
                            {{ form.id ? "Update Section" : "Save Change" }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- delete modal -->
    <div class="modal animated zoomIn" id="secDltModal" tabindex="-1" role="dialog" aria-bs-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
            <div class="modal-content" style="padding: 10px">
                <div class="modal-header justify-content-center">
                    <h4 class="text-warning d-flex align-items-center justify-content-center" id="secDltModalLabel"
                        style="width: 50px; height: 50px; border: 2px solid #ffc107; border-radius: 50%; font-size: 24px;">
                        <i class="fa fa-exclamation" aria-hidden="true"></i>
                    </h4>
                </div>

                <div class="modal-body">
                    <p>Are you sure you want to delete this section? </p>

                    <div class="d-flex align-item-right justify-content-end">
                        <div> <button type="button" class="btn btn-outline-primary btn-sm me-2" data-bs-dismiss="modal" id="close"
                                aria-label="Close">NO</button></div>
                        <div><button type="button" @click.prevent="deleteSection()"
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
