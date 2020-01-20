<?php

/**
 * Created by PhpStorm.
 * User: festus
 * Date: 5/13/17
 * Time: 9:33 AM
 */
class Api extends CI_Controller
{

    function __construct()
    {
        parent::__construct();

    }

    function receive_payment()
    {
        $ayear = $this->common_model->get_academic_year()->row()->AYear;


//        header('Content-type:text/xml');
        $data_xml = file_get_contents("php://input");
//        $data_xml = "<COMMAND>
//                    <TYPE>PAYMENT_REQUEST</TYPE>
//                    <PAYMENTID>786745635</PAYMENTID>
//                    <NETWORK>VODACOM</NETWORK>
//                    <REFERENCEID>1512</REFERENCEID>
//                    <MSISDN>0753443398</MSISDN>
//                    <AMOUNT>25000</AMOUNT>
//                    <RECEIPTNUMBER>3454674896</RECEIPTNUMBER>
//                </COMMAND>";
        $data = simplexml_load_string($data_xml);
        $data_array=array(
            'data' =>$data_xml
        );

        $this->db->insert('payments_log',$data_array);
        //get data from api
        $reference = $data->REFERENCEID;
        $payment_id = $data->PAYMENTID;
        $receipt = $data->RECEIPTNUMBER;
        $phone = $data->MSISDN;
        $amount = $data->AMOUNT;
        $myreference = substr($reference, 2);

        $json = json_encode($data);
        $array_data = json_decode($json,TRUE);

        $payments_log = array(
            'msisdn' => $phone,
            'reference' => $reference,
            'receipt' =>  $receipt,
            'amount' => $amount,
            'data' => print_r($array_data,true),
        );
        $this->db->insert('payments_log', $payments_log);

        $client = $this->applicant_model->get_applicant($myreference);

        //check if reference is exist / mine
        if(!$client){
            $response = "<COMMAND>
                        <TYPE>PAYMENT_RESPONSE</TYPE>
                        <PAYMENTID>$payment_id</PAYMENTID>
                        <REFERENCEID>$reference</REFERENCEID>
                        <RESPONSECODE>999</RESPONSECODE>
                        <RESPONSEDESC>APPLICANT NOT VALID</RESPONSEDESC>
                    </COMMAND>";
            $payments_log = array(
                'msisdn' => $phone,
                'reference' => $reference,
                'receipt' =>  $receipt,
                'amount' => $amount,
                'data' =>  $data_xml,
                'response'=>"APPLICANT NOT VALID"
            );
            $this->db->insert('payments_log', $payments_log);

        }else {
            //is amount sufficient
            if ($amount < APPLICATION_FEE) {
                $response = "<COMMAND>
                        <TYPE>PAYMENT_RESPONSE</TYPE>
                        <PAYMENTID>$payment_id</PAYMENTID>
                        <RESPONSECODE>999</RESPONSECODE>
                        <RESPONSEDESC>INSUFFICIENT AMOUNT</RESPONSEDESC>
                    </COMMAND>";
                $payments_log = array(
                    'msisdn' => $phone,
                    'reference' => $reference,
                    'receipt' =>  $receipt,
                    'amount' => $amount,
                    'data' =>  $data_xml,
                    'response'=>"INSUFFICIENT AMOUNT"
                );
                $this->db->insert('payments_log', $payments_log);
            } else {
                //check if receipt exist
                $check_receipt = $this->db->where("receipt", $receipt)->get("application_payment")->row();
                if (!$check_receipt) {
                    $payments_log = array(
                        'msisdn' => $phone,
                        'reference' => $reference,
                        'receipt' =>  $receipt,
                        'amount' => $amount,
                        'data' =>  $data_xml,
                        'response'=>"PAYMENT MADE SUCCEEFULLY"
                    );
                    $this->db->insert('payments_log', $payments_log);

                    $applicant_id = substr($reference, 2);
                    $client_info = $client_data = $this->applicant_model->get_applicant($applicant_id);
                    $trans_date = date('Y-m-d', strtotime($data['TIMESTAMP']));
                    $payment = array(
                        'msisdn' => $phone,
                        'reference' => $reference,
                        'applicant_id' => $applicant_id,
                        'timestamp' => $trans_date,
                        'receipt' => $receipt,
                        'amount' => $amount,
                        'AYear'=>$ayear
                        //'charges'=>$data['charges']

                    );

                    $this->db->insert('application_payment', $payment);
                    $response = "<COMMAND>
                        <TYPE>PAYMENT_RESPONSE</TYPE>
                        <PAYMENTID>$payment_id</PAYMENTID>
                        <RESPONSECODE>200</RESPONSECODE>
                        <RESPONSEDESC>PAYMENT MADE SUCCEEFULLY</RESPONSEDESC>
                    </COMMAND>";


                } else {
                    //echo duplicate data
                    $response = "<COMMAND>
                        <TYPE>PAYMENT_RESPONSE</TYPE>
                        <PAYMENTID>$payment_id</PAYMENTID>
                        <RESPONSECODE>999</RESPONSECODE>
                        <RESPONSEDESC>DUPLICATE</RESPONSEDESC>
                    </COMMAND>";
                    $payments_log = array(
                        'msisdn' => $phone,
                        'reference' => $reference,
                        'receipt' =>  $receipt,
                        'amount' => $amount,
                        'data' =>  $data_xml,
                        'response'=>"DUPLICATE"
                    );
                    $this->db->insert('payments_log', $payments_log);
                }
            }
        }
        header('Content-type:text/xml');
        $xml_doc = "<?xml version='1.0' encoding='UTF-8'?>\n";
        echo $xml_doc.$response;


    }

}