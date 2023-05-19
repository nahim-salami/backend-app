<?php

try {
	define( 'SITE_DIRECTORY_NAME', 'MySafeBox' );
	define( 'COOKIE_DOMAIN', 'MySafeBox' );
	define( 'SITE_DOMAIN', 'MySafeBox' );
	define( 'DISPLAY_ERROR', true );
	define( 'DIR_SEPARATOR', '/' );

	// Database require informations.
	define( 'DATABASE_NAME', 'msfb' );
	define( 'DATABASE_HOST', 'awseb-e-fjm2kkcwz4-stack-awsebrdsdatabase-qfbduc4sdqjj.ca62ecjlwksv.us-east-2.rds.amazonaws.com;port=3306' );
	define( 'DATABASE_PASSWORD', 'mysafeboxadmindb' );
	define( 'DATABASE_USERNAME', 'msfb' );

	require './class-mysafebox-table.php';
	require './class-mysafebox-database.php';
	require './class-mysafebox-component.php';
	require './functions.php';
	require './class-mysafebox-error.php';
	require './class-mysafebox-user.php';

} catch ( Exception $th ) {
	$logs_src = __DIR__ . '/errorlogs.msfb';
	if ( DISPLAY_ERROR ) {
		trigger_error( "Une erreur s'est produite. Contacter l'administrateur du site. " . $th );
	}

	$error_time = date( 'y-m-d H:i:s' );
	$file_data  = file_get_contents( $logs_src );
	file_put_contents( $logs_src, $file_data . '  ' . $error_time . '  ' . $th );
	exit;
}
