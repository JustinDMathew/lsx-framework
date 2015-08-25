<div id="lsx-tours" class="lsx-editor-panel" {{#is _current_tab value="#lsx-tours"}}{{else}} style="display:none;" {{/is}}>		
	<h4>
		<?php _e('Settings', 'lsx-tours') ; ?>
	</h4>
	
	<?php 
	$forms = get_option( '_caldera_forms' , false );
	if(false !== $forms && class_exists('Caldera_Forms') ) {
		?>
		
		<div class="lsx-config-group">
		
			<label for="lsx-tours-google-api-key">
				<?php _e( 'Google Maps API Key', 'lsx-tours' ); ?>
			</label>
		
			<input id="lsx-tours-google-api-key" type="text" class="regular-text" name="lsx-tours[google_api_key]" value="{{lsx-tours/google_api_key}}" >
		</div>		
		
		<div class="lsx-config-group">
			<label for="lsx-tour-enquire-form">
				<?php _e( 'Enquire Form', 'lsx-tours' ); ?>
			</label>
		
			<select id="lsx-tour-enquire-form" type="text" class="regular-text" name="lsx-tours[enquire_form]" value="{{lsx-tours/enquire_form}}" >
			<option value="0"><?php _e( 'Select a form', 'lsx-tours' ); ?></option>
			<?php 
				//$enquire_form_id = 
				foreach($forms as $form_id=>$form){ ?>
					<option {{#is lsx-tours/enquire_form value="<?php echo $form_id; ?>"}} selected="selected" {{/is}} value="<?php echo $form_id; ?>"><?php echo $form['name']; ?></option>
			<?php } ?>
			</select>
		</div>
		
		<div class="lsx-config-group">
			<label for="lsx-tour-booking-form">
				<?php _e( 'Booking Form', 'lsx-tours' ); ?>
			</label>
		
			<select id="lsx-tour-booking-form" type="text" class="regular-text" name="lsx-tours[booking_form]" value="{{lsx-tours/booking_form}}" >
			<option value="0"><?php _e( 'Select a form', 'lsx-tours' ); ?></option>
			<?php 
				foreach($forms as $form_id=>$form){ ?>
					
					<option {{#is lsx-tours/booking_form value="<?php echo $form_id; ?>"}} selected="selected" {{/is}} value="<?php echo $form_id; ?>"><?php echo $form['name']; ?></option>
				
			<?php } ?>
			</select>
		</div>		
	
	<?php } ?>	

</div>