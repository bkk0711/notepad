<?php
include"db.php"; // Kết nối database
$get_public_url = $_GET['public_url'];  // lấy public_url
$document_get = mysql_query("SELECT * FROM notes WHERE public_url='$get_public_url'");
$match_value = mysql_fetch_array($document_get);
$note = $match_value['note'];
$mode = $match_value['mode'];
$theme = $match_value['theme'];
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <title>Notepad Plus</title>
    <style type="text/css" media="screen">
        body {
            overflow: hidden;
        }
        #editor {
            margin: 0;
            position: absolute;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
        }
    </style>
</head>
<body>
    <pre id="editor"><?php
//giải mã hóa code
$content = base64_decode($note);
if(!empty($content)){
    // show code ra 
    echo $content;
}else{
    // nếu url sai thì show ra "Nothing to show !!!"
    echo "Nothing to show !!!";
}
?></pre>

<script src="https://pagecdn.io/lib/ace/1.4.6/ace.js" integrity="sha256-CVkji/u32aj2TeC+D13f7scFSIfphw2pmu4LaKWMSY8=" crossorigin="anonymous"></script>
<script src="http://tet2020.xyz/ace-builds-master/src-noconflict/ext-language_tools.js" ></script>
<script>
    var editor = ace.edit("editor");
    ace.require("ace/ext/language_tools");
    editor.setTheme("<?=$theme?>");
    editor.session.setMode("<?=$mode?>");
    editor.setOption("showPrintMargin", false)
    editor.setOptions({
        enableBasicAutocompletion: true,
        enableSnippets: true,
        enableLiveAutocompletion: true
    });
    
</script>

</body>
</html>



