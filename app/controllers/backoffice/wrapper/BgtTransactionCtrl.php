<?php
class BgtTransactionCtrl extends AdminCtrl{

    protected $ctrl = __CLASS__;
    protected $page = "bgt_transaction";
	protected $view = "bgt-transaction";
	protected $elq = "BgtTransactionElq";
	
    function listAction($paging = ""){
        if(get('submit') == "print"){
            redirect(route("BgtTransactionCtrl:print")."?".http_build_query($_GET));
        }

        if (isset($_GET['from_date'])){
            $link_fd = str_replace("/", "-", $_GET['from_date']);
            $from_date = isset($link_fd) ? date("Y-m-d", strtotime($link_fd)) : '-';
        }if (isset($_GET['until_date'])){
            $link_ud = str_replace("/", "-", $_GET['until_date']);
            $until_date = isset($link_ud) ? date("Y-m-d", strtotime($link_ud)) : '-';
        }

        if (!empty($from_date) && !empty($until_date)){
            $data = $this->elq->whereBetween('bt_trx_date', array($from_date, $until_date))->orderBy('bt_trx_date', 'ASC');
        }else{
            $data = $this->elq->orderBy('bt_trx_date', 'ASC');
        }

        $_SESSION['list_page'] = current_url();
        if($keyword = get('keyword')){
            $data = $data->like("bt_note", $keyword);
        }
        $data = $data->paginate(300);
        if(!$data->count() && $paging > 1) redirect_route(__CLASS__.':list', ['paging' => $paging - 1]);

        foreach ($data->data() as $key => $row1) {
            $field['category_budget'] = "No Category";
            $field['type_budget'] = "No Type";
            if($row1->MrBgtCategoryElq){
                $field['category_budget'] = $row1->MrBgtCategoryElq['mbc_category'];
            }
            if($row1->MrBgtTypeElq){
                $field['type_budget'] = $row1->MrBgtTypeElq['bgt_type'];
            }

            $data->setData($key, $field);
        }

        // Count All
        $all_data = BgtTransactionElq::get();
        $total_debet = '';
        $total_credit = '';
        foreach ($all_data as $value_all) {
            $total_debet += $value_all['bt_debit'];
            $total_credit += $value_all['bt_credit'];
        }

        return view('backoffice.'.$this->view.'-list',array_merge($this->view_share(),[
            'data' => $data,
            'total_debet' => $total_debet,
            'total_credit' => $total_credit,
            'total_saldo' => $total_debet - $total_credit,
        ]));
    }

    function addAction(){
        return view('backoffice.'.$this->view.'-add', array_merge($this->view_share(),[
            'validation' => $this->elq->jqv(),
            'type_budget' => getSelect(MrBgtTypeElq::active()->get(), 'bgt_id', 'bgt_type'),
            'category_budget' => getSelect(MrBgtCategoryElq::active()->get(), 'mbc_id', 'mbc_category'),
        ]));
    }

    function editAction($id){
        $elq = $this->elq->find($id);

        return view('backoffice.'.$this->view.'-edit', array_merge($this->view_share(),[
            'id' => $id,
            'row' => $elq,
            'status' => $elq->status(),
            'validation' => $this->elq->jqv(),
            'type_budget' => getSelect(MrBgtTypeElq::active()->get(), 'bgt_id', 'bgt_type'),
            'category_budget' => getSelect(MrBgtCategoryElq::active()->get(), 'mbc_id', 'mbc_category'),
        ]));
    }

    function storeAction(){
        $_POST['bt_input_by'] = AuthCtrl::user()->bu_init;
        $_POST['bt_input_date'] = DATETIME;

        $trx_date = str_replace("/", "-", $_POST['bt_trx_date']);
        $_POST['bt_trx_date'] = date("Y-m-d", strtotime($trx_date));

        $amt_debit = $_POST['bt_debit'];
        $amt_credit = $_POST['bt_credit'];
        $data = $_POST;

        $this->elq->fill($_POST);
        if($this->elq->isValid()){
            
            if(!empty($amt_debit)){
                $last_data = BgtTransactionElq::orderBy('bt_id', 'desc')->first();
                $elq = new BgtTransactionElq;
                
                $bt_saldo = $last_data['bt_saldo'] + $amt_debit;
                $elq->fill(array_merge($data,[
                    'bt_saldo' => $bt_saldo,
                    'bt_credit' => '',
                ]))->status(post('status'));
                $elq->save();
            }
            
            if(!empty($amt_credit)){
                $last_data = BgtTransactionElq::orderBy('bt_id', 'desc')->first();
                $elq = new BgtTransactionElq;
                
                $bt_saldo = $last_data['bt_saldo'] - $amt_credit;
                $elq->fill(array_merge($data,[
                    'bt_saldo' => $bt_saldo,
                    'bt_debit' => '',
                ]))->status(post('status'));
                $elq->save();
            }

            return response([
                'info' => UI::alert(lang_var('gen.add_success', ['type' => $this->pages['type']]), 'success'),
                'redirect' => session('list_page'),
                'delay' => 2000,
            ]);
        }
        return response(['pAlert' => [
            'title' => lang('gen.validation_error'),
            'msg' => $this->elq->ulError(),
        ]]);
    }

    function updateAction($id){
        $this->elq = $this->elq->find($id);

        $_POST['bt_update_by'] = AuthCtrl::user()->bu_init;
        $_POST['bt_update_date'] = DATETIME;

        $trx_date = str_replace("/", "-", $_POST['bt_trx_date']);
        $_POST['bt_trx_date'] = date("Y-m-d", strtotime($trx_date));
        $diff_debit = $_POST['bt_debit'] - $this->elq->bt_debit;
        $diff_credit = $_POST['bt_credit'] - $this->elq->bt_credit;

        $this->elq->fill($_POST);
        if($this->elq->isValid()){
            $this->elq->status(post('status'));
            
            $get_trx = BgtTransactionElq::where("bt_input_date", ">=", $this->elq->bt_input_date)->get();
            foreach ($get_trx as $trx) {
                $trx->bt_saldo = $trx->bt_saldo + ($diff_debit);
                $trx->bt_saldo = $trx->bt_saldo - ($diff_credit);
                $trx->save();
            }

            $this->elq->save();

            return response([
                'info' => UI::alert(lang_var('gen.update_success', ['type' => $this->pages['type']]), 'success'),
                'redirect' => session('list_page'),
                'delay' => 2000,
            ]);
        }
        return response(['pAlert' => [
            'title' => lang('gen.validation_error'),
            'msg' => $this->elq->ulError(),
        ]]);
    }

    function store_categoryAction(){
        $category = new MrBgtCategoryElq;

        $category->fill($_POST);
        if($category->isValid()){
            $category->save();
            return response('$(".modal").modal("hide");
            $("[name=\'bt_category\']").append("<option value=\''.$category['mbc_id'].'\' selected>'.$category['mbc_category'].'</option>").trigger("chosen:updated");');
        }
        return response(['pAlert' => [
            'title' => lang('gen.validation_error'),
            'msg' => $elq->ulError(),
        ]]);
    }

    function store_fileAction(){
        $row = BgtTransactionElq::find($_POST['id']);

        return view('backoffice.modal.bgt-file-upload', array_merge($this->view_share(),[
            'row' => $row,
        ])); 
    }

    function list_fileAction(){
        $row = BgtTransactionElq::find($_POST['id']);

        return view('backoffice.modal.list-file-table', array_merge($this->view_share(),[
            'row' => $row,
            'trx_date' => $row['bt_trx_date'],
            'id_transaction' => $row['bt_id'],
        ])); 
    }

    function update_fileAction(){
        $update_row = BgtTransactionElq::find($_POST['bt_id']);

        if(!empty($update_row['bt_file'])){
            $update_row->fill([
                'bt_file' => $update_row['bt_file'].','.$_POST['bt_file'],
            ]);
        }else{
            $update_row->fill([
                'bt_file' => $_POST['bt_file'],
            ]);
        }

        if($update_row->isValid()){
            $update_row->save();
            return response('$(".modal").modal("hide")');
        }
    }


    function printAction(){   
        if (isset($_GET['from_date'])){
            $link_fd = str_replace("/", "-", $_GET['from_date']);
            $from_date = isset($link_fd) ? date("Y-m-d", strtotime($link_fd)) : '-';
        }if (isset($_GET['until_date'])){
            $link_ud = str_replace("/", "-", $_GET['until_date']);
            $until_date = isset($link_ud) ? date("Y-m-d", strtotime($link_ud)) : '-';
        }

        if (!empty($from_date) && !empty($until_date)){
            $data = $this->elq->whereBetween('bt_trx_date', array($from_date, $until_date))->orderBy('bt_trx_date', 'ASC')->get();
        }else{
            $data = $this->elq->orderBy('bt_trx_date', 'ASC')->get();
        }
        $total_row = $data->count();

        // Get Data All
        $data_all = $this->elq->orderBy('bt_trx_date', 'ASC')->get();
        $total_all_debet = '';
        $total_all_credit = '';
        foreach ($data_all as $item_all) {
            $total_all_debet += $item_all['bt_debit'];
            $total_all_credit += $item_all['bt_credit'];
        }
        $total_all_saldo = $total_all_debet - $total_all_credit;
        
        // Create new PHPExcel object
        $objPHPExcel = new PHPExcel();

        // Set document properties
        $objPHPExcel->getProperties()->setCreator("SMERP APP")
                                     ->setLastModifiedBy("SMERP APP")
                                     ->setTitle("Office 2007 XLSX Document")
                                     ->setSubject("Office 2007 XLSX Document")
                                     ->setDescription("Office 2007 XLSX, generated by Lingkar 9 Team.")
                                     ->setKeywords("Office 2007 openxml php")
                                     ->setCategory("Petty Cash Report");
                                     
        // SET STATIC STYLE
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1', 'Petty Cash');
        $objPHPExcel->getActiveSheet()->getStyle('D1')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle("D1")->getFont()->setSize(16);

        /* START SET EXCEL HEADER NAVIGATION */
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A3', 'Date');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B3', 'Initial');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C3', 'Description');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D3', 'Code');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E3', 'Debet');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F3', 'Credit');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G3', 'Saldo');

        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getStyle('B')->getAlignment()->setWrapText(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);

        /* Start Styling */
        $styleArray = array(
            'borders' => array(
             'outline' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array('argb' => '000000'),
                 ),
            ),
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => '75923c')
            ),
            'font'  => array(
                'bold'  => true,
                'color' => array('rgb' => 'FFFFFF'),
                'size'  => 12,
                'name'  => 'Calibri'
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            )   
        ); 
        $objPHPExcel->getActiveSheet()->getStyle('A3:G3')->applyFromArray($styleArray);

        // Data First Saldo
        $styleFirstSaldo = array(
               'borders' => array(
                     'outline' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                            'color' => array('argb' => '000000'),
                     ),
               ),
               'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => 'd7e4bc')
                ),
                'font'  => array(
                    'bold'  => true,
                    'color' => array('rgb' => '000000'),
                    'size'  => 12,
                    'name'  => 'Calibri'
                )
               
        );
        $data_first_saldo = $this->elq->where('bt_trx_date', '<', $from_date)->orderBy('bt_trx_date', 'DESC')->orderBy('bt_id', 'DESC')->first();
        $objPHPExcel->getActiveSheet()->setCellValue('G4', $data_first_saldo['bt_saldo']);
        $objPHPExcel->getActiveSheet()->getStyle('A3:G4')->applyFromArray($styleFirstSaldo)->getNumberFormat()->setFormatCode('[black][>=3000]Rp #,##0;[Red][<0]Rp #,##0;Rp #,##0');

        $i = 5;
        $total_debet = '';
        $total_credit = '';
        foreach($data as $item) {
            $total_debet += $item['bt_debit'];
            $total_credit += $item['bt_credit'];

            $newStyleArray = array(
                    'borders' => array(
                        'allborders' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                            'color' => array('argb' => '000000'),
                        ),
                    ));
            $objPHPExcel->getActiveSheet()->setCellValue('A' . $i, dates('d M Y', $item['bt_trx_date'], ''));
            $objPHPExcel->getActiveSheet()->setCellValue('B' . $i, $item['bt_input_by']);
            $objPHPExcel->getActiveSheet()->setCellValue('C' . $i, $item['bt_note']);
            $objPHPExcel->getActiveSheet()->setCellValue('D' . $i, '-');
            $objPHPExcel->getActiveSheet()->setCellValue('E' . $i, $item['bt_debit']);
            $objPHPExcel->getActiveSheet()->setCellValue('F' . $i, $item['bt_credit']);
            $objPHPExcel->getActiveSheet()->setCellValue('G' . $i, $item['bt_saldo']);

            $objPHPExcel->getActiveSheet()->getStyle('A'.$i.':D'.$i)->applyFromArray($newStyleArray);

            $objPHPExcel->getActiveSheet()->getStyle('E'.$i)->applyFromArray($newStyleArray)->getNumberFormat()->setFormatCode('[black][>=3000]Rp #,##0;[Red][<0]Rp #,##0;Rp #,##0');
            $objPHPExcel->getActiveSheet()->getStyle('F'.$i)->applyFromArray($newStyleArray)->getNumberFormat()->setFormatCode('[black][>=3000]Rp #,##0;[Red][<0]Rp #,##0;Rp #,##0');
            $objPHPExcel->getActiveSheet()->getStyle('G'.$i)->applyFromArray($newStyleArray)->getNumberFormat()->setFormatCode('[black][>=3000]Rp #,##0;[Red][<0]Rp #,##0;Rp #,##0');
            $i++;
        }

        $lastStyleArray = array(
                    'borders' => array(
                     'outline' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                            'color' => array('argb' => '000000'),
                     ),
                    ),
                    'fill' => array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => 'd7e4bc')
                    ),
                    'font'  => array(
                        'bold'  => true,
                        'color' => array('rgb' => '000000'),
                        'size'  => 12,
                        'name'  => 'Calibri'
                    ));

        // Total Periode
        $total_saldo = $total_debet - $total_credit;
        $row_all_total = $total_row + 5;
        $objPHPExcel->getActiveSheet()->setCellValue('D' . $row_all_total, "Total");
        $objPHPExcel->getActiveSheet()->setCellValue('E' . $row_all_total, $total_debet);
        $objPHPExcel->getActiveSheet()->setCellValue('F' . $row_all_total, $total_credit);
        $objPHPExcel->getActiveSheet()->setCellValue('G' . $row_all_total, $total_saldo);
        $objPHPExcel->getActiveSheet()->getStyle('D'.$row_all_total)->applyFromArray($lastStyleArray);
        $objPHPExcel->getActiveSheet()->getStyle('E'.$row_all_total)->applyFromArray($lastStyleArray)->getNumberFormat()->setFormatCode('[black][>=3000]Rp #,##0;[Red][<0]Rp #,##0;Rp #,##0');
        $objPHPExcel->getActiveSheet()->getStyle('F'.$row_all_total)->applyFromArray($lastStyleArray)->getNumberFormat()->setFormatCode('[black][>=3000]Rp #,##0;[Red][<0]Rp #,##0;Rp #,##0');
        $objPHPExcel->getActiveSheet()->getStyle('G'.$row_all_total)->applyFromArray($lastStyleArray)->getNumberFormat()->setFormatCode('[black][>=3000]Rp #,##0;[Red][<0]Rp #,##0;Rp #,##0');

        // Total Saldo 
        $row_total = $total_row + 5 + 1;
        $objPHPExcel->getActiveSheet()->setCellValue('D' . $row_total, "Saldo");
        $objPHPExcel->getActiveSheet()->setCellValue('E' . $row_total, $total_all_debet);
        $objPHPExcel->getActiveSheet()->setCellValue('F' . $row_total, $total_all_credit);
        $objPHPExcel->getActiveSheet()->setCellValue('G' . $row_total, $total_all_saldo);
        $objPHPExcel->getActiveSheet()->getStyle('D'.$row_total)->applyFromArray($lastStyleArray);
        $objPHPExcel->getActiveSheet()->getStyle('E'.$row_total)->applyFromArray($lastStyleArray)->getNumberFormat()->setFormatCode('[black][>=3000]Rp #,##0;[Red][<0]Rp #,##0;Rp #,##0');
        $objPHPExcel->getActiveSheet()->getStyle('F'.$row_total)->applyFromArray($lastStyleArray)->getNumberFormat()->setFormatCode('[black][>=3000]Rp #,##0;[Red][<0]Rp #,##0;Rp #,##0');
        $objPHPExcel->getActiveSheet()->getStyle('G'.$row_total)->applyFromArray($lastStyleArray)->getNumberFormat()->setFormatCode('[black][>=3000]Rp #,##0;[Red][<0]Rp #,##0;Rp #,##0');

        // Rename worksheet
        $workSheet_name = "Petty Cash Report";
        $objPHPExcel->getActiveSheet()->setTitle($workSheet_name);

        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);
        $namafile = "lingkar9-titian-media-pettycash-report.xls";

        // Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/xls');
        header("Content-Disposition: attachment;filename=".$namafile." ");
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        die;
    }

    function deleteAction($slug){
        

        $kode = explode('_', $slug);
        $slug1 = $kode[0];
        $slug2 = $kode[1];

        $data = BgtTransactionElq::find($slug1);
        $bt_file = explode(",", $data['bt_file']);
        $new_bt_file = array_diff($bt_file, [$slug2]);

        $data->update(['bt_file' => implode(",", $new_bt_file)]);
        
        return response('$.ajax({
                url : "'.route('BgtTransactionCtrl:list_file').'",
                type : "post",
                data : "id='.$slug1.'",
                success : function(a){
                    $("#LFModal .modal-body").html(a);
                }
            });');
    }
}