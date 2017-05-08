<html>
<?php
$limit = 100;
// timesheet																			table title
	if ($dbname == "timesheet" && $tablename == "employee") {					//employee
		$fldtoquery	=	"email_name";
		$fld_list	=	"email_name";
		$sql  =   "SELECT $fld_list FROM $tablename ORDER BY $fldtoquery LIMIT $limit;";
		// OK
	} elseif ($dbname == "timesheet" && $tablename == "foodlist") {			//foodlist
		/* table: foodlist. columns: foodid, name, price,  */;
		$fldtoquery	=	"foodid";
		$fld_list	=	"name, price";
		$sql  =   "SELECT $fld_list FROM $tablename ORDER BY $fldtoquery LIMIT $limit;";
	} elseif ($dbname == "timesheet" && $tablename == "foodorder") {			//foodorder
	/* table: foodorder. columns: email_name, foodid, fordate, remarks, orderby */
		$fldtoquery	=	"fordate";
		$fld_list	=	"email_name, foodid, fordate, orderby, remarks";
		$sql  =   "SELECT $fld_list FROM $tablename ORDER BY $fldtoquery DESC LIMIT $limit;";
	} elseif ($dbname == "timesheet" && $tablename == "company") {			//company
		/* table: company. columns: company_id, company_name, description, country, date_start, date_end */
		$fldtoquery	=	"company_name";
		$fld_list	=	"company_id, company_name, country";
		$sql  =   "SELECT $fld_list FROM $tablename ORDER BY $fldtoquery LIMIT $limit;";
	} elseif ($dbname == "timesheet" && $tablename == "country") {			//country
		/* table: country. columns: country_id, country */
		$fldtoquery	=	"country";
		$fld_list	=	"country_id, country";
		$sql  =   "SELECT $fld_list FROM $tablename ORDER BY $fldtoquery LIMIT $limit;";
	} elseif ($dbname == "timesheet" && $tablename == "marketing") {			//marketing
		$fldtoquery	=	"entry_no";
		$fld_list	=	"email_name, brief_code, time, company_name, country, entry_no";
		$sql  =   "SELECT $fld_list FROM $tablename ORDER BY $fldtoquery DESC LIMIT $limit;";
		/* table: marketing. columns: email_name, brief_code, time, company_name, 
			country, entry_no, */
	} elseif ($dbname == "timesheet" && $tablename == "code_prefix") {			//code_prefix
		/* table: code_prefix. columns: codehead_id, code_prefix, codelable */
		$fldtoquery	=	"codehead_id";
		$fld_list	=	"codehead_id, code_prefix, codelable";
		$sql  =   "SELECT $fld_list FROM $tablename ORDER BY $fldtoquery LIMIT $limit;";
	} elseif ($dbname == "timesheet" && $tablename == "dbtabfldlable") {			//dbtabfldlable
		/* table: dbtabfldlable. columns: db0, table0, field0, lable0 */
		$fldtoquery	=	"db0";
		$fld_list	=	"db0, table0, field0";
		$sql  =   "SELECT $fld_list FROM $tablename ORDER BY $fldtoquery LIMIT $limit;";		
	} elseif ($dbname == "timesheet" && $tablename == "projcodes") {			//projcodes
		/* table: projcodes. columns: projcode_id, brief_code, 
			description, special, div15, begin_date, end_date, costcenter */
		$fldtoquery	=	"brief_code";
		$fld_list	=	"projcode_id, brief_code, begin_date, end_date, special, div15";
		$sql  =   "SELECT $fld_list FROM $tablename ORDER BY $fldtoquery LIMIT $limit;";
	} elseif ($dbname == "timesheet" && $tablename == "chargecode") {			//chargecode
		//charging_code, rate, date
		$fldtoquery	=	"charging_code";
		$fld_list	=	"charging_code";
		$sql  =   "SELECT $fld_list FROM $tablename ORDER BY $fldtoquery LIMIT $limit;";
	} elseif ($dbname == "timesheet" && $tablename == "chargeclassfn") {			//chargeclassfn
		/* table: chargeclassfn. columns: email_name, charge_code,  */
		$fldtoquery	=	"email_name";
		$fld_list	=	"email_name";
		$sql  =   "SELECT $fld_list FROM $tablename ORDER BY $fldtoquery LIMIT $limit;";		
	} elseif ($dbname == "timesheet" && $tablename == "entry_no") {		//entry_no
		$fldtoquery	=	"entry_no";
		$fld_list	=	"entry_no, email_name, yyyymmdd";
		//$fld_list	=	"entry_no";
		$sql  =   "SELECT $fld_list FROM $tablename ORDER BY $fldtoquery DESC LIMIT $limit;";
	} elseif ($dbname == "timesheet" && $tablename == "timedata") {			//timedata
		$fldtoquery	=	"entry_no";
		//$fld_list	=	"entry_no, brief_code";
		$fld_list	=	"entry_no, brief_code, minutes";
		$sql  =   "SELECT $fld_list FROM $tablename ORDER BY $fldtoquery DESC LIMIT $limit;";
	} elseif ($dbname == "timesheet" && $tablename == "leavercd") {			//leavercd
		$fldtoquery	=	"entry_no";
		$fld_list	=	"email_name, brief_code, startday, minutes, entry_no";
		$fld_list	=	"*";
		$sql  =   "SELECT $fld_list FROM $tablename ORDER BY $fldtoquery DESC LIMIT $limit;";
	} elseif ($dbname == "timesheet" && $tablename == "researchrcd") {		//researchrcd
		$fldtoquery	=	"entry_no";
		$fld_list	=	"email_name, brief_code, entry_no, activity";
		$sql  =   "SELECT $fld_list FROM $tablename ORDER BY $fldtoquery DESC LIMIT $limit;";
	} elseif ($dbname == "timesheet" && $tablename == "privatecode") {		//privatecode
		$fldtoquery	=	"email_name";
		$fld_list	=	"email_name";
		$sql  =   "SELECT $fld_list FROM $tablename ORDER BY $fldtoquery LIMIT $limit;";
	} elseif ($dbname == "timesheet" && $tablename == "projleader") {		//projleader
		$fldtoquery	=	"id";
		$fld_list	=	"id, leader";
		$sql  =   "SELECT $fld_list FROM $tablename ORDER BY $fldtoquery LIMIT $limit;";
		/* table: projleader. columns: id, leader, codes, grpcode*/;
	} elseif ($dbname == "timesheet" && $tablename == "pub_holidays") {		//pub_holidays
		$fldtoquery	=	"date";
		$fld_list	=	"id, name, date, weekdays, minutes";
		$sql  =   "SELECT $fld_list FROM $tablename ORDER BY $fldtoquery LIMIT $limit;";
		/* table: pub_holidays. columns: name, date, weekdays, minutes */
	} elseif ($dbname == "timesheet" && $tablename == "myconid") {			//myconid
		/* table: myconid. columns: id, conn_no, yyyymmdd, */
		$fldtoquery	=	"id";
		$fld_list	=	"id, conn_no, yyyymmdd";
		$sql  =   "SELECT $fld_list FROM $tablename ORDER BY $fldtoquery LIMIT $limit;";
	} elseif ($dbname == "timesheet" && $tablename == "tsmailconf") {			//tsmailconf
		/* table: tsmailconf. columns: email_name, confirmation */
		$fldtoquery	=	"email_name";
		$fld_list	=	"email_name, confirmation";
		$sql  =   "SELECT $fld_list FROM $tablename ORDER BY $fldtoquery LIMIT $limit;";
	} elseif ($dbname == "timesheet" && $tablename == "leave_entitle") {			//leave_entitle
		/* table: leave_entitle. columns: email_name, first_name, middle_name, 
		last_name, lsl, al, sl, til, onthisday, */
		$fldtoquery	=	"email_name";
		$fld_list	=	"email_name, first_name, lsl, al, sl, til";
		$sql  =   "SELECT $fld_list FROM $tablename ORDER BY $fldtoquery LIMIT $limit;";

	} elseif ($dbname == "timesheet" && $tablename == "TSreceipt") {			//TSreceipt
		/* table: TSreceipt. columns: email_name, yyyymmdd, timestamp, */
		$fldtoquery	=	"timestamp";
		$fld_list	=	"email_name, yyyymmdd, timestamp";
		$sql  =   "SELECT $fld_list FROM $tablename ORDER BY $fldtoquery DESC LIMIT $limit;";
	} elseif ($dbname == "timesheet" && $tablename == "auto_notice") {			//auto_notice
		/* table: auto_notice. columns: msgid, msgfrom, msgto, msgsubject, msgbody, 
			msgtype, datastart, dateend, hour, timeplaced, active */
		$fldtoquery	=	"msgid";
		$fld_list	=	"msgid, msgfrom, timeplaced";
		$sql  =   "SELECT $fld_list FROM $tablename ORDER BY $fldtoquery DESC LIMIT $limit;";
	} elseif ($dbname == "timesheet" && $tablename == "ghrtorlacode") {			//ghrtorlacode
		/* table: ghrtorlacode. columns: ghrcode, rlacode, costcenter, dateenter, 
            enddate, active, isgroup  */
		$fldtoquery	=	"costcenter";
		$fld_list	=	"ghrcode, rlacode, costcenter, isgroup";
		$sql  =   "SELECT $fld_list FROM $tablename ORDER BY $fldtoquery;"; 
		// LIMIT $limit  ORDER BY $fldtoquery
	} elseif ($dbname == "timesheet" && $tablename == "wmmail") {			//wmmail
		/* table: wmmail. columns: email_name, receiver, date, */
		$fldtoquery	=	"date";
		$fld_list	=	"email_name, receiver, date";
		$sql  =   "SELECT $fld_list FROM $tablename ORDER BY $fldtoquery DESC LIMIT $limit;";
	} elseif ($dbname == "timesheet" && $tablename == "projbudgetfile") {			//projbudgetfile
		/* table: projbudgetfile. columns: budgetfileid, email_name, projcode_id, phaseno, 
            budgetfile, neworold, date, budgetstart, 
            budgetend, dateinrow  */
		$fldtoquery	=	"budgetfileid";
		$fld_list	=	"budgetfileid, email_name, projcode_id, phaseno, date";
		$sql  =   "SELECT $fld_list FROM $tablename ORDER BY $fldtoquery DESC LIMIT $limit;";
	} elseif ($dbname == "timesheet" && $tablename == "travel") {			//travel
		/* table: travel. columns: travelid, entry_no, email_name, company, time, activity,  */
		$fldtoquery	=	"travelid";
		$fld_list	=	"travelid, entry_no, email_name, company, time, activity";
		$sql  =   "SELECT $fld_list FROM $tablename ORDER BY $fldtoquery DESC LIMIT $limit;";
	} elseif ($dbname == "timesheet" && $tablename == "bgtfileidx") {			//bgtfileidx
		/* table: bgtfileidx. columns: bgtfileidx, projcode_id, description, client, 
            begin_date, end_date, preparedby, uploaddate, 
            active,  */
		$fldtoquery	=	"bgtfileidx";
		$fld_list	=	"bgtfileidx, projcode_id, description, client, 
            begin_date, end_date, preparedby, uploaddate";
		$sql  =   "SELECT $fld_list FROM $tablename ORDER BY $fldtoquery DESC LIMIT $limit;";
	} elseif ($dbname == "timesheet" && $tablename == "bgtfiletasks") {			//bgtfiletasks
		/* table: bgtfiletasks. columns: taskidx, bgtfileidx, m_stasks, tasks, 
            date_start, date_end, nomths, hours,  */
		$fldtoquery	=	"taskidx";
		$fld_list	=	"taskidx, bgtfileidx, m_stasks, tasks, 
            date_start, date_end, nomths, hours";
		$sql  =   "SELECT $fld_list FROM $tablename ORDER BY $fldtoquery DESC LIMIT $limit;";
	} elseif ($dbname == "timesheet" && $tablename == "bgtfilemlstone") {			//bgtfilemlstone
		/* table: bgtfilemlstone. columns: mlsidx, bgtfileidx, mlstone, date,  */
		$fldtoquery	=	"mlsidx";
		$fld_list	=	"mlsidx, bgtfileidx, mlstone, date";
		$sql  =   "SELECT $fld_list FROM $tablename ORDER BY $fldtoquery DESC LIMIT $limit;";
	} elseif ($dbname == "timesheet" && $tablename == "bgtfileresource") {			//bgtfileresource
		/* table: bgtfileresource. columns: bgtrscidx, bgtfileidx, email_name, title, 
            level, hrateus,  */
		$fldtoquery	=	"bgtrscidx";
		$fld_list	=	"bgtrscidx, bgtfileidx, email_name, title, level, hrateus";
		$sql  =   "SELECT $fld_list FROM $tablename ORDER BY $fldtoquery DESC LIMIT $limit;";
	} elseif ($dbname == "timesheet" && $tablename == "bgtfileres_task_hr") {	//bgtfileres_task_hr
		/* table: bgtfileres_task_hr. columns: rthidx, taskidx, bgtrscidx, hours,  */
		$fldtoquery	=	"rthidx";
		$fld_list	=	"rthidx, taskidx, bgtrscidx, hours";
		$sql  =   "SELECT $fld_list FROM $tablename ORDER BY $fldtoquery DESC LIMIT $limit;";

//##########
// tshtbackup																			table title
	} elseif ($dbname == "tshtbackup" && $tablename == "employee") {					//employee
		$fldtoquery	=	"email_name";
		$fld_list	=	"email_name";
		$sql  =   "SELECT $fld_list FROM $tablename ORDER BY $fldtoquery;";
		// OK
	} elseif ($dbname == "tshtbackup" && $tablename == "company") {			//company
		/* table: company. columns: company_id, company_name, description, country, date_start, date_end */
		$fldtoquery	=	"company_name";
		$fld_list	=	"company_id, company_name, country";
		$sql  =   "SELECT $fld_list FROM $tablename ORDER BY $fldtoquery;";
	} elseif ($dbname == "tshtbackup" && $tablename == "country") {			//country
		/* table: country. columns: country_id, country */
		$fldtoquery	=	"country";
		$fld_list	=	"country_id, country";
		$sql  =   "SELECT $fld_list FROM $tablename ORDER BY $fldtoquery;";
	} elseif ($dbname == "tshtbackup" && $tablename == "marketing") {			//marketing
		$fldtoquery	=	"entry_no";
		$fld_list	=	"email_name, brief_code, time, company_name, country, entry_no";
		$sql  =   "SELECT $fld_list FROM $tablename ORDER BY $fldtoquery DESC;";
		/* table: marketing. columns: email_name, brief_code, time, company_name, 
			country, entry_no, */
	} elseif ($dbname == "tshtbackup" && $tablename == "code_prefix") {			//code_prefix
		/* table: code_prefix. columns: codehead_id, code_prefix, codelable */
		$fldtoquery	=	"codehead_id";
		$fld_list	=	"codehead_id, code_prefix, codelable";
		$sql  =   "SELECT $fld_list FROM $tablename ORDER BY $fldtoquery;";
	} elseif ($dbname == "tshtbackup" && $tablename == "dbtabfldlable") {			//dbtabfldlable
		/* table: dbtabfldlable. columns: db0, table0, field0, lable0 */
		$fldtoquery	=	"db0";
		$fld_list	=	"db0, table0, field0";
		$sql  =   "SELECT $fld_list FROM $tablename ORDER BY $fldtoquery;";		
	} elseif ($dbname == "tshtbackup" && $tablename == "projcodes") {			//projcodes
		/* table: projcodes. columns: projcode_id, brief_code, description, special, div15, begin_date, end_date */
		$fldtoquery	=	"brief_code";
		$fld_list	=	"projcode_id, brief_code, special, div15";
		$sql  =   "SELECT $fld_list FROM $tablename ORDER BY $fldtoquery;";
	} elseif ($dbname == "tshtbackup" && $tablename == "chargecode") {			//chargecode
		//charging_code, rate, date
		$fldtoquery	=	"charging_code";
		$fld_list	=	"charging_code, rate";
		$sql  =   "SELECT $fld_list FROM $tablename ORDER BY $fldtoquery ;";
	} elseif ($dbname == "tshtbackup" && $tablename == "entry_no") {		//entry_no
		$fldtoquery	=	"entry_no";
		$fld_list	=	"entry_no, email_name, yyyymmdd";
		//$fld_list	=	"entry_no";
		$sql  =   "SELECT $fld_list FROM $tablename ORDER BY $fldtoquery DESC LIMIT $limit;";
	} elseif ($dbname == "tshtbackup" && $tablename == "timedata") {			//timedata
		$fldtoquery	=	"entry_no";
		//$fld_list	=	"entry_no, brief_code";
		$fld_list	=	"entry_no, brief_code, minutes";
		$sql  =   "SELECT $fld_list FROM $tablename ORDER BY $fldtoquery DESC  LIMIT $limit;";
	} elseif ($dbname == "tshtbackup" && $tablename == "leavercd") {			//leavercd
		$fldtoquery	=	"entry_no";
		$fld_list	=	"email_name, brief_code, startday, minutes, entry_no";
		$fld_list	=	"*";
		$sql  =   "SELECT $fld_list FROM $tablename ORDER BY $fldtoquery DESC;";
	} elseif ($dbname == "tshtbackup" && $tablename == "researchrcd") {		//researchrcd
		$fldtoquery	=	"entry_no";
		$fld_list	=	"email_name, brief_code, entry_no, activity";
		$sql  =   "SELECT $fld_list FROM $tablename ORDER BY $fldtoquery DESC;";
	} elseif ($dbname == "tshtbackup" && $tablename == "privatecode") {		//privatecode
		$fldtoquery	=	"email_name";
		$fld_list	=	"email_name";
		$sql  =   "SELECT $fld_list FROM $tablename ORDER BY $fldtoquery;";
	} elseif ($dbname == "tshtbackup" && $tablename == "projleader") {		//projleader
		$fldtoquery	=	"id";
		$fld_list	=	"id, leader";
		$sql  =   "SELECT $fld_list FROM $tablename ORDER BY $fldtoquery;";
		/* table: projleader. columns: id, leader, codes, grpcode*/;
	} elseif ($dbname == "tshtbackup" && $tablename == "pub_holidays") {		//pub_holidays
		$fldtoquery	=	"date";
		$fld_list	=	"id, name, date, weekdays, minutes";
		$sql  =   "SELECT $fld_list FROM $tablename ORDER BY $fldtoquery;";
		/* table: pub_holidays. columns: name, date, weekdays, minutes */
	} elseif ($dbname == "tshtbackup" && $tablename == "myconid") {			//myconid
		/* table: myconid. columns: id, conn_no, yyyymmdd, */
		$fldtoquery	=	"id";
		$fld_list	=	"id, conn_no, yyyymmdd";
		$sql  =   "SELECT $fld_list FROM $tablename ORDER BY $fldtoquery;";
	} elseif ($dbname == "tshtbackup" && $tablename == "tsmailconf") {			//tsmailconf
		/* table: tsmailconf. columns: email_name, confirmation */
		$fldtoquery	=	"email_name";
		$fld_list	=	"email_name, confirmation";
		$sql  =   "SELECT $fld_list FROM $tablename ORDER BY $fldtoquery;";
	} elseif ($dbname == "tshtbackup" && $tablename == "leave_entitle") {			//leave_entitle
		/* table: leave_entitle. columns: email_name, first_name, middle_name, 
		last_name, lsl, al, sl, til, onthisday, */
		$fldtoquery	=	"email_name";
		$fld_list	=	"email_name, first_name, lsl, al, sl, til";
		$sql  =   "SELECT $fld_list FROM $tablename ORDER BY $fldtoquery;";

	} elseif ($dbname == "tshtbackup" && $tablename == "TSreceipt") {			//TSreceipt
		/* table: TSreceipt. columns: email_name, yyyymmdd, timestamp, */
		$fldtoquery	=	"email_name";
		$fld_list	=	"email_name, yyyymmdd, timestamp";
		$sql  =   "SELECT $fld_list FROM $tablename ORDER BY $fldtoquery;";
	} elseif ($dbname == "tshtbackup" && $tablename == "auto_notice") {			//auto_notice
		/* table: auto_notice. columns: msgid, msgfrom, msgto, msgsubject, msgbody, 
			msgtype, datastart, dateend, hour, timeplaced, active */
		$fldtoquery	=	"msgid";
		$fld_list	=	"msgid, msgfrom, timeplaced";
		$sql  =   "SELECT $fld_list FROM $tablename ORDER BY $fldtoquery DESC;";
	} elseif ($dbname == "tshtbackup" && $tablename == "ghrtorlacode") {			//ghrtorlacode
		/* table: ghrtorlacode. columns: ghrcode, rlacode, costcenter, dateenter, 
            enddate, active,  */
		$fldtoquery	=	"ghrcode";
		$fld_list	=	"ghrcode, rlacode";	//, rlacode
		$sql  =   "SELECT $fld_list FROM $tablename;"; 
		// LIMIT $limit  ORDER BY $fldtoquery
		
// library																			table title
	} elseif ($dbname == "library" && $tablename == "accesstable") {			//auth_first
		/* table: accesstable. columns: id, country, patent_no, patent_title, assignee, 
			inventor, file_date, patent_date, classification, keycode, pct, keywords, 
			citations, abstract_only, electronic_copy */
		$fldtoquery	=	"patent_no";
		$fld_list	=	"country, patent_no";
		$sql  =   "SELECT $fld_list FROM $tablename ORDER BY $fldtoquery LIMIT $limit;";
	} elseif ($dbname == "library" && $tablename == "auth_first") {			//auth_first
		/* table: auth_first. columns: firstname_id, firstname, */
		$fldtoquery	=	"firstname_id";
		$fld_list	=	"firstname_id, firstname";
		$sql  =   "SELECT $fld_list FROM $tablename ORDER BY $fldtoquery DESC LIMIT $limit;";
	} elseif ($dbname == "library" && $tablename == "auth_middle") {			//auth_middle
		/* table: auth_middle. columns: middlename_id, middlename, */
		$fldtoquery	=	"middlename_id";
		$fld_list	=	"middlename_id, middlename";
		$sql  =   "SELECT $fld_list FROM $tablename ORDER BY $fldtoquery DESC LIMIT $limit;";
	} elseif ($dbname == "library" && $tablename == "auth_last") {			//auth_last
		/* table: auth_last. columns: lastname_id, lastname, */
		$fldtoquery	=	"lastname_id";
		$fld_list	=	"lastname_id, lastname";
		$sql  =   "SELECT $fld_list FROM $tablename ORDER BY $fldtoquery DESC LIMIT $limit;";
	} elseif ($dbname == "library" && $tablename == "author") {				//author
		/* table: author. columns: auth_id, title, firstname_id, middlename_id, lastname_id, 
			year_of_birth, year_of_death, email, homepage, telephone, fax, addr_no_street, 
			addr_suburb, addr_city, addr_state, addr_country, addr_postcode, */
		$fldtoquery	=	"auth_id";
		$fld_list	=	"auth_id, firstname_id, middlename_id, lastname_id";
		$sql  =   "SELECT $fld_list FROM $tablename ORDER BY $fldtoquery DESC LIMIT $limit;";
		
	} elseif ($dbname == "library" && $tablename == "for_book") {				//for_book
		/* table: for_book. columns: book_id, lib_item_id, barcode, dewey, isbn, editor, 
			edition, volume, no_pages, year_published, pub_id, copyright, year_in, 
			year_cancellation, missing_record, */
		$fldtoquery	=	"book_id";
		$fld_list	=	"book_id, lib_item_id, barcode, dewey, isbn";
		$sql  =   "SELECT $fld_list FROM $tablename ORDER BY $fldtoquery DESC LIMIT $limit;";		
	} elseif ($dbname == "library" && $tablename == "for_patent") {			//for_patent
		/* table: for_patent. columns: patent_id, lib_item_id, country, patent_no, 
		assignee, file_date, pub_date, patent_date, intnl_clsfcn, citations, no_pages, elec_copy_add */
		$fldtoquery	=	"lib_item_id";
		$fld_list	=	"patent_id, lib_item_id, patent_no, elec_copy_add";
		$sql  =   "SELECT $fld_list FROM $tablename ORDER BY $fldtoquery DESC LIMIT $limit;";
		//$sql  =   "SELECT $fld_list FROM $tablename WHERE patent_no='3070442';";
		//3070442
	} elseif ($dbname == "library" && $tablename == "keywords") {				//keywords
		/* table: keywords. columns: keyword_id, keyword, */
		$fldtoquery	=	"keyword_id";
		$fld_list	=	"keyword_id, keyword";
		$sql  =   "SELECT $fld_list FROM $tablename ORDER BY $fldtoquery DESC LIMIT $limit;";
	} elseif ($dbname == "library" && $tablename == "lib_entry") {				//lib_entry
		/* table: lib_entry. columns: log_id, action, lib_item_id, cat_id, email, date */
		$fldtoquery	=	"log_id";
		$fld_list	=	"log_id, action, lib_item_id, cat_id, email, date";
		$sql  =   "SELECT $fld_list FROM $tablename ORDER BY $fldtoquery DESC LIMIT $limit;";
	} elseif ($dbname == "library" && $tablename == "lib_item_status") {		//lib_item_status
		/* table: lib_item_status. columns: status_id, lib_item_id, barcode, 
			borrower_email_name, date_out, date_return,  */
		$fldtoquery	=	"status_id";
		$fld_list	=	"status_id, lib_item_id, barcode, borrower_email_name, date_out, date_return";
		$sql  =   "SELECT $fld_list FROM $tablename ORDER BY $fldtoquery DESC LIMIT $limit;";
	} elseif ($dbname == "library" && $tablename == "lib_primlist") {			//lib_primlist
		/* table: lib_primlist. columns: lib_item_id, cat_id, author_id, libtitle, keyword_id, abstract, */
		$fldtoquery	=	"lib_item_id";
		//$fld_list	=	"lib_item_id, cat_id, author_id, keyword_id";
		$fld_list	=	"lib_item_id, cat_id, libtitle";
		$sql  =   "SELECT $fld_list FROM $tablename ORDER BY $fldtoquery DESC LIMIT $limit;";
	} elseif ($dbname == "library" && $tablename == "library_cat") {			//library_cat
		/* table: library_cat. columns: cat_id, category, */	
		$fldtoquery	=	"cat_id";
		$fld_list	=	"cat_id, category";
		$sql  =   "SELECT $fld_list FROM $tablename ORDER BY $fldtoquery LIMIT $limit;";
	} elseif ($dbname == "library" && $tablename == "prim_auth") {			//prim_auth
		/* table: prim_auth. columns: lib_item_id, auth_id */	
		$fldtoquery	=	"lib_item_id";
		$fld_list	=	"lib_item_id, auth_id";
		$sql  =   "SELECT $fld_list FROM $tablename ORDER BY $fldtoquery DESC LIMIT $limit;";
	} elseif ($dbname == "library" && $tablename == "prim_keyword") {			//prim_keyword
		/* table: prim_keyword. columns: lib_item_id, keyword_id, */	
		$fldtoquery	=	"lib_item_id";
		$fld_list	=	"lib_item_id, keyword_id";
		$sql  =   "SELECT $fld_list FROM $tablename ORDER BY $fldtoquery DESC LIMIT $limit;";
	} elseif ($dbname == "library" && $tablename == "pub_contact") {			//pub_contact
		/* table: pub_contact. columns: auth_id, pub_id, */
		$fldtoquery	=	"auth_id";
		$fld_list	=	"auth_id, pub_id";
		$sql  =   "SELECT $fld_list FROM $tablename ORDER BY $fldtoquery LIMIT $limit;";
	} elseif ($dbname == "library" && $tablename == "publisher") {			//publisher
		/* table: publisher. columns: pub_id, pub_name, email, homepage, telephone, fax, 
			po_box, addr_no_street, addr_suburb, addr_city, addr_state, addr_country, 
			addr_postcode, year_establish, year_of_closedown */
		$fldtoquery	=	"pub_id";
		$fld_list	=	"pub_id, pub_name";
		$sql  =   "SELECT $fld_list FROM $tablename ORDER BY $fldtoquery DESC LIMIT $limit;";

	} elseif ($dbname == "library" && $tablename == "dumppatent") {			//dumppatent
		/* table: dumppatent. columns: id, country, patent_no, patent_title, assignee, 
			inventor, file_date, patent_date, classification, keycode, pct, keywords, 
			abstract, citations, abstract_only, electronic_copy, */

		$fldtoquery	=	"id";
		$fld_list	=	"id, country, patent_no, patent_title";
		$sql  =   "SELECT $fld_list FROM $tablename ORDER BY $fldtoquery DESC LIMIT $limit;";
		//$sql  =   "SELECT $fld_list FROM $tablename WHERE id=4246;";
	} elseif ($dbname == "library" && $tablename == "rlahdbk") {			//rlahdbk
		/* table: rlahdbk. columns: id, style, text, */
		$fldtoquery	=	"id";
		$fld_list	=	"id, style";
		$sql  =   "SELECT $fld_list FROM $tablename ORDER BY $fldtoquery;";
	} elseif ($dbname == "library" && $tablename == "patcomments") {			//patcomments
       /* table: patcomments. columns: comid, patent_no, email_name, comments, 
            date,  */
		$fldtoquery	=	"email_name";
		$fld_list	=	"patent_no, email_name";
		$sql  =   "SELECT $fld_list FROM $tablename ORDER BY $fldtoquery;";

// inventory																			table title
	} elseif ($dbname == "inventory" && $tablename == "entry_id") {			//entry_id
		/* table: entry_id columns: entry_id, email_name, computer_ip_addr, yyyymmdd, */ 
		$fldtoquery	=	"entry_id";
		$fld_list	=	"entry_id, email_name, computer_ip_addr, yyyymmdd";
		$sql  =   "SELECT $fld_list FROM $tablename ORDER BY $fldtoquery DESC LIMIT $limit;";
		// OK
	} elseif ($dbname == "inventory" && $tablename == "primlist") {			//itemlist
		/* table: primlist. columns: itemid, catid, brandid, manid, 
            biref_description, barcode, size_xyz_cm, color, 
            year_made, order_no, purchase_date, disposal, 
            purchasing_price, purchased_by, purchased_for, entry_id,  */
       $fldtoquery	=	"biref_description";
		$fld_list	=	"biref_description, barcode";
		$sql  =   "SELECT $fld_list FROM $tablename ORDER BY $fldtoquery DESC;";//LIMIT $limit
		// OK
	} elseif ($dbname == "inventory" && $tablename == "tracking") {			//tracking
		/* table: tracking. columns: id, itemid, barcode, email_name, location, yyyymmdd, entry_id, ownnow*/
		$fldtoquery	=	"id";
		$fld_list	=	"id, itemid, barcode, email_name, ownnow, location, yyyymmdd, entry_id";
		$sql  =   "SELECT $fld_list FROM $tablename ORDER BY $fldtoquery DESC LIMIT $limit;";
		// OK
	} elseif ($dbname == "inventory" && $tablename == "brandname") {			//brandname
		/* table: brandname. columns: brandid, brand_name, */
		$fldtoquery	=	"brandid";
		$fld_list	=	"brandid, brand_name";
		$sql  =   "SELECT $fld_list FROM $tablename ORDER BY $fldtoquery DESC LIMIT $limit;";
		// OK
	} elseif ($dbname == "inventory" && $tablename == "category") {			//itemname
		/* table: category. columns: catid, cat_name, */
		$fldtoquery	=	"catid";
		$fld_list	=	"catid, cat_name";
		$sql  =   "SELECT $fld_list FROM $tablename ORDER BY $fldtoquery DESC LIMIT $limit;";
		// OK
	} elseif ($dbname == "inventory" && $tablename == "manufacture") {		//manufacture
		/* table: manufacture. columns: manid, manufacture, */
		$fldtoquery	=	"manid";
		$fld_list	=	"manid, manufacture";
		$sql  =   "SELECT $fld_list FROM $tablename ORDER BY $fldtoquery DESC LIMIT $limit;";
		// OK
		
//rlafinance
	} elseif ($dbname == "rlafinance" && $tablename == "backorder") {		//backorder
		/* table: backorder. columns: autoid, order_id, unit_ord, unit_rcvd, product, 
            checkin, bdate, rdate,  */;
       $fldtoquery	=	"autoid";
		$fld_list	=	"autoid, order_id, unit_ord, unit_rcvd, product, checkin";
		$sql  =   "SELECT $fld_list FROM $tablename ORDER BY $fldtoquery;";
	} elseif ($dbname == "rlafinance" && $tablename == "control") {		//control
		/* table: control. columns: controllist, details, */
		$fldtoquery	=	"controllist";
		$fld_list	=	"controllist";
		$sql  =   "SELECT $fld_list FROM $tablename ORDER BY $fldtoquery;";
	} elseif ($dbname == "rlafinance" && $tablename == "creditcard") {		//creditcard
		/* table: creditcard. columns: card_id, holder, cardtype, cardno, expiredate, issuer */
		$fldtoquery	=	"card_id";
		$fld_list	=	"card_id, holder";
		$sql  =   "SELECT $fld_list FROM $tablename ORDER BY $fldtoquery;";
	} elseif ($dbname == "rlafinance" && $tablename == "supplierid") {		//supplierid
		/* table: supplier. columns: supid, company, address, contactperson, telno, faxno, mobno, email, www, */
		$fldtoquery	=	"company";
		$fld_list	=	"company, supid";
		$sql  =   "SELECT $fld_list FROM $tablename ORDER BY $fldtoquery;";
	} elseif ($dbname == "rlafinance" && $tablename == "goodsid") {		//goodsid
		/* table: goodslist. columns: goods_id, name, description, supid, product_code */
		$fldtoquery	=	"goods_id";
		$fld_list	=	"goods_id, name, description";
		$sql  =   "SELECT $fld_list FROM $tablename ORDER BY $fldtoquery;";
		
	} elseif ($dbname == "rlafinance" && $tablename == "codeid") {		//codeid
		/* table: chargingcode. columns: code_id, description, codes, category, rlaactive */
		$fldtoquery	=	"code_id";
		//$fld_list	=	"code_id, codes, category, rlaactive";
		$fld_list	=	"code_id, description, codes, category";
		$sql  =   "SELECT $fld_list FROM $tablename ORDER BY $fldtoquery;";
	} elseif ($dbname == "rlafinance" && $tablename == "paymethod") {		//paymethod
		/* table: paymethod. columns: method_id, description */
		$fldtoquery	=	"description";
		$fld_list	=	"description";
		$sql  =   "SELECT $fld_list FROM $tablename ORDER BY $fldtoquery DESC ;";
	} elseif ($dbname == "rlafinance" && $tablename == "orderdetails") {		//orderdetails
		/* table: orderdetails. columns: detailsid, order_id, goods_id, supid, unit, unit_price, 
		gst_applicable, gst_percent, code_id, rlaprojid, to_inventory, inv_processed, 
		checkin, */
		$fldtoquery	=	"detailsid";
		$fld_list	=	"detailsid, order_id, goods_id, unit, unit_price, code_id, rlaprojid, checkin";
		$sql  =   "SELECT $fld_list FROM $tablename ORDER BY $fldtoquery DESC;";
	} elseif ($dbname == "rlafinance" && $tablename == "priceref") {		//priceref
		/* table: priceref. columns: goods_id, price, ymd*/
		$fldtoquery	=	"goods_id";
		$fld_list	=	"goods_id, price, ymd";
		$sql  =   "SELECT $fld_list FROM $tablename ORDER BY $fldtoquery DESC;";
	} elseif ($dbname == "rlafinance" && $tablename == "orderid") {		//orderid
		/* table: orderid. columns: order_id, order_by, orderdate, estimate_cost, spec_instruction, 
			updatestatus, ordercancelled, paymethod, card_cheque, pay_remarks, invoice_no, invoice_date, invoice_cost, 
			invoice_gst, delivery_cost, delivery_gst, */
		$fldtoquery	=	"order_id";
		$fld_list	=	"order_id, order_by, orderdate, estimate_cost, updatestatus, ordercancelled";
		$sql  =   "SELECT $fld_list FROM $tablename ORDER BY $fldtoquery DESC;";
	} elseif ($dbname == "rlafinance" && $tablename == "ordersteps") {		//ordersteps
		/* table: ordersteps. columns: order_id, process, */
		$fldtoquery	=	"order_id";
		$fld_list	=	"order_id, process";
		$sql  =   "SELECT $fld_list FROM $tablename ORDER BY $fldtoquery DESC;";
	} elseif ($dbname == "rlafinance" && $tablename == "ordermod") {		//ordermod
		/* table: ordermod. columns: order_id, moddate, orddetbmod, */
		$fldtoquery	=	"order_id";
		$fld_list	=	"order_id, moddate";
		$sql  =   "SELECT $fld_list FROM $tablename ORDER BY $fldtoquery DESC;";
	} elseif ($dbname == "rlafinance" && $tablename == "queryorder") {		//queryorder
		/* table: queryorder. columns: order_id, query_by, query_msg,  */
		$fldtoquery	=	"order_id";
		$fld_list	=	"order_id, query_by, query_msg";
		$sql  =   "SELECT $fld_list FROM $tablename ORDER BY $fldtoquery DESC;";
	} elseif ($dbname == "rlafinance" && $tablename == "mailrcd") {		//mailrcd
		/* table: mailrcd. columns: mailid, order_id, mfrom, mto, relation, tstamp,  */
		$fldtoquery	=	"mailid";
		$fld_list	=	"mailid, order_id, mfrom, mto, relation, tstamp";
		$sql  =   "SELECT $fld_list FROM $tablename ORDER BY $fldtoquery DESC;";
	} elseif ($dbname == "rlafinance" && $tablename == "rlapjcvsghrsccc") {		//rlapjcvsghrsccc
        /* table: rlapjcvsghrsccc. columns: projcode_id, subac, ccc,  */
		$fldtoquery	=	"projcode_id";
		$fld_list	=	"projcode_id, subac, ccc";
		$sql  =   "SELECT $fld_list FROM $tablename ORDER BY $fldtoquery DESC;";
	} elseif ($dbname == "rlafinance" && $tablename == "orderreason") {		//orderreason
		/* table: orderreason. columns: order_id, orderreason,  */
		$fldtoquery	=	"order_id";
		$fld_list	=	"order_id, orderreason";
		$sql  =   "SELECT $fld_list FROM $tablename ORDER BY $fldtoquery DESC;";

// logging																				table title
	} elseif ($dbname == "logging" && $tablename == "accesslevel") {			//accesslevel
		$fldtoquery	=	"id";
		$fld_list	=	"id, email_name, priviledge";
		$sql  =   "SELECT $fld_list FROM $tablename ORDER BY $fldtoquery LIMIT $limit;";
	} elseif ($dbname == "logging" && $tablename == "access_rcd") {			//access_rcd
		$fldtoquery	=	"email_name";
		$fld_list	=	"entry_id,email_name, action, timestamp";
		$sql  =   "SELECT $fld_list FROM $tablename ORDER BY $fldtoquery LIMIT $limit;";
	} elseif ($dbname == "logging" && $tablename == "acccodedef") {			//acccodedef
		$fldtoquery	=	"priviledge";
		$fld_list	=	"priviledge, definition";
		$sql  =   "SELECT $fld_list FROM $tablename ORDER BY $fldtoquery LIMIT $limit;";	
	} elseif ($dbname == "logging" && $tablename == "logout") {			//logout
		/* table: logout. columns: email_name, logintime, lastaccess, logouttime */
		$fldtoquery	=	"lastaccess";
		$fld_list	=	"lastaccess, logintime, email_name, logouttime";
		$sql  =   "SELECT $fld_list FROM $tablename ORDER BY $fldtoquery DESC LIMIT $limit;";	
	} elseif ($dbname == "logging" && $tablename == "sysmastertable") {			//sysmastertable
		/* table: sysmastertable. columns: id, item, description, */
		$fldtoquery	=	"id";
		$fld_list	=	"id,item";
		$sql  =   "SELECT $fld_list FROM $tablename ORDER BY $fldtoquery DESC LIMIT $limit;";	
	} elseif ($dbname == "logging" && $tablename == "OStable") {			//OStable
		/* table: OStable. columns: email_name, winos, explore, date, */
		$fldtoquery	=	"winos";
		$fld_list	=	"email_name, winos, explore, date"; //, file;//
		$sql  =   "SELECT $fld_list FROM $tablename ORDER BY $fldtoquery DESC LIMIT $limit;";		
	} elseif ($dbname == "logging" && $tablename == "logsec") {			//logsec
		/* table: logsec. columns: email_name, ip, logsec, logtext, date */
		$fldtoquery	=	"email_name";
		$fld_list	=	"email_name, ip, date";//
		$sql  =   "SELECT $fld_list FROM $tablename ORDER BY $fldtoquery DESC LIMIT $limit;";	
	} elseif ($dbname == "logging" && $tablename == "sqlerrlog") {			//sqlerrlog
		/* table: sqlerrlog. columns: id, ename, file, line, sql, err, date */
		$fldtoquery	=	"id";
		$fld_list	=	"id, ename, file, line, date";//
		$sql  =   "SELECT $fld_list FROM $tablename ORDER BY $fldtoquery DESC LIMIT $limit;";	

	} elseif ($dbname == "logging" && $tablename == "employee") {			//employee
		/* table: employee. columns: email_name, logon_name, first_name, middle_name, last_name, oldname*/
		$fldtoquery	=	"email_name";
		$fld_list	=	"email_name, logon_name, first_name, middle_name, last_name, oldname";//
		$sql  =   "SELECT $fld_list FROM $tablename ORDER BY $fldtoquery;";	
	} elseif ($dbname == "logging" && $tablename == "code_prefix") {			//code_prefix
		/* table: code_prefix. columns: oldprefix, code_prefix, codelable, */
		$fldtoquery	=	"code_prefix";
		$fld_list	=	"oldprefix, code_prefix, codelable";//
		$sql  =   "SELECT $fld_list FROM $tablename ORDER BY $fldtoquery;";	
	} elseif ($dbname == "logging" && $tablename == "projcodes") {			//projcodes
	/* table: projcodes. columns: projcode_id, brief_code_old, brief_code, description, */
		$fldtoquery	=	"projcode_id";
		$fld_list	=	"projcode_id, brief_code_old, brief_code, description";//
		$sql  =   "SELECT $fld_list FROM $tablename ORDER BY $fldtoquery;";	
	} elseif ($dbname == "logging" && $tablename == "mailfwd") {			//mailfwd
	/* table: mailfwd. columns: mailfwdid, email_name, addr, datestart, time,  */
		$fldtoquery	=	"datestart";
		$fld_list	=	"mailfwdid, email_name, addr, datestart, time";//
		$sql  =   "SELECT $fld_list FROM $tablename ORDER BY $fldtoquery DESC;";	

// mysql																			table title
	} elseif ($dbname == "mysql" && $tablename == "user") {					//user
		$fldtoquery	=	"User";
		$fld_list	=	"User";
		$sql  =   "SELECT $fld_list FROM $tablename ORDER BY $fldtoquery LIMIT $limit;";
		//OK
	} elseif ($dbname == "mysql" && $tablename == "db") { 						//db
		$fldtoquery	=	"Db";
		$fld_list	=	"User,Db";
		$sql  =   "SELECT $fld_list FROM $tablename ORDER BY user LIMIT $limit;";
		// OK
	} elseif ($dbname == "mysql" && $tablename == "host") {					//host
		$fldtoquery	=	"Db";
		$fld_list	=	"Db";
		$sql  =   "SELECT $fld_list FROM $tablename ORDER BY $fldtoquery LIMIT $limit;";
		// doesn't work when client through www to access mysql';
	} elseif ($dbname == "mysql" && $tablename == "tables_priv") {			//tables_priv
		$fldtoquery	=	"User";
		$fld_list	=	"User,Db,Table_name";
		$sql  =   "SELECT $fld_list FROM $tablename ORDER BY $fldtoquery LIMIT $limit;";
	} elseif ($dbname == "mysql" && $tablename == "columns_priv") {			//columns_priv
		$fldtoquery	=	"User";
		$fld_list	=	"User";
		$sql  =   "SELECT $fld_list FROM $tablename ORDER BY $fldtoquery LIMIT $limit;";
	} elseif ($dbname == "mysql" && $tablename == "func") {					//func
		$fldtoquery	=	"name";
		$fld_list	=	"name";
		$sql  =   "SELECT $fld_list FROM $tablename ORDER BY $fldtoquery LIMIT $limit;";		
	}
	//echo  $sql."<br>";
?>
</html>
