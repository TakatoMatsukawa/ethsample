<script setup>
import AuthenticatedLayout from "@/Layouts/Admin/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
const props = defineProps({
    results: Object,
});
</script>

<template>
    <Head :title="$t('IIIF_manifest_creation_results')" />

    <AuthenticatedLayout>
        <div class="bg-body-light">
            <div class="content content-full w-100 py-2">
                <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
                    <div class="flex-grow-1">
                        <h4 class="h4 fw-bold mb-2">
                            {{ $t("IIIF_manifest_creation_results") }} <Link :href="route('manuscript.manuscript_list')" class="btn btn-main btn-sm ms-2"> &nbsp;{{ $t("manuscript_list") }} </Link>
                        </h4>
                        <h5 class="fs-base lh-base fw-medium text-muted mb-0">{{ $t("number_of_target_data_items") }} : {{ results.count }}</h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="content w-100">
            <div class="row">
                <div class="col-4">
                    <div class="block block-rounded">
                        <div class="block-header block-header-default">
                            <h3 class="block-title d-flex">{{ $t("number_of_created_data_items") }} : {{ results.createDataCount }}</h3>
                        </div>
                        <div class="block-content pt-0">
                            <table class="table table-borderless table-vcenter table-hover">
                                <thead>
                                    <tr>
                                        <th>{{ $t("contents_id") }} : {{ $t("name") }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="createData in results.createData" class="table-success">
                                        <td class="fw-semibold fs-sm">{{ createData.iiifCode }}：{{ createData.name }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="block block-rounded">
                        <div class="block-header block-header-default">
                            <h3 class="block-title">{{ $t("number_of_updated_data_items") }} : {{ results.updateDataCount }}</h3>
                        </div>
                        <div class="block-content pt-0">
                            <table class="table table-borderless table-vcenter table-hover">
                                <thead>
                                    <tr>
                                        <th>{{ $t("contents_id") }} : {{ $t("name") }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="updateData in results.updateData" class="table-success">
                                        <td class="fw-semibold fs-sm">{{ updateData.iiifCode }}：{{ updateData.name }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="block block-rounded">
                        <div class="block-header block-header-default">
                            <h3 class="block-title">{{ $t("number_of_error_data_items") }} : {{ results.errorDataCount }}</h3>
                        </div>
                        <div class="block-content pt-0">
                            <table class="table table-borderless table-vcenter table-hover">
                                <thead>
                                    <tr>
                                        <th>{{ $t("contents_id") }} : {{ $t("name") }}</th>
                                        <th>{{ $t("error_text") }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="errorData in results.errorData" class="table-danger">
                                        <td class="fw-semibold fs-sm">{{ errorData.iiifCode }}：{{ errorData.name }}</td>
                                        <td class="fw-semibold fs-sm">{{ errorData.error }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
