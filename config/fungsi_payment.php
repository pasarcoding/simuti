<?php
    function cek_payment($order_id,$serverKey,$link){
        $server_key = base64_encode($serverKey.':');
        if ($link == 'https://app.sandbox.midtrans.com/snap/snap.js'){
          $link_status = 'https://api.sandbox.midtrans.com';
        }else{
          $link_status = 'https://api.midtrans.com';
        }

        $url = $link_status.'/v2/'.$order_id.'/status';
       
        /*********************** CEK STATUS MIDTRANS **********************/
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => $url,
          CURLOPT_CUSTOMREQUEST => 'GET',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10, 
          CURLOPT_HTTPHEADER => array(
            'Content-type: application/json',
            'Authorization: Basic '.$server_key
          )
        ));

        $response = curl_exec($curl);
        $datres = json_decode($response,true);
        curl_close($curl);
        return $datres;
    }
?>