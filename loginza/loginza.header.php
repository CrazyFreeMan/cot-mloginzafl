<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=header.body
Tags=header.tpl:{LZ_FORM_X}
[END_COT_EXT]
==================== */
defined('COT_CODE') or die('Wrong URL');

if ($usr['id'] < 1)
{	
	$lzget = array();
	foreach($_GET as $gk => $gv)
	{
		if (is_array($gv))
		{
			foreach ($gv as $k => $v)
			{
				if (is_array($v))
				{
					foreach ($v as $sk => $sv)
					{
						$lzget[$gk.'[' . $k . '][' . $sk . ']']  = $sv;
					}
				}
				else
				{
					$lzget[$gk.'[' . $k . ']']  = $v;
				}
			}
		}
		else
		{
			$lzget[$gk]  = $gv;
		}
	}
	unset($lzget['x']);
	$lzget['x'] = $sys['xk'];
	$lzget['send'] = 'input';
	
	require_once(cot_langfile('loginza'));
	$lz = new XTemplate(cot_tplfile(array('loginza', 'header'), 'plug'));

	$def_log_lang = ($usr['lang'] == 'ru') ? $usr['lang'] : 'en';
	if(!empty($cfg['plugin']['loginza']['m_group'])){
		$mng_group = explode(',', str_replace(' ', '', $cfg['plugin']['loginza']['m_group']));
	}else{
		$mng_group = array(4);
	}

	foreach ($mng_group as $key => $value) {
			$lzget['usergroup']	= $value;
			$fullname = urlencode($cfg['mainurl'] . '/' . cot_url($env['ext'], $lzget, '', true)) .'&amp;lang=' . $def_log_lang;
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
			$loginza[$value] = $lz->text("MAIN");
			$t->assign(array(
					'LZ_FORM_'.$value => $loginza[$value]
				));		
	}
}