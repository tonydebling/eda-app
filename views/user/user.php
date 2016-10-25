{% extends 'templates/default.php' %}

{% block title %}{{ user.getFullNameOrUsername }}{% endblock %}
{% block content %}
	<h2>{{ user.username }}</h2>
{% endblock %}
	