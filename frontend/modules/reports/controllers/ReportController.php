<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 06.11.2018
 * Time: 12:17
 */
namespace app\modules\reports\controllers;
use app\components\BaseController;
use app\models\Clients;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use PHPExcel_IOFactory;
use PHPExcel_Cell;
use PHPExcel_Style_Border;
class ReportController extends BaseController
{

    public function actionClientraschet()
    {

    }
    private function cell($val)
    {
        return "<td>".$val."</td>";
    }

    public function beforeAction($action)
    {



        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    public function actionWeekcountreport()
    {
        $dateMain = date('Y-m-d');
        $table = '';
        //$this->enableCsrfValidation = false;
        $fale = false;


        if(isset(Yii::$app->request->post()['date']))
        {


            $dateMain = Yii::$app->request->post()['date'];
            //$date = '2018-11-18';
            $select = 'select distinct(prDate) from v_report_kolbydate ';
            $selectNames = 'select distinct(name), pId, idType from v_report_kolbydate ';
            $selectQuery = 'select pId, name,prDate, round(sum(cnt), 2) as cnt, prixRasx from v_report_kolbydate ';
            $where  = '';
            $whereNames = 'name is not null ';

            if(date('Y-m-d') == $dateMain)
            {
                $where .= ' prDate >= date_add(now(), interval -(WEEKDAY(now())) day)';


            }
            else
            {
                $where .= " prDate >= date_add('$dateMain', interval -(WEEKDAY(date('".$dateMain."'))) day) and prDate <= date_add(date_add('$dateMain', interval -(WEEKDAY(date('$dateMain'))) day), interval 6 day)";
            }


            $select = $select.' where '.$where;
            $selectNames = $selectNames.' where '.$whereNames.' and '.$where;
            $selectQuery = $selectQuery.' where '.$whereNames.' and '.$where.' group by pId,prDate, prixRasx order by prDate;';

            $query = Yii::$app->db->createCommand($select)->queryAll();
            $queryNames = Yii::$app->db->createCommand($selectNames)->queryAll();
            $queryMain = Yii::$app->db->createCommand($selectQuery)->queryAll();

            $table = '<td></td>';
            $i = 0;
            $dateArray = array();
            $itogo = array();
            $tableHeaders = '<th></th>';
            $tableTextHeader = '<td></td>';
            foreach ($query as $odate)
            {
                $onedate = $odate['prDate'];

                //if(!isset($dateArray[$onedate]))
                //    $dateArray[$onedate] = array();
                $dateArray[$onedate] = "<td>0</td><td>0</td>";
                $tableHeaders .= '<th colspan="2">'.$onedate.'</th>';
                $tableTextHeader .= '<td>приход</td><td>продажа</td>';;

            }
            $tableTextHeader .= '<td></td>';
            $tableHeaders .= '<th>Итого</th>';
            $mainArray = array();
            $i = 0;

            foreach ($queryNames as $nm) {
                $name = $nm['name'];
                //if(!isset($mainArray[$name]))
                //    $mainArray[$name] = array();
                $mainArray[$name] = $dateArray;
                $itogo[$name] = 0;

            }


            $same = "";
            foreach ($queryMain as $qryOne)
            {
                if($mainArray[$qryOne['name']][$qryOne['prDate']]=='<td>0</td><td>0</td>') {
                    if ($qryOne['prixRasx'] == 1) {
                        $itogo[$qryOne['name']] += $qryOne['cnt'];

                        $mainArray[$qryOne['name']][$qryOne['prDate']] = '<td>'.$qryOne['cnt'].'</td><td>0</td>';//$this->cell($qryOne['cnt']).$this->cell('');

                    } else {
                        $itogo[$qryOne['name']] -= $qryOne['cnt'];

                        $mainArray[$qryOne['name']][$qryOne['prDate']] = '<td>0</td><td>'.$qryOne['cnt'].'</td>';//$this->cell('').$this->cell($qryOne['cnt']);

                    }
                }
                else
                {
                    $str = $mainArray[$qryOne['name']][$qryOne['prDate']];

                    $mainArray[$qryOne['name']][$qryOne['prDate']] = str_replace('<td>0</td>','<td>'.$qryOne['cnt'].'</td>',$str);
                    if ($qryOne['prixRasx'] == 1) {
                        $itogo[$qryOne['name']] += $qryOne['cnt'];
                    }else
                    {
                        $itogo[$qryOne['name']] -= $qryOne['cnt'];
                    }

                }


            }
            $table='';
            foreach ($mainArray as $key => $val)
            {
                $table .= '<tr>';
                $table .= '<td>'.$key.'</td>';
                foreach ($val as $date => $cnt)
                {
                    $table .= $cnt;


                }
                $table.= '<td>'.$itogo[$key].'</td>';

                $table .= '</tr>';
            }
            $table = '<table class="table table-bordered table-hover">'.'<thead><tr>'.$tableHeaders.'</tr></thead>'.'<tbody>'.$tableTextHeader.$table.'</tbody></table>';
            return $this->render('weeklyBydate', ['table' => $table, 'dateMain'=>$dateMain]);








        }
        return $this->render('weeklyBydate', ['table' => $table, 'dateMain'=>$dateMain]);

    }

    public function actionClientreport()
    {

        $id = 0;
        $dateFrom = date('Y-m-01');
        if(isset(Yii::$app->request->post()['clientId']))
        {
            $id = Yii::$app->request->post()['clientId'];
            $dateFrom = Yii::$app->request->post()['dateFrom'];

        }

        $query = $this->getClientreport($id, $dateFrom);

        $clients = Clients::find()->all();

        return $this->render('clientreport',['model'=> $query, 'clients'=>$clients, 'clientId'=>$id, 'curDate'=>$dateFrom]);





    }

    public function actionClientreportxls($id = 0)
    {
        //$id = 0;
        $dateFrom = date('Y-m-01');
        if(isset(Yii::$app->request->post()['clientId']))
        {
            $id = Yii::$app->request->post()['clientId'];
            $dateFrom = Yii::$app->request->post()['dateFrom'];

        }
        $query = $this->getClientreport($id, $dateFrom);

        $path = Yii::getAlias('@webroot/images/clientreport.xls');


        $inputFileType = 'Excel5';
        $objReader = PHPExcel_IOFactory::createReader($inputFileType);
        $objPHPExcel = $objReader->load($path);

        /*
        $styleArray = array(
            'borders' => array(
                'outline' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array('argb' => '000000'),
                ),
            ),
        );*/

        $client = Clients::find()->where(['clientId'=>$id])->one();


        $activeSheet = $objPHPExcel->setActiveSheetIndex(0);
        // Add some data
        $activeSheet->setCellValue('B2', $client->clientName);

        //if(count($query)>1)
        //    $objPHPExcel->getActiveSheet()->insertNewRowBefore(11, count($query)-1);
        $i = 0;
        $weeks = 'За недели [';
        $first = true;
        $sum = 0;
        $expId = 0;


        //if($i!=0)
        foreach($query as $mod)
        {
            $i++;
            $addition = " ";
            if($first) {
                $weeks = $weeks . date('W', strtotime($mod['dateSum'])) . '-';
                $first = false;
            }


            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.($i+10), $mod['dateSum']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.($i+10), $mod['expenseId']);




            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.($i+10), date('W', strtotime($mod['dateSum'])));
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.($i+10), 'НВЛ');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.($i+10), $mod['clientName']);
            if($mod['typeS']=='продажа')
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.($i+10), 'вывоз т.');
            else
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.($i+10), 'платят нам');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.($i+10), $mod['description']);
            if($mod['typeS']=='продажа')
            {
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.($i+10), -$mod['kol']);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.($i+10), $mod['cena']);

                if($mod['typeS']=='продажа'&& $mod['expenseId']!=$expId)
                {
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O'.($i+10), -$mod['summ']);
                    $sum -= $mod['summ'];

                }


            }else{
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.($i+10), $mod['kol']);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.($i+10), $mod['cena']);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O'.($i+10), $mod['summ']);
                $sum += $mod['summ'];
            }
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('U'.($i+10), $mod['address']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('V'.($i+10), $mod['driver']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('W'.($i+10), $mod['prim']);

            $expId = $mod['expenseId'];

            //->setCellValue('F'.($i+3), '')
            //$objPHPExcel->setActiveSheetIndex(0)->getStyle('F'.($i+10))->applyFromArray($styleArray);

            //$objPHPExcel->getActiveSheet()->duplicateStyle( $objPHPExcel->getActiveSheet()->getStyle('B8'), 'B'.($i+7).':G'.($i+7) );
            //$objPHPExcel->setActiveSheetIndex(0)->getStyle('F'.($i+3))->applyFromArray($styleArray);

        }
        //$activeSheet->setCellValue('B4', $i);
        $weeks =$weeks . ' сегодня]';
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('T2', $weeks);
        //$val = $objPHPExcel->setActiveSheetIndex(0)->getCell('O3')->getValue();

        if($sum<0)
        {
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('X2', "Должны нам:".$sum);
        }








        // Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle('Отчет по клиенту');


        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);



        // Redirect output to a client’s web browser (Excel2007)
        //header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-type:application/vnd.ms-excel");
        header('Content-Disposition: attachment;filename="отчет по клиенту.xls"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        ob_end_clean();
        $objWriter->save('php://output');
        spl_autoload_register(array('YiiBase','autoload'));
        exit;

    }

    private function getClientreport($id, $dateFrom)
    {


        $select = "select * from v_clientreport ".($id!=0?" where clientId = $id":""). ($id!=0?" and dateSum >= '$dateFrom 00:00:00' ":" where dateSum >= '$dateFrom 00:00:00' ") ." order by dateSum asc";

        $query = Yii::$app->db->createCommand($select)->queryAll();
        return $query;
    }
    public function actionGetdate()
    {
        $dt = strtotime('-1 month');
        var_dump(date('Y-m-1', $dt));
    }




}