<!DOCTYPE html>
<html lang="en">

<head>


    <link rel="stylesheet" href="<?= base_url()?>assets/css/app/app.v1.css" />


    <meta charset="utf-8">
    <title>orderonline.mughalmahal.com</title>
    <style>
        @font-face {
            font-family: SourceSansPro;
            src: url(SourceSansPro-Regular.ttf);
        }

        .clearfix:after {
            content: "";
            display: table;
            clear: both;
        }

        a {
            color: #0087C3;
            text-decoration: none;
        }

        body {
            position: relative;
            width: 21cm;
            /*  height: 29.7cm;*/
            margin: 0 auto;
            color: #555555;
            background: #FFFFFF;
            font-family: Arial, sans-serif;
            font-size: 14px;
            /*font-family: SourceSansPro;*/
        }

        header {
            padding: 10px 0 0;
            margin-bottom: 0px;
            border-bottom: 0px !important;
            background-color: white;
        }

        #logo {
            float: left;
            margin-top: 8px;
        }

        #logo img {
            height: 150px;
        }

        #company {
            float: right;
            text-align: right;
        }


        #details {
            margin-bottom: 25px;
            border: 1px solid #aaa;
            border-top: 0px;
            border-right: 0px;
        }

        .client {
           padding: 15px 0px 15px 20px;
            float: left;
            border-right: 1px solid #aaa;

        }

        .client .to {
           /* color: #777777;*/
        }

        .client.text-right {
            padding-right: 6px;
            
            float: right;
            border-left: none;
        }

        h2.name {
            font-size: 1.4em;
            font-weight: normal;
            margin: 0;
        }

        #invoice {
            float: right;
            text-align: right;
        }

        #invoice h1 {
            color: #0087C3;
            font-size: 2.4em;
            line-height: 1em;
            font-weight: normal;
            margin: 0 0 10px 0;
        }

        #invoice .date {
            font-size: 1.1em;
            color: #777777;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
            border: 1px solid #AAAAAA;
            border-bottom: 0;
            border-left: 0px;
        }

        table th,
        table td {
            padding: 5px 14px;
           /* background: #EEEEEE;*/
            text-align: center;
            border-bottom: 1px solid #AAAAAA;
        }

        table th {
            white-space: nowrap;
            font-weight: normal;
        }

        table td {
            text-align: left;
            
        }

        table td h3 {
            color: #444;
            font-size: 1.2em;
            font-weight: normal;
            margin: 0 0 0.2em 0;
        }

        table .no {
            color: #FFFFFF;
            font-size: 1.6em;
            background: #57B223;
        }

        table .desc {
            text-align: left;
            border-right: 1px solid #aaa;
        }

        table .unit {
            /*background: #DDDDDD;*/
            text-align: center;
            border-right: 1px solid #aaa;
            border-left: 1px solid #aaa;
        }

        table .qty {
            text-align: center;
            border-right: 1px solid #aaa;
                border-left: 1px solid #aaa;
        }

        table .total {
            color: #57B223;
            text-align: center;
        }

        table td.unit,
        table td.qty,
        table td.total {
            font-size: 1.0em;
        }

        table tbody tr:last-child td {
           /* border: none;*/
        }

        table tfoot td {
            padding: 5px 14px;
            background: #FFFFFF;
            border-bottom: none;
            font-size: 1.0em;
            white-space: nowrap;
            
        }

        table tfoot tr:first-child td {
            border-top: none;
        }

        table tfoot tr.green_word td {
            color: #57B223;
            font-size: 1.4em;
            /*border-top: 1px solid #57B223;*/
        }

        table tfoot tr.amount_word td {
            font-size: 1.2em;
            padding: 10px 20px;
            background: #FFFFFF;
            white-space: nowrap;
            text-align: left;

        }

        table tfoot tr.amount_word.green_word td {
            border-bottom: 1px solid #aaa;
            border-top: 1px solid transparent;
        }

        #thanks {
            font-size: 2em;
            margin-bottom: 20px;
        }

        #notices {
            padding-left: 6px;
            border-left: 6px solid #0087C3;
        }

        #notices .notice {
            font-size: 1.2em;
        }

        footer {
            color: #777777;
            width: 100%;
            border-bottom: 1px solid #AAAAAA;
            padding: 8px 0;
            text-align: center;
            margin-bottom: 15px;
        }

        .w-100 {
            width: 100%;
        }

        .w-50 {
            width: 49.8%;
            display: inline-block;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
            float: right;
        }

        .text-left {
            text-align: left;
        }

        #company_details {
            padding: 15px 20px;
            border: 1px solid #ccc;
        }

        #company_details h2.text-center {
            margin: 10px 0;
        }
        .disclaimer_section{width: 100%;float: left;padding: 20px 0px;}
       .disclaimer_section ul{padding-inline-start: 20px;}
       .disclaimer_section li{font-size: 12px;line-height: 15px;padding-bottom: 5px;margin-bottom: 0px;}

    </style>
</head>

<body>
    <!--Start Header-->
    <header class="clearfix">
        <div id="logo">
            <img src="<?= base_url().'assets/images/avtar/mughal_mahal_logo.png'?>"/>
        </div>
        <button id="track_order" style="width: auto;float: right; margin-top: 15px;" class="btn btn-primary" onclick="window.print()"><i class="fa fa-print"></i> Print</button>
    </header>
    <!--End Header-->
    <main>
        <div id="company_details" class="clearfix">
            <h2 class="w-100 text-center">Order Details</h2>
            <div class="w-50">
                <div><span style="width: 125px;text-align: left;display: inline-block;float: left;" > Order # -</span> <span style="font-weight: 600;"><?php echo str_pad($orderDetail['sequence_no'],"6","0",STR_PAD_LEFT); ?></span></div>
                <div><span style="width: 125px;text-align: left;display: inline-block;float: left;" > Branch Name: </span> <span style="font-weight: 600;"><?php echo $orderDetail['restaurant_name'] ?></span></div>
                <div><span style="width: 125px;text-align: left;display: inline-block;float: left;" > Order Placed Time:</span>
                    <span style="font-weight: 600;">
                        <?php 
                        $date = date_create($orderDetail['order_placed_time']);
                        $formatted_date =  date_format($date,"M d Y H:i");
                        echo $formatted_date; ?>
                    </span>
                </div>
                <div><span style="width: 125px;text-align: left;display: inline-block;float: left;" > Delivery Time: </span>
                    <span style="font-weight: 600;">
                        <?php 
                        echo ($orderDetail['delivered_time'] !=null)?date_format(date_create($orderDetail['delivered_time']),'M d Y H:i'):"";
                        ?>
                    </span>
                </div>
                <?php if(isset($orderDetail['d_first_name'])) { ?>
                    <div><span style="width: 125px;text-align: left;display: inline-block;float: left;" > Assinged To:</span>
                        <span class="driverName driverName<?php echo $orderDetail['order_id']; ?>" style="font-weight: 600;">
                            <?php echo ($orderDetail['order_status']>3)?$orderDetail['d_first_name'].' '.$orderDetail['d_last_name']:''; ?>
                        </span>
                    </div>

                    <div class="form-group">
                        <label><span style="width: 125px;text-align: left;display: inline-block;float: left;" > Driver Contact:</span></label> 
                        <span class="driverContact driverContact<?php echo $orderDetail['order_id']; ?>" style="font-weight: 600;">
                           <?php echo ($orderDetail['order_status']>3 )?$orderDetail['d_contact_no']:''; ?>
                       </span>
                   </div>
               <?php } ?>
               <div><span style="width: 125px;text-align: left;display: inline-block;float: left;" > Payment Type:</span>
                    <span style="font-weight: 600;">
                        <?php echo $orderDetail['order_type_name'][$orderDetail['order_type']]; ?> 
                    </span>
               </div>
       </div>
       <div class="w-50 text-right">
            <div> Customer Name: <span style="font-weight: 600;"><?php echo $orderDetail['customer_name']; ?></span></div>
        <div>Contact No: <span style="font-weight: 600;"><?php echo ($orderDetail['customer_contact_no'] !='')?" (+965) ".$orderDetail['customer_contact_no']:''; ?></span></div>
        <div>Email: <span style="font-weight: 600;"><?php echo $orderDetail['customer_email'] ?></span></div>
    </div>
</div>
<div id="details" class="clearfix">
    <div class="client">
        <div class="to" style="font-weight: 600; width: 100%; float: left;"><span style="width: 140px;text-align: left;display: inline-block;float: left;" >Delivery Address</span></div>
        <div>
            <label><span style="font-weight: 600; width: 140px;text-align: left;display: inline-block;float: left;" >Area:</span> 
                <span ><?= ($orderDetail['area'] !="")?$orderDetail['area']:""; ?></span>
            </label>
        </div>
        <div style="width: 100%; float: left;">
            <label>
                <span style="font-weight: 600; width: 140px;text-align: left;display: inline-block;float: left;" >Block:</span> 
                <span ><?= ($orderDetail['block']!="")?$orderDetail['block']:""; ?></span>
            </label>
        </div>
        <div style="width: 100%; float: left;">
            <label>
                <span style="width: 140px;text-align: left;display: inline-block;float: left; font-weight: 600;" >Street: </span>
                <span ><?= ($orderDetail['street'] !="")?$orderDetail['street']:''; ?></span>
            </label>
        </div>
        <div style="width: 100%; float: left;">
            <label>
                <span style="width: 140px;text-align: left;display: inline-block;float: left;font-weight: 600;" >Avenue: </span>
                <span ><?= ($orderDetail['avenue']!="")?$orderDetail['avenue']:''; ?></span>
            </label>
        </div>
        <div style="width: 100%; float: left;">
            <label>
                <span style="font-weight: 600; width: 140px;text-align: left;display: inline-block;float: left;" >Building:</span>
                <span ><?= ($orderDetail['building']!="")?$orderDetail['building']:"" ?></span>
            </label>
        </div>
        <div style="width: 100%; float: left;">
            <label>
                <span style="font-weight: 600; width: 140px;text-align: left;display: inline-block;float: left;" >Floor:</span> 
                <span ><?= ($orderDetail['floor']!="")?$orderDetail['floor']:"";  ?></span>
            </label>
        </div>
        <div style="width: 100%; float: left;">
            <label><span style="font-weight: 600; width: 140px;text-align: left;display: inline-block;float: left;" >Appartment No:</span> 
                <span ><?= ($orderDetail['appartment_no'] !="")?$orderDetail['appartment_no']:""; ?></span>
            </label>
        </div>
      <!--   <div style="width: 100%; float: left;">
            <label>
               <span style="width: 140px;text-align: left;display: inline-block;float: left; font-weight: 600;" > Additional directions (Optional):</span>
                <span ><?= ($orderDetail['delivery_address']!="")?$orderDetail['delivery_address']:""; ?></span>
            </label>
        </div> -->
            
    </div>
</div>
<table border="0" cellspacing="0" cellpadding="0">
  <thead>
    <tr>
     <th class="qty">Quantity</th>
      <th class="desc">Dish</th>
      <th class="desc">Special Instruction</th>
      <th class="qty">Dish Price</th>
      <th class="total">Total Amount</th>
  </tr>
</thead>
  <tbody>

    <?php if(is_array($orderDetail['dishes']) && count($orderDetail['dishes'])>0){
            $count = 1;
            foreach($orderDetail['dishes'] as $key => $value){
    ?>
                <tr>
                    <td class="unit" style="font-weight: 600;"><?php echo $value['quantity']; ?></td>
                    <td class="desc">
                        <h3><?php echo $value['product_en_name']; ?></h3>
                        <?php
                            $choices = '';
                            if(isset($orderDetail['dishes'][$key]['choice'])) { 

                                if(is_array($orderDetail['dishes'][$key]['choice']) && count($orderDetail['dishes'][$key]['choice'])>0){
                                    foreach($orderDetail['dishes'][$key]['choice'] as $key1 => $value1){
                                        $choices = $choices.$value1['choice_name'].",";
                                    }
                                    echo rtrim($choices, ',');
                                }
                            }
                        ?>
                    </td>
                    <td class="desc"><?php echo $value['description']; ?></td>
                    <td class="qty"><?php echo number_format($value['price'],3,'.','').' KD'; ?></td>
                    
                    <td class="total" style="font-weight: 600;"><?php echo number_format($value['amount'],3,'.','').' KD';?></td>
                </tr>
    <?php   }
        }
    ?>
</tbody>
<tfoot style="border: 1px solid #aaa; border-bottom: 0;">
    <tr>
        <td colspan="3" style="padding: 5px;">Special Request: <?= !empty($orderDetail['special_instruction']) ? $orderDetail['special_instruction'] : "-" ?></td>
        <td colspan="1" style="border-bottom: 1px solid #aaa; border-left: 1px solid #aaa; text-align: center;">Order Amount </td>
        <td style="border-bottom: 1px solid #aaa; text-align: center;"><?php echo number_format(($orderDetail['total_price']-$orderDetail['delivery_charges']),3,'.','').' KD'; ?></td>
    </tr>
    <tr>
        <td colspan="3"></td>
        <td colspan="1" style="border-bottom: 1px solid #aaa; border-left: 1px solid #aaa; text-align: center;">Delivery Charges </td>
        <td style="border-bottom: 1px solid #aaa; text-align: center;"><?php echo number_format($orderDetail['delivery_charges'],3,'.','').' KD'; ?></td>
    </tr>
    <tr class="green_word">
        <td colspan="3"></td>
        <td colspan="1" style="border-bottom: 1px solid #aaa; border-left: 1px solid #aaa; text-align: center;">Grand Total </td>
        <td style="border-bottom: 1px solid #aaa; font-weight: 600; text-align: center;" ><?php echo number_format($orderDetail['total_price'],3,'.','').' KD'; ?></td>
    </tr>
     <tr>
        <td colspan="3"></td>
        <td colspan="1" style="border-bottom: 1px solid #aaa; border-left: 1px solid #aaa; text-align: center;">Delivery Time </td>
        <td style="border-bottom: 1px solid #aaa; text-align: center; font-weight: 600;">Now</td>
    </tr>
   <!--  <tr>
        <td colspan="4"></td>
        <td colspan="1" style="border-bottom: 1px solid #aaa; border-left: 1px solid #aaa;">Vat :</td>
        <td style="border-bottom: 1px solid #aaa;">amount here</td>
    </tr> -->

</tfoot>
</table>
<div class="disclaimer_section">
   <!--  <p style="font-weight: 600;">Disclaimer :</p>
    <ul>
        <li>text here 1</li>
        <li>text here 2</li>
        <li>text here 3</li>
        <li>text here 3</li>
    </ul> -->
</div>
</main>
<!-- <footer>
    <div id="notices">
      <div class="text-left">NOTICE:</div>
      <div class="notice text-left">Invoice was created on a computer and is valid without the signature and seal.</div>
  </div>

</footer> -->
</body>

</html>
<?php
if(!$print){
    exit();
}
?>
