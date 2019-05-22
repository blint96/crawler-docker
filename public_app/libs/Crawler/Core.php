<?php

namespace Crawler;

class Core 
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

            // apply filters
            $flag = false;
            foreach($this->filter as $f) {
            	if(strpos($href, $f) !== FALSE) {
            		$flag = true;
            		break;
            	}
            }
            if($flag)
            	continue;

	        if(strpos($href, "olx.pl") === FALSE && strpos($href, "www.olx.pl") === FALSE)
	           	continue;

            // Crawl only link that belongs to the start domain
            //$this->crawl($href, $depth - 1);

          	$this->db->query("INSERT INTO fresh VALUES(NULL, ?, NULL)", [$href]);
          	$this->crawl($href, $depth - 1);
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

		curl_setopt_array($curl, [
			CURLOPT_RETURNTRANSFER => 1,
		    CURLOPT_URL => $url,
		    CURLOPT_USERAGENT => $this->userAgent,
		    CURLINFO_HEADER_OUT => true,
		    CURLOPT_HTTPHEADER => $this->headers,
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
	 *	Crawl url
	 */
	public function crawl($url, $depth)
	{
		// prevent from crawling already crawled 
		// website
		if(isset($this->visited[$url]) && strcmp($url, $this->initUrl) != 0) {
			//var_dump("break i chuj");
			return false;
		}

		// temporary
		if (microtime(true) - $this->limitStart >= $this->limitSeconds) {
			//var_dump("break");
		    return false;
		}

		$this->visited[$url] = time();
		list($response, $time, $code) = $this->getContent($url);

		// TODO: process the $response

		// insert info about link
		$this->db->query("INSERT INTO links VALUES(NULL, ?, NULL)", [$url]);

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