// inbox controller

myapp.controller('InboxCtrl', function($scope,SimulateEmails)
{
	$scope.helloWorld="Shashvat rocks!";
	console.log($scope.helloWorld);
	console.log($scope.test);
	$scope.AllEmails=SimulateEmails;
	console.log($scope.AllEmails);

});