<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>Website | {% block title %} {% endblock %}</title>
		<link rel="icon" type="/image/x-icon" href="images/favicon.ico"></link>
		<script src="https://use.fontawesome.com/3ea26fd8d9.js"></script>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="http://www.w3schools.com/lib/w3.css">

        <!-- Add the slick-theme.css if you want default styling -->
        <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/gh/kenwheeler/slick@1.8.1/slick/slick.css"/>
        <!-- Add the slick-theme.css if you want default styling -->
        <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/gh/kenwheeler/slick@1.8.1/slick/slick-theme.css"/>
        <link rel="stylesheet" href="askeddy.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js" type="text/javascript"></script>
        <script type="text/javascript" src="//cdn.jsdelivr.net/gh/kenwheeler/slick@1.8.1/slick/slick.min.js"></script>
	</head>
	<body class="w3-container" >
		<header class="w3-row w3-orange">
		
			<div class="w3-container w3-orange w3-col l8">
			{% if auth %}
				<h2>
				<div class="w3-dropdown-hover w3-orange"> 
				<i class="fa fa-bars" style="padding-right:20px;"> </i>
					<div class="w3-dropdown-content w3-bar-block w3-border">
					<a href="{{ urlFor('user.home') }}?id={{ auth.id }}" class="w3-bar-item w3-button w3-large">
						<i class="fa fa-home" style="padding-right:20px;"></i>Home</a>
					<a href="{{ urlFor('user.profile') }}?id={{ auth.id }}" class="w3-bar-item w3-button w3-large">
						<i class="fa fa-id-card" style="padding-right:20px;"></i>Account</a>
				{% if auth.isAdmin() %}
					<a href="{{ urlFor('admin.x') }}" class="w3-bar-item w3-button w3-large">
                        <i class="fa fa-eye" style="padding-right:20px;"></i>Admin</a>
					<a href="{{ urlFor('user.all') }}" class="w3-bar-item w3-button w3-large">
						<i class="fa fa-user-circle" style="padding-right:20px;"></i>All users</a>
					<a href="{{ urlFor('uploadfile') }}" class="w3-bar-item w3-button w3-large">
						<i class="fa fa-cloud-upload" style="padding-right:20px;"></i>Upload file</a>
                    <a href="{{ urlFor('browse') }}" class="w3-bar-item w3-button w3-large">
                        <i class="fa fa-book" style="padding-right:20px;"></i>Resources</a>
                    <a href="{{ urlFor('template') }}" class="w3-bar-item w3-button w3-large">
						<i class="fa fa-snowflake-o" style="padding-right:20px;"></i>Templates</a>
				{% endif %}
					  <a href="{{ urlFor('logout') }}" class="w3-bar-item w3-button w3-large">
						<i class="fa fa-window-close" style="padding-right:20px;"></i>Log out</a>
					</div>				
				</div>
				{{ auth.getFullNameOrUsername() }}
				</h2>
			{% else %}
			<p></p>
				<a href="{{ urlFor('login') }}" class="w3-button w3-light-green w3-border w3-border-black" style="padding-left:20px;">Log in</a>
				<a href="{{ urlFor('register') }}" class="w3-button w3-orange w3-border w3-border-black" style="padding-left:20px;">Sign up</a>
			{% endif %}
			
			</div>

			<div class="w3-container w3-col l4">
			<h2> <img src="images/askeddy-green-disc.PNG" width=12% height=12%></img>
			<SPACER TYPE=HORIZONTAL SIZE=50>AskEddy.co.uk</h2>
			</div>

			<p></p>
		</header>
		{% include 'templates/partials/messages.php' %}
        <p></p>
		
		
		{% block content %} {% endblock %}
		<p></p>
		<footer class="w3-container w3-orange">
			<h5>(c) Edanalytics Ltd 2016 - 2017.</h5>
			<p></p>
		</footer>
	</body>
</html>