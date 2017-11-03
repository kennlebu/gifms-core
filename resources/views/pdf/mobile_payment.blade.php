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
      <table style="width: 100%;font-size: smaller;"  cellspacing="0">
        <tbody>
          <tr>
            <td style="text-align: center;" colspan="10"  height="30"><span style="text-decoration: underline;"><strong><span style="color: #092d50; font-size: x-large; text-decoration: underline;">MOBILE PAYMENTS </span></strong></span></td>
          </tr>
          <tr>
            <td colspan="4" ></td>
            <td colspan="1"  bgcolor="#092d50" height="20"><strong><span style="color: #ffffff;">REF:</span></strong></td>
            <td style="text-align: right;" bgcolor="#092d50" colspan="2" ><strong><span style="color: #ffffff;">{{$mobile_payment->ref}}</span></strong></td>
          </tr>

          <tr>
            <td colspan="9" >&nbsp;</td>
          </tr>


          

          <tr>
            <td style="border-top: 1px solid #c0c0c0; border-bottom: 1px solid #c0c0c0; border-left: 1px solid #c0c0c0;" bgcolor="#092d50" ><strong><span style="color: #ffffff;">PAYEE #</span></strong></td>
            <td style="border: 1px solid #c0c0c0;" colspan="3" bgcolor="#092d50"><span style="color: #ffffff;width:30%;"><strong>Name</strong></span></td>
            <td style="border: 1px solid #c0c0c0;" bgcolor="#092d50"><strong><span style="color: #ffffff;">MOBILE PHONE</span></strong></td>
            <td style="border: 1px solid #c0c0c0;" bgcolor="#092d50"><strong><span style="color: #ffffff;"></span></strong></td>
            <td style="border: 1px solid #c0c0c0;" bgcolor="#092d50"><strong><span style="color: #ffffff;">TOTAL({{$mobile_payment->currency->currency_name}})</span></strong></td>
          </tr>

          @foreach ($mobile_payment->payees as $key => $payee)


          <tr>
            <td style="border-top: 1px solid #c0c0c0; border-bottom: 1px solid #c0c0c0; border-left: 1px solid #000000; border-right: 1px solid #000000;"  >{{$key+1}}</td>
            <td style="border-top: 1px solid #c0c0c0; border-bottom: 1px solid #c0c0c0; border-left: 1px solid #000000; border-right: 1px solid #000000;" colspan="3" >{{$payee->full_name}}</td>
            <td style="border-top: 1px solid #c0c0c0; border-bottom: 1px solid #c0c0c0; border-left: 1px solid #000000; border-right: 1px solid #000000;" align="right">{{$payee->mobile_number}}</td>
            <td style="border-top: 1px solid #c0c0c0; border-bottom: 1px solid #c0c0c0; border-left: 1px solid #000000; border-right: 1px solid #000000;" align="right"></td>
            <td style="border-top: 1px solid #c0c0c0; border-bottom: 1px solid #c0c0c0; border-left: 1px solid #000000; border-right: 1px solid #000000;" align="right" bgcolor="#E4E8F3">{{number_format($payee->calculated_total,2)}}</td>
          </tr>


          @endforeach


          <tr>
            <td style="border-top: 1px solid #000000;" colspan="10" >&nbsp;</td>
          </tr>
          <tr>
            <td ></td>
            <td ></td>
            <td ></td>
            <td ><span style="color: #ffffff;"></span></td>
            <td colspan="1" >SUBTOTALS</td>
            <td >{{$mobile_payment->currency->currency_name}}</td>
            <td align="right" bgcolor="#E4E8F3">{{number_format($mobile_payment->totals,2)}}</td>
          </tr>
          <tr>
            <td style="border-bottom: 2px solid #000000;" colspan="1" >Authorized by:</td>
            <td style="border-bottom: 2px solid #000000;" ></td>
            <td style="border-bottom: 2px solid #000000;" >Date:</td>
          </tr>

          @foreach ($mobile_payment->approvals as $key => $approval)
            @if ($approval->approval_level_id == 4)

              <tr>
                <td colspan="2" >
                  @isset($approval->approver_id)
                      <strong>{{$approval->approver->full_name}}</strong>
                  @endisset
                </td>
                <td >
                  <span>{{date('d F, Y', strtotime($approval->created_at)) }}</span>
                </td>
              </tr>
              <tr>
                <td colspan="4" rowspan="5" >
                  <img height = "60" alt="." src="{{asset('storage/app/staff/signature'.$approval->approver_id.'.png')}}"></img>
                </td>
                <td ></td>
              </tr>

            @endif
          @endforeach
          <tr>
            <td >&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
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