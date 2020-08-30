#!/usr/bin/php
<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/vendor/autoload.php';

use Treissler\VideoCopier\Page;
use Treissler\VideoCopier\Downloader;
use Treissler\VideoCopier\VideoCopier;
use Treissler\VideoCopier\UploaderInterface;
use Treissler\VideoCopier\DMUploader;

include dirname(__DIR__) . '/src/config.php';


$containerBuilder = new DI\ContainerBuilder();
$containerBuilder->addDefinitions([
    Dailymotion::class => DI\factory(function () {
        return new Dailymotion();
    }),
    Page::class => DI\autowire(Page::class),
    Downloader::class => DI\autowire(Downloader::class),
    UploaderInterface::class => DI\autowire(DMUploader::class),
    VideoCopier::class => DI\autowire(VideoCopier::class)
]);

$container = $containerBuilder->build();

$url = $argv[1];
$videoCopier = $container->get("Treissler\VideoCopier\VideoCopier");
$videoCopier->copy($url);
?>