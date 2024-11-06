<script setup>
import { computed } from "vue";

const props = defineProps({
    file: {
        type: String,
        required: true,
    },
    fileSize: {
        type: String,
        required: true,
    },
});
const fileExtension = computed(() => {
    return "bi-filetype-" + props.file.split(".")[1];
});
</script>

<template>
    <small class="fs-9 align-center text-nowrap">
        （
        <i class="fs-5" :class="fileExtension" />
        {{ props.fileSize }}
        ）
    </small>
</template>
