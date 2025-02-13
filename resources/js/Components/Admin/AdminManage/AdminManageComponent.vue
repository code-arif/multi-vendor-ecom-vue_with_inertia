<script setup>
import { ref, computed } from "vue";
import { Link, usePage, router } from "@inertiajs/vue3";

const list = usePage();

const Header = [
    { text: 'No', value: 'no' },
    { text: "Image", value: "image" },
    { text: "Name", value: "name", sortable: true },
    { text: "Type", value: "type", sortable: true },
    { text: "Email", value: "email" },
    { text: "Mobile", value: "mobile" },
    { text: "Address", value: "address", width: 200 },
    { text: "Zip", value: "zip", },
    { text: "Status", value: "status" },
    { text: "Action", value: "number" },
];

// Transform data for table
const Item = computed(() => {
    return list.props.admins?.map((admin, index) => ({
        no: index + 1,
        image: admin.image,
        name: admin.name,
        type: admin.type,
        email: admin.email,
        mobile: admin.mobile,
        address: admin.address,
        zip: admin.zip,
        status: admin.status == 1 ? "Active" : "Inactive",
        id: admin.id,
        isVendor: admin.type === "vendor"
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
const searchField = ref(["name", "mobile", "address", "zip"]);

//change status functionality
const toggleStatus = (id, currentStatus) => {
    if (confirm("Are you sure you want to change status?")) {
        const newStatus = currentStatus === "Active" ? 0 : 1; // Toggle status

        router.post(route("change.status", { id: id }), { status: newStatus }, {
            preserveScroll: true, // Keeps page position
            onSuccess: () => {
                successToast(list.props.flash.message);
            },
            onError: (errors) => {
                errorToast(list.props.flash.message);
            }
        });
    }
}

</script>


<template>
    <div class="container-fluid pt-4 px-4">
        <div class="row g-4">
            <div class="col-12">
                <div class="bg-light rounded h-100 p-4">
                    <div class="d-flex justify-content-between mb-3 align-item-center">
                        <div>
                            <h6>Admin management table</h6>
                        </div>
                        <div><input placeholder="Search..." class="form-control w-auto form-control-sm" type="text"
                                v-model="searchValue"></div>
                    </div>

                    <div class="table-responsive">
                        <EasyDataTable buttons-pagination alternating :headers="Header" :items="Item" border-cell
                            theme-color="#1d90ff" :rows-per-page="10" :search-field="searchField"
                            :search-value="searchValue">
                            <template #item-type="{ type }">
                                <span :style="typeStyle(type)">
                                    {{ type }}
                                </span>
                            </template>

                            <template #item-status="{ status, id, type }">
                                <button v-if="type !== 'superadmin'" @click="toggleStatus(id, status)"
                                    :class="status === 'Active' ? 'btn btn-sm btn-primary' : 'btn btn-sm btn-outline-danger'"
                                    class="btn btn-sm">
                                    {{ status }}
                                </button>
                            </template>

                            <template #item-image="{ image }">
                                <img :src="image ? `/storage/${image}` : 'https://img.freepik.com/free-vector/businessman-character-avatar-isolated_24877-60111.jpg?uid=R75970278&ga=GA1.1.215570311.1722317775&semt=ais_hybrid'"
                                    alt="Profile image" style="width: 50px; height: 50px; object-fit: cover;"
                                    class="p-1">
                            </template>
                            <template #item-number="{ id, isVendor }">
                                <Link v-if="isVendor" class="btn btn-sm btn-outline-primary"
                                    :href="route('show.vendor.details', { id: id })">
                                View
                                </Link>
                            </template>
                        </EasyDataTable>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped></style>
