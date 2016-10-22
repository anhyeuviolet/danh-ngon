<!-- BEGIN: main -->
<!-- BEGIN: error -->
<div class="alert alert-warning">
	<p>
		<span>{ERROR}</span>
	</p>
</div>
<div class="clear"></div>
<!-- END: error -->
<form class="form-inline" action="{FORM_ACTION}" method="post">
    <table class="table table-striped table-bordered table-hover">
		<col width="200px"/>
		<caption>{TABLE_CAPTION}</caption>
		<tbody>
			<tr>
				<td><strong>{LANG.tags}</strong></td>
				<td>
					<div class="tags-area">
						<!-- BEGIN: tags -->
						<div class="tag-item">
							<label>
								<input type="checkbox" name="tags[]" value="{TAGS}"{CHECKED}/> {TAGS}
							</label>
						</div>
						<!-- BEGIN: break --><div class="clear"></div><!-- END: break -->
						<!-- END: tags -->
					</div>
				</td>
			</tr>
			<tr>
				<td><strong>{LANG.content_new_tags}</strong></td>
				<td><input type="text" name="tags_news" value="" class="form-control"/></td>
			</tr>
			<tr>
				<td><strong>{LANG.content_content}</strong></td>
				<td><textarea rows="5" class="form-control" name="content">{DATA.content}</textarea></td>
			</tr>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="2" class="text-center">
					<input class="btn btn-primary" type="submit" name="submit" value="{LANG.submit}" />
				</td>
			</tr>
		</tfoot>
    </table>
</form>
<!-- END: main -->