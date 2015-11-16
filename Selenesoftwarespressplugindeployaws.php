<?php


use Symfony\Component\EventDispatcher\Event;
use Yosymfony\Spress\Plugin\EventSubscriber;
use Yosymfony\Spress\Plugin\Event\AfterConvertPostsEvent;
use Yosymfony\Spress\Plugin\Event\ConvertEvent;
use Yosymfony\Spress\Plugin\Event\EnvironmentEvent;
use Yosymfony\Spress\Plugin\Event\FinishEvent;
use Yosymfony\Spress\Plugin\Event\RenderEvent;
use Yosymfony\Spress\Plugin\Plugin;

use Aws\S3\S3Client;

class Selenesoftwarespressplugindeployaws extends Plugin
{
    private $io;

    /**
     * The name of the S3 bucket to upload to
     * @var string
     */
    protected $bucket;

    /**
     * Array of config options
     * @var array
     */
    protected $config;

    /**
     * The S3 client object
     * @var Aws\S3\S3Client
     */
    protected $s3;

    /**
     * Destination directory for the rendered pages
     * @var string
     */ 
    protected $dir;

    public function initialize(EventSubscriber $subscriber)
    {
        $subscriber->addEventListener('spress.start', 'onStart');
        $subscriber->addEventListener('spress.before_convert', 'onBeforeConvert');
        $subscriber->addEventListener('spress.after_convert', 'onAfterConvert');
        $subscriber->addEventListener('spress.after_convert_posts', 'onAfterConvertPosts');
        $subscriber->addEventListener('spress.before_render', 'onBeforeRender');
        $subscriber->addEventListener('spress.after_render', 'onAfterRender');
        $subscriber->addEventListener('spress.before_render_pagination', 'onBeforeRenderPagination');
        $subscriber->addEventListener('spress.after_render_pagination', 'onAfterRenderPagination');
        $subscriber->addEventListener('spress.finish', 'onFinish');
    }

    public function onStart(EnvironmentEvent $event)
    {
        $this->io = $event->getIO();

        $this->config = $event->getConfigRepository()->getArray();

        $this->dir = $event->getDestinationDir();

        if ($this->io->isInteractive()) {
             $answer = $this->io->askConfirmation(
                "Do you want to deploy to your AWS S3 bucket? (y/N): ", 
                false);

            if($answer)
            {
                $this->s3 = new S3Client([
                    'region'  => $this->config['aws']['region'],
                    'version' => 'latest',
                    'http'    => [
                        'connect_timeout' => 5
                    ]
                ]);
                $bucket = $this->io->ask('Bucket Name (spress): ', 'spress');
                if (!$this->s3->doesBucketExist($bucket)) {
                    $this->io->write('Bucket does not exist.  Please use the console at http://aws.amazon.com to create it.');
                    return;
                } else {
                    $this->bucket = $bucket;
                }
            }
        }
    }

    public function onBeforeConvert(ConvertEvent $event)
    {

    }

    public function onAfterConvert(ConvertEvent $event)
    {

    }

    public function onAfterConvertPosts(AfterConvertPostsEvent $event)
    {

    }

    public function onBeforeRender(RenderEvent $event)
    {

    }

    public function onAfterRender(RenderEvent $event)
    {

    }

    public function onBeforeRenderPagination(RenderEvent $event)
    {

    }

    public function onAfterRenderPagination(RenderEvent $event)
    {

    }

    public function onFinish(FinishEvent $event)
    {
        if ($this->bucket) {
            var_dump($this->dir);
        }
    }
}
