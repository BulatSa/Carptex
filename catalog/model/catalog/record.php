<?php
class ModelCatalogRecord extends Model
{
	public function updateViewed($record_id)
	{
		$this->db->query("UPDATE " . DB_PREFIX . "record SET viewed = (viewed + 1) WHERE record_id = '" . (int) $record_id . "'");
	}
	public function getRecord($record_id)
	{
        if (SCP_VERSION > 1 ) {
			$get_Customer_GroupId = 'getGroupId';
		} else {
			$get_Customer_GroupId = 'getCustomerGroupId';
		}

		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->$get_Customer_GroupId();
			$customer_query    = " AND (p.customer_group_id = '" . (int) $customer_group_id . "' OR p.customer_group_id = '" . (int) $this->config->get('config_customer_group_id') . "')";
		} //$this->customer->isLogged()
		else {
			$customer_group_id = $this->config->get('config_customer_group_id');
			$customer_query    = " AND p.customer_group_id = '" . (int) $customer_group_id . "'";
		}
		$sql   = "
		SELECT DISTINCT *, pd.name AS name, p.image,

		(SELECT AVG(rating) AS total FROM " . DB_PREFIX . "comment r1
		WHERE
		r1.record_id = p.record_id
		AND
		r1.status = '1'
		GROUP BY r1.record_id) AS rating,
		(SELECT COUNT(*) AS total FROM " . DB_PREFIX . "comment r2
		WHERE
		r2.record_id = p.record_id
		AND
		r2.status = '1'
		GROUP BY r2.record_id) AS comments, p.sort_order FROM " . DB_PREFIX . "record p
		LEFT JOIN " . DB_PREFIX . "record_description pd ON (p.record_id = pd.record_id)
		LEFT JOIN " . DB_PREFIX . "record_to_store p2s ON (p.record_id = p2s.record_id)
		WHERE p.record_id = '" . (int) $record_id . "'
		" . $customer_query . "
		AND
		pd.language_id = '" . (int) $this->config->get('config_language_id') . "'
		AND
		p.status = '1'
		AND NOW() BETWEEN p.date_available AND p.date_end
		AND
		p2s.store_id = '" . (int) $this->config->get('config_store_id') . "'";
		$query = $this->db->query($sql);
		if ($query->num_rows) {
			$query->row['rating'] = round ($query->row['rating']);
			return $query->row;
		} //$query->num_rows
		else {
			return false;
		}
	}




	public function getRelatedRecords($product_id, $data, $pointer='product_id')
	{
	    if (SCP_VERSION > 1 ) {
			$get_Customer_GroupId = 'getGroupId';
		} else {
			$get_Customer_GroupId = 'getCustomerGroupId';
		}
	 if ($product_id) {
		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->$get_Customer_GroupId();
			$customer_query    = " AND (p.customer_group_id = '" . (int) $customer_group_id . "' OR p.customer_group_id = '" . (int) $this->config->get('config_customer_group_id') . "')";
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
			$customer_query    = " AND p.customer_group_id = '" . (int) $customer_group_id . "'";
		}

		$sql_blogs = '';
		if (isset($data['settings']['blogs']) && isset($data['settings']['blogs'][0]) && count($data['settings']['blogs'][0])>0 && $data['settings']['blogs'][0]!= -1) {

			 $sql_blogs = "AND	p2c.blog_id IN (".implode($data['settings']['blogs'],',').") ";

  		}

        if (isset($data['pagination']) && $data['pagination']) {
			 $calc_rows = " SQL_CALC_FOUND_ROWS ";
		 } else {
		  	 $calc_rows = "";
		 }


		$record_data = array();
		$sql         = "SELECT ".$calc_rows." *, p.record_id as record_id FROM " . DB_PREFIX . "record_related pr
		LEFT JOIN " . DB_PREFIX . "record p ON (pr.pointer_id = p.record_id)
		LEFT JOIN " . DB_PREFIX . "record_to_store p2s ON (p.record_id = p2s.record_id)

	  	LEFT JOIN " . DB_PREFIX . "record_to_blog p2c ON (p.record_id = p2c.record_id)


		 WHERE
		 pr.related_id = '" . (int) $product_id . "' AND pointer='".$pointer."'
		 ".$sql_blogs."
		 AND p.status = '1'
		 " . $customer_query . "
		 AND NOW() BETWEEN p.date_available AND p.date_end
		 AND p2s.store_id = '" . (int) $this->config->get('config_store_id') . "'";
         $sql .= " GROUP BY p.record_id ";

		$sort_data = array(
				'rating',
				'comments',
				'popular',
				'latest',
				'sort'
			);
			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				if ($data['sort'] == 'rating') {
					$data['sort'] = 'rating';
				} //$data['sort'] == 'rating'
				if ($data['sort'] == 'comments') {
					$data['sort'] = 'comments';
				} //$data['sort'] == 'comments'
				if ($data['sort'] == 'latest') {
					$data['sort'] = 'p.date_available';
				} //$data['sort'] == 'latest'
				if ($data['sort'] == 'sort') {
					$data['sort'] = 'p.sort_order';
				} //$data['sort'] == 'sort'
				if ($data['sort'] == 'popular') {
					$data['sort'] = 'p.viewed';
				} //$data['sort'] == 'popular'
				$sql .= " ORDER BY " . $data['sort'];
			} //isset($data['sort']) && in_array($data['sort'], $sort_data)
			else {
				$sql .= " ORDER BY p.sort_order";
			}
			if (isset($data['order']) && (strtoupper($data['order']) == 'DESC')) {
				$sql .= " DESC";
			} //isset($data['order']) && (strtoupper($data['order']) == 'DESC')
			else {
				$sql .= " ASC";
			}



            if (isset($data['start']['start'])) {
              $data['start'] = $data['start']['start'];
            } else {
               $data['start'] = 0;
            }

			if (isset($data['start']) || isset($data['limit'])) {
				if ($data['start'] < 0) {
					$data['start'] = 0;
				} //$data['start'] < 0
				if ($data['limit'] < 1) {
					$data['limit'] = 20;
				} //$data['limit'] < 1
				$sql .= " LIMIT " . (int) $data['start'] . "," . (int) $data['limit'];
			}

		$query       = $this->db->query($sql);

			if (isset($data['pagination']) && $data['pagination']) {
				$query_total = $this->db->query("SELECT FOUND_ROWS() as total");
				$total  = $query_total->row['total'];
			 } else {
			  $total = false;
			 }



			foreach ($query->rows as $num => $result) {
				$query->rows[$num]['total'] = $total;
			}


		return $query->rows;

	 }
	}


	public function getRecords($data = array())
	{



        if (SCP_VERSION > 1 ) {
			$get_Customer_GroupId = 'getGroupId';
		} else {
			$get_Customer_GroupId = 'getCustomerGroupId';
		}


		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->$get_Customer_GroupId();
			$customer_query    = " AND (p.customer_group_id = '" . (int) $customer_group_id . "' OR p.customer_group_id = '" . (int) $this->config->get('config_customer_group_id') . "')";
		} //$this->customer->isLogged()
		else {
			$customer_group_id = $this->config->get('config_customer_group_id');
			$customer_query    = " AND p.customer_group_id = '" . (int) $customer_group_id . "'";
		}


		$cache       = md5(http_build_query($data));
		$record_data = $this->cache->get('record.' . (int) $this->config->get('config_language_id') . '.' . (int) $this->config->get('config_store_id') . '.' . (int) $customer_group_id . '.' . $cache);
		if (!$record_data) {
			$sql = "SELECT p.*,
			(SELECT COUNT(*) AS total FROM " . DB_PREFIX . "comment rc
			WHERE rc.record_id = p.record_id AND rc.status = '1' GROUP BY rc.record_id) AS comments,
			(SELECT AVG(rating) AS total FROM " . DB_PREFIX . "comment r1
			WHERE r1.record_id = p.record_id AND r1.status = '1' GROUP BY r1.record_id) AS rating
			FROM " . DB_PREFIX . "record p
			LEFT JOIN " . DB_PREFIX . "record_description pd ON (p.record_id = pd.record_id)
			LEFT JOIN " . DB_PREFIX . "record_to_store p2s ON (p.record_id = p2s.record_id)";

			if (!empty($data['filter_tag'])) {
				$sql .= " LEFT JOIN " . DB_PREFIX . "record_tag pt ON (p.record_id = pt.record_id)";
			} //!empty($data['filter_tag'])
			if (!empty($data['filter_blog_id'])) {
				$sql .= " LEFT JOIN " . DB_PREFIX . "record_to_blog p2c ON (p.record_id = p2c.record_id)";
			} //!empty($data['filter_blog_id'])
			$sql .= " WHERE pd.language_id = '" . (int) $this->config->get('config_language_id') . "'
			AND p.status = '1'
 		    " . $customer_query . "
			AND NOW() BETWEEN p.date_available AND p.date_end
			AND p2s.store_id = '" . (int) $this->config->get('config_store_id') . "'";
			if (!empty($data['filter_name']) || !empty($data['filter_tag'])) {
				$sql .= " AND (";
				if (!empty($data['filter_name'])) {
					$implode = array();
					$words   = explode(' ', $data['filter_name']);
					foreach ($words as $word) {
						if (!empty($data['filter_description'])) {
							$implode[] = "LCASE(pd.name) LIKE '%" . $this->db->escape(utf8_strtolower($word)) . "%' OR LCASE(pd.description) LIKE '%" . $this->db->escape(utf8_strtolower($word)) . "%'";
						} //!empty($data['filter_description'])
						else {
							$implode[] = "LCASE(pd.name) LIKE '%" . $this->db->escape(utf8_strtolower($word)) . "%'";
						}
					} //$words as $word
					if ($implode) {
						$sql .= " " . implode(" OR ", $implode) . "";
					} //$implode
				} //!empty($data['filter_name'])
				if (!empty($data['filter_name']) && !empty($data['filter_tag'])) {
					$sql .= " OR ";
				} //!empty($data['filter_name']) && !empty($data['filter_tag'])
				if (!empty($data['filter_tag'])) {
					$implode = array();
					$words   = explode(' ', $data['filter_tag']);
					foreach ($words as $word) {
						$implode[] = "LCASE(pt.tag) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_tag'])) . "%' AND pt.language_id = '" . (int) $this->config->get('config_language_id') . "'";
					} //$words as $word
					if ($implode) {
						$sql .= " " . implode(" OR ", $implode) . "";
					} //$implode
				} //!empty($data['filter_tag'])
				$sql .= ")";
			} //!empty($data['filter_name']) || !empty($data['filter_tag'])
			if (!empty($data['filter_blog_id'])) {
				if (!empty($data['filter_sub_blog'])) {
					$implode_data   = array();
					$implode_data[] = "p2c.blog_id = '" . (int) $data['filter_blog_id'] . "'";
					$this->load->model('catalog/blog');
					$blogies = $this->model_catalog_blog->getBlogiesByParentId($data['filter_blog_id']);
					foreach ($blogies as $blog_id) {
						$implode_data[] = "p2c.blog_id = '" . (int) $blog_id . "'";
					} //$blogies as $blog_id
					$sql .= " AND (" . implode(' OR ', $implode_data) . ")";
				} //!empty($data['filter_sub_blog'])
				else {
					$sql .= " AND p2c.blog_id = '" . (int) $data['filter_blog_id'] . "'";
				}
			} //!empty($data['filter_blog_id'])
			$sql .= " GROUP BY p.record_id";
			$sort_data = array(
				'pd.name',
				'rating',
				'comments',
				'p.viewed',
				'p.sort_order',
				'p.date_added',
				'p.date_available'
			);




			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				if ($data['sort'] == 'pd.name' || $data['sort'] == 'p.model') {
					$sql .= " ORDER BY LCASE(" . $data['sort'] . ")";
				} //$data['sort'] == 'pd.name' || $data['sort'] == 'p.model'
				else {
					$sql .= " ORDER BY " . $data['sort'];
				}
			} //isset($data['sort']) && in_array($data['sort'], $sort_data)
			else {
				$sql .= " ORDER BY p.sort_order ";
			}
			if (isset($data['order']) && (strtoupper($data['order']) == 'DESC')) {
				$sql .= " DESC";
			} //isset($data['order']) && (strtoupper($data['order']) == 'DESC')
			else {
				$sql .= " ASC";
			}
			if (isset($data['start']) || isset($data['limit'])) {
				if ($data['start'] < 0) {
					$data['start'] = 0;
				} //$data['start'] < 0
				if ($data['limit'] < 1) {
					$data['limit'] = 20;
				} //$data['limit'] < 1
				$sql .= " LIMIT " . (int) $data['start'] . "," . (int) $data['limit'];
			} //isset($data['start']) || isset($data['limit'])
			$record_data = array();



			$query       = $this->db->query($sql);

//print_my($query->rows);


			foreach ($query->rows as $result) {
				$record_data[$result['record_id']] = $this->getRecord($result['record_id']);
			} //$query->rows as $result
			$this->cache->set('record.' . (int) $this->config->get('config_language_id') . '.' . (int) $this->config->get('config_store_id') . '.' . (int) $customer_group_id . '.' . $cache, $record_data);
		} //!$record_data
		return $record_data;
	}
	public function getRecordCategories($record_id)
	{
		$record_blog_data = array();
		$sql              = "SELECT * FROM " . DB_PREFIX . "record_to_blog WHERE record_id = '" . (int) $record_id . "'";
		$query            = $this->db->query($sql);
		foreach ($query->rows as $result) {
			$record_blog_data[] = $result['blog_id'];
		} //$query->rows as $result
		return $record_blog_data;
	}
	public function getLatestRecords($limit)
	{
        if (SCP_VERSION > 1 ) {
			$get_Customer_GroupId = 'getGroupId';
		} else {
			$get_Customer_GroupId = 'getCustomerGroupId';
		}
		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->$get_Customer_GroupId();
			$customer_query    = " AND (p.customer_group_id = '" . (int) $customer_group_id . "' OR p.customer_group_id = '" . (int) $this->config->get('config_customer_group_id') . "')";
		} //$this->customer->isLogged()
		else {
			$customer_group_id = $this->config->get('config_customer_group_id');
			$customer_query    = " AND p.customer_group_id = '" . (int) $customer_group_id . "'";
		}
		$record_data = $this->cache->get('record.latest.' . (int) $this->config->get('config_language_id') . '.' . (int) $this->config->get('config_store_id') . '.' . (int) $limit);
		if (!$record_data) {
			$query = $this->db->query("
			SELECT p.record_id FROM " . DB_PREFIX . "record p
			LEFT JOIN " . DB_PREFIX . "record_to_store p2s ON (p.record_id = p2s.record_id)
			WHERE p.status = '1'
			" . $customer_query . "
			AND NOW() BETWEEN p.date_available AND p.date_end
			AND p2s.store_id = '" . (int) $this->config->get('config_store_id') . "' ORDER BY p.date_added DESC LIMIT " . (int) $limit);
			foreach ($query->rows as $result) {
				$record_data[$result['record_id']] = $this->getRecord($result['record_id']);
			} //$query->rows as $result
			$this->cache->set('record.latest.' . (int) $this->config->get('config_language_id') . '.' . (int) $this->config->get('config_store_id') . '.' . (int) $limit, $record_data);
		} //!$record_data
		return $record_data;
	}
	public function getPopularRecords($limit)
	{
        if (SCP_VERSION > 1 ) {
			$get_Customer_GroupId = 'getGroupId';
		} else {
			$get_Customer_GroupId = 'getCustomerGroupId';
		}
		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->$get_Customer_GroupId();
			$customer_query    = " AND (p.customer_group_id = '" . (int) $customer_group_id . "' OR p.customer_group_id = '" . (int) $this->config->get('config_customer_group_id') . "')";
		} //$this->customer->isLogged()
		else {
			$customer_group_id = $this->config->get('config_customer_group_id');
			$customer_query    = " AND p.customer_group_id = '" . (int) $customer_group_id . "'";
		}
		$record_data = array();
		$query       = $this->db->query("
		SELECT p.record_id FROM " . DB_PREFIX . "record p
		LEFT JOIN " . DB_PREFIX . "record_to_store p2s ON (p.record_id = p2s.record_id)
		 WHERE p.status = '1'
		 " . $customer_query . "
		 AND NOW() BETWEEN p.date_available AND p.date_end
		 AND p2s.store_id = '" . (int) $this->config->get('config_store_id') . "' ORDER BY p.viewed, p.date_added DESC LIMIT " . (int) $limit);
		foreach ($query->rows as $result) {
			$record_data[$result['record_id']] = $this->getRecord($result['record_id']);
		} //$query->rows as $result
		return $record_data;
	}
	public function getBestSellerRecords($limit)
	{
        if (SCP_VERSION > 1 ) {
			$get_Customer_GroupId = 'getGroupId';
		} else {
			$get_Customer_GroupId = 'getCustomerGroupId';
		}
		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->$get_Customer_GroupId();
			$customer_query    = " AND (p.customer_group_id = '" . (int) $customer_group_id . "' OR p.customer_group_id = '" . (int) $this->config->get('config_customer_group_id') . "')";
		} //$this->customer->isLogged()
		else {
			$customer_group_id = $this->config->get('config_customer_group_id');
			$customer_query    = " AND p.customer_group_id = '" . (int) $customer_group_id . "'";
		}
		$record_data = $this->cache->get('record.bestseller.' . (int) $this->config->get('config_language_id') . '.' . (int) $this->config->get('config_store_id') . '.' . (int) $limit);
		if (!$record_data) {
			$record_data = array();
			$query       = $this->db->query("
			SELECT op.record_id, COUNT(*) AS total FROM " . DB_PREFIX . "order_record op
			LEFT JOIN `" . DB_PREFIX . "order` o ON (op.order_id = o.order_id)
			LEFT JOIN `" . DB_PREFIX . "record` p ON (op.record_id = p.record_id)
			 LEFT JOIN " . DB_PREFIX . "record_to_store p2s ON (p.record_id = p2s.record_id) WHERE o.order_status_id > '0'
			 AND p.status = '1'
			 " . $customer_query . "
			 AND NOW() BETWEEN p.date_available AND p.date_end
			 AND p2s.store_id = '" . (int) $this->config->get('config_store_id') . "' GROUP BY op.record_id ORDER BY total DESC LIMIT " . (int) $limit);
			foreach ($query->rows as $result) {
				$record_data[$result['record_id']] = $this->getRecord($result['record_id']);
			} //$query->rows as $result
			$this->cache->set('record.bestseller.' . (int) $this->config->get('config_language_id') . '.' . (int) $this->config->get('config_store_id') . '.' . (int) $limit, $record_data);
		} //!$record_data
		return $record_data;
	}
	public function getRecordAttributes($record_id)
	{
		$record_attribute_group_data  = array();
		$record_attribute_group_query = $this->db->query("
		SELECT ag.attribute_group_id, agd.name FROM " . DB_PREFIX . "record_attribute pa
		LEFT JOIN " . DB_PREFIX . "attribute a ON (pa.attribute_id = a.attribute_id)
		LEFT JOIN " . DB_PREFIX . "attribute_group ag ON (a.attribute_group_id = ag.attribute_group_id)
		LEFT JOIN " . DB_PREFIX . "attribute_group_description agd ON (ag.attribute_group_id = agd.attribute_group_id)
		WHERE pa.record_id = '" . (int) $record_id . "' AND agd.language_id = '" . (int) $this->config->get('config_language_id') . "'
		GROUP BY ag.attribute_group_id ORDER BY ag.sort_order, agd.name");
		foreach ($record_attribute_group_query->rows as $record_attribute_group) {
			$record_attribute_data  = array();
			$record_attribute_query = $this->db->query("
			SELECT a.attribute_id, ad.name, pa.text FROM " . DB_PREFIX . "record_attribute pa
			LEFT JOIN " . DB_PREFIX . "attribute a ON (pa.attribute_id = a.attribute_id)
			LEFT JOIN " . DB_PREFIX . "attribute_description ad ON (a.attribute_id = ad.attribute_id)
			WHERE pa.record_id = '" . (int) $record_id . "'
			AND a.attribute_group_id = '" . (int) $record_attribute_group['attribute_group_id'] . "'
			AND ad.language_id = '" . (int) $this->config->get('config_language_id') . "'
			AND pa.language_id = '" . (int) $this->config->get('config_language_id') . "'
			ORDER BY a.sort_order, ad.name");
			foreach ($record_attribute_query->rows as $record_attribute) {
				$record_attribute_data[] = array(
					'attribute_id' => $record_attribute['attribute_id'],
					'name' => $record_attribute['name'],
					'text' => $record_attribute['text']
				);
			} //$record_attribute_query->rows as $record_attribute
			$record_attribute_group_data[] = array(
				'attribute_group_id' => $record_attribute_group['attribute_group_id'],
				'name' => $record_attribute_group['name'],
				'attribute' => $record_attribute_data
			);
		} //$record_attribute_group_query->rows as $record_attribute_group
		return $record_attribute_group_data;
	}
	public function getRecordImages($record_id)
	{
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "record_image WHERE record_id = '" . (int) $record_id . "' ORDER BY sort_order ASC");
		return $query->rows;
	}
	public function getRecordRelated($record_id)
	{
        if (SCP_VERSION > 1 ) {
			$get_Customer_GroupId = 'getGroupId';
		} else {
			$get_Customer_GroupId = 'getCustomerGroupId';
		}
		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->$get_Customer_GroupId();
			$customer_query    = " AND (p.customer_group_id = '" . (int) $customer_group_id . "' OR p.customer_group_id = '" . (int) $this->config->get('config_customer_group_id') . "')";
		} //$this->customer->isLogged()
		else {
			$customer_group_id = $this->config->get('config_customer_group_id');
			$customer_query    = " AND p.customer_group_id = '" . (int) $customer_group_id . "'";
		}
		$record_data = array();
		$sql         = "SELECT * FROM " . DB_PREFIX . "record_related pr
		LEFT JOIN " . DB_PREFIX . "record p ON (pr.related_id = p.record_id)
		LEFT JOIN " . DB_PREFIX . "record_to_store p2s ON (p.record_id = p2s.record_id)

		 WHERE pr.pointer_id = '" . (int) $record_id . "' AND pointer='record_id'
		 AND p.status = '1'
		 " . $customer_query . "
		 AND NOW() BETWEEN p.date_available AND p.date_end
		 AND p2s.store_id = '" . (int) $this->config->get('config_store_id') . "'";
		$query       = $this->db->query($sql);
		foreach ($query->rows as $result) {
			$record_data[$result['related_id']] = $this->getRecord($result['related_id']);
		}

		/*
		$sql         = "SELECT * FROM " . DB_PREFIX . "record_related pr
		LEFT JOIN " . DB_PREFIX . "record p ON (pr.record_id = p.record_id)
		LEFT JOIN " . DB_PREFIX . "record_to_store p2s ON (p.record_id = p2s.record_id)

		 WHERE pr.related_id = '" . (int) $record_id . "' AND pointer='record_id'
		 AND p.status = '1'
		 " . $customer_query . "
		 AND NOW() BETWEEN p.date_available AND p.date_end
		 AND p2s.store_id = '" . (int) $this->config->get('config_store_id') . "'";
		$query       = $this->db->query($sql);
		foreach ($query->rows as $result) {
			$record_data[$result['record_id']] = $this->getRecord($result['record_id']);
		}
        */




		return $record_data;
	}
	public function getProduct($product_id)
	{
        if (SCP_VERSION > 1 ) {
			$get_Customer_GroupId = 'getGroupId';
		} else {
			$get_Customer_GroupId = 'getCustomerGroupId';
		}
		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->$get_Customer_GroupId();
			$customer_query    = " AND (p.customer_group_id = '" . (int) $customer_group_id . "' OR p.customer_group_id = '" . (int) $this->config->get('config_customer_group_id') . "')";
		} //$this->customer->isLogged()
		else {
			$customer_group_id = $this->config->get('config_customer_group_id');
			$customer_query    = " AND p.customer_group_id = '" . (int) $customer_group_id . "'";
		}
		$query = $this->db->query("
		SELECT DISTINCT *, pd.name AS name, p.image, m.name AS manufacturer,
		(SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int) $customer_group_id . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW()))
		ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special, (SELECT points FROM " . DB_PREFIX . "product_reward pr WHERE pr.product_id = p.product_id AND customer_group_id = '" . (int) $customer_group_id . "') AS reward, (SELECT ss.name FROM " . DB_PREFIX . "stock_status ss WHERE ss.stock_status_id = p.stock_status_id AND ss.language_id = '" . (int) $this->config->get('config_language_id') . "') AS stock_status, (SELECT wcd.unit FROM " . DB_PREFIX . "weight_class_description wcd WHERE p.weight_class_id = wcd.weight_class_id AND wcd.language_id = '" . (int) $this->config->get('config_language_id') . "') AS weight_class,
		(SELECT lcd.unit FROM " . DB_PREFIX . "length_class_description lcd WHERE p.length_class_id = lcd.length_class_id AND lcd.language_id = '" . (int) $this->config->get('config_language_id') . "') AS length_class, (SELECT AVG(rating) AS total FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = p.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating, (SELECT COUNT(*) AS total FROM " . DB_PREFIX . "review r2 WHERE r2.product_id = p.product_id AND r2.status = '1' GROUP BY r2.product_id) AS reviews, p.sort_order
		FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id)
		LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) LEFT JOIN " . DB_PREFIX . "manufacturer m ON (p.manufacturer_id = m.manufacturer_id) WHERE p.product_id = '" . (int) $product_id . "' AND pd.language_id = '" . (int) $this->config->get('config_language_id') . "'
		AND p.status = '1'
		AND p.date_available <= NOW()
		AND p2s.store_id = '" . (int) $this->config->get('config_store_id') . "'");
		if ($query->num_rows) {
			$query->row['price']  = $query->row['price'];
			$query->row['rating'] = round ($query->row['rating']);
			return $query->row;
		} //$query->num_rows
		else {
			return false;
		}
	}
	public function getProductRelated($record_id, $pointer = 'product_id')
	{
		$product_data = array();

		 $sql = "SELECT *
		FROM " . DB_PREFIX . "record_related pr
		LEFT JOIN " . DB_PREFIX . "product p ON (pr.related_id = p.product_id)
		LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id)
		WHERE pr.pointer_id = '" . (int) $record_id . "'
		AND p.status = '1' AND p.date_available <= NOW() AND pr.pointer='".$pointer."'
		AND p2s.store_id = '" . (int) $this->config->get('config_store_id') . "'";


		$query        = $this->db->query($sql);
		foreach ($query->rows as $result) {
			$product_data[$result['product_id']] = $this->getProduct($result['product_id']);
		} //$query->rows as $result
		return $product_data;
	}
	public function getRecordTags($record_id)
	{
		$query = $this->db->query("
		SELECT * FROM " . DB_PREFIX . "record_tag WHERE record_id = '" . (int) $record_id . "' AND language_id = '" . (int) $this->config->get('config_language_id') . "'");
		return $query->rows;
	}
	public function getRecordLayoutId($record_id)
	{
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "record_to_layout WHERE record_id = '" . (int) $record_id . "' AND store_id = '" . (int) $this->config->get('config_store_id') . "'");
		if ($query->num_rows) {
			return $query->row['layout_id'];
		} //$query->num_rows
		else {
			return $this->config->get('config_layout_record');
		}
	}
	public function getBlogies($record_id)
	{
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "record_to_blog WHERE record_id = '" . (int) $record_id . "'");
		return $query->rows;
	}
	public function getTotalRecords($data = array())
	{
        if (SCP_VERSION > 1 ) {
			$get_Customer_GroupId = 'getGroupId';
		} else {
			$get_Customer_GroupId = 'getCustomerGroupId';
		}
		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->$get_Customer_GroupId();
			$customer_query    = " AND (p.customer_group_id = '" . (int) $customer_group_id . "' OR p.customer_group_id = '" . (int) $this->config->get('config_customer_group_id') . "')";
		} //$this->customer->isLogged()
		else {
			$customer_group_id = $this->config->get('config_customer_group_id');
			$customer_query    = " AND p.customer_group_id = '" . (int) $customer_group_id . "'";
		}
		$sql = "SELECT COUNT(DISTINCT p.record_id) AS total FROM " . DB_PREFIX . "record p
		LEFT JOIN " . DB_PREFIX . "record_description pd ON (p.record_id = pd.record_id)
		LEFT JOIN " . DB_PREFIX . "record_to_store p2s ON (p.record_id = p2s.record_id)";
		if (!empty($data['filter_blog_id'])) {
			$sql .= " LEFT JOIN " . DB_PREFIX . "record_to_blog p2c ON (p.record_id = p2c.record_id)
					  LEFT JOIN " . DB_PREFIX . "blog pb ON (p2c.blog_id = pb.blog_id)
			";
		} //!empty($data['filter_blog_id'])
		if (!empty($data['filter_tag'])) {
			$sql .= " LEFT JOIN " . DB_PREFIX . "record_tag pt ON (p.record_id = pt.record_id)";
		} //!empty($data['filter_tag'])
		$sql .= " WHERE pd.language_id = '" . (int) $this->config->get('config_language_id') . "'
		AND p.status = '1'
		" . $customer_query . "
		AND NOW() BETWEEN p.date_available AND p.date_end
		AND p2s.store_id = '" . (int) $this->config->get('config_store_id') . "'";
		if (!empty($data['filter_name']) || !empty($data['filter_tag'])) {
			$sql .= " AND (";
			if (!empty($data['filter_name'])) {
				$implode = array();
				$words   = explode(' ', $data['filter_name']);
				foreach ($words as $word) {
					if (!empty($data['filter_description'])) {
						$implode[] = "LCASE(pd.name) LIKE '%" . $this->db->escape(utf8_strtolower($word)) . "%' OR LCASE(pd.description) LIKE '%" . $this->db->escape(utf8_strtolower($word)) . "%'";
					} //!empty($data['filter_description'])
					else {
						$implode[] = "LCASE(pd.name) LIKE '%" . $this->db->escape(utf8_strtolower($word)) . "%'";
					}
				} //$words as $word
				if ($implode) {
					$sql .= " " . implode(" OR ", $implode) . "";
				} //$implode
			} //!empty($data['filter_name'])
			if (!empty($data['filter_name']) && !empty($data['filter_tag'])) {
				$sql .= " OR ";
			} //!empty($data['filter_name']) && !empty($data['filter_tag'])
			if (!empty($data['filter_tag'])) {
				$implode = array();
				$words   = explode(' ', $data['filter_tag']);
				foreach ($words as $word) {
					$implode[] = "LCASE(pt.tag) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_tag'])) . "%' AND pt.language_id = '" . (int) $this->config->get('config_language_id') . "'";
				} //$words as $word
				if ($implode) {
					$sql .= " " . implode(" OR ", $implode) . "";
				} //$implode
			} //!empty($data['filter_tag'])
			$sql .= ")";
		} //!empty($data['filter_name']) || !empty($data['filter_tag'])
		if (!empty($data['filter_blog_id'])) {
			if (!empty($data['filter_sub_blog'])) {
				$implode_data   = array();
				$implode_data[] = "p2c.blog_id = '" . (int) $data['filter_blog_id'] . "'";
				$this->load->model('catalog/blog');
				$blogies = $this->model_catalog_blog->getBlogiesByParentId($data['filter_blog_id']);
				foreach ($blogies as $blog_id) {
					$implode_data[] = "p2c.blog_id = '" . (int) $blog_id . "'";
				} //$blogies as $blog_id
				$sql .= " AND (" . implode(' OR ', $implode_data) . ")";
			} //!empty($data['filter_sub_blog'])
			else {
				$sql .= " AND p2c.blog_id = '" . (int) $data['filter_blog_id'] . "'";
			}
		} //!empty($data['filter_blog_id'])
		if (!empty($data['filter_manufacturer_id'])) {
			$sql .= " AND p.manufacturer_id = '" . (int) $data['filter_manufacturer_id'] . "'";
		} //!empty($data['filter_manufacturer_id'])
		$query = $this->db->query($sql);
		return $query->row['total'];
	}
	public function getBlogsRecords($data = array())
	{
        if (SCP_VERSION > 1 ) {
			$get_Customer_GroupId = 'getGroupId';
		} else {
			$get_Customer_GroupId = 'getCustomerGroupId';
		}
		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->$get_Customer_GroupId();
			$customer_query    = " AND (p.customer_group_id = '" . (int) $customer_group_id . "' OR p.customer_group_id = '" . (int) $this->config->get('config_customer_group_id') . "')";
		} //$this->customer->isLogged()
		else {
			$customer_group_id = $this->config->get('config_customer_group_id');
			$customer_query    = " AND p.customer_group_id = '" . (int) $customer_group_id . "'";
		}
        if (isset($data['pagination']) && $data['pagination']) {
			 $calc_rows = "SQL_CALC_FOUND_ROWS";
		 } else {
		  	 $calc_rows = "";
		 }

		$cache       = md5(http_build_query($data));
		$record_data = $this->cache->get('blogsrecord.' . (int) $this->config->get('config_language_id') . '.' . (int) $this->config->get('config_store_id') . '.' . (int) $customer_group_id . '.' . $cache);

		if (!$record_data || $data['sort'] == 'rand') {
			$sql = "SELECT ".$calc_rows." p.*,
			(SELECT COUNT(*) AS total FROM " . DB_PREFIX . "comment rc
			WHERE rc.record_id = p.record_id AND rc.status = '1' GROUP BY rc.record_id) AS comments,
			(SELECT AVG(rating) AS total FROM " . DB_PREFIX . "comment r1
			WHERE r1.record_id = p.record_id AND r1.status = '1' GROUP BY r1.record_id) AS rating
			FROM " . DB_PREFIX . "record p
			LEFT JOIN " . DB_PREFIX . "record_to_blog r2b ON (p.record_id = r2b.record_id AND p.status = 1)
			LEFT JOIN " . DB_PREFIX . "record_description pd ON (p.record_id = pd.record_id)
			LEFT JOIN " . DB_PREFIX . "record_to_store p2s ON (p.record_id = p2s.record_id)";
			$sql .= " WHERE
			pd.language_id = '" . (int) $this->config->get('config_language_id') . "' ". $customer_query." ";
			if (!empty($data['filter_blogs'])) {
				$sql .= " AND (";
				$i = 0;
				foreach ($data['filter_blogs'] as $num => $blog_id) {
					$sql .= " r2b.blog_id='" . (int)$blog_id . "' ";
					if ($i != count($data['filter_blogs']) - 1) {
						$sql .= " OR ";
					} //$i != count($data['filter_blogs']) - 1
					$i++;
				} //$data['filter_blogs'] as $num => $blog_id
			} //!empty($data['filter_blogs'])
			$sql .= ") AND p.status = '1'
			AND NOW() BETWEEN p.date_available AND p.date_end
			AND p2s.store_id = '" . (int) $this->config->get('config_store_id') . "'";
			$sql .= " GROUP BY p.record_id";
			$sort_data = array(
				'rating',
				'comments',
				'popular',
				'latest',
				'sort',
				'rand'
			);
			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				if ($data['sort'] == 'rating') {
					$data['sort'] = 'rating';
				} //$data['sort'] == 'rating'
				if ($data['sort'] == 'comments') {
					$data['sort'] = 'comments';
				} //$data['sort'] == 'comments'
				if ($data['sort'] == 'latest') {
					$data['sort'] = 'p.date_available';
				} //$data['sort'] == 'latest'
				if ($data['sort'] == 'sort') {
					$data['sort'] = 'p.sort_order';
				} //$data['sort'] == 'sort'
				if ($data['sort'] == 'popular') {
					$data['sort'] = 'p.viewed';
				} //$data['sort'] == 'popular'
				if ($data['sort'] == 'rand') {
					$data['sort'] = 'RAND()';
				} //$data['sort'] == 'latest'



				$sql .= " ORDER BY " . $data['sort'];
			} //isset($data['sort']) && in_array($data['sort'], $sort_data)
			else {
				$sql .= " ORDER BY p.sort_order";
			}
			if (isset($data['order']) && (strtoupper($data['order']) == 'DESC')) {
				if ($data['sort']!= 'rand') {
					$sql .= " DESC";
				}
			} //isset($data['order']) && (strtoupper($data['order']) == 'DESC')
			else {
				if ($data['sort']!= 'rand') {
					$sql .= " ASC";
				}
			}
            if (isset($data['start']['start'])) {
            	$data['start'] = $data['start']['start'];
            } else {
               $data['start'] = 0;
            }


			if (isset($data['start']) || isset($data['limit'])) {
				if ($data['start'] < 0) {
					$data['start'] = 0;
				}
				if ($data['limit'] < 1) {
					$data['limit'] = 20;
				}
				if ($data['sort']!= 'rand') {
 					$sql .= " LIMIT " . (int) $data['start'] . "," . (int) $data['limit'];
 				} else {
 				    $sql .= " LIMIT " . (int) $data['limit'];
 				}
			} //isset($data['start']) || isset($data['limit'])
			$record_data = array();
   /*
    print_r("<PRE>");
	print_r($data);
	print_r("</PRE>");


    print_r("<PRE>");
	print_r($sql);
	print_r("</PRE>");
     */
            $total = false;
			$query       = $this->db->query($sql);

			if (isset($data['pagination']) && $data['pagination']) {
				$query_total = $this->db->query("SELECT FOUND_ROWS() as total");
				$total  = $query_total->row['total'];
			 }



			foreach ($query->rows as $result) {
				$record_data[$result['record_id']] = $this->getRecord($result['record_id']);
                 if (count($record_data[$result['record_id']]) > 0 ) {
				 	$record_data[$result['record_id']]['total'] = $total;
				 }
			}

			$this->cache->set('blogsrecord.' . (int) $this->config->get('config_language_id') . '.' . (int) $this->config->get('config_store_id') . '.' . (int) $customer_group_id . '.' . $cache, $record_data);
		} //!$record_data

		return $record_data;
	}

	 public function getFoundRows() {
	  $query = $this->db->query("SELECT FOUND_ROWS() as total");
	  $total  = $query->row['total'];
	  return $total;
	}

}
?>