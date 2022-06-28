$(function() {
    var options = {
        widgets: ['filter'],
        widgetOptions : {
            filter_ignoreCase  : true,
            filter_startsWith  : false,
        }
    };
    $("#client_table").tablesorter(options);
    $("#domain_names_table").tablesorter(options);
    $("#server_table").tablesorter(options);
    $("#sites_table").tablesorter(options);
    $("#ads_table").tablesorter(options);
    $("#social_table").tablesorter(options);
    $("#cc_table").tablesorter(options);
    $("#mail_table").tablesorter(options);
    $(".tablesorter-filter.disabled").hide();
});
