<html>
<head>
  <style>
    @page { margin: 150px 40px 120px 40px; }
    header { position: fixed; top: -130px; left: 0px; right: 0px; background-color: white; height: 120px; }
    footer { position: fixed; bottom: -110px; left: 0px; right: 0px; background-color: #E4E8F3; height: 100px; }
    p { page-break-after: always; }
    p:last-child { page-break-after: never; }
    footer:after {
      content: "[Page : " counter(page) "]";
    }
  </style>
</head>
<body  style="font-family: arial;">
  <header><div><img style="width: 100%;height:120px;" src="img/letter_head_top_1200x240.png"></div></header>
  <footer><div><img style="width: 100%;height:80px;" src="img/letter_head_bottom_1200x125.png"></div></footer>
  <main>
    <div>
      <table style="width: 100%;font-size:13px;" cellspacing="0">
        <tbody>
          <tr>
            <td style="text-align:center;font-size:14px;" colspan="10"  height="30"><span style="text-decoration: underline;"><strong><span style="color: #092d50; font-size: x-large; text-decoration: underline;">ASSET DONATION DOCUMENT </span></strong></span></td>
          </tr>
          <tr>
            <td style="font-size:13px;border-top: 1px solid #c0c0c0; border-bottom: 1px solid #c0c0c0; border-left: 1px solid #c0c0c0;" colspan="3" bgcolor="#092d50" ><strong><span style="color: #ffffff;">ASSET DESCRIPTION</span></strong></td>
            <td style="font-size:13px;border: 1px solid #c0c0c0;" bgcolor="#092d50"><span style="color: #ffffff;width:30%;"><strong>TYPE</strong></span></td>
            <td style="font-size:13px;border: 1px solid #c0c0c0;" bgcolor="#092d50"><strong><span style="color: #ffffff;">TAG</span></strong></td>
            <td style="font-size:13px;border: 1px solid #c0c0c0;" bgcolor="#092d50"><strong><span style="color: #ffffff;">SERIAL NO.</span></strong></td>
            <td style="font-size:13px;border: 1px solid #c0c0c0;" bgcolor="#092d50"><span style="color: #ffffff;"><strong>DATE OF ISSUE</strong></span></td>
            <td style="font-size:13px;border: 1px solid #c0c0c0;" bgcolor="#092d50"><span style="color: #ffffff;"><strong>COMMENTS</strong></span></td>
          </tr>

          @foreach ($lpo->items as $key => $item)
          <tr>
            <td style="border-top:1px solid #c0c0c0;border-bottom:1px solid #c0c0c0;border-left:1px solid #000000;border-right:1px solid #000000;font-size:12px;" colspan="3">{{$asset->title}}</td>
            <td style="border-top:1px solid #c0c0c0;border-bottom:1px solid #c0c0c0;border-left:1px solid #000000;border-right:1px solid #000000;font-size:12px;">{{$asset->type->type || '--'}}</td>
            <td style="border-top:1px solid #c0c0c0;border-bottom:1px solid #c0c0c0;border-left:1px solid #000000;border-right:1px solid #000000;font-size:12px;">{{$asset->tag}}</td>
            <td style="border-top:1px solid #c0c0c0;border-bottom:1px solid #c0c0c0;border-left:1px solid #000000;border-right:1px solid #000000;font-size:12px;">{{$asset->serial_no}}</td>
            <td style="border-top:1px solid #c0c0c0;border-bottom:1px solid #c0c0c0;border-left:1px solid #000000;border-right:1px solid #000000;font-size:12px;">{{$asset->date_of_issue}}</td>
            <td style="border-top:1px solid #c0c0c0;border-bottom:1px solid #c0c0c0;border-left:1px solid #000000;border-right:1px solid #000000;font-size:12px;" align="center">{{$asset->comments || '--'}}</td>
          </tr>
          @endforeach

          <tr>
            <td style="border-top: 1px solid #000000;" colspan="10" >&nbsp;</td>
          </tr>

          <tr>
              <td colspan="3">Authorized by:<br/>
                <img style="height:70px;width:200px;" alt="." src="{{asset('storage/signatures/signature'.$transfer->approved_by_id.'.png')}}"><br/>
                @isset($transfer->approved_by_id)
                    <strong>{{$transfer->approved_by->full_name}}</strong>
                @endisset
              </td>
              <td colspan="2">Date:<br/>
                <span>{{date('d F, Y', strtotime($transfer->created_at)) }}</span>
              </td>
            </tr>
            <tr>
                <td colspan="10">&nbsp;</td>
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