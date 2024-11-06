<script setup>
import { computed, onMounted } from "vue";
import { useTemplateStore } from "@/stores/template";
import NavLink from "@/Components/Admin/NavLink.vue";

// SimpleBar, for more info and examples you can check out https://github.com/Grsmto/simplebar/tree/master/packages/simplebar-vue
import SimpleBar from "simplebar";

const props = defineProps({
    nodes: {
        type: Array,
        description: "The nodes of the navigation",
    },
    subMenu: {
        type: Boolean,
        default: false,
        description: "If true, a submenu will be rendered",
    },
    horizontal: {
        type: Boolean,
        default: false,
        description: "Horizontal menu in large screen width",
    },
    horizontalHover: {
        type: Boolean,
        default: false,
        description: "Hover mode for horizontal menu",
    },
    horizontalCenter: {
        type: Boolean,
        default: false,
        description: "Center mode for horizontal menu",
    },
    horizontalJustify: {
        type: Boolean,
        default: false,
        description: "Justify mode for horizontal menu",
    },
    withMiniNav: {
        type: Boolean,
        default: false,
        description: "If the sidebar is in Mini Nav Mode",
    },
});

// Main store
const store = useTemplateStore();

// Init SimpleBar (custom scrolling)
onMounted(() => {
    new SimpleBar(document.getElementById("simplebar-sidebar"));
});

// Main menu toggling and mobile functionality
function linkClicked(e, submenu) {
    if (submenu) {
        // Get closest li element
        let el = e.target.closest("li");

        // Check if we are in a large screen, have horizontal navigation and hover is enabled
        if (!(window.innerWidth > 991 && ((props.horizontal && props.horizontalHover) || props.disableClick))) {
            if (el.classList.contains("open")) {
                // If submenu is open, close it..
                el.classList.remove("open");
            } else {
                // .. else if submenu is closed, close all other (same level) submenus first before open it
                Array.from(el.closest("ul").children).forEach((element) => {
                    element.classList.remove("open");
                });

                el.classList.add("open");
            }
        }
    } else {
        // If we are in mobile, close the sidebar
        if (window.innerWidth < 992) {
            store.sidebar({ mode: "close" });
        }
    }
}
</script>

<template>
    <nav id="sidebar" :class="{ 'with-mini-nav': withMiniNav }" aria-label="Main Navigation">
        <slot>
            <!-- Side Header -->
            <div class="content-header">
                <slot name="header">
                    <!-- Logo -->
                    <span class="smini-visible">
                        <i class="fa fa-circle-notch text-primary"></i>
                    </span>
                    <span class="smini-hide fs-5 tracking-wider"></span>
                    <!-- END Logo -->
                </slot>

                <!-- Extra -->
                <div>
                    <!-- Close Sidebar, Visible only on mobile screens -->
                    <button type="button" class="d-lg-none btn btn-sm btn-alt-secondary ms-1" @click="store.sidebar({ mode: 'close' })">
                        <i class="fa fa-fw fa-times"></i>
                    </button>
                    <!-- END Close Sidebar -->
                </div>
                <!-- END Extra -->
            </div>
            <!-- END Side Header -->

            <!-- Sidebar Scrolling -->
            <div id="simplebar-sidebar" class="js-sidebar-scroll">
                <slot name="content">
                    <!-- Side Navigation -->
                    <div class="content-side">
                        <ul class="nav-main">
                            <NavLink icon="fas fa-home" :href="route('dashboard')" :active="route().current('dashboard')">{{ $t("dashboard") }}</NavLink>
                            <NavLink class="mt-2" icon="fas fa-book-open" :href="route('manuscript.manuscript_list')" :active="route().current('manuscript.*')">{{ $t("manuscript") }}</NavLink>
                        </ul>
                    </div>
                    <!-- END Side Navigation -->
                </slot>
            </div>
            <!-- END Sidebar Scrolling -->
        </slot>
    </nav>
    <!-- END Sidebar -->
</template>
