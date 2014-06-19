<?php


class oaseadminModel extends Model{

    public function getUnit(){
        return $this->_db->getAllData('oase_unit');
    }

    //$this->_model->addunit($name , $address , $unittype , $foundtime , $manager);

    public function addunit($data){
        $id = $this->_db->insert('oase_unit' , $data);
        return $id;
    }

    public function login($user,$pass){
        $sql = "SELECT * FROM oase_employee WHERE username='" . $user  ."'";
        $this->_db->query($sql);
        $data = $this->_db->getDatas();
        $login = null;
        foreach($data as $key => $value){
            if(Cryptography::blowfish_verify($pass , $value['password'])){
                $login = $value['username'];
                break;
            }
        }
        if(!is_null($login)){
            $sql = "SELECT oase_employee.id , oase_employee.username , oase_employee.name , oase_employee_type.name as etn , oase_employee.sex , oase_employee.birthday , oase_employee.salary , oase_employee_type.priority as etp , oase_unit.name as u_name FROM oase_employee , oase_employee_type , oase_unit , oase_unit_type WHERE oase_employee.type = oase_employee_type.id and oase_employee.unit_id = oase_unit.id and oase_unit.type = oase_unit_type.id and oase_employee.username = '" . $login . "'";
            $this->_db->query($sql);
            $result = $this->_db->getData();
        }else{
            return false;
        }
        return $result;
    }

    public function deleteunit($id){
        $this->_db->delete('oase_unit' , $id);
    }

    public function editunit($data){
        $this->_db->update('oase_unit' , $data);
    }

    public function getunittype(){
        return $this->_db->getAllData('oase_unit_type');
    }

    public function getAvailableEmployeeType(){

        $sql = "SELECT * FROM oase_employee_type WHERE oase_employee_type.priority > " . $_SESSION['priority'] ;

        $this->_db->query($sql);

        $data = $this->_db->getDatas();
        return $data;
    }

    public function getAvailableUnit(){
        $sql = "SELECT oase_unit.id , oase_unit.name , oase_unit.address  FROM oase_unit , oase_unit_type WHERE oase_unit.type = oase_unit_type.id and (oase_unit_type.priority >  " . $_SESSION['priority'] . " or oase_unit.name = '" . $_SESSION['user']['u_name'] . "')";

        $this->_db->query($sql);

        $data = $this->_db->getDatas();
        return $data;
    }

    public function addemployee($data){
        return $this->_db->insert('oase_employee' , $data);
    }

    public function getemployee(){
        $sql =  "select oase_employee.* from oase_employee , oase_employee_type WHERE oase_employee.type = oase_employee_type.id and oase_employee_type.priority > " . $_SESSION['priority'];
        $this->_db->query($sql);
        return $this->_db->getDatas();
    }

    public function editemployee($data){
        $this->_db->update('oase_employee' , $data);
    }

    public function deleteemployee($id){
        $this->_db->delete('oase_employee' , $id);
    }

    public function insertproductcategory($name){
        $this->_db->insert('oase_product_category' , array(
            'name' => $name
        ));
    }

    public function getProductcategory(){
        return $this->_db->getAllData('oase_product_category');
    }

    public function editproductcategory($data){
        return $this->_db->update('oase_product_category' , $data);
    }

    public function deleteproductcategory($id){
        $this->_db->delete('oase_product_category' , $id);
    }

    public function addproduct($data){
        return $this->_db->insert('oase_product' , $data);
    }

    public function getproduct(){
        $sql = "SELECT oase_product.* , oase_product_category.name as product_category_name FROM oase_product left join oase_product_category on oase_product.product_category_id = oase_product_category.id";
        $this->_db->query($sql);
        return $this->_db->getDatas();
    }

    public function editproduct($data){
        $this->_db->update('oase_product' , $data);
    }

    public function deleteproduct($id){
        $this->_db->delete('oase_product' , $id);
    }

    public function getproductspecialorder(){
        return $this->_db->getAllData('oase_product_special_order');
    }

    public function addproductspecialorder($data){
        $this->_db->insert('oase_product_special_order' , $data);
    }

    public function editproductspecialorder($data){
        $this->_db->update('oase_product_special_order' , $data);
    }

    public function deleteproductspecialorder($id){
        $this->_db->delete('oase_product_special_order' , $id);
    }

    public function getstore(){
        $sql = "select oase_unit.* , oase_unit_type.name as type from oase_unit left join oase_unit_type on oase_unit.type = oase_unit_type.id where `type`=2";
        $this->_db->query($sql);
        return $this->_db->getDatas();
    }

    public function checkmember($email){
        $sql = "SELECT account FROM oase_member WHERE account = '$email'";
        $this->_db->query($sql);
        return $this->_db->getNum();
    }

    public function addmember($data){
        return $this->_db->insert('oase_member' , $data);
    }

    public function loginmember($username){
        $sql = "SELECT * FROM oase_member WHERE account = '$username'";
        $this->_db->query($sql);
        return $this->_db->getData();
    }

    // ----------------

    public function getallorder(){
        $sql = "select * from oase_order order by order_time DESC";
        $this->_db->query($sql);
        return $this->_db->getDatas();
    }

    public function addorder($data){
        return $this->_db->insert('oase_order' , $data);
    }

    public function editorder($data){
        return $this->_db->update('oase_order' , $data);
    }

    public function getorder($id){
        $sql = "select * from oase_order where id=$id";
        $this->_db->query($sql);
        return $this->_db->getData();
    }

    public function getorderbymember($memid){
        $sql = "select * from oase_order where member_id=$memid order by order_time DESC";
        $this->_db->query($sql);
        return $this->_db->getDatas();
    }

    public function addorderdetail($data){
        return $this->_db->insert('oase_order_detail' , $data);
    }

    public function getorderdetails($id){
        $sql = "select * from oase_order_detail WHERE order_id=$id";
        $this->_db->query($sql);
        return $this->_db->getDatas();
    }

    public function addorderdetailspo($data){
        return $this->_db->insert('oase_order_detail_special_order' , $data);
    }

    public function getorderdetailspo($id){
        $sql = "select * from oase_order_detail_special_order WHERE order_detail_id=$id";
        $this->_db->query($sql);
        return $this->_db->getDatas();
    }

    public function getdeliverorder(){
        $sql = "SELECT oase_order.* , oase_member.mem_name FROM `oase_order` left join oase_member on oase_order.member_id = oase_member.mem_id WHERE oase_order.state<3";
        $this->_db->query($sql);
        return $this->_db->getDatas();
    }

    public function updateorder($data){
        $this->_db->update('oase_order' , $data);
    }


}