<script setup>
import { Link, usePage, useForm, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const page = usePage();

const allShippingAddresses = ref(page.props.shipping_addresses || []);
const shipping_addresses = computed(() => allShippingAddresses.value);
const showShippingForm = ref(false);
const delivaryCharge = '50';

//checkout products sesstion data
const selected_cart_items = page.props.selected_cart_items || [];
const total = page.props.total || 0;
const payable = parseFloat(total) + parseFloat(delivaryCharge)

const selectedShipping = ref(null);
const paymentMethod = ref(null);
const isProcessing = ref(false);


//=================create shipping address===============//
const form = useForm({
    id: null,
    ship_name: '',
    ship_add: '',
    ship_city: '',
    ship_state: '',
    zip: '',
    ship_country: '',
    ship_phone: '',
});

//set edit item
const editShippingAddress = (shipping_address) => {
    form.id = shipping_address.id;
    form.ship_name = shipping_address.ship_name;
    form.ship_add = shipping_address.ship_add;
    form.ship_city = shipping_address.ship_city;
    form.ship_state = shipping_address.ship_state;
    form.zip = shipping_address.zip;
    form.ship_country = shipping_address.ship_country;
    form.ship_phone = shipping_address.ship_phone;
    showShippingForm.value = true;
};

//create and update function
function saveAddress() {
    const routeName = form.id ? 'update.ship.address' : 'create.ship.address';
    const params = form.id ? { id: form.id } : {};

    form.post(route(routeName, params), {
        preserveScroll: true,
        onSuccess: () => {
            successToast(page.props.flash.message);

            if (form.id) {
                const index = allShippingAddresses.value.findIndex(item => item.id === form.id);
                if (index !== -1) {
                    allShippingAddresses.value[index] = { ...form };
                }
            } else {
                allShippingAddresses.value.push({ ...form });
            }

            form.reset();
            showShippingForm.value = false;
        },
        onError: (errors) => {
            if (errors.ship_name) {
                errorToast(errors.ship_name);
            } else if (errors.ship_add) {
                errorToast(errors.ship_add);
            } else if (errors.zip) {
                errorToast(errors.zip);
            } else if (errors.ship_phone) {
                errorToast(errors.ship_phone);
            }
        }
    });
}


//====================delete shipping address=======================//
function deleteShippingAddress(shipping_address) {
    if (confirm('Are you sure you want to delete this shipping address?')) {
        router.delete(route("delete.ship.address", { id: shipping_address.id }), {
            onSuccess: () => {
                successToast(page.props.flash.message);
                allShippingAddresses.value = allShippingAddresses.value.filter(addr => addr.id !== shipping_address.id);
            }
        });
    }
}


//=====================place order ==========================//
const placeOrder = async () => {
    if (!selectedShipping.value) {
        errorToast("Please select a shipping address.");
        return;
    }
    if (!paymentMethod.value) {
        errorToast("Please select a payment method.");
        return;
    }

    isProcessing.value = true;

    try {
        const response = await axios.post('/place-order', {
            shipping_details: selectedShipping.value,
            cart_items: selected_cart_items,
            total,
            delivery_charge: delivaryCharge,
            payable,
            payment_method: paymentMethod.value
        });

        successToast(response.data.message || "Order placed successfully!");
        window.location.href = "/order-success";
    } catch (error) {
        // console.error("Order failed:", error);
        errorToast("Something went wrong! Please try again.");
    } finally {
        isProcessing.value = false;
    }
}

</script>

<template>
    <!-- Breadcrumb Start -->
    <div class="container-fluid">
        <div class="row px-xl-5">
            <div class="col-12">
                <nav class="breadcrumb bg-light mb-30 p-3">
                    <Link :href="route('show.home.page')" class="breadcrumb-item text-dark">Home</Link>
                    <span class="breadcrumb-item active">Checkout</span>
                </nav>
            </div>
        </div>
    </div>
    <!-- Breadcrumb End -->

    <!-- Checkout Start -->
    <div class="container-fluid">
        <div class="row px-xl-5">
            <div class="col-lg-8">
                <h5 class="section-title position-relative text-uppercase mb-3"><span class="bg-light"
                        style="padding:3px 10px">Billing
                        Address</span></h5>
                <div class="bg-light p-30" style="margin-bottom: 15px;">
                    <div class="row">
                        <div class="col-md-12" style="margin-top: 5px;">
                            <div class="ship_div d-flex align-items-center justify-content-between"
                                v-for="(shipping_address, index) in shipping_addresses" :key="index">

                                <div class="d-flex align-items-center">
                                    <!-- Radio Button -->
                                    <input type="radio" class="custom-control-input radio-btn" :id="'shipping' + index"
                                        name="shipping_option" :value="shipping_address" v-model="selectedShipping">

                                    <!-- Label -->
                                    <label class="custom-control-label" :for="'shipping' + index">
                                        {{ shipping_address.ship_name }} | {{ shipping_address.ship_add }}
                                    </label>
                                </div>

                                <!-- Buttons -->
                                <div class="d-flex align-items-center">
                                    <button class="btn btn-sm btn-warning mx-2"
                                        @click="editShippingAddress(shipping_address)">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger"
                                        @click.prevent="deleteShippingAddress(shipping_address)">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </div>
                            </div>

                            <hr>
                        </div>
                        <div class="col-md-12" style="margin-top: 5px;">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="shipto"
                                    v-model="showShippingForm">
                                <label class="custom-control-label" for="shipto" data-bs-toggle="collapse"
                                    :data-bs-target="showShippingForm ? '#shipping-address' : ''">Ship to different
                                    address</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="collapse mb-5" id="shipping-address" :class="{ show: showShippingForm }">
                    <h5 class="section-title position-relative text-uppercase mb-3"><span class="bg-light"
                            style="padding:3px 10px">Shipping
                            Address</span></h5>
                    <div class="bg-light p-30">
                        <form action="" @submit.prevent="saveAddress">
                            <div class="row">
                                <div class="col-md-6 form-group mb">
                                    <label class="mb">Ship Name <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" placeholder="Enter your name"
                                        v-model="form.ship_name">
                                </div>

                                <div class="col-md-6 form-group mb">
                                    <label class="mb"> Country </label>
                                    <input class="form-control" type="text" placeholder="Enter your country name"
                                        v-model="form.ship_country">
                                </div>
                                <div class="col-md-6 form-group mb">
                                    <label class="mb">City </label>
                                    <input class="form-control" type="text" placeholder="Enter your city"
                                        v-model="form.ship_city">
                                </div>

                                <div class="col-md-6 form-group mb">
                                    <label class="mb"> State </label>
                                    <input class="form-control" type="text" placeholder="Enter your state"
                                        v-model="form.ship_state">
                                </div>

                                <div class="col-md-6 form-group mb">
                                    <label class="mb">Mobile Number <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" placeholder="Enter phone no."
                                        v-model="form.ship_phone">
                                </div>
                                <div class="col-md-6 form-group mb">
                                    <label class="mb">ZIP Code <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" placeholder="Enter zip code"
                                        v-model="form.zip">
                                </div>
                                <div class="col-12 form-group mb">
                                    <label class="mb"> Address <span class="text-danger">*</span></label>
                                    <textarea class="form-control" placeholder="Enter your address"
                                        v-model="form.ship_add"></textarea>
                                </div>

                                <div class="col-12 form-group mb mt-2">
                                    <button type="submit" class="btn btn-primary">Save Address</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <h5 class="section-title position-relative text-uppercase mb-3">
                    <span class="bg-light" style="padding:3px 10px">Order Total</span>
                </h5>
                <div class="bg-light p-30 mb-5">
                    <div v-for="(item, index) in selected_cart_items" :key="index"
                        class="d-flex justify-content-between">
                        <p>{{ item.products.product_name }} - {{ item.products.price }} * ({{ item.qty }})</p>
                        <p>{{ item.subtotal }}/-</p>
                    </div>
                    <div class="border-bottom pt-3 pb-2">
                        <div class="d-flex justify-content-between">
                            <h6>Subtotal</h6>
                            <h6>{{ total }} /-</h6>
                        </div>
                    </div>
                    <div class="border-bottom pt-3 pb-2">
                        <div class="d-flex justify-content-between">
                            <h6>Delivery Charge</h6>
                            <h6>{{ delivaryCharge }} /-</h6>
                        </div>
                    </div>
                    <div class="pt-2">
                        <div class="d-flex justify-content-between">
                            <h5>Payable</h5>
                            <h5>{{ payable }} /-</h5>
                        </div>
                    </div>
                </div>

                <h5 class="section-title position-relative text-uppercase mb-3">
                    <span class="bg-light" style="padding:3px 10px">Payment</span>
                </h5>
                <div class="bg-light p-30">
                    <div class="form-group mb-3">
                        <div class="custom-control custom-radio">
                            <input type="radio" class="custom-control-input" id="cod" value="Cash On Delivery"
                                v-model="paymentMethod">
                            <label class="custom-control-label" for="cod">Cash On Delivery</label>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <div class="custom-control custom-radio">
                            <input type="radio" class="custom-control-input" id="bank" value="Bank Transfer"
                                v-model="paymentMethod">
                            <label class="custom-control-label" for="bank">Bank Transfer</label>
                        </div>
                    </div>

                    <button class="btn btn-block btn-info w-100" @click="placeOrder" :disabled="isProcessing">
                        {{ isProcessing ? "Processing..." : "Place Order" }}
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- Checkout End -->
</template>

<style scoped>
.mb {
    margin-bottom: 6px;
}

.ship_div {
    margin-bottom: 10px;
    border: 1px solid #ddd;
    padding: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    border-radius: 5px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.radio-btn {
    position: relative;
    margin-right: 10px;
}


.radio-label {
    display: flex;
    align-items: center;
    font-weight: 500;
    cursor: pointer;
}
</style>
