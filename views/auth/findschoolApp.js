var app = angular.module("findschoolApp", []);

// Create the instant search filter


app.filter('searchFor', function(){

	// All filters must return a function. The first parameter
	// is the data that is to be filtered, and the second is an
	// argument that may be passed with a colon (searchFor:searchString)

	return function(arr, searchString){

		if(!searchString){
			return arr;
		}

		var result = [];

		searchString = searchString.toLowerCase();

		// Using the forEach helper method to loop through the array
		angular.forEach(arr, function(x){

			if(x.name.toLowerCase().indexOf(searchString) !== -1){
				result.push(x);
			}

		});

		return result;
	};
});
