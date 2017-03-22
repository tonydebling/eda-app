<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>Website | {% block title %} {% endblock %}</title>
		<link rel="icon" type="image/x-icon" href="images/favicon.ico"></link>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="http://www.w3schools.com/lib/w3.css">		
	</head>
	<body class="w3-container">
		<header class="w3-container w3-orange">
		  <h1><img src="images/askeddy-green-disc.png" onerror="this.src='../images/askeddy-green-disc.png'" width=5% height=5% />
		  <SPACER TYPE=HORIZONTAL SIZE=50>edanalytics.co.uk</h1>
		</header>
		{% include 'templates/partials/messages.php' %}
		{% include 'templates/partials/navigation.php' %}
		{% block content %} {% endblock %}	
		<footer class="w3-container w3-orange">
			<h5>(c) Edanalytics Ltd 2016 - 2017.</h5>
			<p></p>
		</footer>
	</body>
</html>