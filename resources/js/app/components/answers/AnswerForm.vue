<template>
    <div id="respond" class="comment-respond page-content clearfix">
        <div class="boxedtitle page-title"><h2>Leave a reply</h2></div>
        <form @submit.prevent="sendAnswer" id="commentform" class="comment-form">
            <div id="respond-textarea">
                <p>
                    <label class="required" for="comment">Comment<span>*</span></label>
                    <textarea v-model="detail" id="comment" name="comment" cols="58" rows="8"></textarea>
                    <span v-if="$v.detail.$error" class="alert alert-danger">The comment field is required.</span>
                </p>
            </div>
            <p class="form-submit">
                <input name="submit" type="submit" id="submit" value="Post your answer" class="button small color">
            </p>
        </form>
    </div>
</template>

<script lang="ts">
    import {Mixins, Component, Prop} from "vue-property-decorator";
    import Loader from '../Loader.vue';
    import {validationMixin} from "vuelidate";
    import {required} from "vuelidate/lib/validators";

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
    export default class AnswerForm extends Mixins(validationMixin) {
        @Prop({required: true}) questionId: number;

        detail = '';

        async sendAnswer() {
            this.$v.$touch();
            if (this.$v.detail && !this.$v.detail.$invalid) {
                await this.$store.dispatch('answers/create', {
                        questionId: this.questionId,
                        query: {
                            detail: this.detail
                        }
                    }
                );
                this.detail = '';
                this.$v.$reset();
            }
        }
    }
</script>