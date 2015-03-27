<?php

/* ====================
[BEGIN_COT_EXT]
Hooks=users.register.tags
[END_COT_EXT]
==================== */

defined('COT_CODE') or die('Wrong URL');


if (!empty($lz_uid) && $usr['id'] == 0)
{
	if (empty($a))
	{
		if (!empty($_SESSION['loginza_info']))
		{
			require_once cot_incfile('forms');
			require_once cot_incfile('loginza', 'plug'); 

			$row = $_SESSION['loginza_info'];
			$login = ($row['nickname']) ? $row['nickname'] : $row['full_name'];
			if (empty($login) and (!empty($row['first_name']) or !empty($row['last_name']) ))
			{
				$login = $row['first_name'] . " " . $row['last_name'];
			}
			if (empty($login))
			{
				$login = Nickname($row['identity']);
			}
			if (empty($login))
			{
				$login = "Nologin_" . RandomPassword();
			}
			/* Транслітерація логіну */
			$login = lz_login_translate($login);			
				$t->assign("USERS_REGISTER_USER",  cot_inputbox('text', 'rusername', $login , array('size' => 24, 'maxlength' => 100)));
				//$t->assign("USERS_REGISTER_FIRST_NAME", "<input value=\"" . $login . "\" name=\"ruserfirst_name\" class=\"text\" type=\"text\" maxlength=\"25\" />");
				//$t->assign("USERS_REGISTER_SEND", cot_url('users', 'm=register&a=add&send=input'));
			if ($row['email'])
			{
				$t->assign("USERS_REGISTER_EMAIL", cot_inputbox('text', 'ruseremail', $row['email'], array('size' => 24, 'maxlength' => 64)));
			}

		}
	}
}