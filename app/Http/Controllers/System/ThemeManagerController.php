<?php

namespace App\Http\Controllers\System;

use ZipArchive;
use IteratorIterator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use Symfony\Component\Finder\Iterator\RecursiveDirectoryIterator;

class ThemeManagerController extends HomeController
{

    public function __construct(){
       $this->set_global_userdata(false,['Admin']);  
    }

    public function index()
    {
        return $this->lists();
    }


    public function lists()
    {
        $data['page_title'] = __("Theme Manager");
        $data['body'] = 'system.theme-manager.index';
        $data['is_demo'] = config('app.is_demo');
        // $data['theme_list'] = $this->theme_list();
        return $this->_viewcontroller($data);    
    }


    public function theme_list()
    {
        $myDir = base_path('views/site');
        $file_list = $this->_scanFolder($myDir);
        $one_list_array=array();

        foreach ($file_list as $file) {
            $i = 0;
            $one_list[$i] = $file['file'];
            $one_list[$i]=str_replace("\\", "/",$one_list[$i]);
            $one_list_array[] = explode("/",$one_list[$i]);
        }   
        $final_list_array=array();  

        $i=0;
        foreach ($one_list_array as $value) 
        {
            $pos=count($value)-1; // addonController.php

            $folder_name = $value[$pos];
            $path=base_path('views/site/'.$folder_name);
            $addon_data[$i]=$this->get_theme_data($path."/index.php"); // inside home.php
            $thumb_path = 'application/views/site/'.$folder_name.'/thumb.png';
            if(file_exists($thumb_path))
                $addon_data[$i]['thumb']='application/views/site/'.$folder_name.'/thumb.png';
            else
                $addon_data[$i]['thumb']='';
            $addon_data[$i]['folder_name'] = $folder_name;
            $i++;
        }

        return $addon_data;
    }

    public function upload()
    {
        if(config('app.is_demo') == '1')
        {
            echo "<h2 style='text-align:center;color:red;border:1px solid red; padding: 10px'>This feature is disabled in this demo.</h2>"; 
            exit();
        }

        $data['page_title'] = __("Install Theme");
        $data['body'] = 'system.theme-manager.new-theme-install';
        return $this->_viewcontroller($data);  
    }


    public function upload_addon_zip()
    {
        if(config('app.is_demo') == '1')
        {
            echo "<h2 style='text-align:center;color:red;border:1px solid red; padding: 10px'>This feature is disabled in this demo.</h2>"; 
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'GET') exit();

        $ret=array();
        $output_dir =storage_path("upload/themes");
        if (!file_exists($output_dir)) {
            mkdir($output_dir, 0755, true);
        }
        if (isset($_FILES["myfile"])) 
        {
            $error =$_FILES["myfile"]["error"];
            $post_fileName =$_FILES["myfile"]["name"];
            $post_fileName_array=explode(".", $post_fileName);
            $ext=array_pop($post_fileName_array);
            $filename=implode('.', $post_fileName_array);
            $filename="addon_".Auth::user()->id."_".time().substr(uniqid(mt_rand(), true), 0, 6).".".$ext;


            $allow=".zip";
            $allow=str_replace('.', '', $allow);
            $allow=explode(',', $allow);
            if(!in_array(strtolower($ext), $allow)) 
            {
                echo json_encode("Are you kidding???");
                exit();
            }
            
            move_uploaded_file($_FILES["myfile"]["tmp_name"], $output_dir.'/'.$filename);
            $ret[]= $filename;

            $zip = new ZipArchive;
            if ($zip->open($output_dir.'/'.$filename) === TRUE) 
            {
                $addon_path=base_path("application/views/site/");
                $zip->extractTo($addon_path);
                $zip->close();
                @unlink($output_dir.'/'.$filename);
                session()->flash('theme_upload_success',__('Theme has been uploaded successfully. you can activate it from here.'));
            } 
            echo json_encode($filename);
        }
    }

    public function _scanFolder($myDir)
    {
        $dirTree = array();
        $di = new RecursiveDirectoryIterator($myDir,RecursiveDirectoryIterator::SKIP_DOTS);

        $i=0;
        foreach (new IteratorIterator($di) as $filename) {
            if ($filename->isDir()) 
            {
                $dir = str_replace($myDir, '', dirname($filename));
                $org_dir=str_replace("\\", "/", $dir);

                if($org_dir)
                    $file_path = $org_dir. "/". basename($filename);
                else
                    $file_path = basename($filename);

                $file_full_path=$myDir."/".$file_path;
                $file_size= filesize($file_full_path);
                $file_modification_time=filemtime($file_full_path);

                $dirTree[$i]['file'] = $file_full_path;
                $i++;
            }
        }
        return $dirTree;
    }

    public function active_deactive_theme(Request $request)
    {
        // $this->ajax_check();
        if(Auth::user()->user_type != 'Admin')
        {
            echo json_encode(array('status'=>'0','message'=>__('Access Forbidden')));
            exit();
        }
        $response = [];
        $folder_name = $request->input('folder_name');
        $active_or_deactive = $request->input('active_or_deactive');
        $response['status'] = '1';

        include('config/my_config.php');
        if($active_or_deactive == 'active')
        {
            $config['current_theme'] = $folder_name;
            $response['message'] = __('Theme has been activated successfully.');
        }
        else
        {
            $config['current_theme'] = 'default';
            $response['message'] = __('Theme has been deactivated successfully.');
        }

        file_put_contents('config/my_config.php', '<?php $config = ' . var_export($config, true) . ';');

        echo json_encode($response);
    }

    public function delete_theme(Request $request)
    {
        // $this->ajax_check();
        $response = [];
        $folder_name = $request->input('folder_name');
        if($folder_name == 'default')
        {
            $response['status'] = '0';
            $response['message'] = __('You can not delete the default theme.');
            echo json_encode($response);
            exit();            
        }
        if(Auth::user()->user_type != 'Admin')
        {
            echo json_encode(array('status'=>'0','message'=>__('Access Forbidden')));
            exit();
        }

        $path = base_path("resources/views/site/".$folder_name);
        $this->delete_directory($path);
        $response['status'] = '1';
        $response['message'] = __('Theme has been deleted successfully.');

        if($folder_name == config('my_config.current_theme'))
        {
            include('config/my_config.php');
            $config['current_theme'] = 'default';
            file_put_contents('config/my_config.php', '<?php $config = ' . var_export($config, true) . ';');
        }


        echo json_encode($response);

    }
}
