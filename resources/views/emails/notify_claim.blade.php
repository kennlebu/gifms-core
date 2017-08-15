<html>
<head>
</head>
<body  style="font-family: monospace;">
  <main>
    <div>
      Attn. {{$addressed_to->f_name}},
      <br/>
      <br/>
      @if ($claim->status_id==10||$claim->status_id==2||$claim->status_id==3||$claim->status_id==4)
        The below detailed Claim has been posted and awaits your approval.
      @elseif ($claim->status_id==9)
        The below detailed Claim has been Rejected by {{$claim->rejected_by->name}}
      @endif
      <br/>

    </div>

    <table style="width: 100%;font-size: smaller;border: 1px solid #c0c0c0;"  cellspacing="0">
      <tbody>
        <tr style="border-bottom: : 1px solid #c0c0c0;">
          <td style="text-align: left;" colspan="3"  height="30">
            <span style="text-decoration: underline;">
              <strong>
                <span style="color: #092d50; font-size: x-large; text-decoration: underline;">CLAIM</span>
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
              <span style="color: #092d50;">{{$claim->ref}}</span>
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
              <span style="color: #092d50;">{{$claim->expense_desc}}</span>
            </strong>
          </td>
        </tr>
          <td colspan="1"  bgcolor="#ffffff" height="20">
            <strong>
              <span style="color: #7c7c7c;">Project Manager:</span>
            </strong>
          </td>
          <td style="text-align: left;" bgcolor="#ffffff" colspan="2" >
            <strong>
              <span style="color: #092d50;">{{$claim->project_manager->name}}</span>
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
              <span style="color: #092d50;"> {{$claim->currency->currency_name}}. {{number_format($claim->amount, 2)}}</span>
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
              <span style="color: #092d50;">{{$claim->requested_by->name}}</span>
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
              <span style="color: #092d50;">{{$claim->request_date}}</span>
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
              @foreach ($claim->approvals as $key => $item)
                <span style="color: #092d50;">{{$item->approver->name}}</span>
                <span style="color: #092d50;">{{$item->created_at}}</span>
                <br/>
              @endforeach
            </strong>
          </td>
        </tr>
      <!--   @if ($claim->status_id==99)    
        <tr>
          <td colspan="1"  bgcolor="#ffffff" height="20">
            <strong>
              <span style="color: #7c7c7c;">Cancelled by:</span>
            </strong>
          </td>
          <td style="text-align: left;" bgcolor="#ffffff" colspan="2" >
            <strong>
              <span style="color: #092d50;">{{$claim->cancelled_by->name}}</span>
            </strong>
          </td>
        </tr> 
        @endif  -->
        @if ($claim->status_id==9) 
        <tr>
          <td colspan="1"  bgcolor="#ffffff" height="20">
            <strong>
              <span style="color: #7c7c7c;">Rejected By:</span>
            </strong>
          </td>
          <td style="text-align: left;" bgcolor="#ffffff" colspan="2" >
            <strong>
              <span style="color: #092d50;">{{$claim->rejected_by->name}}}</span>
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