<html>
<head>
</head>
<body  style="font-family: monospace;">
  <main>
    <div>
      Dear {{$addressed_to->f_name}},
      <br/>
      <br/>
      @if ($invoice->status_id==12||$invoice->status_id==1||$invoice->status_id==2||$invoice->status_id==3)
        The below detailed invoice has been posted and awaits your approval.
        <!-- 
      @elseif ($invoice->status_id==99)
        The below detailed invoice has been Cancelled by {{$invoice->cancelled_by->name}}
         -->
      @elseif ($invoice->status_id==9)
        The below detailed invoice has been Rejected by {{$invoice->rejected_by->name}}
      @endif
      <br/>

    </div>

    <table style="width: 100%;font-size: smaller;border: 1px solid #c0c0c0;"  cellspacing="0">
      <tbody>
        <tr style="border-bottom: : 1px solid #c0c0c0;">
          <td style="text-align: left;" colspan="3"  height="30">
            <span style="text-decoration: underline;">
              <strong>
                <span style="color: #092d50; font-size: x-large; text-decoration: underline;">INVOICE</span>
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
              <span style="color: #092d50;">{{$invoice->ref}}</span>
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
              <span style="color: #092d50;">{{$invoice->expense_desc}}</span>
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
              <span style="color: #092d50;">{{$invoice->project->project_name}}</span>
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
              <span style="color: #092d50;">{{$invoice->project_manager->name}}</span>
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
              <span style="color: #092d50;"> {{$invoice->currency->currency_name}}. {{number_format($invoice->amount, 2)}}</span>
            </strong>
          </td>
        </tr>    
        <tr>
          <td colspan="1"  bgcolor="#ffffff" height="20">
            <strong>
              <span style="color: #7c7c7c;">Raised By:</span>
            </strong>
          </td>
          <td style="text-align: left;" bgcolor="#ffffff" colspan="2" >
            <strong>
              <span style="color: #092d50;">{{$invoice->raised_by->name}}</span>
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
              <span style="color: #092d50;">{{$invoice->request_date}}</span>
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
              @foreach ($invoice->approvals as $key => $item)
                <span style="color: #092d50;">{{$item->approver->name}}</span>
                <span style="color: #092d50;">{{$item->created_at}}</span>
                <br/>
              @endforeach
            </strong>
          </td>
        </tr>
      <!--   @if ($invoice->status_id==99)    
        <tr>
          <td colspan="1"  bgcolor="#ffffff" height="20">
            <strong>
              <span style="color: #7c7c7c;">Cancelled by:</span>
            </strong>
          </td>
          <td style="text-align: left;" bgcolor="#ffffff" colspan="2" >
            <strong>
              <span style="color: #092d50;">{{$invoice->cancelled_by->name}}</span>
            </strong>
          </td>
        </tr> 
        @endif  -->
        @if ($invoice->status_id==9) 
        <tr>
          <td colspan="1"  bgcolor="#ffffff" height="20">
            <strong>
              <span style="color: #7c7c7c;">Rejected By:</span>
            </strong>
          </td>
          <td style="text-align: left;" bgcolor="#ffffff" colspan="2" >
            <strong>
              <span style="color: #092d50;">{{$invoice->rejected_by->name}}}</span>
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