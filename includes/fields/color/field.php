	<div class="row form-group">
		<label class="control-label col-sm-3" for="{{_id}}"><?php echo $field['label']; ?></label>
		<div class="col-sm-9">
			<input type="text" value="{{value}}" name="{{:name}}[value]" id="{{_id}}" class="form-control color-field">
			<?php if( !empty( $field['description'] ) ){ echo '<p class="description">' . $field['description'] . '</p>'; } ?>
		</div>
	</div>
