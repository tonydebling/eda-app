{% extends 'templates/default.php' %}

{% block title %}Upload file{% endblock %}
{% block content %}
	<form action="{{ urlFor('uploadfile.post')}}" method="post" enctype="multipart/form-data" autocomplete="off">
		<div>
			<label for"fileType">File type</label>
			<select name="fileType">
				<option value="students">Students</option>
				<option value="sets">Classes</option>
				<option value="studentsets">Student Classes</option>		
			</select>
		</div>
		<div>
            <p>
			<label>Select image to upload:</label>
			<input type="file" name="fileToUpload" id="fileToUpload">
			</p>
		</div>
		<div>
			<input type="submit" value="Upload" >
		</div>
		<input type="hidden" name="{{ csrf_key }}" value="{{ csrf_token }}">
	</form>
{% endblock %}
