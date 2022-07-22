<template>
    <div>
        <div v-if="type !== 'answer'">
            <span class="single-question-vote-result">{{likes}}</span>
            <ul class="single-question-vote">
                <li><a @click.prevent="addLike" href="#" class="single-question-vote-up" title="Like"><i class="icon-thumbs-up"></i></a></li>
            </ul>
        </div>
        <div v-else>
            <div class="comment-vote">
                <ul class="single-question-vote">
                    <li><a @click.prevent="addLike" href="#" class="single-question-vote-up"
                           title="Like"><i class="icon-thumbs-up"></i></a>
                    </li>
                </ul>
            </div>
            <span class="question-vote-result">{{likes}}</span>
        </div>
    </div>
</template>

<script lang="ts">
    import {Vue, Component, Prop} from "vue-property-decorator";

    @Component({})
    export default class Like extends Vue {
        @Prop({required: true, default: 0}) count: number;
        @Prop({required: true}) id: number;
        @Prop({required: true, default: false}) isUserAuth: boolean;
        @Prop({required: false, default: 'answer'}) type: string;

        isDisabled = false;
        likes = 0;

        created() {
            this.likes = this.count;
        }

        async addLike() {
            if (!this.isUserAuth) {
                return false;
            }
            this.isDisabled = true;
            if (this.type === 'answer') {
                await this.$store.dispatch('likes/answerAddLike', {
                        answerId: this.id,
                        count: this.likes
                    }
                );
                this.likes = this.$store.getters['likes/answers'][this.id];
            } else {
                await this.$store.dispatch('likes/questionAddLike', {
                        questionId: this.id,
                        count: this.likes
                    }
                );
                this.likes = this.$store.getters['likes/questions'][this.id];
            }
            this.isDisabled = false;
            return true;
        }
    }
</script>