<script setup>
import BaseHeader from "@/Layouts/Site/Partials/Header.vue";
import BaseFooter from "@/Layouts/Site/Partials/Footer.vue";
</script>

<template>
    <div id="page-container">
        <div id="page-loader"></div>

        <BaseHeader>
            <slot name="header"></slot>
        </BaseHeader>

        <div id="main-container">
            <slot></slot>
        </div>

        <BaseFooter>
            <slot name="footer"></slot>
        </BaseFooter>
    </div>
</template>
