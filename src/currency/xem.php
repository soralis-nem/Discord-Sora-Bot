<?php
function get_xem()
{
	$urls = [];
	$urls['ZA'] = 'https://api.zaif.jp/api/1/ticker/xem_jpy'; // Zaif
	$urls['ZAB'] = 'https://api.zaif.jp/api/1/ticker/xem_btc'; // Zaif
	$urls['PX'] = 'https://poloniex.com/public?command=returnTicker'; //Poloniex
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
			case 'ZA': // Zaif
			$embeds[$name]->setTitle("Zaif");
			$embeds[$name]->setDescription("XEM/JPY");
			$embeds[$name]->addField("last",number_format($res->last,2),true);
			$embeds[$name]->addField("ask",number_format($res->ask,2),true);
			$embeds[$name]->addField("bid",number_format($res->bid,2),true);
			$embeds[$name]->addField("volume",number_format($res->volume),true);
			break;
			case 'ZAB': // Zaif
			$embeds[$name]->setTitle("Zaif");
			$embeds[$name]->setDescription("XEM/BTC");
			$embeds[$name]->addField("last",number_format($res->last,8),true);
			$embeds[$name]->addField("ask",number_format($res->ask,8),true);
			$embeds[$name]->addField("bid",number_format($res->bid,8),true);
			$embeds[$name]->addField("volume",number_format($res->volume),true);
			break;
			case 'PX': // Poloniex
			$res = $res->BTC_XEM;
			$embeds[$name]->setTitle("Poloniex(BTC)");
			$embeds[$name]->setDescription("XEM/BTC");
			$embeds[$name]->addField("last",number_format($res->last,8),true);
			$embeds[$name]->addField("ask",number_format($res->lowestAsk,8),true);
			$embeds[$name]->addField("bid",number_format($res->highestBid,8),true);
			$embeds[$name]->addField("volume",number_format($res->baseVolume/$res->last),true);
			break;
		}
	}
	return $embeds;
}