<script setup>
import AuthenticatedLayout from "@/Layouts/Admin/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import DataCount from "@/Components/Admin/DataCount.vue";
import Activity from "@/Components/Admin/Activity.vue";

defineProps({
    counts: Object,
    activities: Array,
});
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <div class="content w-100">
            <div class="row">
                <div class="col col-12 col-sm-12 col-md-6 col-xl-6">
                    <h2 class="content-heading">{{ $t("number_of_public_items") }}</h2>
                    <div class="row">
                        <DataCount icon="fas fa-book-open" :href="route('manuscript.manuscript_list')" :num="counts.manuscript"> {{ $t("manuscript") }} </DataCount>
                    </div>
                </div>
                <div class="col col-12 col-sm-12 col-md-6 col-xl-6">
                    <h2 class="content-heading">{{ $t("activity") }}</h2>

                    <ul class="timeline timeline-alt py-0">
                        <li v-for="activity in activities" :key="activities.id" class="timeline-event">
                            <Activity :activity="activity" />
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
