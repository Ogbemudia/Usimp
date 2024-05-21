<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInita5236812cdd7b847961304f88dd7d67c
{
    public static $prefixLengthsPsr4 = array (
        'F' => 
        array (
            'Firebase\\JWT\\' => 13,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Firebase\\JWT\\' => 
        array (
            0 => __DIR__ . '/..' . '/firebase/php-jwt/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInita5236812cdd7b847961304f88dd7d67c::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInita5236812cdd7b847961304f88dd7d67c::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInita5236812cdd7b847961304f88dd7d67c::$classMap;

        }, null, ClassLoader::class);
    }
}
