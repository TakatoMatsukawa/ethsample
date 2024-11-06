<script setup>
import AuthenticatedLayout from "@/Layouts/Admin/AuthenticatedLayout.vue";
import { Link, useForm } from "@inertiajs/vue3";
import Confirm from "@/Pages/Admin/Manuscript/EditConfirm.vue";
import InputForm from "@/Pages/Admin/Manuscript/EditForm.vue";
import { ref } from "vue";

const props = defineProps({
    errors: Object,
    license_list: Object,
    is_public: Boolean,
    select_license: String,
    input_name: String,
    input_writer: String,
    input_era: String,
    input_description: String,
    input_file_thumbnail: Object,
    is_registered_thumbnail: Boolean,
    thumbnail: Object,
    pdfs: Object,
    state_list: Object,
    id: String,
});
const pdfLength = Object.keys(props.pdfs).length;
const pdfStates = Array(pdfLength).fill(props.state_list["unchanged"]);

const form = useForm({
    is_public: props.is_public, // 公開状態
    select_license: props.select_license, // ライセンス
    input_name: props.input_name, // 資料名
    input_writer: props.input_writer, // 作者名
    input_era: props.input_era, // 時代
    input_description: props.input_description, // 内容
    input_file_thumbnail: props.input_file_thumbnail, // サムネイル
    is_registered_thumbnail: props.thumbnail.is_registered,
    pdfs: props.pdfs,
    pdf_states: pdfStates,
});

const isConfirm = ref(false);

const onConfirm = () => {
    form.post(
        route("manuscript.manuscript_edit_validate", {
            manuscript: props.id,
        }),
        {
            onSuccess: () => switchConfirm(),
        }
    );
};

const switchConfirm = () => {
    if (form.processing || form.hasErrors) {
        isConfirm.value = false;
        return;
    }
    isConfirm.value = !isConfirm.value;
};
</script>

<template>
    <AuthenticatedLayout>
        <!-- Sub Header -->
        <div class="bg-body-light">
            <div class="content content-full w-100 py-2">
                <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
                    <div class="flex-grow-1">
                        <h4 class="h4 fw-bold mb-2">{{ $t("update_manuscript") }}</h4>
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

                    <div class="row mb-4" v-if="isConfirm">
                        <div class="col-md-5 offset-md-3">
                            <button type="button" @click="form.post(route('manuscript.manuscript_update', { manuscript: props.id }))" class="btn btn-main btn-lg px-7">{{ $t("update") }}</button>
                        </div>
                        <div class="col-md-4">
                            <button @click="switchConfirm" type="button" class="btn btn-dark btn-lg ms-2 px-6">{{ $t("back") }}</button>
                        </div>
                    </div>
                    <div class="row mb-4" v-else>
                        <div class="col-md-5 offset-md-3">
                            <button type="button" @click="onConfirm" :disabled="form.processing" class="btn btn-main btn-lg px-7">{{ $t("confirm") }}</button>
                        </div>
                        <div class="col-md-4">
                            <Link :href="route('manuscript.manuscript_list')" type="button" class="btn btn-dark btn-lg ms-2 px-6">{{ $t("back") }} </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END Page Content -->
    </AuthenticatedLayout>
</template>
