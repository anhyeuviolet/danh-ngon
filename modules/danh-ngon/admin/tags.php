<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2016 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 24-06-2011 10:35
 */

if ( ! defined( 'NV_IS_DANH_NGON_ADMIN' ) ) die( 'Stop!!!' );

// Xoa tags
if ( $nv_Request->isset_request( 'del', 'post' ) )
{
    if ( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );
    
    $id = $nv_Request->get_title( 'id', 'post', '' );
    
    if ( empty( $id ) )
    {
        die( 'NO' );
    }
    
    $sql = "SELECT title FROM " . NV_PREFIXLANG . "_" . $module_data . "_tags WHERE title=" . $db->quote( $id );
    $result = $db->query( $sql );
    $title = $result->fetchColumn();
    
    if ( empty( $title ) )
    {
        die( 'NO' );
    }
	
	// Lay tat ca cac danh ngon nay
	$key = $db->dblikeescape(nv_htmlspecialchars($id));
	$sql = "SELECT id, tags FROM " . NV_PREFIXLANG . "_" . $module_data . " WHERE tags='" . $key . "' OR tags REGEXP '^" . $key . "\\\|' OR tags REGEXP '\\\|" . $key . "\\\|' OR tags REGEXP '\\\|" . $key . "$'";
	$result = $db->query( $sql );
	
	// Cap nhat lai tags
	while( $row = $result->fetch(3) )
	{
		$row['tags'] = explode( "|", $row['tags'] );
		foreach( $row['tags'] as $key => $val )
		{
			if( $val == $id )
			{
				unset( $row['tags'][$key] );
			}
		}
		$row['tags'] = empty( $row['tags'] ) ? "" : implode( "|", $row['tags'] );
		
		$db->query( "UPDATE " . NV_PREFIXLANG . "_" . $module_data . " SET tags=" . $db->quote( $row['tags'] ) . " WHERE id=" . $row['id'] );
	}
	
	// Xoa tags
    $sql = "DELETE FROM " . NV_PREFIXLANG . "_" . $module_data . "_tags WHERE title=" . $db->quote( $id );
    $db->query( $sql );

    $nv_Cache->delMod( $module_name );
	nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['tags_delete'] , $id, $admin_info['userid'] );
    
    die( 'OK' );
}

// Page title collum
$page_title = $lang_module['tags'];

// List levels
$array = array();
$sql = "SELECT title, nums, status FROM " . NV_PREFIXLANG . "_" . $module_data . "_tags ORDER BY title ASC";
$result = $db->query( $sql );
$num = $result->rowCount();

$i = 1;
while ( list ( $title, $nums, $status ) = $result->fetch(3) )
{
	$array[] = array(
		"id" => urlencode( $title ),
		"title" => $title,
		"nums" => $nums,
		"status" => ( $status ) ? " checked=\"checked\"" : "",  //
		"url_edit" => NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;levelid=" . urlencode( $title ) . "#addeditarea",  //
		"class" => ( $i % 2 == 0 ) ? " class=\"second\"" : ""  //
	);
	$i ++;
}

// Add - Edit standard
$levelid = $nv_Request->get_title( 'levelid', 'get', '' );
$error = "";

if ( $levelid )
{
	$sql = "SELECT title FROM " . NV_PREFIXLANG . "_" . $module_data . "_tags WHERE title=" . $db->quote( $levelid );
	$result = $db->query( $sql );
	$check_ok = $result->rowCount();
	
	if ( $check_ok != 1 )
	{
		nv_info_die( $lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'] );
	}
	
	list ( $title ) = $result->fetch(3);
	$level_data_old = $level_data = array(
		"title" => $title,  //
	);
	
	$form_action = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;levelid=" . urlencode( $levelid );
	$table_caption = $lang_module['tags_edit'];
}
else
{
	$form_action = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op;
	$table_caption = $lang_module['tags_add'];
	
	$level_data = array(
		"title" => "",  //
	);
}

if ( $nv_Request->isset_request( 'submit', 'post' ) )
{
    $level_data['title'] = nv_strtolower( nv_substr( $nv_Request->get_title( 'title', 'post', '', 1 ), 0, 255 ));
     
	if ( empty ( $level_data['title'] ) )
	{
		$error = $lang_module['error_title'];
	}
	else
	{
		if ( empty ( $levelid ) )
		{
			$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_tags WHERE title=" .  $db->quote( $level_data['title'] );
			$result = $db->query( $sql );
			list ( $check_exist ) = $result->fetch(3);
			
			if ( $check_exist )
			{
				$error = $lang_module['tags_error_exist'];
			}
			else
			{				
				$sql = "INSERT INTO " . NV_PREFIXLANG . "_" . $module_data . "_tags VALUES ( " . $db->quote( $level_data['title'] ) . ", 0, 1 )";
				
				if ( $db->query( $sql ) )
				{
					//$xxx->closeCursor();
					$nv_Cache->delMod( $module_name );
					nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['tags_add'], $level_data['title'], $admin_info['userid'] );
					Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op );
					exit();
				}
				else
				{
					$error = $lang_module['error_save'];
				}
			}
		}
		else
		{
			$check_exist = false;
			if( $level_data['title'] != $levelid )
			{
				$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_tags WHERE title=" .  $db->quote( $level_data['title'] );
				$result = $db->query( $sql );
				list ( $check_exist ) = $result->fetch(3);
			}
			
			if ( $check_exist )
			{
				$error = $lang_module['tags_error_exist'];
			}
			else
			{
				$sql = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_tags SET title= " . $db->quote( $level_data['title'] ) . " WHERE title=" . $db->quote( $levelid );	
				
				if ( $db->query( $sql ) )
				{
					//$xxx->closeCursor();
					$nv_Cache->delMod( $module_name );
					nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['tags_edit'], $level_data_old['title'] . "&nbsp;=&gt;&nbsp;" . $level_data['title'], $admin_info['userid'] );
					Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op );
					exit();
				}
				else
				{
					$error = $lang_module['error_update'];
				}
			}
		}
	}
}

$xtpl = new XTemplate( "tags.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );
$xtpl->assign( 'TABLE_CAPTION', $table_caption );
$xtpl->assign( 'FORM_ACTION', $form_action );
$xtpl->assign( 'DATA', $level_data );

if ( ! empty ( $error ) )
{
	$xtpl->assign( 'ERROR', $error );
	$xtpl->parse( 'main.error' );
}

foreach ( $array as $row )
{
	$xtpl->assign( 'ROW', $row );

	$xtpl->parse( 'main.row' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';