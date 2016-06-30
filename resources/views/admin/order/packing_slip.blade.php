<!DOCTYPE html>
<html>
<head>
<title>Packing Slip</title>
<style>
body {
	width: 98%;
	padding: 10px;
	font-family: arial;
	font-size: 14px;
}

table {
	width: 100%
	padding: 5px;
	
}

.heading1 {
	background-color: #666;
	color: #fff;
	font-size:16px;
	font-weight: bold;
}


.subhead1 {
	font-weight: bold;
	text-align: right;
  width:auto;
}



.right {
  
  text-align: right;
  
}


.subhead2 {
	font-weight: bold;
	text-align: right;
	font-size: 16px;
}

h1 {
	color: #000;
	font-size:18px;
}

h2 {
	color: #000;
	font-size:14px;
}

.vline {
	border-left: thin solid #666;
}


.hline {
	border-bottom: thin solid #666;
}


.left {
	text-align:left;
}

.center {
	text-align:center;
}

.right {
	text-align:right;
}

.items {
	font-size: 13px;
}


</style>
</head>
<body>

<table width="100%" cellpadding="4">
	<tr><td class="heading1 center">Packing Slip</td></tr>
	<tr><td>
		<h1>Miramix Inc</h1>
		<h2>
		{{COMPANY_ADDRESS}}
		</h2><br />
	</td></tr>
</table>

<table width="100%" border="0">
	
	<tr>
		<td align="right" width="15%" valign="top"><b>Ship To:</b></td>
		<td width="35%" valign="top">
			{{RECIPIENT_NAME}}<br />
			{{RECIPIENT_ADDRESS}} <br />
			{{RECIPIENT_CITY}}, {{RECIPIENT_STATE}} {{RECIPIENT_ZIP}}<br />
      {{RECIPIENT_COUNTRY}}
		</td>
		<td align="left" width="50%">
			<table cellpadding="4" cellspacing="0">
				<tr><td class="subhead1" width="70%">Order#</td><td class="vline" width="10%"></td><td width="20%">{{ORDER_NUMBER}}</td></tr>
				<tr><td class="subhead1">Date</td><td class="vline"></td><td>{{ORDER_DATE}}</td></tr>
				<tr><td class="subhead1">User</td><td class="vline"></td><td>{{USERNAME}}</td></tr>
				<tr><td class="subhead1">Ship Date</td><td class="vline"></td><td>{{SHIP_DATE}}</td></tr>
			</table>
		</td>
		
		
		
	</tr>

</table>
<br />
<table width="100%" cellpadding="2" class="items">
	<tr class="heading1"><td class="left" width="50%">Description</td><td class="right" width="10%">Price</td><td class="center" width="10%">Qty</td><td class="right" width="20%">Ext. Price</td></tr>

  {{ODER_ITEMS}}

	<tr><td class="hline" colspan="4"></td></tr>
	<tr><td colspan="3" class="subhead1">Sub Total:</td><td class="right">${{SUB_TOTAL}}</td></tr>
  <tr><td colspan="3" class="subhead1">Discounts:</td><td class="right">(-) ${{DISCOUNTS}}</td></tr>
	<tr><td colspan="3" class="subhead1">Shipping:</td><td class="right">${{SHIPPINGS}}</td></tr>
  <tr><td class="hline" colspan="4"></td></tr>
	<tr><td colspan="3" class="subhead1">Total:</td><td class="right">${{TOTAL}}</td></tr>
</table>


</body>
</html>
