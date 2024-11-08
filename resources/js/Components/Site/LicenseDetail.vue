<script setup>
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    license: {
        type: Array,
    },
});
</script>

<template>
    <div>
        <img v-if="license.image !== null" :src="license.image" style="height: 30px" :alt="license.label" :title="license.label" />
        <p class="mt-2">
            <template v-if="license.num === '0'">
                <small class="text-danger fs-7">
                    {{ $t("only_view_text") }}
                </small>
            </template>
            <template v-else-if="license.num === '1'">
                <small class="fs-7"> パブリックドメイン </small>
            </template>
            <template v-else-if="license.num === '8'">
                <small class="d-block mb-1">{{ $t("add_conditions") }} </small>
                <small class="text-danger fs-7">
                    {{ $t("only_view_text") }}
                </small>
            </template>
            <template v-else>
                <small class="fs-7 d-block mt-2">{{ license.label }} </small>
            </template>
        </p>
    </div>
</template>
