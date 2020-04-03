<html>
<head>
  <style>
    @page { margin: 150px 40px 120px 40px; }
    header { position: fixed; top: -130px; left: 0px; right: 0px; background-color: white; height: 120px; }
    footer { position: fixed; bottom: -110px; left: 0px; right: 0px; background-color: #E4E8F3; height: 100px; }
    p { page-break-after: always; }
    p:last-child { page-break-after: never; }
    div.stamp.stamped-cancelled:after
    {
        content:"CANCELLED";
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
        color:#c00;
        background:#fff;
        border:solid 4px #c00;
        padding:5px;
        border-radius:5px;
        zoom:1;
        filter:alpha(opacity=20);
        opacity:0.2;
        -webkit-text-shadow: 0 0 2px #c00;
        text-shadow: 0 0 2px #c00;
        box-shadow: 0 0 2px #c00;
    }
    footer:after {
      content: "[Page : " counter(page) "]";
    }
  </style>
</head>
<body  style="font-family: arial;">
  @if($lpo->status_id==15)
  <div class="stamp stamped-cancelled"></div>
  @endif
  <header><div><img style="width: 100%;height:120px;" src="img/letter_head_top_1200x240.png"></div></header>
  <footer><div><img style="width: 100%;height:80px;" src="img/letter_head_bottom_1200x125.png"></div></footer>
  <main>
    <div>
      <table style="width: 100%;font-size:13px;" cellspacing="0">
        <tbody>
          <tr>
            <td style="text-align:center;font-size:14px;" colspan="10"  height="30"><span style="text-decoration: underline;"><strong><span style="color: #092d50; font-size: x-large; text-decoration: underline;">LOCAL PURCHASE ORDER </span></strong></span></td>
          </tr>
          <tr>
            <td colspan="7" ></td>
            <td colspan="1" bgcolor="#092d50" height="20"><strong><span style="color: #ffffff;font-size:12px;">REF:</span></strong></td>
            <td style="text-align:right;" bgcolor="#092d50" colspan="2" ><strong><span style="color: #ffffff;">{{$lpo->ref}}</span></strong></td>
          </tr>
          <tr>
            <td style="border: 1px solid #000000; border-bottom: 1px solid #c0c0c0;" colspan="2"  ><strong>Attn:</strong></td>
            <td style="border-width: 1px; border-style: solid; border-color: #000000 #000000 #c0c0c0; border-image: initial; text-align: right;" colspan="4" >
                @if(empty($lpo->lpo_type)||$lpo->lpo_type!='prenegotiated')
                {{$lpo->preffered_quotation->supplier->contact_name_1}}
                @elseif(!empty($lpo->lpo_type)&&$lpo->lpo_type=='prenegotiated')
                {{$lpo->supplier->contact_name_1}}
                @endif
              </td>
            <td colspan="1" ></td>
            <td colspan="1" ><strong>Date</strong></td>
            <td style="text-align: right;" colspan="2">{{date('d F, Y', strtotime($lpo->created_at)) }}</td>
          </tr>
          <tr>
            <td style="border: 1px solid #000000; border-bottom: 1px solid #c0c0c0; border-top: 1px solid #c0c0c0;" colspan="2"  ><strong>Your Ref</strong></td>
            <td style="border-width: 1px; border-style: solid; border-color: #c0c0c0 #000000; border-image: initial; text-align: right;" colspan="4">{{$lpo->quotation_ref ?? ''}}</td>
            <td colspan="1" ></td>
            <td colspan="5" ></td>
          </tr>
          <tr>
            <td style="border: 1px solid #000000; border-bottom: 1px solid #c0c0c0; border-top: 1px solid #c0c0c0;" colspan="2"  ><strong>Company Name</strong></td>
            <td style="border-width: 1px; border-style: solid; border-color: #c0c0c0 #000000; border-image: initial; text-align: right;" colspan="4" >
              @if(empty($lpo->lpo_type)||$lpo->lpo_type!='prenegotiated')
              {{$lpo->preffered_quotation->supplier->supplier_name}}
              @elseif(!empty($lpo->lpo_type)&&$lpo->lpo_type=='prenegotiated')
              {{$lpo->supplier->supplier_name}}
              @endif
            </td>
            <td colspan="1" ></td>
            <td colspan="5" ></td>
          </tr>
          <tr>
            <td style="border: 1px solid #000000; border-bottom: 1px solid #c0c0c0; border-top: 1px solid #c0c0c0;" colspan="2"  ><strong>Email</strong></td>
            <td style="border-width: 1px; border-style: solid; border-color: #c0c0c0 #000000; border-image: initial; text-align: right;" colspan="4" >
              @if(empty($lpo->lpo_type)||$lpo->lpo_type!='prenegotiated')
              <a href="mailto:{{$lpo->preffered_quotation->supplier->email}}" target="_blank">{{str_replace("@","@ ",$lpo->preffered_quotation->supplier->email)}}</a>
              @elseif(!empty($lpo->lpo_type)&&$lpo->lpo_type=='prenegotiated')
              <a href="mailto:{{$lpo->supplier->email}}" target="_blank">{{str_replace("@","@ ",$lpo->supplier->email)}}</a>
              @endif
            </td>
            <td colspan="1" ></td>
            <td style="border: 1px solid #666666;" rowspan="3" ><strong>Ordered By:</strong></td>
            <td style="text-align: right; border: 1px solid #666666; border-bottom: 1px solid #c0c0c0;" colspan="2" >{{$lpo->requisitioned_by? ($lpo->requisitioned_by->f_name. ' '. $lpo->requisitioned_by->l_name) : $lpo->requested_by->full_name}}</td>
          </tr>
          <tr>
            <td style="border: 1px solid #000000; border-bottom: 1px solid #c0c0c0; border-top: 1px solid #c0c0c0;" colspan="2"  ><strong>Address (City/Town)</strong></td>
            <td style="border-width: 1px; border-style: solid; border-color: #c0c0c0 #000000; border-image: initial; text-align: right;" colspan="4" >
              @if(empty($lpo->lpo_type)||$lpo->lpo_type!='prenegotiated')
              {{$lpo->preffered_quotation->supplier->address}}
              @elseif(!empty($lpo->lpo_type)&&$lpo->lpo_type=='prenegotiated')
              {{$lpo->supplier->address}}
              @endif
            </td>
            <td colspan="1" ></td>
            <td style="text-align: right; border: 1px solid #666666; border-top: 1px solid #c0c0c0; border-bottom: 1px solid #c0c0c0;" colspan="2" ><a href="mailto:{{$lpo->requisitioned_by->mobile_no ?? $lpo->requested_by->mobile_no}}">{{$lpo->requisitioned_by->mobile_no ?? $lpo->requested_by->mobile_no}}</a></td>
          </tr>
          <tr>
            <td style="border: 1px solid #000000; border-top: 1px solid #c0c0c0;" colspan="2"  ><strong>Phone</strong></td>
            <td style="border-width: 1px; border-style: solid; border-color: #c0c0c0 #000000 #000000; border-image: initial; text-align: right;" colspan="4"  >
              @if(empty($lpo->lpo_type)||$lpo->lpo_type!='prenegotiated')
              <a href="mailto:{{$lpo->preffered_quotation->supplier->telephone}}">{{$lpo->preffered_quotation->supplier->contact_phone_1}}</a>
              @elseif(!empty($lpo->lpo_type)&&$lpo->lpo_type=='prenegotiated')
              <a href="mailto:{{$lpo->supplier->telephone}}">{{$lpo->supplier->contact_phone_1}}</a>
              @endif
            </td>
            <td colspan="1" ></td>
            <td style="text-align: right; border: 1px solid #666666; border-top: 1px solid #c0c0c0;" colspan="2" ><a href="mailto:{{$lpo->requisitioned_by->email ?? $lpo->requested_by->email}}" target="_blank">{{str_replace("@","@ ",($lpo->requisitioned_by->email ?? $lpo->requested_by->email))}}</a></td>
          </tr>
          <tr>
            <td colspan="9" >&nbsp;</td>
          </tr>
          <tr>
            <td style="font-size:13px;border-top: 1px solid #c0c0c0; border-bottom: 1px solid #c0c0c0; border-left: 1px solid #c0c0c0;" bgcolor="#092d50" ><strong><span style="color: #ffffff;">ITEM #</span></strong></td>
            <td style="font-size:13px;border: 1px solid #c0c0c0;" colspan="3" bgcolor="#092d50"><span style="color: #ffffff;width:30%;"><strong>PARTICULARS</strong></span></td>
            <td style="font-size:13px;border: 1px solid #c0c0c0;" bgcolor="#092d50"><strong><span style="color: #ffffff;">QTY</span></strong></td>
            <td style="font-size:13px;border: 1px solid #c0c0c0;" bgcolor="#092d50"><strong><span style="color: #ffffff;">UNIT-PRICE</span></strong></td>
            <td style="font-size:13px;border: 1px solid #c0c0c0;" bgcolor="#092d50"><strong><span style="color: #ffffff;"></span></strong></td>
            <td style="font-size:13px;border: 1px solid #c0c0c0;" bgcolor="#092d50"><span style="color: #ffffff;"><strong>SUBTOTAL</strong></span></td>
            <td style="font-size:13px;border: 1px solid #c0c0c0;" bgcolor="#092d50"><span style="color: #ffffff;"><strong>VAT</strong></span></td>
            <td style="font-size:13px;border: 1px solid #c0c0c0;" bgcolor="#092d50"><strong><span style="color: #ffffff;">TOTAL({{$lpo->currency->currency_name}})</span></strong></td>
          </tr>

          @foreach ($lpo->items as $key => $item)
          <tr>
            <td style="border-top:1px solid #c0c0c0;border-bottom:1px solid #c0c0c0;border-left:1px solid #000000;border-right:1px solid #000000;font-size:12px;">{{$key+1}}</td>
            <td style="border-top:1px solid #c0c0c0;border-bottom:1px solid #c0c0c0;border-left:1px solid #000000;border-right:1px solid #000000;font-size:12px;" colspan="3" >{{$item->item}} {{$item->item_description ? ('; '.$item->item_description) : ''}}
            {{-- @if (!empty($item->no_of_days) && !empty($item->requisition_item))
              @if($item->no_of_days > 1)
                . From {{date('d M, Y', strtotime($item->requisition_item->start_date)) ?? ''}} to {{date('d M, Y', strtotime($item->requisition_item->end_date)) ?? ''}}
              @elseif($item->no_of_days == 1)
                . On {{date('d M, Y', strtotime($item->requisition_item->start_date)) ?? ''}}
              @endif
            @endif --}}
            </td>
            <td style="border-top:1px solid #c0c0c0;border-bottom:1px solid #c0c0c0;border-left:1px solid #000000;border-right:1px solid #000000;font-size:12px;" align="center">{{$item->qty_description}}</td>
            <td style="border-top:1px solid #c0c0c0;border-bottom:1px solid #c0c0c0;border-left:1px solid #000000;border-right:1px solid #000000;font-size:12px;" align="right">{{number_format($item->calculated_unit_price,2)}}</td>
            <td style="border-top:1px solid #c0c0c0;border-bottom:1px solid #c0c0c0;border-left:1px solid #000000;border-right:1px solid #000000;font-size:12px;" align="right"></td>
            <td style="border-top:1px solid #c0c0c0;border-bottom:1px solid #c0c0c0;border-left:1px solid #000000;border-right:1px solid #000000;font-size:12px;" align="right">{{number_format($item->calculated_sub_total,2)}}</td>
            <td style="border-top:1px solid #c0c0c0;border-bottom:1px solid #c0c0c0;border-left:1px solid #000000;border-right:1px solid #000000;font-size:12px;" align="right">{{number_format($item->calculated_vat,2)}}</td>
            <td style="border-top:1px solid #c0c0c0;border-bottom:1px solid #c0c0c0;border-left:1px solid #000000;border-right:1px solid #000000;font-size:12px;" align="right" bgcolor="#E4E8F3">{{number_format($item->calculated_total,2)}}</td>
          </tr>
          @endforeach

          <tr>
            <td style="border-top: 1px solid #000000;" colspan="10" >&nbsp;</td>
          </tr>
          <tr>
            <td ></td>
            <td ></td>
            <td ></td>
            <td ></td>
            <td ></td>
            <td ></td>
            <td ><span style="color: #ffffff;"></span></td>
            <td colspan="1" >SUBTOTALS</td>
            <td >{{$lpo->currency->currency_name}}</td>
            <td align="right" bgcolor="#E4E8F3">{{number_format($lpo->sub_totals,2)}}</td>
          </tr>
          <tr>
            <td colspan="6">&nbsp;</td>
            <td ></td>
            <td colspan="1" >VAT RATE</td>
            <td ></td>
            <td align="right">16%</td>
          </tr>
          <tr>
            <td colspan="6">&nbsp;</td>
            <td ></td>
            <td >VAT</td>
            <td >{{$lpo->currency->currency_name}}</td>
            <td align="right" bgcolor="#E4E8F3">{{number_format($lpo->vats,2)}}</td>
          </tr>
          <tr>
            <td colspan="6">&nbsp;</td>
            <td >&nbsp;</td>
            <td style="border-bottom: 3px double #000000;" colspan="1" ></td>
            <td style="border-bottom: 3px double #000000;" ></td>
            <td style="border-bottom: 3px double #000000;" align="right"></td>
          </tr>
          <tr>
            <td colspan="6">&nbsp;</td>
            <td ></td>
            <td colspan="1" ><strong>TOTALS</strong></td>
            <td >{{$lpo->currency->currency_name}}</td>
            <td align="right" bgcolor="#E4E8F3"><strong>{{number_format($lpo->totals,2)}}</strong></td>
          </tr>
          <tr>
            <td colspan="6">&nbsp;</td>
            <td >&nbsp;</td>
            <td ></td>
            <td ></td>
          </tr>

          <tr>
              <td style="border: 1px solid #666666;margin-right: 20px;" colspan="10"  bgcolor="#C0C0C0" ><strong>Terms And Conditions</strong></td>
          </tr>
          <tr>
            <td style="border: 1px solid #666666;margin-right: 20px;" colspan="10" valign="top" >
              <ul style="list-style-type: square;">
                @foreach ($lpo->terms as $term)
                <li><span style="font-size:12px;">{{$term->terms}}</span></li>
                @endforeach
              </ul>
            </td>
          </tr>

          @if (!empty($lpo->quote_exempt_explanation))
          <tr>
            <td colspan="10">&nbsp;</td>
          </tr>
          <tr>
              <td style="border:1px solid #666666;margin-right:20px;" colspan="10"  bgcolor="#C0C0C0" ><strong>Quote exempt reason</strong></td>
          </tr>
          <tr>
            <td style="border:1px solid #666666;margin-right:20px;font-size:12px;" colspan="10" valign="top" >
                {{$lpo->quote_exempt_explanation}}: {{$lpo->quote_exempt_details}}
            </td>
          </tr>
          @endif
          <tr>
              <td colspan="10">&nbsp;</td>
          </tr>

          @foreach ($unique_approvals as $key => $approval)
            <tr>
                <td colspan="3">
                  @if ($approval->approval_level_id == 1)
                      Reviewed by:
                  @elseif($approval->approval_level_id == 2)
                      Verified by:
                  @elseif($approval->approval_level_id == 3)
                      Approved by:
                  @elseif($approval->approval_level_id == 4)
                      Authorised by:
                  @endif                  
                  <br/>
                  <img style="height:70px;width:200px;" alt="." src="{{asset('storage/signatures/signature'.$approval->approver_id.'.png')}}"><br/>
                  @isset($approval->approver_id)
                      <strong>{{$approval->approver->full_name}}</strong>
                  @endisset
                </td>
                <td colspan="2">Date:<br/>
                  <span>{{date('d F, Y', strtotime($approval->created_at)) }}</span>
                </td>
              </tr>
              <tr>
                  <td colspan="10">&nbsp;</td>
              </tr>
          @endforeach
          <tr>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td colspan="10">&nbsp;</td>
          </tr>
        </tbody>
      </table>      
    </div>
  </main>
</body>
</html>