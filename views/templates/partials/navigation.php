{% if auth %}
	Hello,	{{ auth.getFullNameOrUsername() }}
{% endif %}
{% if auth.isAdmin() %}
	(Administrator)
{% endif %}

<ul class="w3-navbar w3-teal">
	<li><a href="{{ urlFor('home') }}">Home</a></li>
	
	{% if auth %}
		<li><a href="{{ urlFor('logout') }}">Logout</a></li>
		<li><a href="{{ urlFor('user.profile', {username: auth.username}) }}">Your profile</a></li>
		<li><a href="{{ urlFor('changepassword') }}">Change password</a></li>
		{% if auth.isAdmin() %}
			<li><a href="{{ urlFor('admin.x') }}">Admin area</a></li>
			<li><a href="{{ urlFor('user.all') }}">All users</a></li>
			<li><a href="{{ urlFor('uploadfile') }}">Upload file</a></li>
		{% endif %}
	{% else %}

		<li><a href="{{ urlFor('register') }}">Register</a></li>
		<li><a href="{{ urlFor('login') }}">Login</a></li>
	{% endif %}

</ul>