<?php
return function ($client) {
    // Extending is required
    return (new class($client) extends \CharlotteDunois\Livia\Commands\Command {
        function __construct(\CharlotteDunois\Livia\LiviaClient $client) {
            parent::__construct($client, array(
                'name' => 'ban',
                'aliases' => array(),
                'group' => 'moderation',
                'description' => 'Bans an user.',
                'guildOnly' => true,
                'throttling' => array( // Throttling is per-user
                    'usages' => 2,
                    'duration' => 3
                ),
                'args' => array(
                    array(
                        'key' => 'user',
                        'prompt' => 'Which user do you wanna ban?',
                        'type' => 'member'
                    )
                )
            ));
        }
        
        // Checks if the command is allowed to run - the default method from Command class also checks userPermissions.
        // Even if you don't use all arguments, you are forced to match that method signature.
        function hasPermission($message, bool $ownerOverride = true) {
            return $message->member->roles->has('SERVER_STAFF_ROLE_ID');
        }
        
        // Even if you don't use all arguments, you are forced to match that method signature.
        function run(\CharlotteDunois\Livia\CommandMessage $message, \ArrayObject $args,
                      bool $fromPattern) {
            // Do what the command has to do.
            // You are free to return a Promise, or do all-synchronous tasks synchronously.
            
            // If you send any messages (doesn't matter how many),
            // return (resolve) the Message instance, or an array of Message instances.
            // Promises are getting automatically resolved.
            
            return $args->user->ban()->then(function () use ($message) {
                return $message->reply('The user got banned!');
            });
        }
    });
};
