<script setup>
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    links: Array,
});
</script>

<template>
    <nav class="Page navigation" v-if="links?.length > 3">
        <ul class="pagination">
            <template v-for="link in links">
                <li class="page-item">
                    <span v-if="link.is_current" class="page-link disabled fs-9 py-1 px-2">{{ link.label }}</span>
                    <Link v-else class="page-link text-black fs-9 py-1 px-2" :href="link.url">{{ link.label }}</Link>
                </li>
            </template>
        </ul>
    </nav>
</template>
