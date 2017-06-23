# PHP 7.1 Kafka Sample Application Using Docker

This is by no means a geniune application. It's merely used to test a basic producer and consumer. Code for producer and consumer taken from [https://github.com/arnaud-lb/php-rdkafka]()

## Run the application

This application uses Docker and Docker Compose, simply run the following in your terminal to start Kafka and Zookeeper;

```
docker-compose up -d
```

Install Composer dependencies

```
docker-compose run --rm consumer composer install
```

### Start the consumer

```
docker-compose run --rm --entrypoint php producer /var/www/html/run_consumer.php
```

### Broadcast basic messages

In a separate terminal window, run;

```
docker-compose run --rm --entrypoint php producer /var/www/html/run_producer.php
```

## Verify messages are produced and consumed

Monolog has been added as a basic logging system to log which messages have been produced and consumed. Run the following in your terminal;

```
tail data/logs/*.log
```

You should see something similar to the output below (allow time for the producers and consumers to run)

```
$ tail -f data/logs/*.log

==> data/logs/consumer.log <==
[2017-06-23 15:07:51] consumer.DEBUG: Running consumer... [] []

==> data/logs/producer.log <==
[2017-06-23 15:09:50] producer.DEBUG: Running producer... [] []
[2017-06-23 15:09:50] producer.DEBUG: Producing: Message 0 [] []
[2017-06-23 15:09:50] producer.DEBUG: Producing: Message 1 [] []
[2017-06-23 15:09:50] producer.DEBUG: Producing: Message 2 [] []
[2017-06-23 15:09:50] producer.DEBUG: Producing: Message 3 [] []
[2017-06-23 15:09:50] producer.DEBUG: Producing: Message 4 [] []
[2017-06-23 15:09:50] producer.DEBUG: Producing: Message 5 [] []
[2017-06-23 15:09:50] producer.DEBUG: Producing: Message 6 [] []
[2017-06-23 15:09:50] producer.DEBUG: Producing: Message 7 [] []
[2017-06-23 15:09:50] producer.DEBUG: Producing: Message 8 [] []
[2017-06-23 15:09:50] producer.DEBUG: Producing: Message 9 [] []

==> data/logs/consumer.log <==
[2017-06-23 15:09:50] consumer.INFO: Message 0 [] []
[2017-06-23 15:09:50] consumer.INFO: Message 1 [] []
[2017-06-23 15:09:50] consumer.INFO: Message 2 [] []
[2017-06-23 15:09:50] consumer.INFO: Message 3 [] []
[2017-06-23 15:09:50] consumer.INFO: Message 4 [] []
[2017-06-23 15:09:50] consumer.INFO: Message 5 [] []
[2017-06-23 15:09:50] consumer.INFO: Message 6 [] []
[2017-06-23 15:09:50] consumer.INFO: Message 7 [] []
[2017-06-23 15:09:50] consumer.INFO: Message 8 [] []
[2017-06-23 15:09:50] consumer.INFO: Message 9 [] []
[2017-06-23 15:09:50] consumer.DEBUG: No more messages; will wait for more [] []


```