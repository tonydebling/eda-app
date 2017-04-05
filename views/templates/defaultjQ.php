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
	<body class="w3-container">
		<header class="w3-container w3-orange">
		  <h1><img src="images/askeddy-green-disc.PNG" onerror="this.src='../images/askeddy-green-disc.PNG'" width=5% height=5% />
		  <SPACER TYPE=HORIZONTAL SIZE=50>AskEddy.co.uk</h1>
		</header>
		{% include 'templates/partials/messages.php' %}
		{% include 'templates/partials/navigation.php' %}
		{% block content %} {% endblock %}	
		<footer class="w3-container w3-orange">
			<h5><i class=" fa fa-copyright"></i> Edanalytics Ltd 2016 - 2017.</h5>
			<p></p>
		</footer>
	</body>
</html>