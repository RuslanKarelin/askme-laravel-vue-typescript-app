<template>
    <div class="commentlistWrap">
        <div v-if="total" id="commentlist" class="page-content">
            <div class="boxedtitle page-title"><h2>Answers ( <span class="color">{{total}}</span> )</h2></div>

            <loader :show="isLoad"/>
            <ol class="commentlist clearfix">
                <answer v-for="(answer, index) in answerList"
                        :key="'answer_' + answer.id"
                        :answer="answer"
                        :index="index"
                        :questionId="questionId"
                        :isUserAuth="isUserAuth"
                />
            </ol>

        </div>
        <a v-if="lastPage > currentPage" @click.prevent="loadAnswers" href="#" class="load-questions"><i
                class="icon-refresh"></i>Load More Answers</a>
    </div>
</template>

<script lang="ts">
    import {Vue, Component, Prop} from "vue-property-decorator";
    import {Answer as AnswerModel} from '../../models/Answer';
    import Answer from './Answer.vue';
    import Loader from '../Loader.vue';

    @Component({
        components: {
            Answer,
            Loader
        }
    })
    export default class AnswerList extends Vue {
        @Prop({required: false, default: ''}) filter: string;
        @Prop({required: true}) questionId: number;
        @Prop({required: true, default: false}) isUserAuth: boolean;

        answerList: AnswerModel[] = [];
        currentPage = 1;
        isLoad = false;

        get total() {
            return this.$store.getters['answers/meta'].total;
        }

        get lastPage() {
            return this.$store.getters['answers/meta'].last_page;
        }

        get query() {
            let query: { [key: string]: any } = {
                page: this.currentPage
            }
            if (this.filter) {
                query.filter = this.filter
            }
            return query;
        }

        async getAnswers() {
            this.isLoad = true;
            await this.$store.dispatch('answers/getList', {questionId: this.questionId, query: this.query});
            this.answerList = this.answerList.concat(this.$store.getters['answers/answers']);
            this.$store.commit('answers/setAnswers', this.answerList);
            this.isLoad = false;
        }

        created() {
            this.getAnswers();
        }

        loadAnswers() {
            ++this.currentPage;
            this.getAnswers();
        }
    }
</script>

<style scoped>
    .commentlistWrap {
        margin-top: 30px;
        position: relative;
    }
</style>