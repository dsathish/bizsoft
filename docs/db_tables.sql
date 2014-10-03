BEGIN;

CREATE SCHEMA demo;
SET SEARCH_PATH TO demo;
SET default_with_oids = true;

CREATE TABLE currency (
	currency_id serial PRIMARY KEY,
	currency_code varchar UNIQUE,
	currency_name varchar NOT NULL UNIQUE,
	conversion_rate numeric DEFAULT 1 CONSTRAINT c_conversion_rate_chk CHECK (conversion_rate > 0)
);

CREATE TABLE country (
	country_id serial PRIMARY KEY,
	country_code varchar(10),
	country_name varchar NOT NULL UNIQUE,
	print_order integer
);

CREATE TABLE city (
	city_id serial PRIMARY KEY,
	city_code varchar(10),
	city_name varchar NOT NULL UNIQUE,
	country_id integer NOT NULL REFERENCES country,
	print_order integer
);
CREATE INDEX ci_country_id_idx ON city(country_id);

CREATE TYPE tactivity AS ENUM ('int', 'sel', 'buy','grp');
--its better to add one more type 'int' - with v can manage 'member company name' (done)
--also is it possible to alter in future / else v restrict to most case right now itself (v can change in future)

CREATE TABLE relationships (
	relationship_id serial PRIMARY KEY,
	relationship_code varchar(10),
	relationship_name varchar NOT NULL,
	activity tactivity NOT NULL DEFAULT 'int', --default v set it as 'int' (done)
	print_order integer
);

CREATE TABLE contacts (
	contact_id serial PRIMARY KEY,
	relationship_id integer NOT NULL REFERENCES relationships,
	tax_detail varchar,
	payment_term varchar,
	address1 varchar,
	address2 varchar,
	phone_no integer,
	email varchar,
	credit_days int
);
CREATE INDEX co_relationship_id_idx ON contacts(relationship_id);
-- CREATE INDEX co_city_id_idx ON contacts(city_id);

--table for product category
CREATE TABLE product_category (
	product_category_id serial PRIMARY KEY,
	product_category_code varchar(10),
	product_category_desc varchar NOT NULL UNIQUE
);

--table for product 
CREATE TABLE products (
	product_id serial PRIMARY KEY,
	product_code varchar(10),
	product_desc varchar NOT NULL UNIQUE,
	product_category_id int REFERENCES product_category,
	product_liter text,
	cost_price numeric,
	cp_currency_id int REFERENCES currency,
	selling_price numeric,
	sp_currency_id int REFERENCES currency,
	reorder numeric	 
);

CREATE TABLE tax_type (
	tax_type_id serial PRIMARY KEY,
	tax_type_desc varchar NOT NULL UNIQUE
);

CREATE TABLE tax (
	tax_id serial PRIMARY KEY,
	tax_desc varchar NOT NULL UNIQUE,
	tax_type_id integer NOT NULL REFERENCES tax_type,
	is_billed smallint NOT NULL DEFAULT 1,
	print_order integer
);

--type creation for record status
CREATE TYPE tstatus AS ENUM ('act','can','del','com');

--As i said over phone , can v remove below receipt related table and directly have relation with transaction table.Even in future , v can seperately it out easily (done)

CREATE TABLE uom (
	uom_id serial PRIMARY KEY,
	uom_code varchar(10) UNIQUE,
	uom_desc varchar NOT NULL UNIQUE
);

CREATE TABLE orders (
	order_id serial PRIMARY KEY,
	order_ref varchar,
	relationship_id integer NOT NULL REFERENCES relationships,
	order_date date NOT NULL DEFAULT now(),
	order_type char(2) DEFAULT 'io' CONSTRAINT order_type_chk CHECK (order_type = 'io' OR order_type= 'po' OR order_type = 'so'),
	order_status tstatus DEFAULT 'act'
);

CREATE TABLE order_items (
	item_id serial PRIMARY KEY,
	order_id integer NOT NULL REFERENCES orders ON DELETE CASCADE,
	product_id integer NOT NULL REFERENCES products,
	order_quantity numeric NOT NULL CONSTRAINT ri_quantity_chk CHECK (order_quantity > 0),
	trans_quantity numeric DEFAULT 0,
	uom_id integer NOT NULL DEFAULT 1 REFERENCES uom,
	price numeric CONSTRAINT ri_price_chk CHECK (price > 0),
	currency_id integer DEFAULT 1 REFERENCES currency,
	conversion_rate numeric DEFAULT 1,
	remarks varchar
);

CREATE TABLE receipt (
	receipt_id serial PRIMARY KEY,
	receipt_ref varchar,
	supplier_id integer NOT NULL REFERENCES relationships,
	receipt_date date NOT NULL DEFAULT NOW(),
	receipt_value	numeric CONSTRAINT receipt_value_chk CHECK (receipt_value > 0.0),
	taxed_value	numeric, 
	payment_value 	numeric DEFAULT 0 CONSTRAINT receipt_payment_value_chk CHECK (payment_value <= (receipt_value+taxed_value))
);
CREATE INDEX r_supplier_id_idx ON receipt(supplier_id);

CREATE TABLE receipt_items (
	item_id serial PRIMARY KEY,
	receipt_id integer NOT NULL REFERENCES receipt ON DELETE CASCADE,
	ref_id int REFERENCES order_items(item_id),
	product_id integer NOT NULL REFERENCES products,
	quantity numeric NOT NULL CONSTRAINT ri_quantity_chk CHECK (quantity > 0),
	uom_id integer NOT NULL DEFAULT 1 REFERENCES uom,
	price numeric CONSTRAINT ri_price_chk CHECK (price > 0),
	currency_id integer DEFAULT 1 REFERENCES currency,
	conversion_rate numeric DEFAULT 1,
	remarks varchar
);
CREATE INDEX ri_receipt_id_idx ON receipt_items(receipt_id);
CREATE INDEX ri_product_id_idx ON receipt_items(product_id);
CREATE INDEX ri_currency_id_idx ON receipt_items(currency_id);
CREATE INDEX ri_uom_id_idx ON receipt_items(uom_id);

CREATE TABLE receipt_tax (
	receipt_id integer NOT NULL REFERENCES receipt ON DELETE CASCADE,
	tax_id integer NOT NULL REFERENCES tax,
	tax_rate numeric,
	tax_value numeric,
	tax_sequence integer
);
CREATE INDEX rt_receipt_id_idx ON receipt_tax(receipt_id);
CREATE INDEX rt_tax_id_idx ON receipt_tax(tax_id);

CREATE TABLE sales (
	sales_id serial PRIMARY KEY,
	sales_ref varchar,
	buyer_id integer NOT NULL REFERENCES relationships,
	sales_date date NOT NULL DEFAULT NOW(),
	sales_value	numeric CONSTRAINT sales_value_chk CHECK (sales_value > 0.0),
	taxed_value	numeric,
	payment_value 	numeric DEFAULT 0 CONSTRAINT sales_payment_value_chk CHECK (payment_value <= (sales_value+taxed_value))
);
CREATE INDEX r_buyer_id_idx ON sales(buyer_id);

CREATE TABLE sales_items (
	item_id serial PRIMARY KEY,
	sales_id integer NOT NULL REFERENCES sales ON DELETE CASCADE,
	ref_id integer REFERENCES order_items(item_id),
	product_id integer NOT NULL REFERENCES products,
	quantity numeric NOT NULL CONSTRAINT si_quantity_chk CHECK (quantity > 0),
	uom_id integer NOT NULL DEFAULT 1 REFERENCES uom,
	price numeric CONSTRAINT si_price_chk CHECK (price > 0),
	currency_id integer DEFAULT 1 REFERENCES currency,
	conversion_rate numeric DEFAULT 1,
	remarks varchar
);
CREATE INDEX si_sales_id_idx ON sales_items(sales_id);
CREATE INDEX si_product_id_idx ON sales_items(product_id);
CREATE INDEX si_currency_id_idx ON sales_items(currency_id);
CREATE INDEX si_uom_id_idx ON sales_items(uom_id);

CREATE TABLE sales_tax (
	sales_id integer NOT NULL REFERENCES sales ON DELETE CASCADE,
	tax_id integer NOT NULL REFERENCES tax,
	tax_rate numeric,
	tax_value numeric,
	tax_sequence integer
);
CREATE INDEX st_sales_id_idx ON sales_tax(sales_id);
CREATE INDEX st_tax_id_idx ON sales_tax(tax_id);


--its better to have transaction reference in name of account head , and v can refer here ( whether purchase / sales etc..) (done)

CREATE TYPE ttype AS ENUM ('i', 'o');
CREATE TYPE thead AS ENUM ('pur','sal','adj','hdl','trn');


--can v rename to transaction_details as table name (done)
CREATE TABLE transaction_details (
	trans_id serial PRIMARY KEY,
	trans_date date NOT NULL,
	trans_type ttype NOT NULL DEFAULT 'i',
	trans_head thead NOT NULL DEFAULT 'pur',
	quantity numeric NOT NULL CONSTRAINT t_quantity_chk CHECK (quantity > 0),
	uom_id integer NOT NULL REFERENCES uom DEFAULT 1,
	value numeric CONSTRAINT t_value_chk CHECK (value > 0),
	ref_id integer NOT NULL, -- it should be receipt_id or sales_id
	parent_id integer REFERENCES transaction_details
);
CREATE INDEX t_trans_type_idx ON transaction_details (trans_type);
CREATE INDEX t_trans_date_idx ON transaction_details (trans_date);
CREATE INDEX t_trans_head_idx ON transaction_details (trans_head);
CREATE INDEX t_uom_idx ON transaction_details (uom_id);
CREATE INDEX t_parent_idx ON transaction_details(parent_id);

CREATE TABLE transaction_balance (
	trans_id integer NOT NULL REFERENCES transaction_details,
	in_qty numeric CONSTRAINT tb_inqty CHECK (in_qty > 0),
	out_qty numeric DEFAULT 0,
	bal_qty numeric DEFAULT 0,
	in_value numeric DEFAULT 0,
	out_value numeric DEFAULT 0,
	bal_value numeric DEFAULT 0
);
CREATE INDEX tb_trans_id_idx ON transaction_balance (trans_id);


CREATE TABLE handloan (
	handln_id serial PRIMARY KEY,
	handln_ref varchar,
	relationship_id int NOT NULL REFERENCES relationships(relationship_id),
	handln_date date,
	currency_id int REFERENCES currency(currency_id)
	);

CREATE TABLE handloan_items (
	item_id serial PRIMARY KEY,
	handln_id int NOT NULL REFERENCES handloan(handln_id),
	ref_id integer REFERENCES handloan_items(item_id),
	product_id integer REFERENCES products(product_id),
	uom_id integer REFERENCES uom(uom_id),
	issued_quantity numeric(10,4) default 0,
	received_quantity numeric(10,4) default 0,
	price numeric(10,4),
	conversion_rate numeric default 1,
	remarks varchar
	);
	
	
	 
/*
-- and for tax details for either purchase or sales , i propose a new table trasaction_overheads/transaction_tax_details (Ok, good idea. V use this table to store overheads) 
CREATE TABLE trasaction_overheads(
	trans_id integer NOT NULL REFERENCES transaction_details,
	tax_id integer NOT NULL REFERENCES tax,
	tax_sequence integer ,
	formula varchar, -- (Need to clarify this....) -- sorry, i m not clear with this now... v will discuss...
	value numeric CONSTRAINT rt_value_chk CHECK (value > 0)
);
--end of new table
CREATE INDEX to_trans_id_idx ON transaction_overheads (trans_id);
CREATE INDEX to_tax_id_idx ON transaction_overheads (tax_id);
*/

-- [Need to implement permission level]
CREATE TABLE reports (
	report_id serial PRIMARY KEY,
	report_code varchar(10),
	report_name varchar NOT NULL UNIQUE,
	view_name varchar NOT NULL
);

--Same , as v discussed over phone , i could recommend the below changes

CREATE TABLE columns (
	column_id serial PRIMARY KEY,
	column_name varchar NOT NULL UNIQUE,
	is_filter smallint,
	filter_query varchar,
	data_type varchar NOT NULL DEFAULT 'int'
--data_type varchar ( value may be date , varchar , int to perform sort operations) (done)
);

CREATE TABLE customizations (
	customization_id serial PRIMARY KEY,
	customization_name varchar NOT NULL,
	is_active smallint DEFAULT 1,
	is_default smallint DEFAULT 0,
	report_id integer NOT NULL REFERENCES reports,
	sub_total smallint,
	grand_total smallint,
	date_column varchar,
	from_date date,
	to_date date,
	--customization_view varchar,
	CONSTRAINT report_cust_id_unq UNIQUE (report_id, customization_id)
);
CREATE INDEX c_reports_idx ON customizations(report_id);

CREATE TYPE tsort AS ENUM ('ASC', 'DESC');
CREATE TABLE customization_columns (
	customization_id integer NOT NULL REFERENCES customizations,
	column_id integer NOT NULL REFERENCES columns,
	display_name varchar NOT NULL,
	display_order numeric,
	--is_sort can be removed , by default it should be sorted (We can use for non display columns)
	sort_order tsort,
	is_group smallint,
	is_filter smallint,--this also not needed , since it is referred in columns tables (v will discuss...)
	default_value varchar, -- this can be renamed to formula (either column value/any formula) (am not clear...)
	--date_condtion not required here as v mention in customizations tables
	date_condition varchar[], -- arg[1]=date_type/column_id, [2]= from_date, [3]=to_date (Need to clarify this...) (v will discuss)
	decimal_places int,
	date_format varchar,
	display_total smallint,
	style varchar,
	--formula varchar, -- sum, avg, count etc (not clear...)
	CONSTRAINT cust_column_id_unq UNIQUE (customization_id, column_id)
);
CREATE INDEX cc_customization_id_idx ON customization_columns(customization_id);
CREATE INDEX cc_column_id_idx ON customization_columns(column_id);


-- default values
INSERT INTO currency (currency_code, currency_name) VALUES ('INR','INR');
INSERT INTO country (country_code, country_name) VALUES ('IN','INDIA');
INSERT INTO city (city_name, country_id) VALUES ('CHENNAI', 1);
INSERT INTO relationships (relationship_name, activity) VALUES ('DEMO','int');
INSERT INTO uom (uom_code, uom_desc) VALUES ('numb','Number') , ('kigm','Kilograms') , ('ltrs','Litres');
INSERT INTO tax_type (tax_type_desc) VALUES ('Add');
INSERT INTO tax_type (tax_type_desc) VALUES ('Add %');
INSERT INTO tax_type (tax_type_desc) VALUES ('Deduce');
INSERT INTO tax_type (tax_type_desc) VALUES ('Deduce %');
INSERT INTO tax_type (tax_type_desc) VALUES ('Surcharge');
INSERT INTO tax (tax_desc, tax_type_id) VALUES ('VAT %',2);
INSERT INTO tax (tax_desc, tax_type_id) VALUES ('Sales Tax',1);
-- receipt report

CREATE OR REPLACE FUNCTION format_number(numeric) RETURNS numeric
AS $$
DECLARE 
	num numeric;
BEGIN
	SELECT substring($1::text,'-{0,1}[0-9]*[.][0-9]*')::numeric INTO num;
        IF num IS NOT NULL THEN
                SELECT substring($1::text,'-{0,1}[0-9]*.{1}[0-9]*[^0]')::numeric INTO num;
        END IF;
        RETURN coalesce(num,$1);
END;
$$
LANGUAGE plpgsql;

CREATE OPERATOR # (
    PROCEDURE = format_number,
    RIGHTARG = numeric
);

CREATE OR REPLACE FUNCTION receipt_item_tax_value_func(int, int) RETURNS numeric
AS $$
DECLARE
	-- arg[1] = receipt_id, [2] = item_id
	tax_rec record;
	var_bill_value numeric;
	var_item_value numeric;
	var_tax_value numeric;
BEGIN
	SELECT sum(quantity * price * conversion_rate) INTO var_bill_value FROM receipt_items WHERE receipt_id = $1;
	SELECT quantity * price * conversion_rate INTO var_item_value FROM receipt_items WHERE item_id = $2;

	var_tax_value = 0;
	FOR tax_rec IN SELECT rt.tax_id, rt.tax_rate, rt.tax_value, t.tax_type_id FROM receipt_tax rt JOIN tax t USING (tax_id) WHERE receipt_id = $1 ORDER BY tax_sequence LOOP
		IF tax_rec.tax_type_id = 1 then
			var_tax_value = var_tax_value + (var_item_value * tax_rec.tax_value / var_bill_value);
		ELSIF tax_rec.tax_type_id = 2 then
			var_tax_value = var_tax_value + (var_item_value * tax_rec.tax_rate / 100);
		ELSIF tax_rec.tax_type_id = 3 THEN
			var_tax_value = var_tax_value - (var_item_value * tax_rec.tax_value / var_bill_value);
		ELSIF tax_rec.tax_type_id = 4 THEN
			var_tax_value = var_tax_value - (var_item_value * tax_rec.tax_rate / 100);
		END IF;
	END LOOP;
RETURN round(var_tax_value,4);
END;
$$
LANGUAGE plpgsql;


--trigger function to update transaction balance details

CREATE OR REPLACE FUNCTION trans_balance_func() RETURNS trigger AS
$$
BEGIN
	IF (TG_OP = 'INSERT') THEN
		IF (NEW.trans_type = 'i' AND NEW.trans_head IN ('pur','adj','hdl')) THEN
			INSERT INTO transaction_balance(trans_id,in_qty,out_qty,bal_qty) VALUES (NEW.trans_id,NEW.quantity,0,NEW.quantity);
		ELSIF (NEW.trans_type = 'o' AND NEW.trans_head IN ('sal','adj','hdl')) THEN
			UPDATE transaction_balance SET out_qty = out_qty+NEW.quantity , bal_qty = bal_qty-NEW.quantity WHERE trans_id = NEW.parent_id;
		END IF;
	ELSIF (TG_OP = 'UPDATE') THEN
		IF (NEW.trans_type = 'i' AND NEW.trans_head IN ('pur','adj','hdl')) THEN
			UPDATE transaction_balance SET in_qty = in_qty+NEW.quantity-OLD.quantity , bal_qty = bal_qty-NEW.quantity+OLD.quantity WHERE trans_id = NEW.trans_id;
		ELSIF (NEW.trans_type = 'o' AND NEW.trans_head IN ('sal','adj','hdl')) THEN
			UPDATE transaction_balance SET out_qty = out_qty+NEW.quantity-OLD.quantity , bal_qty = bal_qty-NEW.quantity+OLD.quantity WHERE trans_id = NEW.parent_id;
		END IF; 
	ELSIF (TG_OP = 'DELETE') THEN
		IF (OLD.trans_type = 'i' AND OLD.trans_head IN ('pur','adj','hdl')) THEN
			DELETE FROM transaction_balance WHERE trans_id = OLD.trans_id;
		ELSIF (OLD.trans_type = 'o' AND OLD.trans_head IN ('sal','adj','hdl')) THEN
			UPDATE transaction_balance SET out_qty = out_qty-OLD.quantity , bal_qty = bal_qty+OLD.quantity WHERE trans_id = OLD.parent_id;			
		END IF;
	END IF;

RETURN NULL;
END;
$$
LANGUAGE 'plpgsql';

CREATE TRIGGER trans_balance_trig AFTER INSERT OR UPDATE OR DELETE ON transaction_details FOR EACH ROW EXECUTE PROCEDURE trans_balance_func();

CREATE OR REPLACE FUNCTION receipt_tax_value_bupdate_func() RETURNS trigger
AS $$
DECLARE
	var_tax_type int;
	var_bill_value numeric;
BEGIN
	SELECT tax_type_id INTO var_tax_type FROM tax WHERE tax_id = NEW.tax_id;
	SELECT sum(quantity * price * conversion_rate) INTO var_bill_value FROM receipt_items WHERE receipt_id = NEW.receipt_id;

	IF var_tax_type = 1 then
                NEW.tax_value = NEW.tax_rate;
        ELSIF var_tax_type = 2 then
                NEW.tax_value = var_bill_value * (NEW.tax_rate/100);
        ELSIF var_tax_type = 3 THEN
                NEW.tax_value = - (NEW.tax_rate);
        ELSIF var_tax_type = 4 THEN
                NEW.tax_value = - var_bill_value * (NEW.tax_rate/100);
	END IF;
RETURN NEW;
END;
$$
LANGUAGE plpgsql;

CREATE TRIGGER receipt_tax_value_bupdate_trig BEFORE INSERT OR UPDATE ON receipt_tax FOR EACH ROW EXECUTE PROCEDURE receipt_tax_value_bupdate_func();

CREATE OR REPLACE FUNCTION sales_tax_value_bupdate_func() RETURNS trigger
AS $$
DECLARE
	var_tax_type int;
	var_bill_value numeric;
BEGIN
	SELECT tax_type_id INTO var_tax_type FROM tax WHERE tax_id = NEW.tax_id;
	SELECT sum(quantity * price * conversion_rate) INTO var_bill_value FROM sales_items WHERE sales_id = NEW.sales_id;

	IF var_tax_type = 1 then
                NEW.tax_value = NEW.tax_rate;
        ELSIF var_tax_type = 2 then
                NEW.tax_value = var_bill_value * (NEW.tax_rate/100);
        ELSIF var_tax_type = 3 THEN
                NEW.tax_value = - (NEW.tax_rate);
        ELSIF var_tax_type = 4 THEN
                NEW.tax_value = - var_bill_value * (NEW.tax_rate/100);
	END IF;
RETURN NEW;
END;
$$
LANGUAGE plpgsql;

CREATE TRIGGER sales_tax_value_bupdate_trig BEFORE INSERT OR UPDATE ON sales_tax FOR EACH ROW EXECUTE PROCEDURE sales_tax_value_bupdate_func();

CREATE OR REPLACE FUNCTION sales_item_tax_value_func(int, int) RETURNS numeric
AS $$
DECLARE
	-- arg[1] = sales_id, [2] = item_id
	tax_rec record;
	var_bill_value numeric;
	var_item_value numeric;
	var_tax_value numeric;
BEGIN
	SELECT sum(quantity * price * conversion_rate) INTO var_bill_value FROM sales_items WHERE sales_id = $1;
	SELECT quantity * price * conversion_rate INTO var_item_value FROM sales_items WHERE item_id = $2;

	var_tax_value = 0;
	FOR tax_rec IN SELECT st.tax_id, st.tax_rate, st.tax_value, t.tax_type_id FROM sales_tax st JOIN tax t USING (tax_id) WHERE sales_id = $1 ORDER BY tax_sequence LOOP
		IF tax_rec.tax_type_id = 1 then
			var_tax_value = var_tax_value + (var_item_value * tax_rec.tax_value / var_bill_value);
		ELSIF tax_rec.tax_type_id = 2 then
			var_tax_value = var_tax_value + (var_item_value * tax_rec.tax_rate / 100);
		ELSIF tax_rec.tax_type_id = 3 THEN
			var_tax_value = var_tax_value - (var_item_value * tax_rec.tax_value / var_bill_value);
		ELSIF tax_rec.tax_type_id = 4 THEN
			var_tax_value = var_tax_value - (var_item_value * tax_rec.tax_rate / 100);
		END IF;
	END LOOP;
RETURN round(var_tax_value,4);
END;
$$
LANGUAGE plpgsql;



--master payment mode table for say cash/cheque/demanddraft
CREATE TABLE payment_mode (
	payment_mode_id serial PRIMARY KEY,
	payment_mode_code varchar(10),
	payment_mode_name varchar NOT NULL UNIQUE,
	print_order integer
);

--insert query payment mode for detault values
INSERT INTO payment_mode(payment_mode_name) VALUES ('CASH'),('CHEQUE'),('DEMAND DRAFT');

--table for payment data for every receipt and sales transaction
CREATE TABLE payments (
	payment_id SERIAL PRIMARY KEY,
	relationship_id integer NOT NULL REFERENCES relationships,
	payment_ref varchar,
	payment_date date,
	payment_mode_id int NOT NULL REFERENCES payment_mode,
	status tstatus NOT NULL default 'act' 
);

--table relating for payment and receipt
CREATE TABLE receipt_payment_relation (
	payment_id int NOT NULL REFERENCES payments,
	receipt_id int NOT NULL REFERENCES receipt,
	amount numeric CHECK (amount > 0.0),
	deductions numeric(10,4)
);	

--table relating for payment and sales
CREATE TABLE sales_payment_relation (
	payment_id int NOT NULL REFERENCES payments,
	sales_id int NOT NULL REFERENCES sales,
	amount numeric CHECK (amount > 0.0),
	deductions numeric(10,4)
);


-- receipt report view
CREATE OR REPLACE VIEW receipt_report_view AS
SELECT rc.receipt_id, rc.receipt_ref, rc.receipt_date, re.relationship_name AS supplier, uo.uom_code, ri.quantity, pr.product_desc, ri.price, ri.conversion_rate, cu.currency_code, ri.remarks, # receipt_item_tax_value_func(ri.receipt_id, ri.item_id) AS item_tax_value
FROM receipt rc
JOIN receipt_items ri USING (receipt_id)
JOIN transaction_details td ON td.ref_id = ri.item_id AND td.trans_type = 'i' AND trans_head in ('pur','adj')
JOIN products pr ON pr.product_id = ri.product_id
JOIN relationships re ON re.relationship_id = rc.supplier_id
JOIN uom uo ON uo.uom_id = ri.uom_id
JOIN currency cu ON cu.currency_id = ri.currency_id;

CREATE OR REPLACE FUNCTION receipt_report_func() RETURNS VOID
AS $$
DECLARE
	var_report_id int;
	var_cust_id int;
	var_column_id int;
BEGIN
	INSERT INTO reports (report_name, view_name) VALUES ('Receipt Report','receipt_report_view') RETURNING report_id INTO var_report_id;

	INSERT INTO customizations (customization_name, report_id, is_default,date_column) VALUES ('Def', var_report_id, 2 ,'receipt_date') RETURNING customization_id INTO var_cust_id;

	INSERT INTO columns (column_name, data_type) VALUES ('receipt_id','int') RETURNING column_id INTO var_column_id;
	INSERT INTO customization_columns (customization_id, column_id, display_name) VALUES (var_cust_id, var_column_id, 'Receipt');

	INSERT INTO columns (column_name, data_type) VALUES ('''<a href="details.php?module=receipt&receipt_id=''||receipt_id||''">''||receipt_ref||''</a>''','varchar') RETURNING column_id INTO var_column_id;
	INSERT INTO customization_columns (customization_id, column_id, display_name, display_order) VALUES (var_cust_id, var_column_id, 'Receipt Ref',2);

	INSERT INTO columns (column_name, data_type) VALUES ('receipt_date','date') RETURNING column_id INTO var_column_id;
	INSERT INTO customization_columns (customization_id, column_id, display_name,display_order, date_format,is_group,sort_order) VALUES (var_cust_id, var_column_id, 'Receipt Date',3, 'dd/mm/yyyy',1,'ASC');

	INSERT INTO columns (column_name, data_type) VALUES ('supplier','varchar') RETURNING column_id INTO var_column_id;
	INSERT INTO customization_columns (customization_id, column_id, display_name, display_order, is_group, sort_order) VALUES (var_cust_id, var_column_id, 'Supplier', 1, 1, 'ASC');

	INSERT INTO columns (column_name, data_type) VALUES ('product_desc','varchar') RETURNING column_id INTO var_column_id;
	INSERT INTO customization_columns (customization_id, column_id, display_name, display_order, is_group, sort_order) VALUES (var_cust_id, var_column_id, 'Product', 4, 1 ,'ASC');

	INSERT INTO columns (column_name, data_type) VALUES ('quantity','numeric') RETURNING column_id INTO var_column_id;
	INSERT INTO customization_columns (customization_id, column_id, display_name, display_order, display_total, decimal_places, style) VALUES (var_cust_id, var_column_id, 'Quantity', 5, 1, 0, 'text-align:right;');

	INSERT INTO columns (column_name, data_type) VALUES ('item_tax_value','numeric') RETURNING column_id INTO var_column_id;
	INSERT INTO customization_columns (customization_id, column_id, display_name, display_order, decimal_places, style,display_total) VALUES (var_cust_id, var_column_id, 'Item Tax', 8, 2, 'text-align:right;',1);

	INSERT INTO columns (column_name, data_type) VALUES ('price','numeric') RETURNING column_id INTO var_column_id;
	INSERT INTO customization_columns (customization_id, column_id, display_name, display_order, decimal_places, style) VALUES (var_cust_id, var_column_id, 'Price', 6, 2, 'text-align:right;');

	INSERT INTO columns (column_name, data_type) VALUES ('quantity * price * conversion_rate','numeric') RETURNING column_id INTO var_column_id;
	INSERT INTO customization_columns (customization_id, column_id, display_name, display_order, decimal_places, style, display_total) VALUES (var_cust_id, var_column_id, 'Value', 7, 2, 'text-align:right;', 1);

	INSERT INTO columns (column_name, data_type) VALUES ('quantity * price * conversion_rate + item_tax_value','numeric') RETURNING column_id INTO var_column_id;
	INSERT INTO customization_columns (customization_id, column_id, display_name, display_order, decimal_places, style,display_total) VALUES (var_cust_id, var_column_id, 'Taxed Value', 9, 2, 'text-align:right;',1);
END;
$$
LANGUAGE plpgsql;

SELECT receipt_report_func();
DROP FUNCTION receipt_report_func();

-- sales report view
CREATE OR REPLACE VIEW sales_report_view AS
SELECT sa.sales_id, sa.sales_ref, sa.sales_date, re.relationship_name AS buyer, uo.uom_code, si.quantity, pr.product_desc, si.price, si.conversion_rate, cu.currency_code, si.remarks, # sales_item_tax_value_func(si.sales_id, si.item_id) AS item_tax_value
FROM sales sa
JOIN sales_items si USING (sales_id)
JOIN products pr ON pr.product_id = si.product_id
JOIN relationships re ON re.relationship_id = sa.buyer_id
JOIN uom uo ON uo.uom_id = si.uom_id
JOIN currency cu ON cu.currency_id = si.currency_id;

-- sales report details
CREATE OR REPLACE FUNCTION sales_report_func() RETURNS VOID
AS $$
DECLARE
	var_report_id int;
	var_cust_id int;
	var_column_id int;
BEGIN
	INSERT INTO reports (report_name, view_name) VALUES ('Sales Report','sales_report_view') RETURNING report_id INTO var_report_id;

	INSERT INTO customizations (customization_name, report_id, is_default,date_column) VALUES ('Def', var_report_id, 2 , 'sales_date') RETURNING customization_id INTO var_cust_id;

	INSERT INTO columns (column_name, data_type) VALUES ('sales_id','int') RETURNING column_id INTO var_column_id;
	INSERT INTO customization_columns (customization_id, column_id, display_name) VALUES (var_cust_id, var_column_id, 'Sales');

	INSERT INTO columns (column_name, data_type) VALUES ('''<a href="details.php?module=sales&sales_id=''||sales_id||''">''||sales_ref||''</a>''','varchar') RETURNING column_id INTO var_column_id;
	INSERT INTO customization_columns (customization_id, column_id, display_name,display_order) VALUES (var_cust_id, var_column_id, 'Sales Ref',2);

	INSERT INTO columns (column_name, data_type) VALUES ('sales_date','date') RETURNING column_id INTO var_column_id;
	INSERT INTO customization_columns (customization_id, column_id, display_name, display_order, date_format,is_group,sort_order) VALUES (var_cust_id, var_column_id, 'Sales Date', 3, 'dd/mm/yyyy',1,'ASC');

	INSERT INTO columns (column_name, data_type) VALUES ('buyer','varchar') RETURNING column_id INTO var_column_id;
	INSERT INTO customization_columns (customization_id, column_id, display_name, display_order, is_group, sort_order) VALUES (var_cust_id, var_column_id, 'Buyer', 1, 1, 'ASC');

	SELECT column_id INTO var_column_id FROM columns WHERE column_name = 'product_desc';
	INSERT INTO customization_columns (customization_id, column_id, display_name, display_order,is_group,sort_order) VALUES (var_cust_id, var_column_id, 'Product', 4,1,'ASC');

	SELECT column_id INTO var_column_id FROM columns WHERE column_name = 'quantity';
	INSERT INTO customization_columns (customization_id, column_id, display_name, display_order, display_total, decimal_places, style) VALUES (var_cust_id, var_column_id, 'Quantity', 5, 1, 0, 'text-align:right;');

	SELECT column_id INTO var_column_id FROM columns WHERE column_name = 'price';
	INSERT INTO customization_columns (customization_id, column_id, display_name, display_order,  decimal_places, style) VALUES (var_cust_id, var_column_id, 'Price', 6, 2, 'text-align:right;');

	SELECT column_id INTO var_column_id FROM columns WHERE column_name = 'quantity * price * conversion_rate';
	INSERT INTO customization_columns (customization_id, column_id, display_name, display_order, display_total, decimal_places, style) VALUES (var_cust_id, var_column_id, 'Value', 7, 1, 2, 'text-align:right;');

	SELECT column_id INTO var_column_id FROM columns WHERE column_name = 'item_tax_value';
	INSERT INTO customization_columns (customization_id, column_id, display_name, display_order, decimal_places, style,display_total) VALUES (var_cust_id, var_column_id, 'Item Tax', 8, 2, 'text-align:right;',1);

	SELECT column_id INTO var_column_id FROM columns WHERE column_name = 'quantity * price * conversion_rate + item_tax_value';
	INSERT INTO customization_columns (customization_id, column_id, display_name, display_order, decimal_places, style,display_total) VALUES (var_cust_id, var_column_id, 'Taxed Value', 9, 2, 'text-align:right;',1);
END;
$$
LANGUAGE plpgsql;

SELECT sales_report_func();
DROP FUNCTION sales_report_func();

-- stock report view
CREATE OR REPLACE VIEW stock_report_view AS
SELECT td.trans_id, td.trans_date, pr.product_code, pr.product_desc, u.uom_code, uom_desc, tb.in_qty, tb.out_qty, tb.bal_qty, rc.receipt_ref, re.relationship_name AS supplier
FROM transaction_details td
JOIN transaction_balance tb USING (trans_id)
JOIN receipt_items ri ON ri.item_id = td.ref_id
JOIN receipt rc ON rc.receipt_id = ri.receipt_id
JOIN relationships re ON re.relationship_id = rc.supplier_id
JOIN products pr ON pr.product_id = ri.product_id
JOIN uom u ON u.uom_id = td.uom_id
WHERE tb.bal_qty > 0 AND td.trans_head in ('pur','adj');

CREATE OR REPLACE FUNCTION stock_report_func() RETURNS VOID
AS $$
DECLARE
	var_report_id int;
	var_cust_id int;
	var_column_id int;
BEGIN
	INSERT INTO reports (report_name, view_name) VALUES ('Stock Report','stock_report_view') RETURNING report_id INTO var_report_id;

	INSERT INTO customizations (customization_name, report_id, is_default ) VALUES ('Def', var_report_id, 2) RETURNING customization_id INTO var_cust_id;

	INSERT INTO columns (column_name, data_type) VALUES ('''<a href="details.php?module=stock&trans_id=''||trans_id||''">''||trans_id||''</a>''','int') RETURNING column_id INTO var_column_id;
	INSERT INTO customization_columns (customization_id, column_id, display_name, display_order) VALUES (var_cust_id, var_column_id, 'Stock', 1);

	INSERT INTO columns (column_name, data_type) VALUES ('trans_date','date') RETURNING column_id INTO var_column_id;
	INSERT INTO customization_columns (customization_id, column_id, display_name,is_group,sort_order) VALUES (var_cust_id, var_column_id, 'Stock Date',2,'ASC');

	SELECT column_id INTO var_column_id FROM columns WHERE column_name = 'product_desc';
	INSERT INTO customization_columns (customization_id, column_id, display_name, display_order, is_group, sort_order) VALUES (var_cust_id, var_column_id, 'Product', 4, 1, 'ASC');

	INSERT INTO columns (column_name, data_type) VALUES ('in_qty','numeric') RETURNING column_id INTO var_column_id;
	INSERT INTO customization_columns (customization_id, column_id, display_name, display_order, display_total, decimal_places, style) VALUES (var_cust_id, var_column_id, 'Receipt Qty', 5, 1, 0, 'text-align:right;');

	INSERT INTO columns (column_name, data_type) VALUES ('out_qty','numeric') RETURNING column_id INTO var_column_id;
	INSERT INTO customization_columns (customization_id, column_id, display_name, display_order, display_total, decimal_places, style) VALUES (var_cust_id, var_column_id, 'Sales Qty', 6, 1, 0, 'text-align:right;');

	INSERT INTO columns (column_name, data_type) VALUES ('bal_qty','numeric') RETURNING column_id INTO var_column_id;
	INSERT INTO customization_columns (customization_id, column_id, display_name, display_order, display_total, decimal_places, style) VALUES (var_cust_id, var_column_id, 'Bal Qty', 7, 1, 0, 'text-align:right;');

	INSERT INTO columns (column_name, data_type) VALUES ('uom_code','varchar') RETURNING column_id INTO var_column_id;
	INSERT INTO customization_columns (customization_id, column_id, display_name) VALUES (var_cust_id, var_column_id, 'UOM');

	INSERT INTO columns (column_name, data_type) VALUES ('uom_desc','varchar') RETURNING column_id INTO var_column_id;
	INSERT INTO customization_columns (customization_id, column_id, display_name) VALUES (var_cust_id, var_column_id, 'UOM');

	SELECT column_id INTO var_column_id FROM columns WHERE column_name = 'receipt_id';
	INSERT INTO customization_columns (customization_id, column_id, display_name) VALUES (var_cust_id, var_column_id, 'Receipt');

	SELECT column_id INTO var_column_id FROM columns WHERE column_name = '''<a href="details.php?module=receipt&receipt_id=''||receipt_id||''">''||receipt_ref||''</a>''';
	INSERT INTO customization_columns (customization_id, column_id, display_name) VALUES (var_cust_id, var_column_id, 'Receipt Ref');

	SELECT column_id INTO var_column_id FROM columns WHERE column_name = 'supplier';
	INSERT INTO customization_columns (customization_id, column_id, display_name, display_order, sort_order) VALUES (var_cust_id, var_column_id, 'Supplier', 10, 'ASC');
	
END;
$$
LANGUAGE plpgsql;
SELECT stock_report_func();
DROP FUNCTION stock_report_func();

-- payment report view
CREATE OR REPLACE VIEW payment_report_view AS
SELECT pa.payment_id, pa.payment_date, pa.payment_ref, pa.status, pm.payment_mode_name AS payment_mode, re.receipt_ref, 'NA'::varchar AS sales_ref, re.receipt_id, re.receipt_date AS trans_date, r.relationship_name, r.activity , rpr.amount AS paid_amount, rpr.deductions AS paid_deductions, (rpr.amount - rpr.deductions) AS paid_total, 0.00::numeric AS received_amount, 0.00::numeric AS received_deductions, 0.00::numeric AS received_total 
FROM payments pa
JOIN receipt_payment_relation rpr USING (payment_id)
JOIN receipt re USING(receipt_id)
JOIN relationships r USING(relationship_id)
JOIN payment_mode pm USING(payment_mode_id)
UNION
SELECT pa.payment_id, pa.payment_date, pa.payment_ref, pa.status, pm.payment_mode_name AS payment_mode, 'NA'::varchar AS receipt_ref, sa.sales_ref, sa.sales_id, sa.sales_date AS trans_date, r.relationship_name, r.activity , 0.00::numeric AS paid_amount, 0.00::numeric AS paid_deductions, 0.00::numeric AS paid_total ,spr.amount AS received_amount, spr.deductions AS received_deductions, (spr.amount - spr.deductions) AS received_total 
FROM payments pa
JOIN sales_payment_relation spr USING (payment_id)
JOIN sales sa USING(sales_id)
JOIN relationships r USING(relationship_id)
JOIN payment_mode pm USING(payment_mode_id);


CREATE OR REPLACE FUNCTION payment_report_func() RETURNS VOID
AS $$
DECLARE
	var_report_id int;
	var_cust_id int;
	var_column_id int;
BEGIN

	INSERT INTO reports (report_name, view_name) VALUES ('Payment Report','payment_report_view') RETURNING report_id INTO var_report_id;

	INSERT INTO customizations (customization_name, report_id, is_default ,date_column) VALUES ('Def', var_report_id, 2 , 'payment_date') RETURNING customization_id INTO var_cust_id;

	INSERT INTO columns (column_name, data_type) VALUES ('''<a href="details.php?module=payment&payment_id=''||payment_id||''">''||payment_ref||''</a>''','int') RETURNING column_id INTO var_column_id;
	INSERT INTO customization_columns (customization_id, column_id, display_name, display_order) VALUES (var_cust_id, var_column_id, 'Payment', 1);

	INSERT INTO columns (column_name, data_type) VALUES ('payment_date','date') RETURNING column_id INTO var_column_id;
	INSERT INTO customization_columns (customization_id, column_id, display_name , display_order , is_group , sort_order ) VALUES (var_cust_id, var_column_id, 'Payment Date' , 2 , 1 ,'ASC');

	INSERT INTO columns (column_name, data_type) VALUES ('payment_mode','varchar') RETURNING column_id INTO var_column_id;
	INSERT INTO customization_columns (customization_id, column_id, display_name) VALUES (var_cust_id, var_column_id, 'Payment Mode');

	INSERT INTO columns (column_name, data_type) VALUES ('status','varchar') RETURNING column_id INTO var_column_id;
	INSERT INTO customization_columns (customization_id, column_id, display_name) VALUES (var_cust_id, var_column_id, 'Status');

	SELECT column_id INTO var_column_id FROM columns WHERE column_name = 'receipt_id';
	INSERT INTO customization_columns (customization_id, column_id, display_name) VALUES (var_cust_id, var_column_id, 'Receipt');

	SELECT column_id INTO var_column_id FROM columns WHERE column_name = '''<a href="details.php?module=receipt&receipt_id=''||receipt_id||''">''||receipt_ref||''</a>''';
	INSERT INTO customization_columns (customization_id, column_id, display_name) VALUES (var_cust_id, var_column_id, 'Receipt Ref');

	SELECT column_id INTO var_column_id FROM columns WHERE column_name = 'sales_id';
	INSERT INTO customization_columns (customization_id, column_id, display_name) VALUES (var_cust_id, var_column_id, 'Sales');

	SELECT column_id INTO var_column_id FROM columns WHERE column_name = '''<a href="details.php?module=sales&sales_id=''||sales_id||''">''||sales_ref||''</a>''';
	INSERT INTO customization_columns (customization_id, column_id, display_name) VALUES (var_cust_id, var_column_id, 'Sales Ref');

	INSERT INTO columns (column_name, data_type) VALUES ('relationship_name','varchar') RETURNING column_id INTO var_column_id;
	INSERT INTO customization_columns (customization_id, column_id, display_name , is_group , sort_order) VALUES (var_cust_id, var_column_id, 'Payee Name',1 ,'ASC');

	INSERT INTO columns (column_name, data_type) VALUES ('paid_amount','numeric') RETURNING column_id INTO var_column_id;
	INSERT INTO customization_columns (customization_id, column_id, display_name, display_order, display_total, decimal_places, style) VALUES (var_cust_id, var_column_id, 'Paid Amount', 2, 1, 0, 'text-align:right;');

	INSERT INTO columns (column_name, data_type) VALUES ('paid_deductions','numeric') RETURNING column_id INTO var_column_id;
	INSERT INTO customization_columns (customization_id, column_id, display_name, display_order, display_total, decimal_places, style) VALUES (var_cust_id, var_column_id, 'Paid Deductions', 3, 1, 0, 'text-align:right;');

	INSERT INTO columns (column_name, data_type) VALUES ('paid_total','numeric') RETURNING column_id INTO var_column_id;
	INSERT INTO customization_columns (customization_id, column_id, display_name, display_order, display_total, decimal_places, style) VALUES (var_cust_id, var_column_id, 'Paid Total', 4, 1, 0, 'text-align:right;');

	INSERT INTO columns (column_name, data_type) VALUES ('received_amount','numeric') RETURNING column_id INTO var_column_id;
	INSERT INTO customization_columns (customization_id, column_id, display_name, display_order, display_total, decimal_places, style) VALUES (var_cust_id, var_column_id, 'Received Amount', 2, 1, 0, 'text-align:right;');

	INSERT INTO columns (column_name, data_type) VALUES ('received_deductions','numeric') RETURNING column_id INTO var_column_id;
	INSERT INTO customization_columns (customization_id, column_id, display_name, display_order, display_total, decimal_places, style) VALUES (var_cust_id, var_column_id, 'Received Deductions', 3, 1, 0, 'text-align:right;');

	INSERT INTO columns (column_name, data_type) VALUES ('received_total','numeric') RETURNING column_id INTO var_column_id;
	INSERT INTO customization_columns (customization_id, column_id, display_name, display_order, display_total, decimal_places, style) VALUES (var_cust_id, var_column_id, 'Received Total', 4, 1, 0, 'text-align:right;');
END;
$$
LANGUAGE plpgsql;
SELECT payment_report_func();
DROP FUNCTION payment_report_func();

-- order report view_name
CREATE OR REPLACE VIEW order_report_view AS SELECT od.order_id, oi.item_id, od.order_ref, od.order_date, od.order_type, od.order_status AS status, re.relationship_name, uo.uom_code, uo.uom_desc, oi.order_quantity, oi.trans_quantity, pr.product_desc, oi.price, oi.conversion_rate, oi.order_quantity*oi.price*oi.conversion_rate AS order_value, cu.currency_code, oi.remarks
FROM orders od
JOIN order_items oi USING (order_id)
JOIN products pr USING (product_id)
JOIN relationships re USING (relationship_id)
JOIN uom uo ON uo.uom_id = oi.uom_id
JOIN currency cu ON cu.currency_id = oi.currency_id;

CREATE OR REPLACE FUNCTION order_report_func() RETURNS VOID
AS $$
DECLARE
	var_report_id int;
	var_cust_id int;
	var_column_id int;
BEGIN

	INSERT INTO reports (report_name, view_name) VALUES ('Order Report','order_report_view') RETURNING report_id INTO var_report_id;

	INSERT INTO customizations (customization_name, report_id, is_default,date_column) VALUES ('Def', var_report_id, 2 , 'order_date') RETURNING customization_id INTO var_cust_id;

	INSERT INTO columns (column_name, data_type) VALUES ('CASE order_type WHEN ''po'' THEN ''<a href="details.php?module=indent&order_id=''||order_id||''">''||order_ref||''</a>'' WHEN ''so'' THEN ''<a href="details.php?module=order&order_id=''||order_id||''">''||order_ref||''</a>'' END','int') RETURNING column_id INTO var_column_id;
	INSERT INTO customization_columns (customization_id, column_id, display_name, display_order) VALUES (var_cust_id, var_column_id, 'Order Reference', 1.5);

	SELECT column_id INTO var_column_id FROM columns WHERE column_name = 'relationship_name';
	INSERT INTO customization_columns (customization_id, column_id, display_name, display_order, is_group, sort_order) VALUES (var_cust_id, var_column_id, 'Relationship',2, 1 ,'ASC');

	INSERT INTO columns (column_name, data_type) VALUES ('order_date','date') RETURNING column_id INTO var_column_id;
	INSERT INTO customization_columns (customization_id, column_id, display_name, display_order,is_group,sort_order) VALUES (var_cust_id, var_column_id, 'Order Date',3 , 1 , 'ASC');

	INSERT INTO columns (column_name, data_type) VALUES ('order_type','varchar') RETURNING column_id INTO var_column_id;
	INSERT INTO customization_columns (customization_id, column_id, display_name, display_order) VALUES (var_cust_id, var_column_id, 'Order Type',4);

	SELECT column_id INTO var_column_id FROM columns WHERE column_name = 'status';
	INSERT INTO customization_columns (customization_id, column_id, display_name, display_order) VALUES (var_cust_id, var_column_id, 'Order Status',5);

	SELECT column_id INTO var_column_id FROM columns WHERE column_name = 'product_desc';
	INSERT INTO customization_columns (customization_id, column_id, display_name, display_order,is_group,sort_order) VALUES (var_cust_id, var_column_id, 'Product', 6, 1 ,'ASC');

	INSERT INTO columns (column_name, data_type) VALUES ('order_quantity','varchar') RETURNING column_id INTO var_column_id;
	INSERT INTO customization_columns (customization_id, column_id, display_name, display_order, display_total, decimal_places, style) VALUES (var_cust_id, var_column_id, 'Order Quantity', 7, 1, 0, 'text-align:right;');

	SELECT column_id INTO var_column_id FROM columns WHERE column_name = 'uom_desc';
	INSERT INTO customization_columns (customization_id, column_id, display_name, display_order) VALUES (var_cust_id, var_column_id, 'UOM',7.5);

	INSERT INTO columns (column_name, data_type) VALUES ('trans_quantity','numeric') RETURNING column_id INTO var_column_id;
	INSERT INTO customization_columns (customization_id, column_id, display_name, display_order, display_total, decimal_places, style) VALUES (var_cust_id, var_column_id, 'Transaction Quantity', 8, 1, 0, 'text-align:right;');

	INSERT INTO columns (column_name, data_type) VALUES ('order_quantity-trans_quantity','numeric') RETURNING column_id INTO var_column_id;
	INSERT INTO customization_columns (customization_id, column_id, display_name, display_order, display_total, decimal_places, style) VALUES (var_cust_id, var_column_id, 'Balance Quantity', 9, 1, 0, 'text-align:right;');

	SELECT column_id INTO var_column_id FROM columns WHERE column_name = 'price';
	INSERT INTO customization_columns (customization_id, column_id, display_name, display_order,  decimal_places, style) VALUES (var_cust_id, var_column_id, 'Price', 10, 2, 'text-align:right;');

	INSERT INTO columns (column_name, data_type) VALUES ('order_quantity * price * conversion_rate','numeric') RETURNING column_id INTO var_column_id;
	INSERT INTO customization_columns (customization_id, column_id, display_name, display_order, display_total, decimal_places, style) VALUES (var_cust_id, var_column_id, 'Value',11, 1, 2, 'text-align:right;');

END;
$$
LANGUAGE plpgsql;
SELECT order_report_func();
DROP FUNCTION order_report_func();

CREATE OR REPLACE VIEW handloan_report_view AS SELECT hd.handln_id,hi.item_id,hd.handln_ref,hd.handln_date,re.relationship_name,hdb.handln_id AS parent_id,CASE WHEN hi.ref_id IS NOT NULL THEN hdb.handln_ref END AS parent_ref,pr.product_desc,uo.uom_desc,CASE WHEN tr.trans_type = 'o' AND hi.ref_id IS NULL THEN tr.quantity ELSE 0::numeric END AS issued_quantity,CASE WHEN tr.trans_type = 'o' THEN hib.received_quantity ELSE 0::numeric END AS received_back_quantity,CASE WHEN tr.trans_type = 'i' AND hi.ref_id IS NULL THEN tr.quantity ELSE 0::numeric END AS received_quantity,CASE WHEN tr.trans_type = 'i' THEN hib.received_quantity ELSE 0::numeric END AS issued_back_quantity
FROM handloan hd
JOIN handloan_items hi ON hd.handln_id=hi.handln_id
JOIN transaction_details tr ON tr.ref_id=hi.item_id AND tr.trans_head = 'hdl'
LEFT JOIN handloan_items hib ON hib.item_id=hi.ref_id
LEFT JOIN handloan hdb ON hdb.handln_id=hib.handln_id
JOIN relationships re ON re.relationship_id=hd.relationship_id
JOIN products pr ON pr.product_id=hi.product_id
JOIN uom uo ON uo.uom_id=hi.uom_id;

CREATE OR REPLACE FUNCTION handloan_report_func() RETURNS VOID
AS $$
DECLARE
	var_report_id int;
	var_cust_id int;
	var_column_id int;
BEGIN

	INSERT INTO reports (report_name, view_name) VALUES ('Handloan Report','handloan_report_view') RETURNING report_id INTO var_report_id;

	INSERT INTO customizations (customization_name, report_id, is_default,date_column) VALUES ('Def', var_report_id, 2,'handln_date') RETURNING customization_id INTO var_cust_id;

	SELECT column_id INTO var_column_id FROM columns WHERE column_name = 'relationship_name';
	INSERT INTO customization_columns (customization_id, column_id, display_name, display_order,is_group,sort_order) VALUES (var_cust_id, var_column_id, 'Buyer',1,1,'ASC');

	INSERT INTO columns (column_name, data_type) VALUES ('''<a href="details.php?module=handloan&handln_id=''||handln_id||''">''||handln_ref||''</a>''','int') RETURNING column_id INTO var_column_id;
	INSERT INTO customization_columns (customization_id, column_id, display_name, display_order) VALUES (var_cust_id, var_column_id, 'Handloan Reference', 2);

	INSERT INTO columns (column_name, data_type) VALUES ('handln_date','date') RETURNING column_id INTO var_column_id;
	INSERT INTO customization_columns (customization_id, column_id, display_name, display_order, is_group, sort_order) VALUES (var_cust_id, var_column_id, 'Handloan Date',3, 1, 'ASC');

	INSERT INTO columns (column_name, data_type) VALUES ('''<a href="details.php?module=handloan&handln_id=''||parent_id||''">''||parent_ref||''</a>''','int') RETURNING column_id INTO var_column_id;
	INSERT INTO customization_columns (customization_id, column_id, display_name, display_order) VALUES (var_cust_id, var_column_id, 'Parent Reference',4);

	SELECT column_id INTO var_column_id FROM columns WHERE column_name = 'product_desc';
	INSERT INTO customization_columns (customization_id, column_id, display_name, display_order,is_group,sort_order) VALUES (var_cust_id, var_column_id, 'Product', 6,1,'ASC');

	INSERT INTO columns (column_name, data_type) VALUES ('issued_quantity','numeric') RETURNING column_id INTO var_column_id;
	INSERT INTO customization_columns (customization_id, column_id, display_name, display_order, display_total, decimal_places, style) VALUES (var_cust_id, var_column_id, 'Issued Qty', 7, 1, 0, 'text-align:right;');

	INSERT INTO columns (column_name, data_type) VALUES ('received_back_quantity','numeric') RETURNING column_id INTO var_column_id;
	INSERT INTO customization_columns (customization_id, column_id, display_name, display_order, display_total, decimal_places, style) VALUES (var_cust_id, var_column_id, 'Received Back Qty', 8, 1, 0, 'text-align:right;');

	INSERT INTO columns (column_name, data_type) VALUES ('received_quantity','numeric') RETURNING column_id INTO var_column_id;
	INSERT INTO customization_columns (customization_id, column_id, display_name, display_order, display_total, decimal_places, style) VALUES (var_cust_id, var_column_id, 'Received Qty', 9, 1, 0, 'text-align:right;');

	INSERT INTO columns (column_name, data_type) VALUES ('issued_back_quantity','numeric') RETURNING column_id INTO var_column_id;
	INSERT INTO customization_columns (customization_id, column_id, display_name, display_order, display_total, decimal_places, style) VALUES (var_cust_id, var_column_id, 'Issued Back Qty', 10, 1, 0, 'text-align:right;');

	SELECT column_id INTO var_column_id FROM columns WHERE column_name = 'uom_desc';
	INSERT INTO customization_columns (customization_id, column_id, display_name, display_order) VALUES (var_cust_id, var_column_id, 'UOM',11);
END;
$$
LANGUAGE plpgsql;
SELECT handloan_report_func();
DROP FUNCTION handloan_report_func();

-- insert first customization for receipt report
CREATE OR REPLACE FUNCTION temp() RETURNS void
AS $$
DECLARE
	var_cust_id int;
BEGIN
	INSERT INTO customizations(customization_name, is_active, is_default, report_id, sub_total, grand_total) SELECT 'Default',is_active,1,report_id,sub_total,grand_total from customizations where customization_id =1 returning customization_id into var_cust_id;
	INSERT INTO customization_columns SELECT var_cust_id,column_id,display_name,display_order,sort_order,is_group,is_filter,default_value,date_condition,decimal_places,date_format,display_total,style from customization_columns where customization_id=1;

	INSERT INTO customizations(customization_name, is_active, is_default, report_id, sub_total, grand_total) SELECT 'Default',is_active,1,report_id,sub_total,grand_total from customizations where customization_id =2 returning customization_id into var_cust_id;
	INSERT INTO customization_columns SELECT var_cust_id,column_id,display_name,display_order,sort_order,is_group,is_filter,default_value,date_condition,decimal_places,date_format,display_total,style from customization_columns where customization_id=2;

	INSERT INTO customizations(customization_name, is_active, is_default, report_id, sub_total, grand_total) SELECT 'Default',is_active,1,report_id,sub_total,grand_total from customizations where customization_id =3 returning customization_id into var_cust_id;
	INSERT INTO customization_columns SELECT var_cust_id,column_id,display_name,display_order,sort_order,is_group,is_filter,default_value,date_condition,decimal_places,date_format,display_total,style from customization_columns where customization_id=3;

	INSERT INTO customizations(customization_name, is_active, is_default, report_id, sub_total, grand_total) SELECT 'Default',is_active,1,report_id,sub_total,grand_total from customizations where customization_id =4 returning customization_id into var_cust_id;
	INSERT INTO customization_columns SELECT var_cust_id,column_id,display_name,display_order,sort_order,is_group,is_filter,default_value,date_condition,decimal_places,date_format,display_total,style from customization_columns where customization_id=4;

	INSERT INTO customizations(customization_name, is_active, is_default, report_id, sub_total, grand_total) SELECT 'Default',is_active,1,report_id,sub_total,grand_total from customizations where customization_id =5 returning customization_id into var_cust_id;
	INSERT INTO customization_columns SELECT var_cust_id,column_id,display_name,display_order,sort_order,is_group,is_filter,default_value,date_condition,decimal_places,date_format,display_total,style from customization_columns where customization_id=5;

	INSERT INTO customizations(customization_name, is_active, is_default, report_id, sub_total, grand_total) SELECT 'Default',is_active,1,report_id,sub_total,grand_total from customizations where customization_id =6 returning customization_id into var_cust_id;
	INSERT INTO customization_columns SELECT var_cust_id,column_id,display_name,display_order,sort_order,is_group,is_filter,default_value,date_condition,decimal_places,date_format,display_total,style from customization_columns where customization_id=6;
END;
$$
LANGUAGE plpgsql;
SELECT temp();
DROP FUNCTION temp();
update columns set filter_query='select relationship_name as value from relationships where activity=''sel''' where column_id = 4;
update customization_columns set is_filter=1 where column_id =4;

create table users(
		user_id serial PRIMARY KEY ,
		user_name varchar not null UNIQUE,
		password varchar not null,
		display_name varchar,
		user_type varchar default 'int',
		added_date date default now()
		);

INSERT INTO users (user_name,password,display_name) values ('admin','YWRtaW4xMjM=','Admin');


CREATE OR REPLACE FUNCTION order_qty_update_func() RETURNS "trigger"
AS
$$
BEGIN
	IF ( NEW.ref_id IS NOT NULL ) THEN 
		IF( TG_OP = 'INSERT' OR TG_OP = 'UPDATE' ) THEN
			UPDATE order_items SET trans_quantity = trans_quantity + NEW.quantity WHERE item_id = NEW.ref_id;
		END IF;
		IF( TG_OP = 'DELETE' OR TG_OP = 'UPDATE' ) THEN
			UPDATE order_items SET trans_quantity = trans_quantity - OLD.quantity WHERE item_id = NEW.ref_id;
		END IF;
	END IF;
RETURN NULL;
END;
$$
LANGUAGE 'plpgsql';

CREATE TRIGGER receipt_qty_update_trig AFTER INSERT OR UPDATE OR DELETE ON receipt_items FOR EACH ROW EXECUTE PROCEDURE order_qty_update_func();

CREATE TRIGGER sales_qty_update_trig AFTER INSERT OR UPDATE OR DELETE ON sales_items FOR EACH ROW EXECUTE PROCEDURE order_qty_update_func();

alter table contacts ALTER COLUMN phone_no type varchar(50);

--ROLLBACK;
COMMIT;


