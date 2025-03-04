<script setup>
import { Link, router, useForm, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();
const orders = page.props.orders || [];

//delete order
const orderDelete = (order) => {
    if (window.confirm("Are you sure you want to delete this order?")) {
        router.delete(route('delete.order', { id: order.id }), {
            onSuccess: () => {
                successToast(page.props.flash.message);
                window.location.href = "/products";
            },
            onError: () => {
                errorToast('Error deleting item');
            }
        });
    }
};
</script>

<template>
    <!-- Breadcrumb Start -->
    <div class="container-fluid">
        <div class="row px-xl-5">
            <div class="col-12">
                <nav class="breadcrumb bg-light mb-30 p-3">
                    <Link :href="route('show.home.page')" class="breadcrumb-item text-dark" href="#">Home</LInk>
                    <a class="breadcrumb-item text-dark" href="#">Profile</a>
                </nav>
            </div>
        </div>
    </div>
    <!-- Breadcrumb End -->

    <!-- Checkout Start -->
    <div class="container-fluid">
        <div class="row px-xl-5">

            <div class="col-lg-4">
                <h5 class="section-title position-relative text-uppercase mb-3"><span class="bg-light"
                        style="padding:3px 10px">Manage Account</span></h5>

                <div class="bg-light p-30 mb-5">
                    <div class="mb-3" style="text-align: center; padding: 3px 5px; border: 1px solid #ddd;">
                        <h6>{{ user_email?.email }}</h6>
                    </div>

                    <Link :href="route('show.profile')" class="mb-3 text-dark btn btn-info w-100 text-start">Profile
                    </Link>
                    <Link :href="route('get.all.order')" class="mb-3 text-dark btn btn-info w-100 text-start">My Order
                    </Link>
                    <Link href="#" class="mb-3 text-dark btn btn-info w-100 text-start">Wishlist</Link>
                    <Link :href="route('show.shipping.address')" class="mb-3 text-dark btn btn-info w-100 text-start">
                    Shipping Address</Link>
                </div>
            </div>

            <div class="col-lg-8">
                <h5 class="section-title position-relative text-uppercase mb-3"><span
                        class="bg-light">Information</span></h5>
                <!-- <div class="table-responsive" style="margin-bottom: 10px;">

                    <table class="table table-light table-hover text-center mb-0">
                        <thead class="thead-dark">
                            <tr>
                                <th class="text-start">Products</th>
                                <th class="text-start">Price</th>
                                <th class="text-start">Quantity</th>
                                <th class="text-start">Total</th>
                                <th class="text-start">Remove</th>
                            </tr>
                        </thead>
                        <tbody v-if="orders.length">
                            <tr >
                                <td>
                                    <div  class="d-flex justify-content-inline">
                                        <div style="margin-right: 10px; margin-bottom: 10px;">
                                            <img :src="item.product.image ? `/storage/${item.product.image}` : 'https://skala.or.id/wp-content/uploads/2024/01/dummy-post-square-1-1.jpg'"
                                            alt="Product image" style="width: 70px; height: 70px; object-fit: cover;"
                                            class="" />
                                        </div>
                                        <div>{{ item.product ?
                                        item.product.product_name : 'No product found' }}</div>
                                    </div>
                                </td>
                                <td>
                                    <div v-for="item in order.invoice_products" :key="item.id">
                                        <div>{{ item.price }}</div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                        <p v-else>No orders found</p>
                    </table>
                </div> -->


                <div class="mt-3" v-if="orders.length">
                    <div class="row align-items-center border p-3" v-for="order in orders" :key="order.id">
                        <!-- Product Image -->

                        <template v-for="item in order.invoice_products" :key="item.id">
                            <div class="col-auto">
                                <div>
                                    <div>
                                        <img :src="item.product.image ? `/storage/${item.product.image}` : 'https://skala.or.id/wp-content/uploads/2024/01/dummy-post-square-1-1.jpg'"
                                            alt="Product image" style="width: 70px; height: 70px; object-fit: cover;" />
                                    </div>

                                </div>
                            </div>
                            <div class="col">
                                <h6 class="mb-1 text-truncate" style="max-width: 250px;">
                                    {{ item.product.product_name }}
                                </h6>
                            </div>
                            <div class="col-auto">
                                <strong>৳ {{ order.grand_total }}</strong>
                            </div>
                            <div class="col-auto">
                                <span class="text-muted">Qty: <strong>{{ item.quantity }}</strong></span>
                            </div>
                            <div class="col-auto">
                                <button style="margin-right: 5px;">{{ order.delivary_status }}</button>
                                <button @click.prevent="orderDelete(order)" class="btn btn-sm btn-info">Cancel Order</button>
                            </div>
                        </template>
                    </div>
                </div>
                <p v-else>No orders found</p>
            </div>
        </div>
    </div>
    <!-- Checkout End -->
</template>

<style scoped></style>
