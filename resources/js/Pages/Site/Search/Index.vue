<script setup>
import MainLayout from "@/Layouts/Site/MainLayout.vue";
import Pagination from "@/Components/Site/Pagination.vue";
import ViewItemsNum from "@/Components/Site/ViewItemsNum.vue";
import { Head, useForm, Link } from "@inertiajs/vue3";
import Breadcrumb from "@/Components/Site/Breadcrumb.vue";
import { computed } from "vue";

const props = defineProps({
    errors: Object,
    input_keyword: String,
    select_search: String,
    select_search_list: Array,
    search_list: Object,
    links: Array,
    default_values: Object,
    page: Number,
    counts: Object,
    breadcrumb: Array,
});

const form = useForm({
    input_keyword: props.input_keyword, // キーワード
    select_search: props.select_search, // キーワード検索条件
});

const onSearch = () => {
    form.transform((data) => {
        return {
            ...data,
            page: 1,
        };
    }).get(route("search"));
};

const startViewItemsNum = computed(() => {
    return props.counts.perPage * (props.counts.currentPage - 1) + 1;
});
const endViewItemsNum = computed(() => {
    return props.counts.lastPage == props.counts.currentPage ? props.counts.total : props.counts.perPage * props.counts.currentPage;
});

let error = "";
if (Object.keys(props.errors).length !== 0) {
    error = props.errors.input_keyword;
}
</script>

<template>
    <Head title="横断検索一覧" />

    <MainLayout>
        <div class="content w-100">
            <div class="container">
                <Breadcrumb :breadcrumbs="props.breadcrumb" />
                <!-- Search Box -->
                <div class="block">
                    <div class="row">
                        <form @submit.prevent="onSearch">
                            <div class="col-md-12">
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

                <div v-if="search_list.length !== 0 && error === ''">
                    <Pagination :links="props.links"></Pagination>
                    <ViewItemsNum :totalViewItemsNum="props.counts.total" :startViewItemsNum="startViewItemsNum" :endViewItemsNum="endViewItemsNum" class="text-end pt-2 mb-0 me-3" />

                    <div class="block">
                        <div class="table-wrap">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>コレクション名</th>
                                        <th>サムネイル</th>
                                        <th>資料名</th>
                                        <th>IIIF</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="search in search_list" :key="search.id">
                                        <!-- No. -->
                                        <td>{{ search.no }}</td>
                                        <!-- コレクション名 -->
                                        <td>{{ search.collection }}</td>
                                        <!-- サムネイル -->
                                        <td>
                                            <Link :href="route(search.detail, search.id)"><img v-if="search.thumbnail" :src="search.thumbnail" alt="サムネイル" width="100" /></Link>
                                        </td>
                                        <!-- 資料名 -->
                                        <td>{{ search.name }}</td>
                                        <!-- IIIF -->
                                        <td>
                                            <a v-if="search.iiif" :href="search.iiif" target="_blank">
                                                <img src="/assets/img/i3f.png" style="height: 30px; margin-top: -5px" />
                                            </a>
                                        </td>
                                        <!-- 詳細 -->
                                        <td>
                                            <Link :href="route(search.detail, search.id)" type="button" class="btn btn-primary btn-sm table-btn" title="詳細"> 詳細 </Link>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div v-else>
                    <p v-if="props.input_keyword === ''">検索キーワードを入力してください。</p>
                    <p v-else>検索内容に該当するものはございません。</p>
                </div>
            </div>
        </div>
    </MainLayout>
</template>
