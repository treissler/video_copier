<?php
namespace Treissler\VideoCopier;

class Page {
    public function getLinks(string $url):array {
        //проверка ввода

        //парсим ссылки на видео
        $content = $this->getContent($url);
        $embedLinks = $this->extractLinks($content);
        return $embedLinks;
    }

    private function getContent (string $url):string {
        $output = curl_init();
        curl_setopt($output, CURLOPT_URL, $url);
        curl_setopt($output, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($output, CURLOPT_HEADER, 0);
        $content = curl_exec($output);
        curl_close($output);

        return $content;
    }

    private function extractLinks (string $text):array {
        preg_match_all(YT_LINK_REGEXP, $text, $matches);
        return $matches[1];
    }
} 
?>