<script setup>
import { Head } from "@inertiajs/vue3";

const props = defineProps({
    form: Object,
    parent_props: Object,
});

const form = props.form;
const parent_props = props.parent_props;

let CountPdf = form.pdfs.length;
const addPdfInput = () => {
    form.pdfs[CountPdf] = null;
    form.pdf_states[CountPdf] = parent_props.state_list["unchanged"];
    CountPdf += 1;
};

const removePdfInput = (index) => {
    // ファイル入力をクリア
    const inputElement = document.getElementById(`input_pdf${index + 1}`);
    if (inputElement) {
        inputElement.value = ""; // inputの値をリセット
    }
    form.pdfs[index] = null;
};

const changeState = (index, actionType) => {
    if (actionType === "delete") {
        form.pdf_states[index] = parent_props.state_list["delete"];
    }
    if (actionType === "input") {
        if (form.pdf_states[index] === parent_props.state_list["delete"]) {
            form.pdf_states[index] = parent_props.state_list["modify"];
        } else if (form.pdf_states[index] === parent_props.state_list["unchanged"]) {
            form.pdf_states[index] = parent_props.state_list["add"];
        }
    }
};
</script>

<template>
    <Head :title="$t('update_page')" />
    <div class="row mb-4">
        <div class="col-md-3 mt-1">
            <label class="form-label" for="public-switch">{{ $t("publication_status") }}</label>
        </div>
        <div class="col-md-9">
            <div class="form-check form-switch form-check-inline fs-sm" :class="{ 'is-invalid': !!parent_props.errors.is_public }">
                <input class="form-check-input check-public" type="checkbox" value="on" id="public-switch" name="public-switch" v-model="form.is_public" />
            </div>
            <div class="invalid-feedback">{{ parent_props.errors.is_public }}</div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-3 mt-1">
            <label class="form-label" for="select_license">{{ $t("license") }}</label
            ><span class="badge badge-danger ms-2">{{ $t("required") }}</span>
        </div>
        <div class="col-md-9">
            <select class="form-select" :class="{ 'is-invalid': !!parent_props.errors.select_license }" id="select_license" name="select_license" v-model="form.select_license">
                <option value="" selected>{{ $t("please_select") }}</option>
                <option v-for="(value, key) in parent_props.license_list" :key="key" :value="key">{{ value }}</option>
            </select>
            <div class="invalid-feedback">{{ parent_props.errors.select_license }}</div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-3 mt-1">
            <label class="form-label" for="input_name">{{ $t("name") }}</label
            ><span class="badge badge-danger ms-2">{{ $t("required") }}</span>
        </div>
        <div class="col-md-9">
            <input type="text" class="form-control" :class="{ 'is-invalid': !!parent_props.errors.input_name }" id="input_name" name="input_name" v-model="form.input_name" />
            <p class="fs-sm text-muted mb-0">
                <small>100{{ $t("characters_or_less") }} {{ $t("line_breaks_not_allowed") }}</small>
            </p>
            <div class="invalid-feedback">{{ parent_props.errors.input_name }}</div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-3 mt-1">
            <label class="form-label" for="input_writer">{{ $t("writer") }}</label>
        </div>
        <div class="col-md-9">
            <input type="text" class="form-control" :class="{ 'is-invalid': !!parent_props.errors.input_writer }" id="input_writer" name="input_writer" v-model="form.input_writer" />
            <p class="fs-sm text-muted mb-0">
                <small>50{{ $t("characters_or_less") }} {{ $t("line_breaks_not_allowed") }}</small>
            </p>
            <div class="invalid-feedback">{{ parent_props.errors.input_writer }}</div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-3 mt-1">
            <label class="form-label" for="input_era">{{ $t("era") }}</label>
        </div>
        <div class="col-md-9">
            <input type="text" class="form-control" :class="{ 'is-invalid': !!parent_props.errors.input_era }" id="input_era" name="input_era" v-model="form.input_era" />
            <p class="fs-sm text-muted mb-0">
                <small>100{{ $t("characters_or_less") }} {{ $t("line_breaks_not_allowed") }}</small>
            </p>
            <div class="invalid-feedback">{{ parent_props.errors.input_era }}</div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-3 mt-1">
            <label class="form-label" for="input_description">{{ $t("description") }}</label>
        </div>
        <div class="col-md-9">
            <textarea
                class="form-control"
                :class="{ 'is-invalid': !!parent_props.errors.input_description }"
                id="input_description"
                name="input_description"
                rows="4"
                v-model="form.input_description"
            ></textarea>
            <p class="fs-sm text-muted mb-0">
                <small>800{{ $t("characters_or_less") }}</small>
            </p>
            <div class="invalid-feedback">{{ parent_props.errors.input_description }}</div>
        </div>
    </div>

    <div class="row mb-4">
        <template v-if="form.is_registered_thumbnail">
            <div class="col-md-3 mt-1">
                <span class="form-label">{{ $t("thumbnail") }}</span>
            </div>
            <div class="col-md-9">
                <div class="input-group">
                    <button type="button" id="remove_thumbnail" class="btn btn-dark btn-file-delete" v-on:click="form.is_registered_thumbnail = false">{{ $t("remove") }}</button>
                    <span class="form-control">
                        {{ parent_props.thumbnail.name }}
                    </span>
                </div>
                <img :src="parent_props.thumbnail.url" class="mt-1 col-4 img-thumbnail" :alt="parent_props.thumbnail.name" />
            </div>
        </template>
        <template v-else>
            <div class="col-md-3 mt-1">
                <label class="form-label" for="input_file_thumbnail">{{ $t("thumbnail") }}</label>
            </div>
            <div class="col-md-9" v-if="form.input_file_thumbnail === '' || form.input_file_thumbnail === null">
                <input
                    class="form-control"
                    :class="{ 'is-invalid': !!parent_props.errors.input_file_thumbnail }"
                    type="file"
                    accept=".jpeg,.jpg,.png"
                    id="input_file_thumbnail"
                    @input="form.input_file_thumbnail = $event.target.files[0]"
                />
                <p class="fs-sm text-muted mb-0">
                    <small>5MB{{ $t("or_less") }}</small>
                </p>
                <div class="invalid-feedback">{{ parent_props.errors.input_file_thumbnail }}</div>
            </div>
            <div class="col-md-9" v-else>
                <div class="input-group">
                    <button type="button" :id="'remove_thumbnail'" class="btn btn-dark btn-file-delete" @click="form.input_file_thumbnail = null">{{ $t("remove") }}</button>
                    <span class="form-control">
                        {{ form.input_file_thumbnail.name }}
                    </span>
                </div>
                <div class="custom-error">{{ parent_props.errors.input_file_thumbnail }}</div>
            </div>
        </template>
    </div>

    <template v-for="(pdf, index) in form.pdfs" :key="index">
        <template v-if="pdf != null && form.pdf_states[index] === parent_props.state_list['unchanged']">
            <div class="row mb-4">
                <div class="col-md-3 mt-1">
                    <span class="form-label">PDF（{{ index + 1 }}）</span>
                </div>
                <div class="col-md-9">
                    <div class="input-group">
                        <button
                            type="button"
                            :id="'remove_pdf' + (index + 1)"
                            class="btn btn-dark btn-file-delete"
                            v-on:click="
                                form.pdfs[index] = null;
                                changeState(index, 'delete');
                            "
                        >
                            {{ $t("remove") }}
                        </button>
                        <span class="form-control">
                            {{ parent_props.pdfs[index].original_file_name }}
                            <a :href="parent_props.pdfs[index].url" target="_blank"><i class="fa-regular fa-file-pdf"></i></a>
                        </span>
                    </div>
                </div>
            </div>
        </template>
        <template v-else>
            <div class="row mb-4">
                <div class="col-md-3 mt-1">
                    <label class="form-label" :for="'input_pdf' + (index + 1)">PDF（{{ index + 1 }}）</label>
                </div>
                <div class="col-md-9" v-if="form.pdfs[index] === '' || form.pdfs[index] === null">
                    <input
                        class="form-control"
                        :class="{ 'is-invalid': !!parent_props.errors.input_pdfs }"
                        accept=".pdf"
                        type="file"
                        :id="'input_pdf' + (index + 1)"
                        @input="
                            form.pdfs[index] = $event.target.files[0];
                            changeState(index, 'input');
                        "
                    />
                    <p class="fs-sm text-muted mb-0">
                        <small>50MB{{ $t("or_less") }}</small>
                    </p>
                    <div class="invalid-feedback">{{ parent_props.errors["input_pdf" + (index + 1)] }}</div>
                </div>
                <div class="col-md-9" v-else>
                    <div class="input-group">
                        <button type="button" :id="'remove_pdf' + (index + 1)" class="btn btn-dark btn-file-delete" @click="removePdfInput(index)">{{ $t("remove") }}</button>
                        <span class="form-control">
                            {{ form.pdfs[index].name }}
                        </span>
                    </div>
                </div>
            </div>
        </template>
    </template>

    <div class="row mb-4">
        <div class="col-md-3 mt-1"></div>
        <div class="col-md-9">
            <div class="custom-error">{{ parent_props.errors.input_pdfs }}</div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-9 offset-md-3">
            <button type="button" @click="addPdfInput" class="btn btn-main">{{ $t("add_pdf") }}</button>
        </div>
    </div>
</template>
