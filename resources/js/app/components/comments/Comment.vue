<template>
    <div class="sub-comment">
        <loader :show="isLoad"/>
        <div v-if="!isEdit" :class="{'can-update': comment.can.update}">
            {{comment.detail}} - <a
                :href="'/users/' + comment.user.id + '/profile'">{{comment.user.profile.fullName}}</a>,
            <span class="date">{{comment.created_at.toRelative()}}</span>
            <span v-if="comment.can.update">
                <i @click="toggleForm" class="icon-update icon-edit"></i>
            </span>
        </div>
        <div v-else :class="{'can-update': comment.can.update}">
            <form @submit.prevent="updateComment">
                <textarea v-model="form.detail" class="edit-area" rows="3"></textarea>
                <span v-if="$v.form.detail.$error" class="alert alert-danger">The field is required.</span>
                <button class="button color small" type="submit">Update Comment</button>
            </form>
            <i @click="toggleForm" class="icon-update icon-remove"></i>
        </div>
        <div :class="{'can-destroy': comment.can.destroy}">
            <i v-if="comment.can.destroy" @click="deleteComment" class="icon-destroy icon-trash"></i>
        </div>
    </div>
</template>

<script lang="ts">
    import {Mixins, Component, Prop} from "vue-property-decorator";
    import Loader from '../Loader.vue';
    import {validationMixin} from "vuelidate";
    import {required} from "vuelidate/lib/validators";
    import {CommentService} from '../../services/CommentService';
    import {Comment as CommentModel} from "../../models/Comment";

    const commentService = new CommentService();

    @Component({
        components: {
            Loader
        },
        validations: {
            form: {
                detail: {
                    required
                }
            }
        }
    })
    export default class Comment extends Mixins(validationMixin) {
        @Prop({required: true}) comment: CommentModel;
        @Prop({required: true}) answerId: number;
        @Prop({required: true}) answerIndex: number;
        @Prop({required: true}) index: number;

        isEdit = false;
        isLoad = false;

        form = {
            detail: ''
        };

        toggleForm() {
            if (this.comment.detail) {
                this.form.detail = this.comment.detail;
            }
            return this.isEdit = !this.isEdit;
        }

        async updateComment() {
            this.$v.$touch();
            if (this.$v.form.detail && !this.$v.form.detail.$invalid) {
                this.$v.$reset();
                this.isLoad = true;
                this.comment.detail = this.form.detail.trim();
                this.toggleForm();
                let comment = await commentService.update(
                    this.comment.id,
                    this.answerId,
                    {detail: this.comment.detail}
                );
                let answers = this.$store.getters['answers/answers'];
                answers[this.answerIndex].comments[this.index] = comment;
                this.$store.commit('answers/setAnswers', answers);
                this.isLoad = false;
            }
        }

        async deleteComment() {
            if (confirm('Do you really want to delete the comment?')) {
                await commentService.destroy(
                    this.comment.id,
                    this.answerId
                );
                let answers = this.$store.getters['answers/answers'];
                answers[this.answerIndex].comments.splice(this.index, 1);
                this.$store.commit('answers/setAnswers', answers);
            }
        }
    }
</script>