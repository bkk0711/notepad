<?php
// kết nối đến database
include "db.php";
// hàm này là để random chuỗi
function rand_string($length, $max)
{
    // các ký tự để nó random
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    // lấy chiều dài của chuỗi và gán
    $size = strlen($chars);
    // đây là vòng lặp for
    for ($i = 0; $i < $max; $i++) 
    {
        $str .= $chars[rand(0, $size - 1)];
    }
    // trả về 1 chuỗi với các ký tự bất kỳ có độ dài bằng $max-1
    return $str;
}
// cái này để get cái public_url note  :3
$get_url = $_GET['public_url'];

// kiểm tra public_url note có hay chưa
if (isset($get_url)) {
    // dòng này là để đếm xem có bao nhiêu note có cùng public_url (trong database thì tên là "url")
    $total = mysql_num_rows(mysql_query("SELECT * FROM notes WHERE url = '$get_url'"));
    // nếu nó lớn hơn 0 , tức nó có tồn tại thì show nội dung nó ra
    if ($total > 0) {
        // lấy toàn bộ cột trong bảng notes có url là public_url , chỉ lấy 1 hàng mới nhất 
        // cái này chỉ cần "SELECT * FROM notes WHERE url='$get_url'" là đủ vì ràng buộc code rất chắc rồi 
        $sql        = mysql_query("SELECT * FROM notes WHERE url='$get_url' ORDER BY id DESC LIMIT 0,1");
        // mấy cái dưới này là gán giá trị :3
        $row        = mysql_fetch_array($sql);
        $note       = $row['note']; // này là nội dung note nè
        $mode       = $row['mode']; // này là note dùng ngôn ngữ gì nè
        $theme      = $row['theme']; // này là giao diện note nè
        $id         = $row['id']; // này là id nè , cái này để phiên bản sau dùng :3
        // lấy ra public_url , à cái public_url này khác cái trên nha.
        // Cái này là dùng để giấu url có thể sửa được . cái public_url này là để share , người xem không thể sửa
        $public_url = $row['public_url']; 
        $access_url = $get_url; // cái này là gán vô nè , cái url này truy cập vô là sửa được nha
    } else {
        $access_url = $get_url; // giống như trên 
    }
} else { 
    // nếu không GET được public_url thì tạo url mới
    $access_url = rand_string(5, rand(5, 9));
}

?>
<html>

<head>
    <title>Notepad Plus</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="description" content="Notepad Online, save your note, Safe your note. Notepad save notes online, save notes, notepad online, online notepad, simple notepad, codepad" />
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
    <script src="/js/jquery-1.10.1.min.js"></script>
    <!-- Optional theme -->
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
    <style>
        .btn-save {
            color: #ffffff !important;
            background-color: #3498db;
            border-color: #3498db;
            margin: 8px 3px 0px 0px;
            text-transform: capitalize;
            border: 0px solid #000;
        }

        .ace_scrollbar {
            contain: strict;
            position: absolute;
            right: 0;
            bottom: 0;
            width: 5px !important;
            border-radius: 5px;
            z-index: 6;
        }
    </style>
</head>

<body>

    <div class="container">
        <br />
        <h3 class="slogan" style="color: gray;"> NOTEPAD PLUS ONLINE <br>Save your notes - Safe your notes</h3>
        <div class="row"> 
            <div class="col-md-8">
                <textarea rows="20" class="quick form-control" id="editor"></textarea>
            </div><!--/.col-md-8-->
            <div class="col-md-4">
                Tình Trạng : <b class="status">Chưa nhập</b><br /><br />
                <button onclick="save()" class="btn btn-save btn-block btn-lg">Lưu</button>
                <br />
                <div class="input-group">
                    <span class="input-group-addon">
                    <?php 
                    // xuất ra domain không có http:// hoặc https://
                    echo $dm; 
                    ?>
                        
                    </span>
                    <input type="text" class="form-control" id="url" placeholder="Username" value="<?php 
                    // cái này là url được get , nếu k có thì nó tạo :3 ở trên có nói rồi ă
                    echo $access_url; 
                    ?>" maxlength="20">

                </div><!---/.input-group--->
                <p>
                    <small>
                        <font color="red">*</font> Bạn có thể đổi đường dẫn truy cập
                    </small>
                </p>

                <div id="public_url" class="box">
                    <div class="input-group">
                        <span class="input-group-addon">Admin URL</span>
                        <input type="text" class="form-control" value="<?php
                        // cái url này vô là sửa được ă nha
                         echo $domain.'/'.$access_url; 
                         ?>" maxlength="20">
                    </div><!---/.input-group--->
                    <p>
                        <small>
                            <font color="red">*</font> Đường dẫn truy cập có thế chỉnh sửa
                        </small>
                    </p>
                    <div class="input-group">
                        <span class="input-group-addon">Link Share</span>
                        <input id="share" type="text" class="form-control" value="<?php
                        // url này là để share nè , ngta sửa không được ă
                         echo $domain.'/share/'.$public_url; ?>" maxlength="20">
                    </div><!---/.input-group--->
                    <p>
                        <small>
                            <font color="red">*</font> Đường dẫn truy cập chỉ xem , không thể chỉnh sửa
                        </small>
                    </p>
                </div><!---/.box--->

                <div class="input-group">
                    <span class="input-group-addon"> Ngôn Ngữ</span>
                    <select id="-mode" onclick="changes()" class="form-control">
                        <option value="ace/mode/abap">ABAP</option>
                        <option value="ace/mode/abc">ABC</option>
                        <option value="ace/mode/actionscript">ActionScript</option>
                        <option value="ace/mode/ada">ADA</option>
                        <option value="ace/mode/apache_conf">Apache Conf</option>
                        <option value="ace/mode/asciidoc">AsciiDoc</option>
                        <option value="ace/mode/asl">ASL</option>
                        <option value="ace/mode/assembly_x86">Assembly x86</option>
                        <option value="ace/mode/autohotkey">AutoHotkey / AutoIt</option>
                        <option value="ace/mode/apex">Apex</option>
                        <option value="ace/mode/aql">AQL</option>
                        <option value="ace/mode/batchfile">BatchFile</option>
                        <option value="ace/mode/c_cpp">C and C++</option>
                        <option value="ace/mode/c9search">C9Search</option>
                        <option value="ace/mode/crystal">Crystal</option>
                        <option value="ace/mode/cirru">Cirru</option>
                        <option value="ace/mode/clojure">Clojure</option>
                        <option value="ace/mode/cobol">Cobol</option>
                        <option value="ace/mode/coffee">CoffeeScript</option>
                        <option value="ace/mode/coldfusion">ColdFusion</option>
                        <option value="ace/mode/csharp">C#</option>
                        <option value="ace/mode/csound_document">Csound Document</option>
                        <option value="ace/mode/csound_orchestra">Csound</option>
                        <option value="ace/mode/csound_score">Csound Score</option>
                        <option value="ace/mode/css">CSS</option>
                        <option value="ace/mode/curly">Curly</option>
                        <option value="ace/mode/d">D</option>
                        <option value="ace/mode/dart">Dart</option>
                        <option value="ace/mode/diff">Diff</option>
                        <option value="ace/mode/dockerfile">Dockerfile</option>
                        <option value="ace/mode/dot">Dot</option>
                        <option value="ace/mode/drools">Drools</option>
                        <option value="ace/mode/edifact">Edifact</option>
                        <option value="ace/mode/eiffel">Eiffel</option>
                        <option value="ace/mode/ejs">EJS</option>
                        <option value="ace/mode/elixir">Elixir</option>
                        <option value="ace/mode/elm">Elm</option>
                        <option value="ace/mode/erlang">Erlang</option>
                        <option value="ace/mode/forth">Forth</option>
                        <option value="ace/mode/fortran">Fortran</option>
                        <option value="ace/mode/fsharp">FSharp</option>
                        <option value="ace/mode/fsl">FSL</option>
                        <option value="ace/mode/ftl">FreeMarker</option>
                        <option value="ace/mode/gcode">Gcode</option>
                        <option value="ace/mode/gherkin">Gherkin</option>
                        <option value="ace/mode/gitignore">Gitignore</option>
                        <option value="ace/mode/glsl">Glsl</option>
                        <option value="ace/mode/gobstones">Gobstones</option>
                        <option value="ace/mode/golang">Go</option>
                        <option value="ace/mode/graphqlschema">GraphQLSchema</option>
                        <option value="ace/mode/groovy">Groovy</option>
                        <option value="ace/mode/haml">HAML</option>
                        <option value="ace/mode/handlebars">Handlebars</option>
                        <option value="ace/mode/haskell">Haskell</option>
                        <option value="ace/mode/haskell_cabal">Haskell Cabal</option>
                        <option value="ace/mode/haxe">haXe</option>
                        <option value="ace/mode/hjson">Hjson</option>
                        <option value="ace/mode/html">HTML</option>
                        <option value="ace/mode/html_elixir">HTML (Elixir)</option>
                        <option value="ace/mode/html_ruby">HTML (Ruby)</option>
                        <option value="ace/mode/ini">INI</option>
                        <option value="ace/mode/io">Io</option>
                        <option value="ace/mode/jack">Jack</option>
                        <option value="ace/mode/jade">Jade</option>
                        <option value="ace/mode/java">Java</option>
                        <option value="ace/mode/javascript">JavaScript</option>
                        <option value="ace/mode/json">JSON</option>
                        <option value="ace/mode/jsoniq">JSONiq</option>
                        <option value="ace/mode/jsp">JSP</option>
                        <option value="ace/mode/jssm">JSSM</option>
                        <option value="ace/mode/jsx">JSX</option>
                        <option value="ace/mode/julia">Julia</option>
                        <option value="ace/mode/kotlin">Kotlin</option>
                        <option value="ace/mode/latex">LaTeX</option>
                        <option value="ace/mode/less">LESS</option>
                        <option value="ace/mode/liquid">Liquid</option>
                        <option value="ace/mode/lisp">Lisp</option>
                        <option value="ace/mode/livescript">LiveScript</option>
                        <option value="ace/mode/logiql">LogiQL</option>
                        <option value="ace/mode/lsl">LSL</option>
                        <option value="ace/mode/lua">Lua</option>
                        <option value="ace/mode/luapage">LuaPage</option>
                        <option value="ace/mode/lucene">Lucene</option>
                        <option value="ace/mode/makefile">Makefile</option>
                        <option value="ace/mode/markdown">Markdown</option>
                        <option value="ace/mode/mask">Mask</option>
                        <option value="ace/mode/matlab">MATLAB</option>
                        <option value="ace/mode/maze">Maze</option>
                        <option value="ace/mode/mel">MEL</option>
                        <option value="ace/mode/mixal">MIXAL</option>
                        <option value="ace/mode/mushcode">MUSHCode</option>
                        <option value="ace/mode/mysql">MySQL</option>
                        <option value="ace/mode/nginx">Nginx</option>
                        <option value="ace/mode/nix">Nix</option>
                        <option value="ace/mode/nim">Nim</option>
                        <option value="ace/mode/nsis">NSIS</option>
                        <option value="ace/mode/objectivec">Objective-C</option>
                        <option value="ace/mode/ocaml">OCaml</option>
                        <option value="ace/mode/pascal">Pascal</option>
                        <option value="ace/mode/perl">Perl</option>
                        <option value="ace/mode/perl6">Perl 6</option>
                        <option value="ace/mode/pgsql">pgSQL</option>
                        <option value="ace/mode/php_laravel_blade">PHP (Blade Template)</option>
                        <option value="ace/mode/php">PHP</option>
                        <option value="ace/mode/puppet">Puppet</option>
                        <option value="ace/mode/pig">Pig</option>
                        <option value="ace/mode/powershell">Powershell</option>
                        <option value="ace/mode/praat">Praat</option>
                        <option value="ace/mode/prolog">Prolog</option>
                        <option value="ace/mode/properties">Properties</option>
                        <option value="ace/mode/protobuf">Protobuf</option>
                        <option value="ace/mode/python">Python</option>
                        <option value="ace/mode/r">R</option>
                        <option value="ace/mode/razor">Razor</option>
                        <option value="ace/mode/rdoc">RDoc</option>
                        <option value="ace/mode/red">Red</option>
                        <option value="ace/mode/rhtml">RHTML</option>
                        <option value="ace/mode/rst">RST</option>
                        <option value="ace/mode/ruby">Ruby</option>
                        <option value="ace/mode/rust">Rust</option>
                        <option value="ace/mode/sass">SASS</option>
                        <option value="ace/mode/scad">SCAD</option>
                        <option value="ace/mode/scala">Scala</option>
                        <option value="ace/mode/scheme">Scheme</option>
                        <option value="ace/mode/scss">SCSS</option>
                        <option value="ace/mode/sh">SH</option>
                        <option value="ace/mode/sjs">SJS</option>
                        <option value="ace/mode/slim">Slim</option>
                        <option value="ace/mode/smarty">Smarty</option>
                        <option value="ace/mode/snippets">snippets</option>
                        <option value="ace/mode/soy_template">Soy Template</option>
                        <option value="ace/mode/space">Space</option>
                        <option value="ace/mode/sql">SQL</option>
                        <option value="ace/mode/sqlserver">SQLServer</option>
                        <option value="ace/mode/stylus">Stylus</option>
                        <option value="ace/mode/svg">SVG</option>
                        <option value="ace/mode/swift">Swift</option>
                        <option value="ace/mode/tcl">Tcl</option>
                        <option value="ace/mode/terraform">Terraform</option>
                        <option value="ace/mode/tex">Tex</option>
                        <option value="ace/mode/text">Text</option>
                        <option value="ace/mode/textile">Textile</option>
                        <option value="ace/mode/toml">Toml</option>
                        <option value="ace/mode/tsx">TSX</option>
                        <option value="ace/mode/twig">Twig</option>
                        <option value="ace/mode/typescript">Typescript</option>
                        <option value="ace/mode/vala">Vala</option>
                        <option value="ace/mode/vbscript">VBScript</option>
                        <option value="ace/mode/velocity">Velocity</option>
                        <option value="ace/mode/verilog">Verilog</option>
                        <option value="ace/mode/vhdl">VHDL</option>
                        <option value="ace/mode/visualforce">Visualforce</option>
                        <option value="ace/mode/wollok">Wollok</option>
                        <option value="ace/mode/xml">XML</option>
                        <option value="ace/mode/xquery">XQuery</option>
                        <option value="ace/mode/yaml">YAML</option>
                        <option value="ace/mode/zeek">Zeek</option>
                        <option value="ace/mode/django">Django</option>
                    </select>
                </div><!---/.input-group--->
                <p>
                    <small>
                        <font color="red">*</font> Lựa chọn ngôn ngữ để dễ dàng chỉnh sửa
                    </small>
                </p>

                <div class="input-group">
                    <span class="input-group-addon"> Giao Diện</span>
                    <select id="-theme" onclick="changes()" class="form-control">
                        <optgroup label="Sáng">
                            <option value="ace/theme/chrome">Chrome</option>
                            <option value="ace/theme/clouds">Clouds</option>
                            <option value="ace/theme/crimson_editor">Crimson Editor</option>
                            <option value="ace/theme/dawn">Dawn</option>
                            <option value="ace/theme/dreamweaver">Dreamweaver</option>
                            <option value="ace/theme/eclipse">Eclipse</option>
                            <option value="ace/theme/github">GitHub</option>
                            <option value="ace/theme/iplastic">IPlastic</option>
                            <option value="ace/theme/solarized_light">Solarized Light</option>
                            <option value="ace/theme/textmate">TextMate</option>
                            <option value="ace/theme/tomorrow">Tomorrow</option>
                            <option value="ace/theme/xcode">XCode</option>
                            <option value="ace/theme/kuroir">Kuroir</option>
                            <option value="ace/theme/katzenmilch">KatzenMilch</option>
                            <option value="ace/theme/sqlserver">SQL Server</option>
                        </optgroup>
                        <optgroup label="Tối">
                            <option value="ace/theme/ambiance">Ambiance</option>
                            <option value="ace/theme/chaos">Chaos</option>
                            <option value="ace/theme/clouds_midnight">Clouds Midnight</option>
                            <option value="ace/theme/dracula">Dracula</option>
                            <option value="ace/theme/cobalt">Cobalt</option>
                            <option value="ace/theme/gruvbox">Gruvbox</option>
                            <option value="ace/theme/gob">Green on Black</option>
                            <option value="ace/theme/idle_fingers">idle Fingers</option>
                            <option value="ace/theme/kr_theme">krTheme</option>
                            <option value="ace/theme/merbivore">Merbivore</option>
                            <option value="ace/theme/merbivore_soft">Merbivore Soft</option>
                            <option value="ace/theme/mono_industrial">Mono Industrial</option>
                            <option value="ace/theme/monokai">Monokai</option>
                            <option value="ace/theme/pastel_on_dark">Pastel on dark</option>
                            <option value="ace/theme/solarized_dark">Solarized Dark</option>
                            <option value="ace/theme/terminal">Terminal</option>
                            <option value="ace/theme/tomorrow_night">Tomorrow Night</option>
                            <option value="ace/theme/tomorrow_night_blue">Tomorrow Night Blue</option>
                            <option value="ace/theme/tomorrow_night_bright">Tomorrow Night Bright</option>
                            <option value="ace/theme/tomorrow_night_eighties">Tomorrow Night 80s</option>
                            <option value="ace/theme/twilight">Twilight</option>
                            <option value="ace/theme/vibrant_ink">Vibrant Ink</option>
                        </optgroup>
                    </select>
                </div><!---/.input-group--->
                <p>
                    <small>
                        <font color="red">*</font> Lựa chọn màu sắc phù hợp bảo vệ mắt
                    </small>
                </p>
                <div class="input-group">
                    <span class="input-group-addon">Password</span>
                    <input id="pass" type="password" class="form-control" name="password" maxlength="20">
                    <span class="input-group-addon">
                        <input type="checkbox" onclick="showpass()">
                    </span>
                </div><!---/.input-group--->
                <p>
                    <small>
                        <font color="red">*</font> Đặt mật khẩu để bảo vệ tài liệu của bạn ( chưa viết )
                    </small>
                </p>
            </div><!---/.col-md-4--->
        </div><!---/.row--->
    </div><!---/.container--->

    <script src="https://pagecdn.io/lib/ace/1.4.6/ace.js" integrity="sha256-CVkji/u32aj2TeC+D13f7scFSIfphw2pmu4LaKWMSY8=" crossorigin="anonymous"></script>
    <script src="/src-noconflict/ext-language_tools.js"></script>
    <!-- Latest compiled and minified JavaScript  -->
    <script>
        function showpass() { //hàm này để show/hide cái mật khẩu ă
            var x = document.getElementById("pass"); // lấy thằng nào có id = "pass"
            if (x.type === "password") { //nếu type ="password" 
                x.type = "text"; //  thì đổi thành type ="text"
            } else {//nếu type ="text"
                x.type = "password"; // thì đổi thành type = "password"
            }
        }
        // hàm này là mã hóa Base64 để dảm bảo quyền riêng tư của note đó 
        // hàm này mã hóa có thể giải được , chỉ là hạn chế xem nội dung của người khác
        // chỉ cố ý mới xem được
        var Base64 = {
            _keyStr: "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",
            encode: function(e) {
                var t = "";
                var n, r, i, s, o, u, a;
                var f = 0;
                e = Base64._utf8_encode(e);
                while (f < e.length) {
                    n = e.charCodeAt(f++);
                    r = e.charCodeAt(f++);
                    i = e.charCodeAt(f++);
                    s = n >> 2;
                    o = (n & 3) << 4 | r >> 4;
                    u = (r & 15) << 2 | i >> 6;
                    a = i & 63;
                    if (isNaN(r)) {
                        u = a = 64
                    } else if (isNaN(i)) {
                        a = 64
                    }
                    t = t + this._keyStr.charAt(s) + this._keyStr.charAt(o) + this._keyStr.charAt(u) + this._keyStr.charAt(a)
                }
                return t
            },
            decode: function(e) {
                var t = "";
                var n, r, i;
                var s, o, u, a;
                var f = 0;
                e = e.replace(/[^A-Za-z0-9+/=]/g, "");
                while (f < e.length) {
                    s = this._keyStr.indexOf(e.charAt(f++));
                    o = this._keyStr.indexOf(e.charAt(f++));
                    u = this._keyStr.indexOf(e.charAt(f++));
                    a = this._keyStr.indexOf(e.charAt(f++));
                    n = s << 2 | o >> 4;
                    r = (o & 15) << 4 | u >> 2;
                    i = (u & 3) << 6 | a;
                    t = t + String.fromCharCode(n);
                    if (u != 64) {
                        t = t + String.fromCharCode(r)
                    }
                    if (a != 64) {
                        t = t + String.fromCharCode(i)
                    }
                }
                t = Base64._utf8_decode(t);
                return t
            },
            _utf8_encode: function(e) {
                e = e.replace(/rn/g, "n");
                var t = "";
                for (var n = 0; n < e.length; n++) {
                    var r = e.charCodeAt(n);
                    if (r < 128) {
                        t += String.fromCharCode(r)
                    } else if (r > 127 && r < 2048) {
                        t += String.fromCharCode(r >> 6 | 192);
                        t += String.fromCharCode(r & 63 | 128)
                    } else {
                        t += String.fromCharCode(r >> 12 | 224);
                        t += String.fromCharCode(r >> 6 & 63 | 128);
                        t += String.fromCharCode(r & 63 | 128)
                    }
                }
                return t
            },
            _utf8_decode: function(e) {
                var t = "";
                var n = 0;
                var r = c1 = c2 = 0;
                while (n < e.length) {
                    r = e.charCodeAt(n);
                    if (r < 128) {
                        t += String.fromCharCode(r);
                        n++
                    } else if (r > 191 && r < 224) {
                        c2 = e.charCodeAt(n + 1);
                        t += String.fromCharCode((r & 31) << 6 | c2 & 63);
                        n += 2
                    } else {
                        c2 = e.charCodeAt(n + 1);
                        c3 = e.charCodeAt(n + 2);
                        t += String.fromCharCode((r & 15) << 12 | (c2 & 63) << 6 | c3 & 63);
                        n += 3
                    }
                }
                return t
            }
        }

        // khởi tạo trình chỉnh sửa ACE editor 
        var editor = ace.edit("editor");
        // cái này là nhập thư viện vô nè 
        ace.require("ace/ext/language_tools");
        // mấy cái dưới này là tùy chỉnh Options
        editor.setAutoScrollEditorIntoView(true);
        editor.setOptions({
            enableBasicAutocompletion: true,
            enableSnippets: true,
            enableLiveAutocompletion: true
        });
        editor.setOption("showPrintMargin", false)
        editor.setOption("maxLines", 40);
        editor.setOption("minLines", 30); 
        <?php
        // cái này là show nội dung của note ra nếu get public_url có tồn tại
        if (isset($note)) {
            // set value của ace editor là nội dung đó , đồng thời giải mã nội dung để người đó có thể xem và chỉnh sửa
            echo 'editor.setValue(Base64.decode("'.$note.'"));';
        } ?>
        <?php
        if (isset($mode) && isset($theme)) {
            // cái này là set giao diện và ngôn ngữ của note có url là public_url get ở trên đã có tồn tại trong hệ thống
            echo '
            editor.setTheme("'.$theme.'");
            editor.session.setMode("'.$mode.'");
            document.getElementById("-mode").value = "'.$mode.'";
            document.getElementById("-theme").value = "'.$theme.'";
            ';
        } ?>
        // cái này là show ra tình trạng 
        $('.ace_text-input').keypress(function() {
            $(".status").html('<i>Đang nhập...</i>');
        });

        // hàm này là để đổi giao diện và ngôn ngữ
        function changes() {
            var modes = document.getElementById('-mode').value;
            var themes = document.getElementById('-theme').value;
            editor.setTheme(themes);
            editor.session.setMode(modes);
        }
        // hàm này là lưu code nếu bạn nhấn vào nút lưu
        function save() {
            var mode = document.getElementById('-mode').value;
            var theme = document.getElementById('-theme').value;
            var message = Base64.encode(editor.getValue());
            var url = document.getElementById('url').value;
            var share = document.getElementById('share').value;
            var pass = document.getElementById('pass').value;

            model = {
                mode: mode,
                theme: theme,
                message: message,
                url: url,
                share: share
            };
            $.ajax({
                url: "save.php",
                type: "post",
                data: model,
                success: function(data) {
                    //phản hồi đã lưu khi lưu xong
                    $("#public_url").html(data);
                    $(".status").html('<i style="color:red">Đã Lưu</i>');
                },
                error: function() {
                    // nếu lỗi thì alert hiện ra và bao lỗi là : error is saving
                    alert('error is saving');
                }
            });
        }
        // cái này là lưu tự động
        $(".ace_text-input").change(function() {
            var mode = document.getElementById('-mode').value;
            var theme = document.getElementById('-theme').value;
            var message = Base64.encode(editor.getValue());
            var url = document.getElementById('url').value;
            var share = document.getElementById('share').value;
            var pass = document.getElementById('pass').value;

            model = {
                mode: mode,
                theme: theme,
                message: message,
                url: url,
                share: share
            };
            $.ajax({
                url: "save.php",
                type: "post",
                data: model,
                success: function(data) {
                    // cũng show ra Đã Lưu khi Lưu Thành Công
                    $("#public_url").html(data);
                    $(".status").html('<i style="color:red">Đã Lưu</i>');
                },
                error: function() {
                    // cũng báo lỗi khi lưu thất bại
                    alert('error is saving');
                }
            });
        });
    </script>
</body>

</html>
