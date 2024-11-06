<script setup>
import MainLayout from "@/Layouts/Site/MainLayout.vue";
import Pagination from "@/Components/Site/Pagination.vue";
import ViewItemsNum from "@/Components/Site/ViewItemsNum.vue";
import Breadcrumb from "@/Components/Site/Breadcrumb.vue";
import { Head, useForm, Link } from "@inertiajs/vue3";
import { computed } from "vue";

const props = defineProps({
    select_license: String,
    license_list: Array,
    input_keyword: String,
    select_search: String,
    select_search_list: Array,
    manuscript_list: Object,
    links: Array,
    default_values: Object,
    page: Number,
    counts: Object,
    breadcrumb: Array,
});

const form = useForm({
    select_license: props.select_license, // ライセンス
    input_keyword: props.input_keyword, // キーワード
    select_search: props.select_search, // キーワード検索条件
});

const onSearch = () => {
    form.transform((data) => {
        return {
            ...data,
            page: 1,
        };
    }).get(route("manuscript"));
};

const startViewItemsNum = computed(() => {
    return props.counts.perPage * (props.counts.currentPage - 1) + 1;
});
const endViewItemsNum = computed(() => {
    return props.counts.lastPage == props.counts.currentPage ? props.counts.total : props.counts.perPage * props.counts.currentPage;
});
</script>

<template>
    <Head title="古文書一覧" />

    <MainLayout>
        <div class="content w-100">
            <div class="container">
                <Breadcrumb :breadcrumb="breadcrumb" />
                <!-- Search Box -->
                <div class="block">
                    <div class="row">
                        <form @submit.prevent="onSearch">
                            <div class="col-md-12">
                                <div class="row mb-2">
                                    <div class="col-md-3">
                                        <div class="mb-4">
                                            <label class="form-label" for="select_license">ライセンス</label>
                                            <select class="form-select" id="select_license" name="select_license" v-model="form.select_license">
                                                <option v-for="l in props.license_list" :value="l.key">{{ l.value }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-6">
                                        <div class="mb-4">
                                            <label class="form-label" for="input_keyword">キーワード</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="input_keyword" name="input_keyword" placeholder="資料名、作成者、内容…" v-model="form.input_keyword" />
                                                <select class="form-select" id="select_search" name="select_search" v-model="form.select_search">
                                                    <option v-for="s in props.select_search_list" :value="s.key">{{ s.value }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <button type="submit" :disabled="form.processing" class="btn btn-primary">検索する</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!--END Search Box -->

                <div v-if="manuscript_list.length !== 0">
                    <Pagination :links="props.links"></Pagination>
                    <ViewItemsNum :totalViewItemsNum="props.counts.total" :startViewItemsNum="startViewItemsNum" :endViewItemsNum="endViewItemsNum" class="text-end pt-2 mb-0 me-3" />

                    <div class="block">
                        <div class="table-wrap">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>サムネイル</th>
                                        <th>資料名</th>
                                        <th>作者名</th>
                                        <th>IIIF</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="manuscript in manuscript_list" :key="manuscript.id">
                                        <!-- No. -->
                                        <td>{{ manuscript.no }}</td>
                                        <!-- サムネイル -->
                                        <td>
                                            <Link :href="route('manuscript.detail', { manuscript: manuscript.id })"
                                                ><img v-if="manuscript.thumbnail" :src="manuscript.thumbnail" alt="サムネイル" width="100"
                                            /></Link>
                                        </td>
                                        <!-- 資料名 -->
                                        <td>{{ manuscript.name }}</td>
                                        <!-- 作者名 -->
                                        <td>{{ manuscript.writer }}</td>
                                        <!-- IIIF -->
                                        <td>
                                            <a v-if="manuscript.iiif" :href="manuscript.iiif" target="_blank">
                                                <img src="/assets/img/i3f.png" style="height: 30px; margin-top: -5px" />
                                            </a>
                                        </td>
                                        <!-- 詳細 -->
                                        <td>
                                            <Link :href="route('manuscript.detail', { manuscript: manuscript.id })" type="button" class="btn btn-primary btn-sm table-btn" title="詳細"> 詳細 </Link>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div v-else>
                    <p>検索内容に該当するものはございません。</p>
                </div>
            </div>
        </div>
    </MainLayout>
</template>
