<?php

namespace Crawler;

class CoreCookies extends Core
{
    protected $overrideHttpHeader = NULL;

    /**
     * Make request
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
			"referer: https://www.olx.pl/motoryzacja/samochody/",
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

			/** nowe */
			CURLOPT_HEADER => 1,
			CURLOPT_COOKIEJAR => 'cookie.txt'
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
     * What to do with request ?
     */
	public function check($url) {
		list($response, $time, $code) = $this->getContent($url);
	}
}