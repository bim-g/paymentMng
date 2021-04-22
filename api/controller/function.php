<?php
    function escape($string){
        return htmlentities($string,ENT_QUOTES,'UTF-8');
    }

    function timePub($timepost){
        // date_default_timezone_set(TimeZone);
        $date=date("Y-m-d H:i:s",time());
        $currentTime=strtotime($date);
     
        $oldTime=strtotime($timepost);
        $diffTime=$currentTime-$oldTime;
        $sec=$diffTime;
        $min=floor($diffTime/(60));
        $hr=floor($diffTime/(60*60));
        $day=floor($diffTime/(60*60*24));

        $time=null;
        if($day>5){
            $time = strtotime($timepost);            
            $time = date("d/m/Y", $time);
        }else{
            if($hr > 23 && $day<5){
                $time=$day."j";
            }
            else{
                if($hr>0 && $hr<24){
                    $time=$hr."h";
                }else{
                    if($min>0 && $min<60){
                        $time=$min."min";
                    }else{
                        $time=$sec."s";
                    }
                }
            }
        }        
        return $time;
    }
    
    function getMonth(string $format, int $month){
        $myMonth=["fr"=>['Janvier','Février','Mars','Avril','Maie','Juin','Juillet','Aôut','Septembre','Octobre','Novembre','Decembre']
        //you can add your format for according to the language
        ];
        return $myMonth[$format][$month];
    }
    function getDay(string $format,int $day){
        $myDay=[
            "fr"=> ['Dimanche', 'Lundi', 'mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi']
            //you can add your format for according to the language
        ];        
        return $myDay[$format][$day];
    }
//    
    function put(){
        $fragma = [];
        if (file_get_contents("php://input")) {
            $file_input= file_get_contents("php://input");
            if(json_decode($file_input)){
                return (array)json_decode($file_input,true);
            }
            else{                
                $explode=explode("\r",implode("\r",explode("\n",$file_input)));
                $len_Arr=count($explode);
                for($i=1;$i<$len_Arr;$i++){
                    if(!strchr($explode[$i], "----------------------------")){
                        if (strlen($explode[$i]) > 0) {
                            $replaced = str_replace("Content-Disposition: form-data; name=", "", $explode[$i]);
                            array_push($fragma, $replaced);
                        }
                    }
                }
                $len_object=count($fragma);
                $object=[];
                for($j=0;$j<$len_object;$j++){
                    if($j==0 || ($j+1)%2!=0){
                        $key=str_replace("\"","", $fragma[$j]);
                        $object = array_merge($object,[$key=> trim($fragma[($j+1)])]);
                    }
                }
                return $object;
            }            
        }
        return false;
    }