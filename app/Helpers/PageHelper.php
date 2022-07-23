<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Request;

class PageHelper
{
    private $breadcrumbs = [];
    private $pageTitle = '';
    private $pageH1 = null;

    public function setBreadcrumbs(array $breadcrumbs): static
    {
        foreach ($breadcrumbs as $breadcrumb) {
            $this->breadcrumbs[] = $this->addBreadcrumb($breadcrumb['title'], $breadcrumb['url']);
        }
        return $this;
    }

    public function addBreadcrumb(string $title = '', string $url = ''): static
    {
        $this->breadcrumbs[] = new Breadcrumb($title, $url);
        return $this;
    }

    public function breadcrumbs(): array
    {
        return $this->breadcrumbs;
    }

    public function setDefaultBreadcrumb(): static
    {
        $this->breadcrumbs = [];
        $this->addBreadcrumb('Home', route('home'));
        return $this;
    }

    public function pageTitle(): string
    {
        return $this->pageTitle;
    }

    public function setPageTitle(string $title): static
    {
        $this->pageTitle = $title;
        return $this;
    }

    public function pageH1(): string
    {
        return $this->pageH1 ?? $this->pageTitle;
    }

    public function setPageH1(string $h1): static
    {
        $this->pageH1 = $h1;
        return $this;
    }

    public function isActiveLink(string $routeName): bool
    {
        return Request::route()->getName() === $routeName;
    }
}