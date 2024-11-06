<script setup>
import InputError from "@/Components/Admin/InputError.vue";
import InputLabel from "@/Components/Admin/InputLabel.vue";
import PrimaryButton from "@/Components/Admin/PrimaryButton.vue";
import TextInput from "@/Components/Admin/TextInput.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import ApplicationLogo from "@/Components/Admin/ApplicationLogo.vue";

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: "",
    password: "",
    remember: false,
});

const submit = () => {
    form.post(route("login"), {
        onFinish: () => form.reset("password"),
    });
};
</script>

<template>
    <Head title="Log in" />

    <div class="container" style="min-height: 100vh">
        <div class="row">
            <div class="col-lg-12 d-flex">
                <div class="w-100">
                    <!-- Header -->
                    <div class="text-center mb-2">
                        <div class="d-lg-flex justify-content-center">
                            <ApplicationLogo />
                            <div class="h5 fw-bold">{{ $t("site_title") }}</div>
                        </div>
                        <p class="text-muted mb-2">{{ $t("login_text") }}</p>
                    </div>
                    <form @submit.prevent="submit">
                        <div class="row justify-content-center mt-4">
                            <div class="col-sm-8 col-lg-4">
                                <InputLabel for="email" :value="$t('login_id')" />
                                <TextInput id="email" type="email" class="mt-1 d-block py-2" v-model="form.email" required autofocus autocomplete="username" />
                                <InputError class="mt-2" :message="form.errors.email" />
                            </div>
                        </div>

                        <div class="row justify-content-center mt-4">
                            <div class="col-sm-8 col-lg-4">
                                <InputLabel for="password" :value="$t('password')" />
                                <TextInput id="password" type="password" class="mt-1 d-block py-2" v-model="form.password" required autocomplete="current-password" />
                                <InputError class="mt-2" :message="form.errors.password" />
                            </div>
                        </div>

                        <div class="row justify-content-center">
                            <div class="col-sm-8 col-lg-4">
                                <div class="d-flex items-center justify-content-end mt-4">
                                    <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing"
                                        ><i class="fa fa-fw fa-sign-in-alt me-1"></i> {{ $t("login") }}
                                    </PrimaryButton>
                                </div>
                            </div>
                        </div>

                        <div class="row justify-content-center">
                            <div class="col-sm-8 col-lg-4">
                                <div class="d-flex items-center justify-content-end mt-4">
                                    <div class="dropdown d-inline-block ms-2">
                                        <button
                                            type="button"
                                            class="btn btn-lg btn-alt-secondary d-flex align-items-center"
                                            id="page-header-user-dropdown"
                                            data-bs-toggle="dropdown"
                                            aria-haspopup="true"
                                            aria-expanded="false"
                                        >
                                            <i class="fa-solid fa-globe d-inline-block opacity-50 ms-1 mt-1"></i>
                                            <span class="d-inline-block ms-2">{{ $t("language") }}</span>
                                            <i class="fa fa-fw fa-angle-down d-inline-block opacity-50 ms-1 mt-1"></i>
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
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<style lang="scss" scoped>
.container {
    margin-top: 150px;
}
</style>
