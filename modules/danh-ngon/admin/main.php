<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2016 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 24-06-2011 10:35
 */

if ( ! defined( 'NV_IS_DANH_NGON_ADMIN' ) ) die( 'Stop!!!' );

// Xoa danh ngon
if ( $nv_Request->isset_request( 'del', 'post' ) )
{
    if ( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );
    
    $id = $nv_Request->get_int( 'id', 'post', 0 );
    $list_levelid = $nv_Request->get_title( 'listid', 'post', '' );
    
    if ( empty( $id ) and empty ( $list_levelid ) ) die( 'NO' );
    
	$listid = array();
	if ( $id )
	{
		$listid[] = $id;
		$num = 1;
	}
	else
	{
		$list_levelid = explode ( ",", $list_levelid );
		$list_levelid = array_map ( "trim", $list_levelid );
		$list_levelid = array_filter ( $list_levelid );

		$listid = $list_levelid;
		$num = count( $list_levelid );
	}
	
	// Lay id, tags
	$sql = "SELECT id, tags FROM " . NV_PREFIXLANG . "_" . $module_data . " WHERE id IN (" . implode ( ",", $listid ) . ")";
	$result = $db->query( $sql );
	$check_level = $result->rowCount();
	
	if ( $check_level != $num ) die( 'NO' );
	
	$array = array();
	while ( list( $did, $tags ) = $result->fetch( 3 ) )
	{
		$array[$did] = $tags;
	}
	
	foreach( $array as $id => $tags )
	{
		if( ! empty( $tags ) )
		{
			$tags = explode( "|", $tags );
			foreach( $tags as $tag )
			{
				$sql = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_tags SET nums=nums-1 WHERE title=" . $db->quote( $tag );
				$db->query( $sql );
			}
		}
	
		$sql = "DELETE FROM " . NV_PREFIXLANG . "_" . $module_data . " WHERE id=" . $id;
		$db->query( $sql );
	}	
    
    $nv_Cache->delMod( $module_name );
	nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['content_delete'], "", $admin_info['userid'] );
	
    die( 'OK' );
}

// Change status
if ( $nv_Request->isset_request( 'changestatus', 'post' ) )
{
    if ( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );
    
    $id = $nv_Request->get_int( 'id', 'post', 0 );
    $controlstatus = $nv_Request->get_int( 'status', 'post', 0 );
    $array_id = $nv_Request->get_title( 'listid', 'post', '' );
    
    if ( empty( $id ) and empty ( $array_id ) ) die( 'NO' );
    
	$listid = array();
	if ( $id )
	{
		$listid[] = $id;
		$num = 1;
	}
	else
	{
		$array_id = explode ( ",", $array_id );
		$array_id = array_map ( "trim", $array_id );
		$array_id = array_filter ( $array_id );

		$listid = $array_id;
		$num = count( $array_id );
	}
	
	// Get base value
	$sql = "SELECT id, status FROM " . NV_PREFIXLANG . "_" . $module_data . " WHERE id IN (" . implode ( ",", $listid ) . ")";
	$result = $db->query( $sql );
	$check = $result->rowCount();
	
	if ( $check != $num ) die( 'NO' );
	
	$array_status = array();
	$array_title = array();
	while ( list( $id, $status ) = $result->fetch( 3 ) )
	{		
		if ( empty ( $controlstatus ) )
		{
			$array_status[$id] = $status ? 0 : 1;
		}
		else
		{
			$array_status[$id] = ( $controlstatus == 1 ) ? 1 : 0;
		}
	}
	
	foreach( $array_status as $id => $status )
	{
		$sql = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . " SET status=" . $status . " WHERE id=" . $id;
		$db->query( $sql );	
	}	
    
    $nv_Cache->delMod( $module_name );
	
    die( 'OK' );
}

// Page title collum
$page_title = $lang_module['main_list'];
$page = $nv_Request->get_int( 'page', 'get', 0 );
$per_page = 30;
$array = array();

// Base data
$sql = "FROM " . NV_PREFIXLANG . "_" . $module_data . " WHERE id!=0";
$base_url = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op;

// Search data
$data_search = array(
	"q" => nv_substr( $nv_Request->get_title( 'q', 'get', $lang_module['filter_enterkey'], 1 ), 0, 100), //
	"from" => nv_substr( $nv_Request->get_title( 'from', 'get', '', 1 ), 0, 100), //
	"to" => nv_substr( $nv_Request->get_title( 'to', 'get', '', 1 ), 0, 100), //
	"disabled" => " disabled=\"disabled\"" //
);

// Enable cancel filter data
if ( ( $data_search['q'] != $lang_module['filter_enterkey'] and ! empty ( $data_search['q'] ) ) or ! empty ( $data_search['from'] ) or ! empty ( $data_search['to'] ) )
{
	$data_search['disabled'] = "";
}

// Filter data
if ( ! empty ( $data_search['q'] ) and $data_search['q'] != $lang_module['filter_enterkey'] )
{
	$base_url .= "&amp;q=" . $data_search['q'];
	$sql .= " AND content LIKE '%" . $db->dblikeescape( $data_search['q'] ) . "%'";
}
	
if ( ! empty ( $data_search['from'] ) )
{
	unset( $match );
	if ( preg_match( "/^([0-9]{1,2})\.([0-9]{1,2})\.([0-9]{4})$/", $data_search['from'], $match ) )
	{
		$from = mktime( 0, 0, 0, $match[2], $match[1], $match[3] );
		$sql .= " AND addtime >= " . $from;
		$base_url .= "&amp;from=" . $data_search['from'];
	}
}
	
if ( ! empty ( $data_search['to'] ) )
{
	unset( $match );
	if ( preg_match( "/^([0-9]{1,2})\.([0-9]{1,2})\.([0-9]{4})$/", $data_search['to'], $match ) )
	{
		$to = mktime( 0, 0, 0, $match[2], $match[1], $match[3] );
		$sql .= " AND addtime <= " . $to;
		$base_url .= "&amp;to=" . $data_search['to'];
	}
}

// Order data
$order = array();
$check_order = array( "ASC", "DESC", "NO" );
$opposite_order = array(
	"NO" => "ASC",  //
	"DESC" => "ASC",  //
	"ASC" => "DESC"  //
);
$lang_order_1 = array(
	"NO" => $lang_module['filter_lang_asc'],  //
	"DESC" => $lang_module['filter_lang_asc'],  //
	"ASC" => $lang_module['filter_lang_desc']  //
);
$lang_order_2 = array(
	"addtime" => $lang_module['addtime'],  //
	"updatetime" => $lang_module['updatetime']  //
);

$order['addtime']['order'] = $nv_Request->get_title( 'order_addtime', 'get', 'NO' );
$order['updatetime']['order'] = $nv_Request->get_title( 'order_updatetime', 'get', 'NO' );

foreach ( $order as $key => $check )
{
	$order[$key]['data'] = array(
		"class" => "order" . strtolower ( $order[$key]['order'] ),  //
		"url" => $base_url . "&amp;order_" . $key . "=" . $opposite_order[$order[$key]['order']],  //
		"title" => sprintf ( $lang_module['filter_order_by'], "&quot;" . $lang_order_2[$key] . "&quot;" ) . " " . $lang_order_1[$order[$key]['order']]  //
	);
	
	if ( ! in_array ( $check['order'], $check_order ) )
	{
		$order[$key]['order'] = "NO";
	}
	else
	{
		$base_url .= "&amp;order_" . $key . "=" . $order[$key]['order'];
	}
}

if  ( $order['addtime']['order'] != "NO" )
{
	$sql .= " ORDER BY 	addtime " . $order['addtime']['order'];
}
elseif  ( $order['updatetime']['order'] != "NO" )
{
	$sql .= " ORDER BY updatetime " . $order['updatetime']['order'];
}
else
{
	$sql .= " ORDER BY id DESC";
}

// Get num row
$sql1 = "SELECT COUNT(*) " . $sql;
$result1 = $db->query( $sql1 );
$all_page = $result1->fetchColumn();

// Build data
$i = 1;
$sql = "SELECT * " . $sql . " LIMIT " . $page . ", " . $per_page;
$result = $db->query( $sql );

while ( $row = $result->fetch() )
{
	$array[] = array(
		"id" => $row['id'],  //
		"content" => $row['content'],  //
		"addtime" => nv_date( "d/m/Y H:i", $row['addtime'] ),  //
		"updatetime" => nv_date( "d/m/Y H:i", $row['updatetime'] ),  //
		"status" => $row['status'] ? " checked=\"checked\"" : "",  //
		"url_edit" => NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=content&amp;id=" . $row['id'], //
		"class" => ( $i % 2 == 0 ) ? " class=\"second\"" : ""  //
	);
	$i ++;
}

// List action
$list_action = array(
	0 => array(
			"key" => 1,  //
			"title" => $lang_global['delete']  //
		),
	1 => array(
			"key" => 2,  //
			"title" => $lang_module['action_status_ok']  //
		),
	2 => array(
			"key" => 3,  //
			"title" => $lang_module['action_status_no']  //
		)
);

$generate_page = nv_generate_page( $base_url, $all_page, $per_page, $page );

$xtpl = new XTemplate( "main.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );
$xtpl->assign( 'FORM_ACTION', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'OP', $op );
$xtpl->assign( 'DATA_SEARCH', $data_search );
$xtpl->assign( 'DATA_ORDER', $order );
$xtpl->assign( 'URL_CANCEL', NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op );

foreach ( $list_action as $action )
{
	$xtpl->assign( 'ACTION', $action );
	$xtpl->parse( 'main.action' );
}

foreach ( $array as $row )
{
	$xtpl->assign( 'ROW', $row );
	$xtpl->parse( 'main.row' );
}

if ( ! empty( $generate_page ) )
{
    $xtpl->assign( 'GENERATE_PAGE', $generate_page );
    $xtpl->parse( 'main.generate_page' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';