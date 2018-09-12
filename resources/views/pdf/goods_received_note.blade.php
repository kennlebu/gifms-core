<html>

<head>
    <style>
        @page {
            margin: 150px 40px 120px 40px;
        }
        
        header {
            position: fixed;
            top: -130px;
            left: 0px;
            right: 0px;
            background-color: white;
            height: 120px;
        }
        
        footer {
            position: fixed;
            bottom: -110px;
            left: 0px;
            right: 0px;
            background-color: #E4E8F3;
            height: 100px;
        }
        
        footer:after {
            content: "[Page : " counter(page) "]";
        }
        
        .title {
            font-size: 13px;
            color: white;
            background-color: #092d50;
            border-top: 1px solid #c0c0c0;
            border-bottom: 1px solid #c0c0c0;
            border-left: 1px solid #c0c0c0;
        }
        
        .body {
            font-size: 12px;
            border-top: 1px solid #c0c0c0;
            border-bottom: 1px solid #c0c0c0;
            border-left: 1px solid #c0c0c0;
        }
        
        table {
            margin-top: 20px;
            width: 100%;
        }
    </style>
</head>

<body style="font-family: arial;">
    <header>
        <div><img style="width: 100%;height:120px;" src="img/letter_head_top_1200x240.png"></div>
    </header>
    <footer>
        <div><img style="width: 100%;height:80px;" src="img/letter_head_bottom_1200x125.png"></div>
    </footer>
    <main>
        <div>
            <div style="text-align:center;"><span style="text-decoration: underline;font-size:14px;"><strong><span style="color: #092d50; font-size: x-large; text-decoration: underline;">GOODS RECEIVED NOTE </span></strong>
            </div>

            <table cellspacing="0">
                <tr>
                    <td colspan="2" bgcolor="#092d50"><strong><span style="color: #ffffff;font-size:12px;">From (Supplier):</span></strong></td>
                    <td colspan="2">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="2">{{$delivery->supplier->supplier_name}}</td>
                    <td colspan="2"><strong>Delivery No:</strong> {{$delivery->external_ref}}</td>
                </tr>
                <tr>
                    <td colspan="2">{{$delivery->supplier->address}}</td>
                    <td colspan="2"><strong>LPO Ref:</strong> {{$delivery->lpo->ref}}</td>
                </tr>
                <tr>
                    <td colspan="2">{{$delivery->supplier->contact_phone_1}}</td>
                    <td colspan="2">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="2">{{$delivery->supplier->email}}</td>
                    <td colspan="2"><strong>Date:</strong> {{date('d F, Y', strtotime($delivery->created_at)) }}</td>
                </tr>
                <tr>
                    <td colspan="4">&nbsp;</td>
                </tr>


                <!-- <table style="width:100%;border:1px solid gray;margin-top:5px;"> -->
                <tr>
                    <th class="title" style="width:10%;border-left: 1px solid #000000;"> # </th>
                    <th class="title"> ITEM </th>
                    <th class="title"> DESCRIPTION </th>
                    <th class="title" style="width:20%;border-right: 1px solid #000000;"> QUANTITY </th>
                </tr>
                @foreach ($delivery->items as $key => $item)
                <tr>
                    <td class="body" style="width:10%;border-left: 1px solid #000000;">{{$key+1}}</td>
                    <td class="body">{{$item->item}}</td>
                    <td class="body">{{$item->item_description}}</td>
                    <td class="body" style="border-right: 1px solid #000000;">{{$item->qty_description}}</td>
                </tr>
                @endforeach
            </table>

            <div style="width:100%;display:inline-block">
                <div style="margin:0 10px 5px 0;">
                    <p>Received By <strong>{{$delivery->received_by->name}}</strong></p>
                </div>
            </div>

        </div>
    </main>
</body>

</html>