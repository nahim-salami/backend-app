<?php

function registerUser( $data ) {
	$msfb_user = new MySafeBoxUser( $data );
	return $msfb_user->register();
}

function loginUser( $data ) {
	$msfb_user = new MySafeBoxUser( $data );
	return $msfb_user->register();
}
