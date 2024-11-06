<script setup>
import CarouselItem from "@/Components/Site/CarouselItem.vue";
</script>
<script>
const CarouselData = [
    {
        id: "carousel1",
        url: new URL("assets/img/slide/slide01_temp.jpg", import.meta.env.VITE_APP_URL),
        active: true,
    },
    {
        id: "carousel2",
        url: new URL("assets/img/slide/slide02_temp.jpg", import.meta.env.VITE_APP_URL),
        active: false,
    },
    {
        id: "carousel3",
        url: new URL("assets/img/slide/slide03_temp.jpg", import.meta.env.VITE_APP_URL),
        active: false,
    },
];
</script>

<template>
    <div id="mainCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
        <div class="carousel-inner">
            <CarouselItem v-for="(item, index) in CarouselData" :key="index" :alt="'slide item'" :active="item.active" :url="item.url" />
        </div>
        <div class="carousel-caption">
            <div class="carousel-caption__search">
                <form action="/search" method="GET" class="form-search d-flex" role="search">
                    <div class="form-search__group input-group">
                        <span class="form-search__label input-group-text rounded-start-1 d-none d-md-block" id="cross-search1"> 横断検索 </span>
                        <input
                            type="text"
                            name="input_keyword"
                            class="form-control form-search__input"
                            placeholder="キーワード検索"
                            aria-label="cross-search"
                            aria-describedby="cross-search1"
                            maxlength="50"
                        />
                        <button class="btn btn-sm btn-primary form-search__btn rounded-end-1" type="submit">
                            <!-- <span class="d-none d-sm-block">探す</span> -->
                            <i class="fas fa-magnifying-glass px-3"></i>
                        </button>
                    </div>
                </form>
            </div>
            <div class="carousel-caption__description">
                <p class="caption-text">佐川町立図書館データベース内にある資料を一括して検索することができます。</p>
            </div>
        </div>
    </div>
</template>

<style lang="scss" scoped>
.carousel {
    &-caption {
        z-index: 2;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 1.1rem;
        // width: fit-content;
        margin-left: auto;
        margin-right: auto;
        background-color: RGBA(50, 50, 50, 0.5);
        padding: 1.2rem 2.4rem;
        &__search {
        }
        &__description {
            .caption-text {
                letter-spacing: 0.05rem;
                text-shadow: 1px 1px 1px shade-color(yellow, 70%);
                margin-bottom: 0;
            }
        }
    }
}
</style>
