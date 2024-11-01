<?php
    global $wpdb;    
    $tableName = $wpdb->prefix . 'global_variable';
    $limit = 20;
    $custom_query = $wpdb->get_results($wpdb->prepare("SELECT * FROM $tableName where %d order by id desc",1));
    $num_rows = count($custom_query); 
?>
<?php
if(isset($_REQUEST['delete_var']) && $_REQUEST['delete_var']!='')
{
    global $wpdb;
    $tableName = $wpdb->prefix . 'global_variable';
    $id = $_REQUEST['p']; 
    $delete = $wpdb->query($wpdb->prepare("DELETE  FROM $tableName where id = %s",$id));
    $url = admin_url();
    $url = $url."/admin.php?page=wp-global-variable/display.php&del=1";
        ?>
        <script type="text/javascript"> 
        window.location.href='<?php echo $url; ?>';
        </script> 
<?php 
} ?> 
 <?php if ($num_rows > 0){ ?>
    <div class="wrap">
    <?php 
         $url = site_url(); 
         $add = $url.'/wp-admin/admin.php?page=wp-global-variable/configuration.php';  
     ?>
<form action="" method="post" onsubmit="return confirmDelete()">

<h2><img src="<?php echo plugins_url('images/cam_1.png', __FILE__)?>" alt="">&nbsp;&nbsp;&nbsp;All Variables<a class="add-new-h2" href="<?php echo $add;?>">Add New</a></h2>

<?php 
    if(isset($_REQUEST['del']) && $_REQUEST['del'] == 1){?>
    <div class="updated below-h2" id="message"><p>Variable Deleted Successfully</p></div>
    <?php }
    if(isset($_REQUEST['edit']) && $_REQUEST['edit'] == 1){?> 
    <div class="updated below-h2" id="message"><p>Variable Updated Successfully</p></div>
    <?php }
    if(isset($_REQUEST['add']) && $_REQUEST['add'] ==1){?>
    <div class="updated below-h2" id="message"><p>Variable Added Successfully</p></div>
    <?php }
    ?>

<table class="wp-list-table widefat fixed posts" cellspacing="0">
<thead>
    <tr>
        <th width="18%" id="title" scope="col" align="center"><span>Name</span></th>
        <th width="17%" id="author" scope="col" align="center"><span>Variable Name</span></th>
        <th width="45%" id="categories" scope="col" align="center"><span>Variable Value</span></th>
        <th width="7%" id="tags" scope="col" align="center"><span>Status</span></th>
        <!--<th colspan="2" class="manage-column column-date" id="date" scope="col" align="center"><span>Option</span></th>-->
        <th width="13%" id="tags" scope="col" align="center"><span>Created Date(Y:M:D)</span></th>
   </tr>
</thead>
<?php 
      $row = $wpdb->get_results($wpdb->prepare("SELECT * FROM ".$wpdb->prefix ."global_variable where %d order by id desc",1));
      $c = 0;
       for($i=0;$i<count($row);$i++){
           $id = $row[$i]->id;
           $name = $row[$i]->name;
           $tag = $row[$i]->tag;
           $value = $row[$i]->value;
           $status =$row[$i]->status;
           $created = $row[$i]->date;
?>
<tbody id="the-list">
           <?php 
           if($c % 2 == 0)
           {
               $alternate = '';
           }else
           {
                $alternate = 'alternate';    
           }
           ?>
   <tr class="<?php echo $alternate;?>">
        <?php
             $url = site_url(); 
             $edit = $url.'/wp-admin/admin.php?page=wp-global-variable/configuration.php';
            
             $del = $url.'/wp-admin/admin.php?page=wp-global-variable/display.php&delete_var=1';
        ?>
       <td><?php echo $name;?>
       <div class="row-actions"><span class="edit"><a title="Edit this item" href="<?php echo $edit;?>&update=update1&id=<?php echo $id;?>">Edit</a> |
        </span><span class="trash"><a href="<?php echo $del;?>?delete=delete&p=<?php echo $id;?>" title="Delete this item" class="submitdelete" onclick="return confirm('Are you sure you want to delete this record?')">Delete</a></span></div>
       </td>
       <td><a href="<?php echo $edit;?>&update=update1&id=<?php echo $id;?>" style="text-decoration: none;"><?php echo $tag;?></a></td>
       <td><?php echo substr(htmlspecialchars($value), 0, 200);?></td>
       <td><?php echo $status;?></td>
      <td align="center"><?php echo $created;?></td>
 </tr> 

</tbody>
   <?php 
       $c ++;
       } ?>
   </div> 
   <?php }else { ?>
   &nbsp;
   <div class="updated below-h2" id="message"><p>No Records.</p></div>
<?php } ?>
   </table>
</form>