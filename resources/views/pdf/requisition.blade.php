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
      <table style="width:100%;font-size:13px;" cellspacing="0">
        <tbody>
          <tr>
            <td style="text-align:center;font-size:14px;" colspan="10"  height="30"><span style="text-decoration: underline;"><strong><span style="color:#092d50; font-size: x-large; text-decoration: underline;">REQUISITION</span></strong></span></td>
          </tr>
          <tr>
            <td colspan="1" style="border-bottom:1px solid black;border-right:1px solid black;">Ref No.: {{$requisition->ref}}</td>
            <td colspan="7"></td>
            <td colspan="2" style="text-align:right;border-bottom:1px solid black;border-left:1px solid black;">Date: {{date('d m Y', strtotime($requisition->created_at))}}</td>
          </tr>
          <tr>
            <td colspan="10">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="2">Requested by:</td>
            <td colspan="8">{{$requisition->requested_by->f_name}} {{$requisition->requested_by->l_name}}</td>
          </tr>
          <tr>
            <td colspan="2">Program Manager:</td>
            <td colspan="8">{{$requisition->program_manager->f_name}} {{$requisition->program_manager->l_name}}</td>
          </tr>
          <tr>
            <td colspan="2">Purpose/Activity:</td>
            <td colspan="8">{{$requisition->purpose}}</td>
          </tr>
          <tr>
            <td colspan="10">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="10"><strong>Allocations</strong></td>
          </tr>
          <tr>
            <th colspan="3" style="border:1px solid black;">Project</th>
            <th colspan="3" style="border:1px solid black;">Account</th>
            <th colspan="2" style="border:1px solid black;">Objective</th>
            <th colspan="1" style="border:1px solid black;">Rate %</th>
            <th>&nbsp;</th>
          </tr>
          @foreach ($requisition->allocations as $allocation)
          <tr>
            <td colspan="3" style="border:1px solid black;">{{$allocation->project->project_code}} - {{$allocation->project->project_name}}</td>
            <td colspan="3" style="border:1px solid black;">{{$allocation->account->account_name}}</td>
            <td colspan="2" style="border:1px solid black;">{{$allocation->objective->objective}}</td>
            <td colspan="1" style="border:1px solid black;">{{$allocation->percentage_allocated}}</td>
            <td>&nbsp;</td>
          </tr>
          @endforeach
          <tr>
            <td colspan="10">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="10"><strong>Items</strong></td>
          </tr>
          <tr>
            <th colspan="3" style="border:1px solid black;">Item</th>
            <th colspan="3" style="border:1px solid black;">Quantity</th>
            <th colspan="3" style="border:1px solid black;">Dates</th>
            <th>&nbsp;</th>
          </tr>
          @foreach ($requisition->items as $item)
          <tr>
            <td colspan="3" style="border:1px solid black;">{{$item->service}}</td>
            <td colspan="3" style="border:1px solid black;">{{$item->qty_description}}</td>
            <td colspan="3" style="border:1px solid black;">{{date('jS F Y', strtotime($item->start_date))}} to {{date('jS F Y', strtotime($item->end_date))}}</td>
            <td>&nbsp;</td>
          </tr>
          @endforeach
          <tr>
            <td colspan="10">&nbsp;</td>
          </tr>
          @foreach ($unique_approvals as $key => $approval)
          <tr>
            <td colspan="3">Approved by:</td>
            <td colspan="7">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="3">
              <img style="height:70px;width:200px;" alt="." src="{{asset('storage/signatures/signature'.$approval->approver_id.'.png')}}"><br/>
              @isset($approval->approver_id)
                <strong>{{$approval->approver->full_name}}</strong>
              @endisset
            </td>
            <td colspan="7">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="3">
              <strong>Date:</strong><br/>
              <span>{{date('d F, Y', strtotime($approval->created_at)) }}</span>
            </td>
            <td colspan="7">&nbsp;</td>
          </tr>
          @endforeach
          <tr>
            <td colspan="10">&nbsp;</td>
          </tr>
        </tbody>
      </table>
    </div>
  </main>
</body>
</html>