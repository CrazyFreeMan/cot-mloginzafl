<?php
defined('COT_CODE') or die('Wrong URL');
global $db_users,$R;
cot_extrafield_add($db_users, 'lzid', 'input', $R['input_text'], '', '', 0, 'HTML', 'Loginza ID');
cot_extrafield_add($db_users, 'lz_provider', 'input', $R['input_text'], '', '', 0, 'HTML', 'Loginza Provider');