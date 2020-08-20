<?php
class BridgeSettings{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

    public function __construct(){
        add_action( 'admin_menu', array( $this, 'add_bridge_page' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );
    }

    /**
     * Add options page
     */
    public function add_bridge_page(){
        // This page will be under "Settings"
        add_options_page(
            'Bridge Settings', 
            'Bridge Settings', 
            'manage_options', 
            'bridge-settings', 
            array( $this, 'create_admin_page' )
        );
    }

    /**
     * Options page callback
     */
    public function create_admin_page() {
        // Set class property
        $this->options = get_option( 'bridge_option_name' );
        ?>
        <div class="wrap">
            <h1>Bridge Settings</h1>
            <form method="post" action="options.php">
            <?php
                // This prints out all hidden setting fields
                settings_fields( 'bridge_option_group' );
                do_settings_sections( 'bridge-setting-admin' );
                submit_button();
            ?>
            </form>
        </div>
        <?php
    }

    /**
     * Register and add settings
     */
    public function page_init(){        
        register_setting(
            'bridge_option_group', 
            'bridge_option_name',
            array( $this, 'sanitize' ) 
        );

        add_settings_section(
            'setting_section_id', 
            'Image Sizes',
            array( $this, 'print_image_section_info' ), 
            'bridge-setting-admin' 
        );  

        add_settings_field(
            'parent_img_width', 
            PARENT_TYPE_NAME.' thumbnail width', 
            array( $this, 'parent_img_width_callback' ), 
            'bridge-setting-admin', 
            'setting_section_id'           
        );      

        add_settings_field(
            'parent_img_height', 
            PARENT_TYPE_NAME.' thumbnail height', 
            array( $this, 'parent_img_height_callback' ), 
            'bridge-setting-admin', 
            'setting_section_id'           
        ); 
        
        add_settings_field(
            'child_img_width', 
            CHILD_TYPE_NAME.' thumbnail width', 
            array( $this, 'child_img_width_callback' ), 
            'bridge-setting-admin', 
            'setting_section_id'           
        ); 

        add_settings_field(
            'child_img_height', 
            CHILD_TYPE_NAME.' thumbnail height', 
            array( $this, 'child_img_height_callback' ), 
            'bridge-setting-admin', 
            'setting_section_id'           
        );

        add_settings_field(
            'parent_archive_img_width', 
            PARENT_TYPE_NAME.' archive thumbnail width', 
            array( $this, 'parent_archive_img_width_callback' ), 
            'bridge-setting-admin', 
            'setting_section_id'           
        ); 

        add_settings_field(
            'parent_archive_img_height', 
            PARENT_TYPE_NAME.' archive thumbnail height', 
            array( $this, 'parent_archive_img_height_callback' ), 
            'bridge-setting-admin', 
            'setting_section_id'           
        );
        
        add_settings_field(
            'child_archive_img_width', 
            CHILD_TYPE_NAME.' archive thumbnail width', 
            array( $this, 'child_archive_img_width_callback' ), 
            'bridge-setting-admin', 
            'setting_section_id'           
        ); 

        add_settings_field(
            'child_archive_img_height', 
            CHILD_TYPE_NAME.' archive thumbnail height', 
            array( $this, 'child_archive_img_height_callback' ), 
            'bridge-setting-admin', 
            'setting_section_id'           
        );


        add_settings_section(
            'setting_section_archive', 
            'Archive Column Width',
            array( $this, 'print_archive_section_info' ), 
            'bridge-setting-admin' 
        );  

        add_settings_field(
            'parent_archive_col_width', 
            PARENT_TYPE_NAME.' archive column width', 
            array( $this, 'parent_archive_col_width_callback' ), 
            'bridge-setting-admin', 
            'setting_section_archive'           
        );

        add_settings_field(
            'child_archive_col_width', 
            CHILD_TYPE_NAME.' archive column width', 
            array( $this, 'child_archive_col_width_callback' ), 
            'bridge-setting-admin', 
            'setting_section_archive'           
        );

        add_settings_field(
            'category_archive_col_width', 
            CHILD_CATEGORY_NAME.' archive column width', 
            array( $this, 'category_archive_col_width_callback' ), 
            'bridge-setting-admin', 
            'setting_section_archive'           
        );
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input ){
        $new_input = array();
        if( isset( $input['parent_img_width'] ) )
            $new_input['parent_img_width'] = absint( $input['parent_img_width'] );

        if( isset( $input['parent_img_height'] ) )
            $new_input['parent_img_height'] = sanitize_text_field( $input['parent_img_height'] );

        if( isset( $input['child_img_width'] ) )
            $new_input['child_img_width'] = absint( $input['child_img_width'] );

        if( isset( $input['child_img_height'] ) )
            $new_input['child_img_height'] = sanitize_text_field( $input['child_img_height'] );

        if( isset( $input['parent_archive_img_width'] ) )
            $new_input['parent_archive_img_width'] = absint( $input['parent_archive_img_width'] );

        if( isset( $input['parent_archive_img_height'] ) )
            $new_input['parent_archive_img_height'] = sanitize_text_field( $input['parent_archive_img_height'] );

        if( isset( $input['child_archive_img_width'] ) )
            $new_input['child_archive_img_width'] = absint( $input['child_archive_img_width'] );

        if( isset( $input['child_archive_img_height'] ) )
            $new_input['child_archive_img_height'] = sanitize_text_field( $input['child_archive_img_height'] );

        if( isset( $input['parent_archive_col_width'] ) )
            $new_input['parent_archive_col_width'] = sanitize_text_field( $input['parent_archive_col_width'] );

        if( isset( $input['child_archive_col_width'] ) )
            $new_input['child_archive_col_width'] = sanitize_text_field( $input['child_archive_col_width'] );

        if( isset( $input['category_archive_col_width'] ) )
            $new_input['category_archive_col_width'] = sanitize_text_field( $input['category_archive_col_width'] );

        return $new_input;
    }

    /** 
     * Print the Section text
     */
    public function print_image_section_info(){
        print 'Enter thumbnail image sizes to show in each connected post single page:';
    }

    public function parent_img_width_callback(){
        printf(
            '<input type="text" id="parent_img_width" name="bridge_option_name[parent_img_width]" value="%s" />',
            isset( $this->options['parent_img_width'] ) ? esc_attr( $this->options['parent_img_width']) : '100'
        );
    }
    public function parent_img_height_callback(){
        printf(
            '<input type="text" id="parent_img_height" name="bridge_option_name[parent_img_height]" value="%s" />',
            isset( $this->options['parent_img_height'] ) ? esc_attr( $this->options['parent_img_height']) : '100'
        );
    }
    public function child_img_width_callback(){
        printf(
            '<input type="text" id="child_img_width" name="bridge_option_name[child_img_width]" value="%s" />',
            isset( $this->options['child_img_width'] ) ? esc_attr( $this->options['child_img_width']) : '100'
        );
    }
    public function child_img_height_callback(){
        printf(
            '<input type="text" id="child_img_height" name="bridge_option_name[child_img_height]" value="%s" />',
            isset( $this->options['child_img_height'] ) ? esc_attr( $this->options['child_img_height']) : '100'
        );
    }
    public function parent_archive_img_width_callback(){
        printf(
            '<input type="text" id="parent_archive_img_width" name="bridge_option_name[parent_archive_img_width]" value="%s" />',
            isset( $this->options['parent_archive_img_width'] ) ? esc_attr( $this->options['parent_archive_img_width']) : '250'
        );
    }
    public function parent_archive_img_height_callback(){
        printf(
            '<input type="text" id="parent_archive_img_height" name="bridge_option_name[parent_archive_img_height]" value="%s" />',
            isset( $this->options['parent_archive_img_height'] ) ? esc_attr( $this->options['parent_archive_img_height']) : '250'
        );
    }
    public function child_archive_img_width_callback(){
        printf(
            '<input type="text" id="child_archive_img_width" name="bridge_option_name[child_archive_img_width]" value="%s" />',
            isset( $this->options['child_archive_img_width'] ) ? esc_attr( $this->options['child_archive_img_width']) : '250'
        );
    }
    public function child_archive_img_height_callback(){
        printf(
            '<input type="text" id="child_archive_img_height" name="bridge_option_name[child_archive_img_height]" value="%s" />',
            isset( $this->options['child_archive_img_height'] ) ? esc_attr( $this->options['child_archive_img_height']) : '250'
        );
    }

    


    /* Archive Functions */

    public function print_archive_section_info(){
        print 'Select the column width of the archive pages:';
    }

    public function parent_archive_col_width_callback(){
        ?>
        <select id="parent_archive_col_width" name="bridge_option_name[parent_archive_col_width]">
            <option value="1" <?php echo ($this->options['parent_archive_col_width'] == '1') ? 'selected':''; ?>>Full Width</option>
            <option value="2" <?php echo ($this->options['parent_archive_col_width'] == '2') ? 'selected':''; ?>>Half Width</option>
            <option value="3" <?php echo ($this->options['parent_archive_col_width'] == '3') ? 'selected':''; ?>>One-Third Column Width</option>
            <option value="4" <?php echo ($this->options['parent_archive_col_width'] == '4') ? 'selected':''; ?>>One-Fourt Column Width</option>
        </select>
        <?php
    }

    public function child_archive_col_width_callback(){
        ?>
        <select id="child_archive_col_width" name="bridge_option_name[child_archive_col_width]">
            <option value="1" <?php echo ($this->options['child_archive_col_width'] == '1') ? 'selected':''; ?>>Full Width</option>
            <option value="2" <?php echo ($this->options['child_archive_col_width'] == '2') ? 'selected':''; ?>>Half Width</option>
            <option value="3" <?php echo ($this->options['child_archive_col_width'] == '3') ? 'selected':''; ?>>One-Third Column Width</option>
            <option value="4" <?php echo ($this->options['child_archive_col_width'] == '4') ? 'selected':''; ?>>One-Fourt Column Width</option>
        </select>
        <?php
    }

    public function category_archive_col_width_callback(){
        ?>
        <select id="category_archive_col_width" name="bridge_option_name[category_archive_col_width]">
            <option value="1" <?php echo ($this->options['category_archive_col_width'] == '1') ? 'selected':''; ?>>Full Width</option>
            <option value="2" <?php echo ($this->options['category_archive_col_width'] == '2') ? 'selected':''; ?>>Half Width</option>
            <option value="3" <?php echo ($this->options['category_archive_col_width'] == '3') ? 'selected':''; ?>>One-Third Column Width</option>
            <option value="4" <?php echo ($this->options['category_archive_col_width'] == '4') ? 'selected':''; ?>>One-Fourt Column Width</option>
        </select>
        <?php
    }
}

if( is_admin() )
    $bridge_ettings = new BridgeSettings();