{% extends 'templates/default.php' %}

{% block title %}Resource Database{% endblock %}
{% block content %}

<script>
    $(document).ready(function(){

        var uploadUrl ="{{ uploadUrl }}";

        $(document).ajaxError(function(event, jqXHR, settings, thrownError){
            alert(thrownError);
        });
        $("#resourcelist").hide();

        $("#search-button").on('click',function(event){
            searchString = $("#search-text-input").val();
            searchStringEncoded = encodeURIComponent(searchString);
            if (searchString.length > 1){
                // update and display list of schools
                $("#resourcelist").hide();
                $("li").remove("");
                var url = 'getdata/resources?sstr="'+searchStringEncoded+'"';
                $.getJSON(url, function(data){
                    data.forEach(function(resource){
                        listElement = '<li id="'+resource['id'] + '" resourceUrl="' + resource['url'] +'">'
                            +'<i class="fa fa-play"></i>&nbsp;'
                            +resource['title']
                            +'</li>';
                        $("#resourcelist").append(listElement);
                    });
                    $("li").on('click',function(){
                        resourceUrl = $(this).attr('resourceUrl');
                        window.open("http://"+resourceUrl);
                    });
                });
                $("#resourcelist").show();
            } else {
                // hide list of schools
                $("#resourcelist").hide();
                $("li").remove("");
            }
        });

        //form Submit action
        $("form").submit(function(evt){
            evt.preventDefault();
            var formData = new FormData($(this)[0]);
            $.ajax({
                url: uploadUrl,
                type: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                enctype: 'multipart/form-data',
                success: function (response) {
                    alert(response);
                }
            });
            return false; // Looks like this is not needed
        });

    });

</script>

<style>

    .scrollable-menu {
        height: auto;
        max-height:350px;
        overflow-x: hidden;
    }
</style>

<div class="w3-container" style="height:600px">
    <h3>Learning Resource Database</h3>
    <div class="w3-border" style='width:40%'>
        <button class ="w3-btn w3-light-grey w3-large" style="float: right" id="search-button" > <i class="fa fa-search"  ></i> </button>
        <div style="overflow: hidden">
            <input class="w3-input w3-border" type="text" id="search-text-input" placeholder="Search">
        </div>
    </div>
    <p></p>
    <ul class="w3-ul w3-border w3-hoverable scrollable-menu" id="resourcelist" style="width:40%">
    </ul>
    <p></p>
    <form class="w3-border" style='width:40%'>
        <button class ="w3-btn w3-light-grey w3-xlarge" style="float: right" id="upload-button" type="submit"> <i class="fa fa-upload"></i> </button>
        <div style="overflow: hidden">
            <input class="w3-input w3-border" type="file" name='fileToUpload' id="csv-file-for-upload">
        </div>
        <input type="hidden" name="{{ csrf_key }}" value="{{ csrf_token }}">
    </form>
    <p></p>
</div>

<p></p>
{% endblock %}
