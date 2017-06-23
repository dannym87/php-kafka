<?php

const KAFKA_PARTITION = 0;
const KAFKA_TOPIC_TEST = 'test';

use Monolog\Handler\StreamHandler;
use Monolog\Logger;

require_once __DIR__ . '/vendor/autoload.php';

$logger = new Logger('consumer');
$logger->pushHandler(new StreamHandler(__DIR__ . '/data/logs/consumer.log'));
$logger->debug('Running consumer...');

$conf = new RdKafka\Conf();

// Set the group id. This is required when storing offsets on the broker
$conf->set('group.id', 'myConsumerGroup');

$kafka = new RdKafka\Consumer($conf);
$kafka->addBrokers('kafka');

$topicConf = new RdKafka\TopicConf();
$topicConf->set('auto.commit.interval.ms', 100);

// Set the offset store method to 'file'
$topicConf->set('offset.store.method', 'file');
$topicConf->set('offset.store.path', sys_get_temp_dir());

// Alternatively, set the offset store method to 'broker'
// $topicConf->set('offset.store.method', 'broker');

// Set where to start consuming messages when there is no initial offset in
// offset store or the desired offset is out of range.
// 'smallest': start from the beginning
$topicConf->set('auto.offset.reset', 'smallest');

$topic = $kafka->newTopic(KAFKA_TOPIC_TEST, $topicConf);

// Start consuming partition 0
$topic->consumeStart(KAFKA_PARTITION, RD_KAFKA_OFFSET_STORED);

while (true) {
    $message = $topic->consume(KAFKA_PARTITION, 120*10000);
    switch ($message->err) {
        case RD_KAFKA_RESP_ERR_NO_ERROR:
            $logger->info($message->payload);
            break;
        case RD_KAFKA_RESP_ERR__PARTITION_EOF:
            $logger->debug('No more messages; will wait for more');
            break;
        case RD_KAFKA_RESP_ERR__TIMED_OUT:
            $logger->warn('Timed out');
            break;
        default:
            $logger->err($message->errstr() . ' - ' . $message->err);
            throw new \Exception($message->errstr(), $message->err);
            break;
    }
}
