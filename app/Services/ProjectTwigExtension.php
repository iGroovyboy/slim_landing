<?php


namespace App\Services;


class ProjectTwigExtension extends \Twig\Extension\AbstractExtension implements \Twig\Extension\GlobalsInterface
{

    public function getGlobals(): array
    {
        return [
            'assets' => '/themes/' . Config::get('app/theme') . '/' . Config::$assetsDir,
        ];
    }

}
