<template>
    <li class="comment">
        <loader :show="isLoad"/>
        <div :class="{'can-destroy': answer.can.destroy}">
            <i v-if="answer.can.destroy" @click="deleteAnswer" class="icon-destroy icon-trash"></i>
        </div>
        <div class="comment-body comment-body-answered clearfix">
            <div class="avatar"><img alt="" :src="answer.user.profile.avatar"></div>
            <div class="comment-text">
                <div class="author clearfix">
                    <div class="comment-author"><a :href="'/users/' + answer.user.id + '/profile'">
                        {{answer.user.profile.fullName}}
                    </a></div>
                    <like :id="answer.id" :count="answer.likes_count" :isUserAuth="isUserAuth"/>
                    <div class="comment-meta">
                        <div class="date"><i class="icon-time"></i>{{answer.created_at.toRelative()}}</div>
                    </div>
                </div>
                <div class="text">
                    <div v-if="!isEdit" :class="{'can-update': answer.can.update}">{{answer.detail}}
                        <span v-if="answer.can.update">
                            <i @click="toggleForm" class="icon-update icon-edit"></i>
                        </span>
                    </div>
                    <div v-else :class="{'can-update': answer.can.update}">
                        <form @submit.prevent="updateAnswer">
                            <textarea v-model="form.detail" class="edit-area" rows="3"></textarea>
                            <span v-if="$v.form.detail.$error" class="alert alert-danger">The field is required.</span>
                            <button class="button color small" type="submit">Update Answer</button>
                        </form>
                        <i @click="toggleForm" class="icon-update icon-remove"></i>
                    </div>
                </div>
            </div>
        </div>
        <comment-list
                :comments="answer.comments"
                :answerId="answer.id"
                :isUserAuth="isUserAuth"
                :answerIndex="index"
        />
    </li>
</template>

<script lang="ts">
    import {Mixins, Component, Prop} from "vue-property-decorator";
    import {Answer as AnswerModel} from '../../models/Answer';
    import Loader from '../Loader.vue';
    import CommentList from '../comments/CommentList.vue';
    import Like from '../like/Like.vue';
    import {validationMixin} from "vuelidate";
    import {required} from "vuelidate/lib/validators";

    @Component({
        components: {
            Loader,
            Like,
            CommentList
        },
        validations: {
            form: {
                detail: {
                    required
                }
            }
        }
    })
    export default class Answer extends Mixins(validationMixin) {
        @Prop({required: true}) answer: AnswerModel;
        @Prop({required: true}) index: number;
        @Prop({required: true}) questionId: number;
        @Prop({required: true, default: false}) isUserAuth: boolean;

        isEdit = false;
        isLoad = false;

        form = {
            detail: ''
        };

        toggleForm() {
            if (this.answer.detail) {
                this.form.detail = this.answer.detail;
            }
            return this.isEdit = !this.isEdit;
        }

        async updateAnswer() {
            this.$v.$touch();
            if (this.$v.form.detail && !this.$v.form.detail.$invalid) {
                this.$v.$reset();
                this.isLoad = true;
                this.answer.detail = this.form.detail.trim();
                this.toggleForm();
                await this.$store.dispatch('answers/update', {
                        questionId: this.questionId,
                        answerId: this.answer.id,
                        index: this.index,
                        query: {
                            detail: this.answer.detail
                        }
                    }
                );
                this.isLoad = false;
            }
        }

        async deleteAnswer() {
            if (confirm('Do you really want to delete the answer?')) {
                await this.$store.dispatch('answers/destroy', {
                        questionId: this.questionId,
                        answerId: this.answer.id,
                        index: this.index
                    }
                );
            }
        }
    }
</script>