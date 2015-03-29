<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=users.register.tags,users.auth.tags
Tags=users.register.tpl:{LZ_FORM_X};login.tpl:{LZ_FORM_X}
[END_COT_EXT]
==================== */
defined('COT_CODE') or die('Wrong URL');

if ($usr['id'] < 1)
	{	
		require_once(cot_langfile('loginza'));
		$lz = new XTemplate(cot_tplfile(array('loginza', 'register-login'), 'plug'));
		unset($lzget);
		$lzget['x'] = $sys['xk'];
		$lzget['send'] = 'input';

		$def_log_lang = ($usr['lang'] == 'ru') ? $usr['lang'] : 'en';
		if(!empty($cfg['plugin']['loginza']['m_group'])){
			$mng_group = explode(',', str_replace(' ', '', $cfg['plugin']['loginza']['m_group']));
		}else{
			$mng_group = array(4);
		}

		foreach ($mng_group as $key => $value) {
				$lzget['usergroup']	= $value;				
				$fullname = urlencode($cfg['mainurl'] . '/' . cot_url('index', $lzget, '', true)) .'&amp;lang=' . $def_log_lang;
				$lz->assign(array(
					"TOKEN_URL_SHORT" => $fullname,
					"MNG_GROUP_TITLE" => $L['lz_mng_group_'.$value]
					));
				if(!empty($cfg['plugin']['loginza']['providers'])){
					$addproviders = '&providers_set=' . str_replace(' ', '', $cfg['plugin']['loginza']['providers']);
					$lz->assign(array(
						"TOKEN_URL_SHORT" => $fullname . $addproviders
						));
				}			
				$lz->parse("MAIN.ROW_LOG");	
				$lz->parse("MAIN");	
				$t->assign(array(
					'LZ_FORM_'.$value => $lz->text("MAIN")
				));
		}

		$_SESSION['loginza']['update'] = 0;
	}