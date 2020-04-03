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
          <td style="text-align:center;font-size:14px;" colspan="10"  height="30"><span style="text-decoration: underline;"><strong><span style="color: #092d50; font-size: x-large; text-decoration: underline;">LOCAL SERVICE ORDER </span></strong></span></td>
        </tr>
        <tr>
          <td colspan="7" ></td>
          <td colspan="1" bgcolor="#092d50" height="20"><strong><span style="color: #ffffff;font-size:12px;">REF:</span></strong></td>
          <td style="text-align:right;" bgcolor="#092d50" colspan="2" ><strong><span style="color: #ffffff;">{{$lpo->ref}}</span></strong></td>
        </tr>
        <tr>
            <td colspan="10">
              Suppliers are warned that this order in INVALID unless availability of funds is confirmed here below by the Program Manager/ Budget holder
            </td>
        </tr>
        <tr>
          <td style="border: 1px solid #000000; border-bottom: 1px solid #c0c0c0;" colspan="2"  ><strong>Attn:</strong></td>
          <td style="border-width: 1px; border-style: solid; border-color: #000000 #000000 #c0c0c0; border-image: initial; text-align: right;" colspan="4" >
              {{$lpo->preferred_supplier->contact_name_1}}
            </td>
          <td colspan="1"></td>
          <td colspan="1"></td>
          <td style="text-align: right;" colspan="2"></td>
        </tr>
        <tr>
          <td style="border: 1px solid #000000; border-bottom: 1px solid #c0c0c0; border-top: 1px solid #c0c0c0;" colspan="2"  ><strong>Your Ref</strong></td>
          <td style="border-width: 1px; border-style: solid; border-color: #c0c0c0 #000000; border-image: initial; text-align: right;" colspan="4" >{{$lpo->quotation_ref ?? '-'}}</td>
          <td colspan="1"></td>
          <td colspan="1"><strong>Requisition No.</strong></td>
          <td style="text-align: right;" colspan="2">{{$lpo->requisition->ref ?? '-'}}</td>
        </tr>
        <tr>
          <td style="border: 1px solid #000000; border-bottom: 1px solid #c0c0c0; border-top: 1px solid #c0c0c0;" colspan="2"  ><strong>Company Name</strong></td>
          <td style="border-width: 1px; border-style: solid; border-color: #c0c0c0 #000000; border-image: initial; text-align: right;" colspan="4" >
            {{$lpo->preferred_supplier->supplier_name}}
          </td>
          <td colspan="1" ></td>
          <td colspan="5"></td>
        </tr>
        <tr>
          <td style="border: 1px solid #000000; border-bottom: 1px solid #c0c0c0; border-top: 1px solid #c0c0c0;" colspan="2"  ><strong>Email</strong></td>
          <td style="border-width: 1px; border-style: solid; border-color: #c0c0c0 #000000; border-image: initial; text-align: right;" colspan="4" >
            <a href="mailto:{{$lpo->preferred_supplier->email}}" target="_blank">{{str_replace("@","@ ",$lpo->preferred_supplier->email)}}</a>
          </td>
          <td colspan="1" ></td>
          <td colspan="1"><strong>Date</strong></td>
          <td style="text-align: right;" colspan="2">{{date('d F, Y', strtotime($lpo->created_at)) }}</td>
        </tr>
        <tr>
          <td style="border: 1px solid #000000; border-bottom: 1px solid #c0c0c0; border-top: 1px solid #c0c0c0;" colspan="2"  ><strong>Address (City/Town)</strong></td>
          <td style="border-width: 1px; border-style: solid; border-color: #c0c0c0 #000000; border-image: initial; text-align: right;" colspan="4" >
            {{$lpo->preferred_supplier->address}}
          </td>
          <td colspan="1"></td>
          <td colspan="5"></td>
        </tr>
        <tr>
          <td style="border: 1px solid #000000; border-top: 1px solid #c0c0c0;" colspan="2"  ><strong>Phone</strong></td>
          <td style="border-width: 1px; border-style: solid; border-color: #c0c0c0 #000000 #000000; border-image: initial; text-align: right;" colspan="4"  >
            <a href="mailto:{{$lpo->preferred_supplier->telephone}}">{{$lpo->preferred_supplier->contact_phone_1}}</a>
          </td>
          <td colspan="1"></td>
          <td colspan="5"></td>
        </tr>
        <tr>
          <td colspan="9">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="9">
              Please carry out the services listed here below at place of delivery as advised by "the CHAI project Officer of 
              mobile number <a href="mailto:{{$lpo->requisitioned_by->mobile_no ?? $lpo->requested_by->mobile_no}}">{{$lpo->requisitioned_by->mobile_no ?? $lpo->requested_by->mobile_no}}</a> and/
              or email <a href="mailto:{{$lpo->requisitioned_by->email ?? $lpo->requested_by->email}}" target="_blank">{{str_replace("@","@ ",($lpo->requisitioned_by->email ?? $lpo->requested_by->email))}}</a>" 
              or "CHAI's Authorised/ Designated Officer" vide mobile number <a href="mailto:{{$designated_officer->mobile_no}}">{{$designated_officer->mobile_no}}</a> and/ or email address <a href="mailto:{{$designated_officer->email}}">
              {{str_replace("@","@ ",($designated_officer->email))}}</a>. on terms and conditions stated in this order, 
              on or before (date) .............................. and send the invoices immediately to CHAI. P.O. Box2011 - 00100, Nairobi			
          </td>
        </tr>
        <tr>
          <td colspan="9">&nbsp;</td>
        </tr>
        <tr>
          <td style="font-size:13px;border-top: 1px solid #c0c0c0; border-bottom: 1px solid #c0c0c0; border-left: 1px solid #c0c0c0;" bgcolor="#092d50" ><strong><span style="color: #ffffff;">ITEM NO</span></strong></td>
          <td style="font-size:13px;border: 1px solid #c0c0c0;" colspan="5" bgcolor="#092d50"><span style="color: #ffffff;width:30%;"><strong>Description of service</strong></span></td>
          <td style="font-size:13px;border: 1px solid #c0c0c0;" bgcolor="#092d50"><strong><span style="color: #ffffff;">QUANTITY</span></strong></td>
          <td style="font-size:13px;border: 1px solid #c0c0c0;" bgcolor="#092d50"><span style="color: #ffffff;"><strong>SUBTOTAL</strong></span></td>
          <td style="font-size:13px;border: 1px solid #c0c0c0;" bgcolor="#092d50"><span style="color: #ffffff;"><strong>VAT</strong></span></td>
          <td style="font-size:13px;border: 1px solid #c0c0c0;" bgcolor="#092d50"><strong><span style="color: #ffffff;">TOTAL({{$lpo->currency->currency_name}})</span></strong></td>
        </tr>

        @foreach ($lpo->items as $key => $item)
        <tr>
          <td style="border-top:1px solid #c0c0c0;border-bottom:1px solid #c0c0c0;border-left:1px solid #000000;border-right:1px solid #000000;font-size:12px;">{{$key+1}}</td>
          <td style="border-top:1px solid #c0c0c0;border-bottom:1px solid #c0c0c0;border-left:1px solid #000000;border-right:1px solid #000000;font-size:12px;" colspan="5" >{{$item->item}}</td>
          <td style="border-top:1px solid #c0c0c0;border-bottom:1px solid #c0c0c0;border-left:1px solid #000000;border-right:1px solid #000000;font-size:12px;">{{$item->qty_description}}</td>
          <td style="border-top:1px solid #c0c0c0;border-bottom:1px solid #c0c0c0;border-left:1px solid #000000;border-right:1px solid #000000;font-size:12px;" align="right">{{number_format($item->calculated_sub_total,2)}}</td>
          <td style="border-top:1px solid #c0c0c0;border-bottom:1px solid #c0c0c0;border-left:1px solid #000000;border-right:1px solid #000000;font-size:12px;" align="right">{{number_format($item->calculated_vat,2)}}</td>
          <td style="border-top:1px solid #c0c0c0;border-bottom:1px solid #c0c0c0;border-left:1px solid #000000;border-right:1px solid #000000;font-size:12px;" align="right" bgcolor="#E4E8F3">{{number_format($item->calculated_total,2)}}</td>
        </tr>
        @endforeach

        <tr>
          <td style="border-top: 1px solid #000000;" colspan="10" >&nbsp;</td>
        </tr>
        <tr>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td><span style="color: #ffffff;"></span></td>
          <td colspan="1" >SUBTOTALS</td>
          <td>{{$lpo->currency->currency_name}}</td>
          <td align="right" bgcolor="#E4E8F3">{{number_format($lpo->sub_totals,2)}}</td>
        </tr>
        <tr>
          <td colspan="6">&nbsp;</td>
          <td></td>
          <td colspan="1" >VAT RATE</td>
          <td></td>
          <td align="right">16%</td>
        </tr>
        <tr>
          <td colspan="6">&nbsp;</td>
          <td></td>
          <td>VAT</td>
          <td>{{$lpo->currency->currency_name}}</td>
          <td align="right" bgcolor="#E4E8F3">{{number_format($lpo->vats,2)}}</td>
        </tr>
        <tr>
          <td colspan="6">&nbsp;</td>
          <td>&nbsp;</td>
          <td style="border-bottom: 3px double #000000;" colspan="1" ></td>
          <td style="border-bottom: 3px double #000000;" ></td>
          <td style="border-bottom: 3px double #000000;" align="right"></td>
        </tr>
        <tr>
          <td colspan="6">&nbsp;</td>
          <td></td>
          <td colspan="1" ><strong>TOTALS</strong></td>
          <td>{{$lpo->currency->currency_name}}</td>
          <td align="right" bgcolor="#E4E8F3"><strong>{{number_format($lpo->totals,2)}}</strong></td>
        </tr>
        <tr>
          <td colspan="10">&nbsp;</td>
        </tr>

        <tr>
          <td colspan="10">&nbsp;</td>
        </tr>
        <tr>
            <td style="border:1px solid #666666;margin-right:20px;" colspan="10"><strong>Allocations</strong></td>
        </tr>
        <tr>
          <th colspan="3" style="font-size:13px;border-top: 1px solid #c0c0c0; border-bottom: 1px solid #c0c0c0; border-left: 1px solid #c0c0c0;" bgcolor="#092d50" ><strong><span style="color:#ffffff;"width:30%;>Project</span></strong></th>
          <th colspan="2" style="font-size:13px;border: 1px solid #c0c0c0;" bgcolor="#092d50"><span style="color:#ffffff;"><strong>Grant</strong></span></th>
          <th colspan="2" style="font-size:13px;border: 1px solid #c0c0c0;" bgcolor="#092d50"><strong><span style="color:#ffffff;">Account</span></strong></th>
          <th colspan="2" style="font-size:13px;border: 1px solid #c0c0c0;" bgcolor="#092d50"><span style="color:#ffffff;"><strong>Objective</strong></span></th>
          <th colspan="1" style="font-size:13px;border: 1px solid #c0c0c0;" bgcolor="#092d50"><span style="color:#ffffff;"><strong>Rate</strong></span></th>
        </tr>
        @foreach ($lpo->allocations as $allocation)
        <tr>
          <td colspan="3">{{$allocation->project->project_code ?? ''}} - {{$allocation->project->project_name ?? ''}}</td>
          <td colspan="2">{{$allocation->grant->grant_name ?? '-'}}</td>
          <td colspan="2">{{$allocation->account->account_name ?? ''}}</td>
          <td colspan="2">{{$allocation->objective->objective ?? ''}}</td>
          <td colspan="1">{{$allocation->percentage_allocated}} %</td>
        </tr>
        @endforeach
        <tr colspan="10"><td>&nbsp;</td></tr>
        <tr>
            <td colspan="10">&nbsp;</td>
        </tr>
        
        <tr>
          <td colspan="10">I confirm that funds are available and that commitment has been allocated in the budget/ grant</td>
        </tr>
        @foreach ($unique_approvals as $key => $approval)
        @if($approval->approval_level_id == 2)
        <tr>
          <td colspan="2"><strong>Signature:</strong></td>
          <td colspan="4">
            <img style="height:70px;width:200px;" alt="." src="{{asset('storage/signatures/signature'.$approval->approver_id.'.png')}}">
          </td>
          <td colspan="4"></td>
        </tr>
        <tr>
          <td colspan="2"><strong>Date:</strong></td>
          <td colspan="4">{{date('d F, Y', strtotime($approval->created_at))}}</td>
          <td colspan="4"></td>
        </tr>
        @endif
        @endforeach
        <tr>
            <td colspan="10">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="10">&nbsp;</td>
      </tr>
        
        <tr>
          <td colspan="6">I confirm receipt of this order</td>
          <td colspan="4"></td>
        </tr>
        <tr>
          <td colspan="6"></td>
          <td colspan="4">______________________________________________ (Vendor name)</td>
        </tr>
        <tr>
          <td colspan="10">&nbsp;</td>
      </tr>
        <tr>
          <td colspan="6"></td>
          <td colspan="4">______________________________________________ (Date)</td>
        </tr>
        <tr>
            <td colspan="10">&nbsp;</td>
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
        <tr>
            <td colspan="10">&nbsp;</td>
        </tr>
        </tbody>
      </table>      
    </div>
  </main>
</body>
</html>