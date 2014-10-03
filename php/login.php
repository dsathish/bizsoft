<?php
require('conf/setup.inc.php');

if (isset($_REQUEST['login']))
{
	$user_id=$common_obj->checkLogin($_REQUEST['username'],$_REQUEST['password']);

	if ($user_id)
	{
		$user_array=$common_obj->getUserInfo($user_id);
		$_SESSION['user_id']=$user_id;
		$_SESSION['user_name']=$user_array['display_name'];	
	}
	else
	{
		$errors = 'Invalid user';
		$smarty->assign('errors',$errors);
	}
}
elseif (isset($_REQUEST['logout']))
{
	if ($_REQUEST['logout'] == 1)
	{
		session_destroy();
		$errors = 'Logged out successfully';
		$smarty->assign('errors',$errors);
	}
}

if($errors)
{
	header("location:../index.php?err=".$errors);
	exit;
}
else
{
	header("location:./home.php");
	exit;
}

?>
