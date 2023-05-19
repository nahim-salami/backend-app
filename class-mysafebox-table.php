<?php

abstract class MySafeBoxTable {

	/**
	 * Objet PDO (Base de donnée).
	 *
	 * @var object
	 */
	private $db;

	public function setDb( $db ) {
		$this->db = $db;
	}

	public function initTable() {
		$user = 'CREATE TABLE IF NOT EXISTS users (
            users_id int(6) NOT NULL AUTO_INCREMENT,
            prenom varchar(24) NULL,
            nom varchar(24) NULL,
            jobrole varchar(24) NULL,
            adress varchar(64) NULL,
            mail varchar(64) NULL,
            date_naissance DATE NULL,
            parent_id varchar(24) NULL,
            PRIMARY KEY (users_id)
        )ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci';

		$account = 'CREATE TABLE IF NOT EXISTS account (
            account_id int(6) NOT NULL AUTO_INCREMENT,
            mail varchar(64) NULL,
            username varchar(24) NULL,
            account_type varchar(24) NULL,
            user_password varchar(128) NULL,
            date_creation DATE NULL,
            users_id int(6),
            PRIMARY KEY (account_id),
            KEY (users_id)
        )ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci';

		$module = 'CREATE TABLE IF NOT EXISTS module (
            module_id int(6) NOT NULL AUTO_INCREMENT,
            nom_module varchar(24) NOT NULL,
            categorie varchar(24) NOT NULL,
            type_donnee varchar(24) NULL,
            desc_module varchar(128) NULL,
            url_module varchar(128) NOT NULL,   
            status_module varchar(64) NULL,
            date_creation_module DATE NOT NULL,
            date_utilisation DATE NOT NULL,
            PRIMARY KEY (module_id)
        )ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci';

        $partage = 'CREATE TABLE IF NOT EXISTS partage (
            module_id int(6) NOT NULL AUTO_INCREMENT,
            jobrole varchar(24) NOT NULL,
            date_partage DATE NOT NULL,
            KEY (module_id)
        )ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci';

		$db = $this->db;
		try {
			if ( method_exists( $db, 'prepare' ) ) {
                $pre = $db->prepare( $user );
                $pre->execute();
                $pre = $db->prepare( $account );
                $pre->execute();
                $pre = $db->prepare( $module );
                $pre->execute();
                $pre = $db->prepare( $partage );
                $pre->execute();
			}
		} catch ( Throwable $th ) {
			$logs_src = dirname( __DIR__, 2 ) . DIR_SEPARATOR . SITE_DIRECTORY_NAME . DIR_SEPARATOR . '/errorlogs.msfb';
			if ( DISPLAY_ERROR ) {
				trigger_error( "Une erreur s'est produite. Contacter l'administrateur du site. " . $th );
			}

			$error_time = date( 'y-m-d H:i:s' );
			$file_data  = file_get_contents( $logs_src );
			file_put_contents( $logs_src, $file_data . '  ' . $error_time . '  ' . $th );
		}

	}
}
