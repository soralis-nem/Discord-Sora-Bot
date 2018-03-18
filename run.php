<?php
setlocale(LC_NUMERIC, 'ja_JP.utf8');

include __DIR__ . '/vendor/autoload.php';
$config = json_decode(file_get_contents(__DIR__ . '/config.json'));

$loop = \React\EventLoop\Factory::create();
$client = new \CharlotteDunois\Livia\LiviaClient(array(
    'owners' => array('YOUR_USER_ID'),
    'unknownCommandResponse' => false
), $loop);

// Registers default commands, command groups and argument types
$client->registry->registerDefaults();


$client->registry->registerGroup(
    array('id' => 'moderation', 'name' => 'Moderation')
);

// Register our commands (this is an example path)
$client->registry->registerCommandsIn(__DIR__.'/commands/');

// If you have created a command, like the example above, you now have registered the command.

$client->on('ready', function () use ($client) {
    echo 'Logged in as '.$client->user->tag.' created on '.
           $client->user->createdAt->format('d.m.Y H:i:s').PHP_EOL;
});

$client->login($config->token);
$loop->run();
