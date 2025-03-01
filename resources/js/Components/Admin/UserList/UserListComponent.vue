<script setup>
import { ref, computed } from "vue";
import { Link, usePage, useForm, router } from "@inertiajs/vue3";

// ===================== Section List ===================== //
const list = usePage();

const Header = [
    { text: "No", value: "no" },
    { text: "Name", value: "cus_name", sortable: true },
    { text: "Email", value: "email", },
    { text: "Address", value: "cus_address" },
    { text: "Country", value: "cus_country" },
    { text: "City", value: "cus_city" },
    { text: "State", value: "cus_state" },
    { text: "ZIP", value: "cus_zip" },
    { text: "FAX", value: "cus_fax" },
];

// Transform data for table
const Item = computed(() => {
    return list.props.users?.map((user, index) => ({
        no: index + 1,
        cus_name: user.customer?.cus_name ?? "N/A",
        email: user.email,
        cus_address: user.customer?.cus_address ?? "N/A",
        cus_country: user.customer?.cus_country ?? "N/A",
        cus_city: user.customer?.cus_city ?? "N/A",
        cus_state: user.customer?.cus_state ?? "N/A",
        cus_zip: user.customer?.cus_zip ?? "N/A",
        cus_fax: user.customer?.cus_fax ?? "N/A",
    }));
});

// Search functionality
const searchValue = ref("");
const searchField = ref(["cus_name"]);
</script>



<template>
    <div class="container-fluid pt-4 px-4">
        <div class="row g-4 mb-3">
            <div class="col-sm-12 col-xl-12">
                <div class="bg-light rounded h-100 p-4">
                    <div class="d-flex justify-content-between mb-3 align-item-center">
                        <div>
                            <h6>All users is here</h6>
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
                        </EasyDataTable>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

