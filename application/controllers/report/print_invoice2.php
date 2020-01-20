<?php

error_reporting(0);
session_start();
ini_set("memory_limit", "-1");
set_time_limit(0);
$this->load->library('pdf');
$pdf = $this->pdf->startPDF('A4');
$pdf->SetHTMLHeader($this->pdf->PDFHeaderBill('Government Bill'));
$pdf->SetHTMLFooter($this->pdf->PDFFooter());
$date=date('Y-m-d',strtotime($invoice->timestamp));
$this->collegeinfo = get_collage_info();
$message=array(
    "opType"=>2,
    "shortCode"=>"001001",
    "billReference"=>$invoice->control_number,
    "amount"=>$invoice->amount,
    "billCcy"=>'TZS',
    "billExprDt"=>date("Y-m-d",strtotime(($date .' + 360days'))),
    "billPayOpt"=>3,
    "billRsv01"=>$this->collegeinfo->Name."|". $invoice->student_name
);
$message=json_encode($message);

//$message='{"opType":"2","shortCode":"001001","billReference":"991237654321","amount":"50000.0","billCcy":"TZS","billExprDt":"2018-09-01","billPayOpt":"2","billRsv01":"Tanzania Forest Agency|Augustino"}';
$postdata = array(
    "title" => $message,
    "control" =>$invoice->control_number ,

);

$file=$invoice->control_number.'.png';
$url='http://41.59.225.216/qr/';
$exists = remoteFileExists($url.'/Qrcode/images/'.$file);
if (!$exists) {
    $url=$url."Qrcode/qrcode.php";
    sendDataOverPost($url,$postdata);
}
//exit;
//if(!file_exists($url.'/Qrcode/images/'.$file))
//{
//    echo"siii   Nipoo";
//    exit;
//    $url=$url."Qrcode/qrcode.php";
//    sendDataOverPost($url,$postdata);
//
//}else{
//    echo"nipooo";
//    exit;
//}

$html = '<style>' . file_get_contents('./media/css/pdf_css.css') . '</style>';

//$html .= '<br><br/><h3 style="text-align: center; padding-top: 20px; margin: 0px;">INVOICE</h3>';
$html .= '<br/><br/><br/><br/>    <div>
   </div>';
$html .= '<tr/><table style="width: 100%;">
<tr>
<td style="width: 80%; vertical-align: top;">
<table  cellpadding="5" cellspacing="0">
                    <tr nobr="true">
                        <th  style=" text-align: left;">Control Number :</th>
                        <td >' . $invoice->control_number . '</td>
                    </tr>
                    <tr nobr="true">
                        <th width="240px"  style="text-align: left;">Payment Ref :</th>
                        <td >' . $invoice->type  . '</td>
                    </tr>
                    <tr nobr="true">
                        <th  style=" text-align: left;">Service Provider Code :</th>
                        <td>'.$ega_auth->spcode.'</td>
                    </tr>
                    <tr nobr="true">
                        <th  style=" text-align: left;">Payer Name :</th>
                        <td >' .$invoice->student_name. ' </td>
                    </tr>
                    <tr nobr="true">
                        <th  style=" text-align: left;">Bill Description :</th>
                        <td >' . $invoice->type . '</td>
                    </tr>
                   
                </table>

 </td>
 <td><img src="'.$url.'Qrcode/images/'.$invoice->control_number.'.png" alt="Qr Code"> </td>


</tr>
</table>';



$html .= '

<table class="table" cellspacing="0" cellpadding="3">
   
    <tbody>';

$html .= ' <tr>
            <td style="">Bill Item(1)</td>
            <td style="">' . $invoice->type . '</td>
            <td style="">' . $invoice->amount  . '</td>            
        </tr>
        <tr>
            <td style=""></td>
            <td style=""><b>Total Billed Amount</b></td>
            <td style="">' . $invoice->amount  . '(TZS)</td>            
        </tr>
    </tbody>
</table>';


$html .= '<table style="width: 100%;">
<tr>
<td style="width: 80%; vertical-align: top;">
<table  cellpadding="5" cellspacing="0">
                    <tr nobr="true">
                        <th  style=" text-align: left;">Amount in word(s) :</th>
                        <td ><b>' . strtoupper(convert_number_to_words((int)$invoice->amount)) . '</b></td>
                    </tr>
                    <tr nobr="true">
                        <th width="240px"  style="text-align: left;">Expires on :</th>
                        <td >' . date("Y-m-d",strtotime(($date .' + 30days')))  . '</td>
                    </tr>';

if($user->firstname)
{
    $html .= '<tr nobr="true">
                        <th  style=" text-align: left;">Prepared By :</th>
                        <td>' . $user->firstname. ' ' . $user->lastname. ' </td>
                    </tr>';

}else{
    $html .= '<tr nobr="true">
                        <th  style=" text-align: left;">Prepared By :</th>
                        <td>Accountant </td>
                    </tr>';

}


$html .= '<tr nobr="true">
                        <th  style=" text-align: left;">Collection Centre :</th>
                        <td>'. $this->collegeinfo->Name.' </td>
                    </tr>';

$html .= '<tr nobr="true">
                        <th  style=" text-align: left;">Printed By :</th>
                        <td >' . $user->firstname. ' ' . $user->lastname. '</td>
                    </tr>';
                   $html .= ' <tr nobr="true">
                        <th  style=" text-align: left;">Printed On :</th>
                        <td >' . date('d-m-Y') . '</td>
                    </tr>
                    
                     <tr nobr="true">
                        <th  style=" text-align: left;">Signature :</th>
                        <td >----------------------------------------</td>
                    </tr>
                       
                </table>

 </td>


</tr>
</table>';


$html .= '<table class="table" cellspacing="0" cellpadding="3">
    <thead>
    <tr>
        <th style="width: 50px;">Jinsi ya Kulipa</th>
        <th style="text-align: center; width: 100px;">How to Pay</th>
        
        
    </tr>
    </thead>
    <tbody>';

$html .= ' <tr>
            <td style="text-align: left;">1. Kupitia Bank:  Fika tawi lolote au wakala wa benki ya 
            NMB. Number ya kumbukumbu: <b>'.$invoice->control_number.'</b>
            </td>
            <td style="text-align: left;">1. Via Bank: Visit any branch or bank agent of  
            NMB. Reference number: <b>'.$invoice->control_number.'</b>
            </td>
                      
        </tr>
        <tr>
            <td >2. Kupitia Mitandao ya Simu
            <ul>
            <li>Ingia kwenye menyu  ya mtandao husika</li>
            <li>Chagua 4(Lipa Bill)</li>
            <li>Chagua 5(Malipo ya serikali)</li>
            <li>Ingiza <b>'.$invoice->control_number.'</b> kama number ya kumbukumbu</li>
         </ul>
        
            </td>
            <td >2. Via mobile network operators MNO: Enter to the respective USSD Menu of MNO
            <ul>
            <li>Select 4(Make Payment)</li>
            <li>Select 5(Government Payments)</li>
            <li>Enter <b>'.$invoice->control_number.'</b> as referrence number</li>
            </ul>
       
            </td>            
        </tr>
    </tbody>
</table>';

$pdf->WriteHTML($html);
//Close and output PDF document
$pdf->Output('Bill_Information' . '.pdf', 'D');
exit;
?>
