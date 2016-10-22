<!-- BEGIN: main -->
<form class="form-inline" action="{FORM_ACTION}" method="post" name="levelnone" id="levelnone">
	<table class="table table-striped table-bordered table-hover">
		<thead>
			<tr>
				<td><strong>{LANG.title}</strong></td>
				<td><strong>{LANG.tags_nums}</strong></td>
				<td class="text-center"><strong>{LANG.feature}</strong></td>
			</tr>
		</thead>
		<tbody>
		<!-- BEGIN: row -->
			<tr>
				<td>{ROW.title}</td>
				<td>{ROW.nums}</td>
				<td class="text-center">
					<span><a class="btn btn-primary btn-xs" href="{ROW.url_edit}"><i class="fa fa-edit margin-right"></i>&nbsp;{GLANG.edit}</a></span>
					&nbsp;&nbsp;
					<span><a class="btn btn-warning btn-xs" href="javascript:void(0);" onclick="nv_delete_tags('{ROW.id}');"><i class="fa fa-trash-o margin-right"></i>&nbsp;{GLANG.delete}</a></span>
				</td>
			</tr>
		<!-- END: row -->
		</tbody>
	</table>
</form>
<!-- BEGIN: error -->
<div style="width: 98%;" class="quote">
    <blockquote class="error">
        <p>
            <span>{ERROR}</span>
        </p>
    </blockquote>
</div>
<div class="clear"></div>
<!-- END: error -->
<form class="form-inline" action="{FORM_ACTION}" method="post" name="levelform" id="levelform">
	<a name="addeditarea"></a>
	<table class="table table-striped table-bordered table-hover">
		<caption>{TABLE_CAPTION}</caption>
		<tbody>
			<tr>
				<td style="width:100px">{LANG.title}</td>
				<td class="text-center" style="width:10px"><span class="requie">*</span></td>
				<td><input class="form-control" type="text" name="title" value="{DATA.title}" style="width:350px"/> <input class="btn btn-primary" type="submit" name="submit" value="{LANG.submit}"/></td>
			</tr>
		</tbody>
	</table>
</form>
<!-- END: main -->