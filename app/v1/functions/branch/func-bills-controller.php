<?php

class BillController{

    function getBillDetailsByCondition( $condition="" ){
        if($condition == "" ){
            return [
                "status" => "warning",
                "message" => "Condition is required",
                "data" => []
            ];
        }
        global $dbCon;
        $returnData = [];
        $query = "SELECT * FROM ".ERP_PURCHASE_BILLS." WHERE " . $condition;
        $result = mysqli_query($dbCon, $query);
        if(mysqli_num_rows($result) > 0){
            $returnData = [
                "status" => "success",
                "message" => "Bills are loaded successfully",
                "data" => mysqli_fetch_assoc($result)
            ];
        }else{
            $returnData = [
                "status" => "warning",
                "message" => "Record not found!",
                "data" => []
            ];
        }
        return $returnData;
    }

    function createNewBill($INPUTS=[]){
        global $dbCon;
        $returnData = [];
        $isValidate = validate($INPUTS, [
            "companyId" => "required",
            "branchId" => "required",
            "adminId" => "required",
            "vendorBillNumber" => "required",
            "billGrandTotal" => "required"
        ], [
            "companyId" => "Invalid company",
            "branchId" => "Invalid branch",
            "adminId" => "Invalid admin",
            "vendorBillNumber" => "Bill number must be required",
            "billGrandTotal" => "Grand total must be required"
        ]);

        if ($isValidate["status"] != "success") {
            $returnData['status'] = "warning";
            $returnData['message'] = "Invalid form inputes";
            $returnData['errors'] = $isValidate["errors"];
            return $returnData;
        }

        $companyId = $INPUTS["companyId"];
        $branchId = $INPUTS["branchId"];
        $adminId = $INPUTS["adminId"];
        $vendorBillNumber = $INPUTS["vendorBillNumber"];
        $billSubTotal = $INPUTS["billSubTotal"];
        $billGrandTotal = $INPUTS["billGrandTotal"];
        $orderNumber = $INPUTS["billOrderNumber"];
        $billRefNumber = $INPUTS["billRefNumber"];
        $billedDate = $INPUTS["billDate"];
        $billDueDate = $INPUTS["billDueDate"];

        $billNote = $INPUTS["billNote"];
        $billVendorname = $INPUTS["vendor"];


        $vendorId = "1";
        $vendorGstin = "18GVHJ8GVJAD6";
        $currencyCode = "INR";
        $billStatus = $INPUTS["billStatus"];

        $billToGstin = "19GVHJ8GVJAD9";
        $credit_gl_code = "02-01";
        $debit_gl_code = "01-01";


        //items
        $itemNameList = $INPUTS["itemName"];
        $itemHsnList = $INPUTS["itemHSN"];
        $itemDescriptionList = $INPUTS["itemDescription"];
        $itemQuantityList = $INPUTS["itemQuantity"];
        $itemUnitPriceList = $INPUTS["itemUnitPrice"];
        $itemTotalPriceList = $INPUTS["itemTotalPrice"];



        $sqlBill = "INSERT `".ERP_PURCHASE_BILLS."` 
                        SET `company_id`='".$companyId."',
                            `branch_id`='".$branchId."',
                            `bill_number`='".$vendorBillNumber."',
                            `bill_ref_number`='".$billRefNumber."',
                            `order_number`='".$orderNumber."',
                            `billed_date`='".$billedDate."',
                            `due_date`='".$billDueDate."',
                            `bill_sub_amount`='".$billSubTotal."',
                            `bill_total_amount`='".$billGrandTotal."',
                            `bill_to_gstin`='".$billToGstin."',
                            `currency_code`='".$currencyCode."',
                            `vendor_id`='".$vendorId."',
                            `vendor_gstin`='".$vendorGstin."',
                            `bill_notes`='".$billNote."',
                            `bill_created_by`='".$adminId."',
                            `bill_updated_by`='".$adminId."',
                            `bill_status`='".$billStatus."'";

        if($res = mysqli_query($dbCon, $sqlBill)){
            $billId = mysqli_insert_id($dbCon);

            $noOfSuccessfullyInsertedItems = 0;
            foreach($itemNameList as $itemKey=>$itemName){
                $itemId = rand(111111,999999); 
                $itemName = $itemNameList[$itemKey];
                $itemHsn = $itemHsnList[$itemKey];
                $itemDescription = $itemDescriptionList[$itemKey];
                $itemQuantity = $itemQuantityList[$itemKey];
                $itemUnitPrice = $itemUnitPriceList[$itemKey];
                $itemTotalPrice = $itemTotalPriceList[$itemKey];

                $sqlBillItem = "INSERT `".ERP_PURCHASE_BILLS_ITEMS."` 
                                    SET 
                                        `bill_id`='".$billId."',
                                        `item_id`='".$itemId."',
                                        `bill_item_desc`='".$itemDescription."',
                                        `bill_item_qty`='".$itemQuantity."',
                                        `bill_item_price`='".$itemUnitPrice."',
                                        `bill_item_total_price`='".$itemTotalPrice."', 
                                        `bill_item_created_by`='".$adminId."', 
                                        `bill_item_updated_by`='".$adminId."',
                                        `bill_item_status`='active'";

                if(mysqli_query($dbCon, $sqlBillItem)){
                    $noOfSuccessfullyInsertedItems++;
                }
            }
            if($billStatus=='active'){
                $sqlslct = "SELECT sr_no FROM `".ERP_ACC_JOURNAL."`  WHERE `company_id`='".$companyId."' AND branch_id='".$branchId."' ORDER BY sr_no desc LIMIT 1";
                $resultslct = mysqli_query($dbCon, $sqlslct);
                $seclt_row = mysqli_fetch_assoc($resultslct);
                $sr_no = $seclt_row['sr_no']+1;
                $jv_no = $sr_no."/"."2022-23";
                
                $sqljournal = "INSERT `".ERP_ACC_JOURNAL."` 
                                    SET 
                                        `invoice_id`='".$billId."',
                                        `company_id`='".$companyId."',
                                        `branch_id`='".$branchId."',
                                        `jv_no`='".$jv_no."',
                                        `sr_no`='".$sr_no."',
                                        `invoice_no`='".$vendorBillNumber."',
                                        `trans_date`='".$billedDate."',
                                        `remark`='".$billVendorname." - ".$vendorBillNumber." ".$billNote."- OCR Uploaded invoice',
                                        `journalEntryReference`='Payment/Expenses', 
                                        `journal_created_by`='".$adminId."', 
                                        `journal_updated_by`='".$adminId."'";

                mysqli_query($dbCon, $sqljournal);
                $journalId = mysqli_insert_id($dbCon);

                $sqlcredit = "INSERT `".ERP_ACC_CREDIT."` 
                                    SET 
                                        `journal_id`='".$journalId."',
                                        `credit_gl_code`='".$credit_gl_code."',
                                        `credit_amount`='".$billGrandTotal."',
                                        `credit_remark`='',
                                        `credit_created_by`='".$adminId."', 
                                        `credit_updated_by`='".$adminId."'";

                mysqli_query($dbCon, $sqlcredit);

                $sqldebit = "INSERT `".ERP_ACC_DEBIT."` 
                                    SET 
                                        `journal_id`='".$journalId."',
                                        `debit_gl_code`='".$debit_gl_code."',
                                        `debit_amount`='".$billGrandTotal."',
                                        `debit_remark`='',
                                        `debit_created_by`='".$adminId."', 
                                        `debit_updated_by`='".$adminId."'";

                mysqli_query($dbCon, $sqldebit);


            }

            if($noOfSuccessfullyInsertedItems == count($itemDescriptionList)){
                $returnData = [
                    "status" => "success",
                    "message" => "bill created successfully"
                ];
            }else{
                $returnData = [
                    "status" => "warning",
                    "message" => "bill created as draft, recheck the items and update the status"
                ];
            }
        }else{
            $returnData = [
                "status" => "warning",
                "message" => "bills creation failed"
            ];
        }
        return $returnData;
    }





}
