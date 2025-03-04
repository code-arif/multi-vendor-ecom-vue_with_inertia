<script setup>
import { ref, computed } from "vue";
import { Link, router, useForm, usePage } from "@inertiajs/vue3";

const page = usePage();
const cartProducts = ref(
    (page.props.cartProducts || []).map(item => ({ ...item, selected: false }))
);

// Function to increase quantity
const increaseQty = (cartItem) => {
    cartItem.qty++;
    updateSubtotal(cartItem);
    updateCart(cartItem);
};

// Function to decrease quantity
const decreaseQty = (cartItem) => {
    if (cartItem.qty > 1) {
        cartItem.qty--;
        updateSubtotal(cartItem);
        updateCart(cartItem);
    }
};

// Function to update subtotal
const updateSubtotal = (cartItem) => {
    cartItem.subtotal = cartItem.qty * cartItem.price;
};

// **Calculate cart summary only for selected items**
const cartSummary = computed(() => {
    let selectedItems = cartProducts.value.filter(item => item.selected);
    let subtotal = selectedItems.reduce((sum, item) => sum + (item.price * item.qty), 0);
    let total = subtotal;
    return { subtotal, total };
});

//==============================Update cart qty and price==================================//
const updateCart = (cartItem) => {
    router.patch(route('update.cart', { id: cartItem.id }), { qty: cartItem.qty }, {
        preserveScroll: true,
        onSuccess: () => {
            if (page.props.flash.status === true) {
                successToast(page.props.flash.message);
            } else {
                errorToast(page.props.flash.message);
            }
        },
        onError: () => {
            errorToast('Failed to update cart');
        }
    });
};


//==============================Delete cart item functionality============================//
const deleteItem = (cartItem) => {
    if (window.confirm("Are you sure you want to remove this item from the cart?")) {
        router.delete(route('delete.cart', { id: cartItem.id }), {
            onSuccess: () => {
                successToast(page.props.flash.message);
                cartProducts.value = cartProducts.value.filter(item => item.id !== cartItem.id);
            },
            onError: () => {
                errorToast('Error deleting cart item');
            }
        });
    }
};


//===========================apply coupon==================================//
const couponForm = useForm({
    coupon_code: "",
    category_ids: [],
});

// **Apply Coupon Function**
const applyCoupon = () => {
    const selectedProducts = cartProducts.value.filter(item => item.selected);

    if (selectedProducts.length === 0) {
        errorToast("Please select at least one product before applying the coupon.");
        return;
    }

    // Collect category_ids from selected products
    couponForm.category_ids = selectedProducts.map(item => item.products.category_id);

    couponForm.post(route("apply.coupon"), {
        onSuccess: () => {
            successToast(page.props.flash.message);
        },
        onError: () => {
            errorToast("Failed to apply coupon");
        }
    });
};

//=======================proceed to checkout===========================//
const proceedToCheckout = () => {
    let selectedItems = cartProducts.value.filter(item => item.selected);
    if (selectedItems.length === 0) {
        errorToast('Please select at least one product to checkout');
        return;
    }

    router.post(route('checkout.store'), {
        selectedProducts: selectedItems,
        total: cartSummary.value.total
    }, {
        preserveScroll: true,
        onSuccess: () => {
            successToast('Proceeding to checkout...');
            router.visit(route('show.checkout.page'));
        },
        onError: () => {
            errorToast('Failed to proceed to checkout');
        }
    });
};



</script>

<template>
    <!-- Breadcrumb Start -->
    <div class="container-fluid">
        <div class="row px-xl-5">
            <div class="col-12">
                <nav class="breadcrumb bg-light mb-30 p-3">
                    <Link class="breadcrumb-item text-dark" :href="route('show.home.page')">Home</Link>
                    <Link class="breadcrumb-item text-dark" :href="route('show.products.page')">Shop</Link>
                    <span class="breadcrumb-item active">Shopping Cart</span>
                </nav>
            </div>
        </div>
    </div>
    <!-- Breadcrumb End -->

    <!-- Cart Start -->
    <div class="container-fluid">
        <div class="row px-xl-5">
            <div class="col-lg-8 table-responsive" style="margin-bottom: 10px;">
                <table class="table table-light table-hover text-center mb-0">
                    <thead class="thead-dark">
                        <tr>
                            <th>Select</th>
                            <th class="text-start">Products</th>
                            <th class="text-start">Price</th>
                            <th class="text-start">Quantity</th>
                            <th class="text-start">Total</th>
                            <th class="text-start">Remove</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(cartItem, index) in cartProducts" :key="index">
                            <td class="pt-4">
                                <input class="form-check-input" type="checkbox" v-model="cartItem.selected" />
                            </td>
                            <td>
                                <div style="display: flex;">
                                    <div class="display-inline-flex">
                                        <img :src="cartItem.products.image ? `/storage/${cartItem.products.image}` : 'https://skala.or.id/wp-content/uploads/2024/01/dummy-post-square-1-1.jpg'"
                                            alt="Product image" style="width: 70px; height: 70px; object-fit: cover;"
                                            class="" />
                                    </div>
                                    <div class="text-start ms-3">
                                        <Link class="text-start"
                                            :href="route('show.product.details.page', { id: cartItem.products.id })">
                                        {{ cartItem.products.product_name }}
                                        </Link>

                                        <div>
                                            <span class="text-muted">Color: {{ cartItem.color }} ||</span>
                                            <span class="text-muted" style="padding-left: 10px;">Size: {{ cartItem.size
                                            }}</span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="text-danger text-start" style="padding-top: 30px;">৳: {{ cartItem.price }}/-</td>
                            <td>
                                <div class="input-group quantity mx-auto text-start"
                                    style="width: 100px; padding-top: 20px;">
                                    <button class="btn btn-sm btn-info btn-minus" @click="decreaseQty(cartItem)">
                                        <i class="fa fa-minus"></i>
                                    </button>
                                    <input type="text" class="form-control form-control-sm border-0 text-center"
                                        v-model="cartItem.qty" readonly>
                                    <button class="btn btn-sm btn-info btn-plus" @click="increaseQty(cartItem)">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                </div>
                            </td>
                            <td class="text-danger" style="padding-top: 30px;">{{ cartItem.subtotal }}</td>
                            <td style="padding-top: 30px;">
                                <button @click="deleteItem(cartItem)" class="btn btn-sm btn-outline-danger">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="col-lg-4">
                <form class="mb-30" @submit.prevent="applyCoupon">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Coupon Code"
                            v-model="couponForm.coupon_code">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-info">Apply Coupon</button>
                        </div>
                    </div>
                </form>

                <h5 class="section-title position-relative text-uppercase mb-3">
                    <span class="bg-light" style="padding: 5px 10px">Cart Summary</span>
                </h5>
                <div class="bg-light p-30 mb-5">
                    <div class="border-bottom pb-2">
                        <div class="d-flex justify-content-between mb-3">
                            <h6>Subtotal</h6>
                            <h6>{{ cartSummary.subtotal }}</h6>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <h6 class="font-weight-medium">Coupon Discount</h6>
                            <h6 class="font-weight-medium">0</h6>
                        </div>
                    </div>
                    <div class="pt-2">
                        <div class="d-flex justify-content-between mt-2">
                            <h5>Total</h5>
                            <h5>{{ cartSummary.total }}</h5>
                        </div>
                        <button class="btn btn-block btn-info font-weight-bold mt-3 w-100"
                            :disabled="cartSummary.total === 0" @click="proceedToCheckout">
                            Proceed To Checkout
                        </button>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Cart End -->
</template>
