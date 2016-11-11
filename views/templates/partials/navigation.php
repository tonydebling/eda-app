{% if auth %}
	Hello,	{{ auth.getFullNameOrUsername() }}
{% endif %}


<ul class="w3-navbar w3-teal">
	
	
	{% if auth %}
		<li><a href="{{ urlFor('user.home', {user_id: auth.id}) }}">Home</a></li>
		<li><a href="{{ urlFor('logout') }}">Logout</a></li>
		<li><a href="{{ urlFor('user.profile', {user_id: auth.id}) }}">Your profile</a></li>
		{% if auth.isStudent() == false %}
			<li><a href="{{ urlFor('changepassword') }}">Change password</a></li>
		{% endif %}
		{% if auth.isAdmin() %}
			<li><a href="{{ urlFor('admin.x') }}">Admin area</a></li>
			<li><a href="{{ urlFor('user.all') }}">All users</a></li>
			<li><a href="{{ urlFor('uploadfile') }}">Upload file</a></li>
		{% endif %}
	{% else %}
		<li><a href="{{ urlFor('home') }}">Home</a></li>
		<li><a href="{{ urlFor('stulogin') }}">Student Login</a></li>
		<li><a href="{{ urlFor('login') }}">Staff Login</a></li>
		<li><a href="{{ urlFor('register') }}">Staff Register</a></li>
	{% endif %}

</ul>