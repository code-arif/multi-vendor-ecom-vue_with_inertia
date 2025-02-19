<script setup>
import { ref, computed } from "vue";
import { Link, usePage, router } from "@inertiajs/vue3";
import moment from "moment";

// ===================== Section List ===================== //
const list = usePage();

const Header = [
    { text: "No", value: "no", width: 40 },
    { text: "Image", value: "image" },
    { text: "Name", value: "name", sortable: true, width: 100 },
    { text: "Price", value: "price" },
    { text: "Quantity", value: "quantity", width: 70 },
    { text: "Status", value: "status", sortable: true },
    { text: "Remark", value: "remark", sortable: true },
    { text: "Category", value: "category", sortable: true },
    { text: "Is Featured", value: "is_featured", sortable: true },
    { text: "Brand", value: "brand", sortable: true },
    { text: "Added By", value: "added_by", width: 100 },
    { text: "Type", value: "type" },
    { text: "Added Date", value: "added_date", width: 100 },
    { text: "Updated Date", value: "updated_date", width: 100 },
    { text: "Action", value: "number", width: 170 },
];

// Transform data for table
const Item = computed(() => {
    return list.props.products?.map((product, index) => ({
        no: index + 1,
        image: product.image,
        name: product.product_name,
        price: product.price,
        quantity: product.stock_quantity,
        status: product.status == 1 ? "Active" : "Inactive",
        remark: product.remark,
        category: product.category.name || "N/A",
        is_featured: product.is_featured == 1 ? "Featured" : "Not Featured",
        brand: product.brand?.name || "N/A",
        added_by: product.admin?.name ?? product.vendor?.name ?? "N/A",
        type: product.admin_type ?? "N/A",
        added_date: product.created_at,
        updated_date: product.updated_at,
        id: product.id,
        isVendor: product.admin_type == "vendor",
        isAdmin: product.admin_type == "admin"
    }));
});

const typeStyle = (type) => {
    const styles = {
        superadmin: "background-color: red; color: white; padding: 3px 6px; border-radius: 5px;",
        admin: "background-color: blue; color: white; padding: 3px 6px; border-radius: 5px;",
        subadmin: "background-color: yellow; color: black; padding: 3px 6px; border-radius: 5px;",
        vendor: "background-color: green; color: white; padding: 3px 6px; border-radius: 5px;",
    };
    return styles[type.toLowerCase()] || "background-color: gray; color: white; padding: 3px 6px; border-radius: 5px;";
};

// Search functionality
const searchValue = ref("");
const searchField = ref(["name", "category", "section", "status", "remark", "brand", "added_by", "type"]);

//====================change status functionality============================//
const toggleStatus = (id, currentStatus) => {
    if (confirm("Are you sure you want to change status?")) {
        const newStatus = currentStatus === "Active" ? 0 : 1; // Toggle status

        router.post(route("change.product.status", { id: id }), { status: newStatus }, {
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
const selectedProductId = ref(null);

//brand delete functionality
function showDeleteModal(id) {
    selectedProductId.value = id;
    $('#productDltModal').modal('show');
}

function deleteProduct() {
    if (!selectedProductId.value) return;

    router.delete(`/product/delete/${selectedProductId.value}`, {
        onSuccess: () => {
            if (list.props.flash.status === true) {
                successToast(list.props.flash.message);
            } else {
                errorToast(list.props.flash.message);
            }
            $('#list').click();
            $('#productDltModal').modal('hide');
            selectedProductId.value = null;
        },
        onError: () => {
            errorToast("Failed to delete the product. Please try again.");
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
                            <Link :href="route('show.save.product')" class="btn btn-sm btn-primary"> Add Product
                            </Link>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <EasyDataTable buttons-pagination alternating :headers="Header" :items="Item" border-cell
                            theme-color="#1d90ff" :rows-per-page="15" :search-field="searchField"
                            :search-value="searchValue">

                            <template #item-added_by="{ id, isVendor, added_by }">
                                <template v-if="isVendor">
                                    <Link class="btn btn-sm btn-outline-primary"
                                        :href="route('show.vendor.details', { id: id })">
                                    {{ added_by }}
                                    </Link>
                                </template>
                                <template v-else>
                                    {{ added_by }}
                                </template>
                            </template>

                            <template #item-is_featured="{ is_featured }">
                                <span :class="is_featured === 'Featured' ? 'text-primary' : 'text-danger'">{{
                                    is_featured }}</span>
                            </template>

                            <template #item-added_date="{ added_date }">
                                {{ moment(added_date).format("MMM Do YYYY, h:mm A") }}
                            </template>

                            <template #item-updated_date="{ updated_date }">
                                {{ moment(updated_date).format("MMM Do YYYY, h:mm A") }}
                            </template>

                            <template #item-type="{ type }">
                                <span :style="typeStyle(type)">
                                    {{ type }}
                                </span>
                            </template>

                            <template #item-image="{ image }">
                                <img :src="image ? `/storage/${image}` : 'https://skala.or.id/wp-content/uploads/2024/01/dummy-post-square-1-1.jpg'"
                                    alt="Product image" style="width: 50px; height: 50px; object-fit: cover;"
                                    class="p-1">
                            </template>

                            <template #item-status="{ status, id }">
                                <button @click="toggleStatus(id, status)"
                                    :class="status === 'Active' ? 'btn btn-sm btn-primary' : 'btn btn-sm btn-outline-danger'"
                                    class="btn btn-sm">
                                    {{ status }}
                                </button>
                            </template>

                            <template #item-number="{ id }">
                                <div class="d-flex align-items-center">
                                    <Link class="btn btn-sm btn-outline-success me-2"
                                        :href="route('show.product.details', { id: id })">
                                    <i class="fa fa-eye"></i>
                                    </Link>
                                    <Link class="btn btn-sm btn-outline-info me-2"
                                        :href="route('show.save.product.details', { id: id })">
                                    <i class="fa fa-plus"></i>
                                    </Link>
                                    <Link class="btn btn-sm btn-outline-info me-2"
                                        :href="route('show.product.specification', { id: id })">
                                    <i class="fa fa-bars" aria-hidden="true"></i>
                                    </Link>
                                    <Link class="btn btn-sm btn-outline-primary me-2"
                                        :href="route('show.save.product', { id: id })">
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
    <div class="modal animated zoomIn" id="productDltModal" tabindex="-1" role="dialog" aria-bs-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
            <div class="modal-content" style="padding: 10px">
                <div class="modal-header justify-content-center">
                    <h4 class="text-warning d-flex align-items-center justify-content-center" id="productDltModalLabel"
                        style="width: 50px; height: 50px; border: 2px solid #ffc107; border-radius: 50%; font-size: 24px;">
                        <i class="fa fa-exclamation" aria-hidden="true"></i>
                    </h4>
                </div>

                <div class="modal-body">
                    <p>Are you sure you want to delete this product? </p>

                    <div class="d-flex align-item-right justify-content-end">
                        <div> <button type="button" class="btn btn-outline-primary btn-sm me-2" data-bs-dismiss="modal"
                                id="close" aria-label="Close">NO</button></div>
                        <div><button type="button" @click.prevent="deleteProduct()"
                                class="btn btn-danger btn-sm">YES</button></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped></style>
