<html>
<head>
</head>
<body  style="font-family: monospace;">
  <main>
    <div>
      Dear {{$lpo->preffered_quotation->supplier->contact_name_1}},
      <br/>
      <br/>
      <p>
    This is to formally inform you that your Local Purchase Order(LPO) No.: {{$lpo_no}} - {{$lpo->expense_desc}} of value {{$lpo->currency->currency_name}} {{number_format($lpo->totals,2)}} has been cancelled.
</p>
<p>The original LPO document has been attached for your referrence.</p>
<p>Should you have any questions, or queries on the above, please do not hesitate to get in touch with us via:<br/><br/>
3rd flr, Timau Plaza, Arwings Kodhek Road,<br/>
P O Box 2011-00100 Nairobi, Kenya<br/>
(t) : 254 20 514 3100/5<br/>
(e) : <a href="mailto:jayuma@clintonhealthaccess.org">jayuma@clintonhealthaccess.org<a/><br/>
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