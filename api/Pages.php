<?php 

require_once dirname(__DIR__).'/api/Cinelab.php';

class Pages extends Cinelab
{
	
	function __construct()
	{
		# code...
	}

	// получить конкретную страницу
	public function get_page($id)
	{

		$selector = null;

		if (strval($id)) {
			$selector = 'url';
		}elseif (intval($id)) {
			$selector = 'id';
		}else
		{
			return null;
		}

		$query = "SELECT * FROM c_pages WHERE $selector = :id";
		$sthm = $this->db->prepare($query);
		$sthm->execute(array('id'=>$id));
		return $page = $sthm->fetch();

	}

	// получить список страниц по фильтру
	public function get_pages($filter = array())
	{

		$visible_filter = '';
		$menu_filter = '';

		if (!empty($filter['visible'])) {
			$visible_filter = "AND visible = ".$filter['visible'];
		}

		if (!empty($filter['menu_id'])) {
			$menu_filter = "AND menu_id = ".$filter['menu_id'];
		}

		$query = "SELECT * FROM c_pages WHERE 1 $visible_filter $menu_filter";
		$sthm = $this->db->prepare($query);
		$sthm->execute();
		return $pages = $sthm->fetchAll();

	}

	public function get_content($filter = array())
	{
		$page_filter = '';

		if (isset($page_filter)) {
			$page_filter = "AND p.url = '".$filter['page']."'";
		}
		$query = "SELECT pc.id, pc.block_title, pc.block_text, pc.block_list, pc.position FROM c_pages p INNER JOIN c_page_contents pc ON p.id = pc.page_id WHERE 1 $page_filter";
		$sthm = $this->db->prepare($query);
		$sthm->execute();
		return $pages = $sthm->fetchAll();
	}



}