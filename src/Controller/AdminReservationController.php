<?php

namespace App\Controller;

// const RECORDS_PER_PAGE = 20;
// const FIELD_LIST = [
//     'id', 'userId', 'carTypeId', 'startDateTime', 'returnDateTime',
//     'dailyPrice', 'netFees', 'rentStoreId', 'returnStoreId'
// ];
// const ITEM_TITLE = 'Reservation';
// const TABLE_NAME = 'reservations';

class AdminReservationController extends AdminManuController 
{
    public function __construct(){
        $this->itemTitle = 'Reservation';

        $this->tableName = 'reservations';

        $this->fieldList = [
            'id', 'userId', 'carTypeId', 'startDateTime', 'returnDateTime',
            'dailyPrice', 'netFees', 'rentStoreId', 'returnStoreId'
        ];
    }

    /*
    public function index (Request $request, Response $response)
    {
        $view = Twig::fromRequest($request);

        return $view->render($response, 'admin/item_list.html.twig',[
            'itemTitle' => ITEM_TITLE,
            'itemKeyList' => FIELD_LIST,
            'currentPage' => 1
        ]);
    }


    public function showAll (Request $request, Response $response)
    {
        $view = Twig::fromRequest($request);

        $get = $this->parseGetRequest($request);

        $startResv = ($get['currentPage'] - 1) * RECORDS_PER_PAGE;

        $resvList = DB::query(
            "SELECT %l
            FROM %l
            ORDER BY %l %l
            LIMIT %i, %i",
            implode(",",FIELD_LIST),
            TABLE_NAME,
            $get['sortBy'], $get['order'], $startResv, RECORDS_PER_PAGE
        );

        return $view->render($response, 'admin/cards/item_card.html.twig', [
            'itemList' => $resvList,
            'currentPage' => $get['currentPage'],
            'totalPage' => $get['totalPage']
        ]);
    }

    public function create (Request $request, Response $response, array $args)
    {
        $jsonData = json_decode($request->getBody(), true);

        // TODO : validation
        // $errorList = Validator::reservation($jsonData);
        $errorList = [];

        if(empty($errorList)){
            DB::insert(TABLE_NAME,$jsonData);
        }

        $response->getBody()->write(json_encode([
            'errorList' => $errorList
        ]));

        return $response;
    }

    public function delete (Request $request, Response $response, array $args)
    {
        $id = $args['id'];

        DB::delete(TABLE_NAME,'id=%i', $id);
        $response->getBody()->write(json_encode(DB::affectedRows()));

        return $response;
    }

    public function edit (Request $request, Response $response, array $args)
    {
        $id = $args['id'];

        $jsonData = json_decode($request->getBody(), true);

        $data = [
            FIELD_LIST[$jsonData['fieldIndex']] => $jsonData['value']
        ];

        // TODO : validation
        // $errorList = Validator::reservation($jsonData, false);
        $errorList = [];

        if(empty($errorList)){
            DB::update(TABLE_NAME,$data,'id=%s',$id);
        }

        $response->getBody()->write(json_encode([
            'errorList' => $errorList
        ]));

        return $response;
    }


    private function parseGetRequest($request)
    {
        $get = $request->getQueryParams();

        $totalResv = DB::queryFirstField(
            "SELECT COUNT(*) FROM %l", TABLE_NAME
        );
        $totalPage = ceil( $totalResv / RECORDS_PER_PAGE);

        return [
            'currentPage' => $this->getCurrentPage($get, $totalPage),
            'sortBy' => $this->getSortBy($get),
            'order' => $this->getOrder($get),
            'totalPage' => $totalPage
        ];

    }

    private function getCurrentPage($get, $totalPage)
    {
        if(isset($get['page'])){
            $page = $get['page'] > $totalPage ? $totalPage : $get['page'];
            $page = $get['page'] < 1 ? 1 : $get['page'];
            return $page;
        }     
        return 1;
    }

    private function getSortBy($get)
    {
        if(isset($get['sortBy'])){
            $fieldList = DB::queryFirstColumn("DESCRIBE reservations");
            return in_array($get['sortBy'], $fieldList) 
                    ? $get['sortBy'] : 'startDateTime';
        }
        return 'startDateTime';
        
    }

    private function getOrder($get)
    {
        if(isset($get['order'])){
            return strtoupper($get['order']) == 'ASC' ? 'ASC': 'DESC';
        }     
        return 'DESC';
    }
    */
}