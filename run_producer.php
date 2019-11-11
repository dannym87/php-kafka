<?php

const KAFKA_PARTITION = 0;
const KAFKA_TOPIC_TEST = 'test';

use Monolog\Handler\StreamHandler;
use Monolog\Logger;

require_once __DIR__ . '/vendor/autoload.php';

$logger = new Logger('producer');
$logger->pushHandler(new StreamHandler(__DIR__ . '/data/logs/producer.log'));
$logger->debug('Running producer...');

$conf = new RdKafka\Conf();
//$conf->set('debug','all');

$kafka = new RdKafka\Producer($conf);
$kafka->setLogLevel(LOG_DEBUG);
$kafka->addBrokers('kafka');

$topic = $kafka->newTopic(KAFKA_TOPIC_TEST);

for ($i = 0; $i < 10; $i++) {
    $message = sprintf('Message %d', $i);
    $logger->debug(sprintf('Producing: %s', $message));
    $topic->produce(KAFKA_PARTITION, 0, $message);
    $kafka->poll(0);
}

for ($flushRetries = 0; $flushRetries < 10; $flushRetries++) {
    $result = $kafka->flush(10000);
    if (RD_KAFKA_RESP_ERR_NO_ERROR === $result) {
        break;
    } else {
        $logger->error('Error in broker when flushing: ' . $result);
    }
}

if (RD_KAFKA_RESP_ERR_NO_ERROR !== $result) {
    throw new \RuntimeException('Was unable to flush, messages might be lost!');
}
