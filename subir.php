<?php
   require 'vendor/autoload.php';

   use Aws\S3\S3Client;
   use Aws\CommandPool;
   use Aws\CommandInterface;
   use Aws\Exception\AwsException;
   use Guzzle\Service\Exception\CommandTransferException;

   $s3Client = new S3Client([
       'region' => 'us-east-1',
       'version' => 'latest',
   	'credentials' => [
           'key'    => 'AKIAIFF4UF2BCFOCP3RA',
           'secret' => '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',
       ]
   ]);
   $commands = array();
   $bucket = 'vendefacil';
   @mkdir('uploads');

       $commands[] = $s3Client->getCommand('PutObject', array(
           'Bucket' => $bucket,
           'Key'    => "subir.jpg",
           'Body' => file_get_contents("subir.jpg"),
           'ACL' => 'public-read'
       ));

       $pool = new CommandPool($s3Client, $commands);
       $promise = $pool->promise();
       $result = $promise->wait();

       print_r($result);
