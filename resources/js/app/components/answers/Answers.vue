<template>
    <div class="answers-component">
        <answer-form v-if="isUserAuth" :questionId="questionId" />
        <div v-else class="alert alert-warning">Log in to respond</div>
        <answer-list :filter="filter" :questionId="questionId" :isUserAuth="isUserAuth" />
    </div>
</template>

<script lang="ts">
    import {Vue, Component, Prop} from "vue-property-decorator";
    import AnswerList from './AnswerList.vue';
    import AnswerForm from './AnswerForm.vue';

    @Component({
        components: {
            AnswerList,
            AnswerForm
        }
    })
    export default class Answers extends Vue {
        @Prop({required: false, default: ''}) filter: string;
        @Prop({required: true}) questionId: number;
        @Prop({required: true, default: false}) isUserAuth: boolean;
    }
</script>