<?php defined('COT_CODE') or die('Wrong URL');
/* ====================
[BEGIN_COT_EXT]
Hooks=loginza.auth.check.done
Order=5
[END_COT_EXT]
==================== */
global $db_payments, $db_userpoints, $L, $db_x;

$db_payments = (isset($db_payments)) ? $db_payments : $db_x . 'payments';
$db_userpoints = (isset($db_userpoints)) ? $db_userpoints : $db_x . 'userpoints';