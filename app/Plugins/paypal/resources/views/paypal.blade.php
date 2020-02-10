
<div class="row payment-plugin" id="paypalPayment" style="display: none;">
    <div class="col-md-8 col-sm-12 box-center center mt-4 mb-0">
        <div class="row">
            
            <div class="col-xl-12 text-center">
                <img class="img-fluid" src="{{ url('images/paypal/payment.png') }}" title="{{ trans('paypal::messages.Payment with Paypal') }}">
            </div>
            
            <!-- ... -->
        
        </div>
    </div>
</div>
<div class="row payment-plugin" id="STCPayment" style="display: none;">
    <div class="col-md-8 col-sm-12 box-center center mt-4 mb-0">
        <div class="row">

            <div class="col-xl-12 text-center">
                <img class="img-fluid" src="{{ url('images/stc.png') }}" title="{{ trans('paypal::messages.Payment with Paypal') }}">
            </div>

            <!-- ... -->

        </div>
    </div>
</div>
<div class="row payment-plugin" id="SadadPayment" style="display: none;">
    <div class="col-md-8 col-sm-12 box-center center mt-4 mb-0">
        <div class="row">

            <div class="col-xl-12 text-center">
                <img class="img-fluid" src="{{ url('images/sadad.png') }}" title="{{ trans('paypal::messages.Payment with Paypal') }}">
            </div>

            <!-- ... -->

        </div>
    </div>
</div>
<div class="row payment-plugin" id="VisaPayment" style="display: none;">
    <div class="col-md-8 col-sm-12 box-center center mt-4 mb-0">
        <div class="row">

            <div class="col-xl-12 text-center">
                <img class="img-fluid" src="{{ url('images/visa.png') }}" title="{{ trans('paypal::messages.Payment with Paypal') }}">
            </div>

            <!-- ... -->

        </div>
    </div>
</div>
<div class="row payment-plugin" id="bankPayment" style="display: none;">
    <div class="col-md-8 col-sm-12 box-center center mt-4 mb-0">
        <div class="row">

            <div class="col-xl-12 text-center">
                <img class="img-fluid" src="{{ url('images/bank_trans.png') }}" title="{{ trans('paypal::messages.Payment with Paypal') }}">
            </div>

            <!-- ... -->

        </div>
    </div>
</div>

@section('after_scripts')
    @parent
    <script>
        $(document).ready(function ()
        {
            var selectedPackage = $('input[name=package_id]:checked').val();
            var packagePrice = getPackagePrice(selectedPackage);
            var paymentMethod = $('#paymentMethodId').find('option:selected').data('name');
    
            /* Check Payment Method */
            checkPaymentMethodForPaypal(paymentMethod, packagePrice);
            
            $('#paymentMethodId').on('change', function () {
                paymentMethod = $(this).find('option:selected').data('name');
                checkPaymentMethodForPaypal(paymentMethod, packagePrice);
            });
            $('.package-selection').on('click', function () {
                selectedPackage = $(this).val();
                packagePrice = getPackagePrice(selectedPackage);
                paymentMethod = $('#paymentMethodId').find('option:selected').data('name');
                checkPaymentMethodForPaypal(paymentMethod, packagePrice);
            });
    
            /* Send Payment Request */
            $('#submitPostForm').on('click', function (e)
            {
                e.preventDefault();
        
                paymentMethod = $('#paymentMethodId').find('option:selected').data('name');
                
                if (paymentMethod != 'paypal' || packagePrice <= 0) {
                    return false;
                }
    
                $('#postForm').submit();
        
                /* Prevent form from submitting */
                return false;
            });
        });

        function checkPaymentMethodForPaypal(paymentMethod, packagePrice)
        {
            if  ($('#paymentMethodId').val() == 1 && packagePrice > 0) {
                $('#paypalPayment').show();
                $('#bankPayment').hide();
                $('#SadadPayment').hide();
                $('#VisaPayment').hide();
                $('#STCPayment').hide();
            }
           else if ($('#paymentMethodId').val() == 2 && packagePrice > 0) {
                $('#bankPayment').show();
                $('#paypalPayment').hide();
                $('#SadadPayment').hide();
                $('#VisaPayment').hide();
                $('#STCPayment').hide();
            }
            else if ($('#paymentMethodId').val() == 3 && packagePrice > 0) {
                $('#SadadPayment').show();
                $('#paypalPayment').hide();
                $('#bankPayment').hide();
                $('#VisaPayment').hide();
                $('#STCPayment').hide();
            }
            else if ($('#paymentMethodId').val() == 4 && packagePrice > 0) {
                $('#VisaPayment').show();
                $('#paypalPayment').hide();
                $('#bankPayment').hide();
                $('#SadadPayment').hide();
                $('#STCPayment').hide();
            }
            else if ($('#paymentMethodId').val() == 5 && packagePrice > 0)  {
                $('#STCPayment').show();
                $('#paypalPayment').hide();
                $('#bankPayment').hide();
                $('#VisaPayment').hide();
                $('#SadadPayment').hide();
            }
            else {
                $('#STCPayment').hide();
                $('#paypalPayment').hide();
                $('#bankPayment').hide();
                $('#VisaPayment').hide();
                $('#SadadPayment').hide();
            }
        }
    </script>
@endsection
