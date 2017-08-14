<html>
<head>
</head>
<body  style="font-family: monospace;">
  <main>
    <div>
      Dear {{$addressed_to->f_name}},
      <br/>
      <br/>
      @if ($lpo->status_id==13||$lpo->status_id==3||$lpo->status_id==4||$lpo->status_id==5)
        The below detailed LPO has been posted and awaits your approval.
      @elseif ($lpo->status_id==11)
        The below detailed LPO has been Cancelled by {{$lpo->cancelled_by->name}}
      @elseif ($lpo->status_id==12)
        The below detailed LPO has been Rejected by {{$lpo->rejected_by->name}}
      @endif
      <br/>

    </div>

    <table style="width: 100%;font-size: smaller;border: 1px solid #c0c0c0;"  cellspacing="0">
      <tbody>
        <tr style="border-bottom: : 1px solid #c0c0c0;">
          <td style="text-align: left;" colspan="3"  height="30">
            <span style="text-decoration: underline;">
              <strong>
                <span style="color: #092d50; font-size: x-large; text-decoration: underline;">LOCAL PURCHASE ORDER </span>
              </strong>
            </span>
          </td>
        </tr>
        <tr>
          <td colspan="1"  bgcolor="#092d50" height="20">
            <strong>
              <span style="color: #ffffff;">REF:</span>
            </strong>
          </td>
          <td style="text-align: left;" bgcolor="#ffffff" colspan="2" >
            <strong>
              <span style="color: #092d50;">{{$lpo->ref}}</span>
            </strong>
          </td>
        </tr> 
        <tr>
          <td colspan="1"  bgcolor="#ffffff" height="20">
            <strong>
              <span style="color: #7c7c7c;">Title:</span>
            </strong>
          </td>
          <td style="text-align: left;" bgcolor="#ffffff" colspan="2" >
            <strong>
              <span style="color: #092d50;">{{$lpo->expense_desc}}</span>
            </strong>
          </td>
        </tr>
        <tr>
          <td colspan="1"  bgcolor="#ffffff" height="20">
            <strong>
              <span style="color: #7c7c7c;">Project:</span>
            </strong>
          </td>
          <td style="text-align: left;" bgcolor="#ffffff" colspan="2" >
            <strong>
              <span style="color: #092d50;">{{$lpo->project->project_name}}</span>
            </strong>
          </td>
        </tr> 
        <tr>
          <td colspan="1"  bgcolor="#ffffff" height="20">
            <strong>
              <span style="color: #7c7c7c;">Project Manager:</span>
            </strong>
          </td>
          <td style="text-align: left;" bgcolor="#ffffff" colspan="2" >
            <strong>
              <span style="color: #092d50;">{{$lpo->project_manager->name}}</span>
            </strong>
          </td>
        </tr> 
        <tr>
          <td colspan="1"  bgcolor="#ffffff" height="20">
            <strong>
              <span style="color: #7c7c7c;">Amount:</span>
            </strong>
          </td>
          <td style="text-align: left;" bgcolor="#ffffff" colspan="2" >
            <strong>
              <span style="color: #092d50;"> {{$lpo->currency->currency_name}}. {{number_format($lpo->amount, 2)}}</span>
            </strong>
          </td>
        </tr>    
        <tr>
          <td colspan="1"  bgcolor="#ffffff" height="20">
            <strong>
              <span style="color: #7c7c7c;">Requested By:</span>
            </strong>
          </td>
          <td style="text-align: left;" bgcolor="#ffffff" colspan="2" >
            <strong>
              <span style="color: #092d50;">{{$lpo->requested_by->name}}</span>
            </strong>
          </td>
        </tr>  
        <tr>
          <td colspan="1"  bgcolor="#ffffff" height="20">
            <strong>
              <span style="color: #7c7c7c;">Requested Date:</span>
            </strong>
          </td>
          <td style="text-align: left;" bgcolor="#ffffff" colspan="2" >
            <strong>
              <span style="color: #092d50;">{{$lpo->request_date}}</span>
            </strong>
          </td>
        </tr> 
        <tr>
          <td colspan="1"  bgcolor="#ffffff" height="20">
            <strong>
              <span style="color: #7c7c7c;">Approved By:</span>
            </strong>
          </td>
          <td style="text-align: left;" bgcolor="#ffffff" colspan="2" >
            <strong>
              @foreach ($lpo->approvals as $key => $item)
                <span style="color: #092d50;">{{$item->approver->name}}</span>
                <span style="color: #092d50;">{{$item->created_at}}</span>
                <br/>
              @endforeach
            </strong>
          </td>
        </tr>     
      </tbody>
    </table>

    <div>
      Login <a href="{{$js_url}}">here</a> to proceed

    </div>
  </main>
</body>
</html>