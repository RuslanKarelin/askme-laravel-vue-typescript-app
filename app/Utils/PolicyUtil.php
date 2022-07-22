<?php

namespace App\Utils;

use Illuminate\Http\Request;
use Illuminate\Support\Str;


class PolicyUtil
{
    private $actions = [
        'questions.edit',
        'questions.update',
        'questions.destroy',
        'questions.answers.update',
        'questions.answers.destroy',
        'answers.comments.update',
        'answers.comments.destroy',
        'users.profile.edit',
        'users.profile.update',
        'users.profile.destroy',
    ];

    public function __construct(private Request $request){}

    public function handle(): bool
    {
        $routeName = $this->request->route()->getName();

        if (in_array($routeName, $this->actions)) {
            $routeNameArray = explode('.', $routeName);
            $actionName = array_pop($routeNameArray);
            $parameterName = $routeNameArray[count($routeNameArray)-1];
            if ($routeNameArray[0] === 'users') {
                $parameterName = $routeNameArray[0];
            }
            $parameterName = Str::singular($parameterName);
            $routeParameters = $this->request->route()->parameters;

            if (
                $actionName &&
                $parameterName &&
                array_key_exists($parameterName, $routeParameters) &&
                $this->request->user()->cannot($actionName, $routeParameters[$parameterName])
            ) return true;
        }

        return false;
    }
}