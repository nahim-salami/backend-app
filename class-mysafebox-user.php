<?php

/**
 * WebreatheTest user.
 */
class MySafeBoxUser {

	/**
	 * Prenom de l'utilisateur.
	 *
	 * @var string
	 */
	private $prenom;

	/**
	 * Nom de l'utilisateur.
	 *
	 * @var string
	 */
	private $nom;

	/**
	 * Mail de l'utilisateur.
	 *
	 * @var string
	 */
	private $mail;

	/**
	 * Adress de l'utilisateur.
	 *
	 * @var array
	 */
	private $addres;

	/**
	 * Date de naissance de l'utilisateur.
	 *
	 * @var string
	 */
	private $date_nais;

	/**
	 * Role de l'utilisateur.
	 *
	 * @var string
	 */
	private $role;

	/**
	 * Base de donnée.
	 *
	 * @var [type]
	 */
	private $db;

	/**
	 * Nom d'utilisateur.
	 *
	 * @var string
	 */
	private $username;

	/**
	 * Mot de passe de l'utilisateur.
	 *
	 * @var string
	 */
	private $password;

	/**
	 * Initialize user data.
	 *
	 * @param array $data The param data.
	 */
	public function __construct( $data ) {
		if ( ! is_array( $data ) ) {
			return false;
		}

		$msfbdb = new MySafeBoxDb();

		$this->db = $msfbdb->getDb();

		if ( isset( $data['prenom'] ) ) {
			$this->setPrenom( $data['prenom'] );
		}

		if ( isset( $data['nom'] ) ) {
			$this->setNom( $data['nom'] );
		}

		if ( isset( $data['email'] ) ) {
			$this->seteMail( $data['email'] );
		}

		if ( isset( $data['role'] ) ) {
			$this->setRole( $data['role'] );
		}

		if ( isset( $data['adress'] ) ) {
			$this->setAdress( $data['adress'] );
		}

		if ( isset( $data['date'] ) ) {
			$this->setDate( $data['date'] );
		}

		if ( isset( $data['username'] ) ) {
			$this->setUsername( $data['username'] );
		}

		if ( isset( $data['password'] ) ) {
			$this->setPassword( $data['password'] );
		}
	}

	/**
	 * Check if users exist.
	 *
	 * @return boolean
	 */
	public function exist() {
		$query   = 'SELECT * FROM account where username= ? OR mail= ?';
		$request = $this->db->prepare( $query );
		$request->execute( array( $this->username, $this->mail ) );
		$result = $request->fetchAll();
		if ( count( $result ) > 0 ) {
			return true;
		}

		return false;
	}

	public function login() {
		$query   = 'SELECT * FROM account where username= ? OR mail= ? AND user_password= ?';
		$request = $this->db->prepare( $query );
		$request->execute( array( $this->username, $this->mail, $this->password ) );
		$result = $request->fetchAll();
		if ( count( $result ) > 0 ) {
			return array(
				'message'  => 'Login success',
				'response' => true,
                'status_code' => 202
			);
		}

		return array(
			'message'  => "Nom d'utilisateur ou mot de passe incorrecte.",
			'response' => false,
            'status_code' => 502
		);
	}

	/**
	 * Register users if not exist.
	 *
	 * @param integer $id account id.
	 * @return array
	 */
	public function register( $id = 0 ) {
		if ( ! $this->exist() ) {
			$query_account = 'INSERT INTO account(username, mail, user_password, date_creation, account_type, users_id) VALUES(:username, :mail, :user_password, :date_creation, :account_type, :users_id)';
			$queryuser     = 'INSERT INTO users(nom, prenom, adress, date_naissance, mail, parent_id) VALUES(:nom, :prenom, :adress, :date_naissance, :mail, :parent_id)';
			$requestuser   = $this->db->prepare( $queryuser );
			$requestuser->execute(
				array(
					':nom'            => $this->nom,
					':prenom'         => $this->prenom,
					':adress'         => $this->adress,
					':date_naissance' => $this->date_nais,
					':mail'           => $this->mail,
					':parent_id'      => $id,
				)
			);

			$users_id = $this->db->lastInsertId();

			$requestaccount = $this->db->prepare( $query_account );

			$requestaccount->execute(
				array(
					':username'      => $this->username,
					':mail'          => $this->mail,
					':user_password' => $this->password,
					':date_creation' => date( 'Y-m-d H:i:s' ),
					':account_type'  => $this->username,
					':users_id'      => $users_id,
				)
			);

			$account_id = $this->db->lastInsertId();

			return array(
				'users_id'   => $users_id,
				'account_id' => $account_id,
				'reponse'    => true,
                'status_code' => 202
			);
		}

		return array(
			'message'  => "Ce t'utilisateur existe déjà",
			'response' => false,
            'status_code' => 201
		);
	}

	private function setDate( $data ) {
		if ( $this->checkSize( $data ) ) {
			$this->date_nais = $data;
		}
	}

	private function setMail( $data ) {
		if ( filter_var( $data, FILTER_VALIDATE_EMAIL ) ) {
			$this->mail = $data;
		}
	}

	private function setUsername( $data ) {
		if ( $this->checkSize( $data ) ) {
			$this->username = $data;
		}
	}

	private function setPassword( $data ) {
		if ( $this->checkSize( $data ) ) {
			$this->password = md5( $data );
		}
	}

	private function setPrenom( $data ) {
		if ( $this->checkSize( $data ) ) {
			$this->prenom = $data;
		}
	}

	private function setNom( $data ) {
		if ( $this->checkSize( $data ) ) {
			$this->nom = $data;
		}
	}


	private function setAdress( $data ) {
		if ( $this->checkSize( $data ) ) {
			$this->adress = $data;
		}
	}


	private function setRole( $data ) {
		if ( $this->checkSize( $data ) ) {
			$this->role = $data;
		}
	}

	public function checkSize( $str ) {
		return ( count( $str ) >= 1 && ! empty( $str ) && is_string( $str ) ) ? true : false;
	}

}
