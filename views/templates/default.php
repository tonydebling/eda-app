<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>Website | {% block title %} {% endblock %}</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="http://www.w3schools.com/lib/w3.css">		
		<body class="w3-container">
			<header class="w3-container w3-teal">
			  <h1>edanalytics.co.uk</h1>
			</header>
			{% include 'templates/partials/messages.php' %}
			{% include 'templates/partials/navigation.php' %}
			{% block content %} {% endblock %}	
			<footer class="w3-container w3-teal">
				<h5>(c) Edanalytics Ltd 2016.</h5>
				<p></p>
			</footer>
		</body>

	</head>
</html>