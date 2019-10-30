<?php

declare(strict_types=1);

namespace PackageVersions;

/**
 * This class is generated by ocramius/package-versions, specifically by
 * @see \PackageVersions\Installer
 *
 * This file is overwritten at every run of `composer install` or `composer update`.
 */
final class Versions
{
    public const ROOT_PACKAGE_NAME = '__root__';
    /**
     * Array of all available composer packages.
     * Dont read this array from your calling code, but use the \PackageVersions\Versions::getVersion() method instead.
     *
     * @var array<string, string>
     * @internal
     */
    public const VERSIONS          = array (
  'doctrine/annotations' => 'v1.8.0@904dca4eb10715b92569fbcd79e201d5c349b6bc',
  'doctrine/cache' => 'v1.8.0@d768d58baee9a4862ca783840eca1b9add7a7f57',
  'doctrine/collections' => 'v1.6.2@c5e0bc17b1620e97c968ac409acbff28b8b850be',
  'doctrine/common' => 'v2.11.0@b8ca1dcf6b0dc8a2af7a09baac8d0c48345df4ff',
  'doctrine/data-fixtures' => 'v1.3.2@09b16943b27f3d80d63988d100ff256148c2f78b',
  'doctrine/dbal' => 'v2.9.2@22800bd651c1d8d2a9719e2a3dc46d5108ebfcc9',
  'doctrine/doctrine-bundle' => '1.11.2@28101e20776d8fa20a00b54947fbae2db0d09103',
  'doctrine/doctrine-cache-bundle' => '1.3.5@5514c90d9fb595e1095e6d66ebb98ce9ef049927',
  'doctrine/doctrine-fixtures-bundle' => '3.2.2@90e4a4f968b2dae40e290a6ee516957af043f16c',
  'doctrine/doctrine-migrations-bundle' => 'v2.0.0@4c9579e0e43df1fb3f0ca29b9c20871c824fac71',
  'doctrine/event-manager' => 'v1.0.0@a520bc093a0170feeb6b14e9d83f3a14452e64b3',
  'doctrine/inflector' => 'v1.3.0@5527a48b7313d15261292c149e55e26eae771b0a',
  'doctrine/instantiator' => '1.2.0@a2c590166b2133a4633738648b6b064edae0814a',
  'doctrine/lexer' => '1.1.0@e17f069ede36f7534b95adec71910ed1b49c74ea',
  'doctrine/migrations' => '2.1.1@a89fa87a192e90179163c1e863a145c13337f442',
  'doctrine/orm' => 'v2.6.4@b52ef5a1002f99ab506a5a2d6dba5a2c236c5f43',
  'doctrine/persistence' => '1.1.1@3da7c9d125591ca83944f477e65ed3d7b4617c48',
  'doctrine/reflection' => 'v1.0.0@02538d3f95e88eb397a5f86274deb2c6175c2ab6',
  'friendsofsymfony/jsrouting-bundle' => '2.4.0@e42ed450eac2b61d5fcba9cd834c294a429e9a40',
  'jdorn/sql-formatter' => 'v1.2.17@64990d96e0959dff8e059dfcdc1af130728d92bc',
  'monolog/monolog' => '1.25.1@70e65a5470a42cfec1a7da00d30edb6e617e8dcf',
  'ocramius/package-versions' => '1.5.1@1d32342b8c1eb27353c8887c366147b4c2da673c',
  'ocramius/proxy-manager' => '2.2.3@4d154742e31c35137d5374c998e8f86b54db2e2f',
  'psr/cache' => '1.0.1@d11b50ad223250cf17b86e38383413f5a6764bf8',
  'psr/container' => '1.0.0@b7ce3b176482dbbc1245ebf52b181af44c2cf55f',
  'psr/log' => '1.1.0@6c001f1daafa3a3ac1d8ff69ee4db8e799a654dd',
  'symfony/annotations-pack' => 'v1.0.0@18c762ffefaede85efa911e840ab2eb0e33993b5',
  'symfony/cache' => 'v4.3.5@40c62600ebad1ed2defbf7d35523d918a73ab330',
  'symfony/cache-contracts' => 'v1.1.7@af50d14ada9e4e82cfabfabdc502d144f89be0a1',
  'symfony/config' => 'v4.3.5@0acb26407a9e1a64a275142f0ae5e36436342720',
  'symfony/console' => 'v4.3.5@929ddf360d401b958f611d44e726094ab46a7369',
  'symfony/debug' => 'v4.3.5@cc5c1efd0edfcfd10b354750594a46b3dd2afbbe',
  'symfony/dependency-injection' => 'v4.3.5@e1e0762a814b957a1092bff75a550db49724d05b',
  'symfony/doctrine-bridge' => 'v4.3.5@486fa65a74692d84f250087c79d0b89d30d655a8',
  'symfony/event-dispatcher' => 'v4.3.5@6229f58993e5a157f6096fc7145c0717d0be8807',
  'symfony/event-dispatcher-contracts' => 'v1.1.7@c43ab685673fb6c8d84220c77897b1d6cdbe1d18',
  'symfony/filesystem' => 'v4.3.5@9abbb7ef96a51f4d7e69627bc6f63307994e4263',
  'symfony/finder' => 'v4.3.5@5e575faa95548d0586f6bedaeabec259714e44d1',
  'symfony/flex' => 'v1.4.6@133e649fdf08aeb8741be1ba955ccbe5cd17c696',
  'symfony/framework-bundle' => 'v4.3.5@fca765488ecea04bf6c1c502d7b0214fa29460d8',
  'symfony/http-foundation' => 'v4.3.5@76590ced16d4674780863471bae10452b79210a5',
  'symfony/http-kernel' => 'v4.3.5@5f08141850932e8019c01d8988bf3ed6367d2991',
  'symfony/inflector' => 'v4.3.5@fc488a52c79b2bbe848fa9def35f2cccb47c4798',
  'symfony/mime' => 'v4.3.5@32f71570547b91879fdbd9cf50317d556ae86916',
  'symfony/monolog-bridge' => 'v4.3.5@6b9d84b34e0c2c5d9d4f4dbd5f36b0c9e4e5ef93',
  'symfony/monolog-bundle' => 'v3.4.0@7fbecb371c1c614642c93c6b2cbcdf723ae8809d',
  'symfony/orm-pack' => 'v1.0.6@36c2a928482dc5f05c5c1c1b947242ae03ff1335',
  'symfony/polyfill-ctype' => 'v1.12.0@550ebaac289296ce228a706d0867afc34687e3f4',
  'symfony/polyfill-intl-idn' => 'v1.12.0@6af626ae6fa37d396dc90a399c0ff08e5cfc45b2',
  'symfony/polyfill-mbstring' => 'v1.12.0@b42a2f66e8f1b15ccf25652c3424265923eb4f17',
  'symfony/polyfill-php72' => 'v1.12.0@04ce3335667451138df4307d6a9b61565560199e',
  'symfony/polyfill-php73' => 'v1.12.0@2ceb49eaccb9352bff54d22570276bb75ba4a188',
  'symfony/property-access' => 'v4.3.5@bb0c302375ffeef60c31e72a4539611b7f787565',
  'symfony/routing' => 'v4.3.5@3b174ef04fe66696524efad1e5f7a6c663d822ea',
  'symfony/security' => 'v4.3.5@78ee59bdea6f34658f0c1a296b558aa922c14dac',
  'symfony/security-bundle' => 'v4.3.5@aa3cd52168c2e5c99effe560907f22fcffe8a788',
  'symfony/serializer' => 'v4.3.5@805eacc72d28e237ef31659344a4d72acef335ec',
  'symfony/service-contracts' => 'v1.1.7@ffcde9615dc5bb4825b9f6aed07716f1f57faae0',
  'symfony/stopwatch' => 'v4.3.5@1e4ff456bd625be5032fac9be4294e60442e9b71',
  'symfony/translation-contracts' => 'v1.1.7@364518c132c95642e530d9b2d217acbc2ccac3e6',
  'symfony/validator' => 'v4.3.5@dd344bae7894ce8d6c399d854d894eb6e52ee178',
  'symfony/var-exporter' => 'v4.3.5@d5b4e2d334c1d80e42876c7d489896cfd37562f2',
  'willdurand/jsonp-callback-validator' => 'v1.1.0@1a7d388bb521959e612ef50c5c7b1691b097e909',
  'zendframework/zend-code' => '3.4.0@46feaeecea14161734b56c1ace74f28cb329f194',
  'zendframework/zend-eventmanager' => '3.2.1@a5e2583a211f73604691586b8406ff7296a946dd',
  'symfony/profiler-pack' => 'v1.0.4@99c4370632c2a59bb0444852f92140074ef02209',
  'symfony/twig-bridge' => 'v4.3.5@499b3f3aedffa44e4e30b476bbd433854afc9bc3',
  'symfony/twig-bundle' => 'v4.3.5@c27738bb0d9b314b96a323aebc5f40a20e2a644b',
  'symfony/var-dumper' => 'v4.3.5@bde8957fc415fdc6964f33916a3755737744ff05',
  'symfony/web-profiler-bundle' => 'v4.3.5@b52bb32e6182d924303dbeb9c584396819fef118',
  'twig/twig' => 'v2.12.1@ddd4134af9bfc6dba4eff7c8447444ecc45b9ee5',
  '__root__' => 'dev-Home@1205193ccc0038f3e657d2d3157963c19e2763ab',
);

    private function __construct()
    {
    }

    /**
     * @throws \OutOfBoundsException If a version cannot be located.
     *
     * @psalm-param key-of<self::VERSIONS> $packageName
     */
    public static function getVersion(string $packageName) : string
    {
        if (isset(self::VERSIONS[$packageName])) {
            return self::VERSIONS[$packageName];
        }

        throw new \OutOfBoundsException(
            'Required package "' . $packageName . '" is not installed: check your ./vendor/composer/installed.json and/or ./composer.lock files'
        );
    }
}
