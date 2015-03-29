<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=users.profile.tags
Tags=users.profile.tpl:{LZ_FORM},{LZ_TITLE}
[END_COT_EXT]
==================== */
defined('COT_CODE') or die('Wrong URL');

if ($m == 'profile')
{	
	require_once(cot_langfile('loginza'));
	$lz = new XTemplate(cot_tplfile(array('loginza', $m), 'plug'));
	
	$lzpath = ($m == 'profile') ? cot_url('users', 'm='.$m.'&send=input&x=' . $sys['xk'], '', true) : cot_url('index', 'send=input&x=' . $sys['xk'], '', true);
	$def_log_lang = ($usr['lang'] == 'ru') ? $usr['lang'] : 'en';
	$fullname = urlencode($cfg['mainurl'] . '/' . $lzpath) .'&lang=' . $def_log_lang;
	$lz->assign(array(
		"TOKEN_URL_SHORT" => $fullname,
		"LZ_INFO" => (($env['ext'] == 'users' && $m == 'profile') && $_SESSION['loginza']['update']) ? $L['lz_update_ok'] : '',
        "LZ_IDENTI" => ($usr['profile']['user_lz_provider'])? $usr['profile']['user_lz_provider']:'',
                
            ));
	if(!empty($cfg['plugin']['loginza']['providers'])){
		$addproviders = '&providers_set=' . str_replace(' ', '', $cfg['plugin']['loginza']['providers']);
		$lz->assign(array(
			"TOKEN_URL_SHORT" => $fullname . $addproviders
			));
	}

	$lz->parse("MAIN");
	$t->assign(array(
		"LZ_FORM" => $lz->text("MAIN"),
        "LZ_TITLE" =>$L['lz_ak']
	));

	$_SESSION['loginza']['update'] = 0;
}