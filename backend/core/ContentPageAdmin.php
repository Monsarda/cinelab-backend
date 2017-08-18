<?php 

require_once('Admin.php');

class ContentPageAdmin extends Admin
{
	
	private $subject;
	private $message = array();
	private $page;
	private $menu_id;

	function __construct()
	{
		parent::__construct();

		$this->subject = $this->request->get('subject');
		$this->menu_id = $this->request->get('menu_id');
		$this->page = strtolower(substr($this->request->get('subject'), 0,-4));

		if (!isset($this->subject)) {
			$this->subject = 'IndexPage';
			header('Location: /backend/index.php?module=ContentPageAdmin&subject=IndexPage&menu_id=1');
		}
		if (!isset($this->menu_id)) {
			$this->menu_id = 1;
			header('Location: /backend/index.php?module=ContentPageAdmin&subject=IndexPage&menu_id=1');
		}

	}

	public function execute()
	{

		if ($this->request->method('post')) {

			$data = $this->request->post();


			$page_name = $meta_title = $data['info']['name'];

			$meta_title = $data['meta']['meta_title'];
			$meta_description = $data['meta']['meta_description'];
			$meta_keywords = $data['meta']['meta_keywords'];

			$meta_query = "UPDATE c_pages p
					
				SET 
					p.name = :name,
					p.meta_title = :title,
					p.meta_description = :description,
					p.meta_keywords = :keywords

				WHERE
					p.url = :url";

			$sthm = $this->db->prepare($meta_query);

			$result_meta = $sthm->execute(
				array(
					':url' => $this->page,
					':name' => $page_name,
					':title' => $meta_title,
					':description' => $meta_description,
					':keywords' => $meta_keywords,

				)
			);

			$content_query = "UPDATE c_page_contents pc 
				SET 
				    pc.block_title = :block_title,
				    pc.block_text = :block_text,
				    pc.block_list = :block_list
				WHERE
				    pc.id = :id";

			$sthm = $this->db->prepare($content_query);


			if (isset($data['content'])) {
			
				foreach ($data['content'] as $key => $value) {
					
					$block_title = $value['block_title'] ?? '';
					$block_text = $value['block_text'] ?? '';
					$block_list = $value['block_list'] ?? '';

					$result_content = $sthm->execute(
						array(
							':id' => $key,
							':block_title' => $block_title,
							':block_text' => $block_text,
							':block_list' => $block_list,
						)
					);
				}

				if($result_content) 
				{
					$this->message = array('success' => true, 'error' => null);
				}else{
					$this->message = array('success' => null, 'error' => true);
				}
			}else 
			{
				
				if($result_meta) 
				{
					$this->message = array('success' => true, 'error' => null);
				}else{
					$this->message = array('success' => null, 'error' => true);
				}
			}

		}

		$page_info = $this->pages->get_page($this->page);
		$page_content = $this->pages->get_content(array('page'=>$this->page));

		return $this->design->render('@admin/'.$this->module.'.twig', array(
			'page_id' => $page_info,
			'page' => $this->page,
			'subject' => $this->subject,
			'menu_id' => $this->menu_id,
			'page_info' => $page_info,
			'page_content' => $page_content,
			'message' => $this->message,
		));

		
	}
}