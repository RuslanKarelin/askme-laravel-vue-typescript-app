<template>
    <div class="sub-comment-list">
        <div v-if="comments.length">
            <comment v-for="(comment, index) in comments"
                     :key="'comment' + comment.id"
                     :answerId="answerId"
                     :comment="comment"
                     :answerIndex="answerIndex"
                     :index="index"
            />

        </div>
        <comment-form v-if="isUserAuth" :answerId="answerId" :answerIndex="answerIndex" />
    </div>
</template>

<script lang="ts">
    import {Vue, Component, Prop} from "vue-property-decorator";
    import Comment from './Comment.vue';
    import CommentForm from './CommentForm.vue';
    import {Comment as CommentModel} from "../../models/Comment";

    @Component({
        components: {
            Comment,
            CommentForm
        }
    })
    export default class CommentList extends Vue {
        @Prop({required: true}) answerId: number;
        @Prop({required: true}) answerIndex: number;
        @Prop({required: true}) comments: CommentModel[];
        @Prop({required: true, default: false}) isUserAuth: boolean;
    }
</script>