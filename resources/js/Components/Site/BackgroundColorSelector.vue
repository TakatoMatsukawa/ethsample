<script setup>
import { useSiteStore } from "@/stores/site";

const store = useSiteStore();
</script>
<template>
    <div>
        <label>背景色</label>
        <button type="button" class="btn btn-sm btn-secondary ms-1" @click="store.setBackgroundColorClass('background-color-blue')">青</button>
        <button type="button" class="btn btn-sm btn-secondary ms-1" @click="store.setBackgroundColorClass('background-color-dark')">黒</button>
        <button type="button" class="btn btn-sm btn-secondary ms-1" @click="store.setBackgroundColorClass('background-color-white')">白</button>
    </div>
</template>
