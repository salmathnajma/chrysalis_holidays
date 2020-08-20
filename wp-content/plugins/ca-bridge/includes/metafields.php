<?php
include_once dirname( __FILE__ ) . '/functions.php';

function portfolio_add_meta_boxes( $post ){
	add_meta_box( CHILD_TYPE_SLUG.'_meta_box',  CHILD_TYPE_NAME.' Settings (CA Bridge)', 'portfolio_build_meta_box', CHILD_TYPE_SLUG, 'normal' );
}
add_action( 'add_meta_boxes_'.CHILD_TYPE_SLUG, 'portfolio_add_meta_boxes' );

function portfolio_build_meta_box( $post ){
    wp_nonce_field( basename( __FILE__ ), CHILD_TYPE_SLUG.'_meta_box_nonce' );
    
    // retrieve the current values
    $lead_type = get_post_meta( $post->ID, 'lead_type', true );
    $hero = get_post_meta( $post->ID, 'hero', true );
    $hero_image = get_post_meta( $post->ID, 'hero-image', true );
    $clients = get_post_meta( $post->ID, 'clients', true );

    $client_list = get_clients_list();
    
    $lead_type_options =  array(	"image" => "Single Lead Image",
                "slideshow" => "Slideshow",
                "audio" => "Audio",
                "video" => "Video" 
            );
    ?>
    <table class="ca_metaboxes_table">
        <tr>
            <th class="ca_metabox_names">
                <label for="lead_type">Lead type</label>
            </th>
            <td>
                <select class="ca_input_select" id="lead_type" name="lead_type">
                <option value="">Select to return to default</option>
                <?php
                if ($lead_type_options) {
        	        
                    foreach ( $lead_type_options as $key => $option ) {
                        $selected = '';
                         
                        if ( $lead_type == $key )
                            $selected = 'selected="selected"';
                        
                        echo '<option value="'. $key .'" '. $selected .'>' . $option .'</option>';

                    }
                }
                ?>
                </select>
                <span class="ca_metabox_desc">Select type of Lead to show</span>
            </td>
        </tr>
        <tr>
            <th class="ca_metabox_names">
                <label for="hero">Show Hero Image</label>
            </th>
            <td>
                <?php
                if ( $hero )
                    $checked = ' checked="checked"';
                else
                    $checked = '';
                ?>
                <input type="checkbox" <?php echo $checked; ?> class="ca_input_checkbox" value="true"  id="hero" name="hero" />
                <span class="ca_metabox_desc">Display Hero Image (Behind Page Title)</span>
            </td>
        </tr>
        <tr>
            <th class="ca_metabox_names">
                <label for="hero-image">Hero Image</label>
            </th>
            <td class="ca_metabox_fields">
            <?php
                if ( function_exists( 'caframework_medialibrary_uploader' ) ){
                    echo caframework_medialibrary_uploader( 'caframework', 'hero-image', $hero_image, 'postmeta','Upload file here...', $post->ID );
                }else{
                    echo ca_uploader_custom_fields($post->ID,'hero-image',$hero_image,'Upload file here...');
                }
            ?>
            </td>
        </tr>
        <tr>
            <th class="ca_metabox_names">
                <label for="clients"><?php echo PARENT_TYPE_NAME; ?></label>
            </th>
            <td>
                <select multiple="multiple" class="ca_input_select chosen_select" id="clients" name="clients[]">
                 <?php
                if ($client_list) {
        	        
                    foreach ( $client_list as $key => $option ) {
                        $selected = '';
                         
                        if ( !empty( $clients ) ) {
                            if ( multi_array_search( $key, $clients ) )
                                $selected = 'selected="selected"';
                        }
                        
                        echo '<option value="'. $key .'" '. $selected .'>' . $option .'</option>';

                    }
                }
                ?>
                </select>
                <span class="ca_metabox_desc">Choose <?php echo PARENT_TYPE_PLURAL; ?> that are part of this <?php echo strtolower(CHILD_TYPE_NAME); ?></span>
            </td>
        </tr>
    </table>
    <?php
   
}

function portfolio_save_meta_boxes_data( $post_id ){
    // verify meta box nonce
    if ( !isset( $_POST[CHILD_TYPE_SLUG.'_meta_box_nonce'] ) || !wp_verify_nonce( $_POST[CHILD_TYPE_SLUG.'_meta_box_nonce'], basename( __FILE__ ) ) )
        return;

    // return if autosave
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
        return;
    
    // Check the user's permissions.
    if ( ! current_user_can( 'edit_post', $post_id ) )
        return;
    
    if ( isset( $_REQUEST['lead_type'] ) ) 
        update_post_meta( $post_id, 'lead_type', sanitize_text_field( $_POST['lead_type'] ) );

    if ( isset( $_REQUEST['hero'] ) ) 
        update_post_meta( $post_id, 'hero', sanitize_text_field( $_POST['hero'] ) );

    if ( isset( $_REQUEST['hero-image'] ) ) 
        update_post_meta( $post_id, 'hero-image', sanitize_text_field( $_POST['hero-image'] ) );

    if ( isset( $_REQUEST['clients'] ) ) {

       

        if ( isset( $_POST['clients'] ) ) {
					
            $posted_value = '';
            $posted_value = $_POST['clients'];
            
            
            $data = array();
            $ids = $posted_value;
             foreach ( $ids as $id ) {
                if ( $id && $id>0 )
                    $data[] = $id;
            }
            $posted_value = $data;
        }
       
        // If it doesn't exist, add the post meta.
        if ( get_post_meta( $post_id, 'clients' ) == "" ) { 
            add_post_meta( $post_id, 'clients', $posted_value, true );
        }
        // Otherwise, if it's different, update the post meta.
        elseif( $posted_value != get_post_meta( $post_id, 'clients', true ) ) { 
            update_post_meta( $post_id, 'clients', $posted_value );
        
        }
        // Otherwise, if no value is set, delete the post meta.
        elseif( $posted_value == "" ) { 
            delete_post_meta( $post_id, 'clients', get_post_meta( $post_id,'clients', true ) );
        }
		
    }
       
    
}
add_action( 'save_post_'.CHILD_TYPE_SLUG, 'portfolio_save_meta_boxes_data', 10, 2 );




?>