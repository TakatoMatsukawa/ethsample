<script setup>
import MainLayout from "@/Layouts/Site/MainLayout.vue";
import ShareSNSButton from "@/Components/Site/ShareSNSButton.vue";
import FileSizeDetail from "@/Components/Site/FileSizeDetail.vue";
import Breadcrumb from "@/Components/Site/Breadcrumb.vue";
import LicenseDetail from "@/Components/Site/LicenseDetail.vue";
import { Head } from "@inertiajs/vue3";

const props = defineProps({
    manuscript: Object,
    breadcrumb: Array,
});

const url = location.href;
</script>

<template>
    <Head :title="manuscript.name" />

    <MainLayout>
        <div class="content">
            <div class="container">
                <Breadcrumb v-if="breadcrumb" :breadcrumb="breadcrumb" />
                <h1 class="fs-4 mb-4">{{ manuscript.name }}</h1>
                <div class="row">
                    <div class="col-xl-5" v-if="manuscript.thumbnail !== null">
                        <div class="mb-3 text-center">
                            <img :src="manuscript.thumbnail" class="img-fluid" style="max-height: 350px" />
                        </div>

                        <dl class="row gy-2 align-items-start">
                            <template v-for="(value, index) in manuscript.pdfFiles" :key="index">
                                <dt class="col-12 col-sm-2">
                                    <p class="mb-0 mb-sm-3">PDF {{ ++index }}</p>
                                </dt>
                                <dd class="col-12 col-sm-10">
                                    <a class="btn btn-primary rounded-1" :href="value['file']" target="_blank">
                                        <i class="fa-solid fa-download"></i>
                                        {{ $t("view_pdf") }}
                                    </a>
                                    <FileSizeDetail :file="value['file']" :fileSize="value['size']" />
                                </dd>
                            </template>
                        </dl>

                        <div class="border border-start-0 border-end-0 border-dark mb-3">
                            <p class="py-2 px-2 mb-0">{{ $t("license") }}</p>
                        </div>
                        <LicenseDetail :license="manuscript.license" />

                        <p class="text-center" v-if="manuscript.iiifURL !== null">
                            <a class="btn btn-light btn-iiif mt-3 mb-2 border" :href="manuscript.iiifURL" target="_blank">
                                <img src="/assets/img/i3f.png" style="height: 22px; margin-top: -5px" />&nbsp;{{ $t("view_in_viewer") }}&nbsp;
                                <i class="fa-solid fa-up-right-from-square"></i>
                            </a>
                        </p>
                    </div>

                    <div :class="manuscript.thumbnail ? 'col-xl-7' : 'col-xl-12'">
                        <!-- Striped Table -->
                        <table class="table">
                            <tbody>
                                <tr>
                                    <th>{{ $t("name") }}</th>
                                    <td>{{ manuscript.name }}</td>
                                </tr>
                                <tr>
                                    <th>{{ $t("writer") }}</th>
                                    <td>{{ manuscript.writer }}</td>
                                </tr>
                                <tr>
                                    <th>{{ $t("era") }}</th>
                                    <td>{{ manuscript.era }}</td>
                                </tr>
                                <tr>
                                    <th>{{ $t("description") }}</th>
                                    <td>{{ manuscript.description }}</td>
                                </tr>
                                <tr>
                                    <th>{{ $t("iiif_manifest") }}</th>
                                    <td>
                                        {{ manuscript.iiifManifest }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <!-- END Striped Table -->
                    </div>

                    <ShareSNSButton :url="url" :text="manuscript.name" />
                </div>
            </div>
        </div>
    </MainLayout>
</template>
