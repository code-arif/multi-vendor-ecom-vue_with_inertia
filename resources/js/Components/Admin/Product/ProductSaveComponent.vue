<script setup>
import { ref } from "vue";
import { Link, usePage, useForm } from "@inertiajs/vue3";
const list = usePage();
const categories = list.props.categories || [];
const brands = list.props.brands || [];

// ===================== Add / Edit category ===================== //
// Form for Add & Edit
const form = useForm({
    vendor_id : "",
    admin_id : "",
    category_id: "",
    brand_id: "",
    product_name: "",
    price: "",
    slug: "",
    sku : "",
    stock_quantity: "",
    stock_status: "in_stock",
    remark: "top",
    has_discount: false,
    discount_price: "",
    status: true,
    is_featured : false,
    short_description: "",
    image: null,
});

function saveProduct(){
    form.post('/product/add'),{
        onSuccess: () => {
            if (list.props.flash.status) {
                successToast(list.props.flash.message);
                form.reset();
            } else {
                errorToast(list.props.flash.message);
            }
        },
        onError: (error) => {console.log(error)}
    }
}


// Reactive property for dynamic image preview
const imagePreview = ref(
    'https://skala.or.id/wp-content/uploads/2024/01/dummy-post-square-1-1.jpg'
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
</script>

<template>
    <div class="container-fluid pt-4 px-4">
        <div class="row g-4 mb-3">
            <div class="col-sm-12 col-xl-12">
                <div class="bg-light rounded p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="mb-1">Edit Product/Add Product</h6>
                        </div>
                        <div>
                            <Link class="btn btn-sm btn-primary" :href="route('show.product')"> Back to list</Link>
                        </div>
                    </div>
                    <hr>
                    <form @submit.prevent="saveProduct">
                        <!-- Select Category -->
                        <div class="row mb-3">
                            <div class="form-group col-md-6">
                                <label class="mb-2" for="category">Select Category</label>
                                <select v-model="form.category_id" id="category" class="form-select" style="max-height: 100px; overflow-y: auto;">
                                    <option value="">Select Category</option>
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
                            </div>

                            <div class="form-group col-md-6">
                                <label class="mb-2" for="brand">Select Brand</label>
                                <select v-model="form.brand_id" id="brand" class="form-select">
                                    <option value="">Select Brand</option>
                                    <option v-for="brand in brands" :key="brand.id" :value="brand.id">
                                        {{ brand?.name }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <!-- Product Name, Price, SKU -->
                        <div class="row mb-3">
                            <div class="form-group col-md-6">
                                <label class="mb-2" for="productName">Product Name</label>
                                <input type="text" v-model="form.product_name" class="form-control" id="productName"
                                    placeholder="Enter Product Name">
                            </div>
                            <div class="form-group col-md-3">
                                <label class="mb-2" for="price">Price</label>
                                <input type="number" v-model="form.price" class="form-control" id="price"
                                    placeholder="Enter Price">
                            </div>
                            <div class="form-group col-md-3">
                                <label class="mb-2" for="sku">SKU</label>
                                <input type="text" v-model="form.sku" class="form-control" id="sku"
                                    placeholder="Enter SKU">
                            </div>
                        </div>

                        <!-- Stock Quantity, Stock Status -->
                        <div class="row mb-3">
                            <div class="form-group col-md-4">
                                <label class="mb-2" for="stockQuantity">Stock Quantity</label>
                                <input type="number" v-model="form.stock_quantity" class="form-control"
                                    id="stockQuantity" placeholder="Enter Stock Quantity">
                            </div>
                            <div class="form-group col-md-4">
                                <label class="mb-2" for="stockStatus">Stock Status</label>
                                <select v-model="form.stock_status" id="stockStatus" class="form-select">
                                    <option value="in_stock">In Stock</option>
                                    <option value="out_of_stock">Out of Stock</option>
                                    <option value="pre_order">Pre-Order</option>
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="mb-2" for="remark">Stock Status</label>
                                <select v-model="form.remark" id="remark" class="form-select">
                                    <option value="popular">Popular</option>
                                    <option value="new">New</option>
                                    <option value="top">Top</option>
                                    <option value="special">Special</option>
                                    <option value="trending">Trending</option>
                                    <option value="regular">Regular</option>
                                </select>
                            </div>
                        </div>

                        <!--Status Discount, Discount Price -->
                        <div class="row mb-3">
                            <div class="form-group col-md-3">
                                <label class="mb-2">Status</label>
                                <select v-model="form.status" class="form-select">
                                    <option :value="true">Active</option>
                                    <option :value="false">Inactive</option>
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label class="mb-2">Is Featured?</label>
                                <select v-model="form.is_featured" class="form-select">
                                    <option :value="true">Yes</option>
                                    <option :value="false">No</option>
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label class="mb-2">Has Discount?</label>
                                <select v-model="form.has_discount" class="form-select">
                                    <option :value="true">Yes</option>
                                    <option :value="false">No</option>
                                </select>
                            </div>
                            <div class="form-group col-md-3" v-if="form.has_discount">
                                <label class="mb-2" for="discountPrice">Discount Price</label>
                                <input type="number" v-model="form.discount_price" class="form-control"
                                    id="discountPrice" placeholder="Enter Discount Price">
                            </div>
                        </div>

                        <!-- Short Description -->
                        <div class="mb-3">
                            <label class="mb-2" for="shortDescription">Short Description</label>
                            <textarea v-model="form.short_description" class="form-control" id="shortDescription"
                                rows="3"></textarea>
                        </div>

                        <!-- Category Image Field -->
                        <div class="">
                            <div class="mb-3"
                                style="width: 100px; height: 100px; overflow: hidden; background: #f8f9fa;">
                                <img :src="imagePreview" alt="Image Preview" class="img-fluid"
                                    style="width: 100%; height: 100%; object-fit: cover; border: 2px solid rgb(0 156 255 / 36%);">
                            </div>
                        </div>

                        <!-- Product Image -->
                        <div class="mb-3">
                            <label class="mb-2" for="productImage">Product Image</label>
                            <input type="file" class="form-control" id="productImage" @change="handleImageInput">
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-primary w-100">
                            {{ form.id ? "Update Product" : "Save Product" }}
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
