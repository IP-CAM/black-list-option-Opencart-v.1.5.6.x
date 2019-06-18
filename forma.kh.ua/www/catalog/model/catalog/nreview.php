<?php
class ModelCatalogNreview extends Model {
	public function addReview($news_id, $data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "info_review SET 
		author = '" . $this->db->escape($data['name']) . "', 
		customer_id = '" . (int)$this->customer->getId() . "', 
		news_id = '" . (int)$news_id . "', 
		text = '" . $this->db->escape($data['text']) . "', 
		rating = '" . (int)$data['rating'] . "', 
		date_added = NOW()");
	}
		
	public function getReviewsByNewsId($news_id, $start = 0, $limit = 20) {
		if ($start < 0) {
			$start = 0;
		}
		
		if ($limit < 1) {
			$limit = 20;
		}		
		
		$query = $this->db->query("SELECT
		r.review_id,
		r.author,
		r.rating,
		r.text,
		n.news_id,
		nd.title,
		r.date_added
		FROM " . DB_PREFIX . "info_review r
		LEFT JOIN " . DB_PREFIX . "news n
		ON (r.news_id = n.news_id)
		LEFT JOIN " . DB_PREFIX . "news_description nd
		ON (n.news_id = nd.news_id)
		WHERE n.news_id = '" . (int)$news_id . "'
		AND n.status = '1'
		AND r.status = '1'
		AND nd.language_id = '" . (int)$this->config->get('config_language_id') . "'
		ORDER BY r.date_added
		DESC LIMIT " . (int)$start . "," . (int)$limit);
			
		return $query->rows;
	}
	
	public function getAverageRating($news_id) {
		$query = $this->db->query("SELECT AVG(rating) AS total FROM " . DB_PREFIX . "info_review WHERE status = '1' AND news_id = '" . (int)$news_id . "' GROUP BY news_id");
		
		if (isset($query->row['total'])) {
			return (int)$query->row['total'];
		} else {
			return 0;
		}
	}	
	
	public function getTotalReviews() {
		$query = $this->db->query("SELECT 
		COUNT(*) AS total 
		FROM " . DB_PREFIX . "info_review r
		LEFT JOIN " . DB_PREFIX . "news n 
		ON (r.news_id = n.news_id) 
		WHERE n.status = '1' AND r.status = '1'");
		
		return $query->row['total'];
	}

	public function getTotalReviewsByNewsId($news_id) {
		$query = $this->db->query("SELECT
		COUNT(*) AS total
		FROM " . DB_PREFIX . "info_review r
		LEFT JOIN " . DB_PREFIX . "news n
		ON (r.news_id = n.news_id)
		LEFT JOIN " . DB_PREFIX . "news_description nd
		ON (n.news_id = nd.news_id)
		WHERE n.news_id = '" . (int)$news_id . "'
		AND n.status = '1'
		AND r.status = '1'
		AND nd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
		
		return $query->row['total'];
	}
}
?>