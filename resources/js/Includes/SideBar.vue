<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

const authUser = usePage().props.auth.user;

const imagePreview = ref(
    authUser.image ? `/storage/${authUser.image}` : 'https://img.freepik.com/free-vector/businessman-character-avatar-isolated_24877-60111.jpg?uid=R75970278&ga=GA1.1.215570311.1722317775&semt=ais_hybrid'
);

</script>

<template>
    <!-- Sidebar Start -->
    <div class="sidebar ">
        <nav class="navbar navbar-light">
            <a href="" class="navbar-brand mx-4 mb-3">
                <h3 class="text-primary"><i class="fa fa-shopping-cart me-2"></i>Mini Shop</h3>
            </a>
            <div class="d-flex align-items-center ms-4 mb-4" v-if="authUser">
                <div class="position-relative">
                    <img class="rounded-circle" :src="imagePreview" alt="Profile"
                        style="width: 40px; height: 40px; object-fit: cover; border-radius: 50%; border: 3px solid #009CFF;">
                </div>
                <div class="ms-3">
                    <h6 class="mb-0">{{ authUser.name }}</h6>
                    <span>{{ authUser.type }}</span>
                </div>
            </div>
            <div class="navbar-nav w-100">
                <Link :href="route('show.admin.dashboard')" class="nav-item nav-link"
                    :class="{ 'active': $page.url === '/admin/dashboard' }" href="/admin/dashboard"><i
                    class="fa fa-tachometer-alt me-2"></i>Dashboard</Link>

                <Link v-if="authUser && authUser.type === 'vendor'" class="nav-item nav-link"
                    :href="route('show.vendor.profile')" :class="{ 'active': $page.url === '/vendor/profile' }">
                <i class="fa fa-users me-2"></i>My Details
                </Link>

                <!-- admin manage -->
                <div class="nav-item dropdown"
                    v-if="authUser && (authUser.type === 'superadmin' || authUser.type === 'admin')">

                    <a href="" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"
                        :class="{ 'active': ['/admins/admin', '/admins/subadmin', '/admins/vendor', '/admins'].includes($page.url) }">
                        <i class="fa fa-unlock me-1"></i> Admin Manage
                    </a>

                    <div class="dropdown-menu bg-transparent border-0">
                        <Link :href="route('admin.manage', { type: 'admin' })" class="dropdown-item"
                            :class="{ 'active': $page.url === '/admins/admin' }">
                        <i class="fa fa-chevron-circle-right me-2"></i> Admin
                        </Link>
                        <Link :href="route('admin.manage', { type: 'subadmin' })" class="dropdown-item"
                            :class="{ 'active': $page.url === '/admins/subadmin' }">
                        <i class="fa fa-chevron-circle-right me-2"></i> Subadmin
                        </Link>
                        <Link :href="route('admin.manage', { type: 'vendor' })" class="dropdown-item"
                            :class="{ 'active': $page.url === '/admins/vendor' }">
                        <i class="fa fa-chevron-circle-right me-2"></i> Vendor
                        </Link>
                        <Link :href="route('admin.manage')" class="dropdown-item"
                            :class="{ 'active': $page.url === '/admins' }">
                        <i class="fa fa-chevron-circle-right me-2"></i> All
                        </Link>
                    </div>
                </div>

                <!-- catalogue manage -->
                <div class="nav-item dropdown"
                    v-if="authUser && (authUser.type === 'superadmin' || authUser.type === 'admin')">

                    <a href="" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"
                        :class="{ 'active': ['/section/list', '/category/list', '/brand/list'].includes($page.url) }">
                        <i class="fa fa-bars" aria-hidden="true"></i> Catalogue Manage
                    </a>

                    <div class="dropdown-menu bg-transparent border-0">
                        <Link :href="route('show.section')" class="dropdown-item"
                            :class="{ 'active': $page.url === '/section/list' }">
                        <i class="fa fa-chevron-circle-right me-2"></i> Sections
                        </Link>
                        <Link :href="route('show.category')" class="dropdown-item"
                            :class="{ 'active': $page.url === '/category/list' }">
                        <i class="fa fa-chevron-circle-right me-2"></i> Category
                        </Link>
                        <Link :href="route('show.brand')" class="dropdown-item"
                            :class="{ 'active': $page.url === '/brand/list' }">
                        <i class="fa fa-chevron-circle-right me-2"></i> Brand
                        </Link>
                    </div>
                </div>

                <!-- product manage -->
                <div class="nav-item dropdown"
                    v-if="authUser && (authUser.type === 'superadmin' || authUser.type === 'admin' || authUser.type === 'vendor')">
                    <a href="" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"
                        :class="{ 'active': ['/product/list', '/product/slider-list'].includes($page.url) }">
                        <i class="fa fa-shopping-bag"></i> Product Manage
                    </a>
                    <div class="dropdown-menu bg-transparent border-0">
                        <Link :href="route('show.product')" class="dropdown-item"
                            :class="{ 'active': $page.url === '/product/list' }">
                        <i class="fa fa-chevron-circle-right me-2"></i> Products
                        </Link>
                        <Link :href="route('show.product.slider')" class="dropdown-item"
                            :class="{ 'active': $page.url === '/product/slider-list' }"> <i
                            class="fa fa-chevron-circle-right me-2"></i> Product
                        Slider
                        </Link>
                    </div>
                </div>

                <!-- coupon manage -->
                <Link :href="route('show.coupon')" class="nav-item nav-link" :class="{ 'active' : route().current('show.coupon') }"><i class="fa fa-gift me-2"></i>Coupon</Link>
                <!-- user manage -->
                <div class="nav-item dropdown"
                    v-if="authUser && authUser.type === 'superadmin' || authUser && authUser.type === 'admin'">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" :class="{ 'active': ['/user-list'].includes($page.url) }"><i
                            class="fa fa-user me-2"></i>User Manage</a>

                    <div class="dropdown-menu bg-transparent border-0">
                        <Link :href="route('get.user.list')" class="dropdown-item" :class="{ 'active' : route().current('get.user.list') }"> <i class="fa fa-chevron-circle-right me-2"></i> User
                        </Link>
                        <Link href="" class="dropdown-item"> <i class="fa fa-chevron-circle-right me-2"></i> Subscriber
                        </Link>
                    </div>
                </div>

                <a href="form.html" class="nav-item nav-link"><i class="fa fa-keyboard me-2"></i>Forms</a>
                <a href="table.html" class="nav-item nav-link"><i class="fa fa-table me-2"></i>Tables</a>
                <a href="chart.html" class="nav-item nav-link"><i class="fa fa-chart-bar me-2"></i>Charts</a>
                <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i
                            class="far fa-file-alt me-2"></i>Pages</a>
                    <div class="dropdown-menu bg-transparent border-0">
                        <a href="signin.html" class="dropdown-item">Sign In</a>
                        <a href="signup.html" class="dropdown-item">Sign Up</a>
                        <a href="404.html" class="dropdown-item">404 Error</a>
                        <a href="blank.html" class="dropdown-item">Blank Page</a>
                    </div>
                </div>
            </div>
        </nav>
    </div>
    <!-- Sidebar End -->
</template>

<style scoped>
.nav-link.dropdown-toggle::after {
    display: inline-block;
    margin-left: 0.5em;
    vertical-align: middle;
    content: "";
    border-top: 0.3em solid;
    border-right: 0.3em solid transparent;
    border-left: 0.3em solid transparent;
}
</style>
