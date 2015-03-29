<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=input
[END_COT_EXT]
==================== */
defined('COT_CODE') or die('Wrong URL');

if (cot_import('send', 'G', 'TXT') == 'input')
{       session_start();
	$_SESSION['loginza']['update'] = 0;
	require_once $cfg['plugins_dir'] . '/loginza/inc/LoginzaAPI.class.php';
	require_once cot_incfile('loginza', 'plug');//require_once $cfg['plugins_dir'] . '/loginza/inc/functions.php';	
	$LoginzaAPI = new LoginzaAPI();
	// проверка переданного токена
	if (!empty($_POST['token']))
	{
		// получаем профиль авторизованного пользователя
		$UserProfile = $LoginzaAPI->getAuthInfo($_POST['token']);

		// проверка на ошибки
		if (!empty($UserProfile->error_type))
		{
			// есть ошибки, выводим их
			// в рабочем примере данные ошибки не следует выводить пользователю, так как они несут информационный характер только для разработчика
			//echo $UserProfile->error_type.": ".$UserProfile->error_message;
		}
		elseif (empty($UserProfile))
		{
			// прочие ошибки
			echo 'Temporary error.';
		}
		else
		{
			// ошибок нет запоминаем пользователя как авторизованного
			$_SESSION['loginza']['is_auth'] = 1;
			// запоминаем профиль пользователя в сессию или создаем локальную учетную запись пользователя в БД
			$_SESSION['loginza']['profile'] = $UserProfile;
		}
	}
	elseif (isset($_GET['quit']))
	{
		// выход пользователя
		unset($_SESSION['loginza']);
		$_SESSION['loginza']['is_auth'] = 0;
	}
	//=================================================================
	if (!empty($_SESSION['loginza']['is_auth']))
	{
		$LoginzaAPI->UserInfo($_SESSION['loginza']['profile']);
		$lz_uid = trim(preg_replace('/[^\w]+/i', '-', $_SESSION['loginza_info']['identity']), '-');


		if ($usr['id'] > 0)
		{
			$_SESSION['loginza']['update'] = 0;
			// Logged in both on LZ and Cotonti

			if (empty($usr['user_lzid']))
			{
				$db->query("UPDATE $db_users SET user_lzid = '" . $lz_uid . "', user_lz_provider = '" . $_SESSION['loginza_info']['identity'] . "' WHERE user_id = " . $usr['id']);

				$lz_res = $db->query("SELECT * FROM $db_users WHERE user_lzid = '" . $lz_uid . "'");
				if ($row = $lz_res->fetch())
				{     
                                    $_SESSION['loginza']['update'] = 1;
                                    lz_autologin($row);
				}
				
				//  lz_autologin($usr['profile']);
			}
			// continue normal execution
		}
		elseif (!defined('COT_USERS') && !defined('COT_MESSAGE') && !$_SESSION['loginza']['noreg']) // avoid deadlocks and loops
		{
			// Check if this FB user has a native Cotonti account
			$lz_res = $db->query("SELECT * FROM $db_users WHERE user_lzid = '" . $lz_uid . "'");
			if ($row = $lz_res->fetch())
			{
				// Load user account and log him in
				lz_autologin($row);
			}
			else
			{	
				//Перевіряємо наявність ГЕТ основної групи якщо немає то по замовчуванні 4	
					$grptmp = cot_import('usergroup','GET','INT');								
					($grptmp>0) ? $defgroup = cot_import('usergroup','GET','INT') : $defgroup = 4 ;

				if ($cfg['plugin']['loginza']['autoreg'])
				{
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

					$res1 = $db->query("SELECT COUNT(*) FROM $db_users WHERE user_name='" . $db->prep($login) . "'")->fetchColumn();

					if ($row['email'])
					{
						$res2 = $db->query("SELECT COUNT(*) FROM $db_users WHERE user_email='" . $db->prep($row['email']) . "'")->fetchColumn();
					}

					if ($res1 > 0)
					{
						cot_redirect(cot_url('message', 'msg=777', '', true));
						exit;
					}
					if ($res2 > 0)
					{
						cot_redirect(cot_url('message', 'msg=778', '', true));
						exit;
					}

					if ($row['dob'] and $row['provider'] == 'http://www.facebook.com/')
					{
						preg_match('#(\d+)-(\d+)-(\d+)#', $row['dob'], $mt);
						$row['dob'] = (int)$mt[1] . "-" . (int)$mt[3] . "-" . (int)$mt[2];
					}
					//-----------------------
					$row['dob'] = (empty($row['dob'])) ? '0000-00-00' : $row['dob'];

					//$defgroup = ($cfg['plugin']['loginza']['m_group']) ? (int) $cfg['plugin']['loginza']['m_group'] : 4 ;										

					$row['gender'] = (!$row['gender']) ? 'U' : $row['gender'];
					$prepass = RandomPassword();
					$mdpass = md5($prepass);
					$validationkey = md5(microtime());
                                        
                                        /* === Hook === */
                                        foreach (cot_getextplugins('loginza.reg.first') as $pl)
                                        {
                                                include $pl;
                                        }
                                        /* ===== */
                                        
                                        $lzitem = array();
                                        $lzitem['user_name'] = $db->prep($login);
										$lzitem['user_password'] = $mdpass;
                                        $lzitem['user_maingrp'] = (int)$defgroup;
                                        $lzitem['user_usergroup'] = (int)$defgroup;
                                        $lzitem['user_email'] = $db->prep($row['email']);
                                        $lzitem['user_hideemail'] = 1;
                                        $lzitem['user_theme'] = $cfg['defaulttheme'];
                                        $lzitem['user_scheme'] = $cfg['defaultscheme'];
                                        $lzitem['user_lang'] = $cfg['defaultlang'];
                                        $lzitem['user_regdate'] = (int)$sys['now_offset'];
                                        $lzitem['user_logcount'] = 0;
                                        $lzitem['user_lostpass'] = $validationkey;
                                        $lzitem['user_gender'] = $db->prep($row['gender']);
                                        $lzitem['user_birthdate'] = $row['dob'];
                                        $lzitem['user_lastip'] = $usr['ip'];
                                        $lzitem['user_lzid'] = $lz_uid;
                                        $lzitem['user_lz_provider'] = $row['identity'];
                                        $lzitem['user_passsalt'] = cot_unique(16);
                                        
                                        $db->insert($db_users, $lzitem); 
                                        
					$userid = $db->lastInsertId();
					$sql = $db->query("INSERT INTO $db_groups_users (gru_userid, gru_groupid) VALUES (" . (int)$userid . ", " . (int)$defgroup . ")");

					$row['user_id'] = $userid;
					$row['user_name'] = $login;
					$row['user_maingrp'] = (int)$defgroup;
					$row['user_banexpire'] = 0;
					$row['user_skin'] = $cfg['defaultskin'];
					$row['user_theme'] = $cfg['defaulttheme'];
					$rremember = $cfg['plugin']['loginza']['remember'];
                                        
                                        /* === Hook === */
                                        foreach (cot_getextplugins('loginza.reg.done') as $pl)
                                        {
                                                include $pl;
                                        }
                                        /* ===== */
                                        
					//-----MAIL------
					if ($cfg['plugin']['loginza']['s_mail'] and !empty($row['email']))
					{
						$rsubject = "{$cfg['maintitle']} - {$L['lz_regok_title']}";
						$rbody = sprintf($L['lz_regok'], $cfg['maintitle'], htmlspecialchars($login), $prepass, $row['provider']);

						cot_mail($remail, $rsubject, $rbody);
					}
					//--------------

					lz_autologin($row);
				}
				elseif(!$_SESSION['loginza']['noreg'])
				{       $_SESSION['loginza']['noreg'] = true; 
					cot_redirect(cot_url('users', 'm=register&send=input', '', TRUE));
				}
				$lz_res = null;
			}
		}
	}
$_SESSION['loginza']['noreg'] = false;        
}