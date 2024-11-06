<script setup>
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    href: {
        type: String,
        required: true,
    },
    num: {
        type: Number,
        required: true,
    },
    icon: {
        type: String,
        required: true,
    },
});

const numStr = props.num.toLocaleString();
</script>

<template>
    <div class="col-md-12 col-xl-6">
        <Link :href="href" class="block block-rounded block-link-pop">
            <div class="block-content block-content-full d-flex align-items-center justify-content-between">
                <span class="h1 mb-0"><i :class="icon"></i></span>
                <div class="ms-3 text-end">
                    <p class="fw-extrabold mb-0"><slot /></p>
                    <p class="fw-semibold mb-0">{{ numStr }}件</p>
                </div>
            </div>
        </Link>
    </div>
</template>
