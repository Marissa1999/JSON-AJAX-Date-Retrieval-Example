<?php
header("Access-Control-Allow-Origin: *"); 
$time = new DateTime();

class Obj2xml {

    var $xmlResult;
   
    function __construct($rootNode){
        $this->xmlResult = new SimpleXMLElement("<$rootNode></$rootNode>");
    }
   
    private function iteratechildren($object,$xml){
        foreach ($object as $name=>$value) {
            if (is_string($value) || is_numeric($value)) {
                $xml->$name=$value;
            } else {
                $xml->$name=null;
                $this->iteratechildren($value,$xml->$name);
            }
        }
    }
   
    function toXml($object) {
        $this->iteratechildren($object,$this->xmlResult);
        return $this->xmlResult->asXML();
    }
}

if (isset($_GET['format']) && $_GET['format']=='JSON'){
	if (isset($_GET['callback'])){
		$callback = $_GET['callback'];
		echo $callback, '(', json_encode(new DateTime()), ');';
	}else{
		echo json_encode(new DateTime());
	}
}else{
	$converter=new Obj2xml("servertime");
	header("Content-Type:text/xml");
	echo $converter->toXml($time);
}
?>