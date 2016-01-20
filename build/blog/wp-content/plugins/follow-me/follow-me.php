<?php
/*
Plugin Name: Follow Me
Plugin URI: http://www.ignitesocialmedia.com/tools/follow-me/
Version: 3.1.1
Author: Brian Chappell @ Ignite Social Media
Description: The Follow Me plugin is designed to allow users the ability to add links to their social media profiles in their blog. To get started activate and visit the <a href="options-general.php?page=fmoptions">Settings Page</a>.
*/


/* UPDATE/INSTALL FUNCTION */


$FollowMe_version = "3.1";
global $FollowMe_version;
function FollowMe_install () {
    $pluginpath = WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__));
    global $wpdb;
    global $FollowMe_version;
    $FollowMe_version = "3.1";
    $installed_ver = get_option("FollowMe_version");

    $table_name = $wpdb->prefix . "FollowMe_Links";
    
    /* UPDATE */
    if($installed_ver !== $FollowMe_version) {
    update_option("FollowMe_version", $FollowMe_version);
	}
    
    
    if($wpdb->get_var("show tables like '$table_name'") != $table_name) {        
        $sql = "CREATE TABLE " . $table_name . " (
        `id` INT NOT NULL AUTO_INCREMENT ,
        `name` VARCHAR( 100 ) NOT NULL ,
        `icon` VARCHAR( 500 ) NOT NULL ,
        `address` VARCHAR( 500 ) NOT NULL ,
        PRIMARY KEY ( `id` )
        );";
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
        $installarr = array("FollowMe_GooglePlus","FollowMe_Yelp","FollowMe_Technorati","FollowMe_SlideShare","FollowMe_Propeller","FollowMe_Picasa","FollowMe_Newsvine","FollowMe_Last","FollowMe_kirtsy","FollowMe_FriendFeed","FollowMe_brightkite","FollowMe_Mahalo","FollowMe_plurk","FollowMe_Ping","FollowMe_MyBlogLog","FollowMe_Twitter","FollowMe_LinkedIn","FollowMe_StumbleUpon","FollowMe_Myspace","FollowMe_Sphinn","FollowMe_Facebook","FollowMe_Digg","FollowMe_Delicious","FollowMe_Youtube","FollowMe_Reddit","FollowMe_Flickr","FollowMe_Mixx","FollowMe_Xing","FollowMe_Identica","FollowMe_Plaxo","FollowMe_Orkut");
        foreach($installarr as $row) {
	                $insert = "INSERT INTO " . $table_name . " (name, icon, address) VALUES ('".str_replace('FollowMe_', '', $row)."','" .$pluginpath ."images/".strtolower(str_replace('FollowMe_','',$row)).".png','" . get_option($row) . "')";
                    $results = $wpdb->query( $insert );
            		if($results){
	            	$wpdb->query("DELETE FROM " . $table_name . " WHERE address = ''");
					delete_option($row);
	            	}
       }                  	
    $i=1;
	while($i<=15)
  	{
	  	$text = "FollowMe_LinkText".$i;	
	  	$url = "FollowMe_LinkURL".$i;	  	
	  	
        	$insertcustom = "INSERT INTO " . $table_name . " (name, icon, address) VALUES ('".get_option($text)."','" .$pluginpath ."images/avatar.jpg','" . get_option($url) . "')";
        	$action = $wpdb->query( $insertcustom );
        	if($action){
	        	$wpdb->query("DELETE FROM " . $table_name . " WHERE address = ''");
	        	delete_option($text);
	        	delete_option($url);
	        }
  		$i++;
		
  	}
  	 add_option("FollowMe_mobile",'off');	
  	 add_option("FollowMe_mewe",'me');	
     add_option("FollowMe_version", $FollowMe_version);
     add_option("FollowMe_Buttons",'button.gif');
     add_option("FollowMe_Layout",'true');
     add_option("FollowMe_NewWin",'check');
     add_option("FollowMe_Columns",'2');
     add_option("FollowMe_Design",'old');
     add_option("FollowMe_New_Side",'left');
     add_option("FollowMe_New_Size",'small');
     }
}

register_activation_hook(__FILE__,'FollowMe_install');



function FollowMe_Scripts(){
if(get_option('FollowMe_Design') == 'old'){}else{
	
$pluginpath = WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__)); 

$useragent=$_SERVER['HTTP_USER_AGENT'];
if(get_option('FollowMe_mobile') == 'on'){
	if(preg_match('/android|avantgo|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|e\-|e\/|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(di|rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|xda(\-|2|g)|yas\-|your|zeto|zte\-/i',substr($useragent,0,4)))
	{$mobile = "yes";}
	else
	{$mobile = "no";}
}
else{$mobile = "no";}
if($mobile == "yes"){}else{
?>
<script type="text/javascript" src="<?php echo $pluginpath; ?>js/bubble.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $pluginpath; ?>css/style.css" />
<?php } } 
}

function FollowMe_Bubble(){
$useragent=$_SERVER['HTTP_USER_AGENT'];
if(get_option('FollowMe_mobile') == 'on'){
	if(preg_match('/android|avantgo|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|e\-|e\/|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(di|rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|xda(\-|2|g)|yas\-|your|zeto|zte\-/i',substr($useragent,0,4)))
	{$mobile = "yes";}
	else
	{$mobile = "no";}
}
else{$mobile = "no";}
if($mobile == "yes"){}else{
	
global $wpdb;
$pluginpath = WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__)); 
if(get_option('FollowMe_Design') == 'old'){}else{
	if(get_option('FollowMe_mewe') == 'we'){

	if(get_option('FollowMe_New_Side') == 'left'){
		if(get_option('FollowMe_New_Size') == 'small'){ ?>
			<a id="FollowMeTabLeftSm" onClick="showFollowMe()" href="#" style="border-bottom:none;"><img src="<?php echo $pluginpath; ?>images/us/leftsm.gif" border="0" /></a>
		<?php }else{ ?> 
			<a id="FollowMeTabLeftLg" onClick="showFollowMe()" href="#" style="border-bottom:none;"><img src="<?php echo $pluginpath; ?>images/us/leftlg.gif" border="0" /></a>	
<?php }} else{ 
			if(get_option('FollowMe_New_Size') == 'small'){ ?>
			<a id="FollowMeTabRightSm" onClick="showFollowMe()" href="#" style="border-bottom:none;"><img src="<?php echo $pluginpath; ?>images/us/rightsm.gif" border="0" /></a>
		<?php }else{ ?>
			<a id="FollowMeTabRightLg" onClick="showFollowMe()" href="#" style="border-bottom:none;"><img src="<?php echo $pluginpath; ?>images/us/rightlg.gif" border="0" /></a>	
 
<?php } } 
		
				
}	
else{
	if(get_option('FollowMe_New_Side') == 'left'){
		if(get_option('FollowMe_New_Size') == 'small'){ ?>
			<a id="FollowMeTabLeftSm" onClick="showFollowMe()" href="#" style="border-bottom:none;"><img src="<?php echo $pluginpath; ?>images/leftsm.gif" border="0" /></a>
		<?php }else{ ?> 
			<a id="FollowMeTabLeftLg" onClick="showFollowMe()" href="#" style="border-bottom:none;"><img src="<?php echo $pluginpath; ?>images/leftlg.gif" border="0" /></a>	
<?php }} else{ 
			if(get_option('FollowMe_New_Size') == 'small'){ ?>
			<a id="FollowMeTabRightSm" onClick="showFollowMe()" href="#" style="border-bottom:none;"><img src="<?php echo $pluginpath; ?>images/rightsm.gif" border="0" /></a>
		<?php }else{ ?>
			<a id="FollowMeTabRightLg" onClick="showFollowMe()" href="#" style="border-bottom:none;"><img src="<?php echo $pluginpath; ?>images/rightlg.gif" border="0" /></a>	
 
<?php } } } ?>

<div id="FollowMeBubbleBG" style="visibility:hidden;"></div>
<div id="FollowMeBubble" style="visibility:hidden;border-bottom:none;">


<?php if(get_option('FollowMe_mewe') == 'we'){ ?>
<div class="top" style="background-image:url('<?php echo $pluginpath; ?>images/us/followme_top.gif');width:329px;height:60px;float:left;display:block;border-bottom:none;">
<?php }else{ ?>
<div class="top" style="background-image:url('<?php echo $pluginpath; ?>images/followme_top.gif');width:329px;height:60px;float:left;display:block;border-bottom:none;">
<?php } ?>
<a id="close" onClick="hideFollowMe()" href="#" style="border-bottom:none"><img src="<?php echo $pluginpath; ?>images/close.png" border="0" /> </a>
<!-- <a id="grab" target="_blank" title="grab this" href="http://www.ignitesocialmedia.com/tools/follow-me/"><span>grab this</span></a>--></div>
<div class="mid" style="background-image:url('<?php echo $pluginpath; ?>images/followme_mid.gif');width:329px;background-repeat:repeat-y;float:left;">
<div id="stretch">
<?php
$table_name = $wpdb->prefix . "FollowMe_Links";
$selectprofiles = "SELECT * FROM ".$table_name;
$action = $wpdb->get_results($selectprofiles);

foreach ($action as $row) {
?>
<span><a title="<?php echo $row->name; ?>"<?php if(get_option('FollowMe_NewWin') == 'check') { ?> target="_blank" <?php } ?>href="<?php echo $row->address; ?>" rel="me"><img width="32px" src="<?php echo $row->icon; ?>" border="0" alt="<?php echo $row->name; ?>" /> <?php echo $row->name; ?></a></span>
<?php } ?>
</div></div>
<div class="bottom" style="background-image:url('<?php echo $pluginpath; ?>images/followme_bottom.gif');height:34px;width:329px;clear:both;float:left;">
<a id="grab" target="_blank" title="grab this" style="border-bottom:none;" href="http://www.ignitesocialmedia.com/tools/follow-me/"><span>grab this</span></a>
</div>
<div class="grab" style="width:329px;clear:both;float:left;"><a style="border-bottom:none;" href="http://www.ignitesocialmedia.com/tools/follow-me/"><img src="<?php echo $pluginpath; ?>images/followme_grab.gif" /></a></div>
</div>






<?php } } }

function FollowMeSocialMedia() {

$useragent=$_SERVER['HTTP_USER_AGENT'];
if(get_option('FollowMe_mobile') == 'on'){
	if(preg_match('/android|avantgo|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|e\-|e\/|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(di|rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|xda(\-|2|g)|yas\-|your|zeto|zte\-/i',substr($useragent,0,4)))
	{$mobile = "yes";}
	else
	{$mobile = "no";}
}
else{$mobile = "no";}
if($mobile == "yes"){}else{
	
if(get_option('FollowMe_Design') == 'new'){}else{	
	
$pluginpath = WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__)); 
if(get_option('FollowMe_Buttons') !== 'none')
{ ?>
<script type="text/javascript" src="
<?php echo $pluginpath; ?>js/followme.js"></script>


<li>

<a href="#" onmouseover="TagToTip('FollowMe', LEFT, 
<?php if(get_option('FollowMe_Layout') == ''){echo "true";}else{echo get_option('FollowMe_Layout');}?>, BORDERWIDTH, 0, BGCOLOR, '#E0E0E0', FADEIN, 200, FADEOUT, 200, STICKY, true, PADDING, 0, CLICKCLOSE, true)" onmouseout="UnTip()">
<img id="FollowMeClick" style="border: none;" src="<?php echo $pluginpath; ?>images/<?php echo get_option('FollowMe_Buttons'); ?>" border="0" alt="" /></a> 

<?php }
else
{ }
?>
<li>
<div style="border: thin solid #E0E0E0; width:260px;
<?php if(get_option('FollowMe_Buttons') !== 'none'){echo "display:none;";}else{echo "";} ?>" id="FollowMe">
<table style="border-style:hidden;border-collapse: collapse;border-spacing:0px;" width="260px"><tbody>
<tr  style="background-color: rgb(224, 224, 224);border-style:hidden;">
<td <?php if(get_option('FollowMe_Columns') != 1){ ?>colspan="2"<?php } ?> style="background-color: rgb(224, 224, 224);border-style:hidden;"  align="left">
<span style="display:inline;font: 10pt 'Lucida Grande', Verdana, Arial, Sans-Serif;color: #000000;text-decoration: none;display: inline; text-transform: capitalize; text-align:left;font-weight:900;">Follow Me:</span><br/>
</td>
</tr>
<tr <?php if(get_option('FollowMe_Columns') != 1){echo 'width="260px"';} ?><?php if(get_option('FollowMe_Columns') == 1){echo 'width="130px"';} ?> style="background-color: rgb(255, 255, 255);border-style:hidden;">
<td width="130px" valign="top" style="padding-left: 4px; padding-top: 4px;border-style:hidden;" >
<?php
global $wpdb;
$table_name = $wpdb->prefix . "FollowMe_Links";
$selectprofiles = "SELECT * FROM ".$table_name;
$action = $wpdb->get_results($selectprofiles);

foreach ($action as $row) { ?>

<span style="display:inline;padding-top: 2px;text-transform:capitalize;">
<img style="border: none; vertical-align:middle; padding-right: 2px;" width="32px" src="<?php echo $row->icon; ?>" alt="" /><a style="font:10pt'Lucida Grande',Verdana,Arial,Sans-Serif;color:#000000;text-decoration:none;display:inline;" rel="me" href="<?php echo $row->address; ?>" title="<?php echo $row->name; ?>"><?php echo $row->name; ?></a>
</span>
<br/>

<?php } ?>

</td>
</tr>
<tr  style="background-color: rgb(224, 224, 224);">
<td style="background-color: rgb(224, 224, 224);" <?php if(get_option('FollowMe_Columns') != 1){ ?>colspan="2"<?php } ?> align="right">
<a style="font: 10pt 'Lucida Grande', Verdana, Arial, Sans-Serif;color: #000000;text-decoration: none;display: inline; text-transform: capitalize; text-align:right;" title="Grab This" target="_blank" href="http://www.ignitesocialmedia.com/tools/follow-me/">Grab This</a>
</td>
</tr>
</tbody>
</table>
</div>
</li>
<?php 
}} }

/* OPTIONS PAGE */

function FollowMeIgniteSocialMedia_options_page()
{
global $wpdb;	
$table_name = $wpdb->prefix . "FollowMe_Links";
$pluginpath = WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__)); 
if(isset($_POST['profilelinks'])){
	$name = $_POST['name'];
	$url = $_POST['url'];
	$insertprofilelinks = "INSERT INTO " . $table_name . " (name, icon, address) VALUES ('" . $name . "','" . $pluginpath . "images/".strtolower($name).".png','" . $url . "')";
    $action = $wpdb->query($insertprofilelinks);
}
if(isset($_POST['customlinks'])){
	$name = $_POST['name'];
	$url = $_POST['url'];
	$imgurl = $_POST['imgurl'];
	if(strlen($imgurl)>7)
	{$customicon = $imgurl;}else{$customicon = $pluginpath . "images/avatar.jpg";}
	$insertcustom = "INSERT INTO " . $table_name . " (name, icon, address) VALUES ('" . $name . "','" . $customicon . "','" . $url . "')";
    $action = $wpdb->query($insertcustom);
}

if(isset($_POST['designoptions'])){
	update_option('FollowMe_mobile', $_POST['mobile']);	
	update_option('FollowMe_mewe', $_POST['mewe']);	
	update_option('FollowMe_Buttons', $_POST['buttons']);
	update_option('FollowMe_Layout', $_POST['layout']);
	update_option('FollowMe_NewWin', $_POST['newwin']);
	update_option('FollowMe_Columns', $_POST['columns']);
	update_option('FollowMe_Design', $_POST['design']);
	update_option('FollowMe_New_Side', $_POST['side']);
	update_option('FollowMe_New_Size', $_POST['size']);
}

if(isset($_GET['del'])){
	$wpdb->query("DELETE FROM " . $table_name . " WHERE id = '".$_GET['del']."'");
	}
	
	
echo "<script type='text/javascript' src='".$pluginpath."js/wz_tooltip.js'></script>";	
 ?>
<div class="wrap">
<h1>Follow Me Plugin Settings</h1>
<div id="poststuff" class="metabox-holder has-right-sidebar">
<?php include('admin-sidebar.php'); ?>
<div class="has-sidebar sm-padded" >
<div id="post-body-content" class="has-sidebar-content">
<div class="meta-box-sortabless">
<div id="sm_rebuild" class="postbox">
<h3 style="cursor:default;" ><span>Manage Your Profile Links:</span></h3>
<div class="inside">
<div style="margin:10px;">
<strong>Add Your Profiles:</strong>
<p>Fill in your details below. In each field add the complete URL to your different profiles.<br>
<span style="color:#FF0000">Please note that you must include HTTP:// in order for your link to work. (ie. http://www.example.com) </span></p>
<?php wp_nonce_field('update-options'); ?>
<table border="0" style="border-collapse: collapse" width="800px">
<form method="post" action="options-general.php?page=fmoptions&updated=true">
<input type="hidden" name="profilelinks" value="1" />
<tr><td width="33%" align="right">
<?php         
$optionarr = array("GooglePlus","Google","Tagged","FourSquare","Blipfm","Email","Skype","MSN","AIM","GoodReads","Hyvesnl","Yelp","Technorati","SlideShare","Propeller","Picasa","Newsvine","Last","Kirtsy","FriendFeed","Brightkite","Mahalo","Plurk","Ping","MyBlogLog","Twitter","LinkedIn","StumbleUpon","Myspace","Sphinn","Facebook","Digg","Delicious","Youtube","Reddit","Flickr","Mixx","Xing","Identica","Plaxo","Orkut","RSS","Buzz");
sort($optionarr);
?>
<select style="width:200px;" name="name">
<?php
foreach($optionarr as $row) {
?>
<option id="<?php echo $row; ?>" value="<?php echo $row; ?>">
<?php echo $row; ?>
</option>
<?php } ?>
</select>
</td>
<td width="33%"><input size="75" type="text" id="url" name="url" value="http://" /></td>
<td width="33%">
<p style="margin:0px;padding:0;" class="submit">
<input type="submit" class="button-primary" name="Submit" value="<?php _e('Add Profile Link') ?>" />
</p>
</td>
</tr>
</form>
<form method="post" action="options-general.php?page=fmoptions&updated=true">
<input type="hidden" name="customlinks" value="1" />
<tr><td colspan="3">
<br/><strong>OR:</strong>
<span class="custom" style="text-decoration:underline;cursor:pointer;">Add A Custom Link.</span>
<div style="margin:5px;background-color:#DAE2ff;" class="custom_body">
<div style="margin:5px;">
Custom Link Name:<br>
<input name="name" type="text" /><br/>
URL:<br/>
<input type="text" name="url" value="http://" />
<br/>
Custom Link Icon URL:<a onmouseover="Tip('Enter the URL of an icon image you would like to assiociate with this link. If left blank a default image will be provided. For best results image should be no larger than 50px x 50px.', WIDTH, 250, ABOVE, true, OFFSETX, 1, FADEIN, 400, FADEOUT, 300)" onmouseout="UnTip()"><img src="<?php echo $pluginpath; ?>images/help.png" /></a><br/>
<input type="text" name="imgurl" value="http://" /><br/>
<p style="margin:5px 0 0 0;padding:0;" class="submit">
<input type="submit" class="button-primary" name="Submit" value="<?php _e('Add Custom Link') ?>" />
</p>
</div>
</div>
<br/><br/>
</td>
</tr>
</form>
</table>
<strong>Your Profiles:</strong>
<table width="700px">

<?php
$selectprofiles = "SELECT * FROM ".$table_name;
$action = $wpdb->get_results($selectprofiles);

foreach ($action as $row) {
?>
<tr style="border-bottom:solid;border-width:1px;border-color:#aeaeae;">
<td style="border-bottom:solid;border-width:1px;border-color:#aeaeae;" width="20px" align="right">
<img width="32px" src="
<?php echo $row->icon; ?>" />
</td>
<td style="border-bottom:solid;border-width:1px;border-color:#aeaeae;" width="655px">
<?php echo $row->address; ?>
</td>
<td style="border-bottom:solid;border-width:1px;border-color:#aeaeae;" width="25px">
<a style="color:red;" href="options-general.php?page=fmoptions&&updated=true&del=
<?php echo $row->id; ?>" >delete</a>
</td>
</tr>
<?php } ?>

</table>
</div>




</div>
</div>
<div id="sm_rebuild" class="postbox">
	<h3 style="cursor:default;"><span>Design:</span></h3>
		<div class="inside">
		
		
		<div style="margin:10px;">
<p>
<form method="post" action="options-general.php?page=fmoptions&updated=true">
<input type="hidden" name="designoptions" value="1" />
<strong>New Windows?</strong>
<p><label><input type="checkbox" <?php if(get_option('FollowMe_NewWin') == 'check')
{
 echo "checked='checked'";
}
?> name="newwin" value="check"> Open links in new window or tab.</label></p>

<strong>New Design</strong>
<p style="margin:0px">With the release of 2.0 we are offering a new design option that utilizes a tab that is placed on either the right or left margins of the page. When the tab is clicked a helpful dialog box is display in the center of the screen displaying your profile links.</p>
<br/><br/>
<strong>Old Design</strong>
<p style="margin:0px">Prior to version 2.0 this plugin used a "floating" box to display links when hovered over this <a href="<?php echo $pluginpath; ?>/images/button.gif" />button</a>. Enabling this option will allow you to contiue using that feature versus the new feature explained above.</p>
<br/><br/>
<div style="margin:5px;border:solid;border-width:2px;border-color:#a9c8d6;">
<div style="margin:5px;">
<span style="font-weight:900;"><input name="design" value="new" <?php if(get_option('FollowMe_Design') == 'new')
{
 echo "checked='checked'";
}
?> type="radio">New Design</span>
<div>
<br/><br/>
Is there more than one of you?
<br/>
<label><input name="mewe" value="me" <?php
if(get_option('FollowMe_mewe') == 'me')
{
 echo "checked='checked'";
}
?> class="tog" type="radio"> "Follow Me"</label>
<br/>
<label><input name="mewe" value="we" <?php
if(get_option('FollowMe_mewe') == 'we')
{
 echo "checked='checked'";
}
?> class="tog" type="radio"> "Follow Us"</label>
<br/><br/>
On which margin should the tab appear?
<br/>
<label><input name="side" value="left" <?php
if(get_option('FollowMe_New_Side') == 'left')
{
 echo "checked='checked'";
}
?> class="tog" type="radio"> Left</label>
<br/>
<label><input name="side" value="right" <?php
if(get_option('FollowMe_New_Side') == 'right')
{
 echo "checked='checked'";
}
?> class="tog" type="radio"> Right</label>
<br/><br/>
What size tab would you like?
<br/>
<label><input name="size" value="small" <?php
if(get_option('FollowMe_New_Size') == 'small')
{
 echo "checked='checked'";
}
?> class="tog" type="radio"> Small</label>
<br/>
<label><input name="size" value="large" <?php
if(get_option('FollowMe_New_Size') == 'large')
{
 echo "checked='checked'";
}
?> class="tog" type="radio"> Large</label>
<br/><br/>
Deactivate on mobile browsers?
<br/>
<label><input name="mobile" value="on" <?php
if(get_option('FollowMe_mobile') == 'on')
{
 echo "checked='checked'";
}
?> class="tog" type="radio"> Yes</label>
<br/>
<label><input name="mobile" value="off" <?php
if(get_option('FollowMe_mobile') == 'off')
{
 echo "checked='checked'";
}
?> class="tog" type="radio"> No</label>
</div>
</div>
</div>

<div style="margin:5px;border:solid;border-width:2px;border-color:#a9c8d6;">
<div style="margin:5px;"> 
<span style="font-weight:900;" class="oldd"><input name="design" value="old" <?php if(get_option('FollowMe_Design') == 'old')
{
 echo "checked='checked'";
}
?> type="radio">Old Design</span>  
<div class="oldd_body">
<strong>Widgets!</strong>
<p> To enable the Follow Me Plugin in your blogs sidebar visit the <a href="<?php bloginfo(url); ?>/wp-admin/widgets.php">Widgets Page</a>. Transfer the "Follow Me" selection from the left to the right and click "Save Changes".</p>
<strong>No Widgets? No Problem!</strong>
<p>If your version of Wordpress, or your blog's template, doesn't support Widgets, feel free to use the following template tag to add the Follow Me plugin to your blog. <strong>&#60;?php FollowMeSocialMedia() ?&#62;</strong> All you have to do is copy the tag and paste it anywhere in your template that you would like the Follow Me badge to appear.</p>

<table width="800px" border="0" style="border-collapse: collapse">
  <tbody>
    <tr>
      <th width="222" align="left"><label>
        <strong>Layout:</strong></label></th>
      <td aligh="left" width="568"><p>With the Follow Me Plugin you have the option of displaying your links using the default button or with a button free "link box" option. </p>
        <p>&nbsp;</p></td>
    </tr>
  </tbody>
</table>
<table border="0" style="border-collapse: collapse" width="800px">
  <tbody>
    <tr>
      <th width="222" align="left"><label><input name="buttons" value="button.gif" <?php
if(get_option('FollowMe_Buttons') == 'button.gif')
{
 echo "checked='checked'";
}
?> class="tog" type="radio">Default Button </label></th>
      <td width="568"><p><img src=" 
<?php echo $pluginpath; ?>/images/button.gif"/></p>
        <p>&nbsp;</p></td>
    </tr>
    <tr>
      <th align="left"><label>
        <input name="buttons" value="buttonmid.gif" class="tog" <?php
if(get_option('FollowMe_Buttons') == 'buttonmid.gif')
{
 echo "checked='checked'";
}
?> type="radio">Medium Button </label></th>
      <td><p><img src=" 
<?php echo $pluginpath; ?>/images/buttonmid.gif"/></p>
        <p>&nbsp;</p></td>
    </tr>
    <tr>
      <th align="left"><label>
        <input name="buttons" value="buttonlrg.gif" class="tog" <?php
if(get_option('FollowMe_Buttons') == 'buttonlrg.gif')
{
 echo "checked='checked'";
}
?> type="radio">Large Button</label></th>
      <td><p><img src=" 
<?php echo $pluginpath; ?>/images/buttonlrg.gif"/></p>
        <p>&nbsp;</p></td>
    </tr>
  </tbody>
</table>
<table width="800px" border="0" class="form-table" style="border-collapse: collapse">
  <tbody>
    <tr>
      <th align="left"><input name="buttons" value="none" <?php
if(get_option('FollowMe_Buttons') == 'none')
{
 echo "checked='checked'";
}
?> class="tog" type="radio">Display as Box:</th>
      <td><img src=" 
<?php echo $pluginpath; ?>/images/box-layout.png"/></td>
    </tr>
    <tr>
      <th width="220" align="left"><label>
        </label></th>
      <td width="570">&nbsp;
        </td>
    </tr>
  </tbody>
</table>
<strong>Number of Columns:</strong>
<label>1<input name="columns" value="1" <?php
if(get_option('FollowMe_Columns') == 1)
{
 echo "checked='checked'";
}
?> class="tog" type="radio"></label>
<br/>
<label>2<input name="columns" value="2" <?php
if(get_option('FollowMe_Columns') != '1')
{
 echo "checked='checked'";
}
?> class="tog" type="radio"></label>
<strong>Window Alignment:</strong>
<table border="0" style="border-collapse: collapse" class="form-table" width="800px">
  <tbody>
    <tr>
      <th width="224" align="left"><label>
        <input name="layout" value="true" <?php
if(get_option('FollowMe_Layout') == 'true')
{
 echo "checked='checked'";
}
?> class="tog" type="radio">
        Open to the left:</label></th>
      <td width="566"><img src=" 
<?php echo $pluginpath; ?>/images/left-right.png"/></td>
    </tr>
    <tr>
      <th align="left"><label>
        <input name="layout" value="false" class="tog" <?php
if(get_option('FollowMe_Layout') == 'false')
{
 echo "checked='checked'";
}
?> type="radio">
        Open to the right:</label></th>
      <td><img src=" 
<?php echo $pluginpath; ?>/images/right-left.png"/></td>
    </tr>
  </tbody>
</table>
<p>&nbsp;</p>
</div>
</div>
</div>
<p class="submit">
<input type="submit" class="button-primary" name="Submit" value="<?php _e('Save Changes') ?>" />
</p>
</form>
</div>		
</div>
</div>
</div>
</div>
</div>
</div>
</div>



</div>
<?php 	
}

function widget_FollowMeIgniteSocialMedia_control()
{
?>
<div style="text-align:left">
To edit the options for this widget, please visit the <a href="options-general.php?page=fmoptions">Follow Me Settings Page</a>.
</div>
<?php
}

/* CALL OPTIONS PAGE */

function FollowMeIgniteSocialMedia_add_pages()
{
$mypage = add_options_page('Follow Me Options', 'Follow Me Options', 8, 'fmoptions', 'FollowMeIgniteSocialMedia_options_page');
add_action( "admin_print_scripts-$mypage", 'FollowMe_admin_head' );
}
function FollowMe_admin_head() {
    $pluginpath = WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__)); 
	wp_enqueue_script('loadjs', $pluginpath . 'js/expand.js');
    echo "<script type='text/javascript' src='".$pluginpath."js/jquery.js'></script>";
}


/* CALL SIDE BAR WIDGET */
function widget_FollowMeIgniteSocialMedia()
{
 FollowMeSocialMedia();
}
function FollowMeIgniteSocialMedia_init()
{
 register_sidebar_widget(__('Follow Me'), 'widget_FollowMeIgniteSocialMedia');
 register_widget_control('Follow Me', 'widget_FollowMeIgniteSocialMedia_control');
}

/* HOOK IT IN */


add_action('wp_head', 'FollowMe_Scripts');
add_action('wp_footer', 'FollowMe_Bubble');
add_action('plugins_loaded', 'FollowMeIgniteSocialMedia_init');
add_action('admin_menu', 'FollowMeIgniteSocialMedia_add_pages');
?>