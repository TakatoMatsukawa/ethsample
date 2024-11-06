<script setup>
import { Link } from "@inertiajs/vue3";
const footerMenu = {
    manuscript: "古文書", // 仮
};
</script>

<template>
    <footer>
        <div class="container-fluid py-5 p-sm-5">
            <div class="row justify-content-center">
                <div class="col-12 col-md-10 d-sm-flex justify-content-between"></div>
            </div>
        </div>
    </footer>
</template>

<style lang="scss" scoped>
footer {
    background-color: #c0c0c0;
    color: #fff;
    a {
        text-decoration: none;
        color: #fff;
    }
}
.footer-logo {
    height: 12rem;
}
ul.footer-menu {
    list-style: none;
    padding-left: unset;
    li {
        margin-bottom: 0.75rem;
        &::before {
            content: "\f0da";
            font-family: "Font Awesome 6 Free";
            font-weight: 900;
            margin-right: 0.5rem;
            display: inline-block;
        }
    }
}
</style>
