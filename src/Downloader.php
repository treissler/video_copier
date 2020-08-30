<?php
namespace Treissler\VideoCopier;
use YouTube\YouTubeDownloader;

class Downloader {
    private $yt;
    public function __construct (YouTubeDownloader $yt) {
        $this->yt = $yt;
    }

    public function getLinks (array $linksArr):array {
        $videoLinks  = [];

        foreach ($linksArr as $videoUrl) {
            $videoId = $this->yt->extractVideoId($videoUrl);
            $videoInfo = $this->yt->getVideoInfo($videoId);
            $allExistingLinks = $this->yt->getDownloadLinks($videoId);

            $fullVideoLink = $this->selectFullVideoLink($allExistingLinks);
            $videoData = [ 'url'=>$fullVideoLink, 'title' => $videoInfo['player_response']['videoDetails']['title'] ];

            array_push($videoLinks, $videoData);
        }
        return $videoLinks;
    }

    public function downloadFile(string $url, string $path):void {
        $newfname = $path;
        $file = fopen ($url, 'rb');
        if ($file) {
            $newf = fopen ($newfname, 'wb');
            if ($newf) {
                while(!feof($file)) {
                    fwrite($newf, fread($file, 1024 * 8), 1024 * 8);
                }
            }
        }
        if ($file) {
            fclose($file);
        }
        if ($newf) {
            fclose($newf);
        }
    }

    private function selectFullVideoLink (array $linksArr):string {
        foreach ($linksArr as $link) {
            preg_match(YT_FORMAT_REGEXP, $link["format"], $matches);
            if (count($matches) > 0)
                return $link["url"];
        }
        return "";
    }
}

?>

