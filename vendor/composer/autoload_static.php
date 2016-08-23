<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit15d81b2fc45a7b220b4a70b52733af08
{
    public static $prefixLengthsPsr4 = array (
        'D' => 
        array (
            'DrewM\\MailChimp\\' => 16,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'DrewM\\MailChimp\\' => 
        array (
            0 => __DIR__ . '/..' . '/drewm/mailchimp-api/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit15d81b2fc45a7b220b4a70b52733af08::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit15d81b2fc45a7b220b4a70b52733af08::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
