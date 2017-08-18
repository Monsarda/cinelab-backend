<?php 

require_once dirname(__DIR__).'/api/Cinelab.php';

class Voices extends Cinelab
{
	
	function __construct()
	{
		parent::__construct();
	}

	public function get_voices($filter = array())
	{
		$sort_filter = '';
		$gender_filter = '';
		$age_filter = '';
		$num = '';
		$marks = array(); // смешиваем запрос
		$age_array = array();
		$gender_array = array();


		$sort_filter = !empty($filter['sort']) ? $filter['sort'] : 'ASC';

		if(!empty($filter['gender']))
		{
			$gender_array = $_POST['gender'];
			$marksGender = str_repeat('?,', count($gender_array) - 1) . '?';
			$gender_filter = "AND a.gender IN ($marksGender)";

		}

		if(!empty($filter['age']))
		{
			$age_array = $_POST['age'];
			$marksAge = str_repeat('?,', count($age_array) - 1) . '?';
			$age_filter = "AND a.age_category IN ($marksAge)";

		}

		$marks = array_merge($gender_array, $age_array);

		$query = "SELECT DISTINCT
	        tmp.*,  GROUP_CONCAT(t.name) as tracks, t.link
	    FROM
	        (SELECT 
	            a.lang, a.id AS author_id, a.name, a.gender, a.age_category, c.category, c.id as category_id
	        FROM
	            c_voice_author a
	        INNER JOIN c_voice_category c
	        WHERE 1 $gender_filter $age_filter) AS tmp
	            LEFT JOIN
	        c_voice_tracks t ON tmp.author_id = t.autor_id AND t.category_id = tmp.category_id
	    GROUP BY tmp.author_id, tmp.category_id
	    ORDER BY author_id $sort_filter";

		$sthm = $this->db->prepare($query);
		$sthm->execute($marks);

		$voices = $sthm->fetchAll();

		foreach ($voices as $key => $value) {

			if($value['author_id'] <= 9) 
			{
				$num = '000'.$value['author_id'];

			}elseif ($value['author_id'] <= 99) {
				$num = '00'.$value['author_id'];

			}elseif ($value['author_id'] <= 999) {
				$num = '0'.$value['author_id'];
			}else
			{
				$num = $value['author_id'];
			}

			$voices[$key]['alt_name'] = explode(' ', $value['name'])[1].'_'.$num;
		}

		return $voices;
	}

	
}