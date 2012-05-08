<?php

	/**

		Code supplied by https://code.google.com/p/phprackcloud/

		Customizaion in Comments Below.

	**/

define ("ERROR_304","Indicates that the requested resource's status has not changed since the specified If-Modified-Since date.");
define ("ERROR_400","There was a syntax error in the request. Do not repeat the request without adjusting the input.");
define ("ERROR_401","The X-Auth-Token is not valid or has expired. Re-authenticate to obtain a fresh token.");
define ("ERROR_403","Access is denied for the given request. Check your X-Auth-Token header. The token may have expired.");
define ("ERROR_404","The server has not found anything matching the Request URI.");
define ("ERROR_413","The server is refusing to process the request because the client request rate exceeds a configured limit for the given account and action type. The server will include a Retry-After header field to indicate that it is temporary and after what time the client MAY try again.");
define("ERROR_500","The server encountered an unexpected condition which prevented it from fulfilling the request.");

/**
 * RackSpace Cloud Request Processor
 * 
 * @author : Leevio Team (www.leevio.com)
 * @copyright : NEW BSD License
 * @since : Oct 12, 2009
 * @version : 1.0
 */
class Request
{
    public static $LastHTTPCode;
    /**
     * make a HTTP POST/GET call to rackspace cloud service. This object is used internall for all 
     * rackspace cloud objects in this package. 
     * 
     *
     * @param string $Url access point
     * @param array $Headers an associated Array of headers 
     * @param mixed $Extra extra POST data
     * @param bool $ReturnHeader 
     * @param bool $HTTPDelete used to perform a HTTP DELETE call
     * @param boll $HTTPPut used to pergorm PUT POSTS (Addded by Nick B)
     * @return string Response 
     */
    public static function post($Url, $Headers, $Extra=null, $ReturnHeader=false,$HTTPDelete=false,$HTTPPut=false)
    {
        try{
            $c = curl_init($Url);
        }
        catch (Exception $e)
        {
            return false;
        }

        if($Headers){
            $_headers = array();
            foreach ($Headers as $key=>$value)
            {
                $_headers[] ="{$key}: {$value}";
            }
            curl_setopt($c,CURLOPT_HTTPHEADER,$_headers);
        }

        if($Extra)
        {
            //echo "Post";
            //echo $Extra;
            curl_setopt($c, CURLOPT_POST, 1);
            curl_setopt($c, CURLOPT_POSTFIELDS, $Extra);
        }
        
        if($HTTPDelete)
        {
            curl_setopt($c, CURLOPT_CUSTOMREQUEST, "DELETE");
        }

        if($HTTPPut) // Added by Nick
        {
            curl_setopt($c, CURLOPT_CUSTOMREQUEST, "PUT");
        }

        if($ReturnHeader){
            curl_setopt($c,CURLOPT_HEADER, true);
            //curl_setopt($c, CURLINFO_HEADER_OUT, true);
        }

        curl_setopt($c,CURLOPT_URL,$Url);
        curl_setopt($c,CURLOPT_SSL_VERIFYPEER,false);
        curl_setopt($c,CURLOPT_VERBOSE, true);
        curl_setopt($c,CURLOPT_RETURNTRANSFER, true);
        
        $Response = curl_exec($c);        
        $Info =curl_getinfo($c,CURLINFO_HTTP_CODE);
        self::$LastHTTPCode = $Info;
        curl_close($c);
        return $Response;
    }
    
    /**
     * This function is used to make a JSON post call to rackspace cloud service. 
     * internally used by all objects in this package. This function makes use of a 
     * authenticated RackAuth object. 
     * 
     * If you are planning to use a cached AuthToken, make sure to populate
     * a RackAuth object with at least AuthToken and XServerManagement Url. 
     *
     * HACKED by NICK B - Added $ReturnHeader=false
     *
     * @param string $Url (key part of the API access point)
     * @param RackAuth $RackAuth Authenticated RackAuth object
     * @param mixed $PostData
     * @return unknown
     */
    public function postAuthenticatedRequest($Url, RackAuth $RackAuth, $PostData=null, $ReturnHeader=false)
    {
        $PostUrl = $RackAuth->getXServerManagementUrl()."/".$Url;
        $AuthToken  = $RackAuth->getXAuthToken();
        
        //echo $AuthToken;
        $Response = self::post($PostUrl, array("X-Auth-Token"=>$AuthToken,"Content-Type"=>"application/json"),$PostData, $ReturnHeader);
        return $Response;
    }

     /**
     * This function is used to make a HTTP DELETE call to rackspace cloud service. 
     * internally used by all objects in this package. This function makes use of a 
     * authenticated RackAuth object. 
     * 
     * If you are planning to use a cached AuthToken, make sure to populate
     * a RackAuth object with at least AuthToken and XServerManagement Url. 
     *
     * @param string $Url (key part of the API access point)
     * @param RackAuth $RackAuth Authenticated RackAuth object
     * @return unknown
     */
    public function postAuthenticatedDeleteRequest($Url, RackAuth $RackAuth)
    {
        $PostUrl = $RackAuth->getXServerManagementUrl()."/".$Url;
        $AuthToken  = $RackAuth->getXAuthToken();
        
        //echo $AuthToken;
        $Response = self::post($PostUrl, array("X-Auth-Token"=>$AuthToken,"Content-Type"=>"application/json"),$PostData,false,true);
        return $Response;
    }

    /**
       
       NICK WAS HERE!

    **/

    /**
     * This function is used to make a HTTP PUT (POST) call to rackspace cloud service. 
     * internally used by all objects in this package. This function makes use of a 
     * authenticated RackAuth object. 
     * 
     * HACKED BY NICK B
     *
     * @param string $Url (key part of the API access point)
     * @param RackAuth $RackAuth Authenticated RackAuth object
     * @return unknown
     */
    public function postAuthenticatedPutRequest($Url, RackAuth $RackAuth, $PostData=null)
    {
        $PostUrl = $RackAuth->getXServerManagementUrl()."/".$Url;
        $AuthToken  = $RackAuth->getXAuthToken();
        
        //echo $AuthToken;
        $Response = self::post($PostUrl, array("X-Auth-Token"=>$AuthToken,"Content-Type"=>"application/json"),$PostData,false,false,true);
        return $Response;
    }

    /**

        [DONE]

    **/
    
    /**
     * Parse Headers and return the parsed data as an associated Array
     *
     * @param string $Header
     * @return array associated array containing the headers
     */
    public  function parseHeaders( $Header )
    {
        $Headers= array();
        $Fields = explode("\r\n",trim($Header));
        foreach( $Fields as $Field ) {
            $FieldParts = explode(":",$Field);
            $_Header = array_shift($FieldParts);
            $Headers[$_Header] = trim(join(":",$FieldParts));
        }
        return $Headers;
    }

    /**
     * return error info about last calls
     *
     * @return string
     */
    function getLastError()
    {
        $LastHTTPCode = self::$LastHTTPCode;
        if($LastHTTPCode>=304)
        {
            $ErrorMessage = constant("ERROR_{$LastHTTPCode}");
            return $ErrorMessage;
        }
        else 
        return "";
    }
    
    /**
     * return last HTTP response code
     *
     * @return string
     */
    function getLastHTTPCode()
    {
        return self::$LastHTTPCode;
    }
}

/**

    hacked & chopped by Nick :)

    London Authentication enabled.
    Management Server for Cloud Monitoring Enabled.

 * RackSpace Cloud Authentication Manager
 * 
 * @author : Leevio Team (www.leevio.com)
 * @copyright : New BSD License
 * @since : Oct 12, 2009
 * @version : 1.0

    https://code.google.com/p/phprackcloud/
 
 */
class RackAuth
{

    private $Username="";
    private $APIKey="";
    private $XAuthToken;
    private $XServerManagementUrl;
    
    public function __construct($Username, $APIKey, $Location)
    {
        $this->Username=$Username;
        $this->APIKey=$APIKey;

        if ($Location == "uk") {
        	$AuthLocation = "https://lon.auth.api.rackspacecloud.com/v1.0";
        } else {
        	$AuthLocation = "https://auth.api.rackspacecloud.com/v1.0";
        }

        $this->Location=$AuthLocation;
    }
    public function auth()
    {

        if(!$this->Username || !$this->APIKey)
        throw new Exception('Username or Password cannot be empty');

        $Response = Request::post($this->Location,array("X-Auth-User"=>$this->Username, "X-Auth-Key"=>$this->APIKey),null,true);
        $Headers = Request::parseHeaders($Response);
        //print_r($Headers);
        if($Headers)
        {
            $this->XAuthToken = $Headers['X-Auth-Token'];
            
            $HiddenAccountNumber = explode("/", $Headers['X-Server-Management-Url']);
            $AccountNumber = $HiddenAccountNumber['4'];
            
            $this->XServerManagementUrl = 'https://monitoring.api.rackspacecloud.com/v1.0/' . $AccountNumber . '' ;
            

            return true;
        }

    }

    function getXAuthToken()
    {
        return $this->XAuthToken;
    }

    function setXAuthToken($AuthToken)
    {
        $this->XAuthToken=$AuthToken;
    }

    function getXServerManagementUrl()
    {
        return $this->XServerManagementUrl;
    }
    
    function setXServerManagementUrl($Url)
    {
        $this->XServerManagementUrl=$Url;
    }

    function getUsername()
    {
        return $this->Username;
    }
    
    function setUsername($Username)
    {
        $this->Username=$Username;
    }

    function getAPIKey()
    {
        return $this->APIKey;
    }
    
    function setAPIKey($APIKey)
    {
        $this->APIKey=$APIKey;
    }
}
?>