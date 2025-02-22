<script setup>
import { Link, usePage } from '@inertiajs/vue3';
const list = usePage();
const featured_products = list.props.featured_products || [];
</script>

<template>
    <!-- Products Start -->
    <div class="container-fluid pt-5 pb-3">
        <h2 class="section-title position-relative text-uppercase mx-xl-5 mb-4"><span class="bg-light"
                style="padding-right: 15px; padding-left: 5px;">Featured Products</span></h2>
        <div class="row px-xl-5">
            <div class="col-lg-2 col-md-4 col-sm-6 pb-1" v-for="product in featured_products" :key="product.id">
                <div class="product-item bg-light mb-4">
                    <div class="product-img position-relative overflow-hidden" style="border: 1px solid #ddd;">
                        <img class="custom-img" :src="`/storage/${product.image}`" alt="">
                        <div class="product-action">
                            <a class="btn btn-outline-dark btn-square" href=""><i class="fa fa-shopping-cart"></i></a>
                            <a class="btn btn-outline-dark btn-square" href=""><i class="far fa-heart"></i></a>
                            <a class="btn btn-outline-dark btn-square" href=""><i class="fa fa-sync-alt"></i></a>
                            <a :href="route('show.product.details.page', { id: product.id })"
                                class="btn btn-outline-dark btn-square" href=""><i class="fa fa-eye"></i></a>
                        </div>
                    </div>
                    <div class="text-center py-4 px-3">
                        <Link class="h6 text-decoration-none text-truncate product-name"
                            :href="route('show.product.details.page', { id: product.id })"> {{
                                product?.product_name.length > 50
                                    ? product.product_name.slice(0, 40) + '...'
                                    : product.product_name
                            }}</Link>
                        <div class="d-flex align-items-center justify-content-center mt-2">
                            <h5 style="margin-right: 10px;">৳ {{ product.price }}</h5>
                            <h6 class="text-muted">
                                <del v-if="product?.has_discount">৳ {{
                                    product.discount_price }}</del>
                            </h6>
                        </div>

                        <div class="d-flex align-items-center justify-content-center mb-1">
                            <small class="fa fa-star text-info mr-1"></small>
                            <small class="fa fa-star text-info mr-1"></small>
                            <small class="fa fa-star text-info mr-1"></small>
                            <small class="fa fa-star text-info mr-1"></small>
                            <small class="fa fa-star text-info mr-1"></small>
                            <small>(99)</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Products End -->
</template>

<style scoped>
.product-name {
    display: block;
    white-space: normal;
    word-wrap: break-word;
    overflow-wrap: break-word;
    text-align: left;
}

.custom-img {
    width: 300px !important;
    height: 200px !important;
    object-fit: cover;
}
</style>
