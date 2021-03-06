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
			//"cookie: mobile_default=desktop; dfp_segment_test_v3=79; dfp_segment_test=96; dfp_segment_test_v4=69; dfp_segment_test_oa=26; fingerprint=MTI1NzY4MzI5MTs4OzA7MDswOzA7MDswOzA7MDswOzE7MTsxOzE7MTsxOzE7MTsxOzE7MTsxOzE7MTswOzE7MTsxOzA7MDsxOzE7MTsxOzE7MTsxOzE7MTsxOzA7MTsxOzA7MTsxOzE7MDswOzA7MDswOzA7MTswOzE7MTswOzA7MDsxOzA7MDsxOzE7MDsxOzE7MTsxOzA7MTswOzMxMzE2MjI4NDc7MjsyOzI7MjsyOzI7Mjs2NTE3MDQxODg7MzU3NzU2MTYzNDsxOzE7MTsxOzE7MTsxOzE7MTsxOzE7MTsxOzE7MTsxOzE7MDswOzA7NDEwMDIxOTk7MTUxMjgyNTgxNjsxODg5NzU5NjAzOzMzMDgzODg0MTsxMDA1MzAxMjAzOzE5MjA7MTA4MDsyNDsyNDsxMjA7NjA7MTIwOzYwOzEyMDs2MDsxMjA7NjA7MTIwOzYwOzEyMDs2MDsxMjA7NjA7MTIwOzYwOzEyMDs2MDsxMjA7NjA7MDswOzA=; dfp_user_id=1d7eb7e1-a5cd-958c-1935-1904d7b7acbb-ver2; random_segment_js=86; used_adblock=adblock_disabled; __gfp_64b=-TURNEDOFF; ldTd=true; G_ENABLED_IDPS=google; lister_lifecycle=1574155010; cmpvendors=3; searchFavTooltip=1; user_id=6824753; observed5_view=list; _abck=6A944742DFA51D9C7E9CF47874E792E5~0~YAAQbtYSAv0TiKJuAQAAiwvtogKo6+d3QppryN+OCsCKWt2Vzb5ubNwy5ixBPWJT5vgNuPrmzZKBPAPyGs9Sgo5aV709AyiKqNJvnM3XvRPRaG//z3q5G5AEnrb4uCghsWyF7csRln8nDLGLTTockr/1uHEhFvxR5Z3WGhuEKE+PgSJ8L2p+1hjMwQZdlWHslniM05OuU4clORwEBqEvZLeMEr0D9+L/iAAqAW46J9dYTDjvyNtB+sDlj3QmX9+uzdrk726yimpIIsiU9no9OvvC50p+feWPoM8flvq3wnAhyICzCLRdFlGXmayMt8oCOLRU~-1~-1~-1; last_locations=41415-0-0-Zdu%C5%84ska+Wola-zdu%C5%84skowolski-zdunska%3Awola_4019-0-0-Bydgoszcz-Kujawsko-pomorskie-bydgoszcz_17-0-1-Podkarpackie--podkarpackie; my_city_2=41415_0_0_Zdu%C5%84ska+Wola_0_zdu%C5%84skowolski_zdunska%3Awola; laquesis=disco-566@a#olxeu-29551@b#olxeu-29990@c#olxeu-30294@a#olxeu-30387@c#olxeu-30466@b; laquesisff=a2b-000#olxeu-0000#olxeu-29763; bm_sz=337DDAD7FDD5BB9D267262638D19303C~YAAQhNYSAm1vm6NvAQAA5Lu2pAa+2R9zs8w6J8OOZS/K9BiNOUC3Z1L4fbVEHj38ZeiOkeY4FCfovjHV4RjOVjU0odSYlYXb1C0uZw4naMhwxkFzl3xm0E/i5BNKVy7B6wZlVA+a71e70NJ9WNJrxXWx2mgIX6oFz7Co8lCIGwxvwCKS6WspexDGQTY=; PHPSESSID=98q0gvqdg50vmqecel74fg1msd; new_dfp_segment_dfp_user_id_1d7eb7e1-a5cd-958c-1935-1904d7b7acbb-ver2=%5B%5D; dfp_segment=%5B%5D; pt=12096fceacc18040d963567e8cbed2b3076b2cb7ec61464ea83e98890f8778611b558398cf4fbcfbe39dbb2c13a26b5e369fd8597bf661d5b0feac69e67b0f31; ak_bmsc=E4E9536B3242A85790BCF9BB1836D3D06873535D8A5C000037FF1D5E309E9B7B~pln4mi8gtsRV6Th372AiF3oScbmWo++ny1AiSVLqmr1JJhYr6TyTmH27aIC2KAcVSA7v5rj6xdK/7iTpBuzU5buTK7qJ0+6/m62XkDXDEN7uTWsLdA8wzVIsQx6kBgzaARUNmZDmHH0DkvdFjR7gv9e74XQSBXc7ESeuaI5a2O817BgR9yjC4oU4z77wZ/BAq12orIrMNrR95hhkVjlwi7oWkCjM89zHZGkOwzZKQ8E7M=; pvps=1; lqstatus=1579025383; bm_mi=9DD74E7DF726EA825804ECBACCCDDCCB~4bXz/cko6zgmOI3scZNdsh1+1s8n7DeNVx7crFL9poZnfEfSDmi7kvERFobXYDxWj6fNIMwZo0ItHuDcWL6mbsYTJJERTsNWmd00f3gDJSnVwZRMy+jaZcktKwprMkAUjePg9JBkyMVhLggb1kRN+dKlHevzb/5yiMiXZYUCMbalnNZBjFEJXZhPGJTF+LtbEr+xwrFZ3HZ8lVlx5WGyEoB2agb8hp8Y+rlxHl/FUfk=; onap=16e82d108b9x61ebacd3-100-16fa52ceee5xeb868ee-5-1579026045; from_detail=1; didomi_token=eyJ1c2VyX2lkIjoiMTZmYTUyZGUtMGI1MS02YjAwLTkxMzItYWY0ODYzZDdlYTY0IiwiY3JlYXRlZCI6IjIwMjAtMDEtMTRUMTc6NTA6NDUuOTc0WiIsInVwZGF0ZWQiOiIyMDIwLTAxLTE0VDE3OjUwOjQ1Ljk3NFoiLCJ2ZW5kb3JzIjp7ImVuYWJsZWQiOltdLCJkaXNhYmxlZCI6W119LCJwdXJwb3NlcyI6eyJlbmFibGVkIjpbXSwiZGlzYWJsZWQiOltdfX0=; bm_sv=AE69E62FC5BF3A99D77C1708ABADBEE7~gAcVqA5crfMvMEVC9JsSQvgFdg3KlmzFcnwxZ6PUV3b88LUOKWAhf0qqYf8SCstkHyIheMrcQdeBUEg+l9ew+HOaFp7Vb97Oy3kIY46/Kzv4YADkJvKWLlc8VCcy7yDJrGA7yegzB1TVm7FqVuEsk6wWSZkqDi7UDVS7aE3huQU=",
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
			CURLOPT_FOLLOWLOCATION => 0,
			CURLOPT_COOKIEFILE => 'cookie.txt'
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
		//die();

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