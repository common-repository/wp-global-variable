<?php
/*
Plugin Name: Wordpress Custom Global Variables
Description: Using WordPress Custom Global Variable you can create your own short codes and gets its values.
Plugin URI:  https://wordpress.org/plugins/wp-global-variable/
Version: 3.0.0
Author: biztechc
Text Domain: global-variable
Author URI: https://www.appjetty.com/
*/                 
?>
<?php
/* Plugin Activation hook */
register_activation_hook(__FILE__, "table_create");
function table_create(){
    
    global $wpdb;
                 
    if (is_multisite()) {
        // check if it is a network activation - if so, run the activation function for each blog id
        if (isset($_GET['networkwide']) && ($_GET['networkwide'] == 1)){
                    
            $blogids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
            foreach ($blogids as $blog_id) {
                switch_to_blog($blog_id);
                global_var_table_create($blog_id);
                restore_current_blog();
            }
           
        }else
        {
          global_var_table_create($wpdb->blogid);  
        }  
    }else
    {
          global_var_table_create($wpdb->blogid);
    }     
}
function global_var_table_create($blog_id) {
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();
    $table_name = $wpdb->prefix . 'global_variable';
     $sql = "CREATE TABLE IF NOT EXISTS $table_name(
                    id INT( 11 ) NOT NULL AUTO_INCREMENT ,
                    name VARCHAR( 500 ) NOT NULL ,
                    tag VARCHAR( 500 ) NOT NULL ,
                    value TEXT NOT NULL ,
                    description VARCHAR( 1000 ) NOT NULL ,
                    status ENUM( 'Active', 'Inactive' ) NOT NULL,
                    date DATE NOT NULL ,
                    PRIMARY KEY ( `id` )) $charset_collate;";

     $wpdb->query($sql);
}

/* Plugin Deactivation Hook */ 
register_deactivation_hook( __FILE__, 'global_deactivate' );

/* Plugin Uninstall Hook */ 
register_uninstall_hook(__FILE__, 'uninstall_global_variable' );
function uninstall_global_variable()
{        
    global $wpdb;
    if (is_multisite()){
      // get ids of all sites
    $blogids = $wpdb->get_col($wpdb->prepare("SELECT blog_id FROM $wpdb->blogs",1));
    foreach ($blogids as $blog_id) {
    switch_to_blog($blog_id); 
    global__variable_uninstall($blog_id);
    restore_current_blog();
    }
  
  }else
    {
    // activated on a single site
    global__variable_uninstall($wpdb->blogid);
    } 
}
function global__variable_uninstall($blog_id)
{
    global $wpdb;
   $dropTable=$wpdb->prepare("DROP TABLE IF EXISTS ".$wpdb->prefix."global_variable",1);
   $wpdb->query($dropTable);
}

/* Create init call for Plugin */ 
add_action("plugins_loaded", "define_variable");
function define_variable(){
        global $wpdb;    
        $tableName = $wpdb->prefix . 'global_variable';
        $sql = $wpdb->prepare("SELECT * FROM $tableName WHERE status = '%s' ",'Active');
        
        $numberofconfig_data = $wpdb->get_results($sql);
        
        if($numberofconfig_data > 0){
            foreach($numberofconfig_data as $config_data){ 
                //$$config_data->tag = $config_data->value;
                if(!defined($config_data->tag)){
                    define($config_data->tag,  stripslashes($config_data->value));
                }
                //return stripslashes($config_data->value);
            }
        }
        
        
        /*$config_data = mysql_query($sql);
        @$numberofconfig_data = mysql_num_rows($config_data);
        
        if($numberofconfig_data > 0){
            while($config_result = mysql_fetch_array($config_data)){
                $$config_result['tag'] = $config_result['value'];
                    if(!defined($config_result['tag'])){
                        define($config_result['tag'],$config_result['value']);
                    }
                }
        }*/
        
        
}

/* Add admin menu for WP Global Variable */
add_action('admin_menu', 'global_variable');  
/* Bellow function create Menu as well as its submentues.*/
function global_variable(){
       
    if (function_exists('add_menu_page')){                
        add_menu_page(__('Wordpress Custom Global Variables', __FILE__), __('Custom Variables', __FILE__), 'manage_options',plugin_dir_path( __FILE__ ).'/display.php', '', plugins_url('/images/cam.png',__FILE__));
    }
    if (function_exists('add_submenu_page')) {
        add_submenu_page(plugin_dir_path( __FILE__ ).'/display.php', __('All Variables', __FILE__), __('All Variables', __FILE__), 'manage_options', plugin_dir_path( __FILE__ ).'/display.php');
        add_submenu_page(plugin_dir_path( __FILE__ ).'/display.php', __('Add New', __FILE__), __('Add New', __FILE__), 'manage_options', plugin_dir_path( __FILE__ ).'/configuration.php');
    } 
}

/* Bellow function returing value of defined shortcode which is in wp_editor e.g: [global_variable variable_name='TEST1'] */ 
add_shortcode('global_variable','global_variable_func');
function global_variable_func($attr)
{
    global $wpdb;    
        $tableName = $wpdb->prefix . 'global_variable';
        $val = $attr['variable_name'];
        $sql = $wpdb->prepare("SELECT * FROM $tableName WHERE status = 'Active' and tag=%s",$val);
        $numberofconfig_data = $wpdb->get_results($sql);
        
        if($numberofconfig_data > 0){
            foreach($numberofconfig_data as $config_data){ 
                return stripslashes($config_data->value);
            }
        }
        /*$config_data = mysql_query($sql);
        //@$numberofconfig_data = mysql_num_rows($config_data);
        if($numberofconfig_data > 0){
            while($config_result = mysql_fetch_array($config_data)){
            return stripslashes($config_result['value']);
            }
        }*/
    
}?>