<?php

namespace Crawler;

class CoreLinksOtomoto 
{
	// path that'll be crawled
	protected $url;
	protected $initUrl;

	// curl headers
	protected $headers = [];

	// name of useragent
	protected $userAgent;

	// visited urls
	protected $visited = [];

	// depth of seeking
	protected $depth;

	// host of website
	protected $host;

	// limit
	protected $limitSeconds = 10;
	protected $limitStart = 0;

	// handler for database
	protected $db;

	// filters
    protected $filter;
    
    protected $overrideHttpHeader = NULL;

	/**
	 * ****************************************************************************
	 * 
	 * Constructor
	 *  
	 * ****************************************************************************
	 */

	/**
	 *	The crawler core constructor
	 */
	public function __construct($url, $db, $userAgent = "CURL", $depth = 5, $filter = []) {
		$this->url = $url;
		$this->initUrl = $url;
		$this->userAgent = $userAgent;
		$this->depth = $depth;
		$this->db = $db;
		$this->filter = $filter;

		$parse = parse_url($url);
        $this->host = $parse['host'];

        // get the visited
        $query = $this->db->query("SELECT * FROM links");
        $result = $query->result_array();
        foreach($result as $r) {
        	$this->visited[$r['link']] = $r['date'];
        }
	}

	/**
	 * ****************************************************************************
	 * 
	 * Protected
	 *  
	 * ****************************************************************************
	 */

	/**
	 *	process the links
	 */
	protected function _processAnchors($content, $url, $depth)
    {
        $dom = new \DOMDocument('1.0');
        @$dom->loadHTML($content);
        $anchors = $dom->getElementsByTagName('a');

        foreach ($anchors as $element) {
            $href = $element->getAttribute('href');

            if (0 !== strpos($href, 'http')) {
                $path = '/' . ltrim($href, '/');
                if (extension_loaded('http')) {
                    $href = http_build_url($url, array('path' => $path));
                } else {
                    $parts = parse_url($url);
                    $href = $parts['scheme'] . '://';
                    if (isset($parts['user']) && isset($parts['pass'])) {
                        $href .= $parts['user'] . ':' . $parts['pass'] . '@';
                    }
                    $href .= $parts['host'];
                    if (isset($parts['port'])) {
                        $href .= ':' . $parts['port'];
                    }
                    $href .= $path;
                }
            }

            if(strpos($href, "oferta") !== FALSE) {
                $check = $this->db->query("SELECT * FROM vehicles_otomoto WHERE link = ? LIMIT 1", [$href]);
                if($check->num_rows() == 0)
                    $this->db->query("INSERT INTO vehicles_otomoto VALUES(NULL, ?, NULL)", [$href]);
            }
        }
    }

    /**
	 * ****************************************************************************
	 * 
	 * Public
	 *  
	 * ****************************************************************************
	 */

	/**
	 *	Get the url of page
	 */
	public function getUrl() {
		return $this->url;
	}

	/**
	 *	Set new url
	 */
	public function setUrl($url) {
		$this->url = $url;
	}

	/**
	 *	Get content of website
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

		//var_dump($response);

        // response total time
        $time = curl_getinfo($curl, CURLINFO_TOTAL_TIME);

        /* Check for 404 (file not found). */
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);
		return [$response, $time, $httpCode];
	}

	/**
	 *	Crawl url
	 */
	public function crawl($url, $depth)
	{
		// prevent from crawling already crawled 
		// website
		if(isset($this->visited[$url]) && strcmp($url, $this->initUrl) != 0) {
			var_dump("break i chuj");
			return false;
		}

		// temporary
		if (microtime(true) - $this->limitStart >= $this->limitSeconds) {
			var_dump("break");
		    return false;
		}

		$this->visited[$url] = time();
		list($response, $time, $code) = $this->getContent($url);

		// TODO: process the $response

		// insert info about link
		//$this->db->query("INSERT INTO links VALUES(NULL, ?, NULL)", [$url]);

		$this->_processAnchors($response, $url, $depth);
	}

	public function setLimitStart($time)
	{
		$this->limitStart = $time;
	}

	/**
	 *	get visited urls
	 */
	public function getVisited()
	{
		return $this->visited;
	}

	/**
	 *	init crawler function
	 */
	public function init()
	{
		$this->limitStart = microtime(true);
		$this->crawl($this->url, $this->depth);
	}
}