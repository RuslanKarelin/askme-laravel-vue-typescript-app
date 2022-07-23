<?php

namespace App\Http\Controllers;

use App\Helpers\PageHelper;

class HomeController extends Controller
{
    private $pageHelper;

    public function __construct()
    {
        $this->pageHelper = app(PageHelper::class);
    }

    public function index()
    {
        $this->pageHelper->setPageTitle('Home');
        return view('themes.askme.pages.home.home');
    }
}
