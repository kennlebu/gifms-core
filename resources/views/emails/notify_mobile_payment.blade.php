<html>
<head>
</head>
<body  style="font-family: monospace;">
  <main>
    <div>
      Attn. {{$addressed_to->f_name}},
      <br/>
      <br/>
      @if ($mobile_payment->status_id==9||$mobile_payment->status_id==2||$mobile_payment->status_id==3||$mobile_payment->status_id==4)
        The below detailed Bulk Mobile Payment has been posted and awaits your approval.
      @elseif ($mobile_payment->status_id==7)
        The below detailed Bulk Mobile Payment has been Rejected by {{$mobile_payment->rejected_by->name}}
      @elseif ($mobile_payment->status_id==12)
      The below detailed Bulk Mobile Payment has been corrected and awaits your approval.
      @endif
      <br/>

    </div>

    <table style="width: 100%;font-size: smaller;border: 1px solid #c0c0c0;"  cellspacing="0">
      <tbody>
        <tr style="border-bottom: : 1px solid #c0c0c0;">
          <td style="text-align: left; border-bottom: 1px dotted #ccbcbc;" colspan="3"  height="30">
            <span style="text-decoration: underline;">
              <strong>
                <span style="color: #092d50; font-size: x-large; text-decoration: underline;">MOBILE PAYMENT </span>
              </strong>
            </span>
          </td>
        </tr>
        <tr>
          <td style="text-align: left; border-bottom: 1px dotted #ccbcbc;" colspan="1"  bgcolor="#092d50" height="20">
            <strong>
              <span style="color: #ffffff;">REF:</span>
            </strong>
          </td>
          <td style="text-align: left; border-bottom: 1px dotted #ccbcbc;" bgcolor="#ffffff" colspan="2" >
            <strong>
              <span style="color: #092d50;">{{$mobile_payment->ref}}</span>
            </strong>
          </td>
        </tr> 
        <tr>
          <td style="text-align: left; border-bottom: 1px dotted #ccbcbc;" colspan="1"  bgcolor="#ffffff" height="20">
            <strong>
              <span style="color: #7c7c7c;">Title:</span>
            </strong>
          </td>
          <td style="text-align: left; border-bottom: 1px dotted #ccbcbc;" bgcolor="#ffffff" colspan="2" >
            <strong>
              <span style="color: #092d50;">{{$mobile_payment->expense_desc}}</span>
            </strong>
          </td>
        </tr>
        <tr>
          <td style="text-align: left; border-bottom: 1px dotted #ccbcbc;" colspan="1"  bgcolor="#ffffff" height="20">
            <strong>
              <span style="color: #7c7c7c;">Purpsoe:</span>
            </strong>
          </td>
          <td style="text-align: left; border-bottom: 1px dotted #ccbcbc;" bgcolor="#ffffff" colspan="2" >
            <strong>
              <span style="color: #092d50;">{{$mobile_payment->expense_purpose}}</span>
            </strong>
          </td>
        </tr>
        <tr>
          <td style="text-align: left; border-bottom: 1px dotted #ccbcbc;" colspan="1"  bgcolor="#ffffff" height="20">
            <strong>
              <span style="color: #7c7c7c;">Project Manager:</span>
            </strong>
          </td>
          <td style="text-align: left; border-bottom: 1px dotted #ccbcbc;" bgcolor="#ffffff" colspan="2" >
            <strong>
              <span style="color: #092d50;">{{$mobile_payment->project_manager->name}}</span>
            </strong>
          </td>
        </tr> 
        <tr>
          <td style="text-align: left; border-bottom: 1px dotted #ccbcbc;" colspan="1"  bgcolor="#ffffff" height="20">
            <strong>
              <span style="color: #7c7c7c;">Amount:</span>
            </strong>
          </td>
          <td style="text-align: left; border-bottom: 1px dotted #ccbcbc;" bgcolor="#ffffff" colspan="2" >
            <strong>
              <span style="color: #092d50;"> {{$mobile_payment->currency->currency_name}}. {{number_format($mobile_payment->totals, 2)}}</span>
            </strong>
          </td>
        </tr>    
        <tr>
          <td style="text-align: left; border-bottom: 1px dotted #ccbcbc;" colspan="1"  bgcolor="#ffffff" height="20">
            <strong>
              <span style="color: #7c7c7c;">Requested By:</span>
            </strong>
          </td>
          <td style="text-align: left; border-bottom: 1px dotted #ccbcbc;" bgcolor="#ffffff" colspan="2" >
            <strong>
              <span style="color: #092d50;">{{$mobile_payment->requested_by->name}}</span>
            </strong>
          </td>
        </tr>  
        <tr>
          <td style="text-align: left; border-bottom: 1px dotted #ccbcbc;" colspan="1"  bgcolor="#ffffff" height="20">
            <strong>
              <span style="color: #7c7c7c;">Requested at:</span>
            </strong>
          </td>
          <td style="text-align: left; border-bottom: 1px dotted #ccbcbc;" bgcolor="#ffffff" colspan="2" >
            <strong>
              <span style="color: #092d50;">{{$mobile_payment->requested_at}}</span>
            </strong>
          </td>
        </tr> 
        <tr>
          <td style="text-align: left; border-bottom: 1px dotted #ccbcbc;" colspan="1"  bgcolor="#ffffff" height="20">
            <strong>
              <span style="color: #7c7c7c;">Approved By:</span>
            </strong>
          </td>
          <td style="text-align: left; border-bottom: 1px dotted #ccbcbc;" bgcolor="#ffffff" colspan="2" >
            <strong>
              @foreach ($mobile_payment->approvals as $key => $item)
                @isset($item->approver_id)
                  <span style="color: #092d50;">{{$item->approver->name}}</span>
                  <span style="color: #092d50;">{{$item->created_at}}</span>
                  <br/>
                @endisset
              @endforeach
            </strong>
          </td>
        </tr>
        @if ($mobile_payment->status_id==7) 
        <tr>
          <td style="text-align: left; border-bottom: 1px dotted #ccbcbc;" colspan="1"  bgcolor="#ffffff" height="20">
            <strong>
              <span style="color: #7c7c7c;">Rejected By:</span>
            </strong>
          </td>
          <td style="text-align: left; border-bottom: 1px dotted #ccbcbc;" bgcolor="#ffffff" colspan="2" >
            <strong>
              <span style="color: #092d50;">{{$mobile_payment->rejected_by->name}}</span><br/>
              <span style="color: #092d50;">{{$mobile_payment->rejected_at}}</span><br/><br/>
              <span style="color: #092d50;">Reason: {{$mobile_payment->rejection_reason}}</span>
            </strong>
          </td>
        </tr>   
        @endif         
      </tbody>
    </table>

    <div>
      Login <a href="{{$js_url}}">here</a> to proceed

    </div>
  </main>
</body>
</html>