<script setup>
import { Link } from '@inertiajs/vue3';


</script>

<template>
    <div class="container success-container">
        <div class="success-box">
            <div class="check-icon">✔</div>
            <h2 class="text-success">Order Placed Successfully!</h2>
            <p class="text-muted">Thank you for your purchase. Your order ID is <strong>#12345</strong>.</p>
            <Link :href="route('get.all.order')" class="btn btn-primary">Continue Shopping</Link>
        </div>
    </div>
</template>

<style scoped>
.success-container {
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    text-align: center;
}

.success-box {
    max-width: 500px;
    background: #ffffff;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

.check-icon {
    font-size: 60px;
    color: #28a745;
}
</style>
