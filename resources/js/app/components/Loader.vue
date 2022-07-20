<template>
    <div v-if="show" class="loader"></div>
</template>

<script lang="ts">
    import {Vue, Component, Prop} from "vue-property-decorator";

    @Component({})
    export default class Loader extends Vue {
        @Prop({required: true, default: false}) show: boolean;
    }
</script>

<style scoped>
    .loader {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        bottom: 0;
        background-color: rgba(255,255,255, 0.5);
    }
</style>