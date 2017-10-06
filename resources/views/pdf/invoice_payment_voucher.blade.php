<html>
<head>
    <style>
    @page { margin: 150px 20px 120px 20px; }
    header { position: fixed; top: -130px; left: 0px; right: 0px; background-color: white; height: 120px; }
    footer { position: fixed; bottom: -110px; left: 0px; right: 0px; background-color: #E4E8F3; height: 100px; }
    p { page-break-after: always; }
    p:last-child { page-break-after: never; }
    footer:after {
        content: "[Page : " counter(page) "]";
    }
</style>
</head>
<body  style="font-family: monospace;">
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



                    <tr>
                        <td style="border: 1px solid #000000; border-bottom: 0px solid #c0c0c0;" colspan="3">
                            <span>Date:</span>
                        </td>
                        <td style="border: 1px solid #000000; border-bottom: 0px solid #c0c0c0;" colspan="3" >
                            <span>Payee:</span>
                        </td>
                        <td colspan="3" bgcolor="#092d50" height="20">
                            <span style="color: #ffffff;">REF:</span>
                        </td>
                    </tr>

                    <tr>
                        <td style="border: 1px solid #000000; border-top: 0px solid #c0c0c0;" colspan="3">
                            <strong style="float: left;">
                                <span>{{date('d F, Y', strtotime($invoice->created_at)) }}</span>
                            </strong>
                        </td>
                        <td style="border: 1px solid #000000; border-top: 0px solid #c0c0c0;" colspan="3" >
                            <strong style="float: left;">{{$invoice->supplier->supplier_name}}</strong>
                        </td>
                        <td colspan="3" bgcolor="#092d50" height="20">
                            <strong style="float: left;">
                                <span style="color: #ffffff;">{{$invoice->ref}}</span>
                            </strong>
                        </td>
                    </tr> 


                    <tr><td colspan="9"></td></tr> 
                    <tr><td colspan="9"></td></tr> 
                    <tr><td colspan="9"></td></tr> 


                    <tr>
                        <td style="text-align: center;" colspan="9"  ><span ><strong><span style="color: #092d50; font-size: medium; text-decoration: underline;">Payment Details:</span></strong></span></td>
                    </tr>

                    <tr><td colspan="9"></td></tr> 
                    <tr><td colspan="9"></td></tr> 
                    <tr><td colspan="9"></td></tr> 



                    <tr>
                        <td style="border: 1px solid #000000; border-bottom: 0px solid #c0c0c0;" colspan="3">
                            <span>Invoice #:</span>
                        </td>
                        <td style="border: 1px solid #000000; border-bottom: 0px solid #c0c0c0;" colspan="3" >
                            <small>Journal Description:</small>
                        </td>
                        <td style="border: 1px solid #000000; border-bottom: 0px solid #c0c0c0;" colspan="2" >
                            <span>Invoice Amount ({{$invoice->currency->currency_name}}):</span>
                        </td>
                        <td style="border: 1px solid #000000; border-bottom: 0px solid #c0c0c0;" colspan="1" >
                            <span>Payment Mode:</span>
                        </td>
                    </tr>

                    <tr "    height: 70px;">
                        <td style="border: 1px solid #000000; border-top: 0px solid #c0c0c0;" colspan="3">
                            <strong style="float: left;">
                                <span>{{$invoice->ref}}</span>
                            </strong>
                        </td>
                        <td style="border: 1px solid #000000; border-top: 0px solid #c0c0c0;" colspan="3" >
                            {{$invoice->expense_desc}}
                        </td>
                        <td style="border: 1px solid #000000; border-top: 0px solid #c0c0c0;" colspan="2" align="right">
                            <strong style="float: right;">{{number_format($invoice->total,2)}}</strong>
                        </td>
                        <td style="border: 1px solid #000000; border-top: 0px solid #c0c0c0;" colspan="1" >..
                        </td>
                    </tr>


                    <tr><td colspan="9"></td></tr> 
                    <tr><td colspan="9"></td></tr> 
                    <tr><td colspan="9"></td></tr> 


                    <tr>
                        <td style="text-align: center;" colspan="9"  >
                            <span >
                                <strong>
                                    <span style="color: #092d50; font-size: medium; text-decoration: underline;">
                                        Program and Account to be debited:
                                    </span>
                                </strong>
                            </span>
                        </td>
                    </tr>


                    <tr><td colspan="9"></td></tr> 
                    <tr><td colspan="9"></td></tr> 
                    <tr><td colspan="9"></td></tr> 

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
                                <span style="color: #ffffff;">TOTAL({{$invoice->currency->currency_name}})</span>
                            </strong>
                        </td>
                    </tr>
                    @php
                        $tot_perc = 0
                    @endphp
                    @php
                        $tot = 0
                    @endphp

                    @foreach ($invoice->allocations as $key => $allocation)
                    
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
                        $tot_words =  $f->format($tot);

                    @endphp


                    @endforeach


                    <tr bgcolor="#C0C0C0">
                        <td style="border-top: 1px solid #C0C0C0; border-bottom: 1px solid #C0C0C0; border-left: 1px solid #000000; border-right: 1px solid #000000;" colspan="2" >
                            <strong>ALLOCATION TOTAL</strong>
                        </td>
                        <td style="border-top: 1px solid #C0C0C0; border-bottom: 1px solid #C0C0C0; border-left: 1px solid #000000; border-right: 1px solid #000000;"  colspan="4">
                            <strong>{{$invoice->currency->currency_name}} {{$tot_words}} only.</strong>
                        </td>
                        <td style="border-top: 1px solid #C0C0C0; border-bottom: 1px solid #C0C0C0; border-left: 1px solid #000000; border-right: 1px solid #000000;"  colspan="2" align="right">
                            <strong>{{number_format($tot_perc,2)}} % </strong>
                        </td>
                        <td style="border-top: 1px solid #C0C0C0; border-bottom: 1px solid #C0C0C0; border-left: 1px solid #000000; border-right: 1px solid #000000;"  colspan="1" align="right">
                            <strong>{{number_format($tot,2)}} </strong>
                        </td>
                    </tr>


                    <tr><td colspan="9">&nbsp;</td></tr> 
                    <tr><td colspan="9">&nbsp;</td></tr> 
                    <tr><td colspan="9">&nbsp;</td></tr> 


                    @foreach ($invoice->approvals as $key => $approval)

                    <tr style="    height: 70px; ">
                        <td colspan="3">
                            <strong>{{$approval->approval_level->approval_level}}</strong>                          
                        </td>
                        <td style="border-top: 1px solid #C0C0C0; border-bottom: 1px solid #C0C0C0; border-left: 1px solid #000000; border-right: 1px solid #000000;   vertical-align: top;  background-color: #f7f8fb;"  colspan="1">
                            <small>Approver</small><br/><br/><br/>
                            @isset($approval->approver_id)
                                <strong>{{$approval->approver->full_name}}</strong>
                            @endisset
                        </td>
                        <td style="border-top: 1px solid #C0C0C0; border-bottom: 1px solid #C0C0C0; border-left: 1px solid #000000; border-right: 1px solid #000000;  vertical-align: top;" colspan="3" >
                            <small>signed:</small><br/>
                        </td>
                        <td style="border-top: 1px solid #C0C0C0; border-bottom: 1px solid #C0C0C0; border-left: 1px solid #000000; border-right: 1px solid #000000;   vertical-align: top;  background-color: #f7f8fb;"  colspan="2" >                  
                                <small>Date</small><br/><br/><br/>          
                            <strong style="float: left;">
                                <span>{{date('d F, Y', strtotime($approval->created_at)) }}</span>
                            </strong>
                        </td>
                    </tr>
                    @endforeach


                </tbody>
            </table>
        </div>
    </main>
</body>
</html>