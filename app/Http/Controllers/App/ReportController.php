<?php

namespace App\Http\Controllers\voucher_tracking_system;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB, Excel;

class ReportController extends Controller
{
  public function __construct(){$this->middleware('auth');}

  function payment(Request $request){
    $search = $request->input('search');
    $to = null;
    $payment=[];
    $total_payment=null;

    if( $request->input('export_xls') != null ){
      $this->xls_payment($search);
    }

    $payment = $this->filter_payment($search);

    if( $payment != [] ){
      $total_payment = clone $payment;
      $total_payment = $total_payment->selectRaw('COUNT(p.id) AS count,SUM(amount) AS total')->first();
      $payment = $payment->paginate(20);
    }

    return view('voucher_tracking_system.report.report_payment',compact('total_payment','payment','search'));
  }

  function customer(Request $request){
    $search = $request->input('search');
    $to = null;

    if( $request->input('export_xls') != null ){
      $this->xls_customer($search);
    }

    $payment=$this->filter_customer($search);
    if($payment != []){
      $payment = $payment->paginate(50);
    }
    $to = db_date($search['to']);
    return view('voucher_tracking_system.report.report_customer',compact('customer','payment','search','to'));
  }

  function filter_payment($search){
    $payment = [];

    if($search!=null){
      $payment = DB::table('payment as p')
              ->selectRaw('
                p.*,
                c.name AS customer_name
              ');
      $payment ->leftJoin('customer as c','c.ic','=','p.ic');
      foreach($search as $k => $v){
        if($v!=''){
          if($k=='from'){
            $payment->where('invoice_date','>=', db_date($v) );
          }elseif($k=='to'){
            $payment->where('invoice_date','<=', db_date($v) );
          }elseif($k=='ic'){
            $payment->whereIn('p.ic',$v);
          }else{}
        }
      }
      $payment->where('cancel',0);
      $payment->orderBy('invoice_date','ASC');
    }
    return $payment;
  }

  function filter_customer($search){
    $payment=[];
    if($search!=null){
      $payment = DB::table('payment as p')
              ->selectRaw('
                p.*,
                c.name AS customer_name
              ');
      $payment ->leftJoin('customer as c','c.ic','=','p.ic');
      foreach($search as $k => $v){
        if($v!=''){
          if($k=='to'){
            $payment->where('invoice_date','<=', db_date($v) );
          }elseif($k=='ic'){
            $payment->whereIn('p.ic',$v);
          }else{}
        }
      }
      $payment->where('cancel',0);
      $payment->orderBy('c.id','DESC');
      $payment->orderBy('p.invoice_date','ASC');
    }
    return $payment;
  }

  function xls_payment($search){
    $data = $this->filter_payment($search);
    $data = $data->get();

    $excel_data = [];
    $excel_data[] = ['Date From',$search['from'],'Date To',$search['to']];
    $excel_data[] = [];
     $excel_data[] = [
      '#',
      'Invoice Date',
      'Invoice No',
      'Customer IC',
      'Customer Name',
      'Invoice Amount',
      'Created By',
      'Created At'
    ];

    foreach($data as $k=>$d){
      $arr = [
        $k+1,
        $d->invoice_date,
        $d->invoice_no,
        $d->ic,
        $d->customer_name,
        floatval($d->amount),
        user_name($d->created_by),
        $d->created_at,
      ];
      $excel_data[] = $arr;
    }
    //dd($excel_data);
    $file_name = "Report Invoice Payment";
    Excel::create($file_name, function($excel) use ($excel_data) {
      $excel->sheet('Sheetname', function($sheet) use ($excel_data) {
        $sheet->fromArray($excel_data, null, 'A1', false, false);
      });
    })->download('xlsx');
  }

  function xls_customer($search){
    $data = $this->filter_customer($search);
    $data = $data->get();
    $to = db_date($search['to']);

    $excel_data = [];
    $excel_data[] = ['Date To',$to];
    $excel_data[] = [];
     $excel_data[] = [
      '#',
      'Customer IC / Passport',
      'Customer Name',
      'Package Amount (RM)',
      'Payment Amount (RM)',
      'Remaining Amount (RM)'
    ];

    $setIc='';
    $count_customer=1;
    foreach($data as $k=>$d){
      if($setIc=='' || $setIc != $d->ic){
        $arr = [
          $count_customer,
          $d->ic,
          $d->customer_name,
          floatval(sum_customer_package($d->ic, $to)),
          floatval(sum_customer_payment($d->ic, $to)),
          floatval(customer_remaining($d->ic, $to)),
        ];
        $excel_data[] = $arr;
        $count_customer++;
      }

      $arr = [
        '',
        '',
        $d->invoice_date,
        $d->invoice_no,
        floatval($d->amount),
        '',
      ];

      $setIc = $d->ic;
      $excel_data[] = $arr;
    }

    $file_name = "Report Customer Until ".$to.'_'.date('YmdHis');
    Excel::create($file_name, function($excel) use ($excel_data) {
      $excel->sheet('Sheetname', function($sheet) use ($excel_data) {
        $sheet->fromArray($excel_data, null, 'A1', false, false);
      });
    })->download('xlsx');
  }



}
