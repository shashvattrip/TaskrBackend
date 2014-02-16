
$('#test123').click(function()
{
	$("#customDatePicker").datepicker();
	console.log('Clicked!');
});

$("#customDatePicker").datepicker(
{
	buttonImage: '../../images/clock.png',
	buttonImageOnly: true,
	changeMonth: true,
	changeYear: true,
	showOn: 'both',
});