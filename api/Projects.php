<?php 

require_once dirname(__DIR__).'/api/Cinelab.php';

class Projects extends Cinelab
{
	
	function __construct()
	{
		
	}

	public function get_projects($filter = array(), $limit_start = 0, $limit = 8)
	{
		$department_filter = '';
		$year_filter = '';
		$limit_filter = '';

		if (!empty($filter['department'])) {
			$dep = $filter['department'];
			$department_filter = "AND d.title = '$dep' ";
		}

		if (!empty($filter['year'])) {
			$year = $filter['year'];
			$year_filter = "AND p.year = $year ";
		}

		if (isset($limit_start)) {
			if ($limit == 'full') {
				$limit_filter = '';
			}else
			{
				$limit_filter = "LIMIT $limit_start, $limit ";
			}
			
		}

	    $query = "SELECT DISTINCT
	        p.title, p.director, p.dop, p.company, p.cinelab, p.year, p.poster
	    FROM
	        c_projects p
	            INNER JOIN
	        c_projects_department pd ON p.id = pd.project_id
	            INNER JOIN c_departments d ON d.id = pd.department_id
			WHERE 1 
			$department_filter 
			$year_filter 
			$limit_filter
	        ";

		$sthm = $this->db->prepare($query);
		$sthm->execute();
		return $projects = $sthm->fetchAll();
	}
}