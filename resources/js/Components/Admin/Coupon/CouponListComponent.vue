<script setup>
import { ref, computed } from "vue";
import { usePage, useForm, router } from "@inertiajs/vue3";

const list = usePage();
const authUser = usePage().props.auth.user;

//section with category
const categories = list.props.categories || [];
const vendor = list.props.vendor || [];

// ===================== coupon List ===================== //
const Header = [
    { text: "No", value: "no", fixed: true, width: 50 },
    { text: "Coupon", value: "coupon_code" },
    { text: "Added_by", value: "added_by" },
    { text: "Applicable Categories", value: "category_name", width: 200 },
    { text: "Type", value: "coupon_type", sortable: true, width: 80 },
    { text: "Discount", value: "coupon_discount", width: 75 },
    { text: "Start From", value: "coupon_start_date", width: 85 },
    { text: "End On", value: "coupon_end_date", width: 85 },
    { text: "Limit", value: "usage_limit", width: 50 },
    { text: "Minimum Order Amount", value: "minimum_order_amount" },
    { text: "Used Count", value: "used_count", width: 50 },
    { text: "Status", value: "coupon_status", width: 90 },
    { text: "Action", value: "action" },
];

// populated data for table
const Item = computed(() => {
    return list.props.coupons?.map((coupon, index) => ({
        no: index + 1,
        coupon_code: coupon.coupon_code,
        added_by: coupon.vendor.name,
        coupon_type: coupon.coupon_type,
        category_name: coupon.categories?.map(category => category.name).join(', ') || 'N/A',
        coupon_discount: coupon.coupon_discount,
        coupon_start_date: coupon.coupon_start_date,
        coupon_end_date: coupon.coupon_end_date,
        usage_limit: coupon.usage_limit,
        minimum_order_amount: coupon.minimum_order_amount,
        used_count: coupon.used_count,
        coupon_status: coupon.coupon_status == 1 ? true : false,
        id: coupon.id,
        category_id: coupon.categories?.map(category => category.id).join(', ') || 'N/A',
        vendor_id: coupon.vendor.id
    }));
});

// Search functionality
const searchValue = ref("");
const searchField = ref(["coupon_code"]);


// ===================== Add / Edit Coupon ===================== //
// Form for Add
const form = useForm({
    id: null,
    vendor_id: vendor.vendor_id,
    category_ids: [],
    coupon_code: "",
    coupon_type: "",
    coupon_discount: "",
    coupon_start_date: "",
    coupon_end_date: "",
    usage_limit: "",
    minimum_order_amount: "",
    coupon_status: "",
});

// Function to set form data when editing
const setEditCoupon = (coupon) => {
    form.id = coupon.id;
    form.category_ids = coupon.category_id.split(',').map(id => parseInt(id.trim()));
    form.vendor_id = coupon.vendor_id;
    form.coupon_code = coupon.coupon_code;
    form.coupon_type = coupon.coupon_type;
    form.coupon_discount = coupon.coupon_discount;
    form.coupon_start_date = coupon.coupon_start_date;
    form.coupon_end_date = coupon.coupon_end_date;
    form.usage_limit = coupon.usage_limit;
    form.minimum_order_amount = coupon.minimum_order_amount;
    form.coupon_status = coupon.coupon_status ?? 1;
};

//=================Function to Add or Update Coupon================//
const saveCoupon = () => {
    const routeName = form.id ? "update.coupon" : "add.coupon";
    const requestData = form.id ? { id: form.id } : {};

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
            if (errors.category_ids) {
                errorToast(errors.category_ids);
            } else if (errors.vendor_id) {
                errorToast(errors.vendor_id);
            } else if (errors.coupon_code) {
                errorToast(errors.coupon_code);
            } else if (errors.coupon_type) {
                errorToast(errors.coupon_type);
            } else if (errors.coupon_discount) {
                errorToast(errors.coupon_discount);
            } else if (errors.coupon_start_date) {
                errorToast(errors.coupon_start_date);
            } else if (errors.coupon_end_date) {
                errorToast(errors.coupon_end_date);
            } else if (errors.usage_limit) {
                errorToast(errors.usage_limit);
            } else if (errors.minimum_order_amount) {
                errorToast(errors.minimum_order_amount);
            } else if (errors.coupon_status) {
                errorToast(errors.coupon_status);
            } else {
                errorToast("Error saving coupon!");
            }
        }
    });
};


//====================change status functionality==================//
const toggleStatus = (id, currentStatus) => {
    if (confirm("Are you sure you want to change status?")) {
        const newStatus = currentStatus === true ? 0 : 1;

        router.post(route("change.coupon.status", { id: id }), {
            coupon_status: newStatus
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


//=========================coupon delete========================//
const selectedCouponId = ref(null);

//brand delete functionality
function showDeleteModal(id) {
    selectedCouponId.value = id;
    $('#couponDltModal').modal('show');
}

function deleteCoupon() {
    if (!selectedCouponId.value) return;

    router.delete(route('delete.coupon', selectedCouponId.value), {
        onSuccess: () => {
            if (list.props.flash.status === true) {
                successToast(list.props.flash.message);
            } else {
                errorToast(list.props.flash.message);
            }
            $('#list').click();
            $('#couponDltModal').modal('hide');
            selectedCouponId.value = null;
        },
        onError: () => {
            errorToast("Failed to delete the coupon. Please try again.");
        },
    });
}
</script>

<template>
    <div class="container-fluid pt-4 px-4">
        <div class="row g-4 mb-3">
            <!-- coupon list start -->
            <div class="col-sm-12 col-xl-8">
                <div class="bg-light rounded h-100 p-4">
                    <div class="d-flex justify-content-between mb-3 align-item-center">
                        <div>
                            <input placeholder="Search..." class="form-control w-auto form-control-sm" type="text"
                                v-model="searchValue">
                        </div>
                    </div>

                    <div class="table-responsive">
                        <EasyDataTable buttons-pagination alternating :headers="Header" :items="Item" border-cell
                            theme-color="#1d90ff" :rows-per-page="15" :search-field="searchField"
                            :search-value="searchValue">
                            <template #item-coupon_status="{ id, coupon_status }">
                                <button @click="toggleStatus(id, coupon_status)"
                                    :class="coupon_status === true ? 'btn btn-sm btn-primary' : 'btn btn-sm btn-outline-danger'"
                                    class="btn btn-sm">
                                    {{ coupon_status === true ? 'Active' : 'Inactive' }}
                                </button>
                            </template>
                            <template
                                #item-action="{ id, category_id, vendor_id, coupon_code, coupon_type, coupon_discount, coupon_start_date, coupon_end_date, usage_limit, minimum_order_amount, coupon_status }">
                                <button class="btn btn-sm btn-outline-primary me-2"
                                    @click="setEditCoupon({ id, category_id, vendor_id, coupon_code, coupon_type, coupon_discount, coupon_start_date, coupon_end_date, usage_limit, minimum_order_amount, coupon_status })">
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
            <!-- coupon list end -->

            <!-- add edit coupon start -->
            <div class="col-sm-12 col-xl-4">
                <div class="bg-light rounded h-100 p-4">
                    <h6>{{ form.id ? 'Edit' : 'Add' }} Coupon</h6>
                    <hr>

                    <form @submit.prevent="saveCoupon">
                        <!-- Coupon Code -->
                        <div class="mb-3">
                            <label for="coupon_code" class="form-label">Coupon Code</label>
                            <input id="coupon_code" type="text" name="coupon_code" class="form-control" required
                                v-model="form.coupon_code">
                        </div>

                        <div class="mb-3" v-if="authUser.type === 'superadmin'">
                            <label for="vandor" class="form-label">Select Vendor</label>
                            <select id="vendor" class="form-select" v-model="form.vendor_id">
                                <option value="" disabled>Select Vendor</option>
                                <option v-for="(vendor, index) in vendor" :key="index" :value="vendor.vendor_id">
                                    {{ vendor.name }}
                                </option>
                            </select>
                        </div>

                        <!-- Category Select -->
                        <div class="mb-3">
                            <label class="mb-2" for="category">Select Categories</label>
                            <select v-model="form.category_ids" id="category" class="form-select" multiple
                                style="max-height: 250px; overflow-y: auto;"
                                :class="{ 'is-invalid': form.errors.name }">
                                <option value="" disabled>Select Categories</option>
                                <!-- Loop through sections -->
                                <optgroup v-for="section in categories" :key="section.id" :label="section.sec_name">
                                    <!-- Loop through categories -->
                                    <template v-for="category in section.categories" :key="category.id">
                                        <option :value="category.id">
                                            -- {{ category.name }}
                                        </option>

                                        <!-- Loop through subcategories -->
                                        <option v-for="subcategory in category.subcategories" :key="subcategory.id"
                                            :value="subcategory.id">
                                            &nbsp; &nbsp; &nbsp; ---- {{ subcategory.name }}
                                        </option>
                                    </template>
                                </optgroup>
                            </select>

                            <div v-if="form.errors.category_id">
                                {{ form.errors.category_id }}
                            </div>
                        </div>


                        <!-- Coupon Type (Fixed / Percentage) -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="coupon_type" id="fixed"
                                        v-model="form.coupon_type" value="fixed">
                                    <label class="form-check-label" for="fixed">Fixed(TK)</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="coupon_type" id="percentage"
                                        value="percentage" v-model="form.coupon_type">
                                    <label class="form-check-label" for="percentage">Percentage(%)</label>
                                </div>
                            </div>
                        </div>

                        <!-- Coupon Discount -->
                        <div class="mb-3">
                            <label for="coupon_discount" class="form-label">Discount Amount</label>
                            <input id="coupon_discount" type="number" name="coupon_discount" class="form-control"
                                step="0.01" required v-model="form.coupon_discount">
                        </div>

                        <!-- Start Date & End Date -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="coupon_start_date" class="form-label">Start Date</label>
                                    <input id="coupon_start_date" type="date" name="coupon_start_date"
                                        class="form-control" required v-model="form.coupon_start_date">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="coupon_end_date" class="form-label">End Date</label>
                                    <input id="coupon_end_date" type="date" name="coupon_end_date" class="form-control"
                                        required v-model="form.coupon_end_date">
                                </div>
                            </div>
                        </div>

                        <!-- Usage Limit -->
                        <div class="row">
                            <div class="mb-3 col-md-6">
                                <label for="usage_limit" class="form-label">Usage Limit</label>
                                <input id="usage_limit" type="number" name="usage_limit" class="form-control"
                                    v-model="form.usage_limit">
                            </div>

                            <!-- Minimum Order Amount -->
                            <div class="mb-3 col-md-6">
                                <label for="minimum_order_amount" class="form-label">Min Order Amount</label>
                                <input id="minimum_order_amount" type="number" name="minimum_order_amount"
                                    class="form-control" step="1" v-model="form.minimum_order_amount">
                            </div>
                        </div>


                        <!-- Coupon Status -->
                        <div class="mb-3 form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="coupon_status"
                                v-model="form.coupon_status">
                            <p>Current Status: {{ form.coupon_status === true ? 'Active' : 'Inactive' }}</p>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-primary w-100">{{ form.id ? 'Update Coupon' : 'Save Coupon'
                            }}</button>
                    </form>
                </div>
            </div>

            <!-- add edit coupon end -->
        </div>
    </div>


    <!-- delete modal -->
    <div class="modal animated zoomIn" id="couponDltModal" tabindex="-1" role="dialog" aria-bs-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
            <div class="modal-content" style="padding: 10px">
                <div class="modal-header justify-content-center">
                    <h4 class="text-warning d-flex align-items-center justify-content-center" id="couponDltModalLabel"
                        style="width: 50px; height: 50px; border: 2px solid #ffc107; border-radius: 50%; font-size: 24px;">
                        <i class="fa fa-exclamation" aria-hidden="true"></i>
                    </h4>
                </div>

                <div class="modal-body">
                    <p>Are you sure you want to delete this coupon? </p>

                    <div class="d-flex align-item-right justify-content-end">
                        <div> <button type="button" class="btn btn-outline-primary btn-sm me-2" data-bs-dismiss="modal"
                                id="close" aria-label="Close">NO</button></div>
                        <div><button type="button" @click.prevent="deleteCoupon()"
                                class="btn btn-danger btn-sm">YES</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>


<style scoped>
/* Custom scrollbar for EasyDataTable */
.table-responsive ::-webkit-scrollbar {
    height: 15px;
}

.table-responsive ::-webkit-scrollbar-track {
    background: #f1f1f1;
}

.table-responsive ::-webkit-scrollbar-thumb {
    background: #3db5ff;
    border-radius: 10px;
}

.table-responsive ::-webkit-scrollbar-thumb:hover {
    background: #009CFF;
}
</style>