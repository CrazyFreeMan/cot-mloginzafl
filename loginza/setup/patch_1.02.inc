<?php
/**
 * Reinstalls module/structure config implantations if necessary
 *
 * @package comments
 * @version 0.9.0.2
 * @author Cotonti Team
 * @copyright Copyright (c) Cotonti Team 2008-2011
 * @license BSD
 */

defined('COT_CODE') or die('Wrong URL');

global $db_users,$R;
cot_extrafield_add($db_users, 'lz_provider', 'input', $R['input_text'], '', '', 0, 'HTML', 'Loginza Provider');
