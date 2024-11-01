<?php
/**
 * General settings administration panel.
 *
 * @package WordPress
 * @subpackage Administration
 */
/** WordPress Administration Bootstrap 

 */
/*
  This program is free software; you can redistribute it and/or
  modify it under the terms of the GNU General Public License
  as published by the Free Software Foundation; either version 2
  of the License, or (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */
if (!current_user_can('manage_options'))
    wp_die(__('You do not have sufficient permissions to manage options for this site.'));

$title = __('Add New');
$parent_file = 'options-general.php';
/* translators: date and time format for exact current time, mainly about timezones, see http://php.net/date */
$timezone_format = _x('Y-m-d G:i:s', 'timezone date format');
/**
 * Display JavaScript on the page.
 *
 * @package WordPress
 * @subpackage General_Settings_Panel
 */
global $wpdb;

$tableName = $wpdb->prefix . 'global_variable';
if (isset($_POST['Save']) && $_POST['Save'] != '') {
    global $wpdb;
    $name = $_REQUEST['name'];
    $tag = strtoupper($_REQUEST['tag']);
    $value = $_REQUEST['content'];
    $desc = $_REQUEST['description'];
    $status = $_REQUEST['status'];
    $status = implode(" ", $status);
    $tableName = $wpdb->prefix . 'global_variable';

    $sql = $wpdb->get_results($wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "global_variable where tag=%s", $tag));

    if (isset($sql) && empty($sql)) {
        $name = $_POST['name'];
        $tag = strtoupper($_POST['tag']);
        $value = $_POST['content'];
        $desc = $_POST['description'];
        $status = $_POST['status'];
        $st = implode(" ", $status);
        /* $insert = $wpdb->query("INSERT INTO $tableName(id,name,tag,value,description,status,date)VALUES(null,'".sanitize_text_field($name)."','".sanitize_text_field($tag)."','$value','".sanitize_text_field($desc)."',
          '".sanitize_text_field($st)."',CURDATE())"); */

        $insert = $wpdb->query($wpdb->prepare("INSERT INTO $tableName(id,name,tag,value,description,status,date)VALUES(null,%s,%s,%s,%s,%s,%s)", sanitize_text_field($name), sanitize_text_field($tag), $value, sanitize_text_field($desc), sanitize_text_field($st), date("Y-m-d")));

        $url = admin_url();
        $url = $url . "/admin.php?page=wp-global-variable/display.php&add=1";
        ?>
        <script type="text/javascript">
            window.location.href = '<?php echo $url; ?>';
        </script>
        <?php
    } else {

        $url = admin_url();
        $url = $url . "/admin.php?page=wp-global-variable/configuration.php&already=1";
        ?>
        <script type="text/javascript">
            window.location.href = '<?php echo $url; ?>';
        </script>
        <?php
    }
}
?>
<script type="text/javascript" src="<?php echo plugins_url('js/general.js', dirname(__FILE__)); ?>"></script>  
<div class='error' style="display: none;"><p>Please fill all compulsory marked fields.</p></div>
<?php if (isset($_REQUEST['already']) && $_REQUEST['already'] == 1) { ?>
    <div class='error'><p>Applied variable slug is already used.Please add another variable name.</p></div>
<?php } ?>
<div class="wrap"> 
    <?php //screen_icon();  
    ?>
    <h2><img src="<?php echo plugins_url('images/cam_1.png', __FILE__) ?>" alt="">&nbsp;&nbsp;&nbsp;<?php echo esc_html($title); ?></h2>
    <?php
    if (isset($_REQUEST['update']) && isset($_REQUEST['id'])) {
        $id = $_REQUEST['id'];                   //Debugbreak();
        $sql = $wpdb->get_results($wpdb->prepare("SELECT * FROM $tableName where id =%s", $id));
        //$sql = "SELECT * FROM $tableName where id = '$id'";
        //$result = mysql_query($sql);
        //while($row= mysql_fetch_array($result))
        {
            /* $id = $row['id'];
              $name = $row['name'];
              $tag = $row['tag'];
              $value = $row['value'];
              $desc = $row['description'];
              $status = $row['status'] ; */
        }
        $id = $sql[0]->id;
        $name = $sql[0]->name;
        $tag = $sql[0]->tag;
        $value = $sql[0]->value;
        $desc = $sql[0]->description;
        $status = $sql[0]->status;
    }
    if (isset($_REQUEST['update1'])) {
        $id = $_REQUEST['id'];
        $name = $_REQUEST['name'];
        $tag = strtoupper($_REQUEST['tag']);
        $value = $_REQUEST['content'];
        $desc = $_REQUEST['description'];
        $status = $_REQUEST['status'][0];
        if (isset($_REQUEST['id'])) {
            if ($status == 'Active' || $status == 'Inactive') {
                
            }
        }
        /* $update = $wpdb->query("UPDATE $tableName SET name='".sanitize_text_field($name)."',
          tag='".sanitize_text_field($tag)."',
          value='$value',
          description='".sanitize_text_field($desc)."',
          status='".sanitize_text_field($status)."' where id ='$id'"); */

        $update = $wpdb->query($wpdb->prepare("UPDATE $tableName SET name=%s,tag=%s,value=%s,description=%s,status=%s where id ='$id'", sanitize_text_field($name), sanitize_text_field($tag), $value, sanitize_text_field($desc), sanitize_text_field($status)));

        $url = admin_url();
        $url = $url . "/admin.php?page=wp-global-variable/display.php&edit=1";
        ?>
        <script type="text/javascript">
        window.location.href = '<?php echo $url; ?>';
        </script>
    <?php } ?> 
    <form  method="post" name="form" onsubmit="return validatoin();">
        <table >
            <tr><?php
                if (isset($name) && !empty($name)) {
                    $name = $name;
                } else {
                    $name = "";
                }



                if (isset($desc) && !empty($desc)) {
                    $desc = $desc;
                } else {
                    $desc = "";
                }
                ?>
                <td><?php _e('Name <em>*</em>', 'global-variable'); ?></td><td><input type="text" name="name" id="name"  title="Please fill this" size="50" value="<?php {
                        echo $name;
                    }
                    ?>" /></td>
            </tr> 
            <tr>
                <td><?php _e('Slug <em>*</em>', 'global-variable'); ?></td><td><input type="text"  name="tag" id="tag" title="Please fill this" size="50" value="<?php {
                        echo $tag;
                    }
                    ?>" /></td>
            </tr> 
            <tr>
                <td><?php _e('Variable Value', 'global-variable'); ?></td><td><div id="" class=""><?php {
                            if (isset($value) && !empty($value)) {
                                wp_editor(stripslashes($value), 'content');
                            } else {
                                wp_editor("", 'content');
                            }
                        }
                        ?></div></td>

            </tr> 
            <tr>
                <td><?php _e('Description <em>*</em>', 'global-variable'); ?></td><td><textarea   name="description" id="description" cols="50" rows="5"><?php {
                            echo $desc;
                        }
                        ?></textarea></td>
            </tr>
            <tr>
                <td><?php _e('Status <em>*</em>', 'global-variable'); ?></td><td>
                    <select  name="status[]" id="status" >
                        <?php
                        $active_selected = '';
                        $inactive_selected = '';
                        if (isset($status) && !empty($status)) {
                            if ($status == 'Active') {
                                $active_selected = 'selected';
                            } elseif ($status == 'Inactive') {
                                $inactive_selected = 'selected';
                            }
                        } else {
                            $active_selected = "";
                            $inactive_selected = "";
                        }
                        ?>
                        <option value="">-- select status --</option>
                        <option  value="Active" <?php echo $active_selected; ?> ><?php _e('Active', 'global-variable'); ?></option>
                        <option value="Inactive" <?php echo $inactive_selected; ?> ><?php _e('Inactive', 'global-variable'); ?></option>
                    </select></td>
            </tr>

            <br/>
            <tr>
                <td>
                    <input type="submit" id="save"  name=" <?php
                           if (isset($_REQUEST['id'])) {
                               echo "update1";
                           } else {
                               echo "Save";
                           }
                           ?>" value=" <?php
                           if (isset($_REQUEST['id'])) {
                               echo "Update";
                           } else {
                               echo "Save";
                           }
                           ?>"></td>
            </tr>
        </table>
    </form> 
    <?php settings_fields('general'); ?>
</div>
<br/>
<div>
</div>
<script type="text/javascript">
    function validatoin()
    {
        flag = 0;
        var title = jQuery('#name').val();
        var variable_name = jQuery('#tag').val();
        var variable_desc = jQuery('#description').val();
        var variable_status = jQuery('#status').val();
        if (title == '')
        {
            document.getElementById('name').style.borderColor = "#FF0000";
            document.getElementById('name').focus();
            flag = 1;
        } else
        {
            document.getElementById('name').style.borderColor = "#999999";
        }

        if (variable_name == '')
        {
            document.getElementById('tag').style.borderColor = "#FF0000";
            document.getElementById('tag').focus();
            flag = 1;
        } else
        {
            document.getElementById('tag').style.borderColor = "#999999";
        }

        if (variable_desc == '')
        {
            document.getElementById('description').style.borderColor = "#FF0000";
            document.getElementById('description').focus();
            flag = 1;
        } else
        {
            document.getElementById('description').style.borderColor = "#999999";
        }

        if (variable_status == '')
        {
            document.getElementById('status').style.borderColor = "#FF0000";
            document.getElementById('status').focus();
            flag = 1;
        } else
        {
            document.getElementById('status').style.borderColor = "#999999";
        }

        if (flag == 1)
        {
            jQuery('.error').css('display', 'block');
            return false;
        } else
        {
            jQuery('.error').css('display', 'none');
            return true;
        }
    }
    jQuery(document).ready(function () {
        jQuery("#tag").keyup(removeextra).blur(removeextra);
    });
    function removeextra() {
        var initVal = jQuery(this).val();
        outputVal = initVal.replace(/[^0-9a-zA-Z]/g, "");
        if (initVal != outputVal) {
            jQuery(this).val(outputVal);
        }
    }
    ;
</script>