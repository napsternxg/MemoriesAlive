<?php
	include_once "config.php";
	if(!isset($_GET['id'])) die("Error");
	$id = $_GET['id'];
	$result = mysql_query("SELECT * FROM profile WHERE id='$id'");
	$row=mysql_fetch_array($result);
	
?>

<?php
/**
 * Copyright 2011 Facebook, Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may
 * not use this file except in compliance with the License. You may obtain
 * a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations
 * under the License.
 */

// Create our Application instance (replace this with your appId and secret).


// Get User ID
$user = $facebook->getUser();
//print_r($user);
//print_r($_SESSION);
// We may or may not have this data based on whether the user is logged in.
//
// If we have a $user id here, it means we know the user is logged into
// Facebook, but we don't know if the access token is valid. An access
// token is invalid if the user logged out of Facebook.

if ($user) {
  try {
    // Proceed knowing you have a logged in user who's authenticated.
    $user_profile = $facebook->api('/me');
  } catch (FacebookApiException $e) {
    error_log($e);
    $user = null;
  }
}

// Login or logout url will be needed depending on current user state.
if ($user) {
  $logoutUrl = $facebook->getLogoutUrl();
} else {
  $loginUrl = $facebook->getLoginUrl();
}
$friendId =explode( ',', $row['friends'] );
// This call will always work since we are fetching public data.

?>
<html lang="en" xmlns:fb="http://www.facebook.com/2008/fbml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=1024" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <title>impress.js | presentation tool based on the power of CSS3 transforms and transitions in modern browsers | by Bartek Szopka @bartaz</title>
    
    <meta name="description" content="impress.js is a presentation tool based on the power of CSS3 transforms and transitions in modern browsers and inspired by the idea behind prezi.com." />
    <meta name="author" content="Bartek Szopka" />

    <link href="http://fonts.googleapis.com/css?family=Open+Sans:regular,semibold,italic,italicsemibold|PT+Sans:400,700,400italic,700italic|PT+Serif:400,700,400italic,700italic" rel="stylesheet" />

    <link href="css/impress-demo.css" rel="stylesheet" />
    
    <link rel="shortcut icon" href="favicon.png" />
    <link rel="apple-touch-icon" href="apple-touch-icon.png" />
</head>

<body class="impress-not-supported">

<!--
    For example this fallback message is only visible when there is `impress-not-supported` class on body.
-->
<div class="fallback-message">
    <p>Your browser <b>doesn't support the features required</b> by impress.js, so you are presented with a simplified version of this presentation.</p>
    <p>For the best experience please use the latest <b>Chrome</b>, <b>Safari</b> or <b>Firefox</b> browser.</p>
</div>
<div class="header">
	<h1>Memories Alive</h1>
	<p><?php
	$fql= "SELECT name,pic_square,profile_url FROM user WHERE uid=me()";
	foreach($friendId as $fid){
		$fql=$fql." OR uid=".$fid;		
	}
	$friends = $facebook->api(array(
								'method'=>'fql.query',
								'query'=>$fql));
	foreach($friends as $fs){
		echo "<a href='".$fs['profile_url']."'>".$fs['name']."</a>,"; 
	}
	?></p>
</div>
<div id="impress">
    <?php if ($user) { ?>
      <pre>
        <?php //print htmlspecialchars(print_r($user_profile, true)) ?>
      </pre>
	  <?php 
			$data_x=-1500;
			$data_y=-1500;
			$data_rotate_z=0;
			$fql = "SELECT caption, src_big, like_info FROM photo WHERE pid IN (SELECT pid FROM photo_tag WHERE subject =me())";
			foreach($friendId as $fid){
				//echo "Friend Id: ".$fid."\n";
				$fql=$fql."AND pid IN (SELECT pid FROM photo_tag WHERE subject=".$fid.")";
			}
			$photos = $facebook->api(array(
                                   'method' => 'fql.query',
                                   'query' => $fql,
                                 ));
			//print_r($photos);
			foreach($photos as $pic):
			?>
			<div class="step" data-x="<?php echo $data_x;?>" data-z="<?php echo $data_y;?>" data-rotate-z="<?php echo $data_rotate_z;?>">
			<p><strong><?php echo $pic['caption'];?></strong> <br/>
			<img src="<?php echo $pic['src_big']; ?>" /></p>
			</div>
	<?php
		$data_x+=1500;
		$data_y+=1500;
		$data_rotate_z+=15;
		endforeach;	
	  ?>
    <?php } else { ?>
      <a href="<?php echo $loginUrl;?>">Login to Facebook</a>
    <?php } ?>
	</div>
    <div id="fb-root"></div>
    
<div class="hint">
    <p>Use a spacebar or arrow keys to navigate</p>
</div>
<script>
if ("ontouchstart" in document.documentElement) { 
    document.querySelector(".hint").innerHTML = "<p>Tap on the left or right to navigate</p>";
}
</script>

<script src="js/impress.js"></script>
<script>impress().init();</script>
</body>
</html>