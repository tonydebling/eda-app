<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>Website | {% block title %} {% endblock %}</title>
		<link rel="icon" type="image/x-icon" href="images/favicon.ico"></link>
		<link rel="icon" type="image/x-icon" href="../images/favicon.ico"></link>
		<script src="https://use.fontawesome.com/3ea26fd8d9.js"></script>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="http://www.w3schools.com/lib/w3.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js" type="text/javascript"></script>
	</head>
	<body class="w3-container" >
		<header class="w3-row w3-orange">
		
			<div class="w3-container w3-orange w3-col s9">
			{% if auth %}
				<h2>
				<div class="w3-dropdown-hover w3-orange"> 
				<i class="fa fa-bars" style="padding-right:20px;"> </i>
					<div class="w3-dropdown-content w3-bar-block w3-border">
					  <a href="{{ urlFor('user.home', {user_id: auth.id}) }}" class="w3-bar-item w3-button w3-large">
						<i class="fa fa-home" style="padding-right:20px;"></i>Home</a>
					  <a href="{{ urlFor('user.profile', {user_id: auth.id}) }}" class="w3-bar-item w3-button w3-large">
						<i class="fa fa-id-card" style="padding-right:20px;"></i>Account</a>
					  <a href="{{ urlFor('logout') }}" class="w3-bar-item w3-button w3-large">
						<i class="fa fa-window-close" style="padding-right:20px;"></i>Log out</a>
					</div>				
				</div>
				{{ auth.getFullNameOrUsername() }}
				</h2>
			{% else %}
			<p></p>
				<a href="{{ urlFor('stulogin') }}" class="w3-button w3-light-green w3-border w3-border-black" style="padding-left:20px;">Log in</a>
				<a href="{{ urlFor('register') }}" class="w3-button w3-orange w3-border w3-border-black" style="padding-left:20px;">Sign up</a>
			{% endif %}
			
			</div>

			<div class="w3-container w3-col s3">
			<h2> <img src="images/askeddy-green-disc.PNG" onerror="this.src='../images/askeddy-green-disc.PNG'" width=10% height=10%></img>
			<SPACER TYPE=HORIZONTAL SIZE=50>AskEddy.co.uk</h2>
			</div>

			<p></p>
		</header>
		{% include 'templates/partials/messages.php' %}
		{% include 'templates/partials/navigation.php' %}
		{% block content %} {% endblock %}
		<p></p>
		<footer class="w3-container w3-orange">
			<h5>(c) Edanalytics Ltd 2016 - 2017.</h5>
			<p></p>
		</footer>
	</body>
</html>