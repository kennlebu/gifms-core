<html>

<head>
    <style>
        @page {
            margin: 150px 50px 120px 50px;
        }
        
        header {
            position: fixed;
            top: -130px;
            left: 0px;
            right: 0px;
            background-color: white;
            height: 120px;
        }
        
        footer {
            position: fixed;
            bottom: -110px;
            left: 0px;
            right: 0px;
            background-color: #E4E8F3;
            height: 100px;
        }
        
        p {
            page-break-after: always;
        }
        
        p:last-child {
            page-break-after: never;
        }
        
        footer:after {
            content: "[Page : " counter(page) "]";
        }
        
        .smaller {
            font-size: 12px;
        }
        
        .muted {
            color: gray;
        }

        body {
            font-size: 14px;
        }
    </style>
</head>

<body style="font-family:arial;">
    <header>
        <div><img style="width: 100%;height:120px;" src="img/letter_head_top_1200x240.png"></div>
    </header>
    <footer>
        <div><img style="width: 100%;height:80px;" src="img/letter_head_bottom_1200x125.png"></div>
    </footer>
    <main>
        <div>
            <table style="width: 100%;font-size: smaller; margin: 0 auto;" cellspacing="0">
                <tbody>
                    <tr>
                        <td style="text-align: center;" colspan="9" height="30">
                            <span>
                                <strong>
                                    <span style="color: #092d50; font-size: x-large;">SUPPLIER STATEMENT</span>
                                </strong>
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="9">&nbsp;</td>
                    </tr>



                    <tr>
                        <td colspan="4">
                            <span>{{$supplier->supplier_name}}</span><br/>
                            {{-- <span>Attn: {{$supplier->supplier_name}}</span><br/> --}}
                            <span>{{$supplier->address}}</span><br/>
                            <span>{{$supplier->phone}}</span><br/>
                            <span>{{$supplier->email}}</span><br/>
                            <span>PIN: {{$supplier->tax_pin}}</span><br/>
                        </td>
                        <td colspan="5">
                            <span><small class="muted">From:</small> {{$from_date}}</span><br/>
                            <span><small class="muted">To:</small> {{$to_date}}</span>
                        </td>
                    </tr>


                    <tr>
                        <td colspan="9">&nbsp;</td>
                    </tr>


                    <tr>
                        <td colspan="9">&nbsp;</td>
                    </tr>


                    <tr>
                        <td style="border: 1px solid #c0c0c0; background-color: #092d50;" colspan="1">
                            <strong>
                                <span style="color: #ffffff;">DATE</span>
                            </strong>
                        </td>
                        <td style="border: 1px solid #c0c0c0; background-color: #092d50;" colspan="4">
                            <strong>
                                <span style="color: #ffffff; background-color: #092d50;">ACTIVITY</span>
                            </strong>
                        </td>
                        <td style="border: 1px solid #c0c0c0; background-color: #092d50;" colspan="2">
                            <strong>
                                <span style="color: #ffffff;">REFERENCE</span>
                            </strong>
                        </td>
                        <td style="border: 1px solid #c0c0c0; background-color: #092d50;" colspan="1">
                            <strong>
                                <span style="color: #ffffff;">INVOICE AMOUNT</span>
                            </strong>
                        </td>
                        <td style="border: 1px solid #c0c0c0; background-color: #092d50;" colspan="1">
                            <strong>
                                <span style="color: #ffffff;">PAYMENT</span>
                            </strong>
                        </td>
                    </tr>

                    <?php $total_kes = 0;
                          $total_usd = 0;
                          $total_kes_payment = 0;
                          $total_usd_payment = 0;
                    ?>
                    @foreach ($invoices as $invoice)
                    <?php
                        if($invoice->currency_id == 1) { $total_kes += (float) $invoice->total; }
                        if($invoice->currency_id == 2) { $total_usd += (float) $invoice->total; }
                    ?>
                    <tr>
                        <td style="border-top: 1px solid #c0c0c0; border-bottom: 1px solid #c0c0c0; border-left: 1px solid #c0c0c0; border-right: 1px solid #c0c0c0;" colspan="1">
                            {{date('d F, Y', strtotime($invoice->created_at))}}
                        </td>
                        <td style="border-top: 1px solid #c0c0c0; border-bottom: 1px solid #c0c0c0; border-left: 1px solid #c0c0c0; border-right: 1px solid #c0c0c0;" colspan="4">
                            {{$invoice->expense_desc}}
                        </td>
                        <td style="border-top:1px solid #c0c0c0;border-bottom: 1px solid #c0c0c0;border-left: 1px solid #c0c0c0;border-right: 1px solid #c0c0c0;" colspan="2">
                            {{$invoice->external_ref}}
                        </td>
                        <td style="border-top: 1px solid #c0c0c0; border-bottom: 1px solid #c0c0c0; border-left: 1px solid #c0c0c0; border-right: 1px solid #c0c0c0; text-align: right;" colspan="1">
                            {{$invoice->currency->currency_name}} {{number_format($invoice->total,2)}}
                        </td>
                        <td style="border-top: 1px solid #c0c0c0; border-bottom: 1px solid #c0c0c0; border-left: 1px solid #c0c0c0; border-right: 1px solid #c0c0c0; text-align: right;" colspan="1">
                            &nbsp;
                        </td>
                    </tr>

                    @foreach ($invoice->payments as $payment)
                    <?php
                        if($payment->currency_id == 1) { $total_kes_payment += (float) $payment->amount; }
                        if($payment->currency_id == 2) { $total_usd_payment += (float) $payment->amount; }
                    ?>
                    <tr>
                        <td style="border-top: 1px solid #c0c0c0; border-bottom: 1px solid #c0c0c0; border-left: 1px solid #c0c0c0; border-right: 1px solid #c0c0c0;" colspan="1">
                            {{date('d F, Y', strtotime($payment->created_at))}}
                        </td>
                        <td style="border-top: 1px solid #c0c0c0; border-bottom: 1px solid #c0c0c0; border-left: 1px solid #c0c0c0; border-right: 1px solid #c0c0c0;" colspan="4">
                            Payment for Invoice {{$invoice->external_ref}}
                        </td>
                        <td style="border-top:1px solid #c0c0c0;border-bottom: 1px solid #c0c0c0;border-left: 1px solid #c0c0c0;border-right: 1px solid #c0c0c0;" colspan="2">
                            @if(isset($payment->bank_transactions) && count($payment->bank_transactions) > 0)
                                @foreach ($payment->bank_transactions as $key => $transaction)
                                    {{$transaction->bank_ref}}
                                    @if ($key < count($payment->bank_transactions)-1)
                                        , 
                                    @endif
                                @endforeach
                            @else
                            {{$payment->ref}}
                            @endif
                        </td>
                        <td style="border-top: 1px solid #c0c0c0; border-bottom: 1px solid #c0c0c0; border-left: 1px solid #c0c0c0; border-right: 1px solid #c0c0c0; text-align: right;" colspan="1">
                            &nbsp;
                        </td>
                        <td style="border-top: 1px solid #c0c0c0; border-bottom: 1px solid #c0c0c0; border-left: 1px solid #c0c0c0; border-right: 1px solid #c0c0c0; text-align: right; background-color: #E4E8F3;" colspan="1">
                            {{$invoice->currency->currency_name}} {{number_format($payment->amount,2)}}
                        </td>
                    </tr>
                    @endforeach
                    
                    @endforeach                    

                    <tr style="height:70px; background-color: #F8F8F8;">
                        <td style="vertical-align:middle; border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #c0c0c0; border-right: none;" colspan="7">
                            <strong>TOTAL</strong>
                        </td>
                        <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #c0c0c0; border-right: none; text-align: right;" colspan="1">
                            @if ($total_kes > 0)
                                KES {{number_format($total_kes,2)}} <br/>
                            @endif
                            @if($total_usd > 0)
                                USD {{number_format($total_usd,2)}} <br/>
                            @endif
                            @if ($total_kes <= 0 && $total_usd <= 0)
                                0.00
                            @endif
                        </td>
                        <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #c0c0c0; border-right: 1px solid #c0c0c0; text-align: right;" colspan="1">
                            @if ($total_kes_payment > 0)
                                KES {{number_format($total_kes_payment,2)}} <br/>
                            @endif
                            @if($total_usd_payment > 0)
                                USD {{number_format($total_usd_payment,2)}} <br/>
                            @endif
                            @if ($total_kes_payment <= 0 && $total_usd_payment <= 0)
                                0.00
                            @endif
                        </td>
                    </tr>


                    <tr>
                        <td colspan="9">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="9">&nbsp;</td>
                    </tr>

                    
					<tr>
						<td style="padding: 30px; background-color: #ffff; width:100%;" colspan="9">
							<table cellpadding="0" cellspacing="0" style="border-top:1px solid #003d79; width:100%;">
								<tr>
									<td style="color: #003d79; font-family: Arial, sans-serif; font-size: 14px; width: 75%; text-align: center; padding-top: 12px;;">
										Clinton Health Access Initiative • 3rd Floor • Timau Plaza • Argwings Kodhek Road<br/>
										P.O. Box 2011-00100 • Nairobi, Kenya • Tel: 254 20 514 3100
									</td>
								</tr>
							</table>
						</td>
					</tr>


                </tbody>
            </table>
        </div>
    </main>
</body>

</html>
