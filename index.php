<?php
	include_once "config.php";
	if(isset($_POST['friends']) && isset($_POST['owner'])) {
		$u = $_POST['owner'];
		$friends=array();
		foreach($_POST['friends'] as $f)array_push($friends, "$f");
		//print_r($friends);
		$friends= implode(",", $friends);
		$err = mysql_query("INSERT INTO profile (id, owner, friends)
VALUES (NULL, '$u','$friends')");
$id=mysql_insert_id();
//echo $err;
	}

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
  echo "<a href='".$loginUrl."'>Login To Facebook</a><br />";
}
?>
<html  lang="en" xmlns:fb="http://www.facebook.com/2008/fbml">
<head>
<title>Memories Alive: Share Memories Forever</title>
<link href="css/main.css" rel="stylesheet"/>
  <link rel="stylesheet" href="chosen/chosen.css" />

</head>
<body>
<div id="header">
<h1>Memories Alive</h1>
<p>Welcome <?php $name = $facebook->api('me'); echo $name['name'];
?></p>
</div>
<form action="" method="POST">
<input type="hidden" name="owner" value="<?php echo $user;?>" />
<h2>Multiple Select</h2>
    <div class="side-by-side clearfix">
      <div>
        <em>Select Friends</em>        
        <select data-placeholder="Choose a Country..." class="chzn-select" multiple style="width:350px;" tabindex="4" name="friends[]">
          <option value=""></option>
		  <?php
			$friends = $facebook->api("/me/friends");
	foreach($friends['data'] as $fs){
		echo "<option value='".$fs['id']."'>".$fs['name']."</option>,";
		}
		  ?>
        </select>
      </div>
    </div>
	<input type="submit" value="Create Show" />
	</form>
	<?php if(isset($id)) echo "<a href='slideshow.php?id=$id'>/slideshow.php?id=$id</a><br />"
	?>
</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js" type="text/javascript"></script>
<script src="chosen/chosen.jquery.js" type="text/javascript"></script>
<script type="text/javascript"> $(".chzn-select").chosen(); $(".chzn-select-deselect").chosen({allow_single_deselect:true}); </script>
</html>