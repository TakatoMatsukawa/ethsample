<script setup>
import { Link } from "@inertiajs/vue3";
import messages from "@/lang/messages.js";
const offCanvasMenu = {
    manuscript: messages[__locale].manuscript, // 仮
};
</script>

<template>
    <header class="header sticky-top">
        <nav class="navbar bg-body-tertiary">
            <div class="container-lg">
                <Link class="navbar-brand header-brand" href="/">
                    <span>{{ $t("site_title") }}</span>
                    <img class="header-brand__image img-fluid ms-3" src="/assets/img/Flag_of_Ethiopia.svg" />
                </Link>
                <div class="d-flex">
                    <form class="mx-3 header-search d-none d-lg-flex form-search" action="/search" method="GET" role="search">
                        <div class="form-search__group input-group input-group-sm header-search__group">
                            <span class="form-search__label input-group-text header-search__label" id="cross-search1"> {{ $t("cross_search") }} </span>
                            <input
                                id="inputKeyword"
                                type="text"
                                name="kw"
                                class="form-control form-control-alt form-search__input header-search__input"
                                :placeholder="$t('search_keyword')"
                                aria-label="cross-search"
                                aria-describedby="headerSearchButton"
                                maxlength="50"
                            />
                            <button type="submit" class="btn btn-primary form-search__btn header-search__button" id="headerSearchButton">
                                <span class="visually-hidden">{{ $t("search") }}</span
                                ><i class="fas fa-magnifying-glass px-3 text-light"></i>
                            </button>
                        </div>
                    </form>
                    <!-- END Search Form -->
                    <div class="mx-3 dropdown my-auto d-none d-lg-block">
                        <a class="btn btn-primary dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-solid fa-globe ms-1 mt-1"></i> <span class="ms-2">{{ $t("language") }}</span>
                        </a>

                        <ul class="dropdown-menu">
                            <div class="p-2">
                                <a class="dropdown-item d-flex align-items-center justify-content-between" v-bind:href="route('lang.change', 'en')">
                                    <span class="fs-sm fw-medium">{{ $t("english") }}</span>
                                </a>
                            </div>
                            <div class="p-2">
                                <a class="dropdown-item d-flex align-items-center justify-content-between" v-bind:href="route('lang.change', 'am')">
                                    <span class="fs-sm fw-medium">{{ $t("amharic") }}</span>
                                </a>
                            </div>
                            <div class="p-2">
                                <a class="dropdown-item d-flex align-items-center justify-content-between" v-bind:href="route('lang.change', 'ja')">
                                    <span class="fs-sm fw-medium">{{ $t("japanese") }}</span>
                                </a>
                            </div>
                        </ul>
                    </div>

                    <button class="navbar-toggler rounded-1" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
                        <span class="navbar-toggler-icon"></span>
                        <span class="fw-bold d-block text-nowrap menu">{{ $t("menu") }}</span>
                    </button>
                    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel" data-bs-scroll="true">
                        <div class="offcanvas-header">
                            <h5 class="offcanvas-title text-wrap fs-sm" id="offcanvasNavbarLabel">{{ $t("site_title") }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                        </div>
                        <div class="offcanvas-body">
                            <ul class="navbar-nav nav-pills justify-content-end flex-grow-1 pe-3">
                                <li class="nav-item px-3" v-for="(value, key) in offCanvasMenu" :key="key">
                                    <Link :href="route(key)" class="nav-link">{{ value }}</Link>
                                </li>
                            </ul>
                            <form action="/search" method="GET" class="mx-3 header-search d-flex d-lg-none mt-2" role="search">
                                <div class="input-group input-group-sm">
                                    <input
                                        type="text"
                                        name="kw"
                                        class="py-2 form-control form-control-alt form-search__input header-search__input"
                                        :placeholder="$t('search_keyword')"
                                        aria-describedby="headerSearchButtonOffcanvas"
                                        maxlength="50"
                                    />
                                    <button class="btn btn-primary px-2 px-sm-3 py-1 header-search__button" type="submit" id="headerSearchButtonOffcanvas">
                                        <span class="visually-hidden">{{ $t("search") }}</span>
                                        <i class="fas fa-magnifying-glass px-3"></i>
                                    </button>
                                </div>
                            </form>
                            <div class="mx-3 dropdown my-auto d-lg-none mt-2">
                                <a class="btn btn-primary dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-globe ms-1 mt-1"></i> <span class="ms-2">{{ $t("language") }}</span>
                                </a>

                                <ul class="dropdown-menu">
                                    <div class="p-2">
                                        <a class="dropdown-item d-flex align-items-center justify-content-between" v-bind:href="route('lang.change', 'en')">
                                            <span class="fs-sm fw-medium">{{ $t("english") }}</span>
                                        </a>
                                    </div>
                                    <div class="p-2">
                                        <a class="dropdown-item d-flex align-items-center justify-content-between" v-bind:href="route('lang.change', 'am')">
                                            <span class="fs-sm fw-medium">{{ $t("amharic") }}</span>
                                        </a>
                                    </div>
                                    <div class="p-2">
                                        <a class="dropdown-item d-flex align-items-center justify-content-between" v-bind:href="route('lang.change', 'ja')">
                                            <span class="fs-sm fw-medium">{{ $t("japanese") }}</span>
                                        </a>
                                    </div>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </header>
</template>

<style lang="scss" scoped>
.header {
    .navbar {
        .header-brand {
            &__image {
                height: 70px;
            }
        }
    }
    .offcanvas {
        .btn-close {
            position: relative;
            &::before {
                position: absolute;
                bottom: -1rem;
                left: 50%;
                transform: translateX(-50%);
                content: "Close";
                display: inline-block;
                font-size: 0.8rem;
                white-space: nowrap;
            }
        }
    }
    .menu {
        font-size: 0.7rem !important;
    }
}
</style>
