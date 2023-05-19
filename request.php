<?php
/**
 * Contacte la class root.
 *
 * @author Nahim SALAMI <nahim.salami@outlook.fr>
 */

require './require.php';

try {
	$response = array(
		'response'    => 'NOT FOUND',
		'status_code' => 502,
		'message'     => 'FORMAT ERROR',
	);

	if ( isset( $_POST['request'] ) && ! empty( $_POST['request'] ) ) {
		
		if ( isset( $_POST['request']['query'] ) && ! empty( $_POST['request']['query'] ) ) {
			if ( isset( $_POST['request']['data'] ) && ! empty( $_POST['request']['data'] ) ) {
				switch ( $_POST['request']['query'] ) {
					case 'register':
						$response = registerUser( $_POST['request']['data'] );
						break;

					case 'login':
						$response = loginUser( $_POST['request']['data'] );
						break;
					// case 'list-user':
					// code...
					// break;

					// case 'list-component':
					// code...
					// break;
					// case 'remove-component':
					// code...
					// break;
					// case 'remove-user':
					// code...
					// break;

					default:
						$response = array(
							'response'    => 'NOT FOUND',
							'status_code' => 502,
							'message'     => 'FORMAT ERROR',
						);
						break;
				}
			}
		}
	}

	echo json_encode( $response );
	die;
} catch ( Throwable $th ) {
	$logs_src = __DIR__ . '/errorlogs.msfb';
	if ( DISPLAY_ERROR ) {
		trigger_error( "Une erreur s'est produite. Contacter l'administrateur du site. " . $th );
	}

	$error_time = date( 'y-m-d H:i:s' );
	$file_data  = file_get_contents( $logs_src );

	if ( ! empty( $file_data ) ) {
		$file_data .= "\n\n";
	}

	$file_data .= $error_time . '  ' . $th;
	file_put_contents( $logs_src, $file_data );
	exit;
}
