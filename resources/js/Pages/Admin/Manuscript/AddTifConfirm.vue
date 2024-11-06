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
if (form.input_image) {
    thumbnailImageData.value = URL.createObjectURL(form.input_image);
}
</script>

<template>
    <Head :title="$t('confirm_page')" />
    <div class="row mb-4">
        <div class="col-md-3">{{ $t("source_image") }}</div>
        <div class="col-md-9">
            <template v-if="form.input_image">
                {{ form.input_image.name }}<br />
                <img :src="thumbnailImageData" class="mt-1 col-4 img-thumbnail" :alt="form.input_image.name" />
            </template>
        </div>
    </div>
</template>
