/* *
 * @Project NUKEVIET 3.1
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2011 VINADES.,JSC. All rights reserved
 * @Createdate 07-03-2011 20:15
 */

function nv_chang_status_result( res ){
	if( res != 'OK' ){
		alert( nv_is_change_act_confirm[2] );
		window.location.href = window.location.href;
	}
	return;
}
function nv_delete_result( res ){
	if( res == 'OK' ){
		window.location.href = window.location.href;
	}else{
		alert( nv_is_del_confirm[2] );
	}
	return false;
}

function nv_delete_tags( id ){
	if ( confirm( nv_is_del_confirm[0] ) ){
		$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=tags&nocache=' + new Date().getTime(),'del=1&id=' + id, function(res) {
			nv_delete_result(res);
		});
	}
	return false;
}
function nv_danh_ngon_action(oForm, nv_message_no_check) {
	var fa = oForm['idcheck[]'];
	var listid = [];
	
	if (fa.length){
		for ( var i = 0; i < fa.length; i++){
			if (fa[i].checked){
				listid.push(fa[i].value);
			}
		}
	}else{
		if (fa.checked){
			listid.push(fa.value);
		}
	}
	
	if (listid != ''){
		var action = document.getElementById('lelelaction').value;
		if (action == 1){
			if ( confirm(nv_is_del_confirm[0]) ){
				$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=main&nocache=' + new Date().getTime(),'del=1&listid=' + listid, function(res) {
					nv_delete_film_result(res);
				});
			}
		}else if (action == 2){
			$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=main&nocache=' + new Date().getTime(),'changestatus=1&status=1&listid=' + listid, function(res) {
				nv_change_film_status_list_res(res);
			});
		}else if (action == 3){
			$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=main&nocache=' + new Date().getTime(),'changestatus=1&status=2&listid=' + listid, function(res) {
				nv_change_film_status_list_res(res);
			});
		}
	}else{
		alert(nv_message_no_check);
	}
}
function nv_delete_danh_ngon( id ){
	if ( confirm( nv_is_del_confirm[0] ) ){
		$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=main&nocache=' + new Date().getTime(),'del=1&id=' + id, function(res) {
			nv_delete_film_result(res);
		});
	}
	return false;
}
function nv_delete_film_result( res ){
	if( res == 'OK' ){
		window.location.href = window.location.href;
	}else{
		alert( nv_is_del_confirm[2] );
	}
	return false;
}
function nv_change_danh_ngon_status( id ){
	var nv_timer = nv_settimeout_disable( 'change_status' + id, 4000 );
	$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=main&nocache=' + new Date().getTime(),'changestatus=1&id=' + id + '&num=' + nv_randomPassword( 8 ), function(res) {
		nv_change_film_status_res(res);
	});
	return;
}
function nv_change_film_status_res( res ){
	if( res != 'OK' ){
		alert( nv_is_change_act_confirm[2] );
		window.location.href = window.location.href;
	}
	return;
}
function nv_change_film_status_list_res( res ){
	if( res != 'OK' ){
		alert( nv_is_change_act_confirm[2] );
	}
	window.location.href = window.location.href;
	return;
}