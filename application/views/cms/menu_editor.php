<div>
	<div id="functions" class="list">
		<div class="listName">
			Functions
		</div>
		<ul>
			<li class="clickable"><a href="javascript:void(0);" onClick="editor.save();">save</a></li>
		</ul>
	</div>
	<div id="content_edit" class="list">
		<div class="listName clickable" onClick="toggleVisibility('#content_edit_body');">
			Content
		</div>
		<ul id="content_edit_body">
			<form>
				<div>
					<div><label for="blog_title">title</label></div>
					<div><input id="blog_title" type="text" name="title" value="<?php echo $editor_title;?>" onInput="editor.setTitle();" /></div>
				</div>
				<div>
					<div><label for="blog_text">text</label></div>
					<div><textarea id="blog_text" name="text" onInput="editor.setBlogText();"><?php echo $editor_body;?></textarea></div>
				</div>
				<div>
					<input class="blog_edit_button" type="button" name="bold" value="B" onClick="editor.setBold();" />
					<input class="blog_edit_button" type="button" name="italic" value="I" onClick="editor.setItalic();" />
					<input class="blog_edit_button" type="button" name="textTitle" value="title" onClick="editor.setTextTitle(3);" />
				</div>
			</form>
		</ul>
	</div>
	
	<div id="link_edit" class="list">
		<div class="listName clickable" onClick="toggleVisibility('#link_edit_body');">
			Links
		</div>
		<ul id="link_edit_body" style="display: none;">
			<div>
				<form>
					<div>
						<div><label for="blog_link_link">link</label></div>
						<div><input id="blog_link_link" name="link_link" type="text" /></div>
					</div>
					<div style="float:left;">
						<div><label for="blog_link_text">text</label></div>
						<div><input id="blog_link_text" class="half_size" name="link_text" type="text" /></div>
					</div>
					<div style="float:left;">
						<div><label>actions</label></div>
						<div>
							<input type="button" name="link_clear" value="new" onClick="editor.components.link.setSelected(0);" />
							<input type="button" name="link_save" value="save" onClick="editor.components.link.setList();" />
						</div>
					</div>
				</form>
			</div>
			<div id="blog_link_list">
			</div>
		</ul>
	</div>
	<div id="image_edit" class="list">
		<div class="listName clickable" onClick="toggleVisibility('#image_edit_body');">
			Images
		</div>
		<ul id="image_edit_body" style="display: none;">
			<div>
				<form id="blog_image_form">
					<div>
						<div><label for="blog_image_link">link</label></div>
						<div><input id="blog_image_link" name="image_link" type="text" /></div>
					</div>
					<div>
						<div style="float:left;">
							<div><label for="blog_image_width">width</label></div>
							<div><input id="blog_image_width" class="half_size" type="text" name="image_width" /></div>
						</div>
						<div style="float:left;">
							<div><label>alignment</label></div>
							<div>
								<input id="image_aligment_left" type="radio" name="image_alignment" value="left" checked />
								<label for="image_aligment_left">left</label>
								<input id="image_aligment_right" type="radio" name="image_alignment" value="right" />
								<label for="image_aligment_right">right</label>
							</div>
						</div>
					</div>
					<div>
						<div style="float:left;">
							<div><label for="blog_image_text">text</label></div>
							<div><input id="blog_image_text" class="half_size" name="image_text" type="text" /></div>
						</div>
						<div style="float:left;">
							<div><label>actions</label></div>
							<div>
								<input type="button" name="image_clear" value="new" onClick="editor.components.image.setSelected(0);" />
								<input type="button" name="image_save" value="save" onClick="editor.components.image.setList();" />
							</div>
						</div>
					</div>
				</form>
			</div>
			<div id="blog_image_list">
			</div>
		</ul>
	</div>
	<div id="video_edit" class="list">
		<div class="listName clickable" onClick="toggleVisibility('#video_edit_body');">
			Videos
		</div>
		<ul id="video_edit_body" style="display: none;">
			<div>
				<form id="blog_video_form">
					<div>
						<div><label for="blog_video_link">link</label></div>
						<div><input id="blog_video_link" name="video_link" type="text" /></div>
					</div>
					<div>
						<div style="float:left;">
							<div><label for="blog_video_width">width</label></div>
							<div><input id="blog_video_width" class="half_size" type="text" name="video_width" /></div>
						</div>
						<div style="float:left;">
							<div><label>alignment</label></div>
							<div>
								<input id="video_aligment_left" type="radio" name="video_alignment" value="left" checked />
								<label for="video_aligment_left">left</label>
								<input id="video_aligment_right" type="radio" name="video_alignment" value="right" />
								<label for="video_aligment_right">right</label>
							</div>
						</div>
					</div>
					<div>
						<div style="float:left;">
							<div><label for="blog_video_text">text</label></div>
							<div><input id="blog_video_text" class="half_size" name="video_text" type="text" /></div>
						</div>
						<div style="float:left;">
							<div><label>actions</label></div>
							<div>
								<input type="button" name="video_clear" value="new" onClick="editor.components.video.setSelected(0);" />
								<input type="button" name="video_save" value="save" onClick="editor.components.video.setList();" />
							</div>
						</div>
					</div>
				</form>
			</div>
			<div id="blog_video_list">
			</div>
		</ul>
	</div>
	<div id="tag_edit" class="list">
		<div class="listName clickable" onClick="toggleVisibility('#tag_edit_body');">
			Tags
		</div>
		<ul id="tag_edit_body" style="display: none;">
			<div>
				<form id="blog_tag_form">
					<div>
						<div><label for="blog_tag_series">project</label></div>
						<div>
							<select id="blog_tag_series" name="blog_series" onChange="editor.components.tag.setSeries();">
								<option value="0">none</option>
								<?php 
								if (isset($project)){
									foreach ($project as $p){
										if (isset($series_id) && $p['id'] == $series_id){
											?>
											<option value="<?php echo $p['id'];?>" selected="selected"><?php echo get_lang_value($p['project_name']);?></option>
											<?php
										}
										else{
											?>
											<option value="<?php echo $p['id'];?>"><?php echo get_lang_value($p['project_name']);?></option>
											<?php
										}
									}
								}
								?>
							</select>
						</div>
					</div>
					<div>
						<div><label for="blog_tag_thumbnail">thumbnail</label></div>
						<div>
							<select id="blog_tag_thumbnail" name="blog_thumbnail" onChange="editor.components.tag.changeThumbnail();">
								<option value="0">none</option>
							</select>
						</div>
					</div>
					<div>
						<div style="float:left;">
							<div><label for="blog_tag_text">tag</label></div>
							<div><input id="blog_tag_text" class="half_size" name="tag_text" type="text" /></div>
						</div>
						<div style="float:left;">
							<div><label>actions</label></div>
							<div>
								<input type="button" name="tag_clear" value="new" onClick="editor.components.tag.setSelected(0);" />
								<input type="button" name="tag_save" value="save" onClick="editor.components.tag.setList();" />
							</div>
						</div>
					</div>
				</form>
			</div>
			<div id="blog_tag_list">
			</div>
		</ul>
	</div>
</div>