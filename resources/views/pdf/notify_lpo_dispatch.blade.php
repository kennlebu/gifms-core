<html>

<head>
    <style>
        @page {
            margin: 150px 40px 120px 40px;
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
    </style>
</head>

<body style="font-family: arial;">
    <header>
        <div><img style="width: 100%;" src="img/letter_head_top_1200x240.png"></div>
    </header>
    <footer>
        <div><img style="width: 100%;height:80px;" src="img/letter_head_bottom_1200x125.png"></div>
    </footer>
    <main>
        <div>
            <table style="width: 100%;font-size: smaller;margin-top:20px;" cellspacing="0">
                <tbody>
                    <tr>
                        <td style="text-align: center;" colspan="10" height="30"><span style="text-decoration: underline;"><strong><span style="color: #092d50; font-size: x-large; text-decoration: underline;">LOCAL PURCHASE ORDER </span></strong>
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="7"></td>
                        <td colspan="1" bgcolor="#092d50" height="20"><strong><span style="color: #ffffff;">Our REF:</span></strong></td>
                        <td style="text-align: right;" bgcolor="#092d50" colspan="2"><strong><span style="color: #ffffff;">{{$lpo->ref}}</span></strong></td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #000000; border-bottom: 1px solid #c0c0c0;" colspan="2"><strong>Attn:</strong></td>
                        <td style="border-width: 1px; border-style: solid; border-color: #000000 #000000 #c0c0c0; border-image: initial; text-align: right;" colspan="4">{{$lpo->preffered_quotation->supplier->contact_name_1}}</td>
                        <td colspan="1"></td>
                        <td colspan="1"><strong>Date</strong></td>
                        <td style="text-align: right;" colspan="2">{{date('l F d, Y') }}</td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #000000; border-bottom: 1px solid #c0c0c0; border-top: 1px solid #c0c0c0;" colspan="2"><strong>Your Ref</strong></td>
                        <td style="border-width: 1px; border-style: solid; border-color: #c0c0c0 #000000; border-image: initial; text-align: right;" colspan="4"></td>
                        <td colspan="1"></td>
                        <td colspan="5"></td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #000000; border-bottom: 1px solid #c0c0c0; border-top: 1px solid #c0c0c0;" colspan="2"><strong>Company Name</strong></td>
                        <td style="border-width: 1px; border-style: solid; border-color: #c0c0c0 #000000; border-image: initial; text-align: right;" colspan="4">{{$lpo->preffered_quotation->supplier->supplier_name}}</td>
                        <td colspan="1"></td>
                        <td colspan="5"></td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #000000; border-bottom: 1px solid #c0c0c0; border-top: 1px solid #c0c0c0;" colspan="2"><strong>Email</strong></td>
                        <td style="border-width: 1px; border-style: solid; border-color: #c0c0c0 #000000; border-image: initial; text-align: right;" colspan="4"><a href="mailto:{{$lpo->preffered_quotation->supplier->email}}" target="_blank">{{str_replace("@","@ ",$lpo->preffered_quotation->supplier->email)}}</a></td>
                        <td colspan="1"></td>
                        <td style="border: 1px solid #666666;" rowspan="3"><strong>Ordered By:</strong></td>
                        <td style="text-align: right; border: 1px solid #666666; border-bottom: 1px solid #c0c0c0;" colspan="2">{{$lpo->requested_by->full_name}}</td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #000000; border-bottom: 1px solid #c0c0c0; border-top: 1px solid #c0c0c0;" colspan="2"><strong>Address (City/Town)</strong></td>
                        <td style="border-width: 1px; border-style: solid; border-color: #c0c0c0 #000000; border-image: initial; text-align: right;" colspan="4">{{$lpo->preffered_quotation->supplier->address}}</td>
                        <td colspan="1"></td>
                        <td style="text-align: right; border: 1px solid #666666; border-top: 1px solid #c0c0c0; border-bottom: 1px solid #c0c0c0;" colspan="2"><a href="mailto:{{$lpo->requested_by->mobile_no}}">{{$lpo->requested_by->mobile_no}}</a></td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #000000; border-top: 1px solid #c0c0c0;" colspan="2"><strong>Phone</strong></td>
                        <td style="border-width: 1px; border-style: solid; border-color: #c0c0c0 #000000 #000000; border-image: initial; text-align: right;" colspan="4"><a href="mailto:{{$lpo->preffered_quotation->supplier->telephone}}">{{$lpo->preffered_quotation->supplier->contact_phone_1}}</a></td>
                        <td colspan="1"></td>
                        <td style="text-align: right; border: 1px solid #666666; border-top: 1px solid #c0c0c0;" colspan="2"><a href="mailto:{{$lpo->requested_by->email}}" target="_blank">{{str_replace("@","@ ",$lpo->requested_by->email)}}</a></td>
                    </tr>
                    <tr>
                        <td colspan="10">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="10"><span style="text-decoration:underline;font-weight:bold">RE:LPO_{{$lpo->id}} {{$lpo->expense_desc}}</span></td>
                    </tr>
                    <tr>
                        <td colspan="10">&nbsp;</td>
                    </tr>
                    <tr>
                        <td style="border-top: 1px solid #c0c0c0; border-bottom: 1px solid #c0c0c0; border-left: 1px solid #c0c0c0;" bgcolor="#092d50"><strong><span style="color: #ffffff;"> # </span></strong></td>
                        <td style="border: 1px solid #c0c0c0;" colspan="3" bgcolor="#092d50"><span style="color: #ffffff;width:30%;"><strong>Particulars</strong></span></td>
                        <td style="border: 1px solid #c0c0c0;" bgcolor="#092d50"><strong><span style="color: #ffffff;">Qty</span></strong></td>
                        <td style="border: 1px solid #c0c0c0;" bgcolor="#092d50" colspan="2"><strong><span style="color: #ffffff;">Unit Price</span></strong></td>
                        <td style="border: 1px solid #c0c0c0;" bgcolor="#092d50"><span style="color: #ffffff;"><strong>VAT</strong></span></td>
                        <td style="border: 1px solid #c0c0c0;" bgcolor="#092d50"><span style="color: #ffffff;"><strong>SUBTOTAL</strong></span></td>
                        <td style="border: 1px solid #c0c0c0;" bgcolor="#092d50"><strong><span style="color: #ffffff;">TOTAL({{$lpo->currency->currency_name}})</span></strong></td>
                    </tr>

                    @foreach ($lpo->items as $key => $item)
                    <tr>
                        <td style="border-top: 1px solid #c0c0c0; border-bottom: 1px solid #c0c0c0; border-left: 1px solid #000000; border-right: 1px solid #000000;">{{$key+1}}</td>
                        <td style="border-top: 1px solid #c0c0c0; border-bottom: 1px solid #c0c0c0; border-left: 1px solid #000000; border-right: 1px solid #000000;" colspan="3">{{$item->item_description}}</td>
                        <td style="border-top: 1px solid #c0c0c0; border-bottom: 1px solid #c0c0c0; border-left: 1px solid #000000; border-right: 1px solid #000000;" align="center">{{$item->qty_description}}</td>
                        <td style="border-top: 1px solid #c0c0c0; border-bottom: 1px solid #c0c0c0; border-left: 1px solid #000000; border-right: 1px solid #000000;" align="right" colspan="2">{{number_format($item->calculated_unit_price,2)}}</td>
                        <td style="border-top: 1px solid #c0c0c0; border-bottom: 1px solid #c0c0c0; border-left: 1px solid #000000; border-right: 1px solid #000000;" align="right">{{number_format($item->calculated_vat,2)}}</td>
                        <td style="border-top: 1px solid #c0c0c0; border-bottom: 1px solid #c0c0c0; border-left: 1px solid #000000; border-right: 1px solid #000000;" align="right">{{number_format($item->calculated_sub_total,2)}}</td>
                        <td style="border-top: 1px solid #c0c0c0; border-bottom: 1px solid #c0c0c0; border-left: 1px solid #000000; border-right: 1px solid #000000;" align="right" bgcolor="#E4E8F3">{{number_format($item->calculated_total,2)}}</td>
                    </tr>
                    @endforeach

                    <tr>
                        <td style="border-top: 1px solid #000000;" colspan="10">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="7">&nbsp;</td>
                        <td colspan="1">SUBTOTALS</td>
                        <td>{{$lpo->currency->currency_name}}</td>
                        <td align="right" bgcolor="#E4E8F3">{{number_format($lpo->sub_totals,2)}}</td>
                    </tr>
                    <tr>
                        <td colspan="7">&nbsp;</td>
                        <td colspan="1">VAT RATE</td>
                        <td>&nbsp;</td>
                        <td align="right">16%</td>
                    </tr>
                    <tr>
                        <td colspan="7">&nbsp;</td>
                        <td>VAT</td>
                        <td>{{$lpo->currency->currency_name}}</td>
                        <td align="right" bgcolor="#E4E8F3">{{number_format($lpo->vats,2)}}</td>
                    </tr>
                    <tr>
                        <td colspan="10">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="7">&nbsp;</td>
                        <td colspan="1"><strong>TOTALS</strong></td>
                        <td>{{$lpo->currency->currency_name}}</td>
                        <td align="right" bgcolor="#E4E8F3"><strong>{{number_format($lpo->totals,2)}}</strong></td>
                    </tr>
                    <tr>
                        <td colspan="10">&nbsp;</td>
                    </tr>

                    <tr>
                        <td style="border: 1px solid #666666;margin-right: 20px;" colspan="10" bgcolor="#C0C0C0"><strong>Terms And Conditions</strong></td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid #666666;margin-right: 20px;" colspan="10" valign="top">
                            <ul style="list-style-type: square;">
                                @foreach ($lpo->terms as $term)
                                <li>{{$term->terms}}</li>
                                @endforeach
                            </ul>
                        </td>
                    </tr>

                    <tr>
                        <td colspan="10">&nbsp;</td>
                    </tr>
                </tbody>
            </table>
            Yours sincerely<br/><br/>
            <div style="display:inline-block">
                <div style="display:inline-block;margin-right:60px;">
                    <br/>
                    <div style="height:70px;width:200px; background:url({{asset('storage/app/staff/signature'.$director->id.'.png')}}; background-repeat:no-repeat;background-size:contain;"></div>
                    <img src="{{asset('storage/app/staff/signature'.$director->id.'.png')}}"><br/>
                    <strong>{{$director->f_name.' '.$director->l_name}}</strong>
                    <br/><strong>{{$director->post}}</strong>
                </div>
            </div>
        </div>
    </main>
</body>

</html>