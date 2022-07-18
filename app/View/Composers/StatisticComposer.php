<?php

namespace App\View\Composers;

use Illuminate\View\View;
use App\Contracts\Services\IStatisticService;


class StatisticComposer
{
    protected $statisticService;

    public function __construct()
    {
        $this->statisticService = app(IStatisticService::class);
    }

    public function compose(View $view)
    {
        $view->with(
            $this->statisticService->getStatisticForSidebar()
        );
    }
}