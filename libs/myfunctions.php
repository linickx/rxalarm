<?php
	/**
		
		Generic Functions Library - by NB :)

	**/

	function authuser() {

		global $db, $user;

		// Start by booting out people with without cookies.

		if (!isset($_COOKIE['rxalarm']['uid'])) { 
			header("Location: $www/twitter.php");
			exit;
		}

		if (!isset($_COOKIE['rxalarm']['at'])) { 
			header("Location: $www/twitter.php");
			exit;
		}
	
		if (!isset($_COOKIE['rxalarm']['auth'])) { 
			header("Location: $www/twitter.php");
			exit;
		}

		// try to curb any buffer overflows.
		$uid = substr($_COOKIE['rxalarm']['uid'], 0, 256);
		$uid = preg_replace("/[^0-9]/", "", $uid); // Better UID Protection... all I need to do now is remove the safeish_uid references.
		$access_token = substr($_COOKIE['rxalarm']['at'], 0, 256);
		$uauth = substr($_COOKIE['rxalarm']['auth'], 0, 256);

		// generate an authentication string
		$auth = "nb_" . $uid . SALT . $access_token;
		$auth = sha1($auth);

		// does the user submitted auth string match ours?
		if ($uauth === $auth) {

			if (!isset($user)) {

				$safeish_uid = $db->escape($uid); 
				$safeish_access_token = $db->escape($access_token); // escape cookie content :)

				$user_account = $db->get_row("SELECT * FROM user WHERE id = \"$safeish_uid\" AND tw_at = \"$safeish_access_token\" ");

				if (is_null($user_account->rs_wh)) {
					$rs_wh = "new"; // new user !
				} else {
					$rs_wh = $user_account->rs_wh;
				}

				$user_twitter = unserialize($user_account->tw_account);

				$user_rackspace = unserialize($user_account->rs_account);

				if (!isset($user_rackspace['timezone'])) {
					$user_rackspace['timezone'] = "Europe/London"; // default timezone.
				}

				$user = array( "twitter" => $user_twitter, "uid" => $uid, "rs_wh" => $rs_wh, "rackspace" => $user_rackspace);
			}

			return true; // life is good!
		
		} else {
			
			// cookies have been tampered with, delete & start again.
			setcookie("rxalarm[uid]", "", time() - 3600);
			setcookie("rxalarm[at]", "", time() - 3600);
			setcookie("rxalarm[auth]", "", time() - 3600);
			header("Location: $www");
			exit;
			
		}

	}

	function getHeaders() { // http://www.rvaidya.com/blog/php/2009/02/25/get-request-headers-sent-by-client-in-php/
		$headers = array();
		
		foreach ($_SERVER as $k => $v) {
			if (substr($k, 0, 5) == "HTTP_") {
				#$k = str_replace('_', ' ', substr($k, 5));
				#$k = str_replace(' ', '-', ucwords(strtolower($k)));
				$headers[$k] = $v;
			}
		}
		
		return $headers;
	}

	function print_html5_head($title, $head, $nav) {
				Global $www, $user;

				if (isset($_COOKIE['rxalarm'])) {
					authuser(); // if user "is" logged in, check it.
				}
		?>
			<!DOCTYPE html>
			<html lang="en">
			<head>
			<meta charset="utf-8">
			<meta name="google-site-verification" content="QokEfecxpAZkKgdID7YlPMzzlCD388UWKXdRZhJi0CM" />

			<link rel="stylesheet" type="text/css" href="<?php echo $www;?>/bootstrap/css/bootstrap.min.css">
			<style>
				body {
					padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
					}
			</style>
			<link rel="stylesheet" type="text/css" href="<?php echo $www;?>/bootstrap/css/bootstrap-responsive.min.css">

			<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
			<script src="<?php echo $www;?>/bootstrap/js/bootstrap.min.js"></script>

			<?php echo $head; ?>

			<title><?php echo $title;?> - [rx]Alarm</title>
	
			</head>
			<body>

				<div class="navbar navbar-fixed-top">
					<div class="navbar-inner">
						<div class="container">
							<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							</a>
							<a class="brand" href="#">[rx]Alarm</a>
							<div class="nav-collapse">
								<ul class="nav">
									<li <?php if (isset($nav['home'])) { echo 'class="active"'; } ?>><a href="<?php echo $www;?>">Home</a></li>
									<li <?php if (isset($nav['console'])) { echo 'class="active"'; } ?>><a href="<?php echo $www;?>/console">Console</a></li>
									<li <?php if (isset($nav['help'])) { echo 'class="active"'; } ?>><a href="<?php echo $www;?>/help">Help</a></li>
									<li <?php if (isset($nav['contact'])) { echo 'class="active"'; } ?>><a href="<?php echo $www;?>/contact">Contact</a></li>

								</ul>
									<ul class="nav pull-right">	
									<li class="divider-vertical"></li>

									<?php if (isset($user)) {
										?>
									<li class="dropdown pull-right" id="menu1">
										<a class="dropdown-toggle" data-toggle="dropdown" href="#menu1">
											<?php echo $user['twitter']->name; ?>
											<b class="caret"></b>
											<img style="padding-left:5px" src="<?php echo $user['twitter']->profile_image_url_https; ?>" alt="Twitter Avatar" width="24px" height="24px" />
										</a>
										<ul class="dropdown-menu">
											<li><a href="<?php echo $www;?>/account">Account</a></li>
											<li class="divider"></li>
											<li><a href="<?php echo $www;?>/logout">Log Out</a></li>
										</ul>

									</li>
										<?php
									} else {
										?>
									<li class="pull-right"><a href="<?php echo $www;?>/twitter.php">Log In</a></li>
										<?php
									} ?>
									
								</ul>
							</div><!--/.nav-collapse -->
						</div>
					</div>
				</div>

				<div class="container-fluid">

    <?php
	}

	function print_html5_foot() {

		?>
				<hr />
				<footer>

					<div class="container-fluid">
						<div class="row-fluid">

							<div class="span2">
								&copy; Nick Bettison - <a href="http://www.linickx.com">linickx.com</a>
							</div>

							<div class="span8">
								&nbsp;
							</div>

							<div class="span2">	
								<iframe src="http://markdotto.github.com/github-buttons/github-btn.html?user=linickx&repo=rxalarm&type=fork"
  allowtransparency="true" frameborder="0" scrolling="0" width="53px" height="20px"></iframe>

  								<iframe src="http://markdotto.github.com/github-buttons/github-btn.html?user=linickx&repo=rxalarm&type=watch"
  allowtransparency="true" frameborder="0" scrolling="0" width="62px" height="20px"></iframe>

							</div>
						</div>
					</div>

				</footer>
				</div> <!-- /.container -->

				<script type="text/javascript">

				  var _gaq = _gaq || [];
				  _gaq.push(['_setAccount', 'UA-76334-10']);
				  _gaq.push(['_trackPageview']);
				
				  (function() {
				    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
				    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
				    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
				  })();
				
				</script>
		</body>
		</html><?php
		die();
	}

	function output_json($array) { // output an array as JSON.

		/**

		Usage: 

		$output = array ('response'=>$res,'msg'=> $msg);
		output_json($output);

		**/
		
		$output = json_encode($array);

		header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
		header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past

		header('Content-Type: text/javascript');
		echo $output;
		exit;
	}

?>