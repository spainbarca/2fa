<?php
namespace App\Http\Controllers\Auth;

    function get_ip(){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "http://httpbin.org/ip");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        $ip = json_decode($output, true);

        $ip_visitor = '';
        switch(true){
            case (!empty($_SERVER['HTTP_X_REAL_IP'])) : $ip_visitor = $_SERVER['HTTP_X_REAL_IP'];
            case (!empty($_SERVER['HTTP_CLIENT_IP'])) : $ip_visitor = $_SERVER['HTTP_CLIENT_IP'];
            case (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) : $ip_visitor = $_SERVER['HTTP_X_FORWARDED_FOR'];
            default : $ip_visitor = $_SERVER['REMOTE_ADDR'];
            }

        $PublicIP = $ip['origin'];
        $json     = file_get_contents("http://ipinfo.io/$PublicIP/geo");
        $json     = json_decode($json, true);
        $country  = $json['country'];
        $region   = $json['region'];
        $city     = $json['city'];
        $loc      = $json['loc'];
        $org      = $json['org'];

        $mac='UNKNOWN';
        foreach(explode("\n",str_replace(' ','',trim(`getmac`,"\n"))) as $i)
        if(strpos($i,'Tcpip')>-1){$mac=substr($i,0,17);break;}

        get_current_user();
        $username = get_current_user();

        return 'IP pública: ' . $PublicIP.
        ' - IP local: ' . $ip_visitor.
        ' -  País: ' . $country.
        ' -  Departamento: ' . $region.
        ' -  Ciudad: ' . $city.
        ' -  Coordenadas: ' . $loc.
        ' -  Operadora: ' . $org.
        ' -  MAC: ' . $mac.
        ' -  Usuario: ' . $username;
    }
