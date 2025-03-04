<script setup>
import { Link, usePage } from '@inertiajs/vue3';

const page = usePage();
const authUser = page.props.authUser.authenticatedUser;
</script>
<template>
    <!-- Topbar Start -->
    <div class="container-fluid bg-info">
        <div class="py-1 px-xl-5 d-flex justify-content-between">
            <div class="d-none d-lg-block">
                <div class="d-inline-flex align-items-center h-100">
                    <Link class="text-white" href="" style="margin-right: 10px;">About</Link>
                    <Link class="text-white" href="" style="margin-right: 10px;">Contact</Link>
                    <Link class="text-white" href="" style="margin-right: 10px;">Help</Link>
                    <Link class="text-white" href="" style="margin-right: 10px;">FAQs</Link>
                </div>
            </div>

            <div class="text-center">
                <Link :href="route('show.vendor.account.create.page')" type="button" class="btn btn-sm btn-light"
                    style="margin-right: 10px;">Become a Saller</Link>

                <div class="btn-group">
                    <button type="button" class="btn btn-sm btn-light dropdown-toggle" data-bs-toggle="dropdown">
                        My Account
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                        <template v-if="authUser">
                            <Link :href="route('show.profile')" class="dropdown-item"> <i class="fa fa-user me-2"></i> Profile</Link>
                            <Link href="#" class="dropdown-item"> <i class="fa fa-heart me-2"></i> Wishlist</Link>
                            <Link :href="route('get.all.order')" class="dropdown-item"> <i class="fa fa-box me-2"></i> All Orders</Link>
                            <Link href="#" class="dropdown-item"> <i class="fa fa-shopping-bag me-2"></i> Cart</Link>
                            <Link href="#" class="dropdown-item"> <i class="fa fa-star me-2"></i> Reviews</Link>
                            <Link :href="route('user.logout')" class="dropdown-item"> <i class="fa fa-arrow-left me-2"></i> Logout</Link>
                        </template>
                        <template v-else>
                            <Link :href="route('show.user.login')" class="dropdown-item"> <i class="fa fa-arrow-right me-2"></i> Sign in</Link>
                        </template>

                    </div>
                </div>
            </div>
        </div>
        <div class="row align-items-center bg-light py-3 px-xl-5 d-none d-lg-flex">
            <div class="col-lg-4">
                <Link href="/" class="text-decoration-none">
                <span class="h1 text-uppercase text-info bg-dark px-2">Mini</span>
                <span class="h1 text-uppercase text-light bg-info px-2 ml-n1">Shop</span>
                </Link>
            </div>
            <div class="col-lg-4 col-6 text-left">
                <form action="">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search for products">
                        <span class="input-group-text bg-transparent text-info">
                            <i class="fa fa-search"></i>
                        </span>
                    </div>
                </form>
            </div>
            <div class="col-lg-4 col-6">
                <p class="m-0" style="text-align: right;">Customer Service</p>
                <h6 class="m-0" style="text-align: right;">+012 345 6789</h6>
            </div>
        </div>
    </div>
    <!-- Topbar End -->
</template>
