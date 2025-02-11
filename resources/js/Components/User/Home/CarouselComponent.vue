<script setup>
import { Link, usePage } from '@inertiajs/vue3';
const list = usePage();
const sliders = list.props.slider || [];
</script>

<template>
    <!-- Carousel Start -->
    <div class="container-fluid mb-3">
        <div class="row px-xl-5">
            <div class="col-lg-8">
                <div id="header-carousel" class="carousel slide carousel-fade mb-30 mb-lg-0" data-bs-ride="carousel">
                    <ol class="carousel-indicators">
                        <li v-for="(slide, index) in sliders" :key="slide.id" data-bs-target="#header-carousel"
                            :data-bs-slide-to="index" :class="{ active: index === 0 }"></li>
                    </ol>
                    <div class="carousel-inner">
                        <div v-for="(slide, index) in sliders" :key="slide.id" class="carousel-item position-relative"
                            :class="{ active: index === 0 }" style="height: 430px;">
                            <img class="position-absolute w-100 h-100" :src="`/storage/${slide.image}`"
                                style="object-fit: cover;">
                            <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                                <div class="p-3" style="max-width: 700px;">
                                    <h1 class="display-4 text-white mb-3 animate__animated animate__fadeInDown">
                                        {{ slide.title }}
                                    </h1>
                                    <p class="mx-md-5 px-5 animate__animated animate__bounceIn">
                                        {{ slide.short_description }}
                                    </p>
                                    <Link class="btn btn-outline-light py-2 px-4 mt-3 animate__animated animate__fadeInUp"
                                        :href="route('show.product.details.page', {id:slide.product_id})">Shop Now</Link>
                                </div>
                            </div>
                        </div>
                    </div>
                    <a class="carousel-control-prev" href="#header-carousel" role="button" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                    </a>
                    <a class="carousel-control-next" href="#header-carousel" role="button" data-bs-slide="next">
                        <span class="carousel-control-next-icon"></span>
                    </a>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="product-offer mb-30" style="height: 200px;">
                    <img class="img-fluid"
                        src="https://img.freepik.com/free-psd/black-friday-super-sale-web-banner-template_106176-4453.jpg?t=st=1738825547~exp=1738829147~hmac=01a0f324f65ade6a144762059f0590fab2c6b8280eb4cd6b0e39bb33eda3cf35&w=740"
                        alt="">
                    <div class="offer-text">
                        <h6 class="text-white text-uppercase">Save 20%</h6>
                        <h3 class="text-white mb-3">Special Offer</h3>
                        <a href="" class="btn btn-info">Shop Now</a>
                    </div>
                </div>
                <div class="product-offer mb-30" style="height: 200px;">
                    <img class="img-fluid"
                        src="https://img.freepik.com/free-psd/black-friday-super-sale-web-banner-template_106176-1647.jpg?t=st=1738825560~exp=1738829160~hmac=b7f94d50bcd5ce9e8bf838a79b3cf3a35ea195af4e2c27eae9df30602d3bbd10&w=740"
                        alt="">
                    <div class="offer-text">
                        <h6 class="text-white text-uppercase">Save 20%</h6>
                        <h3 class="text-white mb-3">Special Offer</h3>
                        <a href="" class="btn btn-info">Shop Now</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Carousel End -->
</template>

<style scoped></style>
