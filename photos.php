<?php 
include_once 'includes/gallery_class.php';
include_once 'includes/event_class.php';
include_once 'includes/maininc.php';

session_start();

ClearEditSession();

$pageTitle = "Info";

include_once 'includes/header.php';

echo <<<JAVASCRIPT
<script language=JavaScript>
function reload(form)
{
var val=form.ddlGalleries.options[form.ddlGalleries.options.selectedIndex].value;
self.location='process_gallery.php?gal=' + val ;
}
</script>
JAVASCRIPT;

include_once 'includes/precontent.php';
include_once 'includes/leftnav.php';
include_once("koschtit/ki_include.php"); 

$gallerylist = Gallery::GetGalleryDirList();

// If GET[id] is set, page loading from Info page. Need to get index of id in gallerylist
if (isset($_GET['id']) && (strlen($_GET['id'])>0)) {
	$selected_gallery = Gallery::GetGalleryIDIndex($_GET['id']);
	$_SESSION['selected_gallery'] = $selected_gallery;
}
else if (isset($_SESSION['selected_gallery']))
	$selected_gallery = $_SESSION['selected_gallery'];
else {
	$selected_gallery = 0;
	$_SESSION['selected_gallery'] = 0;
}

if(count($gallerylist)>0)
	$gallery_name = $gallerylist[$selected_gallery];
else
	$gallery_name = "";
?>

<div id="content">
<form method="post" name="form1" >
<table>
<tr>	
	<td>
    	<table align="center" border="0">
    		<tr>
    			<td>
			    	<span  class="boldnormal">Select the photo gallery:&nbsp;</span>
				    <?php
						echo "<select name='ddlGalleries' onChange='reload(this.form)'>";
						for ($i=0; $i<count($gallerylist); $i++) {
							if($i == $selected_gallery)
								echo "<option selected value='$i'>$gallerylist[$i]</option><br/>";
							else
								echo "<option value='$i'>$gallerylist[$i]</option><br/>";
						}
						echo "</select>";	    
				    ?>
				</td>
				<td>&nbsp;&nbsp;</td>
				<td class="smalltext" align="center">
					Use the controls in the lower-right to use the gallery<br />
					Or click on a thumbnail to view the photo
				</td>
			</tr>					
    	</table>
    </td>
    <td width="8" bgcolor="#FFFFFF"></td>
  </tr>
  <tr>
    <td align="center"><div class="koschtitgallery" title="<?php echo $gallery_name; ?>"></div></td>
  </tr>
 </table>
 </form>
<?php
echo "</ul></div>";
?>
</div>
<?php 
include_once 'includes/postcontent.php';
?>