
<script type="text/javascript" src="<?php 
	echo JURI::base(true); 
	?>/components/com_articleinjector/js/articleinjector.js"></script>
<div>
	Status: <span id="txtStatus" class="badge"></span>
</div>
<table class="table">
	<tbody id="entry_table">
		<tr>
			<th style="width:5%;">ID</th>
			<th style="width:20%;">Article Title</th>
			<th style="width:60%;">Intro Text</th>
			<th style="width:20%;">Category</th>
			<th style="width:5%;">Published</th>
		</tr>
		<tr id="proto_row" style="display:none;">
			<td class="ai_id"></td>
			<td class="ai_title"></td>
			<td class="ai_text"></td>
			<td class="ai_category"></td>
			<td class="ai_published"></td>
		</tr>
		<tr id="entry_row">
			<td>-</td>
			<td>
				<input style="width:100%;" type="text" name="article_title" id="article_title" />
			</td>
			<td>
				<input style="width:100%;" type="text" name="intro_text" id="intro_text" />
			</td>
			<td>
				<input style="width:100%;" type="text" name="category" id="category" />
			</td>
			<td>
				<input type="checkbox" id="chkPublish" name="chkPublish" value="" />
			</td>
		</tr>
	</tbody>
</table>
<div style="text-align:right;">
	<button class="btn btn-success" type="button" onclick="ajaxInsert();return false;">Add Article</button>
</div>
