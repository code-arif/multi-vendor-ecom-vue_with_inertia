<script setup>
import { Link, usePage } from '@inertiajs/vue3';

const list = usePage();
const categories = list.props.categories || [];
</script>

<template>
    <!-- Categories Start -->
    <div class="container-fluid pt-5">
        <h2 class="section-title position-relative text-uppercase mx-xl-5 mb-4">
            <span class="bg-light" style="padding-right: 20px; padding-left: 5px;">Categories</span>
        </h2>
        <div class="row px-xl-5 pb-3">
            <div v-for="category in categories" :key="category.id" class="col-lg-3 col-md-4 col-sm-6 pb-1">
                <Link class="text-decoration-none" :href="route('show.product.by.category',{url:category.url})">
                    <div class="cat-item img-zoom d-flex align-items-center mb-4">
                        <div class="overflow-hidden" style="width: 100px; height: 100px; border:0.2px solid #ddd;">
                            <img
                                class="img-fluid"
                                :src="category.image ? `/storage/${category.image}` : 'https://skala.or.id/wp-content/uploads/2024/01/dummy-post-square-1-1.jpg'"
                                alt="Category Image"
                            />
                        </div>
                        <div class="flex-fill" style="padding-left: 20px;">
                            <h6>{{ category.name }}</h6>
                            <small class="text-body">{{ category.products_count }} Products</small>
                        </div>
                    </div>
                </Link>
            </div>
        </div>
    </div>
    <!-- Categories End -->
</template>

<style scoped>
/* Optional CSS */
</style>
