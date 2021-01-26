<?php

if ( isset($_SESSION['user_name']) )
	{
	libcue_session_logout();
	}
