<script setup>
import { Head } from "@inertiajs/vue3";

const props = defineProps({
    form: Object,
    parent_props: Object,
});

const form = props.form;
const parent_props = props.parent_props;
</script>

<template>
    <Head :title="$t('add_page')" />
    <div class="row mb-4">
        <div class="col-md-3 mt-1">
            <label class="form-label" for="input_image">{{ $t("source_image") }}</label>
        </div>
        <div class="col-md-9" v-if="form.input_image === '' || form.input_image === null">
            <input
                class="form-control"
                :class="{ 'is-invalid': !!parent_props.errors.input_image }"
                type="file"
                accept=".jpeg,.jpg,.png"
                id="input_image"
                @input="form.input_image = $event.target.files[0]"
            />
            <p class="fs-sm text-muted mb-0">{{ $t("tif_recommendation") }}</p>
            <div class="invalid-feedback">{{ parent_props.errors.input_image }}</div>
        </div>
        <div class="col-md-9" v-else>
            <div class="input-group">
                <button type="button" :id="'remove_thumbnail'" class="btn btn-dark btn-file-delete" @click="form.input_image = null">{{ $t("remove") }}</button>
                <span class="form-control">
                    {{ form.input_image.name }}
                </span>
            </div>
            <p class="fs-sm text-muted mb-0">{{ $t("tif_recommendation") }}</p>
            <div class="custom-error">{{ parent_props.errors.input_image }}</div>
        </div>
    </div>
</template>
