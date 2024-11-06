<script setup>
const props = defineProps({
    activity: {
        type: Object,
        required: true,
    },
});
const backgroundColors = {
    0: "bg-gray-dark",
    1: "bg-gray-dark",
    2: "bg-success",
    3: "bg-primary",
    4: "bg-danger",
};
const icons = {
    0: "fa-solid fa-right-to-bracket",
    1: "fa-solid fa-right-from-bracket",
    2: "fas fa-plus",
    3: "fa fa-fw fa-pencil-alt",
    4: "far fa-trash-alt",
};
</script>

<template>
    <div class="timeline-event-icon" :class="backgroundColors[activity.action]">
        <i :class="icons[activity.action]"></i>
    </div>
    <div class="timeline-event-block block rounded">
        <div class="block-header">
            <p class="mb-0">{{ activity.desc }}</p>
            <div class="block-options">
                <div class="timeline-event-time block-options-item fs-sm">{{ activity.dateDiffForHumans }}</div>
            </div>
        </div>
    </div>
</template>
