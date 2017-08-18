<?php 

require_once dirname(__DIR__).'/api/Cinelab.php';

class Database extends Cinelab
{
	public $pdo;
	private $db_server;
	private $db_user;
	private $db_password;
	private $db_name;

	public function __construct()
	{
		$this->db_server = $this->config->db_server;
		$this->db_user = $this->config->db_user;
		$this->db_password = $this->config->db_password;
		$this->db_name = $this->config->db_name;

		$this->pdo = new PDO('mysql:host='.$this->db_server.';dbname='.$this->db_name, $this->db_user, $this->db_password, array(
			PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
		));
		$this->pdo->query('SET NAMES '.$this->config->db_charset);
		$this->pdo->query("SET sql_mode = ''");
	}

	public function query($query)
	{
		return $this->pdo->query($query);
	}

	public function prepare($query)
	{
		return $this->pdo->prepare($query);
	}
}
