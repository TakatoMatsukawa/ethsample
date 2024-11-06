<script setup>
import { computed } from "vue";
const props = defineProps({
    active: {
        type: Boolean,
        default: false,
    },
    url: {
        type: URL,
    },
    alt: {
        type: String,
    },
});
const slideImageURL = computed(() => {
    return props.url.href;
});
</script>
<template>
    <div class="carousel-item" :class="active ? ' active' : ''" data-bs-interval="4000">
        <img :src="slideImageURL" class="d-block w-100 object-fit-cover" :alt="alt" />
    </div>
</template>

<style lang="scss" scoped>
.carousel-item {
    img {
        transform: scale(1.25);
        object-position: 50% 50%;
        max-height: 400px;
        // @include media-breakpoint-down(sm) {
        //     height: 270px;
        //     width: 300px;
        // }
    }
    &.active {
        // transition-duration: 3s;
        // transition-property: opacity;
        // transition-delay: 0s;
        img {
            transition-duration: 2.5s;
            transition-property: transform opacity;
            transition-delay: 0.5s;
            transform: scale(1);
        }
    }
}
</style>
