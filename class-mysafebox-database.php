<?php
/**
 * Manage MySafeBox database.
 *
 * @author Nahim SALAMI <nahim.salami@outlook.fr>
 * @link  http://app.MySafeBox/
 * @since 1.0.0
 *
 * @package    MySafeBox
 * @subpackage MySafeBox/Database
 */
class MySafeBoxDb extends MySafeBoxTable {

	/**
	 * Le nom d'utilisateur du sgbd.
	 *
	 * @var string
	 */
	private $username;

	/**
	 * Le nom de la base de données.
	 *
	 * @var string
	 */
	private $db_name;

	/**
	 * L'adresse de la base de données
	 *
	 * @var string
	 */
	private $host;

	/**
	 * Le mot de passe du sgbd.
	 *
	 * @var string
	 */
	private $password;

	/**
	 * L'objet PDO initialiser avec les informations de la base de données.
	 *
	 * @var PDO
	 */
	private $db;

	public function setDb( $db ) {
		parent::setDb( $db );
		$this->db = $db;
	}

	public function setUsername( $username ) {
		$this->username = filter_var( $username );
	}

	public function setDbName( $db_name ) {
		 $this->db_name = filter_var( $db_name );
	}

	public function setHost( $host ) {
		$this->host = filter_var( $host );
	}

	public function setPassword( $password ) {
		$this->password = filter_var( $password );
	}

	public function getUsername() {
		 return $this->username;
	}

	public function getDbName() {
		return $this->db_name;
	}

	public function getHost() {
		 return $this->host;
	}

	public function getPassword() {
		 return $this->password;
	}

	public function getDb() {
		return $this->db;
	}

	public function __construct(
		$host = DATABASE_HOST,
		$username = DATABASE_USERNAME,
		$password = DATABASE_PASSWORD,
		$db_name = DATABASE_NAME
	) {
		$this->setHost( filter_var( $host ) );
		$this->setPassword( filter_var( $password ) );
		$this->setDbName( filter_var( $db_name ) );
		$this->setUsername( filter_var( $username ) );
	}

	/**
	 * Crée la base de donnée si elle n'existe pas.
	 *
	 * @param string $db_name
	 * @return void
	 */
	private function createDb( $db_name ) {
		 $request = 'CREATE DATABASE IF NOT EXISTS ' . $db_name;
		if ( method_exists( $this->db, 'prepare' ) ) {
			$pre = $this->db->prepare( $request );
			$pre->execute();
			$db = new PDO(
				'mysql:host=' . $this->getHost() . ';dbname=' . $db_name,
				$this->getUsername(),
				$this->getPassword()
			);
			$this->setDb( $db );
		}
	}

	/**
	 * Initialize la base de données.
	 *
	 * @return void
	 */
	public function initDb() {
		try {
			$db = new PDO( 'mysql:host=' . $this->getHost(), $this->getUsername(), $this->getPassword() );
			$db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
			$this->setDb( $db );
			$this->createDb( $this->getDbName() );
			$this->initDefaultDbTab();
			return true;
		} catch ( Throwable $th ) {
			echo "Une erreur s'est produite";
		}
	}

	/**
	 * Initialize les tables par défaut.
	 *
	 * @return void
	 */
	private function initDefaultDbTab() {
		try {
			$db = $this->getDb();
			$this->initTable();
		} catch ( Throwable $th ) {
			echo "Une erreur s'est produite";
		}
	}
}
