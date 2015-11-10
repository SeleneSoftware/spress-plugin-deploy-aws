Spress Pluging Deploy AWS S3
============================

A plugin to deploy your site to a bucket in AWS S3.  Simple really.  Just answer the questions, and all will be well.

## Installation
This plugin requires SDK credentials.  If you don't know what those are, talk to your AWS administrator, or read more about them.  The plugin will pull the AWS credentials from a primary credential file located at `~/.aws/credentials`.  Tolearn more about this, please read http://docs.aws.amazon.com/aws-sdk-php/v3/guide/guide/credentials.html .

Once that is out of the way, time to install the plugin.  If you know how to install plugins, good for you!  If not, keep reading.  Just require the plugin in your composer.json file inside your project, then update.  Or if you are cli savvy like myself, try this:

composer require "SeleneSoftware/spress-plugin-deploy-aws"

Otherwise, the composer file:

"require" {
    "SeleneSoftware/spress-plugin-deploy-aws": "~0.1"
}

And then a composer update, and all should be set.  Spress will automaticly include the plugin in your site.  Have fun!
