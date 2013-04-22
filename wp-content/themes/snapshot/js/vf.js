jQuery(document).ready(function () {
 jQuery( "form#post #publish" ).hide();
 jQuery( "form#post #publish" ).after("<input type=\'button\' value=\'Publish/Update\' class=\'sb_publish button-primary\' /></br><span class=\'sb_js_errors\'></span>");

 jQuery( ".sb_publish" ).click(function() {
 var error = false;
//js validation here

if( jQuery( "form#post #title" ).val() == "" ||
	jQuery( "form#post #simple_fields_fieldgroups_1_3_0" ).val() == "" ||
    jQuery( "form#post #simple_fields_fieldgroups_1_1_0" ).val() == "" ||
    jQuery( "form#post #image1" ).length == 0 )
    error = true;


 if (!error) {
 jQuery( "form#post #publish" ).click();
 } else {
 jQuery(".sb_js_errors").text("Please fill out all the necessary fields, and take a snapshot of 3D model.");
 }
 });
});

