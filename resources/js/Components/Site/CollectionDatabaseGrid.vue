<script setup>
import { Link } from "@inertiajs/vue3";
const props = defineProps({
    name: {
        type: String,
        required: true,
    },
    route: {
        type: String,
        required: true,
    },
    img: {
        type: String,
        required: true,
    },
});
</script>
<template>
    <div class="database-grid__item col-12 col-md-6 col-lg-4 mb-4">
        <div class="card h-100">
            <div class="card-img-top">
                <Link :href="props.route" class="database-thumb__link"><span class="database-thumb__image" :style="'background-image: url(&quot;' + props.img + '&quot;)'"></span></Link>
            </div>
            <div class="card-body">
                <div>
                    <h5 class="card-title">{{ props.name }}</h5>
                </div>
                <Link :href="props.route" class="card-btn btn btn-primary w-100">検索する</Link>
            </div>
        </div>
    </div>
</template>

<style lang="scss" scoped>
.card {
    &-title {
        letter-spacing: 0.02rem;
    }
    &-body {
        display: flex;
        flex-wrap: wrap;
        flex-direction: column;
        justify-content: space-between;
        gap: 1rem;
    }
    &-btn {
        align-self: flex-end;
    }
    &-img-top {
        position: relative;
        overflow: hidden;
        .database-thumb__image {
            background-position: center center;
            background-repeat: no-repeat;
            background-size: cover;
            display: block;
            position: relative;
            width: 100%;
            opacity: 0.7;
            min-height: 225px;
            max-height: 265px;
            transition: transform 0.5s linear;
            &:hover {
                transform: scale(1.2);
            }
        }
    }
}
</style>
