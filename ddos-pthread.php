<?php
error_reporting(0);
set_time_limit(0);
class My extends Thread{
	function __construct($url){
		$this->url = $url;
	}
	public function sdata($url , $custom , $delCookies = null){
		unlink("cookijem.txt");
		$ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, $url);
	    curl_setopt($ch, CURLOPT_HEADER, false);
	    if($custom[uagent]){
	    	curl_setopt($ch, CURLOPT_USERAGENT, $custom[uagent]);
	    }else{
			curl_setopt($ch, CURLOPT_USERAGENT, "msnbot/1.0 (+http://search.msn.com/msnbot.htm)");
			//curl_setopt($ch, CURLOPT_USERAGENT, "Galau Agent ".rand(100,100));
	    }
	    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,0);
	    if($custom[rto]){
	    	curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
	    }else{
	    	curl_setopt($ch, CURLOPT_TIMEOUT, 60);
	    }
	    if($custom[header]){
	    	curl_setopt($ch, CURLOPT_HTTPHEADER, $custom[header]);
	    }
	    curl_setopt($ch, CURLOPT_COOKIEJAR,  getcwd().'/cookijem.txt');
		curl_setopt($ch, CURLOPT_COOKIEFILE, getcwd().'/cookijem.txt');
	    curl_setopt($ch, CURLOPT_VERBOSE, false);
	    if($custom[post]){
	    	if(is_array($custom[post])){
	    		$query = http_build_query($custom[post]);
	        }else{
	    		$query = $custom[post];
	    	}
	    	curl_setopt($ch, CURLOPT_POST, true);
	    	curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
	    }
	    $data           = curl_exec($ch);
	    $httpcode       = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	    curl_close($ch);
	    if($delCookies != false){
	        unlink("cookijem.txt");
	    }
	    return array(
	    	'data' 		=> $data,
	    	'decode' 	=> json_decode($data , true),
	    	'httpcode' 	=> $httpcode
	    );
	}
	public function readline($pesan){
	    echo "[Checker] ".$pesan;
	    $answer =  rtrim( fgets( STDIN ));
	    return $answer;
	}
	public function clean($string) {
   		$string = preg_replace('/\s+/', '', $string);
   		$string = str_replace('-', '', $string);
   		return preg_replace('/[^A-Za-z0-9@._\-]/', '', $string); // Removes special chars.
	}
    public function run(){
    	$data = $this->sdata($this->url);
    		if($data['data'] != "app_not_found"){
				echo "w00t => ".$this->url." => Success\r\n";
				exit;
			}else{
				echo "fail => ".$this->url." => Failed\r\n";
			}
			sleep(2);
    }
}
class Checker extends My
{
	public function ngerunning(){
		$url 		= "https:// ? "; // target url
		$request 	= 10; // recommend  5000

		for ($i=0; $i <$request; $i++) {
			$urlist[] = $url;
		}
		/** start mining **/
		foreach ($urlist as $key => $value) {
			echo "Worker ==> ".$key." of ".count($urlist)."\r\n";
			$pool[] = new My($value); 
		}
	
		foreach($pool as $worker){
		    $worker->start();
		}
		foreach($pool as $worker){
		    $worker->join();
		}
	}
}
$n = new Checker;
while (1 <= 1000) {
	$n->ngerunning();
}
?>
