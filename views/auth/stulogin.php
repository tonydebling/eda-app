{% extends 'templates/default.php' %}

{% block title %}Login{% endblock %}
{% block content %}
	<div>
	<h1>{{ name }}</h1>
	URN: {{ school_id }} <br>
	<a href="{{ urlFor('stulogin') }}">Click if this is not your school?</a>
	<hr>
	</div>
	<form action="{{ urlFor('stulogin.post')}}" method="post" autocomplete="off">
		<div>
			<label for"first_name">First name</label>
			<input type="text" name="first_name" id="first_name" {% if request.post('first_name') %} value={{ request.post('first_name') }}{% endif %}>
			{% if errors.first('first_name') %} {{ errors.first('first_name') }} {% endif %}
		</div>
		<div>
			<label for"last_name">Last name</label>
			<input type="text" name="last_name" id="last_name" {% if request.post('last_name') %} value={{ request.post('last_name') }}{% endif %}>
			{% if errors.first('last_name') %} {{ errors.first('last_name') }} {% endif %}
		</div>
		<div>
			<label for"date_of_birth">Date of birth</label>
			<input type="date" name="date_of_birth" id="date_of_birth" {% if request.post('date_of_birth') %} value={{ request.post('date_of_birth') }}{% endif %}>
			{% if errors.first('date_of_birth') %} {{ errors.first('date_of_birth') }} {% endif %}
		</div>
		<div>
			<input type="submit" value="Sign in" >
		</div>
		<hr>
		<a href="{{ urlFor('login') }}">Click here if you are not a student?</a>
		<input type="hidden" name="{{ csrf_key }}" value="{{ csrf_token }}">
		<input type="hidden" name="school_id" value="{{ school_id }}">
		<input type="hidden" name="name" value="{{ name }}">
		</form>
{% endblock %}
