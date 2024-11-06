<script setup>
const props = defineProps({
    totalViewItemsNum: Number,
    startViewItemsNum: Number,
    endViewItemsNum: Number,
});

function number_format(num) {
    return num.toString().replace(/(\d+?)(?=(?:\d{3})+$)/g, function (x) {
        return x + ",";
    });
}
const locale = __locale;
</script>

<template>
    <!-- 言語切り替え対応 -->
    <p class="text-end pt-2 mb-0 me-3" v-if="locale == 'en'">Displaying {{ number_format(startViewItemsNum) }}-{{ number_format(endViewItemsNum) }} of {{ number_format(totalViewItemsNum) }} items</p>
    <p class="text-end pt-2 mb-0 me-3" v-if="locale == 'am'">
        {{ number_format(totalViewItemsNum) }}ንጥሎች{{ number_format(startViewItemsNum) }}-{{ number_format(endViewItemsNum) }}ከ49 ንጥሎች 1-49 በማሳየት ላይ
    </p>
    <p class="text-end pt-2 mb-0 me-3" v-if="locale == 'ja'">{{ number_format(totalViewItemsNum) }}件中 {{ number_format(startViewItemsNum) }}-{{ number_format(endViewItemsNum) }}件を表示</p>
</template>
