<div class="wrap">
    <h1 class="wp-heading-inline">
        <?php echo $this->base->plugin->displayName; ?>

        <span>
            <?php 
		    _e( 'Settings', $this->base->plugin->name );
		    ?>
        </span>
    </h1>

    <?php
    // Notices
    foreach ( $this->notices as $type => $notices_type ) {
        if ( count( $notices_type ) == 0 ) {
            continue;
        }
        ?>
        <div class="<?php echo ( ( $type == 'success' ) ? 'updated' : $type ); ?> notice">
            <?php
            foreach ( $notices_type as $notice ) {
                ?>
                <p><?php echo $notice; ?></p>
                <?php
            }
            ?>
        </div>
        <?php
    }
    ?>

    <div class="wrap-inner">
    	<form class="comment-rating-field-pro-plugin" name="post" method="post" action="admin.php?page=comment-rating-field-plugin-settings" enctype="multipart/form-data">		
	    	<div id="poststuff">
    		
	    		<div id="post-body" class="metabox-holder columns-2">
	    			<!-- Content -->
		    		<div id="post-body-content">
			    		<!-- Name -->
			    		<input type="hidden" name="name" id="title" value="<?php echo $group['name']; ?>" size="30" placeholder="<?php _e( 'Field Group Name', 'comment-rating-field-pro-plugin' ); ?>" />
			    		<input type="hidden" name="id" id="id" value="<?php echo ( isset( $group['groupID'] ) ? $group['groupID'] : '' ); ?>" />
			    		
			            <div id="normal-sortables" class="meta-box-sortables ui-sortable">                        
			               	<!-- Schema Type -->
			               	<div class="postbox">
		                        <h3 class="hndle"><?php _e( 'General', 'comment-rating-field-pro-plugin' ); ?></h3>
		                    
								<div class="option">
									<div class="left">
										<strong><?php _e( 'Empty Color', 'comment-rating-field-pro-plugin' ); ?></strong>
									</div>
									<div class="right">
										<input type="text" name="css[starBackgroundColor]" value="<?php echo $group['css']['starBackgroundColor']; ?>" class="color-picker-control" />
									
										<p class="description">
					                        <?php _e( 'The color of empty stars/bars.', 'comment-rating-field-pro-plugin' ); ?>
				                        </p>
			                        </div>
								</div>
								
								<div class="option">
									<div class="left">
										<strong><?php _e( 'Filled Color', 'comment-rating-field-pro-plugin' ); ?></strong>
									</div>
									<div class="right">
										<input type="text" name="css[starColor]" value="<?php echo $group['css']['starColor']; ?>" class="color-picker-control" />
									
										<p class="description">
					                        <?php _e( 'The color of filled stars/bars.', 'comment-rating-field-pro-plugin' ); ?>
				                        </p>
			                        </div>
								</div>
								
								<div class="option">
									<div class="left">
										<strong><?php _e( 'Selected Stars Color', 'comment-rating-field-pro-plugin' ); ?></strong>
									</div>
									<div class="right">
										<input type="text" name="css[starInputColor]" value="<?php echo $group['css']['starInputColor']; ?>" class="color-picker-control" />
									
										<p class="description">
					                        <?php _e('The color of selected stars when adding a rating on the comments form.', 'comment-rating-field-pro-plugin'); ?>
				                        </p>
				                    </div>
								</div>
								
								<div class="option">
									<div class="left">
										<strong><?php _e( 'Star Size', 'comment-rating-field-pro-plugin' ); ?></strong>
									</div>
									<div class="right">
										<input type="number" name="css[starSize]" min="16" max="128" step="1" value="<?php echo $group['css']['starSize']; ?>" />
										<?php _e( 'px', 'comment-rating-field-pro-plugin' ); ?>
									
										<p class="description">
					                        <?php _e( 'The size, in pixels, to output stars.', 'comment-rating-field-pro-plugin' ); ?>
				                        </p>
				                    </div>
								</div>
			               	</div>
			               
			                <!-- Fields -->
		                    <div class="postbox">
		                        <h3 class="hndle"><?php _e( 'Rating Fields', 'comment-rating-field-pro-plugin' ); ?></h3>
		                        <div class="option">
			                        <p class="description">
				                        <?php _e( 'Define the rating fields to display in this group.', 'comment-rating-field-pro-plugin' ); ?>
			                        </p>
		                        </div>
		                        
		                        <div id="sortable">
			                    	<?php
				                    // Output existing fields
				                    foreach ( $group['fields'] as $field ) {
				                    	?>
					                    <div class="option">
					                        <div class="left">
					                        	<strong>
						                        	<a href="#" class="dashicons dashicons-sort"></a>
						                        	<span><?php _e( 'Field', 'comment-rating-field-pro-plugin'); ?> #</span>
						                        	<span class="hierarchy"><?php echo $field['hierarchy']; ?></span>
						                        </strong>
						                    </div>
											<div class="right">
					                        	<input type="text" name="fields[label][]" value="<?php echo $field['label']; ?>" placeholder="<?php _e( 'Label', 'comment-rating-field-pro-plugin' ); ?>" />
					                        	<select name="fields[required][]" size="1">
						                        	<option value="0"<?php echo ( ( $field['required'] != 1 ) ? ' selected' : '' ); ?>>
						                        		<?php _e( 'Not Required', 'comment-rating-field-pro-plugin' ); ?>
						                        	</option>
						                        	<option value="1"<?php selected( $field['required'], 1 ); ?>>
						                        		<?php _e( 'Required', 'comment-rating-field-pro-plugin' ); ?>
						                        	</option>
						                        </select>
						                        <input type="text" name="fields[required_text][]" value="<?php echo $field['required_text']; ?>" placeholder="<?php _e( 'Required Text', 'comment-rating-field-pro-plugin' ); ?>" />
					                        	<input type="text" name="fields[cancel_text][]" value="<?php echo $field['cancel_text']; ?>" placeholder="<?php _e( 'Cancel Text', 'comment-rating-field-pro-plugin' ); ?>" />
					                        	<input type="hidden" name="fields[fieldID][]" value="<?php echo $field['fieldID']; ?>" />
					                        </div>
				                        </div>
					                    <?php
				                    }
				                    ?>    
		                        </div>

		                        <div class="option highlight">
								    <div class="full">
								        <h4><?php _e( 'Add Multiple Rating Fields', $this->base->plugin->name ); ?></h4>

								        <p>
								            <?php _e( 'Easily add any number of rating fields, each with different Targeted Placement Options, by upgrading to Comment Rating Field Pro.', $this->base->plugin->name ); ?>
								        </p>
								        
								        <a href="<?php echo $this->base->dashboard->get_upgrade_url( 'settings_inline_upgrade' ); ?>" class="button button-primary" target="_blank"><?php _e( 'Upgrade', $this->base->plugin->name ); ?></a>
								    </div>
								</div>
							</div>
							
							<!-- Rating Input -->
							<div class="postbox">
		                        <h3 class="hndle"><?php _e( 'Rating Input', 'comment-rating-field-pro-plugin' ); ?></h3>
		                        
		                        <div class="option highlight">
								    <div class="full">
								        <h4><?php _e( 'Need greater control over rating inputs?', $this->base->plugin->name ); ?></h4>

								        <p>
								            <?php _e( 'Define the maximum star rating, precision, placement and rating limits (by user role, number of ratings per user) and more with Comment Rating Field Pro.', $this->base->plugin->name ); ?>
								        </p>
								        
								        <a href="<?php echo $this->base->dashboard->get_upgrade_url( 'settings_inline_upgrade' ); ?>" class="button button-primary" target="_blank"><?php _e( 'Upgrade', $this->base->plugin->name ); ?></a>
								    </div>
								</div>
		                    </div>

							<?php            
							// Iterate through excerpt, comment and RSS groups to output settings
							foreach ( Comment_Rating_Field_Pro_Groups::get_instance()->get_output_group_types() as $key => $labels ) {
								?>
								<div class="postbox">
			                        <h3 class="hndle"><?php echo $labels['title']; ?></h3>

			                        <div class="option">
				                        <p class="description">
					                        <?php echo sprintf( __( 'Allows you to display average ratings anywhere where your theme outputs %s.', 'comment-rating-field-pro-plugin' ), $labels['type'] ); ?>
				                        </p>
			                        </div>
			                        
			                        <!-- Enabled -->
			                        <div class="option">
		                            	<div class="left">
		                            		<strong><?php _e( 'Enabled', 'comment-rating-field-pro-plugin' ); ?></strong>
		                            	</div>
										<div class="right">
		                                	<select name="<?php echo $key; ?>[enabled]" size="1" data-conditional="<?php echo $key; ?>-options">
		                                    	<option value="0"<?php selected( $group[ $key ]['enabled'], 0 ); ?>>
													<?php _e( 'Never Display', 'comment-rating-field-pro-plugin' ); ?>
												</option>
												<option value="1"<?php selected( $group[ $key ]['enabled'], 1 ); ?>>
													<?php _e( 'Display when Ratings Exist', 'comment-rating-field-pro-plugin' ); ?>
												</option>
												<option value="2"<?php selected( $group[ $key ]['enabled'], 2 ); ?>>
													<?php _e( 'Always Display', 'comment-rating-field-pro-plugin' ); ?>
												</option>
		                                    </select>
		                                </div>
		                            </div>
		                            
		                            <div id="<?php echo $key; ?>-options">  
		                            	<?php
				                        // Average Label and Position
				                        if ( isset( $group[ $key ]['averageLabel'] ) ) {
		                            		?> 
				                            <!-- Average Label and Position -->
					                        <div class="option">
				                            	<div class="left">
				                            		<strong><?php _e( 'Average Label', 'comment-rating-field-pro-plugin' ); ?></strong>
				                            	</div>
												<div class="right">
				                                	<input type="text" name="<?php echo $key; ?>[averageLabel]" value="<?php echo $group[ $key ]['averageLabel']; ?>" placeholder="<?php _e( 'Average Rating Label', 'comment-rating-field-pro-plugin' ); ?>" />
				                                </div>
				                            </div>
				                            <?php
				                        }
				                        ?>
		                            </div>
		                            <!-- ./extra-options -->

		                            <div class="option highlight">
									    <div class="full">
									        <h4><?php _e( 'More Output Options', $this->base->plugin->name ); ?></h4>

									        <p>
									            <?php _e( 'Choose where to Position ratings in your Excerpts and Content, display Total Ratings, Breakdowns and Amazon Bar Styles.<br />Give visitors options to Filter Comments by Rating with Comment Rating Field Pro.', $this->base->plugin->name ); ?>
									        </p>
									        
									        <a href="<?php echo $this->base->dashboard->get_upgrade_url( 'settings_inline_upgrade' ); ?>" class="button button-primary" target="_blank"><?php _e( 'Upgrade', $this->base->plugin->name ); ?></a>
									    </div>
									</div>
								</div>
								<?php
							} // Close foreach
							?>
							
							<!-- Rating Output: Comments -->
							<div class="postbox">
		                        <h3 class="hndle"><?php _e( 'Rating Output: Comments', 'comment-rating-field-pro-plugin' ); ?></h3>

		                        <div class="option">
			                        <p class="description">
				                        <?php _e( 'Defines how ratings are displayed on Comments.', 'comment-rating-field-pro-plugin' ); ?>
			                        </p>
		                        </div>
		                        
		                        <!-- Enabled -->
		                        <div class="option">
	                            	<div class="left">
	                            		<strong><?php _e( 'Enabled', 'comment-rating-field-pro-plugin' ); ?></strong>
	                            	</div>
									<div class="right">
	                                	<select name="ratingOutputComments[enabled]" size="1" data-conditional="comment-options">
	                                    	<option value="0"<?php selected( $group['ratingOutputComments']['enabled'], 0 ); ?>>
	                                    		<?php _e( 'Never Display', 'comment-rating-field-pro-plugin' ); ?>
	                                    	</option>
	                                    	<option value="1"<?php selected( $group['ratingOutputComments']['enabled'], 1 ); ?>>
	                                    		<?php _e( 'Display when Ratings Exist', 'comment-rating-field-pro-plugin' ); ?>
	                                    	</option>
	                                    	<option value="2"<?php selected( $group['ratingOutputComments']['enabled'], 2 ); ?>>
	                                    		<?php _e( 'Always Display', 'comment-rating-field-pro-plugin' ); ?>
	                                    	</option>
	                                    </select>
	                                </div>
	                            </div>
	                            
	                            <div id="comment-options">
		                            <!-- Average Label and Position -->
			                        <div class="option">
		                            	<div class="left">
		                            		<strong><?php _e( 'Average Label', 'comment-rating-field-pro-plugin' ); ?></strong>
		                            	</div>
										<div class="right">
											<input type="text" name="ratingOutputComments[averageLabel]" value="<?php echo $group['ratingOutputComments']['averageLabel']; ?>" placeholder="<?php _e( 'Average Rating Label', 'comment-rating-field-pro-plugin' ); ?>" / >
	                            		</div>
				                    </div>
		                            
		                            <div class="option highlight">
									    <div class="full">
									        <h4><?php _e( 'More Output Options', $this->base->plugin->name ); ?></h4>

									        <p>
									            <?php _e( 'Display Total Ratings, Breakdowns and Sort/Filtering options for visitors to see the highest and lowest ratings, with Comment Rating Field Pro.', $this->base->plugin->name ); ?>
									        </p>
									        
									        <a href="<?php echo $this->base->dashboard->get_upgrade_url( 'settings_inline_upgrade' ); ?>" class="button button-primary" target="_blank"><?php _e( 'Upgrade', $this->base->plugin->name ); ?></a>
									    </div>
									</div>
	                            </div>
	                            <!-- ./extra-options -->
							</div>
			                
			            	<!-- Save -->
			                <div class="submit">
				                <?php wp_nonce_field( 'save_group', $this->base->plugin->name . '_nonce' ); ?>
			                	<input type="submit" name="submit" value="<?php _e( 'Save', 'comment-rating-field-pro-plugin' ); ?>" class="button-primary" />
			                </div>

			                <hr /><br /><br />

			                <!-- Upgrade -->
			                <?php require( $this->base->plugin->folder . '/_modules/dashboard/views/footer-upgrade.php' ); ?>
						</div>
						<!-- /normal-sortables -->	    			
		    		</div>
		    		<!-- /post-body-content -->
		    		
		    		<!-- Sidebar -->
		    		<div id="postbox-container-1" class="postbox-container">
		    		    <!-- Targeted Placement Options -->
		                <div class="postbox targeted-placement-options">
		                    <h3 class="hndle"><?php _e( 'Targeted Placement Options', 'comment-rating-field-pro-plugin' ); ?></h3>

	                		<?php
	                        // Go through all Post Types
	                    	$post_types = Comment_Rating_Field_Pro_Common::get_instance()->get_post_types();
	                    	foreach ( $post_types as $type => $post_type ) {
	                    		?>
	                    		<div class="option">
		                    		<label for="placement_options_type_<?php echo $post_type->name; ?>">
										<div class="left">
											<strong><?php echo sprintf( __( 'Enable on %s', 'comment-rating-field-pro-plugin' ), $post_type->labels->name ); ?></strong>
										</div>
										<div class="right">
											<input id="placement_options_type_<?php echo $post_type->name; ?>" type="checkbox" name="placementOptions[type][<?php echo $type; ?>]" value="1"<?php echo ( isset( $group['placementOptions']['type'][ $type ] ) ? ' checked' : ''); ?> />
										</div>
									</label>
								</div>
								<?php
							}
	                    	?>
						</div>

		                <!-- Save -->
		                <div class="postbox targeted-placement-options">
		                    <h3 class="hndle"><?php _e( 'Publish', 'comment-rating-field-pro-plugin' ); ?></h3>
		                    <div class="inside">
								<input type="submit" name="submit" value="<?php _e( 'Save', 'comment-rating-field-pro-plugin'); ?>" class="button button-primary" />
		                	</div>
		                </div>
				
		    		</div>
		    	</div>
		    </div>
		</form>
		<!-- /form end -->		
	</div><!-- ./wrap-inner -->         
</div>