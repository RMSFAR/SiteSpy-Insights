<?php

namespace App\Http\Controllers\SEO_tools;

use Spatie\Crawler\Crawler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Spatie\Sitemap\SitemapGenerator;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Services\Custom\WebCommonReportServiceInterface;

class UtilitiesController extends HomeController
{    
    public $download_id; 

    public function __construct(WebCommonReportServiceInterface $web_common_repport)
    {

        $download_id=time();
        $this->web_repport= $web_common_repport;
        $this->set_global_userdata(true,[],[],13);

    }

    public function index()
    {
      if(session('user_type') != 'Admin' && !in_array(13,$this->module_access))
          return redirect()->route('login'); 

      $data['body'] ='seo-tools.utilities.index';
      return $this->_viewcontroller($data);
    }

    public function email_encoder_decoder()
    {       
        $this->member_validity();
        $data['body'] ='seo-tools.utilities.email-encoder-decoder';
        return $this->_viewcontroller($data);
    }

    public function email_encoder_action(Request $request)
    {

        $emails=strip_tags($request->input('emails'));
        $emails=str_replace("\n", ",", $emails);
        $emails_array=explode(",", $emails);
        $total_emal=count($emails_array);
        $download_id = time();
        if (!file_exists(storage_path("app/public/download/email_encode_decode"))) {
          mkdir(storage_path("app/public/download/email_encode_decode"), 0777, true);
        }
        $email_validator_writer=fopen(storage_path("app/public/download/email_encode_decode/email_encode_decode_{$download_id}.csv"), "w");
        // make output csv file unicode compatible
        fprintf($email_validator_writer, chr(0xEF).chr(0xBB).chr(0xBF));
        // $total_valid_email=0;
        
        /*** Write header in the csv file ***/
        
        $write_validation[]="Email";       
        $write_validation[]="Encoded Email";       
        fputcsv($email_validator_writer, $write_validation);
        
        $valid_email="";

        $count=0;
        $str="<div class='card'>
                  <div class='card-header'>
                    <h4><i class='fas fa-at'></i> ".__("Email Encoder/Decoder")."</h4>
                    <div class='card-header-action'>
                      <div class='badges'>
                        <a title='".__("Download Encoded Email List")."' class='btn btn-primary float-right' href='".url('/')."/storage/download/email_encode_decode/email_encode_decode_{$download_id}.csv'> <i class='fa fa-cloud-download'></i> ".__("Download")." </a>
                      </div>                    
                    </div>
                  </div>
                  <div class='card-body p-0'>
                    <div class='table-responsive table_scroll'>
                      <table class='table table-hover table-bordered'>";
        $str.="<tbody><tr>
                  <th>#</th>
                  <th>".__("Email List")."</th>
                  <th>".__("Encoded Email")."</th>";             
        $str.="</tr>";
        
        
        foreach ($emails_array as $email) {
            $result = $this->email_encoder($email);           
            
            $write_validation=[];

            $write_validation[]=$email;
            $write_validation[]=$result;          
            fputcsv($email_validator_writer, $write_validation);
            
            $count++;
            $str.= "<tr><td>".$count."</td><td>".$email."</td>";
            $str.="<td>".$result."</td>";
            $str.="</tr></tbody>";
            $str.=" <style>
                        .table_scroll{
                            position: relative;
                            width: 500px;
                            height: 500px;
                        }
                    </style>
                    <script>
                     const ps = new PerfectScrollbar('.table_scroll',{
                                  wheelSpeed: 2,
                                  wheelPropagation: true,
                                  minScrollbarLength: 20
                                });
                   </script>";
        }
        
        /*** Write all encoded url address in text file **/
        
        /*$valid_email_file_writer = fopen("download/url_encode/url_encode_{$download_id}.txt", "w");
        fwrite($valid_email_file_writer, $valid_email);*/
        /*fclose($valid_email_file_writer);*/
        
        /**Display total encoded url***/
       
        echo $str.="</table></div></div></div>";

    }

    public function email_decoder_action(Request $request)
    {

        $emails=$request->input('emails');
            $emails=str_replace("\n", ",", $emails);
            $emails_array=explode(",", $emails);
            $total_emal=count($emails_array);
            $download_id = time();
            if (!file_exists(storage_path("app/public/download/email_encode_decode"))) {
              mkdir(storage_path("app/public/download/email_encode_decode"), 0777, true);
            }
            $email_validator_writer=fopen(storage_path("app/public/download/email_encode_decode/email_encode_decode_{$download_id}.csv"), "w");
            // make output csv file unicode compatible
            fprintf($email_validator_writer, chr(0xEF).chr(0xBB).chr(0xBF));
            // $total_valid_email=0;
            
            /*** Write header in the csv file ***/
            
            $write_validation[]="Email";       
            $write_validation[]="Decoded Email";       
            fputcsv($email_validator_writer, $write_validation);
            
            $valid_email="";

            $count=0;
            $str="<div class='card'>
                    <div class='card-header'>
                        <h4><i class='fas fa-at'></i> ".__("Email Encoder/Decoder")."</h4>
                        <div class='card-header-action'>
                        <div class='badges'>
                            <a title='".__("Download Encoded Email List")."' class='btn btn-primary float-right' href='".url('/')."/storage/download/email_encode_decode/email_encode_decode_{$download_id}.csv'> <i class='fa fa-cloud-download'></i> ".__("Download")." </a>
                        </div>                    
                        </div>
                    </div>
                    <div class='card-body p-0'>
                        <div class='table-responsive table_scroll2'>
                        <table class='table table-striped table-md'>";
                    $str.="<tbody><tr>
                                <th>#</th>
                                <th>".__("Email List")."</th>
                                <th>".__("Decoded Email")."</th>";             
                    $str.="</tr>";

            
            
            foreach ($emails_array as $email) {
                $result = $this->email_decoder($email);           
                
                $write_validation=[];

                $write_validation[]=$email;
                $write_validation[]=$result;          
                fputcsv($email_validator_writer, $write_validation);

                $count++;
                $str.= "<tr><td>".$count."</td><td>".htmlspecialchars($email)."</td>";
                $str.="<td>".$result."</td>";
            $str.="</tr></tbody>";
            $str.="<style>
                        .table_scroll2{
                            position: relative;
                            width: 500px;
                            height: 500px;
                        }
                    </style>
                    <script>
                        const ps1 = new PerfectScrollbar('.table_scroll2',{
                                    wheelSpeed: 2,
                                    wheelPropagation: true,
                                    minScrollbarLength: 20
                                });
                    </script>";
                
            }
            
            /*** Write all decoded url in text file **/
            
            /*$valid_email_file_writer = fopen("download/url_decode/url_decode_{$download_id}.txt", "w");
            fwrite($valid_email_file_writer, $valid_email);*/
            /*fclose($valid_email_file_writer);*/
            
            /**Display total decoded url***/
            
            echo $str.="</table></div></div></div>";

    }

    public function email_encoder($email)
    {
        $output = '';
        for($i=0;$i< strlen($email); $i++){
            $output .= '&#'.ord($email[$i]).';';
        }
    
        return htmlspecialchars($output);
    }
    
    public function email_decoder($email)
    {
        for($i=33;$i<127;$i++){
            $html_encoded = "&#".$i.";";
            $html_decode = chr($i);
            $email =str_replace($html_encoded,$html_decode,$email);
        }
    
        return $email;
    } 

    public function plagarism_check_action(Request $request)
    {

        $text = strip_tags($request->input('emails'));
        //$this->load->library('Tools_library');
   
        $this->plagarism_check($text); 	
   
    }


    public function plagarism_check($sample_text)
    {
        //************************************************//

        $status=$this->_check_usage($module_id=13,$req=1);

        if($status=="2") 
        {
            echo 2;
            return;
        }
        else if($status=="3") 
        {
            echo 3;
            return;
        }
        //************************************************//

        //******************************//
        // insert data to useges log table
        $this->_insert_usage_log($module_id=13,$req=1);   
        //******************************//

        $found =0;
        $not_found = 0;

        $sample_text = trim($sample_text); // trim spaces of sample text

        $page_encoding =  mb_detect_encoding($sample_text);

        if(isset($page_encoding))
        {
            $utf8_text = iconv( $page_encoding, "utf-8", $sample_text );
            $sample_text = $utf8_text;
        }

        $number_of_words = $this->mb_count_words($sample_text); // find the length of string

        // $number_of_words = count(mb_split(' ', $sample_text));


        // find the number of phrase. if number of words is less than 10 it will be considered as one and only phrase.
        if($number_of_words  >= 1 && $number_of_words < 10 ) $str = $sample_text;

        // setting a variable $x to find the number of phrases containing 10 words
        elseif($number_of_words >= 10 && $number_of_words <= 500) 
        $x = ($number_of_words - ($number_of_words % 10)) / 10;

        // explode string to array of words
        // $i for number of total phrase.  $j number of adding words. $l is the length of phrase or ngram

        // if $x is set i.e. the string contain 10 or more than 10 words we will run this segment of code

        $str1="<div class='card'>
                    <div class='card-header'>
                        <h4><i class='fas fa-language'></i> ".__("Plagiarism Checker")."</h4>
                    </div>
                    <div class='card-body p-0'>
                        <div class='table-responsive table_scroll'>
                        <table class='table table-striped table-md'>";
        $str1.="<tbody><tr>
                
                <th>".__("Text")."</th>
                <th>".__("Search Result")."</th>";             
        $str1.="</tr>";
            if(isset($x))

            {   
                // explode sample text string to an array of words
                $word = explode(" ",$sample_text);

                // $j is the second loop variable to create the phrase of 10 words to check plagarism
                // $l is the number of words in the phrase which is initialy zero.
                $j = 0;
                $l = 0; 
                $split_word=[];

                for($i=0; $i<=$x-1; $i++)
                {       
                    $ngram = '';
                    for($j = 0; $j < 10; $j++)      
                    {    
                        if(isset($word[$j+$l]))
                        $ngram = $ngram." ".$word[$j+$l];           
                    }
                    $l = $l + 10;   
                    if(isset($ngram))       
                    $split_word[] = trim($ngram);           
                }

                $split_word=array_filter($split_word);

                $size_split_word = sizeof($split_word);
                // sending phases to search engine 
                for($i=0; $i < $size_split_word; $i++)
                {
                    // only even numbers phares are send to search for reducing time
                    if( $i % 2 == 0)

                    {
                        // searching on search engine.

                        $keyword = $split_word[$i];
                        $keyword_raw=$keyword;
                        $keyword = urlencode($keyword); 

                        $url="www.google.com/search?q={$keyword}";  
                        $ch = curl_init(); // initialize curl handle
                        curl_setopt($ch, CURLOPT_HEADER, 0);
                        curl_setopt($ch, CURLOPT_VERBOSE, 0);
                        //curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible;)");
                        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.3) Gecko/20070309 Firefox/2.0.0.3");  
                        curl_setopt($ch, CURLOPT_AUTOREFERER, false);
                        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,7);
                        curl_setopt($ch, CURLOPT_REFERER, 'http://'.$url);
                        curl_setopt($ch, CURLOPT_URL,$url); // set url to post to
                        curl_setopt($ch, CURLOPT_FAILONERROR, 1);
                        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);// allow redirects
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); // return into a variable
                        curl_setopt($ch, CURLOPT_TIMEOUT, 50); // times out after 50s
                        curl_setopt($ch, CURLOPT_POST, 0); // set POST method

                        /***** Proxy set for google . if lot of request gone, google will stop reponding. That's why it's should use some proxy *****/

                        /**** Using proxy of public and private proxy both ****/
                        if($this->web_repport->proxy_ip!='')
                        curl_setopt($ch, CURLOPT_PROXY, $this->web_repport->proxy_ip);

                        if($this->web_repport->proxy_auth_pass!='')   
                        curl_setopt($ch, CURLOPT_PROXYUSERPWD, $this->web_repport->proxy_auth_pass);



                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                        curl_setopt($ch, CURLOPT_COOKIEJAR, "my_cookies.txt");  
                        curl_setopt($ch, CURLOPT_COOKIEFILE, "my_cookies.txt"); 

                        $content = curl_exec($ch); // run the whole process
                        $content = preg_replace('#<input.*?>#si',"",$content);
                        $content = preg_replace('#<title>.*?</title>#si',"",$content);
                        $content = preg_replace('#<img.*?>#si',"",$content);
                        $content = preg_replace('#<script.*?>.*?</script>#si',"",$content);

                        /*$content= preg_replace('#<a.*?>.*?</a>#si',"",$content);*/

                        $find_not_found_word='#<div style="font-size:16px;padding-left:10px">.*?<b>'.$keyword_raw.'</b>.*?<ul.*?class="COi8F">#si';
                        $find_not_found_word = str_replace('(', '', $find_not_found_word);
                        $find_not_found_word = str_replace(')', '', $find_not_found_word);


                            $content= preg_replace($find_not_found_word,"",$content);

                            $find_not_found_word='#<h3 class="r">.*?<b>'.$keyword_raw.'</b>.*?</h3>#si';
                            $find_not_found_word = str_replace('(', '', $find_not_found_word);
                            $find_not_found_word = str_replace(')', '', $find_not_found_word);
                            $content= preg_replace($find_not_found_word,"",$content);

                            $content= preg_replace('#Your search(.*?)did not match any documents.#si',"",$content);
                            $content= preg_replace('#Did you mean:#si',"",$content);
                            $content=str_replace("<b>","",$content);
                            $content=str_replace("</b>","",$content);
                            $content=str_replace("<strong>","",$content);
                            $content=str_replace("</strong>","",$content);

                            // echo string position if found
                            $str_pos = mb_stripos($content, $keyword_raw);              

                            // if string found print match found or print mantch not found  
                            if($str_pos!==FALSE)
                            {    
                                $url = 'https://www.google.com/search?q="'.$split_word[$i].'"';               
                                $str1.= "<tr><td>{$split_word[$i]}</td><td><a href='".$url."' target = '_blank'><span class='badge badge-primary'>".__('Already Exist')."</sapn></a></td></tr></tbody>";
                                    $found++; 

                            }

                                else
                                {                       
                                    $str1.= "<tr><td>{$split_word[$i]}</td><td><span class='badge badge-secondary'>".__('Not Exist')."</sapn></td></tr></tbody>";
                                        $not_found++;
                                    }   

                                }
                            }
                        $str1.= "</table></div></div></div>"; 

                        $total = $found + $not_found; 
                        $unique_result = ($found/$total)*100;

                        $str1.= "_sep_".$unique_result;

                        //******************************//
                        // insert data to useges log table
                        $this->_insert_usage_log($module_id=13,$req=1);   
                        //******************************//

                        echo $str1;       
                    }
                    // if string contain less than 10 words consider it as single and send it to search engine
                    elseif(isset($str))
                    {
                        $keyword = $str;
                        $keyword_raw=$keyword;
                        $keyword = urlencode($keyword); 

                        $url="www.google.com/search?q={$keyword}";  
                        $ch = curl_init(); // initialize curl handle
                        curl_setopt($ch, CURLOPT_HEADER, 0);
                        curl_setopt($ch, CURLOPT_VERBOSE, 0);
                        //curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible;)");
                        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.3) Gecko/20070309 Firefox/2.0.0.3");  
                        curl_setopt($ch, CURLOPT_AUTOREFERER, false);
                        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,7);
                        curl_setopt($ch, CURLOPT_REFERER, 'http://'.$url);
                        curl_setopt($ch, CURLOPT_URL,$url); // set url to post to
                        curl_setopt($ch, CURLOPT_FAILONERROR, 1);
                        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);// allow redirects
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); // return into a variable
                        curl_setopt($ch, CURLOPT_TIMEOUT, 50); // times out after 50s
                        curl_setopt($ch, CURLOPT_POST, 0); // set POST method

                        /***** Proxy set for google . if lot of request gone, google will stop reponding. That's why it's should use some proxy *****/

                        if($this->web_repport->proxy_ip!='')
                        curl_setopt($ch, CURLOPT_PROXY, $this->web_repport->proxy_ip);

                        if($this->web_repport->proxy_auth_pass!='')   
                        curl_setopt($ch, CURLOPT_PROXYUSERPWD, $this->web_repport->proxy_auth_pass);



                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                        curl_setopt($ch, CURLOPT_COOKIEJAR, "my_cookies.txt");  
                        curl_setopt($ch, CURLOPT_COOKIEFILE, "my_cookies.txt"); 

                        $content = curl_exec($ch); // run the whole process
                        $content = preg_replace('#<input.*?>#si',"",$content);
                        $content = preg_replace('#<title>.*?</title>#si',"",$content);
                        $content = preg_replace('#<img.*?>#si',"",$content);
                        $content = preg_replace('#<script.*?>.*?</script>#si',"",$content);
                        /*$content= preg_replace('#<a.*?>.*?</a>#si',"",$content);*/
                        $content= preg_replace('#Your search(.*?)did not match any documents.#si',"",$content);
                        $content= preg_replace('#<p>Searches related to.*?</p>#si',"",$content);

                        $find_not_found_word='#<div style="font-size:16px;padding-left:10px">.*?<b>'.$keyword_raw.'</b>.*?<ul.*?class="COi8F">#si';
                        $content= preg_replace($find_not_found_word,"",$content);

                        $find_not_found_word='#<h3 class="r">.*?<b>'.$keyword_raw.'</b>.*?</h3>#si';
                        $content= preg_replace($find_not_found_word,"",$content);

                        // echo string position if found
                        $str_pos=mb_stripos($content, $keyword_raw);



                        // if string found print match found or print mantch not found  
                        if($str_pos!==FALSE)
                        {    
                            $url = 'https://www.google.com/search?q="'.$str.'"';
                            $found++;
                            $str1.= "<tr><td>{$str}</td><td><a href='".$url."' target = '_blank'><span class='badge badge-primary'>".__('Already Exist')."</sapn></a></td></tr></tbody>";
                            }

                            else
                            {    

                                $str1.= "<tr><td>{$str}</td><td><span class='badge badge-secondary'>".__('Not Exist')."</sapn></td></tr></tbody>";
                                    $not_found++;
                                }

                            $str1.= "</table></div></div></div>"; 

                            $total = $found + $not_found; 
                            $unique_result = ($found/$total)*100;

                            $str1.= "_sep_".$unique_result;

                            echo $str1;   


                        }

                        // if string is more than 500 words show this message
                        elseif($number_of_words > 500) echo "size_error_sep_0";

                        // if string is blank show this message
                        else echo "blank_error_sep_0";


    }

    public function email_validator(Request $request)
    {
        $emails=strip_tags($request->input('emails'));
        $emails=str_replace("\n", ",", $emails);
        $emails_array=explode(",", $emails);
        $total_emal=count($emails_array);
        $download_id = time();
        if (!file_exists(storage_path("app/public/download/email_validator"))) {
          mkdir(storage_path("app/public/download/email_validator"), 0777, true);
        }
        $email_validator_writer=fopen(storage_path("app/public/download/email_validator/email_validator_{$download_id}.csv"), "w");
        // make output csv file unicode compatible
        fprintf($email_validator_writer, chr(0xEF).chr(0xBB).chr(0xBF));
        $total_valid_email=0;
        
        /*** Write header in the csv file ***/
        
        $write_validation[]="Email";
        $write_validation[]="Is Valid Pattern";
        $write_validation[]="Is MX Record Exist";
        fputcsv($email_validator_writer, $write_validation);
        
        $valid_email="";
        
        
        foreach ($emails_array as $email) {
            $result = $this->web_repport->email_validate($email);
            $is_valid  = ($result['is_valid']) ? 'Yes':'No';
            $is_exists = ($result['is_exists']) ? "Yes" : "No";
            
            $write_validation=[];
            $write_validation[]=$email;
            $write_validation[]=$is_valid;
            $write_validation[]=$is_exists;
            fputcsv($email_validator_writer, $write_validation);
            
            /**if two validation passed then 1+1= 2**/
            if ($result['is_valid']+$result['is_exists'] == 2) {
                $valid_email.=$email."\n";
                $total_valid_email++;
            }
        }
        
        /*** Write all valid email address in text file **/
        if (!file_exists(storage_path("app/public/download/email_validator"))) {
          mkdir(storage_path("app/public/download/email_validator"), 0777, true);
        }
        $valid_email_file_writer=fopen(storage_path("app/public/download/email_validator/email_validator_{$download_id}.csv"), "w");
        fwrite($valid_email_file_writer, $valid_email);
        fclose($valid_email_file_writer);
        
        /**Display total valid email between total email***/

      echo  "<div class='card'>
              <div class='card-header'>
                <h4><i class='fas fa-envelope'></i> ".__("Valid Email Check Results")."</h4>
              </div>
              <div class='card-body text-center'>
                <p class='text-muted'>Total {$total_valid_email} valid email found of {$total_emal}</p>
                <div class='buttons'>
                  <a  class='btn btn-primary' href='".url('/')."/storage/download/email_validator/email_validator_{$download_id}.csv'> <i class='fa fa-cloud-download'></i> ".__("Download Csv")." </a>
                  <a  class='btn btn-warning' href='".url('/')."/storage/download/email_validator/email_validator_{$download_id}.txt'> <i class='fa fa-cloud-download'></i> ".__("Download Txt")." </a>

                </div>
              </div>
        </div>";
        
        
    }

    public function email_unique_maker(Request $request)
    {
        $download_id = time();
        $emails=strip_tags($request->input('emails'));
        $emails=str_replace("\n", ",", $emails);
        $emails_array=explode(",", $emails);
        
        $total_email=count($emails_array);
        
        $emails_array=array_unique($emails_array);
        
        $total_unique_email=count($emails_array);
        
        $unique_email_str=implode("\n", $emails_array);
        
        /*** Write all Unique email address in text file **/
        if (!file_exists(storage_path("app/public/download/unique_email_"))) {
          mkdir(storage_path("app/public/download/unique_email_"), 0777, true);
        }
        $unique_email_file_writer = fopen(storage_path("app/public/download/unique_email/unique_email_{$download_id}.txt"), "w");
        fwrite($unique_email_file_writer, $unique_email_str);
        fclose($unique_email_file_writer);
        

        echo "<div class='card'>
                      <div class='card-header'>
                        <h4><i class='fas fa-envelope-square'></i> ".__("Duplicate Email Filter Results")."</h4>
                      </div>
                      <div class='card-body text-center'>
                        <p class='text-muted'>Total {$total_unique_email} valid email found of {$total_email}</p>
                        <div class='buttons'>
                          <a  class='btn btn-primary' href='".url('/')."/storage/download/unique_email/unique_email_{$download_id}.txt'> <i class='fa fa-cloud-download'></i> ".__("Download")." </a>

                        </div>
                      </div>
                </div>";
    }

    public function url_encode_action(Request $request)
    {
        $download_id = time();
        // $this->load->library('web_common_report');
        $emails=strip_tags($request->input('emails'));
        $emails=str_replace("\n", ",", $emails);
        $emails_array=explode(",", $emails);
        $total_emal=count($emails_array);
        $download_id = time();
        if (!file_exists(storage_path("app/public/download/url_encode"))) {
          mkdir(storage_path("app/public/download/url_encode"), 0777, true);
        }
        $email_validator_writer=fopen(storage_path("app/public/download/url_encode/url_encode_{$download_id}.csv"), "w");
        // make output csv file unicode compatible
        fprintf($email_validator_writer, chr(0xEF).chr(0xBB).chr(0xBF));
        // $total_valid_email=0;
        
        /*** Write header in the csv file ***/
        
        $write_validation[]="URL";       
        $write_validation[]="Encoded URL";       
        fputcsv($email_validator_writer, $write_validation);
        
        $valid_email="";


        $count=0;
        $str="<div class='card'>
                  <div class='card-header'>
                    <h4><i class='fas fa-link'></i> ".__("URL Encoder/Decoder Results")."</h4>
                    <div class='card-header-action'>
                      <div class='badges'>
                        <a  class='btn btn-primary float-right' href='".url('/')."/public/storage/storage/download/url_encode/url_encode_{$download_id}.csv'> <i class='fa fa-cloud-download'></i> ".__("Download")." </a>
                      </div>                    
                    </div>
                  </div>
                  <div class='card-body p-0'>
                    <div class='table-responsive table_scroll'>
                      <table class='table table-hover table-bordered'>";
                  $str.="<tbody><tr>
                            <th>#</th>
                            <th>".__("URL List")."</th>
                            <th>".__("Encoded URL")."</th>";             
                  $str.="</tr>";
        
        
        foreach ($emails_array as $email) {
            $result = rawurlencode($email);           
            
            $write_validation=[];

            $write_validation[]=$email;
            $write_validation[]=$result;          
            fputcsv($email_validator_writer, $write_validation);
            
            $count++;

            $str.= "<tr><td>".$count."</td><td>".$email."</td>";
            $str.="<td>".$result."</td>";
            $str.="</tr></tbody>";
            $str.=" <style>
                        .table_scroll{
                            position: relative;
                            width: 500px;
                            height: 500px;
                        }
                    </style>
                    <script>
                     const ps = new PerfectScrollbar('.table_scroll',{
                                  wheelSpeed: 2,
                                  wheelPropagation: true,
                                  minScrollbarLength: 20
                                });
                   </script>";
        }
        
        /*** Write all encoded url address in text file **/
        if (!file_exists(storage_path("app/public/download/url_encode"))) {
          mkdir(storage_path("app/public/download/url_encode"), 0777, true);
        }
        $valid_email_file_writer=fopen(storage_path("app/public/download/url_encode/url_encode_{$download_id}.csv"), "w");
        fwrite($valid_email_file_writer, $valid_email);
        fclose($valid_email_file_writer);
        
        /**Display total encoded url***/
        
        echo $str.="</table></div></div></div>";

    }

    public function url_decode_action(Request $request)
    {

        // $this->load->library('web_common_report');
        $emails=strip_tags($request->input('emails'));
        $emails=str_replace("\n", ",", $emails);
        $emails_array=explode(",", $emails);
        $total_emal=count($emails_array);
        $download_id = time();
        if (!file_exists(storage_path("app/public/download/url_decode"))) {
          mkdir(storage_path("app/public/download/url_decode"), 0777, true);
        }
        $email_validator_writer=fopen(storage_path("app/public/download/url_decode/url_decode_{$download_id}.csv"), "w");
        // make output csv file unicode compatible
        fprintf($email_validator_writer, chr(0xEF).chr(0xBB).chr(0xBF));
        // $total_valid_email=0;
        
        /*** Write header in the csv file ***/
        
        $write_validation[]="URL";       
        $write_validation[]="Decoded URL";       
        fputcsv($email_validator_writer, $write_validation);
        
        $valid_email="";

        $count=0;

        $str="<div class='card'>
                  <div class='card-header'>
                    <h4><i class='fas fa-link'></i> ".__("URL Encoder/Decoder")."</h4>
                    <div class='card-header-action'>
                      <div class='badges'>
                        <a  class='btn btn-primary float-right' href='".url('/')."/storage/download/url_decode/url_decode_{$download_id}.csv'> <i class='fa fa-cloud-download'></i> ".__("Download")." </a>
                      </div>                    
                    </div>
                  </div>
                  <div class='card-body p-0'>
                    <div class='table-responsive table_scroll2'>
                      <table class='table table-striped table-md'>";
                  $str.="<tbody><tr>
                            <th>#</th>
                            <th>".__("URL List")."</th>
                            <th>".__("Decoded URL")."</th>";             
                  $str.="</tr>";
        
        
        foreach ($emails_array as $email) {
            $result = urldecode($email);           
            
            $write_validation=[];

            $write_validation[]=$email;
            $write_validation[]=$result;          
            fputcsv($email_validator_writer, $write_validation);

            $count++;

            $str.= "<tr><td>".$count."</td><td>".$email."</td>";
            $str.="<td>".$result."</td>";
            $str.="</tr></tbody>";
            $str.=" <style>
                        .table_scroll2{
                            position: relative;
                            width: 500px;
                            height: 500px;
                        }
                    </style>
                    <script>
                     const ps2 = new PerfectScrollbar('.table_scroll2',{
                                  wheelSpeed: 2,
                                  wheelPropagation: true,
                                  minScrollbarLength: 20
                                });
                   </script>";
            
        }
        
        /*** Write all decoded url in text file **/
        if (!file_exists(storage_path("app/public/download/url_decode"))) {
          mkdir(storage_path("app/public/download/url_decode"), 0777, true);
        }
        $valid_email_file_writer=fopen(storage_path("app/public/download/url_decode/url_decode_{$download_id}.csv"), "w");
        fwrite($valid_email_file_writer, $valid_email);
        fclose($valid_email_file_writer);
        
        /**Display total decoded url***/
        
        echo $str.="</table></div></div></div>";

    }


    public function url_canonical_action(Request $request) 
    {

        if ($request->isMethod('get')) {
            return redirect()->route('access_forbidden');
        }    

        $url_lists = [];
        $url_values = explode(',',strip_tags($request->input('emails')));

        if(count($url_values) <= 50) :
            foreach($url_values as $url_value) :
                $url_value = trim($url_value);
                if(is_valid_url($url_value) === TRUE) :
                    $check_data = $this->web_repport->url_canonical_check($url_value);
                    $url_lists[] = ['url' => $url_value, 'url_canonical' => $check_data];
                endif;
            endforeach;
        endif;    

        $count=0;

        $str="<div class='card'>
                  <div class='card-header'>
                    <h4><i class='fas fa-external-link-square-alt'></i> ".__("URL Canonical Check Results")."</h4>
                  </div>
                  <div class='card-body p-0'>
                    <div class='table-responsive table_scroll2'>
                      <table class='table table-hover table-bordered'>";
        $str.="<tbody><tr>
                <th>#</th>
                <th>".__("URL")."</th>
                <th>".__("Canonical")."</th>";             
        $str.="</tr>";

        foreach ($url_lists as $key => $value) {
           
           $count++;

           if ($value['url_canonical'] == '1') 
                $status = 'Yes';
            else
                $status = 'No';

    
           $str.= "<tr><td>".$count."</td><td>".$value['url']."</td>";
           $str.="<td>".$status."</td>";
           $str.="</tr></tbody>";
           $str.=" <style>
                       .table_scroll2{
                           position: relative;
                           width: 500px;
                           height: 500px;
                       }
                   </style>
                   <script>
                    const ps2 = new PerfectScrollbar('.table_scroll2',{
                                 wheelSpeed: 2,
                                 wheelPropagation: true,
                                 minScrollbarLength: 20
                               });
                  </script>";
        }
        echo $str.="</table></div></div></div>";

    }

    public function gzip_check_action(Request $request) 
    {

        if ($request->isMethod('get')) {
            return redirect()->route('access_forbidden');
        }      

        
        $url_lists = [];
        $url_values = explode(',',strip_tags($request->input('emails')));

        if(count($url_values) <= 50) :
            foreach($url_values as $url_value) :
                $url_value = trim($url_value);
                if(is_valid_url($url_value) === TRUE) :
                    $check_data = $this->web_repport->gzip_compression_check($url_value);
                    $url_lists[] = ['url' => $url_value, 'gzip_enable' => $check_data['is_gzip_enable'], 'gzip_page_size' => $check_data['gzip_page_size'], 'normal_page_size' => $check_data['normal_page_size']];
                endif;
            endforeach;
        endif;    

        

        $str="<div class='card'>
                  <div class='card-header'>
                    <h4><i class='fas fa-external-link-square-alt'></i> ".__("Gzip Check")."</h4>
                  </div>
                  <div class='card-body p-0'>
                    <div class='table-responsive table_scroll2'>
                      <table class='table table-hover table-bordered'>";
        $str.="<tbody><tr>
                <th>".__('URL')."</th>
                <th>".__('Gzip Enable')."</th>
                <th>".__('Gzip Page Size')."</th>
                <th>".__('Normal Page Size')."</th>";
   

        $str.="</tr>";

        foreach ($url_lists as $key => $value) {

           $str.= "<tr><td>".$value['url']."</td><td>".$value['gzip_enable']."</td>";
           $str.="<td>".$value['gzip_page_size']."</td>";
           $str.="<td>".$value['normal_page_size']."</td>";
           $str.="</tr></tbody>";
           $str.=" <style>
                       .table_scroll2{
                           position: relative;
                           width: 500px;
                           height: 500px;
                       }
                   </style>
                   <script>
                    const ps3 = new PerfectScrollbar('.table_scroll2',{
                                 wheelSpeed: 2,
                                 wheelPropagation: true,
                                 minScrollbarLength: 20
                               });
                  </script>";
        }
      
        echo $str.="</table></div></div></div>";
    }

    public function base64_encode_action(Request $request) 
    {

        $base64=strip_tags($request->input('base64'));

        $output = $this->web_repport->base_64_encode($base64);
        
         $str =  "<div class='card card-light'>
              <div class='card-header'>
                <h4> <i class='fab fa-centercode'></i> ".__("Base64 Encoder/Decoder Results")."</h4>
              </div>
              <div class='card-body text-center table_scroll'>
                <p id='copyTarget'>".$output."</p>
                 <button id='copyButton' type='button' data-clipboard-action='copy' data-clipboard-target='#copyTarget' class='btn btn-primary'><i class='fas fa-copy'></i> ".__("Copy")."</button>
              </div>
            </div>";
        $str .= "<style>
                       .table_scroll{
                           position: relative;
                           width: 600px;
                           height: 500px;
                       }
                </style>";

        $str .= "<script>
                   var clipboard = new Clipboard('.btn');

                    clipboard.on('success', function(e) {
                        alert('Copied');
                    });

                    clipboard.on('error', function(e) {
                        alert('Not Copied!');
                    });
                    const ps3 = new PerfectScrollbar('.table_scroll',{
                         wheelSpeed: 2,
                         wheelPropagation: true,
                         minScrollbarLength: 20
                       });
                </script>";
        echo $str;


    }

    public function base64_decode_action(Request $request) 
    {

        
        $base64=strip_tags($request->input('base64'));

        $output =  $this->web_repport->base_64_decode($base64);

         $str =  "<div class='card card-light'>
              <div class='card-header'>
                <h4> <i class='fab fa-centercode'></i> ".__("Base64 Encoder/Decoder Results")."</h4>
              </div>
              <div class='card-body text-center table_scroll2'>
                <p id='copyTarget1'>".$output."</p>
                 <button id='copyButton' type='button' data-clipboard-action='copy' data-clipboard-target='#copyTarget1' class='btn btn-primary'><i class='fas fa-copy'></i> ".__("Copy")."</button>
              </div>
            </div>";
        $str .= "<style>
                       .table_scroll2{
                           position: relative;
                           width: 600px;
                           height: 500px;
                       }
                </style>";

        $str .= "<script>
                   var clipboard = new Clipboard('.btn');

                    clipboard.on('success', function(e) {
                        alert('Copied');
                    });

                    clipboard.on('error', function(e) {
                        alert('Not Copied!');
                    });
                    const ps = new PerfectScrollbar('.table_scroll2',{
                         wheelSpeed: 2,
                         wheelPropagation: true,
                         minScrollbarLength: 20
                       });
                </script>";
        echo $str;

    }  

    public function robot_code_generator_action(Request $request)
    {           
        $download_id = time();
        $all_robot = $request->input('all_robot');
        $custom_robot = $request->input('custom_robot');

        $basic_all_robots = $request->input('basic_all_robots');
        $crawl_delay = $request->input('crawl_delay');
        $site_map = $request->input('site_map');
        $custom_crawl_delay = $request->input('custom_crawl_delay');
        $custom_site_map = $request->input('custom_site_map');
        $google = $request->input('google');
        $msn_search = $request->input('msn_search');
        $yahoo = $request->input('yahoo');
        $ask_teoma = $request->input('ask_teoma');
        $cuil = $request->input('cuil');
        $gigablast = $request->input('gigablast');
        $scrub = $request->input('scrub');
        $dmoz_checker = $request->input('dmoz_checker');
        $nutch = $request->input('nutch');
        $alexa_wayback = $request->input('alexa_wayback');
        $baidu = $request->input('baidu');
        $never = $request->input('never');


        $google_image = $request->input('google_image');
        $google_mobile = $request->input('google_mobile');
        $yahoo_mm = $request->input('yahoo_mm');
        $msn_picsearch = $request->input('msn_picsearch');
        $SingingFish = $request->input('SingingFish');
        $yahoo_blogs = $request->input('yahoo_blogs');

        $restricted_dir = $request->input('restricted_dir');

        $restricted_dir = rtrim($restricted_dir,',');
        $directories = explode(',', $restricted_dir);

        if($all_robot == 1)
        {
            if($basic_all_robots == 'allowed')
            {

                $user_agent = 'User-agent: *'.PHP_EOL;
                $disallow = 'Disallow:'.PHP_EOL;
                
                if (!file_exists(storage_path("app/public/download/robot"))) {
                  mkdir(storage_path("app/public/download/robot"), 0777, true);
                }
                $handle_write=fopen(storage_path("app/public/download/robot/robot_{$download_id}.txt"), "w");

                $write_var = fwrite($handle_write, $user_agent);
                $write_var = fwrite($handle_write, $disallow);

                if(isset($crawl_delay))
                {
                    $crawl_delay = 'Crawl-delay: '.$crawl_delay.PHP_EOL;
                    $write_var = fwrite($handle_write, $crawl_delay);
                }

                if(isset($site_map))
                {
                    $site_map = 'Sitemap: '.$site_map.PHP_EOL;
                    $write_var = fwrite($handle_write, $site_map);
                }   

                if(isset($write_var)) echo __('your file is ready to download');
            }

            else
            {

                $user_agent = 'User-agent: *'.PHP_EOL;
                $disallow = 'Disallow: /'.PHP_EOL;
                if (!file_exists(storage_path("app/public/download/robot"))) {
                  mkdir(storage_path("app/public/download/robot"), 0777, true);
                }
                $handle_write=fopen(storage_path("app/public/download/robot/robot_{$download_id}.txt"), "w");

                $write_var = fwrite($handle_write, $user_agent);
        $write_var = fwrite($handle_write, $disallow);

        if(isset($crawl_delay))
        {
            $crawl_delay = 'Crawl-delay: '.$crawl_delay.PHP_EOL;
            $write_var = fwrite($handle_write, $crawl_delay);
        }

        if(isset($site_map))
        {
            $site_map = 'Sitemap: '.$site_map.PHP_EOL;
            $write_var = fwrite($handle_write, $site_map);
        }   
                if(isset($write_var)) echo __('your file is ready to download');
            }             



        }//End of if (all_robot) *******************************************

        if($custom_robot == 1)
        {
           $bots = [];
           $bots_disallowed = [];

            /*
                    if($google == 'allowed') $bots[] = 'googlebot';
                    else
                        $bots_disallowed[] = 'googlebot';

                    if($google == 'allowed') $bots[] = 'googlebot';
                    else
                        $bots_disallowed[] = 'googlebot';*/

        if($google == 'allowed') $bots[] = 'googlebot';
           else
            $bots_disallowed[] = 'googlebot';

        if($msn_search == 'allowed') $bots[] = 'msnbot';
        else
            $bots_disallowed[] = 'msnbot';

        if($yahoo == 'allowed') $bots[] = 'yahoo-slurp';
        else
            $bots_disallowed[] = 'yahoo-slurp';

        if($ask_teoma == 'allowed') $bots[] = 'teoma';
        else
            $bots_disallowed[] = 'teoma';

        if($cuil == 'allowed') $bots[] = 'twiceler';
        else
            $bots_disallowed[] = 'twiceler';

        if($gigablast == 'allowed') $bots[] = 'gigabot';
        else
            $bots_disallowed[] = 'gigabot';

        if($scrub == 'allowed') $bots[] = 'scrubby';
        else
            $bots_disallowed[] = 'scrubby';

        if($dmoz_checker == 'allowed') $bots[] = 'robozilla';
        else
            $bots_disallowed[] = 'robozilla';

        if($nutch == 'allowed') $bots[] = 'nutch';
        else
            $bots_disallowed[] = 'nutch';

        if($alexa_wayback == 'allowed') $bots[] = 'ia_archiver';
        else
            $bots_disallowed[] = 'ia_archiver';

        if($baidu == 'allowed') $bots[] = 'baiduspider';
        else
            $bots_disallowed[] = 'baiduspider';

        if($never == 'allowed') $bots[] = 'naverbot';
        else
            $bots_disallowed[] = 'naverbot';


        if($google_image == 'allowed') $bots[] = 'googlebot-image';
        else
            $bots_disallowed[] = 'googlebot-image';

        if($google_mobile == 'allowed') $bots[] = 'googlebot-mobile';
        else
            $bots_disallowed[] = 'googlebot-mobile';

        if($yahoo_mm == 'allowed') $bots[] = 'yahoo-mmcrawler';
        else
            $bots_disallowed[] = 'yahoo-mmcrawler';

        if($msn_picsearch == 'allowed') $bots[] = 'psbot';
        else
            $bots_disallowed[] = 'psbot';

        if($SingingFish == 'allowed') $bots[] = 'asterias';
        else
            $bots_disallowed[] = 'asterias';

        if($yahoo_blogs == 'allowed') $bots[] = 'yahoo-blogs/v3.9';
        else
            $bots_disallowed[] = 'yahoo-blogs/v3.9';

        if (!file_exists(storage_path("app/public/download/robot"))) {
          mkdir(storage_path("app/public/download/robot"), 0777, true);
        }
        $handle_write=fopen(storage_path("app/public/download/robot/robot_{$download_id}.txt"), "w");


        if(!empty($bots) || !empty($bots_disallowed) || !empty($directories) || isset($custom_crawl_delay) || isset($custom_site_map))       
        {   

            if (!file_exists(storage_path("app/public/download/robot"))) {
              mkdir(storage_path("app/public/download/robot"), 0777, true);
            }
            $handle_write = fopen(storage_path("app/public/download/robot/robot_{$download_id}.txt"), "w");
            
            if( !empty($bots) )
            {
                for($i=0; $i < count($bots); $i++)
                {
                    fwrite($handle_write, "User-agent: ".$bots[$i].PHP_EOL);
                    fwrite($handle_write, "Disallow: ".PHP_EOL);                
                }           
            }

            if( !empty($bots_disallowed) )
            {
                for($j=0; $j < count($bots_disallowed); $j++)
                {
                    fwrite($handle_write, "User-agent: ".$bots_disallowed[$j].PHP_EOL);
                    fwrite($handle_write, "Disallow: /".PHP_EOL);               
                }
            }
            

            fwrite($handle_write, "User-agent: *".PHP_EOL);
            fwrite($handle_write, "Disallow: ".PHP_EOL);
        // if(empty($directories)) fwrite($handle_write, "Disallow: ".PHP_EOL);


            if(!empty($directories))
            {                   
                for($k=0; $k < count($directories); $k++)
                {           
                    fwrite($handle_write, "Disallow: ".$directories[$k].PHP_EOL);                       
                }                                       
            }

            if(isset($custom_crawl_delay))
                fwrite($handle_write, "Crawl-delay: ".$custom_crawl_delay.PHP_EOL);
            if(isset($custom_site_map))
                fwrite($handle_write, "Sitemap: ".$custom_site_map.PHP_EOL);

            fclose($handle_write);      

             echo __('your file is ready to download');            


        }           


        }

    }

    public function meta_tag_action(Request $request)
    {
        $download_id= date("Ymd");
        $is_google = $request->input('is_google');
        $is_facebook = $request->input('is_facebook');
        $is_twiter = $request->input('is_twiter');
     
         $google_description = strip_tags($request->input('google_description'));       
         $google_keywords = strip_tags($request->input('google_keywords'));       
         $google_copyright = strip_tags($request->input('google_copyright'));       
         $google_author = strip_tags($request->input('google_author'));       
         $google_application_name = strip_tags($request->input('google_application_name'));
                
     
         $facebook_title = strip_tags($request->input('facebook_title'));
         $facebook_type = strip_tags($request->input('facebook_type'));
         $facebook_image = strip_tags($request->input('facebook_image'));
         $facebook_url = strip_tags($request->input('facebook_url'));
         $facebook_description = strip_tags($request->input('facebook_description'));
         $facebook_app_id = strip_tags($request->input('facebook_app_id'));
         $facebook_localization = strip_tags($request->input('facebook_localization'));
     
         $twiter_card = strip_tags($request->input('twiter_card'));
         $twiter_title = strip_tags($request->input('twiter_title'));
         $twiter_description = strip_tags($request->input('twiter_description'));
         $twiter_image = strip_tags($request->input('twiter_image'));
     
         if (!file_exists(storage_path("app/public/download/metatag"))) {
          mkdir(storage_path("app/public/download/metatag"), 0777, true);
        }
         $handle_write = fopen(storage_path("app/public/download/metatag/metatag_{$download_id}.txt"), "w");
     
         if($is_google == 1){    
     
             fwrite($handle_write,'<meta name="description" content="'.$google_description.'" />'.PHP_EOL);
             fwrite($handle_write,'<meta name="keywords" content="'.$google_keywords.'" />'.PHP_EOL);
             fwrite($handle_write,'<meta name="author" content="'.$google_copyright.'" />'.PHP_EOL);
             fwrite($handle_write,'<meta name="copyright" content="'.$google_author.'" />'.PHP_EOL);
             fwrite($handle_write,'<meta name="application-name" content="'.$google_application_name.'" />'.PHP_EOL);
             
         } 
     
         if($is_facebook == 1){
     
             fwrite($handle_write, '<meta property="og:title" content="'.$facebook_title.'" />'.PHP_EOL);
             fwrite($handle_write, '<meta property="og:type" content="'.$facebook_type.'" />'.PHP_EOL);
             fwrite($handle_write, '<meta property="og:image" content="'.$facebook_image.'" />'.PHP_EOL);
             fwrite($handle_write, '<meta property="og:url" content="'.$facebook_url.'" />'.PHP_EOL);
             fwrite($handle_write, '<meta property="og:description" content="'.$facebook_description.'" />'.PHP_EOL);
             fwrite($handle_write, '<meta property="fb:app_id" content="'.$facebook_app_id.'" />'.PHP_EOL);
             fwrite($handle_write, '<meta property="og:locale" content="'.$facebook_localization.'" />'.PHP_EOL);
         }
     
     
         if($is_twiter == 1){
     
            fwrite($handle_write, '<meta name="twitter:card" content="'.$twiter_card.'" />'.PHP_EOL);
            fwrite($handle_write, '<meta name="twitter:title" content="'.$twiter_title.'" />'.PHP_EOL);
            fwrite($handle_write, '<meta name="twitter:description" content="'.$twiter_description.'" />'.PHP_EOL);
            fwrite($handle_write, '<meta name="twitter:image" content="'.$twiter_image.'" />'.PHP_EOL);
         }
     
         echo __('your file is ready to download');
       
    }

    public function meta_tag_list()
    {
      if(Auth::user()->user_type != 'Admin' && !in_array(13,$this->module_access))
          return redirect()->route('login'); 
      $this->member_validity();
      $data['body'] ='seo-tools.utilities.metatag-generator';
      return $this->_viewcontroller($data);

    }
    public function plagarism_check_list()
    {
      if(Auth::user()->user_type != 'Admin' && !in_array(13,$this->module_access))
          return redirect()->route('login');
      $this->member_validity();
      $data['body'] ='seo-tools.utilities.plagiarism-check';
      return $this->_viewcontroller($data);

    }
    public function valid_email_check()
    {
        if(Auth::user()->user_type != 'Admin' && !in_array(13,$this->module_access))
            return redirect()->route('login');
        $this->member_validity();

        $data['body'] ='seo-tools.utilities.valid-email-check';
        return $this->_viewcontroller($data);

    }
    public function duplicate_email_filter_list()
    {
      if(Auth::user()->user_type != 'Admin' && !in_array(13,$this->module_access))
        return redirect()->route('login');
      $this->member_validity();

      $data['body'] ='seo-tools.utilities.duplicate-email-filter';
      return $this->_viewcontroller($data);
    }
    public function url_encode_list()
    {
      if(Auth::user()->user_type != 'Admin' && !in_array(13,$this->module_access))
        return redirect()->route('login');
      $this->member_validity();

      $data['body'] ='seo-tools.utilities.url-encoder-decoder';
      return $this->_viewcontroller($data);
    }
    public function url_canonical_check()
    {
        $this->member_validity();
        $data['body'] ='seo-tools.utilities.url-canonical-check';
        return $this->_viewcontroller($data);
    }
    public function gzip_check()
    {
        $this->member_validity();
        $data['body'] ='seo-tools.utilities.gzip-check';
        return $this->_viewcontroller($data);
    }
    public function base64_encode_list()
    {
        $this->member_validity();
        $data['body'] ='seo-tools.utilities.base64-encoder-decoder';
        return $this->_viewcontroller($data);

    }
    public function robot_code_generator()
    {
      if(Auth::user()->user_type != 'Admin' && !in_array(13,$this->module_access))
        return redirect()->route('login');
      $this->member_validity();
      $data['body'] ='seo-tools.utilities.robot-code-generator';
      return $this->_viewcontroller($data);
    }

    public function mb_count_words($string) {
        preg_match_all('/[\pL\pN\pPd]+/u', $string, $matches);
        return count($matches[0]);
    }

    public function sitemap_generator()
    {
      $this->member_validity();
      $data['body'] ='seo-tools.utilities.sitemap';
      return $this->_viewcontroller($data);
    }
    public function sitemap_generator_action(Request $request)
    {
        $domain = $request->input('domain_name');
        if (strpos($domain, 'https://') === false) {
            $domain = 'https://' . $domain;
        }
        $depth_size = $request->input('depth_size') ?? 0;
        $sitemap = SitemapGenerator::create($domain)
            ->hasCrawled(function ($url) {
                // Custom logic for filtering or modifying URLs
                return $url;
            })
            ->configureCrawler(function (Crawler $crawler) use ($depth_size) {
                $crawler->setMaximumDepth($depth_size);
            })
            ->getSitemap();
        if (!file_exists(storage_path("app/public/download/sitemap"))) {
          mkdir(storage_path("app/public/download/sitemap"), 0777, true);
        }
        $sitemap->writeToFile(storage_path('app/public/download/sitemap/sitemap_'.Auth::user()->id . '.xml'));
        $sitemapContent = file_get_contents(storage_path('app/public/download/sitemap/sitemap_'.Auth::user()->id.'.xml'));
        // Echo the results in the desired format
        $xml = simplexml_load_string($sitemapContent);
        // Generate the download button

        $str = "<div class='card'>
        <div class='card-header'>
            <h4><i class='fas fa-server'></i> ".__("SiteMap")."</h4>
            <div class='card-header-action'>
                <div class='badges'>
                    <a class='btn btn-primary float-right' href='".route('download.sitemap')."'>
                        <i class='fa fa-cloud-download'></i> ".__("Download")."
                    </a>
                </div>                    
            </div>
        </div>
        <div class='card-body'>
                        <pre class='table-responsive'>
                            <table class='table'>
                                <thead>
                                    <tr>
                                        <th>URL</th>
                                        <th>Change Frequency</th>
                                        <th>Priority</th>
                                    </tr>
                                </thead>
                                <tbody>";

        // Combine the sitemap table and download button

        foreach ($xml->url as $url) {
            $loc = (string) $url->loc;
            $changefreq = (string) $url->changefreq;
            $priority = (string) $url->priority;
            $str .= "<tr>
                        <td>{$loc}</td>
                        <td>{$changefreq}</td>
                        <td>{$priority}</td>
                    </tr>";
        }
        
        $str .= "</tbody>
                </table>
            </pre>
        </div>
      
        
        </div>
        </div>
        </div>";
        
        echo $str;
        // Save the sitemap to a file
        


        
    }

    public function downloadSitemap(Request $request)
    {

        $short_domain = 'sitemap_'.Auth::user()->id;

        $sitemapPath = storage_path('app/public/download/sitemap/'.$short_domain . '.xml');
        
        // Check if the sitemap file exists
        if (file_exists($sitemapPath)) {
            // Return the file for download
            return response()->download($sitemapPath);
        } else {
            // If the sitemap file does not exist, return a 404 response
            abort(404);
        }
    }

    public function comparision()
    {
      $this->member_validity();
      $data['body'] = 'seo-tools.utilities.comparison-checker';
      $data['page_title'] = __('Website Comparison');
      return $this->_viewcontroller($data);

    }


    public function comparison_action(Request $request)
    {    
       
       $url1 = $request->input('url1', true);
       if (strpos($url1, 'https://') === false) {
        $url1 = 'https://' . $url1;
      }
       $url2 = $request->input('url2', true);
       if (strpos($url2, 'https://') === false) {
        $url2 = 'https://' . $url2;
      }
       $output = array();
       if ($url1 != '') 
       {
           
           
        $existency_data = $this->web_repport->fb_like_comment_share($url1);
        //echo "<pre>";print_r($existency_data);exit;
        $date_time = isset($existency_data['updated_time']) ? $existency_data['updated_time'] : "";
        $date_time = strtotime($date_time);
        if ($date_time !='') 
          $update_time = date('F j, Y, g:i a', $date_time);
        else
          $update_time = '';
        $description = isset($existency_data['description']) ? $existency_data['description'] : "";
        // if(strlen($description)>30)
        //    $des = '...';
        // else
        //    $des = "";
    
      if(isset($existency_data['errormessage']))
      {
        $output["output1"] = '<div class="card card-statistic-2 red">
            <div class="card-stats">
              <div class="card-stats-title"> '.$existency_data['errormessage'].' 
              </div>

            </div>


          </div>';
      }
      else
      {
        $total_share = isset($existency_data['total_share']) ? $existency_data['total_share'] : '0';
        $total_reaction = isset($existency_data['total_reaction']) ? $existency_data['total_reaction'] :'0';
        $total_comment = isset($existency_data['total_comment']) ? $existency_data['total_comment'] : '0';
        $title = isset($existency_data['title']) ? $existency_data['title'] : "";
        $type = isset($existency_data['type']) ? $existency_data['type'] : "";
        $output["output1"] = '<div class="card card-statistic-2">
                  <div class="card-stats">
                    <div class="card-stats-title"> '.__("Website Report").' 
                    </div>
                    <div class="card-stats-items">
                    <div class="card-stats-item">
                        <div class="card-stats-item-count">'.custom_number_format($total_share).'</div>
                        <div class="card-stats-item-label">'.__('Share').'</div>
                      </div>
                      <div class="card-stats-item">
                        <div class="card-stats-item-count">'.custom_number_format($total_reaction).'</div>
                        <div class="card-stats-item-label">'.__('Reaction').'</div>
                      </div>
                      <div class="card-stats-item">
                        <div class="card-stats-item-count">'.custom_number_format($total_comment).'</div>
                        <div class="card-stats-item-label">'.__('Comment').'</div>
                      </div>
                    </div>
                  </div>
                  <div class="card-icon shadow-primary bg-primary">
                    <i class="fas fa fa-link"></i>
                  </div>
                  <div class="card-wrap">
                    <div class="card-header">
                      <h4>'.$title.'</h4>
                    </div>
                    <div class="card-body">
                      <p style="font-size:15px;"> '.$type.' </p>
                    </div>
                  </div>
                </div>

                <li class="media">
                  <div class="media-body">
                    <div class="card card-statistic-1">
                      <div class="card-icon bg-primary">
                        <i class="fas fa fa-clock-o"></i>
                      </div>
                      <div class="card-wrap">
                        <div class="card-header">
                          <h4>'.__('Updated Time').'</h4>
                        </div>
                        <div class="card-body"><p style="font-size:13px;">'.$update_time.'</p></div>
                      </div>
                    </div>
                  </div>
                </li>


                <li class="media">
                  <div class="media-body">
                    <div class="card card-statistic-1">
                      <div class="card-icon bg-primary">
                        <i class="fas fa-align-justify"></i>
                      </div>
                      <div class="card-wrap">
                        <div class="card-header">
                          <h4>'.__('Description').'</h4>
                        </div>
                        <div class="card-body"><p class="description_tooltip" style="font-size:13px;overflow:auto;" data-toggle="tooltip" data-placement="top" title="" data-original-title="'.$description.'">'.$description.'</p></div>
                      </div>
                    </div>
                  </div>
                </li>
            
              ';
        
      }



            
       }
       else
       {
        $output["empty"] = 'empty';
       }

       

       if($url2 != '')
       {
           
        $existency_data2 = $this->web_repport->fb_like_comment_share($url2);

        $date_time2 =  isset($existency_data2['updated_time']) ? $existency_data2['updated_time'] : "";
        $date_time2 = strtotime($date_time2);
        if ($date_time2 !='') 
            $update_time2 = date('F j, Y, g:i a',$date_time2);
        else
            $update_time2 = '';
        $description2 = isset($existency_data2['description']) ? $existency_data2['description'] : "";
        // if(strlen($description2)>30)
        //    $des2 = '...';
        // else
        //    $des2 = "";

          if(isset($existency_data2['errormessage']))
          {
            $output["output2"] = '<div class="card card-statistic-2 red">
                <div class="card-stats">
                  <div class="card-stats-title"> '.$existency_data2['errormessage'].' 
                  </div>

                </div>


              </div>';
          }
          else
          {
          $total_share2 = isset($existency_data2['total_share']) ? $existency_data2['total_share'] : '0';
          $total_reaction2 = isset($existency_data2['total_reaction']) ? $existency_data2['total_reaction'] : '0';
          $total_comment2 =  isset($existency_data2['total_comment']) ? $existency_data2['total_comment'] : '0';
          $title2 = isset($existency_data2['title']) ? $existency_data2['title'] : '';
          $type2 = isset($existency_data2['type']) ? $existency_data2['type'] : '';

          $output["output2"] = '<div class="card card-statistic-2">
                                  <div class="card-stats">
                                    <div class="card-stats-title"> '.__("Competitor Website Report").' 
                                    </div>
                                    <div class="card-stats-items">
                                      <div class="card-stats-item">
                                        <div class="card-stats-item-count">'.custom_number_format($total_share2).'</div>
                                        <div class="card-stats-item-label">'.__('Share').'</div>
                                      </div>
                                      <div class="card-stats-item">
                                        <div class="card-stats-item-count">'.custom_number_format($total_reaction2).'</div>
                                        <div class="card-stats-item-label">'.__('Reaction').'</div>
                                      </div>
                                      <div class="card-stats-item">
                                        <div class="card-stats-item-count">'.custom_number_format($total_comment2).'</div>
                                        <div class="card-stats-item-label">'.__('Comment').'</div>
                                      </div>
                                    </div>
                                  </div>
                                  <div class="card-icon shadow-primary bg-primary">
                                    <i class="fas fa fa-link"></i>
                                  </div>
                                  <div class="card-wrap">
                                    <div class="card-header">
                                      <h4>'.$title2.'</h4>
                                    </div>
                                    <div class="card-body">
                                      <p style="font-size:15px;">'.$type2.' </p>
                                    </div>
                                  </div>
                                </div>
                                <li class="media">
                                  <div class="media-body">
                                    <div class="card card-statistic-1">
                                      <div class="card-icon bg-primary">
                                        <i class="fas fa fa-clock-o"></i>
                                      </div>
                                      <div class="card-wrap">
                                        <div class="card-header">
                                          <h4>'.__('Updated Time').'</h4>
                                        </div>
                                        <div class="card-body"><p style="font-size:13px;">'.$update_time2.'</p></div>
                                      </div>
                                    </div>
                                  </div>
                                </li>

                                <li class="media">
                                  <div class="media-body">
                                    <div class="card card-statistic-1">
                                      <div class="card-icon bg-primary">
                                        <i class="fas fa-align-justify"></i>
                                      </div>
                                      <div class="card-wrap">
                                        <div class="card-header">
                                          <h4>'.__('Description').'</h4>
                                        </div>
                                        <div class="card-body"><p style="font-size:13px;overflow:auto;" class="description_tooltipp" data-toggle="tooltip" data-placement="top" title="" data-original-title="'.$description2.'">'.$description2.'</p></div>
                                      </div>
                                    </div>
                                  </div>
                                </li>
                              
                                ';
          
          }



             
       }
       else
       {
            
        $output["empty1"] = 'empty1';
           
       }
       $this->_insert_usage_log($module_id=13,$req=1);
       echo json_encode($output);


    }

    public function word_count()
    {
      $this->member_validity();
      $data['body'] ='seo-tools.utilities.word-count';
      return $this->_viewcontroller($data);

    }
    public function word_count_action(Request $request)
    {

      $text = $request->input('emails');
      $no_of_word = __('No. of words');
      $wordCount = str_word_count($text);

      $textWithoutSpaces = str_replace(' ', '', $text);
      $no_of_carecters = __('No. of characters');
      $no_of_carecters_without_space = __('No. of characters without space');
      $characterCount = strlen($text);
      $characterCountWithout = strlen($textWithoutSpaces);
      $str1="<div class='card'>
              <div class='card-header'>
                  <h4><i class='fas fa-plus-circle'></i> ".__("Word Counter")."</h4>
              </div>
              <div class='card-body p-0'>
                  <div class='table-responsive table_scroll'>
                  <table class='table table-striped table-md'>";
      $str1.="<tbody><tr>
            <th >".__("Text")."</th>
            <th class='text-center'>".__("Search Result")."</th>";             
      $str1.="</tr>";

      $str1.= "<tr><td class='text-primary'>{$no_of_word}</td><td class='text-center'><span class='badge badge-secondary'>".$wordCount."</sapn></td></tr>";
      $str1.= "<tr><td class='text-primary'>{$no_of_carecters}</td><td class='text-center'><span class='badge badge-secondary'>".$characterCount."</sapn></td></tr>";
      $str1.= "<tr><td class='text-primary'>{$no_of_carecters_without_space}</td><td class='text-center'><span class='badge badge-secondary'>".$characterCountWithout."</sapn></td></tr></tbody>";
      $str1.= "</table></div></div></div>"; 


      echo $str1;

    }
    
}
