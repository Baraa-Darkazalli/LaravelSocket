<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit037bf6e9575fbb9c84bd0dde39c69432
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'Psr\\Log\\' => 8,
        ),
        'E' => 
        array (
            'ElephantIO\\' => 11,
        ),
        'B' => 
        array (
            'Baraadark\\Laravelsocket\\' => 24,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Psr\\Log\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/log/src',
        ),
        'ElephantIO\\' => 
        array (
            0 => __DIR__ . '/..' . '/elephantio/elephant.io/src',
        ),
        'Baraadark\\Laravelsocket\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit037bf6e9575fbb9c84bd0dde39c69432::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit037bf6e9575fbb9c84bd0dde39c69432::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit037bf6e9575fbb9c84bd0dde39c69432::$classMap;

        }, null, ClassLoader::class);
    }
}
