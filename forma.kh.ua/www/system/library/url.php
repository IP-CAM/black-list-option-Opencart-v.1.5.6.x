<?php
class Url {
	private $url;
	private $ssl;
	private $rewrite = array();
    private $isSSL = false;
	
	public function __construct($url, $ssl = '') {
		$this->url = $url;
		$this->ssl = $ssl;
        if (isset($_SERVER['HTTPS']) && (($_SERVER['HTTPS'] == 'on') || ($_SERVER['HTTPS'] == '1'))) {
            $this->isSSL = true;
		} else {
			$this->isSSL = false;
		}
	}
		
	public function addRewrite($rewrite) {
		$this->rewrite[] = $rewrite;
	}
		
	public function link($route, $args = '', $connection = 'NONSSL') {
		if ($this->isSSL) {
			$url = $this->ssl;	
		} else {
			$url = $this->url;	
		}
		
		$url .= 'index.php?route=' . $route;
			
		if ($args) {
			$url .= str_replace('&', '&amp;', '&' . ltrim($args, '&')); 
		}
		
		foreach ($this->rewrite as $rewrite) {
			$url = $rewrite->rewrite($url);
		}
				
		return $url;
	}
}
?>