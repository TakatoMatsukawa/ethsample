<script setup>
import AuthenticatedLayout from "@/Layouts/Admin/AuthenticatedLayout.vue";
import Pagination from "@/Components/Admin/Pagination.vue";
import FlashMessage from "@/Components/Admin/FlashMessage.vue";
import ViewItemsNum from "@/Components/Admin/ViewItemsNum.vue";
import { Head, useForm, Link } from "@inertiajs/vue3";
import { computed, onMounted, onUnmounted } from "vue";
import messages from "@/lang/messages.js";
const props = defineProps({
    select_public: String,
    public_list: Array,
    select_license: String,
    license_list: Array,
    input_keyword: String,
    select_search: String,
    select_search_list: Array,
    select_thumbnail: String,
    select_thumbnail_list: Array,
    select_pdf: String,
    select_pdf_list: Array,
    order_name: String,
    order_id: String,
    manuscript_list: Object,
    links: Array,
    default_values: Object,
    page: Number,
    counts: Object,
});

const form = useForm({
    select_public: props.select_public, // 公開状態
    select_license: props.select_license, // ライセンス
    input_keyword: props.input_keyword, // キーワード
    select_search: props.select_search, // キーワード検索条件
    select_thumbnail: props.select_thumbnail, // サムネイル
    select_pdf: props.select_pdf, // PDF
    order_name: props.order_name, // 資料名順序
    order_id: props.order_id, // ID順序
});

// デフォルト値を保存
const initialFormValues = { ...form.data() };

// フォームに変更がないか判別（変更があるときvueで全データマニフェスト作成ボタンを非表示）
const hasFormNotChanged = computed(() => {
    return JSON.stringify(form.data()) === JSON.stringify(initialFormValues);
});

const onReset = () => {
    form.defaults(props.default_values);
    form.reset();
    form.transform((data) => {
        return {
            ...data,
            page: 1,
        };
    }).get(route("manuscript.manuscript_list"));
};

const onDelete = (id) => {
    const confirmed = window.confirm(messages[__locale].confirm_delete);
    if (confirmed) {
        form.delete(route("manuscript.manuscript_delete", id) + "?page=" + props.page, { preserveScroll: true });
    }
};

const onChangePublicStatus = (id) => {
    form.patch(route("manuscript.toggle_public", id) + "?page=" + props.page, { preserveScroll: true });
};

const onCreateManifest = (id) => {
    form.patch(route("manuscript.create_manifest", id) + "?page=" + props.page, { preserveScroll: true });
};

const onCreateAllManifest = () => {
    const confirmed = window.confirm(messages[__locale].confirm_create_all_manifests);
    if (confirmed) {
        form.transform((data) => {
            return {
                ...data,
            };
        }).get(route("manuscript.create_all_manifest"));
    }
};

const onSearch = () => {
    form.transform((data) => {
        return {
            ...data,
            page: 1,
        };
    }).get(route("manuscript.manuscript_list"));
};

const onChangeOrderName = () => {
    const newOrderName = props.order_name === "asc" ? "desc" : "asc";
    form.transform((data) => {
        return {
            ...data,
            page: 1,
            order_name: newOrderName,
            order_id: "",
        };
    }).get(route("manuscript.manuscript_list"));
};

const onChangeOrderId = () => {
    const newOrderId = props.order_id === "asc" ? "desc" : "asc";
    form.transform((data) => {
        return {
            ...data,
            page: 1,
            order_name: "",
            order_id: newOrderId,
        };
    }).get(route("manuscript.manuscript_list"));
};

// Helper variables
let tooltipTriggerList = [];
let tooltipList = [];

// Init tooltips on content loaded
onMounted(() => {
    tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));

    tooltipList = tooltipTriggerList.map((tooltipTriggerEl) => {
        const tooltip = new bootstrap.Tooltip(tooltipTriggerEl, {
            container: tooltipTriggerEl.dataset.bsContainer || "#page-container",
            animation: tooltipTriggerEl.dataset.bsAnimation && tooltipTriggerEl.dataset.bsAnimation.toLowerCase() == "true" ? true : false,
        });
        tooltipTriggerEl.addEventListener("click", () => {
            tooltip.hide();
        });
        return tooltip;
    });
});

// Dispose tooltips on unMounted
onUnmounted(() => {
    tooltipList.forEach((tooltip) => tooltip.dispose());
});

const startViewItemsNum = computed(() => {
    return props.counts.perPage * (props.counts.currentPage - 1) + 1;
});
const endViewItemsNum = computed(() => {
    return props.counts.lastPage == props.counts.currentPage ? props.counts.total : props.counts.perPage * props.counts.currentPage;
});
</script>

<template>
    <Head :title="$t('manuscript_list')" />

    <AuthenticatedLayout>
        <div class="bg-body-light">
            <div class="content content-full w-100 py-2">
                <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
                    <div class="flex-grow-1">
                        <h4 class="h4 fw-bold mb-2">
                            {{ $t("manuscript_list") }}<Link :href="route('manuscript.manuscript_add')" class="btn btn-main btn-sm ms-2"> &nbsp;{{ $t("add_manuscript") }} </Link>
                        </h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="content w-100">
            <!-- Search Box -->
            <div class="block block-rounded">
                <div class="block-content">
                    <div class="row">
                        <form @submit.prevent="onSearch">
                            <div class="col-md-12">
                                <div class="row mb-2">
                                    <div class="col-md-2">
                                        <div class="mb-4">
                                            <label class="form-label" for="select_public">{{ $t("publication_status") }}</label>
                                            <select class="form-select" id="select_public" name="select_public" v-model="form.select_public">
                                                <option v-for="p in props.public_list" :value="p.key">{{ p.value }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-4">
                                            <label class="form-label" for="select_license">{{ $t("license") }}</label>
                                            <select class="form-select" id="select_license" name="select_license" v-model="form.select_license">
                                                <option v-for="l in props.license_list" :value="l.key">{{ l.value }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-4">
                                            <label class="form-label" for="select_thumbnail">{{ $t("thumbnail") }}</label>
                                            <select class="form-select" id="select_thumbnail" name="select_thumbnail" v-model="form.select_thumbnail">
                                                <option v-for="v in props.select_thumbnail_list" :value="v.key">{{ v.value }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-4">
                                            <label class="form-label" for="select_pdf">PDF</label>
                                            <select class="form-select" id="select_pdf" name="select_pdf" v-model="form.select_pdf">
                                                <option v-for="v in props.select_pdf_list" :value="v.key">{{ v.value }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-6">
                                        <div class="mb-4">
                                            <label class="form-label" for="input_keyword">{{ $t("keyword") }}</label>
                                            <div class="input-group">
                                                <input
                                                    type="text"
                                                    class="form-control"
                                                    id="input_keyword"
                                                    name="input_keyword"
                                                    :placeholder="$t('name') + ', ' + $t('keyword')"
                                                    v-model="form.input_keyword"
                                                />
                                                <select class="form-select" id="select_search" name="select_search" v-model="form.select_search">
                                                    <option v-for="s in props.select_search_list" :value="s.key">{{ s.value }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <button type="submit" :disabled="form.processing" class="btn btn-main">{{ $t("search") }}</button>
                                    <button @click="onReset()" type="button" class="btn btn-dark ms-2">{{ $t("clear") }}</button>
                                    <button @click="onCreateAllManifest()" v-if="hasFormNotChanged" type="button" class="btn btn-secondary ms-2">{{ $t("create_all_manifests") }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!--END Search Box -->

            <FlashMessage />

            <div class="block block-rounded">
                <ViewItemsNum :totalViewItemsNum="props.counts.total" :startViewItemsNum="startViewItemsNum" :endViewItemsNum="endViewItemsNum" />
                <div class="block-content block-content-full table-wrap">
                    <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                        <thead>
                            <tr>
                                <th class="text-center">No.</th>
                                <th class="text-center">Actions</th>
                                <th class="text-center">{{ $t("publication_status") }}</th>
                                <th>
                                    {{ $t("contents_id") }}
                                    <button
                                        type="button"
                                        @click="onChangeOrderId()"
                                        class="btn btn-sm btn-alt-secondary"
                                        data-bs-toggle="tooltip"
                                        data-bs-placement="top"
                                        :title="order_id === 'asc' ? $t('sort_by_desc') : $t('sort_by_asc')"
                                    >
                                        <i v-if="order_id === 'asc'" class="fa-solid fa-arrow-down"></i>
                                        <i v-if="order_id === 'desc' || order_id === ''" class="fa-solid fa-arrow-up"></i></button
                                    ><br />{{ $t("license") }}
                                </th>
                                <th>
                                    {{ $t("name") }}
                                    <button
                                        type="button"
                                        @click="onChangeOrderName()"
                                        class="btn btn-sm btn-alt-secondary"
                                        data-bs-toggle="tooltip"
                                        data-bs-placement="top"
                                        :title="order_name === 'asc' ? $t('sort_by_desc') : $t('sort_by_asc')"
                                    >
                                        <i v-if="order_name === 'asc'" class="fa-solid fa-arrow-down"></i>
                                        <i v-if="order_name === 'desc' || order_name === ''" class="fa-solid fa-arrow-up"></i>
                                    </button>
                                </th>
                                <th class="text-center">IIIF</th>
                                <th class="text-center">{{ $t("thumbnail") }}</th>
                                <th class="text-center">PDF</th>
                                <th>{{ $t("updated_date") }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="manuscript in manuscript_list" :key="manuscript.id">
                                <!-- No. -->
                                <td class="text-center fs-sm">{{ manuscript.no }}</td>
                                <!-- Actions -->
                                <td class="text-center">
                                    <div class="btn-group">
                                        <Link
                                            :href="route('manuscript.manuscript_edit', { manuscript: manuscript.id })"
                                            type="button"
                                            class="btn btn-sm btn-alt-secondary"
                                            data-bs-toggle="tooltip"
                                            data-bs-placement="top"
                                            :title="$t('edit')"
                                        >
                                            <i class="fa fa-fw fa-pencil-alt"></i>
                                        </Link>
                                        <button
                                            type="button"
                                            @click="onDelete(manuscript.id)"
                                            class="btn btn-sm btn-alt-secondary"
                                            data-bs-toggle="tooltip"
                                            data-bs-placement="top"
                                            :title="$t('delete')"
                                        >
                                            <i class="far fa-trash-alt"></i>
                                        </button>
                                        <a :href="manuscript.preview" target="_blank" class="btn btn-sm btn-alt-secondary" data-bs-toggle="tooltip" data-bs-placement="top" :title="$t('preview')">
                                            <i class="fas fa-tv"></i>
                                        </a>
                                    </div>
                                </td>
                                <!-- 公開状態 -->
                                <td class="fw-semibold fs-sm">
                                    <div class="form-check form-switch form-check-inline">
                                        <input
                                            class="form-check-input check-public"
                                            type="checkbox"
                                            value=""
                                            name="public-switch"
                                            :checked="manuscript.isPublic"
                                            @change="onChangePublicStatus(manuscript.id)"
                                        />
                                    </div>
                                </td>
                                <!-- ユニークID / ライセンス -->
                                <td class="d-none d-sm-table-cell fs-sm">{{ manuscript.uniqueId }}<br />{{ manuscript.license }}</td>
                                <!-- 資料名 -->
                                <td class="break-cell">{{ manuscript.name }}</td>
                                <!-- IIIF -->
                                <td class="text-center">
                                    <div class="btn-group">
                                        <Link
                                            :href="route('manuscript.manuscript_add_tif', { manuscript: manuscript.id })"
                                            type="button"
                                            class="btn btn-sm btn-alt-secondary"
                                            data-bs-toggle="tooltip"
                                            data-bs-placement="top"
                                            :title="$t('create_tif')"
                                        >
                                            <i class="fas fa-photo-film"></i>
                                        </Link>
                                        <a
                                            v-if="manuscript.iiif"
                                            :href="manuscript.iiif"
                                            target="_blank"
                                            type="button"
                                            class="btn btn-sm btn-alt-secondary"
                                            data-bs-toggle="tooltip"
                                            data-bs-placement="top"
                                            :title="$t('view_iiif')"
                                        >
                                            <img src="/assets/img/i3f.png" style="height: 20px; margin-top: -5px" />
                                        </a>
                                        <button
                                            type="button"
                                            @click="onCreateManifest(manuscript.id)"
                                            class="btn btn-sm btn-alt-secondary"
                                            data-bs-toggle="tooltip"
                                            data-bs-placement="top"
                                            :title="manuscript.iiif ? $t('update_iiif') : $t('create_iiif')"
                                        >
                                            <i class="fa-solid fa-file-export"></i>
                                        </button>
                                    </div>
                                </td>
                                <!-- サムネイル -->
                                <td class="d-none d-sm-table-cell text-center"><img v-if="manuscript.thumbnail" :src="manuscript.thumbnail" :alt="$t('thumbnail')" width="100" /></td>
                                <!-- PDF -->
                                <td class="d-none d-sm-table-cell">{{ manuscript.pdfExist }}</td>
                                <!-- 更新日時 -->
                                <td>{{ manuscript.updatedAt }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <Pagination :links="props.links"></Pagination>
        </div>
    </AuthenticatedLayout>
</template>
