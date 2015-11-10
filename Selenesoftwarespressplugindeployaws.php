<?php


use Symfony\Component\EventDispatcher\Event;
use Yosymfony\Spress\Plugin\EventSubscriber;
use Yosymfony\Spress\Plugin\Event\AfterConvertPostsEvent;
use Yosymfony\Spress\Plugin\Event\ConvertEvent;
use Yosymfony\Spress\Plugin\Event\EnvironmentEvent;
use Yosymfony\Spress\Plugin\Event\FinishEvent;
use Yosymfony\Spress\Plugin\Event\RenderEvent;
use Yosymfony\Spress\Plugin\Plugin;

class Selenesoftwarespressplugindeployaws extends Plugin
{
    private $io;

    /**
     * The name of the S3 bucket to upload to
     * @var string
     */
    protected $bucket;

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

        if ($this->io->isInteractive()) {
             $answer = $this->io->askConfirmation(
                "Do you want to connect to deploy to your AWS S3 bucket? ", 
                false);

            if($answer)
            {
                $this->bucket = $this->io->ask('Bucket Name: ');
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
    {var_dump($event->getPayload());die();
        if ($this->bucket) {
            $aws = ([
                'region'  => 'us-west-2',
		'version' => 'latest',
		'http'    => [
		    'connect_timeout' => 5
		]
	]);
        }
    }
}
