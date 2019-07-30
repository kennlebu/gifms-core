<html>
<head>
</head>
<body  style="font-family: monospace;">
  <main>
    <div>
      Attn. {{$addressed_to->f_name ?? ''}}
      <br/>
      <br/>
      @if ($leave_request->status_id==2)
        The below Leave Request has been posted and awaits your approval.
      @elseif ($leave_request->status_id==3)
        The below Leave Reqeust has been Approved by {{$leave_request->line_manager->name}}
      @elseif ($leave_request->status_id==4)
        The below Leave Request has been Rejected by {{$leave_request->rejected_by->name}}
        @elseif ($leave_request->status_id==6)
          Cancellation of the below Leave Request has been approved by {{$leave_request->rejected_by->name}}
      @endif
      <br/>

    </div>

    <table style="width: 100%;font-size: smaller;border: 1px solid #c0c0c0;"  cellspacing="0">
      <tbody>
        <tr style="border-bottom: : 1px solid #c0c0c0;">
          <td style="text-align: left; border-bottom: 1px dotted #ccbcbc;" colspan="3"  height="30">
            <span style="text-decoration: underline;">
              <strong>
                <span style="color: #092d50; font-size: x-large; text-decoration: underline;">LEAVE REQUEST </span>
              </strong>
            </span>
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
              <span style="color: #092d50;">{{$leave_request->requested_by->name}}</span>
            </strong>
          </td>
        </tr>
        <tr>
          <td style="text-align: left; border-bottom: 1px dotted #ccbcbc;" colspan="1"  bgcolor="#ffffff" height="20">
            <strong>
              <span style="color: #7c7c7c;">Type of leave:</span>
            </strong>
          </td>
          <td style="text-align: left; border-bottom: 1px dotted #ccbcbc;" bgcolor="#ffffff" colspan="2" >
            <strong>
              <span style="color: #092d50;">{{$leave_request->leave_type->name}}</span>
            </strong>
          </td>
        </tr> 
        <tr>
          <td style="text-align: left; border-bottom: 1px dotted #ccbcbc;" colspan="1"  bgcolor="#ffffff" height="20">
            <strong>
              <span style="color: #7c7c7c;">Leave Dates:</span>
            </strong>
          </td>
          <td style="text-align: left; border-bottom: 1px dotted #ccbcbc;" bgcolor="#ffffff" colspan="2" >
            <strong>
              <span style="color: #092d50;"><strong>From</strong> {{$leave_request->start_date}}
                    &nbsp;<strong>To</strong> {{$leave_request->end_date}} ({{$leave_request->no_of_days}} days)</span>
            </strong>
          </td>
        </tr>
        @if(empty($leave_request->requester_comments))
        <tr>
          <td style="text-align: left; border-bottom: 1px dotted #ccbcbc;" colspan="1"  bgcolor="#ffffff" height="20">
            <strong>
              <span style="color: #7c7c7c;">Comments/Remarks:</span>
            </strong>
          </td>
          <td style="text-align: left; border-bottom: 1px dotted #ccbcbc;" bgcolor="#ffffff" colspan="2" >
            <strong>
              <span style="color: #092d50;">{{$leave_request->requester_comments}}</span>
            </strong>
          </td>
        </tr> 
        @endif   
        <tr>
          <td style="text-align: left; border-bottom: 1px dotted #ccbcbc;" colspan="1"  bgcolor="#ffffff" height="20">
            <strong>
              <span style="color: #7c7c7c;">Requested at:</span>
            </strong>
          </td>
          <td style="text-align: left; border-bottom: 1px dotted #ccbcbc;" bgcolor="#ffffff" colspan="2" >
            <strong>
              <span style="color: #092d50;">{{$leave_request->created_at}}</span>
            </strong>
          </td>
        </tr>
        @if ($leave_request->status_id==3) 
        <tr>
          <td style="text-align: left; border-bottom: 1px dotted #ccbcbc;" colspan="1"  bgcolor="#ffffff" height="20">
            <strong>
              <span style="color: #7c7c7c;">Approved By:</span>
            </strong>
          </td>
          <td style="text-align: left; border-bottom: 1px dotted #ccbcbc;" bgcolor="#ffffff" colspan="2" >
            <strong>
              <span style="color: #092d50;">{{$leave_request->line_manager->name}}</span><br/>
              {{-- <span style="color: #092d50;">{{$leave_request->approved_at}}</span><br/><br/> --}}
              {{-- <span style="color: #092d50;">Reason: {{$leave_request->rejection_reason}}</span> --}}
            </strong>
          </td>
        </tr>   
        {{-- @endif  --}}
        @elseif ($leave_request->status_id==4) 
        <tr>
          <td style="text-align: left; border-bottom: 1px dotted #ccbcbc;" colspan="1"  bgcolor="#ffffff" height="20">
            <strong>
              <span style="color: #7c7c7c;">Rejected By:</span>
            </strong>
          </td>
          <td style="text-align: left; border-bottom: 1px dotted #ccbcbc;" bgcolor="#ffffff" colspan="2" >
            <strong>
              <span style="color: #092d50;">{{$leave_request->rejected_by->name}}</span><br/>
              <span style="color: #092d50;">{{$leave_request->rejected_at}}</span><br/><br/>
              <span style="color: #092d50;">Reason: {{$leave_request->rejection_reason}}</span>
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