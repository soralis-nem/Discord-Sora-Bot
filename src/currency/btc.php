<?php
function get_btc(){
	$USD_JPY = 0;
	get_rate($USD_JPY);
	$urls = [];
	$urls['BFFX'] = 'https://api.bitflyer.jp/v1/ticker?product_code=FX_BTC_JPY'; // bitFlyerFX
	$urls['BF'] = 'https://api.bitflyer.jp/v1/ticker?product_code=BTC_JPY'; // bitFlyer
	$urls['ZAIF'] = 'https://api.zaif.jp/api/1/ticker/btc_jpy'; //zaif
	$urls['CC'] = 'https://coincheck.com/api/ticker'; // CoinCheck
	$urls['BFIN'] = 'https://api.bitfinex.com/v1/pubticker/btcusd';
	$mh = curl_multi_init();
	$chs = [];
	foreach ($urls as $name => $url) {
		$ch = curl_init();
		curl_setopt_array($ch, [CURLOPT_URL => $url, CURLOPT_RETURNTRANSFER => true, CURLOPT_TIMEOUT => 5, CURLOPT_CONNECTTIMEOUT => 5, ]);
		curl_multi_add_handle($mh, $ch);
		$chs[$name] = $ch;
	}
	do {
		curl_multi_exec($mh, $start);
	} while ($start);
	$last = [];
	$ask = [];
	$bid = [];
	$vol = [];
	$embeds=[];
	foreach ($chs as $name => $ch) {
		$res = json_decode(curl_multi_getcontent($ch));
		$embeds[$name] = new \CharlotteDunois\Yasmin\Models\MessageEmbed();
		$embeds[$name]->setColor(rand(0x000000, 0xFFFFFF));
		
		switch ($name) {
			case 'BFFX': // bitFlyerFX
				$embeds[$name]->setTitle("bitFlyerFX");
				$embeds[$name]->addField("last",number_format($res->ltp,1),true);
				$embeds[$name]->addField("ask",number_format($res->best_ask,1),true);
				$embeds[$name]->addField("bid",number_format($res->best_bid,1),true);
				$embeds[$name]->addField("volume",number_format($res->volume_by_product),true);
			break;
			case 'BF': // bitFlyer
				$embeds[$name]->setTitle("bitFlyer");
				$embeds[$name]->addField("last",number_format($res->ltp),true);
				$embeds[$name]->addField("ask",number_format($res->best_ask),true);
				$embeds[$name]->addField("bid",number_format($res->best_bid),true);
				$embeds[$name]->addField("volume",number_format($res->volume_by_product),true);
			break;
			case 'ZAIF': //zaif
				$embeds[$name]->setTitle("Zaif");
				$embeds[$name]->addField("last",number_format($res->last),true);
				$embeds[$name]->addField("ask",number_format($res->ask),true);
				$embeds[$name]->addField("bid",number_format($res->bid),true);
				$embeds[$name]->addField("volume",number_format($res->volume),true);
			break;
			case 'CC': // CoinCheck
				$embeds[$name]->setTitle("CoinCheck");
				$embeds[$name]->addField("last",number_format($res->last),true);
				$embeds[$name]->addField("ask",number_format($res->ask),true);
				$embeds[$name]->addField("bid",number_format($res->bid),true);
				$embeds[$name]->addField("volume",number_format($res->volume),true);
			break;
			case 'BFIN': // Bitninex
				$embeds[$name]->setTitle("BitFInex");
				$embeds[$name]->addField("last",number_format($res->last_price),true);
				$embeds[$name]->addField("ask",number_format($res->ask),true);
				$embeds[$name]->addField("bid",number_format($res->bid),true);
				$embeds[$name]->addField("volume",number_format($res->volume),true);
			break;
		}
	}

	return  $embeds;
}