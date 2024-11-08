<script setup>
import MainLayout from "@/Layouts/Site/MainLayout.vue";
import { Head } from "@inertiajs/vue3";
import Carousel from "@/Components/Site/Carousel.vue";
import CollectionDatabaseGrid from "@/Components/Site/CollectionDatabaseGrid.vue";
const props = defineProps({
    errors: Object,
});
</script>

<template>
    <Head :title="$t('top')" />

    <MainLayout>
        <section class="search">
            <Carousel></Carousel>
        </section>

        <section class="collection mt-5">
            <div class="container">
                <div class="row">
                    <h1 class="pb-1 display-5 fw-bold collection__title">
                        <small class="fs-1">{{ $t("search_by_category") }}</small>
                    </h1>
                    <CollectionDatabaseGrid :name="$t('manuscript')" :route="route('manuscript')" img="assets/img/noimage.png"></CollectionDatabaseGrid>
                </div>
            </div>
        </section>
    </MainLayout>
</template>
