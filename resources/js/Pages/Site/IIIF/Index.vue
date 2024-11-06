<script setup>
import { ref, onMounted } from "vue";
import Mirador from "mirador";

const props = defineProps({
    manifest: {
        type: String,
        required: true,
    },
});

const miradorInstance = ref(null);

onMounted(() => {
    const config = {
        id: "mirador",
        language: "ja",
        windows: [
            {
                loadedManifest: props.manifest,
            },
        ],
    };

    miradorInstance.value = Mirador.viewer(config);
});
</script>

<template>
    <div id="mirador"></div>
</template>

<style scoped></style>
