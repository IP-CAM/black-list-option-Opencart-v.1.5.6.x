<?php
class ModelCatalogNreview extends Model {
	public function addReview($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "info_review SET 
		author = '" . $this->db->escape($data['author']) . "', 
		news_id = '" . $this->db->escape($data['news_id']) . "',
		text = '" . $this->db->escape(strip_tags($data['text'])) . "', 
		rating = '" . (int)$data['rating'] . "', 
		status = '" . (int)$data['status'] . "', 
		date_added = NOW()");
	
		//$this->cache->delete('product');
	}
	
	public function editReview($review_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "info_review SET
		author = '" . $this->db->escape($data['author']) . "', 
		news_id = '" . $this->db->escape($data['news_id']) . "',
		text = '" . $this->db->escape(strip_tags($data['text'])) . "', 
		rating = '" . (int)$data['rating'] . "', 
		status = '" . (int)$data['status'] . "', 
		date_added = NOW() 
		WHERE review_id = '" . (int)$review_id . "'");
	
		//$this->cache->delete('product');
	}
	
	public function deleteReview($review_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "info_review WHERE review_id = '" . (int)$review_id . "'");
		
		//$this->cache->delete('product');
	}
	
	public function getReview($review_id) {
		$query = $this->db->query("SELECT DISTINCT *, (SELECT 
		nd.title FROM " . DB_PREFIX . "news_description nd
		WHERE nd.news_id = r.news_id
		AND nd.language_id = '" . (int)$this->config->get('config_language_id') . "')
		AS news FROM " . DB_PREFIX . "info_review r
		WHERE r.review_id = '" . (int)$review_id . "'");
		
		return $query->row;
	}

	public function getReviews($data = array()) {
		$sql = "SELECT 
		r.review_id, 
		nd.title,
		r.author, 
		r.rating, 
		r.status, 
		r.date_added 
		FROM " . DB_PREFIX . "info_review r 
		LEFT JOIN " . DB_PREFIX . "news_description nd
		ON (r.news_id = nd.news_id)
		WHERE nd.language_id = '" . (int)$this->config->get('config_language_id') . "'";
		
		$sort_data = array(
			'nd.title',
			'r.author',
			'r.rating',
			'r.status',
			'r.date_added'
		);	
			
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY r.date_added";	
		}
			
		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}
		
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}			

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}	
			
			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}																																							  
																																							  
		$query = $this->db->query($sql);																																				
		
		return $query->rows;	
	}
	
	public function getTotalReviews() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "info_review");
		
		return $query->row['total'];
	}
	
	public function getTotalReviewsAwaitingApproval() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "info_review WHERE status = '0'");
		
		return $query->row['total'];
	}	
}
?>