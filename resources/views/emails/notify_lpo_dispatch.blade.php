<html>
<head>
</head>
<body  style="font-family: monospace;">
  <main>
    <div>
      @if(empty($lpo->lpo_type)||$lpo->lpo_type!='prenegotiated')
      Dear {{$lpo->preffered_quotation->supplier->contact_name_1 ?? ''}}
      @elseif(!empty($lpo->lpo_type)&&$lpo->lpo_type=='prenegotiated')
      Dear {{$lpo->supplier->contact_name_1 ?? ''}}
      @endif
      <br/>
      <br/>
      <p>
    Your Local Purchase Order(LPO) No.: {{$lpo_no}} - {{$lpo->expense_desc}} of value {{$lpo->currency->currency_name}} {{number_format($lpo->totals,2)}} has been successfully processed.
</p>
<p>Please find a copy attached for you to download and process accordingly.</p>
<p>Please note all payments are made via electronic transfer so kindly submit your Bank Details to our offices.</p>
<p>Should you have any questions, or queries on the above, please do not hesitate to get in touch with us via:<br/><br/>
3rd flr, Timau Plaza, Arwings Kodhek Road,<br/>
P O Box 2011-00100 Nairobi, Kenya<br/>
(t) : 254 20 514 3100/5<br/>
(e) : <a href="mailto:jayuma@clintonhealthaccess.org">jayuma@clintonhealthaccess.org</a><br/>
<a href="https://www.clintonhealthaccess.org">www.clintonhealthaccess.org</a>
</p><br/>
Best regards,<br/><br/>
<em>Clinton Health Access Initiative (Kenya) - Finance Team</em><br/><br/><br/>
      <br/>

    </div>
    <div>
    383 Dorchestor Avenue. Suite 400. Boston MA 02127. Tel 617-774-0220<br/>
    <strong>Clinton Health Access Initiative</strong>

    </div>
  </main>
</body>
</html>