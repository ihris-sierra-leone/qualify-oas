<?php
/**
 * Created by PhpStorm.
 * User: miltone
 * Date: 5/13/17
 * Time: 9:33 AM
 */
class Egaapi extends  CI_Controller
{

    function __construct()
    {
        parent::__construct();
    }

    function receive_payment()
    {

        $data_json = file_get_contents("php://input");
        $data = json_decode($data_json, TRUE);
        if($data['status']==1)
        {
            $reference=$data['reference'];
            $invoice_number = substr($reference, 3);
            $log_data_array=array(
                'responce'=>print_r($data,true),
                'status'=>$data['status'],
                'description'=>$data['description'],
                'type'=>'payment'
            );

            $checkif_exit=$this->db->query("select * from payment where ega_refference='".$data['ega_reference']."'")->result();
            if(!$checkif_exit)
            {
                $payment_data_array=array(
                    'chanell_transaction_id'=>$data['channel_trans_id'],
                    'student_id'=>$data['user_id'],
                    'invoice_number'=>$invoice_number,
                    'ega_refference'=>$data['ega_reference'],
                    'control_number'=>$data['CtrNum'],
                    'paid_amount'=>$data['amount'],
                    'transaction_date'=>$data['created_date'],
                    'payment_channell'=>$data['channel'],
                    'payer_mobile'=>$data['mobile'],
                    'payer_name'=>$data['payer_name'],
                    'payer_email'=>$data['payer_email'],
                    'receipt_number'=>$data['receipt_number'],
                    'channel_name'=>$data['channel_name']
                );
                $update_invoice=array(
                    'status'=>2
                );
                $this->db->insert('ega_logs',$log_data_array);
                $this->db->insert('payment',$payment_data_array);
                $this->db->update('invoices', $update_invoice, array('id'=>$invoice_number));
            }else{
                echo "Payment exist";
            }

        }



    }



    function ReceiveControlNumber()
    {
        $data_json = file_get_contents("php://input");
        $data = json_decode($data_json, TRUE);
        $reference=$data['reference'];
        $control_number=$data['CtrNum'];
        $invoice_number = substr($reference, 3);
        $log_data_array=array(
            'responce'=>print_r($data,true),
            'status'=>$data['status'],
            'description'=>$data['description'],
            'type'=>'control_number'
        );
        $this->db->insert('ega_logs',$log_data_array);
        if($data['status']==1)
        {
            $update_invoice=array(
                'control_number'=>$control_number,
                'status'=>1,
            );
            $this->db->update('invoices', $update_invoice, array('id'=>$invoice_number));



            $file=$control_number.'.png';
            //$url=base_url()."Qrcode/qrcode.php";
            $url='http://41.59.225.216/qr/';
            $exists = remoteFileExists($url.'/Qrcode/images/'.$file);
            $invoice=$this->db->query("select * from invoices where id=".$invoice_number);

            //if(!file(base_url().'Qrcode/images/'.$file))
            if(!$exists)
            {
                if($invoice)
                {
                    $student_info=$this->db->query("select * from application where id=".$invoice->student_id)->row();
                    $date=date("Y-m-d",strtotime($invoice->timestamp));
                    $this->collegeinfo = get_collage_info();
                    $message=array(
                        "opType"=>2,
                        "shortCode"=>"001001",
                        "billReference"=>$control_number,
                        "amount"=>$invoice->amount,
                        "billCcy"=>'TZS',
                        "billExprDt"=>date("Y-m-d",strtotime(($date .' + 360days'))),
                        "billPayOpt"=>3,
                        "billRsv01"=>$this->collegeinfo->Name."|".$invoice->student_name
                    );
                    $message=json_encode($message);

                    $postdata = array(
                        "title" => $message,
                        "control" =>$invoice->control_number ,

                    );

                    sendDataOverPost($url,$postdata);

                }


            }

            //send nortification
            execInBackground('response send_control_number ' . $control_number.' ');
        }

    }

    function ReceiveReconciliation()
    {
        $data_json = file_get_contents("php://input");
        $data = json_decode($data_json, TRUE);
        $log_data_array=array(
            'responce'=>print_r($data,true),
            'type'=>'reconciliation'
        );
        $this->db->insert('ega_logs',$log_data_array);

    }

}

