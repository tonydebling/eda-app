{% extends 'templates/default.php' %}

{% block title %}Register{% endblock %}
{% block content %}
	<div>
	<h1>{{ name }}</h1>
	<a href="{{ urlFor('register') }}">Click here if this is not your school</a>
	<p></p>
	</div>
	<form action="{{ urlFor('register.post')}}" method="post" autocomplete="off" class="w3-container w3-card" style="width:50%">
		<p></p>
		<div>
			<label for"username">Username</label>
			<input type="text" name="username" id="username" placeholder="Your school email address before the @"class="w3-input"
			{% if request.post('username') %} value={{ request.post('username') }}{% endif %}>
			{% if errors.first('email') %}<span class="w3-text-red"> {{ errors.first('email') }}</span> {% endif %}
		</div>
		<div>
			<label for"password">Password</label>
			<input type="password" name="password" id="password" class="w3-input">
			{% if errors.first('password') %}<span class="w3-text-red"> {{ errors.first('password') }}</span> {% endif %}
		</div>
		<div>
			<label for"password_confirm">Confirm password</label>
			<input type="password" name="password_confirm" id="password_confim" class="w3-input">
			{% if errors.first('password_confirm') %}<span class="w3-text-red"> {{ errors.first('password_confirm') }}</span> {% endif %}
		</div>
		<p></p>
		<div>
			<input type="submit" value="Sign up" >
		</div>
		<p></p>
		<input type="hidden" name="{{ csrf_key }}" value="{{ csrf_token }}">
		<input type="hidden" name="school_id" value="{{ school_id }}">
		<input type="hidden" name="school_domain" value="{{ school_domain }}">
		<input type="hidden" name="name" value="{{ name }}">
	</form>
{% endblock %}
	