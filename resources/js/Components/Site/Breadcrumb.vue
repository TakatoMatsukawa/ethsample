<script setup>
import { Link } from "@inertiajs/vue3";
const props = defineProps({
    breadcrumb: Object,
});
</script>
<template>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li v-for="(crumb, index) in breadcrumb" :key="index" class="breadcrumb-item">
                <Link v-if="crumb.link" :href="crumb.link">{{ crumb.name }}</Link>
                <span v-else>{{ crumb.name }}</span>
            </li>
        </ol>
    </nav>
</template>

<style lang="scss" scoped>
.breadcrumb {
    display: flex;
    list-style: none;
    .breadcrumb-item {
        margin-right: 0.5rem;
        a {
            text-decoration: none;
        }
        span {
            color: #6c757d;
        }
    }
}
</style>
