<?php

namespace Crawler;

class OLX_Cars_Core extends Core
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
			"cookie: PHPSESSID=2aff89773d581475d940c4c14dff9960d09fdaf8; mobile_default=desktop; cmpvendors=%5B91%2C69%2C10%2C76%2C16%2C45%2C50%2C511%2C32%2C52%2C25%5D; dfp_segment_test_v3=32; dfp_segment_test=66; dfp_segment_test_v4=45; dfp_segment_test_oa=29; pt=ad7f7e4cb2a335875042ae6cbfe66b2afb8bf3c08c35be51cf8bb6f8f931fad2f5552173443457ffeece27df3253a08eb589b8ed69ad3b803c5eb3808ed12f6e; bm_sz=1ABBB45FC6B2FD941A58FE96916012E6~YAAQH9cSApkAdblpAQAAqQKAzgO6jKqfcruQIxodsk9T6xecXWIoNeJoDUlRBXwDYnImXmy4Z7E6bKCeUBtCQhjxMXfdxSKGIAVw069kttZGaKXq+6eyW7le0Xv2r+AWyalJxFhoXK8QMJkyiyZWiL+trPYrR98BhGYm90u4lWAF7la3RymfjAOfqbg=; fingerprint=MTI1NzY4MzI5MTs4OzA7MDswOzA7MDswOzA7MDswOzE7MTsxOzE7MTsxOzE7MTsxOzE7MTsxOzE7MTswOzE7MTsxOzA7MDsxOzE7MTsxOzE7MTsxOzE7MTsxOzA7MTsxOzA7MTsxOzE7MDswOzA7MDswOzA7MTswOzE7MTswOzA7MDsxOzA7MDsxOzE7MDsxOzE7MTsxOzA7MTswOzMxMzE2MjI4NDc7MjsyOzI7MjsyOzI7Mjs0ODIwMDYyMzc7MzcyNDc2MDA1NTsxOzE7MTsxOzE7MTsxOzE7MTsxOzE7MTsxOzE7MTsxOzE7MDswOzA7MjEzMjAzMTQyNDsxNTEyODI1ODE2OzM1OTE4MTcyOTg7MzMwODM4ODQxOzEwMDUzMDEyMDM7MTkyMDsxMDgwOzI0OzI0OzEyMDs2MDsxMjA7NjA7MTIwOzYwOzEyMDs2MDsxMjA7NjA7MTIwOzYwOzEyMDs2MDsxMjA7NjA7MTIwOzYwOzEyMDs2MDswOzA7MA==; dfp_user_id=728b4ed8-fcaa-1075-c10a-febb9e616f1c-ver2; __gfp_64b=7HjLDNJoqtNt44TskYNa_6kZSWQgxkknL_9fHJDLB63.y7; _abck=CEC2AB580FCC5C41FCDD05EB52E1267B~0~YAAQH9cSAsoAdblpAQAADBeAzgH6VMC0JAXgHVOHlFFlqUmVrMVGPQ9xV7UpSlrzJDD6BmlqaqdNSc3WFBMLa7eyliudypFGZsl/o2fIrZI0BkcAiGyjQLXjdfC2gt+cVeLhM41b+B7YTSOtnjRDEakUN4ppcadg13KppbfPtC/bOuYtxZ+7GBM9w4P4V+dOvlWTv6bO+bYFWzKUp0zn1t3wLYICdNCbFPZjgeVGCd5AfrNF2n1h7XsSAFpiG0oWhDOsOTX/+zLrwbGVrN/4Ycyu88OKUjmpfQGF4nO3kmGEZ90s7A==~-1~-1~-1; ak_bmsc=27DFB6B3B3023581FBA530433E4F8D490212D71FFE4B0000295C9F5C36A0A52F~pl0LMex6vUGhhmsVoQh6+2lcF5/5N1AWiK7HCF9MxJABpp+5n6KV4oRqQ53+VMnRzxZK246e4XBlSgqqg08Fn/Twh1/6VcROYH/nGtGvD/8RxhvWb+3VxgdlFVoLecs/4UFEzLimWMj2XB7TtHkYZGW86NizJilHk72yOJ24nI6xk04FwcJJPfl5Myr8D1wKM6g6oR6AS9HZ7xlxI0jTLBuwDzBMNRKYQzUrOoEeqwNS0GZJcf0vBYN8g7rY12VeoJ; cookieBarSeen=true; consentBarSeen=true; __utma=221885126.1012683019.1553947698.1553947698.1553947698.1; __utmc=221885126; __utmz=221885126.1553947698.1.1.utmcsr=(direct)|utmccn=(direct)|utmcmd=(none); __utmt=1; pvps=1; new_dfp_segment_dfp_user_id_728b4ed8-fcaa-1075-c10a-febb9e616f1c-ver2=%5B%5D; dfp_segment=%5B%5D; lister_lifecycle=1553947699; bm_sv=3921EF6CFD68EF6207067DE7E44BD640~Azi9WYQN3+mAyBhYWs16VuxTOItnoko18gYysREWUWsopIC+MXs9HdgvdSlKBNyF8gk3Zylb1HWbMikAJ+dXbSAxrKjlvlBpvQe0YXSZr+6VUHR32NfgPTXs5gcxkD8lfnoiGoGn1yrx16++yYfUONVS22Bnp2eF90FxKlZRaBI=; used_adblock=adblock_disabled; from_detail=1; ldTd=true; lqstatus=1553948908|||; laquesis=olxeu-25609@a; laquesis_ff=; __gads=ID=9006067a8174f07b:T=1553947706:S=ALNI_MZbc8OpPHNdtv7LFYsqTCIUXRtjzA; _ga=GA1.2.1012683019.1553947698; _gid=GA1.2.1919354769.1553947713; _gat_clientNinja=1; optimizelyEndUserId=oeu1553947714486r0.055300716844679076; G_ENABLED_IDPS=google; _gcl_au=1.1.25504486.1553947718; onap=169ce804129x18c1f7cf-1-169ce804129x18c1f7cf-5-1553949520; __utmb=221885126.4.8.1553947719885",
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

		// parse returned website
		// as dom elements
		$dom = new \DOMDocument('1.0');
        @$dom->loadHTML($response);
		$divs = $dom->getElementsByTagName('script');

		// get item description from div #textContent
		$description = $dom->getElementById('textContent');
		$description = $description->nodeValue;

		// product car instance
		$product = new ProductCar();

		// get the number
		// https://www.olx.pl/ajax/misc/contact/phone/zTkvd/?pt=7ed669d75ecb8209db761f0c48d85ad98e488b51ad279ba43223b2a08da697dcec37ee4ea01931d5d61d1f0ec1dc9ca8413c11bc91ac9f45d4b2385c79dea90e
		/*$shortId = "";
		$phoneToken = "";
		for($i = (strpos($url, 'ID') + 6); $i < strlen($url); $i++)
		{	
			if($url[$i] == '.')
				break;
			else
				$shortId .= $url[$i]; 
		}
		for($i = (strpos($response, 'phoneToken') + 14); $i < strlen($response); $i++)
		{
			if($response[$i] == "'") break;
			else
				$phoneToken .= $response[$i];
		}

		$this->getContent("https://tracking.olx-st.com/h/v2/it-cee?sl=16adc81d582x4af147ce&s=16adc81d582x4af147ce&cl=1&c=31&cou=PL&cisoid=616&cid=170&pid=8&eventName=reply_phone_1step&iid=530358677&imagesCount=6&ad_price=46900&price_currency=PLN&item_condition=NA&adpage_type=standard&sellerType=private&sellerId=24735249&categoryLevel1Id=5&cat_l1_name=motoryzacja&categoryLevel2Id=84&cat_l2_name=samochody&categoryLevel3Id=192&cat_l3_name=kia&category_id=192&provinceId=1&region_name=wielkopolskie&cityId=52239&city_name=pamiatkowo&action_type=reply_phone_1step&platformType=desktop&extra=%7B%22url%22%3A%22%2Foferta%2Fkia-sportage-xl-2-crdi-4x4-salon-aso-gwara%22%2C%22dfp_segment%22%3A%22%5B%5D%22%2C%22last_pv_imps%22%3A%221%22%2C%22user-ad-fq%22%3A%221%22%2C%22ses_pv_seq%22%3A%221%22%2C%22user-ad-dens%22%3A%221%22%7D&event_type=click&user_status=unlogged&traffic_source=direct&dfp_user_id=3ca3213a-7a9e-3f7c-6198-ee1bda561828-ver2&dfp_segment_test_v2=22&dfp_segment_test_v3=22&dfp_segment_test_v4=16&dfp_segment_test_oa=53&search_id=null&used_adblock=adblock_disabled&touch_point_page=ad_page&touch_point_button=top_button&pageName=oferta%2Fkia-sportage-xl-2-crdi-4x4-salon-aso-gwarancja-cid5-idztkvd&host=www.olx.pl&hash=%2344d327b66e%3Bpromoted&ivd=olx-pl_organic&t=1558477814978&source=image");

		$this->overrideHttpHeader = [
			":authority: www.olx.pl",
			":method: GET",
			":scheme: https",
			":path: /ajax/misc/contact/phone/" . $shortId . "/?pt=" . $phoneToken,*/
			//"accept: */*",
			/*"accept-encoding: gzip, deflate, br",
			"accept-language: pl-PL,pl;q=0.9,en-US;q=0.8,en;q=0.7",
			"cookie: mobile_default=desktop; dfp_segment_test_v3=95; dfp_segment_test=99; dfp_segment_test_v4=30; dfp_user_id=3ca3213a-7a9e-3f7c-6198-ee1bda561828-ver2; used_adblock=adblock_disabled; __utmc=221885126; ldTd=true; _ga=GA1.2.2007350975.1546536151; __gfp_64b=9GnHenUgAa2J1NmYYEWtsRBMGLJowwQ6PsaNa02sF7P.Z7; optimizelyEndUserId=oeu1546536151953r0.9460989891905749; __gads=ID=74a67720c12e7c8e:T=1546536153:S=ALNI_MYl4Ngn42x4zLS6i4E1WVa3zy457Q; G_ENABLED_IDPS=google; fbm_121167521293285=base_domain=.olx.pl; lister_lifecycle=1546536206; laquesis_ff=; searchFavTooltip=1; user_id=6824753; cookieBarSeen=true; consentBarSeen=true; didomi_token=eyJ1c2VyX2lkIjoiMTY4NjdhYzktZWIyMi02YTYyLTk1OWMtNzA1ZDg3NDM4Njk0IiwiY3JlYXRlZCI6IjIwMTktMDEtMTlUMTk6NTM6MTIuNjY0WiIsInVwZGF0ZWQiOiIyMDE5LTAxLTE5VDIwOjAxOjMyLjYzNFoiLCJ2ZW5kb3JzIjp7ImVuYWJsZWQiOltdLCJkaXNhYmxlZCI6W119LCJwdXJwb3NlcyI6eyJlbmFibGVkIjpbImNvb2tpZXMiLCJhZHZlcnRpc2luZ19wZXJzb25hbGl6YXRpb24iLCJhZF9kZWxpdmVyeSIsImNvbnRlbnRfcGVyc29uYWxpemF0aW9uIiwiYW5hbHl0aWNzIl0sImRpc2FibGVkIjpbXX19; euconsent=BOaowGWOaoxUeAHABBPLB6-AAAAiyALAAUABAADIAIAAWgAyABoAEUAJgAWwD_g; fingerprint=MTI1NzY4MzI5MTs4OzA7MDswOzA7MDswOzA7MDswOzE7MTsxOzE7MTsxOzE7MTsxOzE7MTsxOzE7MTswOzE7MTsxOzA7MDswOzA7MTswOzA7MTsxOzE7MTsxOzA7MTswOzA7MTsxOzE7MDswOzA7MDswOzA7MTswOzE7MDswOzA7MDswOzA7MDsxOzE7MDsxOzE7MTsxOzA7MTswOzMxMzE2MjI4NDc7MjsyOzI7MjsyOzI7Mjs0ODIwMDYyMzc7MzcyNDc2MDA1NTsxOzE7MTsxOzE7MTsxOzE7MTsxOzE7MTsxOzE7MTsxOzE7MDswOzA7MjEzMjAzMTQyNDsxNTEyODI1ODE2OzM1OTE4MTcyOTg7MzMwODM4ODQxOzEwMDUzMDEyMDM7MTkyMDsxMDgwOzI0OzI0OzEyMDs2MDsxMjA7NjA7MTIwOzYwOzEyMDs2MDsxMjA7NjA7MTIwOzYwOzEyMDs2MDsxMjA7NjA7MTIwOzYwOzEyMDs2MDswOzA7MA==; cmpvendors=%5B91%2C69%2C10%2C76%2C16%2C45%2C50%2C511%2C32%2C52%2C25%5D; __diug=true; dfp_segment_test_oa=35; dfp_seg_test_mweb_v7=77; _gcl_au=1.1.629611488.1554314608; last_locations=4019-0-0-Bydgoszcz-Kujawsko-pomorskie-bydgoszcz_15459-0-0-Siedlce-Mazowieckie-siedlce_14673-0-0-Radom-Mazowieckie-radom; laquesis=disco-105@b#discovery-64@a#olxeu-25609@a; bm_sz=9CCC4D1AFA85446A89822577988BAE8F~YAAQXtYSArhvodtqAQAARWjG2wMuMmD6TMIjHDf4I/miiVCalohrfMJZmwd4C93fQDmMvPN/ZhmvP2mMYA0LXzyZS6xQwwcQEWH4ZjQMvS7c86kiXtXKsL+mLqRM7utwPr4FLXrq3dQPtQ026rl9QTdFr5XOBqGsmrwXQy2Q5jX/RoLgrLlz/tXseoo=; __utmz=221885126.1558465372.74.44.utmcsr=google|utmccn=(organic)|utmcmd=organic|utmctr=(not%20provided); _gid=GA1.2.1102112768.1558465373; PHPSESSID=jc8j23p2qlu76325bpihg45n7o; new_dfp_segment_dfp_user_id_33734cd8-4965-3858-63f9-71e5f470214d-ver2=%5B%22t000%22%2C%22t014%22%2C%22to948%22%2C%22o512%22%2C%22o724%22%2C%22o353%22%2C%22o367%22%2C%22o695%22%2C%22o898%22%5D; dfp_segment=%5B%22t000%22%2C%22t014%22%2C%22to948%22%2C%22o512%22%2C%22o724%22%2C%22o353%22%2C%22o367%22%2C%22o695%22%2C%22o898%22%5D; pt=aceae1f304766876dac463bbb2fef38bbeaf01dfdd1b76cdc25d1e836172f27daa6130e4f186c755ac1809d2bcdea62b89b1ed333551576e238ecc44332c19c2; bm_mi=4A78BCC8C950B38CA7870C39CF879E46~rI3Gh2xnoJYbMJQVXw/1JhA8F5lza4Rb1rby7EXb95zgbW2+80Uf2K9YaivoNRxhbLMesMsEPtkRJEttR+tCTTYPp10GuUaBZUOKHdhPjtBAi5P5xKFNDXzS0CJli7sZPpH9AH8pRfpq8wIdfCOH42ghse30xq5BjIlAO3MKqXVJYeiWS5Sz+Ty8Po3Il9HhJqM4HghHjRdVfT4E6FrX1SqbUr6qXBuUa/8XFvBBUqchg+GELE7d5ofYVsBKniFOxTTd9wQ0CabL9RojiioQz7135S+HSMP7hLK9oh/0p4wt3qh5+QVABXaUs+8HOVqoll0gjZqwu+v0p9ijkdz82UXwzSzwPYOJjADDxhrzPUM=; ak_bmsc=8E2E1BD25A83C1905E7DE41740EAA5BA0212D70ED76200004F69E45CA0AC3E0E~plcsbN8Lv2eoc7Opn65dY/VJ7JAfvV7SH/omXmSNMTx+uONxF6PSN1ecdiikTkOuGhkNzx9wZ0qYCAJ4gwINJvVHnyrbdsnyAzZCP7jmg1mMnOY+pm9+qmA1xl9vxpyBsunXYhxEE5X5siwB3cx4jtRMO0/Lpagrhm1kKkG5qGk2Tz8BleMAnFFPuu0Yo17jNCjFgxpQElckK7aSKbMRgciUHxrND5sZKCaxwnHRLdffoF2Ai7H0VqdOHz2dTVBvH3; lqstatus=1558476677|||; _abck=06764AF98018B4A4715A7CA824DB56FF~0~YAAQxtcSAqw888xqAQAAm6Rw3AFej1sr2iOiwYC7lqkcyZRZnptK766LXKRlUu+YlgXejnqEbJLIO1HHgA02chJHAbwzQK+yPaiLmxD7C5f22j97bTVhuXYnKvrXxYMXfVFIEbj8EO1CSyIJas1lKJUKG7auZQ0zuwKvQpdxMPj+cGQf6HGu7s0J2NvUrcOaon8xV1enS9jOIyDSNAfddgz8C1xb9LyQWXymN7MMC+lSTQdVovH3aB9D0glK1IsJ5WispE/mgvnp9fR7RcFYCSNf2b8wE34eB3HeP1kXliGl9EArqA==~-1~-1~-1; _gat_clientNinja=1; pvps=1; from_detail=1; __utma=221885126.2007350975.1546536151.1558465372.1558476529.77; __utmt=1; bm_sv=3B344C20D07B8658BF32176C54FFAAF0~dHtR6Bi3vE1snkWJPtbSgvApyuGfKtId64h/z72DUJKYpX49KtaVlvwXjaU7KWQB6L8nVk7qCISvPXB6i/1H42+LDbQ7iUGISClUaMqbkyxwU3ozAzjgXA2/oLLU9U+JSQ6yqAZGSGd3HPdZ+98DC9Af32xDq5kEpV1BUENNz78=; avppv=1; avps=1; fbsr_121167521293285=xAPDJUcTcZ1tKvDOuU0VPmsk0kS3EhBpn0BrTFRhQ1c.eyJjb2RlIjoiQVFBR2NYalJGMnBqeWltWWR5eWFleHZHZjFhV016SzctdzViR3hMUk9NTnZ1YTlKcGw3Ym8yNGQtaFUycEZwR1hqMHQ2dW5EV05nUENLMVdxV1RTWDh1UENmUTBoZlNoRzBTWU8wYjNTcG5UTnVNTlpEZlpBQlN6c21sdERZYVBDeUhOVE5FeFB2NkNiVGVoZzhLLVN1SmtPRnNHZGdRbDBqSXdQYnlnWlZmTkxtazBfTGtONjV1ZlhzVXEwNXhodEpSbmVtNDRYR3hPOVVZOWdGNDJsMVVXR01rdm1nclc4R205bWl2LWhUUXYzSWF0S2pyT2xVcURESWU4dGZXOGNkaFNXYk1BSEZTV0FhQkNKc1k1Yk9OanJIb0xYVlRWcllSVWJwdW1tbGthOHozMV9XQnBQdnNXSnFYQmhObTdyOElyclY5aktmUksxX1lDOEF3LTNWelUiLCJ1c2VyX2lkIjoiMTIxNzg0MTEwMTYzMjAzNyIsImFsZ29yaXRobSI6IkhNQUMtU0hBMjU2IiwiaXNzdWVkX2F0IjoxNTU4NDc2NTM5fQ; onap=16814bce831x6309309-73-16adc70989ax3431278d-6-1558478338; __utmb=221885126.3.8.1558476538319",
			"referer: https://www.olx.pl/oferta/kia-sportage-xl-2-crdi-4x4-salon-aso-gwarancja-CID5-IDzTkvd.html",
			"user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/72.0.3626.82 Safari/537.36 Vivaldi/2.3.1440.41",
			"x-requested-with: XMLHttpRequest"
		];

		var_dump($this->overrideHttpHeader);

		sleep(2);

		$phoneEndpointUrl = "https://www.olx.pl/ajax/misc/contact/phone/" . $shortId . "/?pt=" . $phoneToken;
		list($responsePhone, $timePhone, $codePhone) = $this->getContent($phoneEndpointUrl);
		var_dump($responsePhone);*/

		$finder = new \DomXPath($dom);
		$details = $finder->query("//*[contains(@class, 'details')]");
		foreach($details as $d) {
			if($d->tagName === "table") {
				$parts = preg_split("/[\t]/", $d->nodeValue);
				$sorted = [];

				// clean parts from empty spaces
				foreach($parts as $key => $value) {
					if(strlen($value) == 0 || strlen($value) == 1) {
						unset($parts[$key]);
					} else {
						$sorted[] = $value;
					}
				}

				foreach($sorted as $key => $value)
				{
					ProductCar::parseDetailsTable($sorted, $key, $product);
					//var_dump($key . ' ' . $value);
				}
			}
		}

		$img_array = [];
		$images = $finder->query("//*[contains(@class, 'bigImage')]");
		foreach($images as $i) {
			if(strlen(urldecode($i->getAttribute('src'))) > 0) {
				$img_array[] = urldecode($i->getAttribute('src'));
			}
		}

        foreach($divs as $div) {
        	if(strpos($div->nodeValue, "trackingData") !== FALSE) {
				$json = json_decode($this->getBetween($div->nodeValue, "parse('", "');"));
				$pageView = (array)$json->pageView;

				if(isset($pageView['ad_id'])) {
					if($pageView['cat_l2_name'] === "samochody") {
						$product->id = $pageView['ad_id'];
						$product->price = $pageView['ad_price'];
						//$product->url = $pageView['url'];
						$product->url = $url;
						$product->region = $pageView['region_name'];
						$product->city = $pageView['city_name'];
						$product->description = $description;
						$product->images = $img_array;

						var_dump($product);
						$product->save($this->db);
					}
				}
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