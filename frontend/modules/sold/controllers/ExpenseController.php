<?php

namespace app\modules\sold\controllers;
use app\components\BaseController;
use app\models\From;
use app\modules\accounting\models\Account;
use moonland\phpexcel;
use app\modules\sold\models\Expense;
use app\modules\calc\models\Product;
use app\modules\calc\models\Stuff;
use app\models\Clients;
use app\modules\sold\models\Orders;
use Yii;
use yii\helpers\ArrayHelper;


use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use PHPExcel_IOFactory;
use PHPExcel_Cell;
use PHPExcel_Style_Border;
/**
 * ProductController implements the CRUD actions for Product model.
 */
class ExpenseController extends BaseController
{
    public $layout = '@app/views/layouts/accounting';
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function beforeAction($action)
    {



        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }
    
    public function actionIndex()
    {
        
        return $this->render('index');
    }

    public function actionInprocesslist()
    {

        return $this->render('inprocesslist');
    }

    /**
     * Lists all Product models.
     * @return mixed

    */
    public function actionExistsold($id)
    {
        $session = \Yii::$app->session;

        // проверяем наличие открытой сессии
        if ($session->isActive)
        {
            $session->open();
        }
        $session['expenseId'] = $id;
        $this->redirect('/sold/expense/step1');
    }

    public function actionSolded($id)
    {
        //$id = Yii::$app->request->post()['id'];
        $modelEx = new Expense();
        $modelEx = Expense::find()->where(['expenseId'=>$id])->one();
        $modelEx->status = 0;
        $modelEx->save();
        $this->redirect('/sold/expense/inprocesslist');
    }




    public function actionStep1()
    {
        
        $session = \Yii::$app->session;
 
        // проверяем наличие открытой сессии
        if ($session->isActive)
        {    
            $session->open();
        }

        $form_model =  new Expense();
        $modelProduct = new Product();
        $modelStuff = new Stuff();
        $modelFrom = new From();
        //if ($form_model->load(Yii::$app->request->post(), '')) {
        $date = new \DateTime();
        if(!isset($session['expenseId']))
        {
            
            $form_model->expenseDate = $date->format('Y-m-d H:i:s');
            $form_model->debt = 0;
            $form_model->comment = '';
            $form_model->clientId = null;//Yii::$app->request->post()['clientId'];
            $form_model->fakt = 0;
            $form_model->expType = 0;
            $form_model->transfer = 0;
            $form_model->inCash = 0;
            $form_model->terminal = 0;
            $form_model->expSum = 0;
            $form_model->status = 1;
            $form_model->userId = \Yii::$app->user->id;
            $form_model->paidType = 0;
            $form_model->charge = 0;

            $form_model->save(false);
            
            $session->set("expenseId", $form_model->expenseId);
            $session->set("step", 1);
        //var_dump($form_model);
        } else {
            $expId = $session->get("expenseId");
            $form_model = Expense::find()->where(['expenseId'=>$expId])->orderBy(['expenseId'=>SORT_DESC])->one();
            $session->set("step", 1);

            //return print_r($form_model);
        }   
        
        $modelProduct = Product::find()->all();
        $modelStuff = Stuff::find()->all();
        $modelFrom = From::find()->all();
        
        
        return $this->render('create', ['model'=> $form_model, 
            'mProduct'=>$modelProduct, 
            'mStuff'=>$modelStuff,
            'mFrom'=>$modelFrom
                ]);

        //}
        
        //return $this->render('create', ['model'=> $form_model]);
    }
    public function actionStep2()
    {
        $session = \Yii::$app->session;

        // проверяем наличие открытой сессии
        if($session->isActive && isset($session['expenseId']))
        {
            if($session['step']==1) {

                $expId = $session->get("expenseId");

                $form_model = Expense::find()->where(['expenseId' => $expId])->orderBy(['expenseId' => SORT_DESC])->one();
                if ($form_model->load(Yii::$app->request->post(), '')) {
                    $form_model->paidType = Yii::$app->request->post()['paidType'];
                    $form_model->clientId = (Yii::$app->request->post()['clientId']!=0?Yii::$app->request->post()['clientId']:null);
                    $form_model->fromId = 1;
                    $expSum = 0;
                    $orders = $form_model->orders;

                    foreach ($orders as $order) {
                        $expSum += isset($order->orderSumm)?$order->orderSumm:0;

                    }

                    $form_model->comment = Yii::$app->request->post()['comment'];
                    switch ($form_model->paidType) {
                        case 0:
                            $form_model->inCash = $expSum;
                            break;
                        case 1:
                            $form_model->terminal= $expSum;
                            break;
                        case 2:
                            $form_model->transfer = $expSum;
                            break;
                    }


                    $form_model->expSum = $expSum;
                    //$session['step'] = 2;
                    if ($form_model->validate())
                    {
                        $session['step'] = 2;
                        $form_model->save();
                        $this->redirect('/sold/delivery/step2');
                    }
                    else
                        return var_dump($form_model->errors);
                        //redirect('/sold/expense/step1');

                }else
                {
                    $this->redirect('/sold/expense');
                }

            }
            else
            {

                $this->redirect('/sold/expense');
            }


        }


    }
    public function actionRefreshdlist()
    {

        $models = Expense::find()->where(['expType'=>0, 'status'=>1])->orderBy(['expenseId'=>SORT_DESC])->limit(1000)->all();

        //return var_dump($models);
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        //$this->redirect("site/login");
        $arr = ArrayHelper::toArray($models,[
            Expense::class =>[
                'expenseId',
                'expenseDate',
                'from'=>function($data){
                    return isset($data->from->kod)?$data->from->kod:"";
                },
                'comment',
                'clientId',
                'paidType',
                'paidTypeName'=>function($data){
                    $str = 'Наличные';
                    switch($data->paidType)
                    {
                        case 0:
                            $str = 'Наличные';
                            break;
                        case 1:
                            $str = 'Без нал';
                            break;
                        case 2:
                            $str = 'Перечисление';
                            break;
                    }
                    return $str;
                },
                'expSum',
                'clientName'=>function($data){
                    return (isset($data->client->clientName)?$data->client->clientName:'прямая продажа');
                },
                'deliveryType'=>function($data){
                    if (!isset($data->delivery->deliveryType)||$data->delivery->deliveryType == 0) {
                        return 'Самовывоз';
                    } else {
                        return 'Доставка';
                    }


                },
                'deliveryPrice'=>function($data){
                    return isset($data->delivery->price)?$data->delivery->price:"";
                },
                'delivery'=>function($data){
                    return (isset($data->delivery->address)?$data->delivery->address:"").' '.(isset($data->delivery->name)?$data->delivery->name:"").' '.(isset($data->delivery->description)?$data->delivery->description:"");
                },

                //'category'=>function($data){
                //    return $data->category->name;
                //},
            ],

        ]);
        return ['datas' =>$arr];
    }

    public function actionRefreshd()
    {

        $models = Expense::find()->andWhere(['expType'=>0])->andWhere(['or', ['status'=>1], ['status'=>3]])->orderBy(['expenseId'=>SORT_DESC])->limit(1000)->all();

        //return var_dump($models);
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        //$this->redirect("site/login");
        $arr = ArrayHelper::toArray($models,[
                    Expense::class =>[
                'expenseId',
                'expenseDate',
                'from'=>function($data){
                    if(isset($data->from))
                    return $data->from->kod;
                    else
                        return '';
                },
                'comment',
                'clientId',
                'paidType',

                'paidTypeName'=>function($data){
                    $str = 'Наличные';
                    switch($data->paidType)
                    {
                        case 0:
                            $str = 'Наличные';
                            break;
                        case 1:
                            $str = 'Без нал';
                            break;
                        case 2:
                            $str = 'Перечисление';
                            break;
                    }
                    return $str;
                },
                'expSum',
                'clientName'=>function($data){
                    return (isset($data->client->clientName)?$data->client->clientName:'прямая продажа');
                },
                'deliveryType'=>function($data){
                    if(isset($data->delivery)) {
                        if ($data->delivery->deliveryType == 0) {
                            return 'Самовывоз';
                        } else {
                            return 'Доставка';
                        }
                    }else return '';

                },
                'deliveryPrice'=>function($data){
                    if(isset($data->delivery)) {
                        return $data->delivery->price;
                    }else
                    {return '';}
                },
                'delivery'=>function($data){
                    if(isset($data->delivery)) {
                        return $data->delivery->address . ' ' . $data->delivery->name . ' ' . $data->delivery->description;
                    }else
                    {return '';}
                },
                'status',

                //'category'=>function($data){
                //    return $data->category->name;
                //},
            ],

        ]);
        return ['datas' =>$arr];
    }

    public function actionNakladnaya($id)
    {
        $modelClients = new Clients();
        $mFrom = new From();

        $model = Expense::find()->where(['expenseId'=>$id])->one();
        if(isset($model))
        {
            $modelDelivery = $model->delivery;
            $modelClients = Clients::find()->all();
            $mFrom = From::find()->all();
            $order = Orders::find()->where(["expenseId" => $model->expenseId])->one();
            $alert = isset($session['expenseId'])?'Продажа осуществлена':'';

            return $this->render('nakladnaya', ['model'=>$model, 'mDelivery'=>$modelDelivery, 'orders'=>$order,'mClients'=>$modelClients, 'mFrom'=>$mFrom, 'alert'=>'']);

        }

    }



    public function actionPrintn()
    {
        $from = isset(Yii::$app->request->post()['sender'])&&Yii::$app->request->post()['sender']!=''?Yii::$app->request->post()['sender']:Yii::$app->request->post()['fromId'];
        $klient = isset(Yii::$app->request->post()['client'])?Yii::$app->request->post()['client']:'';
        $date = isset(Yii::$app->request->post()['date'])?Yii::$app->request->post()['date']:'';
        $driver = isset(Yii::$app->request->post()['voditel'])?Yii::$app->request->post()['voditel']:'';
        $formatter = \Yii::$app->formatter;
        $date = $formatter->asDate($date, 'dd.MM.yyyy');

        $number = isset(Yii::$app->request->post()['number'])?Yii::$app->request->post()['number']:'';
        $director = isset(Yii::$app->request->post()['director'])?Yii::$app->request->post()['director']:'';
        $fromaddress = isset(Yii::$app->request->post()['pogruzka'])?Yii::$app->request->post()['pogruzka']:'';
        $address = isset(Yii::$app->request->post()['address'])?Yii::$app->request->post()['address']:'';

        $orders = Orders::find()->where(['expenseId'=>$number])->all();





        //$sheet = new \moonland\phpexcel\Excel();
        //$objPHPExcel = $sheet->getExcelClass();


        $path = Yii::getAlias('@webroot/images/nakladnaya_template.xls');


        $inputFileType = 'Excel5';
        $objReader = PHPExcel_IOFactory::createReader($inputFileType);
        $objPHPExcel = $objReader->load($path);

        $styleArray = array(
            'borders' => array(
                'outline' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array('argb' => '000000'),
                ),
            ),
        );


        $activeSheet = $objPHPExcel->setActiveSheetIndex(0);
        // Add some data
        $activeSheet->setCellValue('B4', $from);
        $activeSheet = $objPHPExcel->setActiveSheetIndex(0);
        $activeSheet->setCellValue('B5', $klient);
        $activeSheet->setCellValue('B6', $klient);

        $activeSheet->setCellValue('N3',$date); //дата
        $activeSheet->setCellValue('N2',$number);

        if(count($orders)>1)
            $objPHPExcel->getActiveSheet()->insertNewRowBefore(12, count($orders)-1);
        $i = 0;

       //if($i!=0)
        foreach($orders as $mod)
        {
            $i++;
            $addition = " ";
            if($mod->addition != 0){
                $prod = Product::findOne(["productId"=>$mod->addition]);
                $addition .= "c ".$prod->name."-".$mod->additionCnt;
            }

            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.($i+10), $mod->idType==0?$mod->product->name.$addition:$mod->stuff->name.$addition);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.($i+10), $mod->packCount);
            //->setCellValue('F'.($i+3), '')
            //$objPHPExcel->setActiveSheetIndex(0)->getStyle('F'.($i+10))->applyFromArray($styleArray);

            //$objPHPExcel->getActiveSheet()->duplicateStyle( $objPHPExcel->getActiveSheet()->getStyle('B8'), 'B'.($i+7).':G'.($i+7) );
            //$objPHPExcel->setActiveSheetIndex(0)->getStyle('F'.($i+3))->applyFromArray($styleArray);

        }
        //$activeSheet->setCellValue('B4', $i);
        // Miscellaneous glyphs, UTF-8
        $i--;
        $activeSheet->setCellValue('E'.(15+$i),'Ген. директор/'.$director.'/');
        $activeSheet->setCellValue('E'.(16+$i),'Ген. директор/'.$director.'/');
        $activeSheet->setCellValue('B'.(21+$i),'Ген. директор/'.$director.'/');
        $activeSheet->setCellValue('B'.(18+$i),$driver);
        $activeSheet->setCellValue('B'.(19+$i),$fromaddress);
        $activeSheet->setCellValue('G'.(19+$i),$address);
        $activeSheet->setCellValue('G'.(18+$i),'Автотранспорт');





        // Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle('Накладная');


        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);



        // Redirect output to a client’s web browser (Excel2007)
        //header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-type:application/vnd.ms-excel");
        header('Content-Disposition: attachment;filename="nakladnaya.xls"');
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

    public function actionNew()
    {
        $isAjax = false;
        $form_model =  new \app\models\Expense();
        if(\Yii::$app->request->isAjax){
            $isAjax = TRUE;// return 'Запрос принят!';
        }
        //$form_model->load(\Yii::$app->request->post());

        if ($form_model->load(Yii::$app->request->post(), '')) {
            $form_model->expenseId = Yii::$app->request->post()['expenseId'];
            $form_model->expenseDate = Yii::$app->request->post()['expenseDate'];
            $form_model->debt = Yii::$app->request->post()['debt'];
            $form_model->comment = Yii::$app->request->post()['comment'];
            $form_model->clientId = Yii::$app->request->post()['clientId'];
            $form_model->fakt = Yii::$app->request->post()['fakt'];
            $form_model->expType = Yii::$app->request->post()['expType'];
            $form_model->transfer = Yii::$app->request->post()['transfer'];
            $form_model->inCash = Yii::$app->request->post()['inCash'];
            $form_model->terminal = Yii::$app->request->post()['terminal'];
            $form_model->expSum = Yii::$app->request->post()['expSum'];
            $form_model->status = Yii::$app->request->post()['status'];
            $form_model->userId = Yii::$app->request->post()['userId'];
            $form_model->paidType = Yii::$app->request->post()['paidType'];
            $form_model->charge = Yii::$app->request->post()['charge'];
            $form_model->charge = Yii::$app->request->post()['from'];
            $form_model->save();
            
            /*
             * 
             * 'expenseId' => 'Номер накладной',
            'expenseDate' => 'Дата',
            'debt' => 'Карзж',
            'comment' => 'Комментарий',
            'clientId' => 'Клиент',
            'fakt' => 'Факт',
            'expType' => 'Тип',
            'transfer' => 'Перечисление',
            'inCash' => 'Наличные',
            'terminal' => 'Терминал',
            'expSum' => 'Сумма',
            'status' => 'Статус',
            'userId' => 'Пользователь',
            'paidType' => 'Тип оплаты',
            'charge' => 'Наценка',
             */
            if($isAjax)
            {
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return $form_model->toArray();

            }else
                return $this->render('index', ['models'=> $form_model]);

        }
        //var_dump($form_model);
        return $this->render('index', ['model'=> $form_model]);
    }

    public function actionDelete()
    {
        $id = Yii::$app->request->post()['id'];

        try {

            $expense = Expense::find()->where(['expenseId'=>$id])->one();


            Yii::$app->db->createCommand()->insert('expenseDefect',[
                'expenseId' => $expense->expenseId,
                'expenseDate' => $expense->expenseDate,
                 'debt' => $expense->debt,
                 'comment' => $expense->comment,
                 'clientId' =>  $expense->clientId,
                 'fakt'=>$expense->fakt,
                 'expType' => $expense->expType,
                 'transfer' =>  $expense->transfer,
                 'inCash' =>$expense->inCash,
                 'terminal'=>$expense->terminal,
                 'expSum'=>$expense->expSum,
                'status'=>$expense->status,
                'userId'=>$expense->userId,
                'paidType'=>$expense->paidType,
                'charge'=>$expense->charge,
                'fromId' =>$expense->fromId
            ])
                ->execute();
            $orders = Orders::deleteAll('expenseId='.$id);
            Account::deleteAll('expenseId='.$id);

            $rowCnt = Expense::deleteAll('expenseId='.$id);
            return $orders;
        }   catch (\yii\db\Exception $e) {
            echo $e->getMessage();
        }

    }

    public function actionClearfilter()
    {
        $session = \Yii::$app->session;
        if(isset($session["filtrRefreshClients"]))
        {
            $session->remove('filtrRefreshClients');
            $this->redirect('/sold/expense/refreshdbyclients');

        }

    }



    public function actionRefreshdbyclients()
    {


        $sql = "select e.expenseId, pr.name as Pname, o.addition,o.additionCnt, o.stuffProdId,p.name, round(((o.orderSumm)/o.faktCount), 2) as price, o.packCount, o.faktCount, o.idType, e.expenseDate, e.fromId,
                    e.clientId, cl.clientName, e.paidType,o.orderSumm, e.expSum,
                    d.deliveryType, d.driver, d.price as deliveryPrice, concat(d.address,' ',d.name,' ',d.description) as deliver 
                from orders as o
                LEFT OUTER JOIN (select * from (select productId as pId, name,(select 0) as idType, price from product
										union all
										select stuffId as pId, name, (select 1) as idType, price from stuff)
										as prod) as p on p.pId = o.stuffProdId 
                
                LEFT OUTER JOIN expense as e on o.expenseId = e.expenseId
                LEFT OUTER JOIN delivery as d on e.expenseId = d.expenseId
                LEFT OUTER JOIN clients as cl on e.clientId = cl.clientId
                LEFT OUTER JOIN product as pr on pr.productId = o.addition
                
                ";
        $isPost = false;
        $clientId  = 0;
        $prodStuffId ='';
        $idType = '';
        $dateFrom = '';
        $dateTo = '';
        $prodName = '';
        $session = \Yii::$app->session;
        $isPost = false;
        $prodName = '';
        $driver = '';
        if($session->has("filtrRefreshClients"))
        {
            $filter = $session["filtrRefreshClients"];

            $clientId  = $filter['clientId'];
            $driver  = $filter['driver'];
            $prodStuffId =$filter['stuffProdId'];
            $idType = $filter['idType'];
            $dateFrom = $filter['dateFrom'];
            $dateTo = $filter['dateTo'];


        }
        if($dateFrom==''&&$dateTo=='')
        {
            $dateFrom = '2018-01-01';
            $dateTo = date('Y-m-d');
        }






        if(isset(Yii::$app->request->post()['clientId'])&&isset(Yii::$app->request->post()['stuffProdId'])&&isset(Yii::$app->request->post()['idType']))
        {
            $isPost = true;
            $clientId = isset(Yii::$app->request->post()['clientId'])&&Yii::$app->request->post()['clientId']!=''?Yii::$app->request->post()['clientId']:0;
            $driver = isset(Yii::$app->request->post()['driver'])&&Yii::$app->request->post()['driver']!=''?Yii::$app->request->post()['driver']:'';
            $prodStuffId = isset(Yii::$app->request->post()['stuffProdId'])&&Yii::$app->request->post()['stuffProdId']!=''?Yii::$app->request->post()['stuffProdId']:'';
            $idType =  isset(Yii::$app->request->post()['idType'])&&Yii::$app->request->post()['idType']!=''?Yii::$app->request->post()['idType']:'';
            $dateFrom = isset(Yii::$app->request->post()['dateFrom'])&&Yii::$app->request->post()['dateFrom']!=''?Yii::$app->request->post()['dateFrom']:'date(now())';
            $dateTo = isset(Yii::$app->request->post()['dateTo'])&&Yii::$app->request->post()['dateTo']!=''?Yii::$app->request->post()['dateTo']:'date(now())';

            $dateRange = 'date(now())';

            if($dateFrom==$dateTo)
                $dateRange = "e.expenseDate between '".$dateFrom." 00:00:01' and '".$dateFrom." 23:59:59'";
            else
            {
                $dateRange = "e.expenseDate between '".$dateFrom." 00:00:01' and '".$dateTo." 23:59:59'";
            }


            $where = '';
            if($clientId!=0)
                $where = "e.clientId = $clientId";
            if($prodStuffId!='')
            {
                $where .= ($where!=''?" and o.stuffProdId = $prodStuffId": " o.stuffProdId = $prodStuffId");
            }

            if($idType!='')
            {
                $where .= ($where!=''?" and o.idType = $idType": " o.idType = $idType");
            }
            if($driver!='')
            {
                $where .= ($where!=''?" and d.driver = '$driver'": " d.driver = '$driver'");
            }
            if($where!='')
                $sql .=  ' where '.$dateRange.' and p.idType = o.idType and (' . $where .')';
            else
                $sql .= ' where '.$dateRange.' and p.idType = o.idType';




            //$sql .= "where p.idType = o.idType and (e.clientId = $clientId and o.stuffProdId = $prodStuffId and o.idType = $idType)";
            //return $sql;




        }else
        {
            $dateRange = 'date(now())';
            if($dateFrom==$dateTo)
                $dateRange = "e.expenseDate between '".$dateFrom." 00:00:01' and '".$dateFrom." 23:59:59'";
            else
            {
                $dateRange = "e.expenseDate between '".$dateFrom." 00:00:01' and '".$dateTo." 23:59:59'";
            }

            //$sql .= "where p.idType = o.idType";
            $where = '';
            if($clientId!=0)
                $where = "e.clientId = $clientId";
            if($prodStuffId!='')
            {
                $where .= ($where!=''?" and o.stuffProdId = $prodStuffId": " o.stuffProdId = $prodStuffId");
            }
            if($driver!='')
            {
                $where .= ($where!=''?" and d.driver = '$driver'": " d.driver = '$driver'");
            }

            if($idType!='')
            {
                $where .= ($where!=''?" and o.idType = $idType": " o.idType = $idType");
            }
            if($where!='')
                $sql .=  ' where '.$dateRange.' and p.idType = o.idType and (' . $where .')';
            else
                $sql .= ' where '.$dateRange.' and p.idType = o.idType';

        }

        $mProduct = new Product();
        $mStuff = new Stuff();
        $mClients = new Clients();
        $mProduct = Product::find()->all();
        $mStuff = Stuff::find()->all();
        $mClients = Clients::find()->all();





        //\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $sql .= " order by e.expenseId DESC limit 1000";
        $command = \Yii::$app->db->createCommand($sql)->queryAll();
        if($isPost) {
            $prodName = '';
            if($prodStuffId!='') {

                if ($idType == 0) {
                    $prodName = Product::findOne(['productId' => $prodStuffId])->name;
                } else if($idType == 1){
                    $prodName = Stuff::findOne(['stuffId' => $prodStuffId])->name;
                }
                $session->set('filtrRefreshClients', ['idType' => $idType, 'clientId' => $clientId, 'stuffProdId' => $prodStuffId, 'prodName'=>$prodName, 'dateFrom'=>$dateFrom, 'dateTo'=>$dateTo, 'driver'=>$driver]);

                return $this->render('filtered', ['records' => $command, 'mProduct' => $mProduct, 'mStuff' => $mStuff, 'mClients' => $mClients]);
            }else
            {
                $session->set('filtrRefreshClients', ['idType' => $idType, 'clientId' => $clientId, 'stuffProdId' => $prodStuffId, 'prodName'=>$prodName, 'dateFrom'=>$dateFrom, 'dateTo'=>$dateTo, 'driver'=>$driver]);
                return $this->render('filtered', ['records' => $command, 'mProduct' => $mProduct, 'mStuff' => $mStuff, 'mClients' => $mClients]);
            }

        }
        else
        {
            $session->set('filtrRefreshClients', ['idType' => $idType, 'clientId' => $clientId, 'stuffProdId' => $prodStuffId, 'prodName'=>$prodName, 'dateFrom'=>$dateFrom, 'dateTo'=>$dateTo, 'driver'=>$driver]);
            return $this->render('filtered', ['records' => $command, 'mProduct' => $mProduct, 'mStuff' => $mStuff, 'mClients' => $mClients]);

        }



    }


}
