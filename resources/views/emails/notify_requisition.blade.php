<html>
<head>
    <style>
        table, tr, th, td { border-collapse: collapse; vertical-align: top; }
        table { border: 1px solid #E0E0E0; box-shadow: 0px 0px 1px rgba(128, 128, 128, .2); margin: 1em 0; width: 100%; }
        table th, table td { border: solid #E0E0E0; border-width: 1px 0; padding: 8px 10px; }
        table th { background-color: #E0E0E0; font-weight: bold; text-align: left; }
    </style>
</head>
<body  style="font-family: monospace;">
  <main>
    <div>
      Attn. {{$addressed_to->f_name ?? ''}}
      <br/>
      <br/>
      @if ($requisition->status_id==2)
        The below detailed Requisition has been submitted and awaits your approval.
        @elseif ($requisition->status_id==3)
          The below detailed Requisition has been approved by {{$requisition->program_manager->f_name}} {{$requisition->program_manager->l_name}}
      @elseif ($requisition->status_id==4)
        The below detailed Requisition has been returned by {{$requisition->returned_by->f_name}} {{$requisition->returned_by->l_name}}
      @endif
      <br/>

    </div>

    <table style="width: 100%;font-size: smaller;border: 1px solid #c0c0c0;"  cellspacing="0">
      <tbody>
        <tr style="border-bottom: : 1px solid #c0c0c0;">
          <td style="text-align: left; border-bottom: 1px dotted #ccbcbc;" colspan="3"  height="30">
            <span style="text-decoration: underline;">
              <strong>
                <span style="color: #092d50; font-size: x-large; text-decoration: underline;">REQUISITION </span>
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
              <span style="color: #092d50;">{{$requisition->ref}}</span>
            </strong>
          </td>
        </tr> 
        <tr>
          <td style="text-align: left; border-bottom: 1px dotted #ccbcbc;" colspan="1"  bgcolor="#ffffff" height="20">
            <strong>
              <span style="color: #7c7c7c;">Expense purpose:</span>
            </strong>
          </td>
          <td style="text-align: left; border-bottom: 1px dotted #ccbcbc;" bgcolor="#ffffff" colspan="2" >
            <strong>
              <span style="color: #092d50;">{{$requisition->purpose}}</span>
            </strong>
          </td>
        </tr>>
        </tr>
        <tr>
          <td style="text-align: left; border-bottom: 1px dotted #ccbcbc;" colspan="1"  bgcolor="#ffffff" height="20">
            <strong>
              <span style="color: #7c7c7c;">Program Manager:</span>
            </strong>
          </td>
          <td style="text-align: left; border-bottom: 1px dotted #ccbcbc;" bgcolor="#ffffff" colspan="2" >
            <strong>
              <span style="color: #092d50;">{{$requisition->program_manager->f_name}} {{$requisition->program_manager->l_name}}</span>
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
              <span style="color: #092d50;">{{$requisition->requested_by->name}}</span>
            </strong>
          </td>
        </tr>
        <tr>
          <td style="text-align: left; border-bottom: 1px dotted #ccbcbc;" colspan="1"  bgcolor="#ffffff" height="20">
            <strong>
              <span style="color: #7c7c7c;">Items:</span>
            </strong>
          </td>
          <td style="text-align: left; border-bottom: 1px dotted #ccbcbc; padding:10px 1px 10px 1px;" bgcolor="#ffffff" colspan="2" >
            <table class="table table-bordered table-responsive">
                <thead>
                    <tr>
                        <th>Item/Service</th>
                        <th>Quantity</th>
                        <th>Quantity desc</th>
                        <th>Location</th>
                        <th>Dates</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($requisition->items as $item)
                        <tr>
                            <td>{{$item->service?? ''}}</td>
                            <td>{{$item->qty ?? ''}}</td>
                            <td>{{$item->qty_description ?? ''}}</td>
                            <td>{{$item->county->county_name ?? 'N/A'}}</td>
                            <td>{{date('Y-m-d', strtotime($item->start_date))}} to {{date('Y-m-d', strtotime($item->end_date))}}</td>
                            <td><span class="badge badge-default">{{$item->transaction_status}}</span></td>
                        </tr>
                    @endforeach                    
                </tbody>
            </table>
          </td>
        </tr>
        <tr>
          <td style="text-align: left; border-bottom: 1px dotted #ccbcbc;" colspan="1"  bgcolor="#ffffff" height="20">
            <strong>
              <span style="color: #7c7c7c;">Allocations:</span>
            </strong>
          </td>
          <td style="text-align: left; border-bottom: 1px dotted #ccbcbc; padding:10px 1px 10px 1px;" bgcolor="#ffffff" colspan="2" >
            <table class="table table-bordered table-responsive">
                <thead>
                  <tr>
                    <th>Project</th>
                    <th>Account</th>
                    <th>Objective</th>
                    <th>Rate</th>
                  </tr>
                </thead>
                <tbody>
                    @foreach ($requisition->allocations as $allocation)
                        <tr>
                            <td>{{$allocation->project->project_code ?? ''}} - {{$allocation->project->project_name ?? ''}}</td>
                            <td>{{$allocation->account->account_name ?? ''}}</td>
                            <td>{{$allocation->objective->objective ?? ''}}</td>
                            <td>{{number_format($allocation->percentage_allocated, 2)}}%</td>
                        </tr>
                    @endforeach                  
                </tbody>
              </table>
          </td>
        </tr>
        @if ($requisition->status_id==4) 
        <tr>
          <td style="text-align: left; border-bottom: 1px dotted #ccbcbc;" colspan="1"  bgcolor="#ffffff" height="20">
            <strong>
              <span style="color: #7c7c7c;">Returned By:</span>
            </strong>
          </td>
          <td style="text-align: left; border-bottom: 1px dotted #ccbcbc;" bgcolor="#ffffff" colspan="2" >
            <strong>
              <span style="color: #092d50;">{{$requisition->returned_by->f_name}} {{$requisition->returned_by->l_name}}</span><br/><br/>
              <span style="color: #092d50;">Reason: {{$requisition->return_reason}}</span>
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