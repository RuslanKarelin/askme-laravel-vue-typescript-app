<template>
    <div class="sendCommentForm">
        <a @click.prevent="isShowForm = !isShowForm" class="addCommentLink" href="#">Add comment</a>
        <form v-if="isShowForm" @submit.prevent="sendComment">
            <textarea v-model="detail" class="edit-area" rows="3"></textarea>
            <span v-if="$v.detail.$error" class="alert alert-danger">The field is required.</span>
            <button class="button color small" type="submit">Send Comment</button>
        </form>
    </div>
</template>

<script lang="ts">
    import {Mixins, Component, Prop} from "vue-property-decorator";
    import Loader from '../Loader.vue';
    import {validationMixin} from "vuelidate";
    import {required} from "vuelidate/lib/validators";
    import {CommentService} from '../../services/CommentService';

    const commentService = new CommentService();

    @Component({
        components: {
            Loader
        },
        validations: {
            detail: {
                required
            }
        }
    })
    export default class CommentForm extends Mixins(validationMixin) {
        @Prop({required: true}) answerId: number;
        @Prop({required: true}) answerIndex: number;

        detail = '';
        isShowForm = false;

        async sendComment() {
            this.$v.$touch();
            if (this.$v.detail && !this.$v.detail.$invalid) {
                let comment = await commentService.create(this.answerId, {detail: this.detail});
                let answers = this.$store.getters['answers/answers'];
                answers[this.answerIndex].comments.push(comment);
                this.$store.commit('answers/setAnswers', answers);
                this.isShowForm = false;
                this.detail = '';
                this.$v.$reset();
            }
        }
    }
</script>