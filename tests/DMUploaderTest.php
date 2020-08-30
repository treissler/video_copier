<?php
use PHPUnit\Framework\TestCase;
include dirname(__DIR__) . '/src/config.php';

class DMUploaderTest extends TestCase {
    private $apiMock;

    private $containerBuilder;

    public function setUp(): void
    {
        $this->apiMock = $this->createMock(Dailymotion::class);

        $this->containerBuilder = new DI\ContainerBuilder();
        $this->containerBuilder->addDefinitions([
            Dailymotion::class => $this->apiMock,
        ]);
    }

    public function testSuccessUpload()
    {
        $container = $this->containerBuilder->build();
        
        $videoId = "dnsk28";
        $testVideoPath = "test.mp4";
        $videoUrl = "testUrl";
        $videoTitle = "testTitle";

        $this->apiMock->expects($this->once())
                    ->method('uploadFile')
                    ->with($this->equalTo($testVideoPath))
                    ->willReturn($videoUrl);

        $this->apiMock->expects($this->once())
            ->method('post')
            ->with(
                $this->equalTo(DM_CHANNEL),
                $this->callback(function($params) use ($videoUrl, $videoTitle) {
                    return $params['url'] === $videoUrl
                            && $params['title'] === $videoTitle;
                  }))
            ->willReturn(['id' => $videoId]);

        $uploader = $container->get('\Treissler\VideoCopier\DMUploader');
        $result = $uploader->upload($testVideoPath, $videoTitle);

        $this->assertEquals($result, DM_VIDEO_HOST . $videoId);
    }
}

?>

