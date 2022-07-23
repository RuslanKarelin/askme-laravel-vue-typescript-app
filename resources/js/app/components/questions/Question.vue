<template>
    <article class="question question-type-normal">
        <h2>
            <a :href="'/questions/' + question.id">{{question.title}}</a>
        </h2>
        <div class="question-author">
            <a :href="'/users/' + question.user.id + '/profile'" original-title="ahmed" class="question-author-img tooltip-n"><span></span><img
                    alt="" :src="question.user.profile.avatar"></a>
        </div>
        <div class="question-inner">
            <div class="clearfix"></div>
            <p class="question-desc">{{detail}}</p>
            <div class="question-details">
                <span class="question-answered" :class="{'question-answered-done': question.status.id === 2}"><i class="icon-ok"></i>{{question.status.title}}</span>
            </div>
            <span class="question-date"><i class="icon-time"></i>{{question.created_at.toRelative()}}</span>
            <span class="question-comment"><i class="icon-comment"></i>{{question.answers_count}} Answer</span>
            <span class="question-view"><i class="icon-user"></i>{{question.state.views}} views</span>
            <div class="clearfix"></div>
        </div>
    </article>
</template>

<script lang="ts">
    import {Vue, Component, Prop} from "vue-property-decorator";
    import {Question as QuestionModel} from '../../models/Question';

    @Component({})
    export default class Question extends Vue {
        @Prop({required: true}) question: QuestionModel;

        get detail() {
            if (this.question.detail && this.question.detail.length > 100) {
                this.question.detail = this.question.detail?.slice(0, 100) + '...'
            }
            return this.question.detail;
        }
    }
</script>