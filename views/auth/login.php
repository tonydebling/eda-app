{% extends 'templates/default.php' %}

{% block title %}Login{% endblock %}
{% block content %}
	<div>
	<h1>{{ name }}</h1>
	<a href="{{ urlFor('login') }}">Click here if this is not your school</a>
	<p></p>
	</div>
	<p></p>
	<form action="{{ urlFor('login.post')}}" method="post" autocomplete="off" class="w3-container w3-card" style="width:50%">
		<p></p>
		<div>
			<label>Username</label>
			<input type="text" name="identifier" id="identifier" placeholder="Your school email address before the @"class="w3-input"
			{% if request.post('identifier') %} value={{ request.post('identifier') }}{% endif %}>
			{% if errors.first('identifier') %} <span class="w3-text-red"> {{ errors.first('identifier') }} </span> {% endif %}
		</div>
		<div>
			<label>Password</label>
			<input type="password" name="password" id="password" class="w3-input">
			{% if errors.first('password') %} <span class="w3-text-red"> {{ errors.first('password') }} </span> {% endif %}
		</div>
		<p></p>
		<div>
			<input type="submit" value="Login" >
		</div>
		<p></p>
		<a href="{{ urlFor('recoverpassword') }}">Forgotten password?</a>
		<input type="hidden" name="school_id" value="{{ school_id }}">
		<input type="hidden" name="school_domain" value="{{ school_domain }}">
		<input type="hidden" name="name" value="{{ name }}">
		<input type="hidden" name="{{ csrf_key }}" value="{{ csrf_token }}">
	</form>
{% endblock %}
