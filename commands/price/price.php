<?php
return function ($client) {
	// Extending is required
	return (new class($client) extends \CharlotteDunois\Livia\Commands\Command {
		function __construct(\CharlotteDunois\Livia\LiviaClient $client) {
			parent::__construct($client, array(
				'name' => 'price',
				'aliases' => array(),
				'group' => 'price',
				'description' => '暗号通貨の価格を表示します。',
				'guildOnly' => false,
				'throttling' => array(
					'usages' => 2,
					'duration' => 3
				),
				'args' => array(
					array(
						'key' => 'name',
						'prompt' => '何を知りたい？',
						'type' => 'string'
					)
				)
			));
		}
		
		
		// Even if you don't use all arguments, you are forced to match that method signature.
		function run(\CharlotteDunois\Livia\CommandMessage $message, \ArrayObject $args, bool $fromPattern) {
			switch ($args->name) {
				case 'btc':
					$embeds = get_btc();
					$embed = new \CharlotteDunois\Yasmin\Models\MessageEmbed();
					$embed->setColor(0x61FF00);
					$embed->setTitle("BTC価格");
					$embed->setTimestamp(time());

					$message->say("",array('embed' => $embed));
					foreach ($embeds as $embed) {
						$message->say("",array('embed' => $embed));
					}
				break;
				
				case 'xem':
					$embeds = get_xem();
					$embed = new \CharlotteDunois\Yasmin\Models\MessageEmbed();
					$embed->setColor(0x6100FF);
					$embed->setTitle("XEM価格");
					$embed->setTimestamp(time());

					$message->say("",array('embed' => $embed));
					foreach ($embeds as $embed) {
						$message->say("",array('embed' => $embed));
					}
				break;

				default:
				return $message->say("not found ".$args->name);
				break;
			}
			
		}
	});
};