<?php
    try
    {
        require_once("../../ts3phpframework/libraries/TeamSpeak3/TeamSpeak3.php");
        header('Content-Type: application/json');
        $file_name = sys_get_temp_dir()."/user_cache";

        if ( file_exists($file_name) )
        {
            $cache_json =  file_get_contents($file_name);
            $cache = json_decode($cache_json,true);

            if ( time() - $cache["timestamp"] < 10 )
            {
                $arr_ClientList = $cache["clients"];
            }
        }

        if(!isset($arr_ClientList))
        {
            function transform_client($client)
            {
                return array("name"=>(string)$client["client_nickname"],"uptime"=>time()-$client["client_lastconnected"],"id"=>$client["client_base64HashClientUID"]);
            }
            $ts3_VirtualServer = TeamSpeak3::factory("serverquery://".getenv("TS_USERNAME").":".getenv("TS_PASSWORD")."@".getenv("TS_ADDRESS").":10011/?server_port=9987");
            $arr_ClientList = $ts3_VirtualServer->clientList();
            $_admin_client = array();
            foreach($arr_ClientList as $ts3_Client)
            {
                if($ts3_Client["client_database_id"]==1)
                {
                    array_push($_admin_client,$ts3_Client);
                }
            }
            $arr_ClientList = array_diff($arr_ClientList,$_admin_client);
            $arr_ClientList = array_values( array_map('transform_client',$arr_ClientList));
            file_put_contents($file_name,json_encode(array("timestamp"=>time(),"clients"=>$arr_ClientList)));
        }
        
        print(json_encode($arr_ClientList));
    }
    catch(TeamSpeak3_Exception $e)
    {
        print( json_encode (  array("error"=> array("code"=> $e->getCode(), "message" => $e->getMessage()) )));
        exit(-1);
    }
    catch(Exception $e)
    {
        print( json_encode (  array("error"=> array("code"=> "-1", "message" => "unknown") )));
        exit(-1);
    }
?>
