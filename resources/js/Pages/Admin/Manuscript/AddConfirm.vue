<script setup>
import { Head } from "@inertiajs/vue3";
import { ref } from "vue";

const props = defineProps({
    form: Object,
    parent_props: Object,
});

const form = props.form;
const parent_props = props.parent_props;

// サムネイル
const thumbnailImageData = ref();
if (form.input_file_thumbnail) {
    thumbnailImageData.value = URL.createObjectURL(form.input_file_thumbnail);
}

const pdfData = ref([]);
Object.keys(form.input_pdfs).forEach(function (key) {
    if (form.input_pdfs[key]) {
        pdfData.value.push(URL.createObjectURL(form.input_pdfs[key]));
    } else {
        pdfData.value.push(null);
    }
});
</script>

<template>
    <Head :title="$t('confirm_page')" />
    <div class="row mb-4">
        <div class="col-md-3">{{ $t("publication_status") }}</div>
        <div class="col-md-9 border-bottom">{{ form.is_public ? $t("public") : $t("private") }}</div>
    </div>
    <div class="row mb-4">
        <div class="col-md-3">{{ $t("license") }}</div>
        <div class="col-md-9 border-bottom">{{ parent_props.license_list[form.select_license] }}</div>
    </div>
    <div class="row mb-4">
        <div class="col-md-3">{{ $t("name") }}</div>
        <div class="col-md-9 border-bottom">{{ form.input_name }}</div>
    </div>
    <div class="row mb-4">
        <div class="col-md-3">{{ $t("writer") }}</div>
        <div class="col-md-9 border-bottom">{{ form.input_writer }}</div>
    </div>
    <div class="row mb-4">
        <div class="col-md-3">{{ $t("era") }}</div>
        <div class="col-md-9 border-bottom">{{ form.input_era }}</div>
    </div>
    <div class="row mb-4">
        <div class="col-md-3">{{ $t("description") }}</div>
        <div class="col-md-9 border-bottom">{{ form.input_description }}</div>
    </div>
    <div class="row mb-4">
        <div class="col-md-3">{{ $t("thumbnail") }}</div>
        <div class="col-md-9">
            <template v-if="form.input_file_thumbnail">
                {{ form.input_file_thumbnail.name }}<br />
                <img :src="thumbnailImageData" class="mt-1 col-4 img-thumbnail" :alt="form.input_file_thumbnail.name" />
            </template>
        </div>
    </div>
    <div v-for="(pdf, index) in form.input_pdfs" :key="index" class="row mb-4">
        <div class="col-md-3">PDF（{{ index + 1 }}）</div>
        <div class="col-md-9">
            <template v-if="pdf">
                {{ pdf.name }}<br />
                <object :data="pdfData[index]" class="mt-1 col-4 img-thumbnail pdf-preview" type="application/pdf"></object>
            </template>
        </div>
    </div>
</template>
