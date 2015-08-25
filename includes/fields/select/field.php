<div class="row form-group">
	<label class="control-label col-sm-3" for="{{_id}}"><?php echo $field['label']; ?></label>
	<div class="col-sm-9">
		<select value="{{value}}" data-live-sync="true" name="{{:name}}[value]" id="{{_id}}" class="form-control">
			<option></option>
			<?php foreach( $field['options'] as $value=>$label ){ ?>
				<option value="<?php echo esc_attr( $value ); ?>" {{#is value value="<?php echo $value; ?>"}} selected="selected"{{/is}}><?php echo esc_html( $label ); ?></option>
			<?php } ?>
			<?php if( !empty( $field['other'] ) ){ ?>
			<option value="_other" {{#is value value="_other"}}selected="selected"{{/is}}>Other</option>
			<?php } ?>
		</select>
		{{#is value value="_other"}}
		if other: <input type="text" name="{{:name}}[other]" value="{{other}}">
		{{/is}}
		<?php if( !empty( $field['description'] ) ){ echo '<p class="description">' . $field['description'] . '</p>'; } ?>
	</div>
</div>
