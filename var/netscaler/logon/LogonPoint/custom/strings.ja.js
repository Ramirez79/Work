$.ctxsAjax({
	type: 'GET',
	url: "/logon/LogonPoint/custom/strings.ja.json",
	dataType: 'json',
	async: false,
	success: custom_strings_initialize,
	error: function(responseData, textStatus, XMLHttpRequest) {
	},
	refreshSession: true
});
function custom_strings_initialize(responseData, textStatus, XMLHttpRequest){
	var custom_strings_json = XMLHttpRequest.responseJSON;
	$.localization.customStringBundle("ja", custom_strings_json);
}
