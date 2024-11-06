<script setup>
import { computed, onMounted } from "vue";
import { useTemplateStore } from "@/stores/template";

// Import all layout partials
import BaseHeader from "@/Layouts/Admin/Partials/Header.vue";
import BaseSidebar from "@/Layouts/Admin/Partials/Sidebar.vue";
import BaseFooter from "@/Layouts/Admin/Partials/Footer.vue";

// Main store
const store = useTemplateStore();

// Render main classes based on store options
const classContainer = computed(() => {
    return {
        "sidebar-r": store.layout.sidebar && !store.settings.sidebarLeft,
        "sidebar-mini": store.layout.sidebar && store.settings.sidebarMini,
        "sidebar-o": store.layout.sidebar && store.settings.sidebarVisibleDesktop,
        "sidebar-o-xs": store.layout.sidebar && store.settings.sidebarVisibleMobile,
        "sidebar-dark": store.layout.sidebar && store.settings.sidebarDark && !store.settings.darkMode,
        "page-header-fixed": store.layout.header && store.settings.headerFixed,
        "main-content-boxed": store.settings.mainContent === "boxed",
        "main-content-narrow": store.settings.mainContent === "narrow",
        "side-trans-enabled": store.settings.sideTransitions,
        "side-scroll": true,
    };
});

// Remove side transitions on window resizing
onMounted(() => {
    let winResize = false;

    window.addEventListener("resize", () => {
        clearTimeout(winResize);

        store.setSideTransitions({ transitions: false });

        winResize = setTimeout(() => {
            store.setSideTransitions({ transitions: true });
        }, 500);
    });
});
</script>

<template>
    <div id="page-container" :class="classContainer">
        <div id="page-loader" :class="{ show: store.settings.pageLoader }"></div>

        <BaseSidebar v-if="store.layout.sidebar">
            <template #header>
                <slot name="sidebar-header"></slot>
            </template>

            <template #content>
                <slot name="sidebar-content"></slot>
            </template>

            <slot name="sidebar"></slot>
        </BaseSidebar>

        <BaseHeader v-if="store.layout.header">
            <slot name="header"></slot>
        </BaseHeader>

        <div id="main-container">
            <slot></slot>
        </div>

        <BaseFooter v-if="store.layout.footer">
            <slot name="footer"></slot>
        </BaseFooter>
    </div>
</template>
