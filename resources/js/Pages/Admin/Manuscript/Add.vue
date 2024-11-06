<script setup>
import AuthenticatedLayout from "@/Layouts/Admin/AuthenticatedLayout.vue";
import { Link, useForm } from "@inertiajs/vue3";
import Confirm from "@/Pages/Admin/Manuscript/AddConfirm.vue";
import InputForm from "@/Pages/Admin/Manuscript/AddForm.vue";
import { ref } from "vue";

const props = defineProps({
    errors: Object,
    license_list: Array,
    select_license: String,
    input_name: String,
    input_writer: String,
    input_era: String,
    input_description: String,
    input_file_thumbnail: Object,
    input_pdfs: Object,
});
const form = useForm({
    is_public: false,
    select_license: props.select_license, // ライセンス
    input_name: props.input_name, // 資料名
    input_writer: props.input_writer, // 作者名
    input_era: props.input_era, // 時代
    input_description: props.input_description, // 内容
    input_file_thumbnail: props.input_file_thumbnail, // サムネイル
    input_pdfs: props.input_pdfs, // PDFファイル
});

const isConfirm = ref(false);
const pdfInputs = ref([null]);

const onConfirm = () => {
    form.input_pdfs = pdfInputs.value;
    form.post(route("manuscript.manuscript_add_validate"), {
        onSuccess: () => switchConfirm(),
    });
};

const switchConfirm = () => {
    if (form.processing || form.hasErrors) {
        isConfirm.value = false;
        console.log(form);
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
                        <h4 class="h4 fw-bold mb-2">{{ $t("add_manuscript") }}</h4>
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
                        <Confirm :form="form" :parent_props="props" :pdfInputs="pdfInputs"></Confirm>
                    </div>
                    <div v-show="!isConfirm">
                        <InputForm :form="form" :parent_props="props" :pdfInputs="pdfInputs"></InputForm>
                    </div>

                    <div class="row mb-4" v-if="isConfirm">
                        <div class="col-md-5 offset-md-3">
                            <button type="button" @click="form.post(route('manuscript.manuscript_store'))" class="btn btn-main btn-lg px-7">{{ $t("add") }}</button>
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
                            <Link :href="route('manuscript.manuscript_list')" type="button" class="btn btn-dark btn-lg ms-2 px-6">{{ $t("back") }}</Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END Page Content -->
    </AuthenticatedLayout>
</template>
