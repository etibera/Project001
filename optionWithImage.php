<!DOCTYPE html>
<html>
<head>
<title>Page Title</title>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.rawgit.com/prashantchaudhary/ddslick/master/jquery.ddslick.min.js" ></script>
</head>
<body>

<h1>This is a Heading</h1>
<p>This is a paragraph.</p>
  <select id="demoCallback">
        <option value="0" data-imagesrc="https://i.imgur.com/XkuTj3B.png"
            data-description="Description with Facebook">Facebook</option>
        <option value="1" data-imagesrc="https://i.imgur.com/8ScLNnk.png"
            data-description="Description with Twitter">Twitter</option>
        <option value="2" selected="selected" data-imagesrc="https://i.imgur.com/aDNdibj.png"
            data-description="Description with LinkedIn">LinkedIn</option>
        <option value="3" data-imagesrc="https://i.imgur.com/kFAk2DX.png"
            data-description="Description with Foursquare">Foursquare</option>
    </select>
    <span id="selectedval"></span>
    <div id="myDropdown"></div>
    <script type="text/javascript">
        $('#demoCallback').ddslick({
            width: 300,
            imagePosition: "left",
            selectText: "Select your favorite social network",
            onSelected: function(data){
               console.log(data); 
            }
});
    </script>
</body>
</html>