<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	private $brands = [
        "alfa-romeo", "audi", "bmw", "cadillac", "chevrolet", "chrysler", "citroen", "dacia", "daewoo", "renault",
        "fiat", "ford", "honda", "hyundai", "jeep", "kia", "lexus", "mazda", "mercedes-benz", "mitsubishi", "nissan", "opel", "peugeot", "volkswagen", "toyota"
    ];

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		//$query = $this->db->query("SELECT * FROM links");
		//$this->load->view('welcome_message');

		//var_dump(filter_var('vnestea@wp.pl', FILTER_VALIDATE_EMAIL));

		date_default_timezone_set('Europe/Warsaw');

		$olx = $this->db->query("SELECT * FROM cars");
		$otomoto = $this->db->query("SELECT * FROM cars_otomoto");

		$links_olx = $this->db->query("SELECT * FROM vehicles WHERE date IS NULL");
		$links_otomoto = $this->db->query("SELECT * FROM vehicles_otomoto WHERE date IS NULL");

		$last_olx_car = $this->db->query("SELECT * FROM cached WHERE name = ? LIMIT 1", ['LAST_OLX_CAR']);
		$last_otomoto_car = $this->db->query("SELECT * FROM cached WHERE name = ? LIMIT 1", ['LAST_OTOMOTO_CAR']);

		$now_olx = $this->db->query("SELECT * FROM cached WHERE name = ? LIMIT 1", ['OLX_NOW']);
		$now_otomoto = $this->db->query("SELECT * FROM cached WHERE name = ? LIMIT 1", ['OTOMOTO_NOW']);

		$data["olx_count"] = $olx->num_rows();
		$data["om_count"] = $otomoto->num_rows();
		$data["lolx_count"] = $links_olx->num_rows();
		$data["lom_count"] = $links_otomoto->num_rows();

		$data["now_olx"] = $now_olx->row()->value;
		$data["now_otomoto"] = $now_otomoto->row()->value;

		$data["brands"] = $this->brands;

		$data["last_olx"] = $last_olx_car->row();
		$data["last_otomoto"] = $last_otomoto_car->row();
		$this->load->view('welcome_message', $data);
	}

	public function test2()
	{
		/*
			Co odświeżenie strony zmienia się phoneToken <- trzeba to mieć na uwadze dla danego IP, wielowątkowość tak na prawdę odpada
			$id bierzemy z DOM parenta guzika pobierania numeru na stronie

			phoneToken generuje sie dla konkretnej oferty
			czyli jednak wielowątkowość wchodzi w grę

			$pt bierze sie ze strony ze zmiennej phoneToken var
		*/

		//Crawler\Core::foo();

		$id = "z8jTZ";
		$pt = "bce9c5a3c7e244ee8a28158b0d14365ee75baa459653d25b721feabcafb092c3467f18c8264d52b690829d5271bdaf271d77c6014bb101c3b46c954c6bd50a95";


		// Get cURL resource
		$curl = curl_init();
		// Set some options - we are passing in a useragent too here
		curl_setopt_array($curl, [
		    CURLOPT_RETURNTRANSFER => 1,
		    CURLOPT_URL => 'https://www.olx.pl/ajax/misc/contact/phone/'. $id .'/?pt=' . $pt,
		    CURLOPT_USERAGENT => 'Codular Sample cURL Request',
		    CURLOPT_HTTPHEADER => [
		    	"X-Requested-With: XMLHttpRequest",
		    	":authority: www.olx.pl",
		    	":method: GET",
		    	":path: /ajax/misc/contact/phone/" . $id . "/?pt=" . $pt,
		    	":scheme: https",
		    	"accept: */*",
		    	"accept-encoding: gzip, deflate, br",
		    	"referer: https://www.olx.pl/oferta/peugeot-boxer-2-8-hdi-chlodnia-izoterma-2006r-CID5-IDz8jTZ.html",
		    	"cookie: PHPSESSID=5c39602df61b98610bf28b9567843c96563fe3ee; mobile_default=desktop; cmpvendors=%5B91%2C69%2C10%2C76%2C16%2C45%2C50%2C511%2C32%2C52%2C25%5D; dfp_segment_test_v3=17; dfp_segment_test=14; dfp_segment_test_v4=61; dfp_segment_test_oa=92; pt=f62af16badb4b7d6a0b46554cb7322a14715cc40e99090c7ea689fc10d8eb9a0d52a5907dbe0b2319bb56710637043290fe086f83865cdc6e9fd1393feb36b0b; bm_sz=2D54B2E0DB99062DEA022DC7BB7853A0~YAAQuOF7XPd+6MRpAQAA2ZQ3xQMIiRYzTqYEGdHRilHsTwfx2BBeDWMiZTqUlT8j2ShOBkU2m3PY3QqcZmmKEzEA8yIrUj+xENQs/wYKO/LgJlPKWb1Um+Ko3X3lvbBhls17r3EhhZ33uTkOz5DjjDnYCQ5xImyHso+OwV3cBvPNzdgN9xr5I3Gtkvs=; used_adblock=adblock_disabled; fingerprint=MTI1NzY4MzI5MTs4OzA7MDswOzA7MDswOzA7MDswOzE7MTsxOzE7MTsxOzE7MTsxOzE7MTsxOzE7MTswOzE7MTsxOzA7MDsxOzE7MTsxOzE7MTsxOzE7MTsxOzA7MTsxOzA7MTsxOzE7MDswOzA7MDswOzA7MTswOzE7MTswOzA7MDsxOzA7MDsxOzE7MDsxOzE7MTsxOzA7MTswOzMxMzE2MjI4NDc7MjsyOzI7MjsyOzI7Mjs0ODIwMDYyMzc7MzcyNDc2MDA1NTsxOzE7MTsxOzE7MTsxOzE7MTsxOzE7MTsxOzE7MTsxOzE7MDswOzA7MjEzMjAzMTQyNDsxNTEyODI1ODE2OzM1OTE4MTcyOTg7MzMwODM4ODQxOzEwMDUzMDEyMDM7MTM2Njs3Njg7MjQ7MjQ7MTIwOzYwOzEyMDs2MDsxMjA7NjA7MTIwOzYwOzEyMDs2MDsxMjA7NjA7MTIwOzYwOzEyMDs2MDsxMjA7NjA7MTIwOzYwOzA7MDsw; dfp_user_id=874168b9-20fc-7094-9c3c-547b4d3f4988-ver2; ldTd=true; __gfp_64b=aAIqrPy6TvgpggN0wbFxYIPW_zlG1GcxNi3A4SL5u1n.a7; lqstatus=1553793148|||; laquesis=olxeu-25609@a; laquesis_ff=; ak_bmsc=0B49DF286DDC7A17FDE89CA6F7500E155C7BE1B80A700000CBFB9C5C24164272~pl8QGDFRRrzOmwT8Be07nHysrHydKTLS+EpqYMxPOLMtIdp9sa1Ah5ApyjOx7muhYcs5x8HSTvwsdAWZw5ItoR7m6R2zNLkiQ4HbQcwxrmmyspgU0hbMSHiUFbJLmEiEE5gUP9T2ojqpW+xr3zPGSjaof5YSIRKeC89m9e0JywBYFN+s62Zvtj8cSQU36FAoWQIF61cxNqkKK3cR0khhuLiP6oz9XtVGFgMua39AlDo3jwFKORWtU/dotepUqHIM0P; pvps=1; _ga=GA1.2.1482005572.1553791948; _gid=GA1.2.1480283925.1553791948; _gat_clientNinja=1; __utma=221885126.1482005572.1553791948.1553791948.1553791948.1; __utmc=221885126; __utmz=221885126.1553791948.1.1.utmcsr=(direct)|utmccn=(direct)|utmcmd=(none); __utmt=1; _abck=0A4D550851D33813E9D7B3F2A32A66E5~0~YAAQuOF7XBt/6MRpAQAAFaA3xQEnFv5ffeaZmfew82rKYI/XyGUzes8nC5KTsHk2mC0zY8yP9f3gIdl5ctsl5qIbaW64wbGtoKV6Y9wh7jYG/BAulvW97M1EjfRbS21tcDpVdp2FSEnwpk+r56q4QplvZB4kHCkYnWGxOoLUnrAZSkwb1dsEhYqUAfKwRu7rxsxGJsVyFpNrTpG9p43lc18Sfmbl8uKRNUr1wPZiBUbSGhIAhlKVZqHoUva9lCFoq1J7ymuTYD1actJpIa0GvILBMpigqoJ6xlulRoI//6or60j19A==~-1~-1~-1; optimizelyEndUserId=oeu1553791948638r0.3196494887371075; G_ENABLED_IDPS=google; _gcl_au=1.1.900178153.1553791949; __gads=ID=d6139fcab91e47f9:T=1553791953:S=ALNI_MbMXZh_DVCdFoDNxJOuhouNGu5bmQ; new_dfp_segment_dfp_user_id_874168b9-20fc-7094-9c3c-547b4d3f4988-ver2=%5B%5D; dfp_segment=%5B%5D; cookieBarSeen=true; consentBarSeen=true; didomi_token=eyJ1c2VyX2lkIjoiMTY5YzUzNzktMzA4My02MWViLThmN2YtNWRjZmEwNDdmNmJlIiwiY3JlYXRlZCI6IjIwMTktMDMtMjhUMTY6NTI6MjcuNTY3WiIsInVwZGF0ZWQiOiIyMDE5LTAzLTI4VDE2OjUzOjE3LjIzMVoiLCJ2ZW5kb3JzIjp7ImVuYWJsZWQiOltdLCJkaXNhYmxlZCI6W119LCJwdXJwb3NlcyI6eyJlbmFibGVkIjpbImNvb2tpZXMiLCJhZHZlcnRpc2luZ19wZXJzb25hbGl6YXRpb24iLCJhZF9kZWxpdmVyeSIsImNvbnRlbnRfcGVyc29uYWxpemF0aW9uIiwiYW5hbHl0aWNzIl0sImRpc2FibGVkIjpbXX19; euconsent=BOeIdXzOeIdfkAHABBPLCI-AAAAlaALAAUABAADIAIAAWgAyABoAEUAJgAWwD_g; avppv=1; avps=1; lister_lifecycle=1553792002; bm_sv=A5CC22BA56632D1C09B4171103756EC9~9bgWFrdA9yeQgXHUfLpPukpTrTMLyYKtVuH9XJdJBgOT64JI1A/ZHeLK4BSD9gFljRzO6ChwmUS6LEbc++BYrVFqKis9BwhMD7WbQ2WH+SDQunBVV/NUtcD1qWvthMNWht8KEl+4R1Y+EZ20MmY45OcXSUtMqwoFB51jm0fIH2A=; from_detail=1; onap=169c537916fx43cebdfe-1-169c537916fx43cebdfe-13-1553793807; __utmb=221885126.5.8.1553792007225"
		    ],
		    CURLINFO_HEADER_OUT => true
		]);
		// Send the request & save response to $resp
		$resp = curl_exec($curl);
		$sent_request = curl_getinfo($curl);
		// Close request to clear up some resources
		curl_close($curl);

		//echo print_r($sent_request);

		header('Content-Type: application/json');
		echo $resp;
	}

	public function test()
	{
		/*
			Co odświeżenie strony zmienia się phoneToken <- trzeba to mieć na uwadze dla danego IP, wielowątkowość tak na prawdę odpada
			$id bierzemy z DOM parenta guzika pobierania numeru na stronie

			phoneToken generuje sie dla konkretnej oferty
			czyli jednak wielowątkowość wchodzi w grę

			$pt bierze sie ze strony ze zmiennej phoneToken var
		*/

		$id = "mRDem";
		$pt = "f4fa6e4ebb873b94b9ea0ac28a7d026a9b41e2df32896ec50d3dda1981bab123d8bac1d640ea196d5fdc1be4afa8da6e8f399936ff6d464f7aaafa1eb2243913";


		// Get cURL resource
		$curl = curl_init();
		// Set some options - we are passing in a useragent too here
		curl_setopt_array($curl, [
		    CURLOPT_RETURNTRANSFER => 1,
		    CURLOPT_URL => 'https://www.olx.pl/ajax/misc/contact/phone/'. $id .'/?pt=' . $pt,
		    CURLOPT_USERAGENT => 'Codular Sample cURL Request',
		    CURLOPT_HTTPHEADER => [
		    	"X-Requested-With: XMLHttpRequest",
		    	":authority: www.olx.pl",
		    	":method: GET",
		    	":path: /ajax/misc/contact/phone/" . $id . "/?pt=" . $pt,
		    	":scheme: https",
		    	"accept: */*",
		    	"accept-encoding: gzip, deflate, br",
		    	"referer: https://www.olx.pl/oferta/plyty-drogowe-betonowe-CID628-IDmRDem.html",
		    	"cookie: PHPSESSID=5c39602df61b98610bf28b9567843c96563fe3ee; mobile_default=desktop; cmpvendors=%5B91%2C69%2C10%2C76%2C16%2C45%2C50%2C511%2C32%2C52%2C25%5D; dfp_segment_test_v3=17; dfp_segment_test=14; dfp_segment_test_v4=61; dfp_segment_test_oa=92; pt=f62af16badb4b7d6a0b46554cb7322a14715cc40e99090c7ea689fc10d8eb9a0d52a5907dbe0b2319bb56710637043290fe086f83865cdc6e9fd1393feb36b0b; bm_sz=2D54B2E0DB99062DEA022DC7BB7853A0~YAAQuOF7XPd+6MRpAQAA2ZQ3xQMIiRYzTqYEGdHRilHsTwfx2BBeDWMiZTqUlT8j2ShOBkU2m3PY3QqcZmmKEzEA8yIrUj+xENQs/wYKO/LgJlPKWb1Um+Ko3X3lvbBhls17r3EhhZ33uTkOz5DjjDnYCQ5xImyHso+OwV3cBvPNzdgN9xr5I3Gtkvs=; used_adblock=adblock_disabled; fingerprint=MTI1NzY4MzI5MTs4OzA7MDswOzA7MDswOzA7MDswOzE7MTsxOzE7MTsxOzE7MTsxOzE7MTsxOzE7MTswOzE7MTsxOzA7MDsxOzE7MTsxOzE7MTsxOzE7MTsxOzA7MTsxOzA7MTsxOzE7MDswOzA7MDswOzA7MTswOzE7MTswOzA7MDsxOzA7MDsxOzE7MDsxOzE7MTsxOzA7MTswOzMxMzE2MjI4NDc7MjsyOzI7MjsyOzI7Mjs0ODIwMDYyMzc7MzcyNDc2MDA1NTsxOzE7MTsxOzE7MTsxOzE7MTsxOzE7MTsxOzE7MTsxOzE7MDswOzA7MjEzMjAzMTQyNDsxNTEyODI1ODE2OzM1OTE4MTcyOTg7MzMwODM4ODQxOzEwMDUzMDEyMDM7MTM2Njs3Njg7MjQ7MjQ7MTIwOzYwOzEyMDs2MDsxMjA7NjA7MTIwOzYwOzEyMDs2MDsxMjA7NjA7MTIwOzYwOzEyMDs2MDsxMjA7NjA7MTIwOzYwOzA7MDsw; dfp_user_id=874168b9-20fc-7094-9c3c-547b4d3f4988-ver2; ldTd=true; __gfp_64b=aAIqrPy6TvgpggN0wbFxYIPW_zlG1GcxNi3A4SL5u1n.a7; lqstatus=1553793148|||; laquesis=olxeu-25609@a; laquesis_ff=; ak_bmsc=0B49DF286DDC7A17FDE89CA6F7500E155C7BE1B80A700000CBFB9C5C24164272~pl8QGDFRRrzOmwT8Be07nHysrHydKTLS+EpqYMxPOLMtIdp9sa1Ah5ApyjOx7muhYcs5x8HSTvwsdAWZw5ItoR7m6R2zNLkiQ4HbQcwxrmmyspgU0hbMSHiUFbJLmEiEE5gUP9T2ojqpW+xr3zPGSjaof5YSIRKeC89m9e0JywBYFN+s62Zvtj8cSQU36FAoWQIF61cxNqkKK3cR0khhuLiP6oz9XtVGFgMua39AlDo3jwFKORWtU/dotepUqHIM0P; pvps=1; _ga=GA1.2.1482005572.1553791948; _gid=GA1.2.1480283925.1553791948; _gat_clientNinja=1; __utma=221885126.1482005572.1553791948.1553791948.1553791948.1; __utmc=221885126; __utmz=221885126.1553791948.1.1.utmcsr=(direct)|utmccn=(direct)|utmcmd=(none); __utmt=1; _abck=0A4D550851D33813E9D7B3F2A32A66E5~0~YAAQuOF7XBt/6MRpAQAAFaA3xQEnFv5ffeaZmfew82rKYI/XyGUzes8nC5KTsHk2mC0zY8yP9f3gIdl5ctsl5qIbaW64wbGtoKV6Y9wh7jYG/BAulvW97M1EjfRbS21tcDpVdp2FSEnwpk+r56q4QplvZB4kHCkYnWGxOoLUnrAZSkwb1dsEhYqUAfKwRu7rxsxGJsVyFpNrTpG9p43lc18Sfmbl8uKRNUr1wPZiBUbSGhIAhlKVZqHoUva9lCFoq1J7ymuTYD1actJpIa0GvILBMpigqoJ6xlulRoI//6or60j19A==~-1~-1~-1; optimizelyEndUserId=oeu1553791948638r0.3196494887371075; G_ENABLED_IDPS=google; _gcl_au=1.1.900178153.1553791949; __gads=ID=d6139fcab91e47f9:T=1553791953:S=ALNI_MbMXZh_DVCdFoDNxJOuhouNGu5bmQ; new_dfp_segment_dfp_user_id_874168b9-20fc-7094-9c3c-547b4d3f4988-ver2=%5B%5D; dfp_segment=%5B%5D; cookieBarSeen=true; consentBarSeen=true; didomi_token=eyJ1c2VyX2lkIjoiMTY5YzUzNzktMzA4My02MWViLThmN2YtNWRjZmEwNDdmNmJlIiwiY3JlYXRlZCI6IjIwMTktMDMtMjhUMTY6NTI6MjcuNTY3WiIsInVwZGF0ZWQiOiIyMDE5LTAzLTI4VDE2OjUzOjE3LjIzMVoiLCJ2ZW5kb3JzIjp7ImVuYWJsZWQiOltdLCJkaXNhYmxlZCI6W119LCJwdXJwb3NlcyI6eyJlbmFibGVkIjpbImNvb2tpZXMiLCJhZHZlcnRpc2luZ19wZXJzb25hbGl6YXRpb24iLCJhZF9kZWxpdmVyeSIsImNvbnRlbnRfcGVyc29uYWxpemF0aW9uIiwiYW5hbHl0aWNzIl0sImRpc2FibGVkIjpbXX19; euconsent=BOeIdXzOeIdfkAHABBPLCI-AAAAlaALAAUABAADIAIAAWgAyABoAEUAJgAWwD_g; avppv=1; avps=1; lister_lifecycle=1553792002; bm_sv=A5CC22BA56632D1C09B4171103756EC9~9bgWFrdA9yeQgXHUfLpPukpTrTMLyYKtVuH9XJdJBgOT64JI1A/ZHeLK4BSD9gFljRzO6ChwmUS6LEbc++BYrVFqKis9BwhMD7WbQ2WH+SDQunBVV/NUtcD1qWvthMNWht8KEl+4R1Y+EZ20MmY45OcXSUtMqwoFB51jm0fIH2A=; from_detail=1; onap=169c537916fx43cebdfe-1-169c537916fx43cebdfe-13-1553793807; __utmb=221885126.5.8.1553792007225"
		    ],
		    CURLINFO_HEADER_OUT => true
		]);
		// Send the request & save response to $resp
		$resp = curl_exec($curl);
		$sent_request = curl_getinfo($curl);
		// Close request to clear up some resources
		curl_close($curl);

		//echo print_r($sent_request);

		header('Content-Type: application/json');
		echo $resp;
	}
}
