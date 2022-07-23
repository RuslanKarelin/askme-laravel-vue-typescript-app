<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\UserService;
use App\Contracts\Services\IUserService;
use App\Services\FileService;
use App\Contracts\Services\IFileService;
use App\Services\QuestionService;
use App\Contracts\Services\IQuestionService;
use App\Services\AnswerService;
use App\Contracts\Services\IAnswerService;
use App\Services\CommentService;
use App\Contracts\Services\ICommentService;
use App\Services\SearchService;
use App\Contracts\Services\ISearchService;
use App\Services\StatisticService;
use App\Contracts\Services\IStatisticService;
use App\Contracts\Helpers\Response\IResponseHelper;
use App\Helpers\Response\ResponseHelper;
use App\Helpers\PageHelper;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(IUserService::class, function ($app) {
            return new UserService();
        });

        $this->app->bind(IFileService::class, function ($app) {
            return new FileService();
        });

        $this->app->bind(IQuestionService::class, function ($app) {
            return new QuestionService();
        });

        $this->app->bind(IAnswerService::class, function ($app) {
            return new AnswerService();
        });

        $this->app->bind(ICommentService::class, function ($app) {
            return new CommentService();
        });

        $this->app->bind(IResponseHelper::class, function ($app) {
            return new ResponseHelper();
        });

        $this->app->bind(IStatisticService::class, function ($app) {
            return new StatisticService();
        });

        $this->app->bind(ISearchService::class, function ($app) {
            return new SearchService();
        });

        $this->app->singleton(PageHelper::class, function ($app) {
            return new PageHelper();
        });
    }
}
