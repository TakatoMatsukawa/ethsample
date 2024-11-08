<script setup>
import { Link } from "@inertiajs/vue3";
import { useTemplateStore } from "@/stores/template";

const store = useTemplateStore();
</script>

<template>
    <!-- Header -->
    <header id="page-header">
        <!-- Header Content -->
        <div class="content-header w-100">
            <!-- Left Section -->
            <div class="d-flex align-items-center">
                <!-- Toggle Sidebar -->
                <button type="button" class="btn btn-sm btn-alt-secondary me-2 d-lg-none" @click="store.sidebar({ mode: 'toggle' })">
                    <i class="fa fa-fw fa-bars"></i>
                </button>
                <!-- END Toggle Sidebar -->
                {{ $t("admin_title") }}
            </div>
            <!-- END Left Section -->

            <!-- Right Section -->
            <div class="d-flex align-items-center">
                <div class="dropdown d-inline-block ms-2">
                    <button
                        type="button"
                        class="btn btn-sm btn-alt-secondary d-flex align-items-center"
                        id="page-header-user-dropdown"
                        data-bs-toggle="dropdown"
                        aria-haspopup="true"
                        aria-expanded="false"
                    >
                        <i class="fa-solid fa-globe d-none d-sm-inline-block opacity-50 ms-1 mt-1"></i>
                        <span class="d-none d-sm-inline-block ms-2">{{ $t("language") }}</span>
                        <i class="fa fa-fw fa-angle-down d-none d-sm-inline-block opacity-50 ms-1 mt-1"></i>
                    </button>
                    <!-- 言語切り替え対応 -->
                    <div class="dropdown-menu dropdown-menu-md dropdown-menu-end p-0 border-0" aria-labelledby="page-header-user-dropdown">
                        <div class="p-2">
                            <a class="dropdown-item d-flex align-items-center justify-content-between" v-bind:href="route('lang.change', 'en')">
                                <span class="fs-sm fw-medium">{{ $t("english") }}</span>
                            </a>
                        </div>
                        <div role="separator" class="dropdown-divider m-0"></div>
                        <div class="p-2">
                            <a class="dropdown-item d-flex align-items-center justify-content-between" v-bind:href="route('lang.change', 'am')">
                                <span class="fs-sm fw-medium">{{ $t("amharic") }}</span>
                            </a>
                        </div>
                        <div role="separator" class="dropdown-divider m-0"></div>
                        <div class="p-2">
                            <a class="dropdown-item d-flex align-items-center justify-content-between" v-bind:href="route('lang.change', 'ja')">
                                <span class="fs-sm fw-medium">{{ $t("japanese") }}</span>
                            </a>
                        </div>
                    </div>
                </div>
                <!-- User Dropdown -->
                <div class="dropdown d-inline-block ms-2">
                    <button
                        type="button"
                        class="btn btn-sm btn-alt-secondary d-flex align-items-center"
                        id="page-header-user-dropdown"
                        data-bs-toggle="dropdown"
                        aria-haspopup="true"
                        aria-expanded="false"
                    >
                        <i class="fas fa-user-circle"></i>
                        <span class="d-none d-sm-inline-block ms-2">{{ $page.props.auth.user.name }}</span>
                        <i class="fa fa-fw fa-angle-down d-none d-sm-inline-block opacity-50 ms-1 mt-1"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-md dropdown-menu-end p-0 border-0" aria-labelledby="page-header-user-dropdown">
                        <div class="p-2">
                            <a class="dropdown-item d-flex align-items-center justify-content-between" href="">
                                <span class="fs-sm fw-medium">{{ $t("profile") }}</span>
                            </a>
                        </div>
                        <div role="separator" class="dropdown-divider m-0"></div>
                        <div class="p-2">
                            <Link :href="route('logout')" method="post" class="text-black dropdown-item d-flex align-items-center justify-content-between" as="button">
                                <span class="fs-sm fw-medium"> {{ $t("logout") }} </span></Link
                            >
                        </div>
                    </div>
                </div>
                <!-- END User Dropdown -->
            </div>
            <!-- END Right Section -->
        </div>
        <!-- END Header Content -->
    </header>
    <!-- END Header -->
</template>

<script>
export default {
    computed: {
        user() {
            return this.$page.props.auth.user;
        },
    },
};
</script>
