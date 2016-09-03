<?php

/*

   Copyright 2015 Selene Software

   Licensed under the Apache License, Version 2.0 (the "License");
   you may not use this file except in compliance with the License.
   You may obtain a copy of the License at

     http://www.apache.org/licenses/LICENSE-2.0

   Unless required by applicable law or agreed to in writing, software
   distributed under the License is distributed on an "AS IS" BASIS,
   WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
   See the License for the specific language governing permissions and
   limitations under the License.

*/

use Yosymfony\Spress\Core\Plugin\PluginInterface;
use Yosymfony\Spress\Core\Plugin\EventSubscriber;
use Yosymfony\Spress\Core\Plugin\Event\EnvironmentEvent;
use Yosymfony\Spress\Core\Plugin\Event\FinishEvent;

use Aws\S3\S3Client;
use Aws\S3\Transfer;

class Selenesoftwarespressplugindeployaws implements PluginInterface
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

    public function onFinish(FinishEvent $event)
    {
        if ($this->bucket) {
            $manager = new Transfer($this->s3, $this->dir, 's3://' . $this->bucket);
            $manager->transfer();
        }
    }

    /**
     * Gets the metas of a plugin.
     *
     * Standard metas:
     *   - name: (string) The name of plugin.
     *   - description: (string) A short description of the plugin.
     *   - author: (string) The author of the plugin.
     *   - license: (string) The license of the plugin.
     *
     * @return array
     */
    public function getMetas()
    {
        return [
            'name' => 'selene-software/spress-plugin-deploy-aws',
            'description' => 'Deploy your site to an AWS S3 bucket for static site hosting.',
            'author' => 'Jason Marshall, Hendrik Grahl',
            'license' => 'Apache-2.0',
        ];
    }
}
