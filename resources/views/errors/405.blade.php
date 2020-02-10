
<style>
    #load{
        width:100%;
        height:100%;
        position:fixed;
        z-index:9999;
        background: #f0f0f0;
        padding-top: 10%;
    }
    .loader {
        border: 5px solid #f0f0f0;
        border-radius: 50%;
        border-top: 5px solid #0A74BC;
        border-bottom: 5px solid #0A74BC;
        width: 65px;
        height: 65px;
        -webkit-animation: spin 2s linear infinite;
        animation: spin 2s linear infinite;
        margin: 50px auto;
    }    @-webkit-keyframes spin {
             0% { -webkit-transform: rotate(0deg); }
             100% { -webkit-transform: rotate(360deg); }
         }    @keyframes spin {
                  0% { transform: rotate(0deg); }
                  100% { transform: rotate(360deg); }
              }
</style><div id="load" class="text-center">
    <img src="{{ asset('/') }}images/paytabs-logo.png" width="150" class="center">
    <h4 style="color: #0A74BC; margin-top: 40px;text-align: center;">{{__('جاري إتمام عملية الدفع، برجاء الانتظار ..')}}</h4>
    <div class="loader"></div>
    <form>
           <input type="hidden" id="payment_data"  value='<?php echo json_encode($_REQUEST);  ?> '>
    </form>

   
</div>
<!-- Loader -->
<script type="text/javascript" src="https://code.jquery.com/jquery-1.10.0.min.js"></script>

<script>
$(document).ready(function(){
    	var input_payment_data = document.getElementById("payment_data").value;
      console.log(input_payment_data)
        $.ajax({
        
            url: '{{ url("paytab/savedata") }}',
        
            type: "POST",
            data: {
                payment_data: input_payment_data,

            },
            success: function (data) {
                console.log(data)
                data = JSON.parse(data);
                data = data.data;

                if (data != '') {
                    if (data == 'success') {
                        // alert('There are no fields to generate a report');
                        window.location.replace('https://www.theqqa.com');
                        // $('.make-list').append(data);
                    }

                    //     else if (view == 'compact-view') {

                    //         $('.make-compact').append(data);
                    //     }
                    //     else if (view == 'grid-view') {

                    //         $('.make-grid').append(data);
                    //     }

                    //     $('#remove-row').remove();
                    //     $('#load-data').append(data);
                    // }
                    // else {
                    //     $('#btn-more').html("No Data");
                    // }
                }
            }
        });
    
});

</script>