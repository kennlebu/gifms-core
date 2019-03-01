<html>

<head>
</head>

<body style="font-family: monospace;">
    <main>
        <div>
            To Whom It May Concern <br/>
            {{$addressed_to}}
            <br/>
            <br/>
            <p>
                @if ($payable_type=='advances') 
                    Your Advance Ref.: {{$payable->ref}} for {{$payable->expense_desc}} of amount {{$payable->currency->currency_name}} {{number_format($payable->total, 2)}} has been paid successfully. 
                @elseif ($payable_type=='claims') 
                    Your Claim Ref.: {{$payable->ref}} for {{$payable->expense_desc}} of amount {{$payable->currency->currency_name}} {{number_format($payable->total, 2)}} has been paid successfully. 
                @elseif ($payable_type=='mobile_payments') 
                    Your Mobile Payment Ref.: {{$payable->ref}} for {{$payable->expense_desc}} of amount {{$payable->currency->currency_name}} {{number_format($payable->totals, 2)}} has been paid successfully. 
                @elseif ($payable_type=='invoices') 
                    Your Invoice Ref.: {{$payable->external_ref}} for {{$payable->expense_desc}} of amount {{$payable->currency->currency_name}} {{number_format($payable->total, 2)}}
                    has been paid successfully. 
                    @if(!empty($payable->withholding_tax) && !empty($payable->withholding_vat))
                    Net amount paid is {{$payment->net_amount}} and tax of 
                        @if(!empty($payable->withholding_tax)) {{$payable->withholding_tax}} (Income tax), @endif 
                        @if(!empty($payable->withholding_tax)) {{$payable->withholding_vat}} (VAT)@endif  has been withheld. 
                    @endif
                @endif
            </p>
            <p>Should you have any questions, or queries on the above, please do not hesitate to get in touch with us via:<br/><br/> 3rd flr, Timau Plaza, Arwings Kodhek Road,<br/> P O Box 2011-00100 Nairobi, Kenya<br/> (t) : 254 20 514 3100/5<br/> (e) :
                <a href="mailto:jayuma@clintonhealthaccess.org">jayuma@clintonhealthaccess.org<a/><br/>
<a href="https://www.clintonhealthaccess.org">www.clintonhealthaccess.org</a>
            </p><br/> Best regards,<br/><br/>
            <em>Clinton Health Access Initiative (Kenya) - Finance Team</em><br/><br/><br/>
            <br/>

        </div>
        <div>
            383 Dorchestor Avenue. Suite 400. Boston MA 02127. Tel 617-774-0220<br/>
            <strong>Clinton Health Access Initiative</strong>

        </div>
    </main>
</body>

</html>