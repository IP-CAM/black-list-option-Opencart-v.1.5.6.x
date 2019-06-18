<?php

/*******************************************************************************
*                                 Opencart SEO Pack                            *
*                             � Copyright Ovidiu Fechete                       *
*                              email: ovife21@gmail.com                        *
*                Below source-code or any part of the source-code              *
*                          cannot be resold or distributed.                    *
*******************************************************************************/


class ControllerFeedGoogleSitemapExtra extends Controller {
   public function index() {
		 $output  = '<?xml version="1.0" encoding="UTF-8"?>';
		 $output .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
		 
		 $fields = array();
		 $fields = $this->db->query("SELECT DISTINCT ua.keyword FROM " . DB_PREFIX . "url_alias ua INNER JOIN " . DB_PREFIX . "language l on l.language_id = ua.language_id where l.code = '". $_GET["language"] ."';")->rows;
         		 
		 foreach ($fields as $field) {
			$output .= '<url>';
			$output .= '<loc>' . HTTPS_SERVER  . $field['keyword'] . '</loc>';
			$output .= '<changefreq>weekly</changefreq>';
			$output .= '<priority>1.0</priority>';
			$output .= '</url>';   
		 }
		 
		 $output .= '</urlset>';
		 
		 $this->response->addHeader('Content-Type: application/xml');
		 $this->response->setOutput($output);
	  }
     
}
?>