<?php
//-----------------------------------------------------
// News Module for Opencart v1.5.5   							
// Modified by villagedefrance                          			
// contact@villagedefrance.net                         			
//-----------------------------------------------------

class ControllerInformationNews extends Controller {

	public function index() {
	
    	$this->language->load('information/news');

        //added
        $this->language->load('product/product');

        $this->data['entry_name'] = $this->language->get('entry_name');
        $this->data['entry_review'] = $this->language->get('entry_review');
        $this->data['entry_rating'] = $this->language->get('entry_rating');
        $this->data['entry_good'] = $this->language->get('entry_good');
        $this->data['entry_bad'] = $this->language->get('entry_bad');
        $this->data['entry_captcha'] = $this->language->get('entry_captcha');
        $this->data['text_write'] = $this->language->get('text_write');
        $this->data['text_note'] = $this->language->get('text_note');

        //added

		$this->load->model('catalog/news');
	
		$this->data['breadcrumbs'] = array();
	
		$this->data['breadcrumbs'][] = array(
			'href'      => $this->url->link('common/home'),
			'text'      => $this->language->get('text_home'),
			'separator' => false
		);
	
		if (isset($this->request->get['news_id'])) {
			$news_id = $this->request->get['news_id'];
		} else {
			$news_id = 0;
		}
	
		$news_info = $this->model_catalog_news->getNewsStory($news_id);
	
		if ($news_info) {
	  	
			$this->document->addStyle('catalog/view/theme/default/stylesheet/news.css');
			$this->document->addStyle('catalog/view/javascript/jquery/colorbox/colorbox.css');
		
			$this->document->addScript('catalog/view/javascript/jquery/colorbox/jquery.colorbox-min.js');
		
			$this->data['breadcrumbs'][] = array(
				'href'      => $this->url->link('information/news'),
				'text'      => $this->language->get('heading_title'),
				'separator' => $this->language->get('text_separator')
			);
		
			$this->data['breadcrumbs'][] = array(
				'href'      => $this->url->link('information/news', 'news_id=' . $this->request->get['news_id']),
				'text'      => $news_info['title'],
				'separator' => $this->language->get('text_separator')
			);
			
			$this->document->setTitle($news_info['title']);
			$this->document->setDescription($news_info['meta_description']);
			$this->document->setKeywords($news_info['keyword']);
			$this->document->addLink($this->url->link('information/news', 'news_id=' . $this->request->get['news_id']), 'canonical');
		
     		$this->data['news_info'] = $news_info;
     		$this->data['news_id'] = $news_id;

     		$this->data['heading_title'] = $news_info['title'];
		
			$this->data['description'] = html_entity_decode($news_info['description']);
			
			$this->data['viewed'] = sprintf($this->language->get('text_viewed'), $news_info['viewed']);
		
			$this->data['addthis'] = $this->config->get('news_newspage_addthis');
		
			$this->data['min_height'] = $this->config->get('news_thumb_height');
		
			$this->load->model('tool/image');
		
			if ($news_info['image']) { $this->data['image'] = TRUE; } else { $this->data['image'] = FALSE; }
		
			$this->data['thumb'] = $this->model_tool_image->resize($news_info['image'], $this->config->get('news_thumb_width'), $this->config->get('news_thumb_height'));
			$this->data['popup'] = $this->model_tool_image->resize($news_info['image'], $this->config->get('news_popup_width'), $this->config->get('news_popup_height'));
		
     		$this->data['button_news'] = $this->language->get('button_news');
			$this->data['button_continue'] = $this->language->get('button_continue');
		
			$this->data['news'] = $this->url->link('information/news');
			$this->data['continue'] = $this->url->link('common/home');
			
		/*	$this->data['referred'] = $_SERVER['HTTP_REFERER'];*/
			$this->data['refreshed'] = 'http://' . $_SERVER['HTTP_HOST'] . '' . $_SERVER['REQUEST_URI'];
			
/*			if ($this->data['referred'] != $this->data['refreshed']) {
				$this->model_catalog_news->updateViewed($this->request->get['news_id']);
			}*/
		
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/information/news.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/information/news.tpl';
			} else {
				$this->template = 'default/template/information/news.tpl';
			}
		
			$this->children = array(
				'common/column_left',
				'common/column_right',
				'common/content_top',
				'common/content_bottom',
				'common/footer',
				'common/header'
			);
		
			$this->response->setOutput($this->render());
		
	  	} else {
		
	  		$news_data = $this->model_catalog_news->getNews();
		
	  		if ($news_data) {
			
				$this->document->setTitle($this->language->get('heading_title'));
			
				$this->data['breadcrumbs'][] = array(
					'href'      => $this->url->link('information/news'),
					'text'      => $this->language->get('heading_title'),
					'separator' => $this->language->get('text_separator')
				);
			
				$this->data['heading_title'] = $this->language->get('heading_title');
			
				$this->document->addStyle('catalog/view/javascript/jquery/panels/main.css');
				$this->document->addScript('catalog/view/javascript/jquery/panels/utils.js');
			
				$this->data['text_more'] = $this->language->get('text_more');
				$this->data['text_posted'] = $this->language->get('text_posted');
				
				$chars = $this->config->get('news_headline_chars');
			
				foreach ($news_data as $result) {
					$this->data['news_data'][] = array(
						'id'  				=> $result['news_id'],
						'title'        		=> $result['title'],
						'description'  	=> utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $chars),
						'href'         		=> $this->url->link('information/news', 'news_id=' . $result['news_id']),
						'posted'   		=> date($this->language->get('date_format_short'), strtotime($result['date_added']))
					);
				}
			
				$this->data['button_continue'] = $this->language->get('button_continue');
			
				$this->data['continue'] = $this->url->link('common/home');
			
				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/information/news.tpl')) {
					$this->template = $this->config->get('config_template') . '/template/information/news.tpl';
				} else {
					$this->template = 'default/template/information/news.tpl';
				}
			
				$this->children = array(
					'common/column_left',
					'common/column_right',
					'common/content_top',
					'common/content_bottom',
					'common/footer',
					'common/header'
				);
			
				$this->response->setOutput($this->render());
			
	    	} else {
			
		  		$this->document->setTitle($this->language->get('text_error'));
			
	     		$this->document->breadcrumbs[] = array(
	        		'href'      => $this->url->link('information/news'),
	        		'text'      => $this->language->get('text_error'),
	        		'separator' => $this->language->get('text_separator')
	     		);
			
				$this->data['heading_title'] = $this->language->get('text_error');
			
				$this->data['text_error'] = $this->language->get('text_error');
			
				$this->data['button_continue'] = $this->language->get('button_continue');
			
				$this->data['continue'] = $this->url->link('common/home');
			
				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/not_found.tpl')) {
					$this->template = $this->config->get('config_template') . '/template/error/not_found.tpl';
				} else {
					$this->template = 'default/template/error/not_found.tpl';
				}
			
				$this->children = array(
					'common/column_left',
					'common/column_right',
					'common/content_top',
					'common/content_bottom',
					'common/footer',
					'common/header'
				);
			
				$this->response->setOutput($this->render());
		  	}
		}
	}

    //added
    public function review() {
        $this->language->load('product/product');

        $this->load->model('catalog/nreview');

        $this->data['text_on'] = $this->language->get('text_on');
        $this->data['text_no_nreviews'] = $this->language->get('text_no_nreviews');

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $this->data['reviews'] = array();

        $review_total = $this->model_catalog_nreview->getTotalReviewsByNewsId($this->request->get['news_id']);

        $results = $this->model_catalog_nreview->getReviewsByNewsId($this->request->get['news_id'], ($page - 1) * 5, 5);

        foreach ($results as $result) {
            $this->data['reviews'][] = array(
                'author'     => $result['author'],
                'text'       => $result['text'],
                'rating'     => (int)$result['rating'],
                'reviews'    => sprintf($this->language->get('text_reviews'), (int)$review_total),
                'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added']))
            );
        }

        $pagination = new Pagination();
        $pagination->total = $review_total;
        $pagination->page = $page;
        $pagination->limit = 5;
        $pagination->text = $this->language->get('text_pagination');
        $pagination->url = $this->url->link('information/news/review', 'news_id=' . $this->request->get['news_id'] . '&page={page}');

        $this->data['pagination'] = $pagination->render();

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/information/review.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/information/review.tpl';
        } else {
            $this->template = 'default/template/information/review.tpl';
        }

        $this->response->setOutput($this->render());
    }

    public function write() {
        $this->language->load('product/product');

        $this->load->model('catalog/nreview');

        $json = array();

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 25)) {
                $json['error'] = $this->language->get('error_name');
            }

            if ((utf8_strlen($this->request->post['text']) < 25) || (utf8_strlen($this->request->post['text']) > 1000)) {
                $json['error'] = $this->language->get('error_text');
            }

            if (empty($this->request->post['rating'])) {
                $json['error'] = $this->language->get('error_rating');
            }

            if (empty($this->session->data['captcha']) || ($this->session->data['captcha'] != $this->request->post['captcha'])) {
                $json['error'] = $this->language->get('error_captcha');
            }

            if (!isset($json['error'])) {
                $this->model_catalog_nreview->addReview($this->request->get['news_id'], $this->request->post);

                $json['success'] = $this->language->get('text_success');
            }
        }

        $this->response->setOutput(json_encode($json));
    }
    //added

}
?>
