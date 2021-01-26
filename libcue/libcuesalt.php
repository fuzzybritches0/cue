<?php
function libcue_salt($user, $password)
	{
	$salt =  libcue_fsdb_load_variables( "salt/" . $user);
	$choice = rand( 0, 9);
	return $password . $salt[$choice];
	}
function libcue_salt_create($user)
	{
	for ( $salt=0; $salt<=9; $salt++ )
		{
		$rand_salt[$salt] = libcue_session_key();
		}
	libcue_fsdb_save_variables( "salt/" . $user, $rand_salt );
	}