<?php

namespace App\Http\Controllers\SEO_tools;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Services\CodeMinifierServiceInterface;

class CodeMinifierController extends HomeController
{


  public function __construct()
  {
    $this->set_global_userdata(true,[],[],17);    
  }


  public function index()
  {
    $data['body'] = "seo-tools.code-minifier.index";
    return $this->_viewcontroller($data);
  }
  public function html_index()
  {    
    $data['body'] = "seo-tools.code-minifier.html-minifier";
    return $this->_viewcontroller($data);
  }

  function minify_css_helper($input)
  {
    if (trim($input) === "") return $input;
    // Force white-space(s) in `calc()`
    if (strpos($input, 'calc(') !== false) {
      $input = preg_replace_callback('#(?<=[\s:])calc\(\s*(.*?)\s*\)#', function ($matches) {
        return 'calc(' . preg_replace('#\s+#', "\x1A", $matches[1]) . ')';
      }, $input);
    }
    return preg_replace(
      array(
        // Remove comment(s)
        '#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')|\/\*(?!\!)(?>.*?\*\/)|^\s*|\s*$#s',
        // Remove unused white-space(s)
        '#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\'|\/\*(?>.*?\*\/))|\s*+;\s*+(})\s*+|\s*+([*$~^|]?+=|[{};,>~+]|\s*+-(?![0-9\.])|!important\b)\s*+|([[(:])\s++|\s++([])])|\s++(:)\s*+(?!(?>[^{}"\']++|"(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')*+{)|^\s++|\s++\z|(\s)\s+#si',
        // Replace `0(cm|em|ex|in|mm|pc|pt|px|vh|vw|%)` with `0`
        '#(?<=[\s:])(0)(cm|em|ex|in|mm|pc|pt|px|vh|vw|%)#si',
        // Replace `:0 0 0 0` with `:0`
        '#:(0\s+0|0\s+0\s+0\s+0)(?=[;\}]|\!important)#i',
        // Replace `background-position:0` with `background-position:0 0`
        '#(background-position):0(?=[;\}])#si',
        // Replace `0.6` with `.6`, but only when preceded by a white-space or `=`, `:`, `,`, `(`, `-`
        '#(?<=[\s=:,\(\-]|&\#32;)0+\.(\d+)#s',
        // Minify string value
        '#(\/\*(?>.*?\*\/))|(?<!content\:)([\'"])([a-z_][-\w]*?)\2(?=[\s\{\}\];,])#si',
        '#(\/\*(?>.*?\*\/))|(\burl\()([\'"])([^\s]+?)\3(\))#si',
        // Minify HEX color code
        '#(?<=[\s=:,\(]\#)([a-f0-6]+)\1([a-f0-6]+)\2([a-f0-6]+)\3#i',
        // Replace `(border|outline):none` with `(border|outline):0`
        '#(?<=[\{;])(border|outline):none(?=[;\}\!])#',
        // Remove empty selector(s)
        '#(\/\*(?>.*?\*\/))|(^|[\{\}])(?:[^\s\{\}]+)\{\}#s',
        '#\x1A#'
      ),
      array(
        '$1',
        '$1$2$3$4$5$6$7',
        '$1',
        ':0',
        '$1:0 0',
        '.$1',
        '$1$3',
        '$1$2$4$5',
        '$1$2$3',
        '$1:0',
        '$1$2',
        ' '
      ),
      $input
    );
  }

  function minify_html($input)
  {

    if (trim($input) === "") return $input;
    // Remove extra white-space(s) between HTML attribute(s)
    $input = preg_replace_callback('#<([^\/\s<>!]+)(?:\s+([^<>]*?)\s*|\s*)(\/?)>#s', function ($matches) {
      return '<' . $matches[1] . preg_replace('#([^\s=]+)(\=([\'"]?)(.*?)\3)?(\s+|$)#s', ' $1$2', $matches[2]) . $matches[3] . '>';
    }, str_replace("\r", "", $input));
    // Minify inline CSS declaration(s)
    if (strpos($input, ' style=') !== false) {
      $input = preg_replace_callback('#<([^<]+?)\s+style=([\'"])(.*?)\2(?=[\/\s>])#s', function ($matches) {
        return '<' . $matches[1] . ' style=' . $matches[2] . $this->minify_css_helper($matches[3]) . $matches[2];
      }, $input);
    }
    return preg_replace(
      array(
        // t = text
        // o = tag open
        // c = tag close
        // Keep important white-space(s) after self-closing HTML tag(s)
        '#<(img|input)(>| .*?>)#s',
        // Remove a line break and two or more white-space(s) between tag(s)
        '#(<!--.*?-->)|(>)(?:\n*|\s{2,})(<)|^\s*|\s*$#s',
        '#(<!--.*?-->)|(?<!\>)\s+(<\/.*?>)|(<[^\/]*?>)\s+(?!\<)#s', // t+c || o+t
        '#(<!--.*?-->)|(<[^\/]*?>)\s+(<[^\/]*?>)|(<\/.*?>)\s+(<\/.*?>)#s', // o+o || c+c
        '#(<!--.*?-->)|(<\/.*?>)\s+(\s)(?!\<)|(?<!\>)\s+(\s)(<[^\/]*?\/?>)|(<[^\/]*?\/?>)\s+(\s)(?!\<)#s', // c+t || t+o || o+t -- separated by long white-space(s)
        '#(<!--.*?-->)|(<[^\/]*?>)\s+(<\/.*?>)#s', // empty tag
        '#<(img|input)(>| .*?>)<\/\1\x1A>#s', // reset previous fix
        '#(&nbsp;)&nbsp;(?![<\s])#', // clean up ...
        // Force line-break with `&#10;` or `&#xa;`
        '#&\#(?:10|xa);#',
        // Force white-space with `&#32;` or `&#x20;`
        '#&\#(?:32|x20);#',
        // Remove HTML comment(s) except IE comment(s)
        '#\s*<!--(?!\[if\s).*?-->\s*|(?<!\>)\n+(?=\<[^!])#s'
      ),
      array(
        "<$1$2</$1\x1A>",
        '$1$2$3',
        '$1$2$3',
        '$1$2$3$4$5',
        '$1$2$3$4$5$6$7',
        '$1$2$3',
        '<$1$2',
        '$1 ',
        "\n",
        ' ',
        ""
      ),
      $input
    );
  }


  public function html_minifier_textarea(Request $request)
  {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
      return redirect()->route('login');
    }
    $code = $request->html_code;

    $html_minify = $this->minify_html($code);

    $str = "<div class='card'>
                  <div class='card-header'>
                    <h4><i class='fab fa-html5'></i> " . __("HTML Minified Results") . "</h4>
                  </div>
                  <div class='card-body'>
                    <div class='form-group'>
                      <textarea id='html_code2' name='html_code2' class='form-control' style='width:100%;min-height: 300px;' rows='10'>" . $html_minify . "</textarea>
                      <div class='text-center mt-4'>
                        <button id='html_code2' type='button' data-clipboard-action='copy' data-clipboard-target='#html_code2' class='btn btn-primary'><i class='fas fa-copy'></i> " . __("Copy") . "</button>
                      </div>
                    </div>
                  </div>
                </div>
                <script>
                  var clipboard = new Clipboard('#html_code2');

                    clipboard.on('success', function(e) {
                        alert('Copied');
                    });

                    clipboard.on('error', function(e) {
                        alert('Not Copied!');
                    });
                </script>";
    echo $str;
  }

  public function read_text_file_html(Request $request)
  {
    if ($request->isMethod('get')) {
      return redirect()->route('access_forbidden');
    }

    $ret = array();
    if (!file_exists(storage_path("app/public/upload/tmp/"))) {
      mkdir(storage_path("app/public/upload/tmp/"), 0777, true);
    }
    $output_dir = storage_path("app/public/upload/tmp");
    if (isset($_FILES["myfile"])) {
      $error = $_FILES["myfile"]["error"];
      $post_fileName = $_FILES["myfile"]["name"];
      $post_fileName_array = explode(".", $post_fileName);
      $ext = array_pop($post_fileName_array);
      $filename = implode('.', $post_fileName_array);
      $filename = "html_code_minify" . Auth::user()->id . "_" . time() . substr(uniqid(mt_rand(), true), 0, 6) . "." . $ext;

      $allow = ".html";
      $allow = str_replace('.', '', $allow);
      $allow = explode(',', $allow);

      if (!in_array(strtolower($ext), $allow)) 
      {
        echo json_encode(array("are_u_kidding_me" => "yarki"));
        exit();
      }


      move_uploaded_file($_FILES["myfile"]["tmp_name"], $output_dir.'/'.$filename);

      $path = storage_path( "app/public/upload/tmp/". $filename);
      $read_handle = fopen($path, "r");
      $context_array = array('file_name' => $filename);
      $context = "";
      while (!feof($read_handle)) {
        $information = fgetcsv($read_handle);
        if (!empty($information)) {
          foreach ($information as $info) {
            if (!is_numeric($info))
              $context .= $info . "\n";
          }
        }
      }

      $context_array['content'] = trim($context, "\n");
      echo json_encode($context_array);
    }
  }

  public function read_after_delete_html(Request $request) // deletes the uploaded video to upload another one
  {
    if ($request->isMethod('get')) {
      return redirect()->route('access_forbidden');
    }
     
    $output_dir = storage_path('public/upload/html');
    if(isset($_POST["op"]) && $_POST["op"] == "delete" && isset($_POST['name']))
    {
          $fileName =$_POST['name'];
          $fileName=str_replace("..",".",$fileName); //required. if somebody is trying parent folder files
          $filePath = $output_dir. $fileName;
          if (file_exists($filePath))
          {
            unlink($filePath);
          }
    }
  }

  public function js_index()
  {
    $this->important_feature(false);
    $this->member_validity();
    $data['body'] = "seo-tools.code-minifier.js-minifier";
    return $this->_viewcontroller($data);
  }

  // JavaScript Minifier
  function minify_js($input)
  {
    if (trim($input) === "") return $input;

    return preg_replace(
      array(
        // Remove comment(s)
        '#\s*("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')\s*|\s*\/\*(?!\!|@cc_on)(?>[\s\S]*?\*\/)\s*|\s*(?<![\:\=])\/\/.*(?=[\n\r]|$)|^\s*|\s*$#',
        // Remove white-space(s) outside the string and regex
        '#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\'|\/\*(?>.*?\*\/)|\/(?!\/)[^\n\r]*?\/(?=[\s.,;]|[gimuy]|$))|\s*([!%&*\(\)\-=+\[\]\{\}|;:,.<>?\/])\s*#s',
        // Remove the last semicolon
        '#;+\}#',
        // Minify object attribute(s) except JSON attribute(s). From `{'foo':'bar'}` to `{foo:'bar'}`
        '#([\{,])([\'])(\d+|[a-z_]\w*)\2(?=\:)#i',
        // --ibid. From `foo['bar']` to `foo.bar`
        '#([\w\)\]])\[([\'"])([a-z_]\w*)\2\]#i',
        // Replace `true` with `!0`
        '#(?<=return |[=:,\(\[])true\b#',
        // Replace `false` with `!1`
        '#(?<=return |[=:,\(\[])false\b#',
        // Clean up ...
        '#\s*(\/\*|\*\/)\s*#'
      ),
      array(
        '$1',
        '$1$2',
        '}',
        '$1$3',
        '$1.$3',
        '!0',
        '!1',
        '$1'
      ),
      $input
    );
  }

  public function js_minifier_textarea(Request $request)
  {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
      return redirect()->route('access_forbidden'); 
    }

    $code = $request->js_code;

    $js_minify = $this->minify_js($code);

    $str = "<div class='card'>
                  <div class='card-header'>
                    <h4><i class='fab fa-css3'></i> " . __("JS Minified Results") . "</h4>
                  </div>
                  <div class='card-body'>
                    <div class='form-group'>
                      <textarea id='js_code2' name='js_code2' class='form-control' style='width:100%;min-height: 300px;' rows='10'>" . $js_minify . "</textarea>
                      <div class='text-center mt-4'>
                        <button id='js_code2' type='button' data-clipboard-action='copy' data-clipboard-target='#js_code2' class='btn btn-primary'><i class='fas fa-copy'></i> " . __("Copy") . "</button>
                      </div>
                    </div>
                  </div>
               </div>
               <script>
                  var clipboard = new Clipboard('.btn');

                   clipboard.on('success', function(e) {
                       alert('Copied');
                   });

                   clipboard.on('error', function(e) {
                       alert('Not Copied!');
                   });
               </script>";
    echo $str;
  }

  public function css_index()
  {
    $this->important_feature(false);
    $this->member_validity();
    $data['body'] = "seo-tools.code-minifier.css-minifier";
    return $this->_viewcontroller($data);
  }

  // CSS Minifier => http://ideone.com/Q5USEF + improvement(s)
  function minify_css($input)
  {
    if (trim($input) === "") return $input;
    // Force white-space(s) in `calc()`
    if (strpos($input, 'calc(') !== false) {
      $input = preg_replace_callback('#(?<=[\s:])calc\(\s*(.*?)\s*\)#', function ($matches) {
        return 'calc(' . preg_replace('#\s+#', "\x1A", $matches[1]) . ')';
      }, $input);
    }
    return preg_replace(
      array(
        // Remove comment(s)
        '#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')|\/\*(?!\!)(?>.*?\*\/)|^\s*|\s*$#s',
        // Remove unused white-space(s)
        '#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\'|\/\*(?>.*?\*\/))|\s*+;\s*+(})\s*+|\s*+([*$~^|]?+=|[{};,>~+]|\s*+-(?![0-9\.])|!important\b)\s*+|([[(:])\s++|\s++([])])|\s++(:)\s*+(?!(?>[^{}"\']++|"(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')*+{)|^\s++|\s++\z|(\s)\s+#si',
        // Replace `0(cm|em|ex|in|mm|pc|pt|px|vh|vw|%)` with `0`
        '#(?<=[\s:])(0)(cm|em|ex|in|mm|pc|pt|px|vh|vw|%)#si',
        // Replace `:0 0 0 0` with `:0`
        '#:(0\s+0|0\s+0\s+0\s+0)(?=[;\}]|\!important)#i',
        // Replace `background-position:0` with `background-position:0 0`
        '#(background-position):0(?=[;\}])#si',
        // Replace `0.6` with `.6`, but only when preceded by a white-space or `=`, `:`, `,`, `(`, `-`
        '#(?<=[\s=:,\(\-]|&\#32;)0+\.(\d+)#s',
        // Minify string value
        '#(\/\*(?>.*?\*\/))|(?<!content\:)([\'"])([a-z_][-\w]*?)\2(?=[\s\{\}\];,])#si',
        '#(\/\*(?>.*?\*\/))|(\burl\()([\'"])([^\s]+?)\3(\))#si',
        // Minify HEX color code
        '#(?<=[\s=:,\(]\#)([a-f0-6]+)\1([a-f0-6]+)\2([a-f0-6]+)\3#i',
        // Replace `(border|outline):none` with `(border|outline):0`
        '#(?<=[\{;])(border|outline):none(?=[;\}\!])#',
        // Remove empty selector(s)
        '#(\/\*(?>.*?\*\/))|(^|[\{\}])(?:[^\s\{\}]+)\{\}#s',
        '#\x1A#'
      ),
      array(
        '$1',
        '$1$2$3$4$5$6$7',
        '$1',
        ':0',
        '$1:0 0',
        '.$1',
        '$1$3',
        '$1$2$4$5',
        '$1$2$3',
        '$1:0',
        '$1$2',
        ' '
      ),
      $input
    );
  }

  public function css_minifier_textarea(Request $request)
  {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
      return redirect()->route('login'); 
    }

    $code = $request->css_code;
    $css_minify = $this->minify_css($code);

    $str = "<div class='card'>
                  <div class='card-header'>
                    <h4><i class='fab fa-css3'></i> " . __("CSS Minified Results") . "</h4>
                  </div>
                  <div class='card-body'>
                    <div class='form-group'>
                      <textarea id='css_code2' name='css_code2' class='form-control' style='width:100%;min-height: 300px;' rows='10'>" . $css_minify . "</textarea>
                      <div class='text-center mt-4'>
                        <button id='css_code2' type='button' data-clipboard-action='copy' data-clipboard-target='#css_code2' class='btn btn-primary'><i class='fas fa-copy'></i> " . __("Copy") . "</button>
                      </div>
                    </div>
                  </div>
               </div>
               <script>
                  var clipboard = new Clipboard('.btn');

                   clipboard.on('success', function(e) {
                       alert('Copied');
                   });

                   clipboard.on('error', function(e) {
                       alert('Not Copied!');
                   });
               </script>";
    echo $str;
  }

  public function read_text_file_css(Request $request)
  {
    if ($request->isMethod('get')) {
      return redirect()->route('access_forbidden');
    }

    $ret=array();
    $output_dir = storage_path("public/upload/tmp");
    if (isset($_FILES["myfile"])) {
        $error =$_FILES["myfile"]["error"];
        $post_fileName =$_FILES["myfile"]["name"];
        $post_fileName_array=explode(".", $post_fileName);
        $ext=array_pop($post_fileName_array);
        $filename=implode('.', $post_fileName_array);
        $filename="css_code_minify".Auth::user()->id."_".time().substr(uniqid(mt_rand(), true), 0, 6).".".$ext;

        $allow=".css";
        $allow=str_replace('.', '', $allow);
        $allow=explode(',', $allow);

        if(!in_array(strtolower($ext), $allow)) 
        {
            echo json_encode(array("are_u_kidding_me" => "yarki"));
            exit();
        }

        
        move_uploaded_file($_FILES["myfile"]["tmp_name"], $output_dir.'/'.$filename);

        $path = storage_path("app/public/upload/tmp/".$filename);
        $read_handle=fopen($path, "r");
        $context_array =array('file_name'=>$filename);
        $context ="";
        while (!feof($read_handle)) 
        {
            $information = fgetcsv($read_handle);
            if (!empty($information)) 
            {
                foreach ($information as $info) 
                {
                    if (!is_numeric($info)) 
                    $context.=$info."\n";                       
                }
            }
        }

        $context_array['content'] = trim($context, "\n");
        echo json_encode($context_array);
        
    }
  }

  public function read_after_delete_css(Request $request) // deletes the uploaded video to upload another one
  {
    if ($request->isMethod('get')) {
      return redirect()->route('access_forbidden');
    }
     
    $output_dir = storage_path("public/upload/tmp");
    if(isset($_POST["op"]) && $_POST["op"] == "delete" && isset($_POST['name']))
    {
          $fileName =$_POST['name'];
          $fileName=str_replace("..",".",$fileName); //required. if somebody is trying parent folder files
          $filePath = $output_dir. $fileName;
          if (file_exists($filePath))
          {
            unlink($filePath);
          }
    }
  }

  public function read_text_file_js(Request $request)
  {
    if ($request->isMethod('get')) {
      return redirect()->route('access_forbidden');
    }

    $ret=array();
    $output_dir = storage_path("public/upload/tmp");
    if (isset($_FILES["myfile"])) {
        $error =$_FILES["myfile"]["error"];
        $post_fileName =$_FILES["myfile"]["name"];
        $post_fileName_array=explode(".", $post_fileName);
        $ext=array_pop($post_fileName_array);
        $filename=implode('.', $post_fileName_array);
        $filename="css_code_minify".Auth::user()->id."_".time().substr(uniqid(mt_rand(), true), 0, 6).".".$ext;

        $allow=".js";
        $allow=str_replace('.', '', $allow);
        $allow=explode(',', $allow);

        if(!in_array(strtolower($ext), $allow)) 
        {
            echo json_encode(array("are_u_kidding_me" => "yarki"));
            exit();
        }

        
        move_uploaded_file($_FILES["myfile"]["tmp_name"], $output_dir.'/'.$filename);

        $path = storage_path("public/upload/tmp/".$filename);
        $read_handle=fopen($path, "r");
        $context_array =array('file_name'=>$filename);
        $context ="";
        while (!feof($read_handle)) 
        {
            $information = fgetcsv($read_handle);
            if (!empty($information)) 
            {
                foreach ($information as $info) 
                {
                    if (!is_numeric($info)) 
                    $context.=$info."\n";                       
                }
            }
        }

        $context_array['content'] = trim($context, "\n");
        echo json_encode($context_array);
        
    }
  }

  public function read_after_delete_js(Request $request) // deletes the uploaded video to upload another one
  {
    if ($request->isMethod('get')) {
      return redirect()->route('access_forbidden');
    }
     
    $output_dir = storage_path("public/upload/tmp");
    if(isset($_POST["op"]) && $_POST["op"] == "delete" && isset($_POST['name']))
    {
          $fileName =$_POST['name'];
          $fileName=str_replace("..",".",$fileName); //required. if somebody is trying parent folder files
          $filePath = $output_dir. $fileName;
          if (file_exists($filePath))
          {
            unlink($filePath);
          }
    }
  }

}
