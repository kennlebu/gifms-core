<html>

<head>
</head>

<body style="font-family: monospace;">
    <main>
        <div>
            Attn. {{$delivery->received_for->f_name ?? ''}}
            <br/>
            <br/> The below delivery has been made by {{$lpo->supplier->supplier_name}} and received by {{$delivery->received_by->f_name}}
            <br/>

        </div>

        <table style="width: 100%;font-size: smaller;border: 1px solid #c0c0c0;" cellspacing="0">
            <tbody>
                <tr style="border-bottom: 1px solid #c0c0c0;">
                    <td style="text-align: left; border-bottom: 1px dotted #ccbcbc;" colspan="3" height="30">
                        <span style="text-decoration: underline;">
              <strong>
                <span style="color: #092d50; font-size: x-large; text-decoration: underline;">DELIVERY</span>
                        </strong>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: left; border-bottom: 1px dotted #ccbcbc;" colspan="1" bgcolor="#092d50" height="20">
                        <strong>
              <span style="color: #ffffff;">External-REF:</span>
            </strong>
                    </td>
                    <td style="text-align: left; border-bottom: 1px dotted #ccbcbc;" bgcolor="#ffffff" colspan="2">
                        <strong>
              <span style="color: #092d50;">{{$delivery->external_ref}}</span>
            </strong>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: left; border-bottom: 1px dotted #ccbcbc;" colspan="1" bgcolor="#ffffff" height="20">
                        <strong>
              <span style="color: #7c7c7c;">Supplier:</span>
            </strong>
                    </td>
                    <td style="text-align: left; border-bottom: 1px dotted #ccbcbc;" bgcolor="#ffffff" colspan="2">
                        <strong>
              <span style="color: #092d50;">{{$lpo->supplier->supplier_name}}</span>
            </strong>
                    </td>
                </tr>

                <tr>
                    <td style="text-align: left; border-bottom: 1px dotted #ccbcbc;" colspan="1" bgcolor="#ffffff" height="20">
                        <strong>
                <span style="color: #7c7c7c;">LPO:</span>
              </strong>
                    </td>
                    <td style="text-align: left; border-bottom: 1px dotted #ccbcbc;" bgcolor="#ffffff" colspan="2">
                        <strong>
                <span style="color: #092d50;">{{$lpo->ref}} - {{$lpo->expense_desc}} - {{$lpo->currency->currency_name}} {{number_format($lpo->totals, 2)}}</span>
              </strong>
                    </td>
                </tr>

                <tr>
                    <td style="text-align: left; border-bottom: 1px dotted #ccbcbc;" colspan="1" bgcolor="#ffffff" height="20">
                        <strong>
              <span style="color: #7c7c7c;">Amount:</span>
            </strong>
                    </td>
                    <td style="text-align: left; border-bottom: 1px dotted #ccbcbc;" bgcolor="#ffffff" colspan="2">
                        <strong>
              <span style="color: #092d50;"> {{$lpo->currency->currency_name}} {{number_format($lpo->totals, 2)}}</span>
            </strong>
                    </td>
                </tr>

                <tr>
                    <td style="text-align: left; border-bottom: 1px dotted #ccbcbc;" colspan="1" bgcolor="#ffffff" height="20">
                        <strong>
              <span style="color: #7c7c7c;">Comment:</span>
            </strong>
                    </td>
                    <td style="text-align: left; border-bottom: 1px dotted #ccbcbc;" bgcolor="#ffffff" colspan="2">
                        <strong>
              <span style="color: #092d50;">{{$delivery->comment}}</span>
            </strong>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: left; border-bottom: 1px dotted #ccbcbc;" colspan="1" bgcolor="#ffffff" height="20">
                        <strong>
              <span style="color: #7c7c7c;">Received By:</span>
            </strong>
                    </td>
                    <td style="text-align: left; border-bottom: 1px dotted #ccbcbc;" bgcolor="#ffffff" colspan="2">
                        <strong>
              <span style="color: #092d50;">{{$delivery->received_by->name}}</span>
            </strong>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: left; border-bottom: 1px dotted #ccbcbc;" colspan="1" bgcolor="#ffffff" height="20">
                        <strong>
              <span style="color: #7c7c7c;">Received at:</span>
            </strong>
                    </td>
                    <td style="text-align: left; border-bottom: 1px dotted #ccbcbc;" bgcolor="#ffffff" colspan="2">
                        <strong>
              <span style="color: #092d50;">{{$delivery->created_at}}</span>
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