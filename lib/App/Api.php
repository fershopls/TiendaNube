<?php

namespace lib\App;

class Api {

    protected $token;
    const URL_PREFIX = 'http://45.33.36.126/rest/V1';

    protected function getToken () {
        return "t08p909o0f7hbp9ix7hl83oentmoc3rs";
        $token_string = $this->request("POST /integration/admin/token", array('username'=>'manduka','password'=>'mikywe123'), FALSE);
        return $token_string;
    }

    protected function getNiceSyntax ($string) {
        preg_match("/^(GET|POST|PUT|DELETE)\s(.+)$/i", $string, $matches);
        return array($matches[1],$matches[2]);
    }

    public function request ($request_string, $data = array(), $token = true)
    {
        list($method, $url) = $this->getNiceSyntax($request_string);

        $urldata = $method=="GET"&&$data?'?'.http_build_query($data):'';

        $token = $token?'Authorization: Bearer '. $this->getToken():'';

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => self::URL_PREFIX . $url . $urldata,
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
                "Content-Length: " . strlen(json_encode($data)),
                $token,
            ),
            CURLOPT_CUSTOMREQUEST => strtoupper($method),
            CURLOPT_POSTFIELDS => json_encode($data),
        ]);
        $response = curl_exec($curl);
        curl_close($curl);
        return json_decode($response);
    }

}