<template>
    <div class="tabs-warp question-tab">
        <ul class="tabs">
            <li class="tab"><a href="#" class="current">Recent Questions</a></li>
            <li class="tab"><a href="#">Most Responses</a></li>
            <li class="tab"><a href="#">Recently Answered</a></li>
            <li class="tab"><a href="#">No answers</a></li>
        </ul>
        <div class="tab-inner-warp">
            <div class="tab-inner">
                <question-list filter="recentQuestions"/>
            </div>
        </div>
        <div class="tab-inner-warp">
            <div class="tab-inner">
                <question-list filter="mostResponses"/>
            </div>
        </div>
        <div class="tab-inner-warp">
            <div class="tab-inner">
                <question-list filter="recentlyAnswered"/>
            </div>
        </div>
        <div class="tab-inner-warp">
            <div class="tab-inner">
                <question-list filter="noAnswers"/>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
    import {Vue, Component} from "vue-property-decorator";
    import QuestionList from '../QuestionList.vue';

    @Component({
        components: {
            QuestionList
        }
    })
    export default class QuestionListOnMain extends Vue {

    }
</script>