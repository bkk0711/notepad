<?php 
// kết nối CSDL
include"db.php";
$message = mysql_escape_string(htmlspecialchars($_POST["message"])); 
$url     = mysql_escape_string(strip_tags($_POST["url"])); 
$mode    = mysql_escape_string($_POST["mode"]);
$theme   = mysql_escape_string($_POST['theme']);
$share   = mysql_escape_string($_POST["share"]); 
$time    = time(); //lấy timestamp của thời điểm hiện tại
$ip      = $_SERVER['SERVER_ADDR']; // lấy ip của người viết note

// hàm random chuỗi 
function rand_string($length, $max) 
{
  $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";  
  $size  = strlen( $chars );
  for($i = 0; $i < $max; $i++) {
    $str .= $chars[ rand( 0, $size - 1 ) ];
  }
  // xuất ra chuỗi ngẫu nhiên có độ dài bằng $max - 1
  return $str;
}
//tạo ra chuỗi ngẫu nhiên và gán
$public_url = rand_string(5,rand(5,20));
// dòng này là để đếm xem có bao nhiêu note có cùng url
$total = mysql_num_rows(mysql_query("SELECT * FROM notes WHERE url = '$url'"));
// nếu chưa có
if($total  < 1)
{
  // thì thêm vô
 $success = mysql_query("INSERT INTO notes(url, note, ip, public_url, time, mode, theme) VALUES ('$url', '$message','$ip','$public_url', '$time', '$mode', '$theme')");
 // trả về 2 <input> có chứa url mới
   ?>
  <div class="input-group">
    <span class="input-group-addon">Admin URL</span>
    <input type="text" class="form-control" value="<?php echo $domain.'/'.$url; ?>" maxlength="20">
  </div>
<!---/.input-group--->
<p>
  <small>
        <font color="red">*</font> Đường dẫn truy cập có thế chỉnh sửa
  </small>
</p>
<div class="input-group">
    <span class="input-group-addon">Link Share</span>
    <input type="text" id = "share" class="form-control" value="<?php echo $domain.'/share/'.$public_url; ?>" maxlength="20">
</div>
<!---/.input-group--->
<p>
  <small>
        <font color="red">*</font> Đường dẫn truy cập chỉ xem , không thể chỉnh sửa
    </small>
    </p>
    <?php
}else{
  // nếu đã tồn tại rồi thì cập nhật lại nó
    mysql_query("UPDATE `notes` SET `url` = '$url',`note`= '$message',`ip` = '$ip',`time`='$time',`mode`='$mode',`theme`='$theme' WHERE `url` = '$url'");
    ?>
  <div class="input-group">
    <span class="input-group-addon">Admin URL</span>
    <input type="text" class="form-control" value="<?php echo $domain.'/'.$url; ?>" maxlength="20">
  </div><!---/.input-group--->
<p>
  <small>
        <font color="red">*</font> Đường dẫn truy cập có thế chỉnh sửa
  </small>
</p>
<div class="input-group">
    <span class="input-group-addon">Link Share</span>
    <input type="text" id = "share" class="form-control" value="<?php echo $share; ?>" maxlength="20">
</div><!---/.input-group--->
<p>
  <small>
        <font color="red">*</font> Đường dẫn truy cập chỉ xem , không thể chỉnh sửa
  </small>
</p>
<?php
}
?>

                   