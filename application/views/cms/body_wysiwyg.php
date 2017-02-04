<div id="content_edit" class="list">
	<div>
		<textarea id="input" style="width:400px; height:200px"></textarea>
		<script type="text/javascript">
		new TINY.editor.edit('editor',{
			id:'input',
			width:700,
			height:450,
			cssclass:'te',
			controlclass:'tecontrol',
			rowclass:'teheader',
			dividerclass:'tedivider',
			controls:['bold','italic','underline','strikethrough','|','subscript','superscript','|',
					  'orderedlist','unorderedlist','|','outdent','indent','|','leftalign',
					  'centeralign','rightalign','blockjustify','|','unformat','|','undo','redo','n',
					  'font','size','style','|','image','hr','link','unlink','|','cut','copy','paste','print'],
			footer:true,
			fonts:['PT Sans','Verdana','Arial','Georgia','Trebuchet MS'],
			xhtml:true,
			cssfile:'style.css',
			bodyid:'editor',
			footerclass:'tefooter',
			toggle:{text:'source',activetext:'wysiwyg',cssclass:'toggle'},
			resize:{cssclass:'resize'},
			<?php echo $load;?>
		);
		</script>
	</div>
</div>
<div class="pop-up-bg">

</div>
<div id="ext-image" class="pop-up" style="display:none;">
	<form id="ext-image-form">
		<div>
			<label>Šírka (pixelov)</label>
			<input name="width_px" type="text"> 
		</div>
		<div>
			<label>Obtekanie</label>
			<select name="float" id="block">
				<option value="none">žiadne</option>
				<option value="left">z ľava</option>
				<option value="right">z prava</option>
			</select> 
		</div>
		<div>
			<label>Opacita (od 0 do 1)</label>
			<input name="opacity" type="text">
		</div>
		<div>
			<input class="save" type="button" value="Uložiť" value="Uložiť">
		</div>
	</form>
</div>