<?php
require_once '../init.php';
class Invite {
    function __construct()
    {
        $this->conn = new Database();
        $this->conn = $this->conn->getmyDB();
    }
    public function getNames($customer_id){
        $s = $this->conn->prepare("SELECT CNAME.customer_id,CNAME.firstname,CNAME.lastname,CNAME.date_added FROM oc_customer AS CNAME 
        INNER JOIN oc_customer_links AS LINKS ON CNAME.customer_id=LINKS.invite_id 
        WHERE LINKS.customer_id = :customer_id and LINKS.invite_id != 0 and CNAME.nexmo_status=1 ORDER BY id DESC");
        $s->bindValue(':customer_id', $customer_id);
        $s->execute();
        return $s->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getLinks($page_number, $customer_id){
        $offset = ($page_number-1) * 1;
        $stmt = $this->conn->prepare("SELECT * FROM oc_customer_links WHERE customer_id = :customer_id and invite_id = 0 LIMIT :offset, :no_per_page ");
        $stmt->bindValue(':offset', (int) $offset, PDO::PARAM_INT);
        $stmt->bindValue(':no_per_page', 10, PDO::PARAM_INT);
        $stmt->bindValue(':customer_id', (int) $customer_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
$invite = new Invite();