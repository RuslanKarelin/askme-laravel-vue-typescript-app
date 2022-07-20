<template>
    <div class="question-list">
        <loader :show="isLoad"/>
        <question v-for="question in questionList" :key="filter + '_question_' + question.id" :question="question"/>
        <a v-if="lastPage > currentPage" @click.prevent="loadQuestions" href="#" class="load-questions"><i
                class="icon-refresh"></i>Load More Questions</a>
    </div>
</template>

<script lang="ts">
    import {Vue, Component, Prop} from "vue-property-decorator";
    import {QuestionService} from '../../services/QuestionService'
    import {Question as QuestionModel} from '../../models/Question';
    import Question from './Question.vue';
    import Loader from '../Loader.vue';

    @Component({
        components: {
            Question,
            Loader
        }
    })
    export default class QuestionList extends Vue {
        @Prop({required: false, default: ''}) filter: string;

        questionService: QuestionService;
        questionList: QuestionModel[] = [];
        dataFromResponse: { [key: string]: any } = {};
        currentPage = 1;
        isLoad = false;

        constructor() {
            super();
            this.questionService = new QuestionService();
        }

        get lastPage() {
            return this.dataFromResponse?.meta?.last_page
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

        async created() {
            this.isLoad = true;
            this.dataFromResponse = await this.questionService.getList(this.query);
            this.questionList = this.dataFromResponse.data;
            this.isLoad = false;
        }

        async loadQuestions() {
            this.isLoad = true;
            ++this.currentPage;
            this.dataFromResponse = await this.questionService.getList(this.query);
            this.questionList = this.questionList.concat(this.dataFromResponse.data);
            this.isLoad = false;
        }
    }
</script>