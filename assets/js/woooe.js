jQuery(document).ready(function(){
    
    /*
     * Add datepicker to start and end date boxes.
     */
    jQuery('.woooe-datepicker').datepicker({ dateFormat : "dd-mm-yy" });

    /*
     * 
     * @param Object params
     * @returns Void
     */
    var getReport = function( params ){

        jQuery.post(ajaxurl, params, function(response){

            var total_records = parseInt(response.data.total_records);
            var chunk_size = parseInt(response.data.chunk_size);
            var offset = parseInt(response.data.offset);

            if( (total_records - (chunk_size * ++offset)) > 0 ){
                ++response.data.offset;
                new getReport(response.data);
            }
        });
    };

    /*
     * Fire ajax request for order export
     */
    jQuery('#woooe_field_export_now').on('click', function(){

       var start_date = jQuery("#woooe_field_start_date").val();
       var end_date   = jQuery("#woooe_field_end_date").val();

       var data = {
         action: 'woooe_get_report',
         startDate : start_date,  
         endDate : end_date,
       };

        jQuery.post( ajaxurl, data, function(response){

            if( response.success === true ){
                if(parseInt(response.data.total_records) > 0){
                    new getReport(response.data);
                }
            }
        });
       
    });
});