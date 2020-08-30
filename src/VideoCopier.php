<?php
namespace Treissler\VideoCopier;

class VideoCopier {
    private $page;
    private $downloader;
    private $uploader;

    public function __construct (Page $page, Downloader $downloader, UploaderInterface $uploader) {
        $this->page = $page;
        $this->downloader = $downloader;
        $this->uploader = $uploader;       
    }

    public function copy(string $url):void {
        $embedLinks = $this->page->getLinks($url);
        $downloadData = $this->downloader->getLinks($embedLinks);
        $path = TEMP_FILE_PATH;
        try {
            foreach ($downloadData as $linkData) {
                $this->downloader->downloadFile($linkData['url'], $path);
                echo $this->uploader->upload($path, $linkData['title']);//вывод в консоль
                unlink($path);
            }
        } catch (\Exception $e) {
            echo $e->getMessage() . "\n";
            if (file_exists($path))
                unlink($path);
        }
    }
}

?>