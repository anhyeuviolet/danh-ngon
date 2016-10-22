<!-- BEGIN: main -->
<link type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.css" rel="stylesheet" />
<link rel="stylesheet" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.css">

<table class="table table-striped table-bordered table-hover">
	<tbody>
		<tr>
			<td>
				<form class="form-inline" id="filter-form" method="get" action="" onsubmit="return false;">
					<input class="form-control" style="width:230px" type="text" name="q" value="{DATA_SEARCH.q}" onfocus="if(this.value == '{LANG.filter_enterkey}') {this.value = '';}" onblur="if (this.value == '') {this.value = '{LANG.filter_enterkey}';}"/>
					{LANG.filter_from}
					<input class="form-control text" value="{DATA_SEARCH.from}" type="text" id="from" name="from" readonly="readonly" style="width:70px" />
					{LANG.filter_to}
					<input class="form-control text" value="{DATA_SEARCH.to}" type="text" id="to" name="to" readonly="readonly" style="width:70px" />
					<input type="button" name="do" value="{LANG.filter_action}"/>
					<input type="button" name="cancel" value="{LANG.filter_cancel}" onclick="window.location='{URL_CANCEL}';"{DATA_SEARCH.disabled}/>
					<input type="button" name="clear" value="{LANG.filter_clear}"/>
				</form>
			</td>
		</tr>
	</tbody>
</table>
<form class="form-inline" action="{FORM_ACTION}" method="post" name="levelnone" id="levelnone">
	<table class="table table-striped table-bordered table-hover">
		<thead>
			<tr>
				<td class="text-center" style="width:10px">
					<input name="check_all[]" type="checkbox" value="yes" onclick="nv_checkAll(this.form, 'idcheck[]', 'check_all[]',this.checked);" />
				</td>
				<td><strong>{LANG.content_content}</strong></td>
				<td class="aright" style="width:150px">
					<a href="{DATA_ORDER.addtime.data.url}" title="{DATA_ORDER.addtime.data.title}" class="{DATA_ORDER.addtime.data.class}"><strong>{LANG.addtime}</strong></a>
				</td>
				<td class="aright" style="width:150px">
					<a href="{DATA_ORDER.updatetime.data.url}" title="{DATA_ORDER.updatetime.data.title}" class="{DATA_ORDER.updatetime.data.class}"><strong>{LANG.updatetime}</strong></a>
				</td>
				<td style="width:100px"><strong>{LANG.status}</strong></td>
				<td style="width:150px" class="text-center"><strong>{LANG.feature}</strong></td>
			</tr>
		</thead>
		<tbody>
		<!-- BEGIN: row -->
			<tr class="topalign">
				<td class="text-center">
					<input type="checkbox" onclick="nv_UncheckAll(this.form, 'idcheck[]', 'check_all[]', this.checked);" value="{ROW.id}" name="idcheck[]" />
				</td>
				<td>{ROW.content}</td>
				<td class="aright">
					<strong>{ROW.addtime}</strong>
				</td>
				<td class="aright">
					<strong>{ROW.updatetime}</strong>
				</td>
				<td class="text-center">
					<input name="status" id="change_status{ROW.id}" value="1" type="checkbox"{ROW.status} onclick="nv_change_danh_ngon_status({ROW.id})" />
				</td>
				<td class="text-center">
					<span><a class="btn btn-primary btn-xs" href="{ROW.url_edit}"><i class="fa fa-edit margin-right"></i>&nbsp;{GLANG.edit}</a></span>
					&nbsp;&nbsp;
					<span><a class="btn btn-warning btn-xs" href="javascript:void(0);" onclick="nv_delete_danh_ngon({ROW.id});"><i class="fa fa-trash-o margin-right"></i>&nbsp;{GLANG.delete}</a></span>
				</td>
			</tr>
		<!-- END: row -->
		</tbody>
		<!-- BEGIN: generate_page -->
		<tbody>
			<tr>
				<td colspan="7">
					{GENERATE_PAGE}
				</td>
			</tr>
		<!-- END: generate_page -->
		</tbody>
		<tfoot>
			<tr>
				<td colspan="7">
					<select class="form-control" name="lelelaction" id="lelelaction">
						<!-- BEGIN: action -->
						<option value="{ACTION.key}">{ACTION.title}</option>
						<!-- END: action -->
					</select>
					<input type="button" name="action" value="{LANG.action}" onclick="nv_danh_ngon_action(this.form, '{LANG.alert_check}');" />
				</td>
			</tr>
		</tfoot>
	</table>
</form>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/i18n/{NV_LANG_INTERFACE}.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>

<script type="text/javascript">
$(document).ready(function(){
	$("#from,#to").datepicker({
		showOn: "button",
		dateFormat: "dd.mm.yy",
		changeMonth: true,
		changeYear: true,
		showOtherMonths: true,
		buttonText: '{LANG.select}',
		showButtonPanel: true,
		showOn: 'focus'
	});
	$('input[name=clear]').click(function(){
		$('#filter-form .text').val('');
		$('input[name=q]').val('{LANG.filter_enterkey}');
	});
	$('input[name=do]').click(function(){
		var f_q = $('input[name=q]').val();
		var f_from = $('input[name=from]').val();
		var f_to = $('input[name=to]').val();
		if ( ( f_q != '{LANG.filter_enterkey}' && f_q != '' ) || f_from != '' || f_to != '' ){
			$('#filter-form input, #filter-form select').attr('disabled', 'disabled');
			window.location = '{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}={OP}&q=' + f_q + '&from=' + f_from + '&to=' + f_to;	
		}else{
			alert ('{LANG.filter_err_submit}');
		}
	});
});
</script>

<script type="text/javascript">
	var nv_teachers_delete_confirm = '{LANG.teachers_delete_confirm}';
	var nv_teachers_change_status_confirm = '{LANG.teachers_change_status_confirm}';
	var nv_error_unknow = '{LANG.error_unknow}';
</script>

<!-- END: main -->