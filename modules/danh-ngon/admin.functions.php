<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2016 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 07-03-2011 20:15
 */

if ( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) or ! defined( 'NV_IS_MODADMIN' ) ) die( 'Stop!!!' );

//$submenu['content'] = $lang_module['content'];
//$submenu['tags'] = $lang_module['tags'];

$allow_func = array( 'main', 'content', 'tags' );

define( 'NV_IS_DANH_NGON_ADMIN', true );