jQuery( document ).ready(function() {

jQuery( ".search_by_post" ).change(function() {
    if ( jQuery( ".geodir-cat-list-tax" ).length ) {
        var postType = jQuery(this).val()
       jQuery( ".geodir-cat-list-tax" ).val(postType+"category"); 
       jQuery( ".geodir-cat-list-tax" ).change(); 
    }
});

jQuery("#showMap").click(function() {
        jQuery(".sd.archive.geodir-page aside#gd-sidebar-wrapper").css("visibility", "visible");
        jQuery("#showMap").css("display", "none");
        jQuery("#showSearch").css("display", "none");
        jQuery("#hideMap").css("display", "block");
    });

jQuery("#hideMap").click(function() {
        jQuery(".sd.archive.geodir-page aside#gd-sidebar-wrapper").css("visibility", "hidden");
        jQuery("#showMap").css("display", "block");
        jQuery("#showSearch").css("display", "block");
        jQuery("#hideMap").css("display", "none");
    });
 
jQuery("#showSearch").click(function() {
        jQuery(".sd.archive.geodir-page .geodir_advanced_search_widget").toggle();
    });

jQuery("#showMap").click(function() {
        jQuery(".sd.search.geodir-page aside#gd-sidebar-wrapper").css("visibility", "visible");
        jQuery("#showMap").css("display", "none");
        jQuery("#showSearch").css("display", "none");
        jQuery("#hideMap").css("display", "block");
    });

jQuery("#hideMap").click(function() {
        jQuery(".sd.search.geodir-page aside#gd-sidebar-wrapper").css("visibility", "hidden");
        jQuery("#showMap").css("display", "block");
        jQuery("#showSearch").css("display", "block");
        jQuery("#hideMap").css("display", "none");
    });
 
jQuery("#showSearch").click(function() {
        jQuery(".sd.search.geodir-page .geodir_advanced_search_widget").toggle();
    });

});