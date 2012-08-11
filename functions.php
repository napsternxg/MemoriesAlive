<html lang="en" xmlns:fb="http://www.facebook.com/2008/fbml">
<head>
    <title>Home Page</title>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script>
$(function() {

    //Facebook Connection
    FB.init({
        //Put app ID here
        appId: 'MYAPPID', 
        cookie: true, 
        xfbml: true, 
        status: true });

    //Function to output API call to console
    function log( message ) {
        $( "<div/>" ).text( message ).prependTo( "#log" );
        $( "#log" ).attr( "scrollTop", 0 );
    }

    //Check if logged in to FB
    FB.getLoginStatus(function (response) {
        if (response.session) {

            //Get Access Token
            var accessToken = response.session.access_token;
            //Get Callback URL for API call
            var tokenUrl = "https://graph.facebook.com/me/friends?access_token=" + accessToken + "&callback=?";

            //Output callback URL to 'div#access'
            $('#access').append('<p>' + tokenUrl + '</p>');

            $( "#name" ).autocomplete({
                source: function( request, response ) {
                    //Make call to Graph API
                    $.ajax({
                        //Use tokenUrl for callback URL
                        url: tokenUrl,
                        //Use JSONP for external JSON callback
                        dataType: "jsonp",
                        data: {
                            featureClass: "P",
                            style: "full",
                            maxRows: 12,
                            name_startsWith: function () { return $("#name").val() }
                        },
                        //Output request data to console
                        success: function(results){
                            console.log(results);
                        }
                    });
                },
                minLength: 2
            });

        } else {
            // Show Login....
        }
    });
});
</script>
</head>
<body></body>
</html>