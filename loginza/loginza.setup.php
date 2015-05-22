<?php
/* ====================
[BEGIN_COT_EXT]
Code=loginza
Name=mLOGINZA FL
Description=Authorization and registration for FL site through loginza.ru all-in-one service
Version=1.0.5
Date=01-08-2014
Author=Dr2005alex, CrazyFreeMan
Copyright=&copy; 2014 Dr2005alex http://mycotonti.ru, CrazyFreeMan (simple-website.in.ua)
Notes=
SQL=
Auth_guests=R
Lock_guests=W12345A
Auth_members=RW
Lock_members=12345A
Requires_plugins=usergroupselector
Requires_modules=users
[END_COT_EXT]
[BEGIN_COT_EXT_CONFIG]
providers=01:text:::List Providers
autoreg=02:hidden:0,1:1:Enabled auto registration ?
remember=03:radio:0,1:1:Remember the user for Auto Login.?
s_mail=04:radio:0,1:0:Send message?
m_group=05:string::4,7:Main group
[END_COT_EXT_CONFIG]
==================== */
defined('COT_CODE') or die('Wrong URL');