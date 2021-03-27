<?php


namespace App\Services;


class Install
{

    /** TODO move this to theme.install module
     * Creates symlink from root/themes/.. to root/public/themes/..
     *
     * @param null|View $view
     */
    public static function createAssetsSymlink(View $view = null): void
    {
            $originalDir    = $view->getThemePath() . Config::$assetsDir . DS;
            $publicThemeDir = Config::getPath('app/paths/public')
                              . Config::get('app/paths/themes')
                              . DS . $view->getTheme();
            $link           = $publicThemeDir . DS . Config::$assetsDir . DS;



        if (is_link($link)) {
            return;
        }

        $mkdir = mkdir($publicThemeDir, 0664); //0775?
        if ( ! $mkdir) {
            if ( ! file_exists($mkdir) || ! is_dir($mkdir)) {
                // TODO log?
            }
        }

        $symlink = symlink($originalDir, $link);

        if ( ! $symlink) {
            // TODO log?
        }
    }
}
