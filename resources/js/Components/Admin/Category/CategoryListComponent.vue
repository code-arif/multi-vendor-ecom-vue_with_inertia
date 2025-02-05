<script setup>
import { ref, computed } from "vue";
import { Link, usePage, router } from "@inertiajs/vue3";

// ===================== Section List ===================== //
const list = usePage();

const Header = [
    { text: "No", value: "no"},
    { text: "Image", value: "image"},
    { text: "Name", value: "name", sortable: true},
    { text: "Parent Category", value: "p_category", sortable: true},
    { text: "Section", value: "section", sortable: true,},
    { text: "Status", value: "status"},
    { text: "URL", value: 'url' },
    { text: "Meta Title", value: "meta_title" },
    { text: "Action", value: "number"},
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
            <div class="col-sm-12 col-xl-12">
                <div class="bg-light rounded h-100 p-4">
                    <div class="d-flex justify-content-between mb-3 align-item-center">
                        <div>
                            <input placeholder="Search..." class="form-control w-auto form-control-sm" type="text"
                                v-model="searchValue">
                        </div>
                        <div>
                            <Link :href="route('show.save.category')" class="btn btn-sm btn-primary"> Add Category
                            </Link>
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
                                #item-number="{ id }">
                                <div class="d-flex align-items-center">
                                    <Link class="btn btn-sm btn-outline-primary me-2" :href="route('show.save.category', {id: id})">
                                    <i class="fa fa-edit"></i>
                                    </Link>
                                    <button class="btn btn-sm btn-outline-danger" @click="showDeleteModal(id)">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </div>
                            </template>
                        </EasyDataTable>
                    </div>
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
                        <div> <button type="button" class="btn btn-outline-primary btn-sm me-2" data-bs-dismiss="modal"
                                id="close" aria-label="Close">NO</button></div>
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
