<?php
/**
 * Модель module/homecards.
 */
class ModelModuleHomecards extends Model {
	/**
	 * Минимальная цена товара в указанной категории
	 * @param  integer $category_id Категория для поиска минимальной цены
	 * @return float                Минимальная цена или $this->getCategoryPriceRecursive()
	 */
	public function getCategoryPrice($category_id) {
		$query = $this->db->query("
			SELECT LEAST(p.price,IFNULL(ps.price, p.price)) minimum_price 
			FROM `" . DB_PREFIX . "category` c
			RIGHT JOIN `" . DB_PREFIX . "product_to_category` p2c ON p2c.category_id = c.category_id
			RIGHT JOIN `" . DB_PREFIX . "product` p ON p2c.product_id = p.product_id
			LEFT JOIN `" . DB_PREFIX . "product_special` ps ON p.product_id = ps.product_id AND ps.date_end >= NOW() AND ps.date_start <= NOW()
			WHERE c.category_id = " . (int)$category_id . " AND p.status = 1 AND c.status = 1
			ORDER BY minimum_price
			LIMIT 0,1
		");
		
		return $query->num_rows ? $query->row['minimum_price'] : $this->getCategoryPriceRecursive($category_id);
	}
	
	/**
	 * Минимальная цена товара в указанной категории (включая все подкатегории)
	 * @param  integer $category_id Категория для поиска минимальной цены
	 * @return float                Минимальная цена или ноль
	 */
	public function getCategoryPriceRecursive($category_id) {
		$categories = $this->getCategoriesRecursive($category_id);
		$categories[] = (int)$category_id;
		$categories = array_map('intval', $categories);
		
		if ($categories) {
			$query = $this->db->query("
				SELECT LEAST(p.price,IFNULL(ps.price, p.price)) minimum_price 
				FROM `" . DB_PREFIX . "category` c
				RIGHT JOIN `" . DB_PREFIX . "product_to_category` p2c ON p2c.category_id = c.category_id
				RIGHT JOIN `" . DB_PREFIX . "product` p ON p2c.product_id = p.product_id
				LEFT JOIN `" . DB_PREFIX . "product_special` ps ON p.product_id = ps.product_id AND ps.date_end >= NOW() AND ps.date_start <= NOW()
				WHERE c.category_id IN (" . implode(',', $categories) . ") AND p.status = 1 AND c.status = 1
				ORDER BY minimum_price
				LIMIT 0,1
			");
			
			return $query->num_rows ? $query->row['minimum_price'] : 0;
		}
		
		return 0;
	}
	
	/**
	 * Товары со скидкой для заданной категории и её дочерних/внучатых/пр. Возвращаются случайные записи
	 * @param  integer $category_id Идентификатор категории
	 * @param  integer $limit       Количество записей
	 * @return array                Массив со списком записей
	 */
	public function getCategorySpecial($category_id, $limit = 1) {
		$categories = $this->getCategoriesRecursive($category_id);
		$categories[] = (int)$category_id;
		$categories = array_map('intval', $categories);
		
		if ($categories) {
			$query = $this->db->query("
				SELECT 
					p.product_id,
					p.price,
					ps.price special,
					pd.name
				FROM `" . DB_PREFIX . "category` c 
				RIGHT JOIN `" . DB_PREFIX . "product_to_category` p2c ON p2c.category_id = c.category_id 
				RIGHT JOIN `" . DB_PREFIX . "product` p ON p2c.product_id = p.product_id
				LEFT JOIN `" . DB_PREFIX . "product_special` ps ON p.product_id = ps.product_id AND ps.date_end >= NOW() AND ps.date_start <= NOW()
				LEFT JOIN `" . DB_PREFIX . "product_description` pd ON 
					p.product_id = pd.product_id AND 
					pd.language_id = " . (int)$this->config->get('config_language_id') . "
				WHERE 
					c.category_id IN (" . implode(',', $categories) . ") AND 
					p.status = 1 AND 
					c.status = 1 AND 
					ps.price IS NOT NULL 
				GROUP BY p.product_id 
				ORDER BY RAND() 
				LIMIT " . (int)$limit . "
			");
			
			return $query->num_rows ? $query->rows : array();
		}
		
		return array();
	}
	
	/**
	 * Последние добавленные опубликованные продукты в опубликованных категориях
	 * @param  integer $limit Количество возвращаемых записей
	 * @return array          Массив со списком записей
	 */
	public function getNewestProducts($category_id, $limit = 1) {
		$categories = $this->getCategoriesRecursive($category_id);
		$categories[] = (int)$category_id;
		$categories = array_map('intval', $categories);
		
		$query = $this->db->query("
			SELECT 
				p.product_id,
				p.price,
				ps.price special,
				pd.name
			FROM `" . DB_PREFIX . "product` p
			LEFT JOIN `" . DB_PREFIX . "product_to_category` p2c ON p2c.product_id = p.product_id
			LEFT JOIN `" . DB_PREFIX . "category` c ON c.category_id = p2c.category_id
			LEFT JOIN `" . DB_PREFIX . "product_special` ps ON ps.product_id = p.product_id AND ps.date_end >= NOW() AND ps.date_start <= NOW()
			LEFT JOIN `" . DB_PREFIX . "product_description` pd ON 
				pd.product_id = p.product_id AND 
				pd.language_id = " . (int)$this->config->get('config_language_id') . "
			WHERE 
				c.category_id IN (" . implode(',', $categories) . ") AND 
				p.status = 1 AND 
				c.status = 1 
			GROUP BY p.product_id 
			ORDER BY p.date_added DESC
			LIMIT " . (int)$limit . "
		");
		
		return $query->rows;
	}
	
	
	/**
	 * Список дочерних категорий
	 * @param  integer $category_id Идентификатор родительнской категории
	 * @return array                Массив с данными дочерних категорий
	 */
	public function getCategories($category_id = 0) {
		$query = $this->db->query("
			SELECT * 
			FROM " . DB_PREFIX . "category c 
			LEFT JOIN " . DB_PREFIX . "category_description cd ON c.category_id = cd.category_id 
			LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON c.category_id = c2s.category_id 
			WHERE 
				c.parent_id = '" . (int)$category_id . "' AND 
				cd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND 
				c2s.store_id = '" . (int)$this->config->get('config_store_id') . "'  AND 
				c.status = '1' 
			ORDER BY c.sort_order, LCASE(cd.name)
		");

		return $query->rows;
	}
	
	/**
	 * Все дочерние, внучатые и пр. категории для текущей категории. Применяется рекурсия.
	 * @param  integer $category_id Идентификатор категории для поиска
	 * @return array                Массив с идентификаторами дочерних категорий
	 */
	public function getCategoriesRecursive($category_id) {
		$category_data = array();

		$categories = $this->getCategories((int)$category_id);

		foreach ($categories as $category) {
			$category_data[] = $category['category_id'];

			$children = $this->getCategoriesRecursive($category['category_id']);

			if ($children) {
				$category_data = array_merge($children, $category_data);
			}
		}

		return $category_data;
	}
}