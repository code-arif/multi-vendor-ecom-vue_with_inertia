<script setup>
import { ref, computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

// Product details receive from props
const list = usePage();
const productDetails = list.props.productDetails || [];
</script>


<template>
    <div class="container-fluid pt-4 px-4">
        <div class="row g-4 mb-3">
            <div class="col-sm-12 col-xl-12">
                <div class="bg-light rounded p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <h6 class="mb-1">Product Details</h6>
                        <Link class="btn btn-sm btn-primary" :href="route('show.product')">Back to list</Link>
                    </div>
                    <hr>
                    <div class="row">
                        <!-- product image -->
                        <div class="col-md-5 mb-30">
                            <div id="product-carousel" class="carousel slide" data-bs-ride="carousel">
                                <div class="carousel-inner bg-light">
                                    <!-- Primary Image (From product table) -->
                                    <div class="carousel-item active">
                                        <img class="w-100 h-100"
                                            :src="productDetails.image ? `/storage/${productDetails.image}` : 'https://skala.or.id/wp-content/uploads/2024/01/dummy-post-square-1-1.jpg'"
                                            alt="Image">
                                    </div>

                                    <!-- Extra Images (From product_images table) -->
                                    <div class="carousel-item" v-for="(image, index) in productDetails.product_images"
                                        :key="index">
                                        <img class="w-100 h-100"
                                            :src="image.image_path ? `/storage/${image.image_path}` : 'https://skala.or.id/wp-content/uploads/2024/01/dummy-post-square-1-1.jpg'"
                                            alt="Product Image">
                                    </div>
                                </div>

                                <a class="carousel-control-prev" href="#product-carousel" data-bs-slide="prev">
                                    <i class="fa fa-2x fa-angle-left text-dark"></i>
                                </a>
                                <a class="carousel-control-next" href="#product-carousel" data-bs-slide="next">
                                    <i class="fa fa-2x fa-angle-right text-dark"></i>
                                </a>
                            </div>

                        </div>

                        <!-- product details -->
                        <div class="col-md-7 h-auto mb-30">
                            <div class="h-100 bg-light p-30">
                                <h3>{{ productDetails.product_name }}</h3>
                                <div class="d-flex mb-3">
                                    <div class="text-info mr-2">
                                        <small class="fas fa-star"></small>
                                        <small class="fas fa-star"></small>
                                        <small class="fas fa-star"></small>
                                        <small class="fas fa-star-half-alt"></small>
                                        <small class="far fa-star"></small>
                                    </div>
                                    <small class="pt-1">(99 Reviews) <span class="text-danger">(Not
                                            functional)</span></small>
                                </div>
                                <h5 class="font-weight-semi-bold mb-3">Price(৳): {{ productDetails.price }}</h5>
                                <h6 class="mb-3 text-muted">Additional Price(৳): {{
                                    productDetails.specifications?.additional_price ?? "N/A" }}</h6>
                                <p class="mb-4">{{ productDetails.short_description }}</p>

                                <h4>Product Summary:</h4>
                                <div class="d-flex pt-2 table-responsive" style="border:1px solid #ddd;">
                                    <table class="table table-bordered table-light">
                                        <thead>
                                            <tr class="text-primary">
                                                <th scope="col" class="text-nowrap">Id</th>
                                                <th scope="col" class="text-nowrap">Added By</th>
                                                <th scope="col" class="text-nowrap">Admin Type</th>
                                                <th scope="col" class="text-nowrap">Category</th>
                                                <th scope="col" class="text-nowrap">Brand</th>
                                                <th scope="col" class="text-nowrap">Slug</th>
                                                <th scope="col" class="text-nowrap">SKU</th>
                                                <th scope="col" class="text-nowrap">Qty</th>
                                                <th scope="col" class="text-nowrap">Stock Status</th>
                                                <th scope="col" class="text-nowrap">Remark</th>
                                                <th scope="col" class="text-nowrap">Has Discount</th>
                                                <th scope="col" class="text-nowrap">Discount Price</th>
                                                <th scope="col" class="text-nowrap">Status</th>
                                                <th scope="col" class="text-nowrap">Is Featured</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <th class="text-nowrap">{{ productDetails.id }}</th>
                                                <td class="text-nowrap">{{ productDetails.admin?.name || "N/A" }}</td>
                                                <td class="text-nowrap">{{ productDetails.admin?.type || "N/A" }}</td>
                                                <td class="text-nowrap">{{ productDetails.category?.name }}</td>
                                                <td class="text-nowrap">{{ productDetails.brand?.name || 'N/A' }}</td>
                                                <td class="text-nowrap">{{ productDetails.slug }}</td>
                                                <td class="text-nowrap">{{ productDetails.sku }}</td>
                                                <td class="text-nowrap">{{ productDetails.stock_quantity }}</td>
                                                <td class="text-nowrap">{{ productDetails.stock_status }}</td>
                                                <td class="text-nowrap">{{ productDetails.remark }}</td>
                                                <td class="text-nowrap">{{ productDetails.has_discount == 1 ? 'Yes' :
                                                    'No' }}</td>
                                                <td class="text-nowrap">{{ productDetails?.discount_price || 'N/A' }}
                                                </td>
                                                <td class="text-nowrap">{{ productDetails.status == 1 ? 'Active' :
                                                    'Inactive' }}</td>
                                                <td class="text-nowrap">{{ productDetails.is_featured == 1 ? 'Yes' :
                                                    'No' }}</td>
                                            </tr>
                                        </tbody>
                                    </table>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row px-xl-5">
                        <div class="col">
                            <div class="bg-light">
                                <div class="nav nav-tabs mb-4">
                                    <a class="nav-item nav-link text-dark active" data-bs-toggle="tab"
                                        href="#tab-pane-1">Description</a>
                                    <a class="nav-item nav-link text-dark" data-bs-toggle="tab"
                                        href="#tab-pane-2">Specifications</a>
                                    <a class="nav-item nav-link text-dark" data-bs-toggle="tab"
                                        href="#tab-pane-3">Meta</a>
                                    <a class="nav-item nav-link text-dark" data-bs-toggle="tab"
                                        href="#tab-pane-4">Reviews (0) <span class="text-danger">Not
                                            functional</span></a>
                                </div>
                                <div class="tab-content">

                                    <div class="tab-pane fade show active" id="tab-pane-1">
                                        <h4 class="mb-3">Product Description</h4>
                                        <p>{{ productDetails.product_details?.long_description || "N/A" }}</p>
                                    </div>

                                    <div class="tab-pane fade" id="tab-pane-2">
                                        <h4 class="mb-3"></h4>
                                        <div class="row">
                                            <!-- Attributes List -->
                                            <div class="col-md-3">
                                                <ul class="list-group list-group-flush">
                                                    <li class="list-group-item">Size</li>
                                                    <li class="list-group-item">Color</li>
                                                    <li class="list-group-item">Material</li>
                                                    <li class="list-group-item">Weight</li>
                                                    <li class="list-group-item">Length</li>
                                                    <li class="list-group-item">Width</li>
                                                    <li class="list-group-item">Height</li>
                                                    <li class="list-group-item">Volume</li>
                                                    <li class="list-group-item">Weight Unit</li>
                                                    <li class="list-group-item">Length Unit</li>
                                                    <li class="list-group-item">Width Unit</li>
                                                    <li class="list-group-item">Height Unit</li>
                                                    <li class="list-group-item">Volume Unit</li>
                                                    <li class="list-group-item">Additional Price</li>
                                                </ul>
                                            </div>

                                            <!-- Values List -->
                                            <div class="col-md-9">
                                                <ul class="list-group list-group-flush">
                                                    <li class="list-group-item">
                                                        {{ productDetails.specifications?.size || "N/A" }}
                                                    </li>
                                                    <li class="list-group-item">
                                                        {{ productDetails.specifications?.color || "N/A" }}
                                                    </li>
                                                    <li class="list-group-item">
                                                        {{ productDetails.specifications?.material || "N/A" }}
                                                    </li>
                                                    <li class="list-group-item">
                                                        {{ productDetails.specifications?.weight || "N/A" }}
                                                    </li>
                                                    <li class="list-group-item">
                                                        {{ productDetails.specifications?.length || "N/A" }}
                                                    </li>
                                                    <li class="list-group-item">
                                                        {{ productDetails.specifications?.width || "N/A" }}
                                                    </li>
                                                    <li class="list-group-item">
                                                        {{ productDetails.specifications?.height || "N/A" }}
                                                    </li>
                                                    <li class="list-group-item">
                                                        {{ productDetails.specifications?.volume || "N/A" }}
                                                    </li>
                                                    <li class="list-group-item">
                                                        {{ productDetails.specifications?.weight_unit || "N/A" }}
                                                    </li>
                                                    <li class="list-group-item">
                                                        {{ productDetails.specifications?.length_unit || "N/A" }}
                                                    </li>
                                                    <li class="list-group-item">
                                                        {{ productDetails.specifications?.width_unit || "N/A" }}
                                                    </li>
                                                    <li class="list-group-item">
                                                        {{ productDetails.specifications?.height_unit || "N/A" }}
                                                    </li>
                                                    <li class="list-group-item">
                                                        {{ productDetails.specifications?.volume_unit || "N/A" }}
                                                    </li>
                                                    <li class="list-group-item">
                                                        {{ productDetails.specifications?.additional_price || "N/A" }}
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="tab-pane fade" id="tab-pane-3">
                                        <h4 class="mb-3"></h4>
                                        <div class="row">
                                            <!-- Keys List -->
                                            <div class="col-md-3">
                                                <ul class="list-group list-group-flush">
                                                    <li class="list-group-item">
                                                        Meta Keyword
                                                    </li>
                                                    <li class="list-group-item">
                                                        Meta Title
                                                    </li>
                                                    <li class="list-group-item">
                                                        Meta Description
                                                    </li>
                                                </ul>
                                            </div>
                                            <!-- Values List -->
                                            <div class="col-md-9">
                                                <ul class="list-group list-group-flush">
                                                    <li class="list-group-item">
                                                        {{ productDetails.meta_keywords ?? 'N/A' }}
                                                    </li>
                                                    <li class="list-group-item">
                                                        {{ productDetails.meta_title ?? 'N/A' }}
                                                    </li>
                                                    <li class="list-group-item">
                                                        {{ productDetails.meta_description ?? 'N/A' }}
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="tab-pane fade" id="tab-pane-4">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h4 style="margin-bottom: 15px;">1 review for "Product Name"</h4>
                                                <div class="media" style="margin-bottom: 15px;">
                                                    <img src="https://img.freepik.com/premium-vector/avatar-profile-icon-flat-style-male-user-profile-vector-illustration-isolated-background-man-profile-sign-business-concept_157943-38764.jpg?semt=ais_hybrid"
                                                        alt="Image" class="img-fluid"
                                                        style="width: 45px; margin-right: 15px;">
                                                    <div class="media-body">
                                                        <h6>John Doe<small> - <i>01 Jan 2045</i></small></h6>
                                                        <div class="text-info mb-2">
                                                            <i class="fas fa-star"></i>
                                                            <i class="fas fa-star"></i>
                                                            <i class="fas fa-star"></i>
                                                            <i class="fas fa-star-half-alt"></i>
                                                            <i class="far fa-star"></i>
                                                        </div>
                                                        <p>Diam amet duo labore stet elitr ea clita ipsum, tempor
                                                            labore accusam
                                                            ipsum et no at. Kasd diam tempor rebum magna dolores sed
                                                            sed eirmod
                                                            ipsum.</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <h4 style="margin-bottom: 15px;">Leave a review</h4>
                                                <small>Your email address will not be published. Required fields are
                                                    marked
                                                    *</small>
                                                <div class="d-flex my-3">
                                                    <p class="mb-0 mr-2">Your Rating * :</p>
                                                    <div class="text-info">
                                                        <i class="far fa-star"></i>
                                                        <i class="far fa-star"></i>
                                                        <i class="far fa-star"></i>
                                                        <i class="far fa-star"></i>
                                                        <i class="far fa-star"></i>
                                                    </div>
                                                </div>
                                                <form>
                                                    <div class="form-group">
                                                        <label for="message" style="margin-bottom: 10px;">Your
                                                            Review *</label>
                                                        <textarea id="message" cols="30" rows="5"
                                                            class="form-control"></textarea>
                                                    </div>
                                                    <div class="form-group" style="margin-top: 15px;">
                                                        <label for="name" style="margin-bottom: 10px;">Your Name
                                                            *</label>
                                                        <input type="text" class="form-control" id="name">
                                                    </div>
                                                    <div class="form-group" style="margin-top: 15px;">
                                                        <label for="email" style="margin-bottom: 10px;">Your Email
                                                            *</label>
                                                        <input type="email" class="form-control" id="email">
                                                    </div>
                                                    <div class="form-group" style="margin-top: 15px;">
                                                        <input type="submit" value="Leave Your Review"
                                                            class="btn btn-info px-3">
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped></style>
