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
            <span><br/><strong>Our Ref.:</strong> {{$lpo->ref}}</span><br/><br/>
            {{date('l F d, Y') }}<br/><br/>
            <span style="font-weight:bold">Attention: {{$lpo->attention}}</span><br/><br/>
            {{$lpo->preffered_quotation->supplier->supplier_name}}<br/>
            {{$lpo->preffered_quotation->supplier->address}}<br/>
            {{$lpo->preffered_quotation->supplier->email}}<br/><br/>
            <span style="text-decoration: underline;font-weight:bold">RE:LPO_{{$lpo->id}}_{{$lpo->expense_desc}}</span><br/><br/>


            <table style="width: 100%;font-size: smaller;" cellspacing="0">
                <tbody>

                    <tr>
                        <td style=" width: 20px; border-top: 1px solid #c0c0c0; border-bottom: 1px solid #c0c0c0; border-left: 1px solid #c0c0c0;" bgcolor="#092d50"><strong><span style="color: #ffffff;">#</span></strong>
            </td>
            <td style="border: 1px solid #c0c0c0;" colspan="3" bgcolor="#092d50"><span style="color: #ffffff;width:30%;"><strong>Item Description</strong></span></td>
            <td style="border: 1px solid #c0c0c0;" bgcolor="#092d50"><strong><span style="color: #ffffff;">Unit Price</span></strong></td>
            <td style="border: 1px solid #c0c0c0;" bgcolor="#092d50"><strong><span style="color: #ffffff;">Qty</span></strong></td>
            <td style="border: 1px solid #c0c0c0;" bgcolor="#092d50"><strong><span style="color: #ffffff;">TOTAL({{$lpo->currency->currency_name}})</span></strong></td>
            </tr>

            @foreach ($lpo->items as $key => $item)


            <tr>
                <td style="border-top: 1px solid #c0c0c0; border-bottom: 1px solid #c0c0c0; border-left: 1px solid #000000; border-right: 1px solid #000000;">{{$key+1}}</td>
                <td style="border-top: 1px solid #c0c0c0; border-bottom: 1px solid #c0c0c0; border-left: 1px solid #000000; border-right: 1px solid #000000;" colspan="3"><strong>{{$item->item}}</strong>
                    <br>{{$item->item_description}}
                </td>
                <td style="border-top: 1px solid #c0c0c0; border-bottom: 1px solid #c0c0c0; border-left: 1px solid #000000; border-right: 1px solid #000000;" align="right">{{number_format($item->calculated_unit_price,2)}}</td>
                <td style="border-top: 1px solid #c0c0c0; border-bottom: 1px solid #c0c0c0; border-left: 1px solid #000000; border-right: 1px solid #000000;" align="right">{{$item->qty}}</td>
                <td style="border-top: 1px solid #c0c0c0; border-bottom: 1px solid #c0c0c0; border-left: 1px solid #000000; border-right: 1px solid #000000;" align="right" bgcolor="#E4E8F3">{{number_format($item->calculated_sub_total,2)}}</td>
            </tr>


            @endforeach


            <tr>
                <td style="border-top: 1px solid #000000;" colspan="7">&nbsp;</td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td><span style="color: #ffffff;"></span></td>
                <td colspan="1"><strong>SUBTOTAL</strong></td>
                <td>{{$lpo->currency->currency_name}}</td>
                <td align="right" bgcolor="#E4E8F3">{{number_format($subtotal,2)}}</td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td><span style="color: #ffffff;"></span></td>
                <td colspan="1"><strong>VAT (16%)</strong></td>
                <td>&nbsp;</td>
                <td align="right" bgcolor="#E4E8F3">{{number_format($vat,2)}}</td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td><span style="color: #ffffff;"></span></td>
                <td colspan="1"><strong>TOTAL</strong></td>
                <td>{{$lpo->currency->currency_name}}</td>
                <td align="right" bgcolor="#E4E8F3">{{number_format($lpo->totals,2)}}</td>
            </tr>

            </tbody>
            </table>
            <br/><br/>
            <div>
                <strong>Terms and conditions:</strong><br/><br/> @foreach ($lpo->terms as $key => $term) {{$key+1}}: {{$term->terms}}<br/> @endforeach <br/>Yours sincerely<br/><br/>
            </div>
            <div style="display:inline-block">
                <div style="display:inline-block;margin-right:60px;">
                    <br/>
                    <img src="{{asset('storage/app/staff/signature'.$director->id.'.png')}}"><br/>
                    <strong>{{$director->f_name.' '.$director->l_name}}</strong>
                    <br/><strong>{{$director->post}}</strong>
                </div>
            </div>
        </div>
    </main>
</body>

</html>