<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";
import { Link } from "@inertiajs/vue3";

const sections = ref([]);

const fetchSections = async () => {
    try {
        const response = await axios.get("/get-header-section");
        // console.log(response.data); // Check if data is received
        sections.value = response.data;
    } catch (error) {
        errorToast("Error fetching sections:", error);
    }
};

onMounted(fetchSections);
</script>

<template>
    <!-- Navbar Start -->
    <div class="container-fluid bg-dark mb-30">
        <div class="row px-xl-5">
            <!-- categories -->
            <div class="col-lg-3 d-none d-lg-block">
                <a class="d-flex align-items-center justify-content-between bg-info w-100" data-bs-toggle="collapse"
                    href="#navbar-vertical" style="height: 65px; padding: 0 30px;">
                    <h6 class="text-light m-0"><i class="fa fa-bars" style="margin-right: 10px;"></i>Categories</h6>
                    <i class="fa fa-angle-down text-light"></i>
                </a>
                <nav class="collapse position-absolute navbar navbar-vertical navbar-light align-items-start p-0 bg-light"
                    id="navbar-vertical" style="width: 21.5%; z-index: 999;">
                    <div class="navbar-nav w-100">
                        <div class="nav-item dropdown dropright" v-for="section in sections" :key="section.id">
                            <a href="#" class="nav-link dropdown-item" data-bs-toggle="dropdown">
                                {{ section.sec_name }}
                                <i class="fa fa-angle-right float-right mt-1"></i>
                            </a>
                            <div class="dropdown-menu position-absolute rounded-0 border-0 m-0 p-3"
                                style="width: 600px; left: 100%; top: 0;">
                                <div class="row">
                                    <div class="col-md-4" v-for="category in section.categories" :key="category.id">
                                        <Link :href="route('show.product.by.category',{url:category.url})" class="text-info">{{ category.name }}</Link>

                                        <Link :href="route('show.product.by.category',{url:subcategory.url})" class="dropdown-item" v-for="subcategory in category.subcategories"
                                            :key="subcategory.id">
                                            {{ subcategory.name }}
                                        </Link>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </nav>
            </div>

            <!-- other navs -->
            <div class="col-lg-9">
                <nav class="navbar navbar-expand-lg bg-dark navbar-dark py-3 py-lg-0 px-0">
                    <Link :href="route('show.home.page')" class="text-decoration-none d-block d-lg-none">
                        <span class="h1 text-uppercase text-dark bg-light px-2">Mini</span>
                        <span class="h1 text-uppercase text-light bg-primary px-2 ml-n1">Shop</span>
                    </Link>
                    <button type="button" class="navbar-toggler" data-bs-toggle="collapse"
                        data-target="#navbarCollapse">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse justify-content-between" id="navbarCollapse">
                        <div class="navbar-nav mr-auto py-0">

                            <Link :href="route('show.home.page')" class="nav-item nav-link" :class="{ 'active': $page.url === '/' }">Home</Link>

                            <Link :href="route('show.products.page')" class="nav-item nav-link"
                            :class="{ 'active': $page.url === '/products' }">Shop</Link>
                        </div>

                    </div>
                </nav>
            </div>
        </div>
    </div>
    <!-- Navbar End -->
</template>

<style scoped></style>
