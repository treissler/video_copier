<?php
namespace Treissler\VideoCopier;

class DMUploader implements UploaderInterface {
    private $api;

    public function __construct (\Dailymotion $dailymotion) {
        $scopes = array('userinfo', 'feed', 'manage_videos');

        $this->api = $dailymotion;
        $this->api->setGrantType(
            \Dailymotion::GRANT_TYPE_PASSWORD,
            DM_API_KEY,
            DM_API_SECRET,
            $scopes,
            array(
                'username' => DM_USER_USERNAME,
                'password' => DM_USER_PASSWORD
            )
        );
    }

    public function upload (string $filePath, string $videoTitle):string {
        $url = $this->preUpload ($filePath);
        $result = $this->fullUpload($url, $videoTitle);

        return DM_VIDEO_HOST . $result['id'];
    }

    private function preUpload (string $filePath):string {
        $url = $this->api->uploadFile($filePath);
        return $url;
    }

    private function fullUpload (string $url, string $videoTitle):array {
        $result = $this->api->post(
            DM_CHANNEL,
            array('url' => $url, 'title' => $videoTitle)
        );
        return $result;
    }   
}
?>