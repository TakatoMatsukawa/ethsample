<script setup>
import { useSiteStore } from "@/stores/site";

const store = useSiteStore();
</script>
<template>
    <div>
        <label>文字サイズ</label>
        <button type="button" class="btn btn-sm btn-secondary ms-1" @click="store.setFontSizeClass('font-size-normal')">標準</button>
        <button type="button" class="btn btn-sm btn-secondary ms-1" @click="store.setFontSizeClass('font-size-large')">大</button>
    </div>
</template>
