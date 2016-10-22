<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2016 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 22/2/2011, 22:19
 */

if ( ! defined( 'NV_IS_FILE_SITEINFO' ) ) die( 'Stop!!!' );

$lang_siteinfo = nv_get_lang_module( $mod );

$number = $db->query( "SELECT COUNT(*) as number FROM " . NV_PREFIXLANG . "_" . $mod_data . "" )->fetchColumn();
if ( $number > 0 )
{
    $siteinfo[] = array( 
        'key' => $lang_siteinfo['siteinfo_num'] , 'value' => $number 
    );
}