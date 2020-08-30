<?php
namespace Treissler\VideoCopier;

interface UploaderInterface {
    public function upload (string $filePath, string $videoTitle):string;
}
?>