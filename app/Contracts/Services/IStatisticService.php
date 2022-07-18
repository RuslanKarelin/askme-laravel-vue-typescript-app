<?php
namespace App\Contracts\Services;


interface IStatisticService {
    public function getStatisticForSidebar(): array;
}