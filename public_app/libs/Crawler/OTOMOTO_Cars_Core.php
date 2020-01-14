<?php

namespace Crawler;

class OTOMOTO_Cars_Core extends Core
{
	protected $overrideHttpHeader = NULL;

	/**
	 *	@Override method
	 */
	public function getContent($url) {
		$curl = curl_init();

		$headers = [
			":authority: www.olx.pl",
			":method: GET",
			":scheme: https",
			"accept: */*",
			"accept-language: pl-PL,pl;q=0.9,en-US;q=0.8,en;q=0.7",
			"cookie: mobile_default=desktop; dfp_segment_test_v3=79; dfp_segment_test=96; dfp_segment_test_v4=69; dfp_segment_test_oa=26; fingerprint=MTI1NzY4MzI5MTs4OzA7MDswOzA7MDswOzA7MDswOzE7MTsxOzE7MTsxOzE7MTsxOzE7MTsxOzE7MTswOzE7MTsxOzA7MDsxOzE7MTsxOzE7MTsxOzE7MTsxOzA7MTsxOzA7MTsxOzE7MDswOzA7MDswOzA7MTswOzE7MTswOzA7MDsxOzA7MDsxOzE7MDsxOzE7MTsxOzA7MTswOzMxMzE2MjI4NDc7MjsyOzI7MjsyOzI7Mjs2NTE3MDQxODg7MzU3NzU2MTYzNDsxOzE7MTsxOzE7MTsxOzE7MTsxOzE7MTsxOzE7MTsxOzE7MDswOzA7NDEwMDIxOTk7MTUxMjgyNTgxNjsxODg5NzU5NjAzOzMzMDgzODg0MTsxMDA1MzAxMjAzOzE5MjA7MTA4MDsyNDsyNDsxMjA7NjA7MTIwOzYwOzEyMDs2MDsxMjA7NjA7MTIwOzYwOzEyMDs2MDsxMjA7NjA7MTIwOzYwOzEyMDs2MDsxMjA7NjA7MDswOzA=; dfp_user_id=1d7eb7e1-a5cd-958c-1935-1904d7b7acbb-ver2; random_segment_js=86; used_adblock=adblock_disabled; __gfp_64b=-TURNEDOFF; ldTd=true; G_ENABLED_IDPS=google; lister_lifecycle=1574155010; cmpvendors=3; searchFavTooltip=1; pt=20d1e00f9a497ed1a02784f8baa6c1cdd8751ece67b2f43fcbacdfa3733965e7b38f057db0569f336858307afd24df2d2b8319f497e8c4154a0cf1fc45473fc1; PHPSESSID=gnqqiasedkhe5ro8rqhrraq0el; user_id=6824753; observed5_view=list; layerappsSeen=1; last_locations=7509-0-0-Kalisz-Wielkopolskie-kalisz_24893-0-0-Brzoza-bydgoski-brzoza%3B24893_91689-0-0-Nowe+Smolno-bydgoski-nowe%3Asmolno; _abck=6A944742DFA51D9C7E9CF47874E792E5~0~YAAQbtYSAv0TiKJuAQAAiwvtogKo6+d3QppryN+OCsCKWt2Vzb5ubNwy5ixBPWJT5vgNuPrmzZKBPAPyGs9Sgo5aV709AyiKqNJvnM3XvRPRaG//z3q5G5AEnrb4uCghsWyF7csRln8nDLGLTTockr/1uHEhFvxR5Z3WGhuEKE+PgSJ8L2p+1hjMwQZdlWHslniM05OuU4clORwEBqEvZLeMEr0D9+L/iAAqAW46J9dYTDjvyNtB+sDlj3QmX9+uzdrk726yimpIIsiU9no9OvvC50p+feWPoM8flvq3wnAhyICzCLRdFlGXmayMt8oCOLRU~-1~-1~-1; disabledgeo=1; laquesis=olxeu-28435@c#olxeu-29551@b#olxeu-29990@c#olxeu-30294@b#olxeu-30387@a#train-2@b; cookieBarSeen=true; consentBarSeen=true; laquesisff=a2b-000#olxeu-0000#olxeu-29763; from_detail=0; new_dfp_segment_user_id_6824753=%5B%22t000%22%2C%22t014%22%2C%22a626%22%2C%22a390%22%5D; dfp_segment=%5B%22t000%22%2C%22t014%22%2C%22a626%22%2C%22a390%22%5D; ak_bmsc=07CAF08042534109F5655FF00405C1EA0212D62EDA71000094AEE35D4280DF3B~plt0ZWPBkronhFRJ+CqFSM0XTDIktBktd/NZQ3QRD+MMvzcG3tHsMF4IU4H3Wp+aucHmJdVAu06D1R3tKPwvNQEbO1MOd44K65GPPJp/L04yH+g1Egik9LSlWybf42vLBabZ2xnxEWk0RxYXDbl0iHXVa9ft9IdSgQgoXwkk8/gyO7WRHlFXBgcuUIcse3KULIUwfzQf1Kv+5L057vFySFX04l1TvToUbfVCvj+XXOBX8=; bm_sz=AC3027BC3881EC93859BC289460F1432~YAAQLtYSAvbBgXJuAQAAJvNhwQUKLKLMA90uE/Y6aOoOszxa2+RxSvDdLSf+seAv4wFhaoSRTZqBp5pUg6Oc44ue+YqtfvE9ffP5UT4wplLYm19K4zmoLVoVo4JtJmjbo6S4DwU7YSXP36xIjYuBgVBI8qJWyW0SIUslPSVdfDtdSq4eTkGKx7g++ME=; pvps=1; lqstatus=1575203653; bm_mi=D2C4834C36EAB7E93B078603F911E036~iV+hRqDEpWg1A0P3DyVuIsjpmnvRcIKWnAAy9DQ3vjWlL9Y3tVxPrHy84QGCsia1ykpZzkmkF2T6w8C4t5bmNvEQ8bGSX8OK4yOxRSheVEVsn6VZ5lQJf4j/WN/CO7ukb6rLyL9P+MTRtn7nwOYJHx9YCHVfsVpTNIyGqng1rGpYknc9QxDCw7JbuCuzDFPZQ84Ri1FKKCWTGh7GGJR+dbZAGrqrNboRomHhLg+Uw4V61nDFURltUjJYj9N0qdoE; search_id_md5=601025596269fba039ab37da8e35d00a; bm_sv=25E0183E47F9F477FF12BE3D085FD2BF~iHEcvgTNjrIYdzAF02saBrbLxxwlHzyg1DuSwMyM3MEGizLQDf3+ejEjD0k0qVSOwnsIDP4mKIdcc4+1wSyIpOt42Dydc4YnliHF9Ap2AWJ7j2KjF8cjgUlw7cACAzl5gjPcn0KLoKzKw+p0OLIntkU9o2391EvTxdD9BR/0iok=; onap=16e82d108b9x61ebacd3-25-16ec161f628x4ac63bf0-9-1575204275",
			"referer: https://www.olx.pl/oferta/citroen-c4-na-bogato-CID5-IDxObFt.html",
			"user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/72.0.3626.82 Safari/537.36 Vivaldi/2.3.1440.41",
		];

		if($this->overrideHttpHeader !== NULL)
			$headers = $this->overrideHttpHeader;

		curl_setopt_array($curl, [
			CURLOPT_RETURNTRANSFER => 1,
		    CURLOPT_URL => $url,
		    CURLOPT_USERAGENT => $this->userAgent,
		    CURLINFO_HEADER_OUT => true,
		    CURLOPT_HTTPHEADER => $headers,
		    CURLOPT_FOLLOWLOCATION => 0
		]);

		 /* Get the HTML or whatever is linked in $url. */
        $response = curl_exec($curl);

        // response total time
        $time = curl_getinfo($curl, CURLINFO_TOTAL_TIME);

        /* Check for 404 (file not found). */
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);
		return [$response, $time, $httpCode];
	}

	/**
	 *	@Override method from \Core
	 */
	public function crawl($url, $depth)
	{
		// temporary
		if (microtime(true) - $this->limitStart >= $this->limitSeconds) {
		    return false;
		}

		$this->visited[$url] = time();
		list($response, $time, $code) = $this->getContent($url);

        //var_dump($response);
        //echo $response;
		//die();

		// parse returned website
		// as dom elements
		$dom = new \DOMDocument('1.0');
        @$dom->loadHTML($response);
        $divs = $dom->getElementsByTagName('script');
        $finder = new \DomXPath($dom);

        // get item description from div #textContent
        $offer_description = $finder->query("//*[contains(@class, 'offer-description__description')]");
        $description = $offer_description[0]->nodeValue;

		// product car instance
        $product = new ProductCar();
        $details = $finder->query("//*[contains(@class, 'offer-params__item')]");
        $labels = $finder->query("//*[contains(@class, 'offer-params__label')]");
        $values = $finder->query("//*[contains(@class, 'offer-params__value')]");

        $sorted = [];
        for($i = 0; $i < $details->length; $i++) {
            try {
                $__label = $labels[$i]->nodeValue;
                $__value = trim(preg_replace('/\t+/', '',  $values[$i]->nodeValue));
                ProductCar::parseDetailsOtomoto($product, $__label, $__value);
            } catch (Exception $e) { }
        }

		$img_array = [];
        $images = $finder->query("//*[contains(@class, 'bigImage')]");
		foreach($images as $i) {
			if(strlen(urldecode($i->getAttribute('data-lazy'))) > 0) {
				$img_array[] = urldecode($i->getAttribute('data-lazy'));
			}
        }

        foreach($divs as $div) {
            $__data = $this->getBetween($div->nodeValue, "window.ninjaPV = ", ";");
            if(strlen($__data) > 0) {
                $json = (array) json_decode($__data);
                $product->id = $json['ad_id'];
                $product->price = $json['ad_price'];
                $product->url = $url;
                $product->region = $json['region_name'];
                $product->city = $json['city_name'];
                $product->description = $description;
                $product->images = $img_array;
                $product->saveOtomoto($this->db);
                //var_dump($product);
                //die();
                break;
            }
        }
	}

	/**
	 * Method for cut string between two other strings
	 */
	protected function getBetween($string, $start = "", $end = ""){
		if (strpos($string, $start)) { // required if $start not exist in $string
			$startCharCount = strpos($string, $start) + strlen($start);
			$firstSubStr = substr($string, $startCharCount, strlen($string));
			$endCharCount = strpos($firstSubStr, $end);
			if ($endCharCount == 0) {
				$endCharCount = strlen($firstSubStr);
			}
			return substr($firstSubStr, 0, $endCharCount);
		} else {
			return '';
		}
	}
}