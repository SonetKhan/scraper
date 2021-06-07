<?php

namespace App\Http\Controllers;

use Goutte\Client;

use Illuminate\Http\Request;
use App\scraperModel;
use Illuminate\Support\Facades\DB;

class scraperController extends Controller
{
   
    public $resultUrl = array();
    public $resultTitle = array();
    public $assArray = array();

    function scraper(){

        $client = new Client();

        $url = 'https://vidnext.net/';

        $page = $client->request('GET',$url);

        // $page->filter('.wpb_wrapper')->each(function ($item) {
        //     $this->results[$item->filter('h1')->text()] = $item->filter('.maincounter-number')->text();
        // });
            $page->filter('.wpb_wrapper')->each(function($item){

              $item->filter('.video-block')->each(function($link){

                $url = $link->filter('a')->attr('href')."<br>";

                $title = $link->filter('.name')->text()."<br>";

                array_push($this->resultUrl,$url);
                array_push($this->resultTitle,$title);

             });

            });

            
            if(count($this->resultUrl) == count($this->resultTitle)){
                

                for($i=0;$i<count($this->resultUrl);$i++) {
                    $this->assArray[$this->resultUrl[$i]] = $this->resultTitle[$i];
                }
            }

            // print_r($this->assArray);

            foreach($this->assArray as $key=>$value){



               $result = scraperModel::insert([
                'video_title'=> $value,
                'video_link' =>$key
               ]);

            }
            ($result)?"Data add successful":"Data add not successful";

           



        
    }
    



    
}
