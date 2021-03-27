<?php


namespace App\Services;


class Install
{
    public static function default()
    {
        // migrate db

        // add admin user

        // update config and options

        // create default directories

        // create symlinks

    }

    public static function publicThemes()
    {
        $srcThemesDir  = Config::getPath('app/paths/themes');
        $destThemesDir = Config::getPath('app/paths/public') . Config::get('app/paths/themes') . DS;

        $dirs = array_diff(scandir($srcThemesDir), array('..', '.', 'readme.md'));

//        $x = array_filter(glob($srcThemesDir . '*' , GLOB_ONLYDIR), 'is_dir');

        $mkdir = mkdir($destThemesDir, 0664); //0775?
        if ( ! $mkdir && ! file_exists($destThemesDir)) {
            throw new \Exception("Can't create theme folder!");
            // TODO log - cant create dir
        }

        array_map(function ($dir) use ($srcThemesDir, $destThemesDir) {
            mkdir($destThemesDir . $dir);
            symlink(
                $srcThemesDir . $dir . DS . Config::$assetsDir,
                $destThemesDir . $dir . DS . Config::$assetsDir
            );
        }, $dirs);
    }

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
