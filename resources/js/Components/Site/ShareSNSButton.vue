<script setup>
const props = defineProps({
    url: String,
    text: String,
})

const twitterShare = () => {
    //シェアする画面を設定
    var shareURL =
        'https://twitter.com/intent/tweet?text=' +
        props.text +
        '&url=' +
        props.url
    window.open(shareURL)
}

const facebookShare = () => {
    //シェアする画面を設定
    var shareURL = 'https://www.facebook.com/sharer/sharer.php?u=' + props.url
    window.open(shareURL)
}
</script>

<template>
    <div class="d-sm-flex mb-3 mt-3 justify-content-center">
        <div class="facebook me-sm-2">
            <a class="btn-sns btn-facebook mx-auto" @click="facebookShare">
                <svg
                    class="my-auto"
                    xmlns="http://www.w3.org/2000/svg"
                    height="1em"
                    viewBox="0 0 320 512"
                >
                    <path
                        d="M279.14 288l14.22-92.66h-88.91v-60.13c0-25.35 12.42-50.06 52.24-50.06h40.42V6.26S260.43 0 225.36 0c-73.22 0-121.08 44.38-121.08 124.72v70.62H22.89V288h81.39v224h100.17V288z"
                    ></path>
                </svg>
                <span class="mx-auto">シェアする</span></a
            >
        </div>
        <div class="twitter ms-sm-2 mt-3 mt-sm-0">
            <a class="btn-sns btn-x mx-auto" @click="twitterShare">
                <svg
                    class="my-auto"
                    xmlns="http://www.w3.org/2000/svg"
                    height="1em"
                    viewBox="0 0 512 512"
                >
                    <path
                        d="M389.2 48h70.6L305.6 224.2 487 464H345L233.7 318.6 106.5 464H35.8L200.7 275.5 26.8 48H172.4L272.9 180.9 389.2 48zM364.4 421.8h39.1L151.1 88h-42L364.4 421.8z"
                    ></path>
                </svg>
                <span class="mx-auto">ポスト</span></a
            >
        </div>
    </div>
</template>
