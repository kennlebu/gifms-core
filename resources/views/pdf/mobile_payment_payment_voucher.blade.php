<html>
<head>
    <style>
    @page { margin: 150px 50px 120px 50px; }
    header { position: fixed; top: -130px; left: 0px; right: 0px; background-color: white; height: 120px; }
    footer { position: fixed; bottom: -110px; left: 0px; right: 0px; background-color: #E4E8F3; height: 100px; }
    p { page-break-after: always; }
    p:last-child { page-break-after: never; }
    footer:after {
        content: "[Page : " counter(page) "]";
    }
    
    div.stamp.stamped-paid:after
    {
        content:"PAID";
        position:absolute;
        top:-10px;
        left:10px;
        z-index:1;
        font-family:Arial,sans-serif;
        -webkit-transform: rotate(-15deg); /* Safari */
        -moz-transform: rotate(-15deg); /* Firefox */
        -ms-transform: rotate(-15deg); /* IE */
        -o-transform: rotate(-15deg); /* Opera */
        transform: rotate(-15deg);
        font-size:40px;
        color:#4CAF50;
        background:#fff;
        border:solid 4px #4CAF50;
        padding:5px;
        border-radius:5px;
        zoom:1;
        filter:alpha(opacity=20);
        opacity:0.2;
        -webkit-text-shadow: 0 0 2px #4CAF50;
        text-shadow: 0 0 2px #4CAF50;
        box-shadow: 0 0 2px #4CAF50;
    }
</style>
</head>
<body style="font-family:arial;">
    @if($bank_transaction)
    <div class="stamp stamped-paid"></div>
    @endif
    <header><div><img style="width: 100%;height:120px;" src = "img/letter_head_top_1200x240.png"></div></header>
    <footer><div><img style="width: 100%;height:80px;" src = "img/letter_head_bottom_1200x125.png"></div></footer>
    <main>
        <div>
            <table style="width: 100%;font-size: smaller;" align="center" cellspacing="0">
                <tbody>
                    <tr>
                        <td style="text-align: center;" colspan="9"  height="30">
                            <span style="text-decoration: underline;">
                                <strong>
                                    <span style="color: #092d50; font-size: x-large; text-decoration: underline;">PAYMENT VOUCHER</span>
                                </strong>
                            </span>
                        </td>
                    </tr>
                    <tr><td colspan="9">&nbsp;</td></tr>



                    <tr>
                        <td style="border: 1px solid #000000; border-bottom:none;" colspan="2">
                            <span>Date:</span>
                        </td>
                        <td style="border: 1px solid #000000; border-bottom:none;" colspan="4" >
                            <span>Payee:</span>
                        </td>
                        <td colspan="3" bgcolor="#092d50" height="20">
                            <span style="color: #ffffff;">Voucher #:</span>
                        </td>
                    </tr>

                    <tr>
                        <td style="border: 1px solid #000000; border-top: 0px solid #c0c0c0;" colspan="2">
                            <strong style="float: left;">
                                @if(empty($mobile_payment->management_approval_at))
                                <span> - </span>
                                @else
                                <span>{{date('d F, Y', strtotime($mobile_payment->management_approval_at)) }}</span>
                                @endif
                            </strong>
                        </td>
                        <td style="border: 1px solid #000000; border-top: 0px solid #c0c0c0;" colspan="4" >
                            <strong>{{$vendor}}</strong>
                        </td>
                        <td colspan="3" bgcolor="#092d50" height="20">
                            <strong>
                                <span style="color: #ffffff;">{{$voucher_no}}</span>
                            </strong>
                        </td>
                    </tr> 


                    <tr><td colspan="9">&nbsp;</td></tr>


                    <tr>
                        <td style="text-align: center;" colspan="9"  ><span ><strong><span style="color: #092d50; font-size: medium;">Payment Details:</span></strong></span></td>
                    </tr>

                    <tr>
                        @if($bank_transaction)
                        <td style="border: 1px solid #000000; border-bottom: 0px solid #c0c0c0;" colspan="2">
                            <small><span>Mobile Payment #:</span></small>
                        </td>
                        <td style="border: 1px solid #000000; border-bottom: 0px solid #c0c0c0;" colspan="1">
                            <small><span>Bank Ref:</span></small>
                        </td>
                        @else
                        <td style="border: 1px solid #000000; border-bottom: 0px solid #c0c0c0;" colspan="3">
                            <small><span>Mobile Payment #:</span></small>
                        </td>
                        @endif
                        <td style="border: 1px solid #000000; border-bottom: 0px solid #c0c0c0;" colspan="3" >
                            <small>Journal Description:</small>
                        </td>
                        <td style="border: 1px solid #000000; border-bottom: 0px solid #c0c0c0;" colspan="2" >
                            <small><span>Amount ({{$mobile_payment->currency->currency_name}}):</span></small>
                        </td>
                        <td style="border: 1px solid #000000; border-bottom: 0px solid #c0c0c0;" colspan="1" >
                            <small><span>Payment Mode:</span></small>
                        </td> 
                    </tr>

                    <tr>
                        @if($bank_transaction)
                        <td style="border: 1px solid #000000; border-top: 0px solid #c0c0c0;" colspan="2">
                            <strong style="float: left;">
                                <span>{{$mobile_payment->ref}}</span>
                            </strong>
                        </td>
                        <td style="border: 1px solid #000000; border-top: 0px solid #c0c0c0;text-align:left;" colspan="1" >
                            <strong>{{$bank_transaction->bank_ref}}</strong>
                        </td>
                        @else
                        <td style="border: 1px solid #000000; border-top: 0px solid #c0c0c0;" colspan="3">
                            <strong style="float: left;">
                                <span>{{$mobile_payment->ref}}</span>
                            </strong>
                        </td>
                        @endif
                        <td style="border: 1px solid #000000; border-top: 0px solid #c0c0c0;text-align:left;" colspan="3" >
                            {{$mobile_payment->expense_desc}}
                        </td>
                        <td style="border: 1px solid #000000; border-top: 0px solid #c0c0c0;" colspan="2">
                            <strong>{{number_format($mobile_payment->totals,2)}}</strong>
                        </td>
                        <td style="border: 1px solid #000000; border-top: 0px solid #c0c0c0;" colspan="1" ><strong>Bulk MMTS</strong>
                        </td>
                    </tr>


                    <tr><td colspan="9">&nbsp;</td></tr>


                    <tr>
                        <td style="text-align: center;" colspan="9"  >
                            <span >
                                <strong>
                                    <span style="color:#092d50;font-size:medium;">
                                        Program and Account to be debited:
                                    </span>
                                </strong>
                            </span>
                        </td>
                    </tr>

                    <tr>
                        <td style="border: 1px solid #c0c0c0;" bgcolor="#092d50" colspan="2">
                            <strong>
                                <span style="color: #ffffff;">PROGRAM</span>
                            </strong>
                        </td>
                        <td style="border: 1px solid #c0c0c0;" bgcolor="#092d50" colspan="2">
                            <strong>
                                <span style="color: #ffffff;">ACCOUNT</span>
                            </strong>
                        </td>
                        <td style="border: 1px solid #c0c0c0;" bgcolor="#092d50" colspan="2">
                            <strong>
                                <span style="color: #ffffff;">PURPOSE</span>
                            </strong>
                        </td>
                        <td style="border: 1px solid #c0c0c0;" bgcolor="#092d50" colspan="2">
                            <strong>
                                <span style="color: #ffffff;">%</span>
                            </strong>
                        </td>
                        <td style="border: 1px solid #c0c0c0;" bgcolor="#092d50" colspan="1">
                            <strong>
                                <span style="color: #ffffff;">TOTAL({{$mobile_payment->currency->currency_name}})</span>
                            </strong>
                        </td>
                    </tr>
                    @php
                        $tot_perc = 0
                    @endphp
                    @php
                        $tot = 0
                    @endphp

                    @foreach ($mobile_payment->allocations as $key => $allocation)
                        
                        <tr>
                            <td style="border-top: 1px solid #c0c0c0; border-bottom: 1px solid #c0c0c0; border-left: 1px solid #000000; border-right: 1px solid #000000;" colspan="2" >
                                {{$allocation->project->project_code}}
                            </td>
                            <td style="border-top: 1px solid #c0c0c0; border-bottom: 1px solid #c0c0c0; border-left: 1px solid #000000; border-right: 1px solid #000000;"  colspan="2">
                                {{$allocation->account->account_name}}
                            </td>
                            <td style="border-top: 1px solid #c0c0c0; border-bottom: 1px solid #c0c0c0; border-left: 1px solid #000000; border-right: 1px solid #000000;"  colspan="2">
                                {{$allocation->allocation_purpose}}
                            </td>
                            <td style="border-top: 1px solid #c0c0c0; border-bottom: 1px solid #c0c0c0; border-left: 1px solid #000000; border-right: 1px solid #000000;"  colspan="2" align="right">
                                {{number_format($allocation->percentage_allocated,2)}} % 
                            </td>
                            <td style="border-top: 1px solid #c0c0c0; border-bottom: 1px solid #c0c0c0; border-left: 1px solid #000000; border-right: 1px solid #000000;"  colspan="1" bgcolor="#E4E8F3" align="right">
                                {{number_format($allocation->amount_allocated,2)}} 
                            </td>
                        </tr>

                        @php 

                            $tot_perc=$allocation->percentage_allocated+$tot_perc 

                        @endphp

                        @php 

                            $tot=$allocation->amount_allocated+$tot 

                        @endphp

                        @php 

                            $f = new NumberFormatter("en", NumberFormatter::SPELLOUT);
                            $tot_words =  ucwords($f->format($tot));

                        @endphp


                    @endforeach


                    <tr bgcolor="#F8F8F8" style="height:70px;">
                        <td style="vertical-align:middle;: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;" colspan="2" >
                            <strong>TOTAL</strong>
                        </td>
                        <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;"  colspan="4">
                            <strong>{{$mobile_payment->currency->currency_name}} {{$tot_words}} only.</strong>
                        </td>
                        <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;"  colspan="2" align="right">
                            <strong>{{number_format($tot_perc,2)}} %</strong>
                        </td>
                        <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000;"  colspan="1" align="right">
                            <strong>{{number_format($tot,2)}} </strong>
                        </td>
                    </tr>


                    <tr><td colspan="9">&nbsp;</td></tr> 
                    <tr><td colspan="9">&nbsp;</td></tr>

                    @foreach ($unique_approvals as $key => $approval)
                        @isset($approval->approver_id)
                        @if($approval->approval_level_id == 5)
                        <tr style="    height: 55px; ">
                            <td colspan="2">
                                <strong>REVIEW<br/><small>(Finance)</small></strong>                          
                            </td>
                            <td style="border-top: 1px solid #C0C0C0; border-bottom: 1px solid #C0C0C0; border-left: 1px solid #000000; border-right: 1px solid #000000;   vertical-align: top;  background-color: #f7f8fb;"  colspan="2">
                                <strong><small>Initials</small></strong><br/><br/>
                                @isset($approval->approver_id)
                                    <strong>{{$approval->approver->full_name}}</strong>
                                @endisset
                            </td>
                            <td style="border-top: 1px solid #C0C0C0; border-bottom: 1px solid #C0C0C0; border-left: 1px solid #000000; border-right: 1px solid #000000;  vertical-align: top;" colspan="3" >
                                <small> Signed:</small><br/>
                                <img  style="height:50px;width:148px;" alt="." src="{{asset('storage/signatures/signature'.$approval->approver_id.'.png')}}"></img>
                            </td>
                            <td style="border-top: 1px solid #C0C0C0; border-bottom: 1px solid #C0C0C0; border-left: 1px solid #000000; border-right: 1px solid #000000;   vertical-align: top;  background-color: #f7f8fb;"  colspan="2" >                  
                                    <small>Date</small><br/><br/><br/>          
                                <strong style="float: left;">
                                    <span>{{date('d F, Y', strtotime($approval->created_at)) }}</span>
                                </strong>
                            </td>
                        </tr>
                        @endif
                        @if($approval->approval_level_id == 2)
                        <tr style="    height: 55px; ">
                            <td colspan="2">
                                <strong>VERIFICATION<br/><small>(Program Manager)</small></strong>                          
                            </td>
                            <td style="border-top: 1px solid #C0C0C0; border-bottom: 1px solid #C0C0C0; border-left: 1px solid #000000; border-right: 1px solid #000000;   vertical-align: top;  background-color: #f7f8fb;"  colspan="2">
                                <strong><small>Initials</small></strong><br/><br/>
                                @isset($approval->approver_id)
                                    <strong>{{$approval->approver->full_name}}</strong>
                                @endisset
                            </td>
                            <td style="border-top: 1px solid #C0C0C0; border-bottom: 1px solid #C0C0C0; border-left: 1px solid #000000; border-right: 1px solid #000000;  vertical-align: top;" colspan="3" >
                                <small> Signed:</small><br/>
                                <img  style="height:50px;width:148px;" alt="." src="{{asset('storage/signatures/signature'.$approval->approver_id.'.png')}}"></img>
                            </td>
                            <td style="border-top: 1px solid #C0C0C0; border-bottom: 1px solid #C0C0C0; border-left: 1px solid #000000; border-right: 1px solid #000000;   vertical-align: top;  background-color: #f7f8fb;"  colspan="2" >                  
                                    <small>Date</small><br/><br/><br/>          
                                <strong style="float: left;">
                                    <span>{{date('d F, Y', strtotime($approval->created_at)) }}</span>
                                </strong>
                            </td>
                        </tr>
                        @endif
                        @if($approval->approval_level_id == 3)
                        <tr style="    height: 55px; ">
                            <td colspan="2">
                                <strong>APPROVAL<br/><small>(Finance Manager)</small><br/><br/></strong>                          
                            </td>
                            <td style="border-top: 1px solid #C0C0C0; border-bottom: 1px solid #C0C0C0; border-left: 1px solid #000000; border-right: 1px solid #000000;   vertical-align: top;  background-color: #f7f8fb;"  colspan="2">
                                <strong><small>Initials</small></strong><br/><br/>
                                @isset($approval->approver_id)
                                    <strong>{{$approval->approver->full_name}}</strong>
                                @endisset
                            </td>
                            <td style="border-top: 1px solid #C0C0C0; border-bottom: 1px solid #C0C0C0; border-left: 1px solid #000000; border-right: 1px solid #000000;  vertical-align: top;" colspan="3" >
                                <small> Signed:</small><br/>
                                <img  style="height:50px;width:148px;" alt="." src="{{asset('storage/signatures/signature'.$approval->approver_id.'.png')}}"></img>
                            </td>
                            <td style="border-top: 1px solid #C0C0C0; border-bottom: 1px solid #C0C0C0; border-left: 1px solid #000000; border-right: 1px solid #000000;   vertical-align: top;  background-color: #f7f8fb;"  colspan="2" >                  
                                    <small>Date</small><br/><br/><br/>          
                                <strong style="float: left;">
                                    <span>{{date('d F, Y', strtotime($approval->created_at)) }}</span>
                                </strong>
                            </td>
                        </tr>
                        @endif
                        @if($approval->approval_level_id == 4)
                        <tr style="height: 55px; ">
                            <td colspan="2">
                                <strong>AUTHORIZATION<br/><small>(CD/DCD)</small></strong>                          
                            </td>
                            <td style="border-top: 1px solid #C0C0C0; border-bottom: 1px solid #C0C0C0; border-left: 1px solid #000000; border-right: 1px solid #000000;   vertical-align: top;  background-color: #f7f8fb;"  colspan="2">
                                <strong><small>Initials</small></strong><br/><br/>
                                @isset($approval->approver_id)
                                    <strong>{{$approval->approver->full_name}}</strong>
                                @endisset
                            </td>
                            <td style="border-top: 1px solid #C0C0C0; border-bottom: 1px solid #C0C0C0; border-left: 1px solid #000000; border-right: 1px solid #000000;  vertical-align: top;" colspan="3" >
                                <small> Signed:</small><br/>
                                <img  style="height:50px;width:148px;" alt="." src="{{asset('storage/signatures/signature'.$approval->approver_id.'.png')}}"></img>
                            </td>
                            <td style="border-top: 1px solid #C0C0C0; border-bottom: 1px solid #C0C0C0; border-left: 1px solid #000000; border-right: 1px solid #000000;   vertical-align: top;  background-color: #f7f8fb;"  colspan="2" >                  
                                    <small>Date</small><br/><br/><br/>          
                                <strong style="float: left;">
                                    <span>{{date('d F, Y', strtotime($approval->created_at)) }}</span>
                                </strong>
                            </td>
                        </tr>
                        @endif
                        @endisset
                    @endforeach


                </tbody>
            </table>
        </div>
    </main>
</body>
</html>