<script setup>
import { computed } from "vue";
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    href: {
        type: String,
        required: true,
    },
    active: {
        type: Boolean,
    },
    icon: {
        type: String,
    },
});

const classes = computed(() => (props.active ? "nav-main-link active" : "nav-main-link"));
</script>

<template>
    <li class="nav-main-item">
        <Link :href="href" :class="classes">
            <span class="nav-main-link-name">
                <i class="me-2" :class="icon"></i>
                <slot />
            </span>
        </Link>
    </li>
</template>
