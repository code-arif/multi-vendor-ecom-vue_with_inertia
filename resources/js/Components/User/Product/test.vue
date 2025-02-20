<script setup>
import { ref, computed } from 'vue';
import { Link, usePage, useForm } from '@inertiajs/vue3';

const list = usePage();
const productDetails = list.props.productDetails || {};
const specifications = productDetails.specifications || {};

// Convert size and color strings to arrays
const sizes = specifications.size ? specifications.size.split(', ') : [];
const colors = specifications.color ? specifications.color.split(', ') : [];

// Selected options
const selectedSize = ref(sizes.length ? sizes[0] : '');
const selectedColor = ref(colors.length ? colors[0] : '');
const quantity = ref(1);

// Methods
const increaseQuantity = () => {
    quantity.value++;
};

const decreaseQuantity = () => {
    if (quantity.value > 1) {
        quantity.value--;
    }
};

//======================cart functionallity======================//
const form = useForm({
    product_id: productDetails.id,
    qty: quantity || 1,
    size: selectedSize || '',
    color: selectedColor || '',
});

const addToCart = () => {
    form.post(route('create.cart'), {
        preserveScroll: true,
        onSuccess: () => alert('Product added to cart!'),
    });
};
</script>

<template>
    <!-- Shop Detail Start -->
    <div class="container-fluid pb-5">

        <div class="row px-xl-5">
          

            <div class="col-lg-7 h-auto mb-30">
                <div class="h-100 bg-light p-30">
                    <p class="mb-4">{{ productDetails.short_description }}</p>
                    <p class="mb-2"><Strong>Only </Strong> <span class="text-info">{{ productDetails.stock_quantity }}</span> left in stock</p>
                    <p class="mb-2"><Strong>Brand: </Strong> <span class="text-info"><Link :href="route('show.product.by.brand', {id:productDetails.brand.id })" class="text-info">{{ productDetails.brand?.name || "No Brand" }}</Link></span></p>
                    <p class="mb-2"><Strong>Remark: </Strong> <span class="text-info">{{ productDetails.remark }}</span></p>
                    <p class="mb-2"><Strong>SKU: </Strong> <span class="text-info">{{ productDetails.sku }}</span></p>
                    <p class="mb-4" style="border: 1px solid red ; padding: 5px;"><Strong>Availability: </Strong> <span class="text-info">{{ productDetails.stock_status }}</span></p>
                    <!-- Sizes -->
                    <div class="d-flex mb-3">
                        <strong class="text-dark" style="margin-right: 10px;">Sizes:</strong>
                        <div v-for="size in sizes" :key="size"
                            class="custom-control custom-radio custom-control-inline">
                            <input type="radio" class="custom-control-input" :id="'size-' + size" name="size"
                                v-model="selectedSize" :value="size">
                            <label class="custom-control-label" :for="'size-' + size">{{ size }}</label>
                        </div>
                    </div>

                    <!-- Colors -->
                    <div class="d-flex mb-4">
                        <strong class="text-dark" style="margin-right: 10px;">Colors:</strong>
                        <div v-for="color in colors" :key="color"
                            class="custom-control custom-radio custom-control-inline">
                            <input type="radio" class="custom-control-input" :id="'color-' + color" name="color"
                                v-model="selectedColor" :value="color">
                            <label class="custom-control-label" :for="'color-' + color">{{ color }}</label>
                        </div>
                    </div>

                    <!-- Quantity and Add to Cart -->
                    <div class="d-flex align-items-center mb-4 pt-2">
                        <div class="input-group quantity mr-3" style="width: 130px;">
                            <div class="input-group-btn">
                                <button class="btn btn-info btn-minus" @click="decreaseQuantity">
                                    <i class="fa fa-minus"></i>
                                </button>
                            </div>
                            <input type="text" class="form-control border-0 text-center" v-model="quantity" readonly>
                            <div class="input-group-btn">
                                <button class="btn btn-info btn-plus" @click="increaseQuantity">
                                    <i class="fa fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        <button class="btn btn-info px-3" style="margin-left: 10px;" @click="addToCart">
                            <i class="fa fa-shopping-cart"></i> Add To Cart
                        </button>

                        <button class="btn btn-info px-3" style="margin-left: 10px;">
                            <i class="fa fa-heart"></i> Add to Wish list
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Shop Detail End -->
</template>
