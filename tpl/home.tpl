<!-- begin: #col1 - first float column -->
<div id="col1">
	<div id="col1_content" >
		<!-- skiplink anchor: Content -->
                <div class="subcolumns equalize box-top">
            		<div class="c33l">
              			<div class="subcl">
				<!-- Insert your subtemplate content here -->
				<h6>Top Sales Today ( Kgs )</h6>
				<table>
				{section name=id loop=$sales_array}
				<tr>
					<td style="width:200px;">{$reorder_array[id].buyer}</td>
					<td style="width:50px;">{$reorder_array[id].quantity}</td>
				</tr>
				{/section}
				</table>
				</div>
            		</div>
           		<div class="c33l">
              			<div class="subc">
				<!-- Insert your subtemplate content here -->
				<h6>Payment Today ( Lakhs )</h6>
				<table>
				{section name=id loop=$payment_array}
				<tr>
					<td style="width:200px;">{$reorder_array[id].buyer}</td>
					<td style="width:50px;">{$reorder_array[id].value}</td>
				</tr>
				{/section}
				</table>
				</div>
            		</div>
			<div class="c33r">
				<div class="subcr">
				<!-- Insert your subtemplate content here -->
				<h6>Top Outstandings ( Lakhs )</h6>
				<table>
				{section name=id loop=$outstanding_array}
				<tr>
					<td style="width:200px;">{$reorder_array[id].buyer}</td>
					<td style="width:50px;">{$reorder_array[id].value}</td>
				</tr>
				{/section}
				</table>
				</div>
			</div>
          	</div>
         	<div class="subcolumns equalize no-ie-padding box-bottom">
            		<div class="c33l">
             			<div class="subcl"><a href="#" class="noprint">&rarr; read more<span class="hideme"> about Topic One</span>.</a></div>
            		</div>
            		<div class="c33l">
              			<div class="subc"><a href="#" class="noprint">&rarr; read more<span class="hideme"> about Topic Two</span>.</a></div>
            		</div>
			<div class="c33r">
              			<div class="subcr"><a href="#" class="noprint">&rarr; read more<span class="hideme"> about Topic Tree</span>.</a></div>
            		</div>
		</div>
{*
<!-- 		<div class="important"> -->
<!--  		<script type="text/javascript" src="http://www.brainyquote.com/link/quotebr.js"></script> -->
		<b>Quote of the Day</b><br>
		Laughter is America's most important export.<br>
		<a href="http://www.brainyquote.com/quotes/authors/w/walt_disney.html">Walt Disney</a>
		<br>
		<small><i>more <a href="http://www.brainyquote.com/">Famous Quotes</a></i></small>
		</div>         
 *}         
        </div>
</div>
<div id="col3">
        <div id="col3_content" class="clearfix">
          	<div class="info" style="text-align:left;">
          		<h2>Stocks @ Glance</h2>
          			<h3>Reorder ( Kgs )</h3>
				<table>
				{section name=id loop=$reorder_array}
				<tr>
					<td style="width:200px;">{$reorder_array[id].product_desc}</td>
					<td style="width:50px;">{$reorder_array[id].stock_qty}</td>
				</tr>
				{sectionelse}
					<blink class="error">No Items Currently<blink>
				{/section}
				</table>
				<br/>
          			<h3>Ageing ( Days )</h3>
				<table>
				{section name=id loop=$ageing_array}
				<tr>
					<td style="width:200px;">{$ageing_array[id].product_desc}</td>
					<td style="width:50px;">{$ageing_array[id].age}</td>
				</tr>
				{sectionelse}
					<blink class="error">No Items Currently<blink>
				{/section}
				</table>
				{*
				<ul>
					<li><span style="padding-right:100px;">Abiderm BLACK WER</span>1000Kg</li>
					<li><span style="padding-right:100px;">Abiderm AS</span>1000Kg</li>
					<li>Abixol Pigment Magenta</li>
					<li>Abidye PINK DL</li>
				</ul>
				*}
		</div>
        </div>
	<div id="ie_clearing">&nbsp;</div>
        <!-- End: IE Column Clearing -->
</div>
<!-- end: #col1 -->

