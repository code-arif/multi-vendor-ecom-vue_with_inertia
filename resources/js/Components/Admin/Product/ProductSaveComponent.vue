<script setup>
import { ref, computed } from "vue";
import { Link, usePage, useForm } from "@inertiajs/vue3";

const list = usePage();
const categories = list.props.categories || [];
const brands = list.props.brands || [];
const products = list.props.products || [];
const authUser = usePage().props.auth.user;

// Form for Add & Edit
const form = useForm({
    id:products.id || null,
    vendor_id: authUser.type === 'vendor' ? authUser.id : "",
    admin_id: authUser.id,
    category_id: products.category_id || "",
    brand_id: products.brand_id || "",
    product_name: products.product_name || "",
    price: products.price || "",
    sku: products.sku || "",
    stock_quantity: products.stock_quantity || "",
    stock_status: products.stock_status || "in_stock",
    remark: products.remark || "top",
    has_discount: products.has_discount || 0,
    discount_price: products.discount_price || "",
    status: products.status || 0, // Default status to true for admins/subadmins
    is_featured: products.is_featured || 0,
    short_description: products.short_description || "",
    image: products.image || null,
    meta_title: products.meta_title || "",
    meta_description: products.meta_description || "",
    meta_keywords: products.meta_keywords || "",
});


function saveProduct() {
    const routeName = form.id ? "update.product" : "add.product";
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
            if (errors.vendor_id) {
                errorToast(errors.vendor_id);
            } else if (errors.admin_id) {
                errorToast(errors.admin_id);
            } else if (errors.category_id) {
                errorToast(errors.category_id);
            } else if (errors.brand_id) {
                errorToast(errors.brand_id);
            } else if (errors.product_name) {
                errorToast(errors.product_name);
            } else if (errors.price) {
                errorToast(errors.price);
            } else if (errors.sku) {
                errorToast(errors.sku);
            } else if (errors.stock_quantity) {
                errorToast(errors.stock_quantity);
            } else if (errors.stock_status) {
                errorToast(errors.stock_status);
            } else if (errors.remark) {
                errorToast(errors.remark);
            } else if (errors.has_discount) {
                errorToast(errors.has_discount);
            } else if (errors.discount_price) {
                errorToast(errors.discount_price);
            } else if (errors.is_featured) {
                errorToast(errors.is_featured);
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

// Reactive property for dynamic image preview
const imagePreview = ref(
    products.image ? `/storage/${products.image}` : 'https://skala.or.id/wp-content/uploads/2024/01/dummy-post-square-1-1.jpg'
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
                            <h6 class="mb-1">{{ form.id ? "Edit Product" : "Add Product" }}</h6>
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
                                <select v-model="form.category_id" id="category" class="form-select"
                                    style="max-height: 100px; overflow-y: auto;"
                                    :class="{ 'is-invalid': form.errors.name }">
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

                                <div v-if="form.errors.category_id" class="invalid-feedback">
                                    {{ form.errors.category_id }}
                                </div>
                            </div>

                            <!-- select brand -->
                            <div class="form-group col-md-6">
                                <label class="mb-2" for="brand">Select Brand</label>
                                <select v-model="form.brand_id" id="brand" class="form-select"
                                    :class="{ 'is-invalid': form.errors.brand_id }">
                                    <option value="">Select Brand</option>
                                    <option v-for="brand in brands" :key="brand.id" :value="brand.id">
                                        {{ brand?.name }}
                                    </option>
                                </select>

                                <div v-if="form.errors.brand_id" class="invalid-feedback">
                                    {{ form.errors.brand_id }}
                                </div>
                            </div>
                        </div>
                        <!-- Product Name, Price, SKU -->
                        <div class="row mb-3">
                            <div class="form-group col-md-6">
                                <label class="mb-2" for="productName">Product Name</label>
                                <input type="text" v-model="form.product_name" class="form-control" id="productName"
                                    placeholder="Enter Product Name"
                                    :class="{ 'is-invalid': form.errors.product_name }">
                                <div v-if="form.errors.product_name" class="invalid-feedback">
                                    {{ form.errors.product_name }}
                                </div>
                            </div>
                            <div class="form-group col-md-3">
                                <label class="mb-2" for="price">Price</label>
                                <input type="number" v-model="form.price" class="form-control" id="price"
                                    placeholder="Enter Price" :class="{ 'is-invalid': form.errors.price }">
                                <div v-if="form.errors.price" class="invalid-feedback">
                                    {{ form.errors.price }}
                                </div>
                            </div>
                            <div class="form-group col-md-3">
                                <label class="mb-2" for="sku">SKU</label>
                                <input type="text" v-model="form.sku" class="form-control" id="sku"
                                    placeholder="Enter SKU" :class="{ 'is-invalid': form.errors.sku }">
                                <div v-if="form.errors.sku" class="invalid-feedback">
                                    {{ form.errors.sku }}
                                </div>
                            </div>
                        </div>

                        <!-- Stock Quantity, Stock Status -->
                        <div class="row mb-3">
                            <div class="form-group col-md-4">
                                <label class="mb-2" for="stockQuantity">Stock Quantity</label>
                                <input type="number" v-model="form.stock_quantity" class="form-control"
                                    id="stockQuantity" placeholder="Enter Stock Quantity"
                                    :class="{ 'is-invalid': form.errors.stock_quantity }">
                                <div v-if="form.errors.stock_quantity" class="invalid-feedback">
                                    {{ form.errors.stock_quantity }}
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="mb-2" for="stockStatus">Stock Status</label>
                                <select v-model="form.stock_status" id="stockStatus" class="form-select"
                                    :class="{ 'is-invalid': form.errors.stock_status }">
                                    <option value="in_stock">In Stock</option>
                                    <option value="out_of_stock">Out of Stock</option>
                                    <option value="pre_order">Pre-Order</option>
                                </select>
                                <div v-if="form.errors.stock_status" class="invalid-feedback">
                                    {{ form.errors.stock_status }}
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="mb-2" for="remark">Stock Status</label>
                                <select v-model="form.remark" id="remark" class="form-select"
                                    :class="{ 'is-invalid': form.errors.remark }">
                                    <option value="popular">Popular</option>
                                    <option value="new">New</option>
                                    <option value="top">Top</option>
                                    <option value="special">Special</option>
                                    <option value="trending">Trending</option>
                                    <option value="regular">Regular</option>
                                </select>
                                <div v-if="form.errors.remark" class="invalid-feedback">
                                    {{ form.errors.remark }}
                                </div>
                            </div>
                        </div>

                        <!--Status, Discount, Discount Price -->
                        <div class="row mb-3">
                            <div class="form-group col-md-3">
                                <label class="mb-2">Status</label>
                                <select v-model="form.status" class="form-select"
                                    :class="{ 'is-invalid': form.errors.status }">
                                    <option :value=1>Active</option>
                                    <option :value=0>Inactive</option>
                                </select>
                                <div v-if="form.errors.status" class="invalid-feedback">
                                    {{ form.errors.status }}
                                </div>
                            </div>
                            <div class="form-group col-md-3">
                                <label class="mb-2">Is Featured?</label>
                                <select v-model="form.is_featured" class="form-select"
                                    :class="{ 'is-invalid': form.errors.is_featured }">
                                    <option :value=1>Yes</option>
                                    <option :value=0>No</option>
                                </select>
                                <div v-if="form.errors.is_featured" class="invalid-feedback">
                                    {{ form.errors.is_featured }}
                                </div>
                            </div>
                            <div class="form-group col-md-3">
                                <label class="mb-2">Has Discount?</label>
                                <select v-model="form.has_discount" class="form-select"
                                    :class="{ 'is-invalid': form.errors.has_discount }">
                                    <option :value=1>Yes</option>
                                    <option :value=0>No</option>
                                </select>
                                <div v-if="form.errors.has_discount" class="invalid-feedback">
                                    {{ form.errors.has_discount }}
                                </div>
                            </div>
                            <div class="form-group col-md-3" v-if="form.has_discount">
                                <label class="mb-2" for="discountPrice">Discount Price</label>
                                <input type="number" v-model="form.discount_price" class="form-control"
                                    id="discountPrice" placeholder="Enter Discount Price"
                                    :class="{ 'is-invalid': form.errors.discount_price }">
                                <div v-if="form.errors.discount_price" class="invalid-feedback"> {{
                                    form.errors.discount_price }}</div>
                            </div>
                        </div>

                        <!-- Short Description -->
                        <div class="mb-3">
                            <label class="mb-2" for="shortDescription">Short Description</label>
                            <textarea v-model="form.short_description" class="form-control" id="shortDescription"
                                rows="4" :class="{ 'is-invalid': form.errors.short_description }"></textarea>
                            <div v-if="form.errors.short_description" class="invalid-feedback">
                                {{ form.errors.short_description }}
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="form-group col-md-6">
                                <label class="mb-2" for="metaTitle">Meta Title</label>
                                <input type="text" v-model="form.meta_title" class="form-control" id="metaTitle"
                                    placeholder="Enter Meta Title" :class="{ 'is-invalid': form.errors.meta_title }">
                                <div v-if="form.errors.meta_title" class="invalid-feedback">
                                    {{ form.errors.meta_title }}
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                <label class="mb-2" for="meta_keywords">Meta Keywords</label>
                                <input type="text" v-model="form.meta_keywords" class="form-control" id="meta_keywords"
                                    placeholder="Enter Meta Keywords"
                                    :class="{ 'is-invalid': form.errors.meta_keywords }">
                                <div v-if="form.errors.meta_keywords" class="invalid-feedback">
                                    {{ form.errors.meta_keywords }}
                                </div>
                            </div>
                        </div>

                        <!-- Meta Description -->
                        <div class="mb-3">
                            <label class="mb-2" for="meta_description">Meta Description</label>
                            <textarea v-model="form.meta_description" class="form-control" id="meta_description"
                                rows="4" :class="{ 'is-invalid': form.errors.meta_description }"></textarea>
                            <div v-if="form.errors.meta_description" class="invalid-feedback">
                                {{ form.errors.meta_description }}
                            </div>
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
                            <progress v-if="form.progress" :value="form.progress.percentage" max="100">
                                {{ form.progress.percentage }}%
                            </progress>
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
