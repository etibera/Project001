<?php
require_once '../init.php';
class Search {
    function __construct()
    {
        $this->conn = new Database();
        $this->conn = $this->conn->getmyDB();
    }
    public function autocomplete($search_value){
        $data = array();
        $count = str_word_count($search_value) + 2;
        $st = $this->conn->prepare("SELECT pp.name FROM (
		SELECT distinct SUBSTRING_INDEX(pd.name, ' ', :count) as `name`, p.status 
        FROM pcvill_ocnew.oc_product_description pd 
        left join pcvill_ocnew.oc_product p ON p.product_id = pd.product_id
        UNION ALL
        SELECT distinct SUBSTRING_INDEX(bg.product_name, ' ', :count) as `name`, bg.status
        FROM pcvill_ocnew.bg_product bg
        ) pp WHERE pp.name like :search_value AND pp.status = 1 limit 10");
        $st->bindValue(':count', (int) trim($count), PDO::PARAM_INT);
        $st->bindValue(':search_value', "%". trim($search_value). "%");
        $st->execute();
        foreach($st->fetchAll() as $value){
            $data[] = array(trim(preg_replace('/[^A-Za-z0-9_ \-]/', '', strtolower(html_entity_decode($value['name']))), " "));
        }
        return $data;
    }
    public function recommendation(){
        $data = array();
        $s = $this->conn->prepare("SELECT * From oc_product_brand WHERE status = 1 order by id desc");
        $s->execute();
        if($s->rowCount() > 0){
            foreach($s->fetchAll(PDO::FETCH_ASSOC) as $res){
                $data[] = array(
                    'name' => $res['name'],
                    'description' => $res['description'],
                );
            }
        }
        return $data;
    }
}
$search = new Search();