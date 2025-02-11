<script setup>
import { ref, computed } from 'vue'
import { Link, usePage } from '@inertiajs/vue3';
const list = usePage();
const productDetails = list.props.productDetails || [];
</script>

<template>
    <!-- Breadcrumb Start -->
    <div class="container-fluid">
        <div class="row px-xl-5">
            <div class="col-12">
                <nav class="breadcrumb bg-light mb-30 p-3">
                    <Link class="breadcrumb-item text-dark" href="/">Home</Link>
                    <Link class="breadcrumb-item text-dark" :href="route('show.products.page')">Shop</Link>
                    <span class="breadcrumb-item active">Shop Detail</span>
                </nav>
            </div>
        </div>
    </div>
    <!-- Breadcrumb End -->


    <!-- Shop Detail Start -->
    <div class="container-fluid pb-5">

        <div class="row px-xl-5">
            <div class="col-lg-5 mb-30">
                <div id="product-carousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner bg-light">
                        <!-- Primary Image (From product table) -->
                        <div class="carousel-item active">
                            <img class="w-100 h-100"
                                :src="productDetails.image ? `/storage/${productDetails.image}` : 'https://skala.or.id/wp-content/uploads/2024/01/dummy-post-square-1-1.jpg'"
                                alt="Image">
                        </div>

                        <!-- Extra Images (From product_images table) -->
                        <div class="carousel-item" v-for="(image, index) in productDetails.product_images" :key="index">
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

            <div class="col-lg-7 h-auto mb-30">
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
                        <small class="pt-1">(99 Reviews) <span class="text-danger">Not functional</span></small>
                    </div>

                    <div class="d-flex align-items-center">
                        <h5 class="font-weight-semi-bold">Price (৳): {{ productDetails.price }}</h5>
                        <h6 v-if="productDetails.has_discount" class="text-muted" style="margin-left: 10px;">
                            <del>৳{{ productDetails.discount_price }}</del>
                        </h6>
                    </div>

                    <p class="mb-4">{{ productDetails.short_description }}</p>

                    <div class="d-flex mb-3">
                        <strong class="text-dark" style="margin-right: 20px;">Sizes:</strong>
                        <form>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" class="custom-control-input" id="size-1" name="size">
                                <label class="custom-control-label" for="size-1">XS</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" class="custom-control-input" id="size-2" name="size">
                                <label class="custom-control-label" for="size-2">S</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" class="custom-control-input" id="size-3" name="size">
                                <label class="custom-control-label" for="size-3">M</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" class="custom-control-input" id="size-4" name="size">
                                <label class="custom-control-label" for="size-4">L</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" class="custom-control-input" id="size-5" name="size">
                                <label class="custom-control-label" for="size-5">XL</label>
                            </div>
                        </form>
                    </div>
                    <div class="d-flex mb-4">
                        <strong class="text-dark" style="margin-right: 20px;">Colors:</strong>
                        <form>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" class="custom-control-input" id="color-1" name="color">
                                <label class="custom-control-label" for="color-1">Black</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" class="custom-control-input" id="color-2" name="color">
                                <label class="custom-control-label" for="color-2">White</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" class="custom-control-input" id="color-3" name="color">
                                <label class="custom-control-label" for="color-3">Red</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" class="custom-control-input" id="color-4" name="color">
                                <label class="custom-control-label" for="color-4">Blue</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" class="custom-control-input" id="color-5" name="color">
                                <label class="custom-control-label" for="color-5">Green</label>
                            </div>
                        </form>
                    </div>
                    <div class="d-flex align-items-center mb-4 pt-2">
                        <div class="input-group quantity mr-3" style="width: 130px;">
                            <div class="input-group-btn">
                                <button class="btn btn-info btn-minus">
                                    <i class="fa fa-minus"></i>
                                </button>
                            </div>
                            <input type="text" class="form-control border-0 text-center" value="1">
                            <div class="input-group-btn">
                                <button class="btn btn-info btn-plus">
                                    <i class="fa fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        <button class="btn btn-info px-3" style="margin-left: 15px;"><i class="fa fa-shopping-cart"></i>
                            Add To
                            Cart</button>
                    </div>
                    <div class="d-flex pt-2">
                        <strong class="text-dark mr-2">Share on:</strong>
                        <div class="d-inline-flex">
                            <a class="text-dark px-2" href="">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a class="text-dark px-2" href="">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a class="text-dark px-2" href="">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                            <a class="text-dark px-2" href="">
                                <i class="fab fa-pinterest"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="row px-xl-5">
            <div class="col">
                <div class="bg-light p-30">

                    <div class="nav nav-tabs mb-4">
                        <a class="nav-item nav-link text-dark active" data-bs-toggle="tab"
                            href="#tab-pane-1">Description</a>
                            
                        <a class="nav-item nav-link text-dark" data-bs-toggle="tab"
                            href="#tab-pane-2">Specifications</a>

                        <a class="nav-item nav-link text-dark" data-bs-toggle="tab" href="#tab-pane-3">Reviews (0) <span
                                class="text-danger">Not
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
                            <div class="row">
                                <div class="col-md-6">
                                    <h4 style="margin-bottom: 15px;">1 review for "Product Name"</h4>
                                    <div class="media" style="margin-bottom: 15px;">
                                        <img src="https://img.freepik.com/premium-vector/avatar-profile-icon-flat-style-male-user-profile-vector-illustration-isolated-background-man-profile-sign-business-concept_157943-38764.jpg?semt=ais_hybrid"
                                            alt="Image" class="img-fluid" style="width: 45px; margin-right: 15px;">
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
                                            <textarea id="message" cols="30" rows="5" class="form-control"></textarea>
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
                                            <input type="submit" value="Leave Your Review" class="btn btn-info px-3">
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
    <!-- Shop Detail End -->
</template>

<style scoped></style>
