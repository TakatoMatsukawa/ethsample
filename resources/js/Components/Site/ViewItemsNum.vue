<script setup>
const props = defineProps({
    totalViewItemsNum: Number,
    startViewItemsNum: Number,
    endViewItemsNum: Number,
})

function number_format(num) {
    return num.toString().replace(/(\d+?)(?=(?:\d{3})+$)/g, function (x) {
        return x + ','
    })
}
</script>

<template>
    <p>
        {{ number_format(totalViewItemsNum) }}件中
        {{ number_format(startViewItemsNum) }}-{{
            number_format(endViewItemsNum)
        }}件を表示
    </p>
</template>
