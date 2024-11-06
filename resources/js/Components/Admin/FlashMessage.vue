<script setup></script>
<template>
    <div v-if="$page.props.flash.status === 'success'" class="alert alert-success alert-dismissible" role="alert">
        <p class="mb-0">{{ $page.props.flash.message }}</p>
    </div>
    <div v-if="$page.props.flash.status === 'danger'" class="alert alert-danger alert-dismissible" role="alert">
        <p class="mb-0">{{ $page.props.flash.message }}</p>
    </div>
</template>
