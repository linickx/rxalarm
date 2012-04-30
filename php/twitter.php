<?php

/**
	
    Twitter Oauth Login, brought to you by copy & paste!

    See comments below, this script was by Guido, basically I found "google" and replaced with "twitter" ;-)

*/

require_once("../libs/setup.php");

include_once "../libs/OAuthSimple.php";


define("TWITTER_OAUTH_HOST","https://api.twitter.com");
define("TWITTER_REQUEST_TOKEN_URL", TWITTER_OAUTH_HOST . "/oauth/request_token");
define("TWITTER_AUTHORIZE_URL", TWITTER_OAUTH_HOST . "/oauth/authorize");
define("TWITTER_AUTHENTICATE_URL", TWITTER_OAUTH_HOST . "/oauth/authenticate");
define("TWITTER_ACCESS_TOKEN_URL", TWITTER_OAUTH_HOST . "/oauth/access_token");
define("TWITTER_PUBLIC_TIMELINE_API", TWITTER_OAUTH_HOST . "/statuses/public_timeline.json");
define("TWITTER_UPDATE_STATUS_API", TWITTER_OAUTH_HOST . "/statuses/update.json");
define("TWITTER_MENTIONS_API", TWITTER_OAUTH_HOST . "/statuses/mentions.json");

///////////////////////////////////\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
// Author: Guido Schlabitz
// Email: guido.schlabitz@gmail.com
//
// http://unitedHeroes.net/OAuthSimple
//
//////////////////////////////////////////////////////////////////////
#require 'oauth.php';
$oauthObject = new OAuthSimple();

// As this is an example, I am not doing any error checking to keep 
// things simple.  Initialize the output in case we get stuck in
// the first step.
$output = 'Authorizing...';

// Fill in your API key/consumer key you received when you registered your 
// application with Google.
$signatures = array( 'consumer_key'     => TWITTER_CONSUMER_KEY,
                     'shared_secret'    => TWITTER_CONSUMER_SECRET);

// In step 3, a verifier will be submitted.  If it's not there, we must be
// just starting out. Let's do step 1 then.
if (!isset($_GET['oauth_verifier'])) {
    ///////////////////////////////////\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    // Step 1: Get a Request Token
    //
    // Get a temporary request token to facilitate the user authorization 
    // in step 2. We make a request to the OAuthGetRequestToken endpoint,
    // submitting the scope of the access we need (in this case, all the 
    // user's calendars) and also tell Google where to go once the token
    // authorization on their side is finished.
    //
    $result = $oauthObject->sign(array(
        'path'      => TWITTER_REQUEST_TOKEN_URL,
        'parameters'=> array(
            'scope'         => TWITTER_MENTIONS_API,
            'oauth_callback'=> "$www/twitter.php"),
        'signatures'=> $signatures));

    // The above object generates a simple URL that includes a signature, the 
    // needed parameters, and the web page that will handle our request.  I now
    // "load" that web page into a string variable.
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, $result['signed_url']);
    $r = curl_exec($ch);
    curl_close($ch);

    // We parse the string for the request token and the matching token
    // secret. Again, I'm not handling any errors and just plough ahead 
    // assuming everything is hunky dory.
    parse_str($r, $returned_items);
    $request_token = $returned_items['oauth_token'];
    $request_token_secret = $returned_items['oauth_token_secret'];

    // We will need the request token and secret after the authorization.
    // Google will forward the request token, but not the secret.
    // Set a cookie, so the secret will be available once we return to this page.
    setcookie("oauth_token_secret", $request_token_secret, time()+3600);
    //
    //////////////////////////////////////////////////////////////////////
    
    ///////////////////////////////////\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    // Step 2: Authorize the Request Token
    //
    // Generate a URL for an authorization request, then redirect to that URL
    // so the user can authorize our access request.  The user could also deny
    // the request, so don't forget to add something to handle that case.
    $result = $oauthObject->sign(array(
        'path'      => TWITTER_AUTHENTICATE_URL,
        'parameters'=> array(
            'oauth_token' => $request_token),
        'signatures'=> $signatures));

    // See you in a sec in step 3.
    header("Location:$result[signed_url]");
    exit;
    //////////////////////////////////////////////////////////////////////
}
else {
    ///////////////////////////////////\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    // Step 3: Exchange the Authorized Request Token for a Long-Term
    //         Access Token.
    //
    // We just returned from the user authorization process on Google's site.
    // The token returned is the same request token we got in step 1.  To 
    // sign this exchange request, we also need the request token secret that
    // we baked into a cookie earlier. 
    //

    // Fetch the cookie and amend our signature array with the request
    // token and secret.
    $signatures['oauth_secret'] = $_COOKIE['oauth_token_secret'];
    $signatures['oauth_token'] = $_GET['oauth_token'];
    
    // Build the request-URL...
    $result = $oauthObject->sign(array(
        'path'      => TWITTER_ACCESS_TOKEN_URL,
        'parameters'=> array(
            'oauth_verifier' => $_GET['oauth_verifier'],
            'oauth_token'    => $_GET['oauth_token']),
        'signatures'=> $signatures));

    // ... and grab the resulting string again. 
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, $result['signed_url']);
    $r = curl_exec($ch);

    // Voila, we've got a long-term access token.
    parse_str($r, $returned_items);        
    $access_token = $returned_items['oauth_token'];
    $access_token_secret = $returned_items['oauth_token_secret'];
    
    // We can use this long-term access token to request Google API data,
    // for example, a list of calendars. 
    // All Google API data requests will have to be signed just as before,
    // but we can now bypass the authorization process and use the long-term
    // access token you hopefully stored somewhere permanently.
    
    #$signatures['oauth_token'] = $access_token;
    #$signatures['oauth_secret'] = $access_token_secret;
    
    //////////////////////////////////////////////////////////////////////
    
    // Example Google API Access:
    // This will build a link to an RSS feed of the users calendars.
    
    #$oauthObject->reset();
    #$result = $oauthObject->sign(array(
    #    'path'      =>TWITTER_MENTIONS_API,
    #    'parameters'=> array('count' => '15'),
    #    'signatures'=> $signatures));

    // Instead of going to the list, I will just print the link along with the 
    // access token and secret, so we can play with it in the sandbox:
    //
    
    #curl_setopt($ch, CURLOPT_URL, $result['signed_url']);
    #$output = "<p>Access Token: $access_token<BR>
    #              Token Secret: $access_token_secret</p>
    #           <p><a href='$result[signed_url]'>LINK</a></p>";
    #curl_close($ch);

    /**
    	NICK woz ere...
    */

    $uid = $db->get_row("SELECT id,tw_at FROM user WHERE tw_at = \"$access_token\" ");

    if (!$uid) { // new user

        // Hook up with twitter....
        define("TWITTER_OAUTH_HOST","https://api.twitter.com");
        define("TWITTER_ACCOUNT_API", TWITTER_OAUTH_HOST . "/account/verify_credentials.json");

        $signatures = array(    'consumer_key'     => TWITTER_CONSUMER_KEY,
                                'shared_secret'    => TWITTER_CONSUMER_SECRET);

        // Get rocking with oAuth.
        $oauthObject = new OAuthSimple();   

        $signatures['oauth_token'] = $access_token;
        $signatures['oauth_secret'] = $access_token_secret;
        
        $oauthObject->reset();

        $result = $oauthObject->sign(array(
            'path'      =>TWITTER_ACCOUNT_API,
            'signatures'=> $signatures));
        
        curl_setopt($ch, CURLOPT_URL, $result['signed_url']);
        curl_close($ch);

        $twdata = file_get_contents($result[signed_url]); // fetch data
        $twdata = json_decode($twdata); // decode_data.
        $twdata = serialize($twdata); // serialise for mysql
        $twdata = $db->escape($twdata);

        $db->query("INSERT INTO user (id, tw_at, tw_sec, tw_account) VALUES (NULL,\"$access_token\",\"$access_token_secret\", \"$twdata\")");
		$uid = $db->insert_id;

    } else {

    	$uid = $uid->id;

    }

    // Set Cookies to identify user
    setcookie("rxalarm[uid]", $uid, time() + 86400);
    setcookie("rxalarm[at]", $access_token, time() + 86400);

    // Generate a checksum to authenticate user.
    $CookieAuth = "nb_" . $uid . SALT . $access_token;
    $CookieAuth = sha1($CookieAuth);
	setcookie("rxalarm[auth]", $CookieAuth, time() + 86400);

    // remove the previous secret.
	setcookie ("oauth_token_secret", "", time() - 3600); 

	header("Location: $www/console");

    /**
    	yup, I'm done.
    */

}        
?>
<HTML>
<BODY>
<?php echo $output;?>
</BODY>
</HTML>