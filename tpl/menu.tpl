<div id="myjquerymenu" class="jquerycssmenu">
	<ul>
		<li><a href="{$offset_path}/php/home.php">Home</a></li>
		<li><a href="#">Entry</a>
			<ul>
				<li><a href="#">Purchase</a>
					<ul>
					<li><a href="{$offset_path}/php/order.php?activity=sel">Indent</a></li>
					<li><a href="{$offset_path}/php/receipt.php">Receipt</a></li>
					<li><a href="{$offset_path}/php/payment.php?action=sel">Payment</a></li>
					</ul>
				<li>
				<li><a href="#">Sales</a>
					<ul>
					<li><a href="{$offset_path}/php/order.php?activity=buy">Order</a></li>
					<li><a href="{$offset_path}/php/sales.php">Despatches</a></li>
					<li><a href="{$offset_path}/php/payment.php?action=buy">Invoice</a></li>
					</ul>
				</li>
				<li><a href="#"> Handloan</a>
					<ul>
					<li><a href="{$offset_path}/php/handloan.php?handln_type=issue">Issue</a></li>
					<li><a href="{$offset_path}/php/handloan.php?handln_type=receive">Receive</a></li>
					</ul>
				</li>
				<li><a href="#">Stakeholders</a>
					<ul>
					<li><a href="{$offset_path}/php/contacts.php?activity=sel">Supplier</a></li>
					<li><a href="{$offset_path}/php/contacts.php?activity=buy">Buyer</a></li>
					</ul>
				</li>
				{*<li><a href="#">Tax</a></li>*}
			</ul>
		</li>
		<li><a href="#">Edit</a>
			<ul>
				<li><a href="#">Receipt</a></li>
				<li><a href="#">Sales</a></li>
				<li><a href="{$offset_path}/php/stock.php">Stock</a></li>
				<li><a href="{$offset_path}/php/product.php">Product</a></li>
				{*<li><a href="#">Country</a></li>
				<li><a href="#">Tax</a></li>*}
			</ul>
		</li>
		<li><a href="#">Report</a>
			<ul>
				<li><a href="{$offset_path}/php/report.php?report_id=1">Receipt</a></li>
				<li><a href="{$offset_path}/php/report.php?report_id=2">Sales</a></li>
				<li><a href="{$offset_path}/php/report.php?report_id=3">Stock</a></li>
				<li><a href="{$offset_path}/php/report.php?report_id=4">Payment</a></li>
				<li><a href="{$offset_path}/php/report.php?report_id=5">Order</a></li>
				<li><a href="{$offset_path}/php/report.php?report_id=6">Handloan</a></li>
			</ul>
		</li>
	</ul>
	<form class="yform" id="src_form" name="src_form" method="GET" action="home.php">
		<div align="right">
			<select class="type-input" name="search_id" id="search_id" style="width:100px;padding:0px;" >
				<option value="">-None-</option>
			</select>
			<input type="text" name="search_value" id="search_value" size="8" maxlength="10" />
			<input type="submit" name="search" value="Search" />
		</div>	
	</form>
</div>