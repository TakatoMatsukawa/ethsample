<script setup>
import AuthenticatedLayout from "@/Layouts/Admin/AuthenticatedLayout.vue";
import { Link, useForm } from "@inertiajs/vue3";
import Confirm from "@/Pages/Admin/Manuscript/AddTifConfirm.vue";
import InputForm from "@/Pages/Admin/Manuscript/AddTifForm.vue";
import { ref } from "vue";

const props = defineProps({
    errors: Object,
    input_image: Object,
    id: String,
});
const form = useForm({
    input_image: props.input_image, // サムネイル
});

const isConfirm = ref(false);

const onConfirm = () => {
    form.post(
        route("manuscript.manuscript_add_tif_validate", {
            manuscript: props.id,
        }),
        {
            onSuccess: () => form.post(route("manuscript.manuscript_store_tif", { manuscript: props.id })),
        }
    );
};
</script>

<template>
    <AuthenticatedLayout>
        <!-- Sub Header -->
        <div class="bg-body-light">
            <div class="content content-full w-100 py-2">
                <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
                    <div class="flex-grow-1">
                        <h4 class="h4 fw-bold mb-2">{{ $t("create_tif") }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <!-- END Sub Header -->

        <!-- Page Content -->
        <div class="content">
            <div class="block block-rounded">
                <div class="block-content">
                    <div v-if="isConfirm">
                        <Confirm :form="form" :parent_props="props"></Confirm>
                    </div>
                    <div v-show="!isConfirm">
                        <InputForm :form="form" :parent_props="props"></InputForm>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-5 offset-md-3">
                            <button type="button" @click="onConfirm" :disabled="form.processing" class="btn btn-main btn-lg px-7">{{ $t("add") }}</button>
                        </div>
                        <div class="col-md-4">
                            <Link :href="route('manuscript.manuscript_list')" type="button" class="btn btn-dark btn-lg ms-2 px-6">{{ $t("back") }}</Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END Page Content -->
    </AuthenticatedLayout>
</template>
