<?php

namespace App\Enums;


enum QuestionFilter: string {
    case recentQuestions = "recentQuestions";
    case mostResponses = "mostResponses";
    case recentlyAnswered = "recentlyAnswered";
    case noAnswers = "noAnswers";
}