<?php

class oaseadminController extends Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->_opdata['menu'] = array(
            "DashBoard" => array(
                "icon" => "dashboard",
                "priority" => 30,
                "submenu" => array(
                    "Main Dashboard" => url('main', 'oaseadmin')
                )
            ),
            "員工系統" => array(
                "icon" => "male",
                "priority" => 60,
                "submenu" => array(
                    "新增員工" => url('addemployee', 'oaseadmin'),
                    "員工管理" => url('employeemanager', 'oaseadmin')
                )
            ),
            /*"會員系統" => array(
                "icon" => "group",
                "priority" => 60,
                "submenu" => array(
                    "會員管理" => "#"
                )
            ),*/
            "機構系統" => array(
                "icon" => "ambulance",
                "priority" => 30,
                "submenu" => array(
                    "部門管理" => url('unitmanager', 'oaseadmin')
                )
            ),
            "產品系統" => array(
                "icon" => "coffee",
                "priority" => 30,
                "submenu" => array(
                    "產品分類管理" => url('productcategorymanager', 'oaseadmin'),
                    "產品管理" => url('productmanager', 'oaseadmin'),
                    "加料管理" => url('productspecialorder', 'oaseadmin')
                )
            ),
            "點餐系統" => array(
                "icon" => "beer",
                "priority" => 85,
                "submenu" => array(
                    "POS" => "#",
                    "訂單管理" => url('ordermanager', 'oaseadmin')
                )
            ) /*,
            "運送系統" => array(
                "icon" => "ambulance",
                "priority" => 85,
                "submenu" => array(

                )
            ),
            "財務系統" => array(
                "icon" => "money",
                "priority" => 60,
                "submenu" => array(

                )
            ),
            "佈告系統" => array(
                "icon" => "list-ul",
                "priority" => 30,
                "submenu" => array(

                )
            ),*/
        );
    }

    public function index()
    {
        session_start();
    }

    private function islogin($b = true)
    {
        session_start();
        if ($_SESSION['islogin']) {
            return true;
        } else {
            if ($b == true) {
                redirect('index');
            }
        }
        return false;
    }

    public function login()
    {
        session_start();
        $username = $this->_request->getPost('username');
        $password = $this->_request->getPost('password');

        $result = $this->_model->login($username, $password);

        if (is_array($result)) {
            $_SESSION['islogin'] = true;
            $_SESSION['user'] = $result;

            $etp = $result['etp']; // employee type priority
            $utp = $result['utp']; // unit type priority
            /*
                        if($etp < $utp){
                            $_SESSION['priority'] = $etp;
                        }else{
                            $_SESSION['priority'] = $utp;
                        }
            */
            $_SESSION['priority'] = $etp;

            redirect('main');
        } else {
            redirect('index');
        }
        return self::AUTO_SHOWVIEW_OFF;
    }


    public function logout()
    {
        session_start();
        unset($_SESSION['islogin']);
        unset($_SESSION['user']);
        redirect('index');
        return self::AUTO_SHOWVIEW_OFF;
    }

    /**
     * Dash Board
     */
    public function main()
    {
        session_start();
        $this->_opdata['active'] = 'Main Dashboard';
        $this->islogin();
    }

    /**
     * 部門管理
     *
     */
    public function unitmanager()
    {
        session_start();
        $this->islogin();
        $this->_opdata['active'] = '部門管理';
        $this->_opdata['employee'] = $this->_model->getemployee();
        $this->_opdata['unit'] = $this->_model->getUnit();
        $this->_opdata['type'] = $this->_model->getunittype();
    }

    /**
     * 新增部門
     */
    public function addunit()
    {
        session_start();
        $this->islogin();
        $name = $this->_request->getPost('unitname');
        $address = $this->_request->getPost('unitaddress');
        $long = $this->_request->getPost('longitude');
        $lat = $this->_request->getPost('latitude');
        $unittype = $this->_request->getPost('unittype');
        $foundtime = $this->_request->getPost('foundtime');
        $manager = $this->_request->getPost('manager');

        $data = array(
            "name" => $name,
            "address" => $address,
            "latitude" => $lat,
            "longitude" => $long,
            "type" => $unittype,
            "found_time" => $foundtime,
        );
        if ($manager != "") {
            $data['manager'] = $manager;
        }

        $id = $this->_model->addunit($data);
        if ($id > 0) {
            redirect('unitmanager');
        } else {
            throw new Exception("Add Unit Error");
        }
        return self::AUTO_SHOWVIEW_OFF;
    }

    /**
     * 刪除部門
     */
    public function deleteunit()
    {
        session_start();
        $result = null;
        if ($this->islogin(false)) {
            $result['id'] = $this->_request->getPost('id');
            $this->_model->deleteunit($result['id']);
        } else {
            $result = false;
        }
        api_json();
        echo json_encode($result);
        return self::AUTO_SHOWVIEW_OFF;
    }

    /**
     * 修改部門
     */
    public function editunit()
    {
        session_start();

        $id = $this->_request->getPost('unitid');
        $name = $this->_request->getPost('unitname');
        $address = $this->_request->getPost('unitaddress');
        $unittype = $this->_request->getPost('unittype');
        $lat = $this->_request->getPost('latitude');
        $long = $this->_request->getPost('longitude');
        $unittype = $this->_request->getPost('unittype');
        $foundtime = $this->_request->getPost('foundtime');
        $manager = $this->_request->getPost('manager');

        $data = array(
            "id" => $id,
            "name" => $name,
            "address" => $address,
            "latitude" => $lat,
            "longitude" => $long,
            "type" => $unittype,
            "found_time" => $foundtime,
        );

        if ($manager != "") {
            $data['manager'] = $manager;
        }

        $this->_model->editunit($data);

        redirect('unitmanager', 'oaseadmin');

        return self::AUTO_SHOWVIEW_OFF;
    }

    //-------------------------------------------------------------------

    /**
     * 新增員工頁面
     */
    public function addemployee()
    {
        session_start();
        $this->_opdata['active'] = '新增員工';
        $this->_opdata['type'] = $this->_model->getAvailableEmployeeType();
        $this->_opdata['unit'] = $this->_model->getAvailableUnit();
    }

    /**
     * 新增員工動作
     */
    public function doaddemployee()
    {
        session_start();

        $username = $this->_request->getPost('username');
        $password = $this->_request->getPost('password');
        $type = $this->_request->getPost('type');
        $name = $this->_request->getPost('name');
        $sex = $this->_request->getPost('sex');
        $birthday = $this->_request->getPost('birthday');
        $address = $this->_request->getPost('address');
        $salary = $this->_request->getPost('salary');
        $unit_id = $this->_request->getPost('unit');

        $data = array(
            'username' => $username,
            'password' => Cryptography::blowfish_password($password),
            'type' => $type,
            'name' => $name,
            'sex' => $sex,
            'birthday' => $birthday,
            'address' => $address,
            'salary' => $salary,
            'unit_id' => $unit_id
        );

        $this->_model->addemployee($data);
        redirect('addemployee', 'oaseadmin');
        return self::AUTO_SHOWVIEW_OFF;
    }

    /**
     * 管理員工
     */
    public function employeemanager()
    {
        session_start();
        $this->_opdata['active'] = '員工管理';
        $this->_opdata['employee'] = $this->_model->getemployee();
        $this->_opdata['type'] = $this->_model->getAvailableEmployeeType();
        $this->_opdata['unit'] = $this->_model->getAvailableUnit();

        foreach ($this->_opdata['type'] as $value) {
            $this->_opdata['atype'][$value['id']] = $value;
        }

        foreach ($this->_opdata['unit'] as $value) {
            $this->_opdata['aunit'][$value['id']] = $value;
        }
    }

    /**
     * 刪除員工
     */
    public function deleteemployee()
    {
        session_start();
        $id = $this->_request->getPost('id');
        $this->_model->deleteemployee($id);
    }

    /**
     * 編輯員工
     */
    public function editemployee()
    {
        session_start();
        utf8();
        debug_show($_POST);

        $id = $this->_request->getPost('id');
        $name = $this->_request->getPost('name');
        $type = $this->_request->getPost('type');
        $sex = $this->_request->getPost('sex');
        $birthday = $this->_request->getPost('birthday');
        $address = $this->_request->getPost('address');
        $password = $this->_request->getPost('password');
        $salary = $this->_request->getPost('salary');
        $unit = $this->_request->getPost('unit');

        $data = array(
            'id' => $id,
            'name' => $name,
            'type' => $type,
            'sex' => $sex,
            'birthday' => $birthday,
            'address' => $address,
            'salary' => $salary,
            'unit_id' => $unit
        );

        if ($password != "") {
            $data['password'] = Cryptography::blowfish_password($password);
        }

        $this->_model->editemployee($data);

        redirect('employeemanager');

        return self::AUTO_SHOWVIEW_OFF;
    }

    // -------------------------------------------------------------------

    /**
     * 產品分類管理
     */
    public function productcategorymanager()
    {
        session_start();
        $this->_opdata['active'] = '產品分類管理';
        $this->_opdata['product_category'] = $this->_model->getProductcategory();
    }

    /**
     * 新增產品分類
     */
    public function insertproductcategory()
    {
        session_start();
        $name = $this->_request->getPost('name');
        $this->_model->insertproductcategory($name);
        redirect('productcategorymanager');
        return self::AUTO_SHOWVIEW_OFF;
    }

    /**
     * 修改產品分類
     */
    public function editproductcategory()
    {
        session_start();
        $id = $this->_request->getPost('id');
        $name = $this->_request->getPost('name');
        $data = array(
            'id' => $id,
            'name' => $name
        );
        $this->_model->editproductcategory($data);
        redirect('productcategorymanager');
        return self::AUTO_SHOWVIEW_OFF;
    }

    /**
     * 刪除產品分類
     */
    public function deleteproductcategory()
    {
        session_start();
        $id = $this->_request->getPost('id');
        $this->_model->deleteproductcategory($id);
        redirect('productcategorymanager');
        return self::AUTO_SHOWVIEW_OFF;
    }

    /**
     * 產品管理
     */
    public function productmanager()
    {
        session_start();
        $this->_opdata['active'] = '產品管理';
        $this->_opdata['cate'] = $this->_model->getProductcategory();
        $this->_opdata['product'] = $this->_model->getproduct();
    }

    public function insertproduct()
    {
        session_start();
        utf8();
        debug_show($_POST);
        debug_show($_FILES);

        $name = $this->_request->getPost('name');
        $category = $this->_request->getPost('category');
        $L = $this->_request->getPost('L');
        $M = $this->_request->getPost('M');
        $S = $this->_request->getPost('S');

        $data = array(
            'name' => $name,
            'product_category_id' => $category,
            'price_L' => $L,
            'price_M' => $M,
            'price_S' => $S
        );

        $id = $this->_model->addproduct($data);

        move_uploaded_file($_FILES['img']['tmp_name'], ROOT . '/public/upload/product/' . $id . '.jpg');
        redirect('productmanager');
        return self::AUTO_SHOWVIEW_OFF;
    }

    /**
     * 修改產品
     */
    public function editproduct()
    {
        session_start();

        $id = $this->_request->getPost('id');
        $name = $this->_request->getPost('name');
        $category = $this->_request->getPost('category');
        $L = $this->_request->getPost('L');
        $M = $this->_request->getPost('M');
        $S = $this->_request->getPost('S');

        $filename = $_FILES['img']['name'];

        $data = array(
            'id' => $id,
            'name' => $name,
            'product_category_id' => $category,
            'price_L' => $L,
            'price_M' => $M,
            'price_S' => $S
        );

        if ($filename != "") {
            unlink(ROOT . '/public/upload/product/' . $id . '.jpg');
            move_uploaded_file($_FILES['img']['tmp_name'], ROOT . '/public/upload/product/' . $id . '.jpg');
        }

        $this->_model->editproduct($data);

        redirect('productmanager');
        return self::AUTO_SHOWVIEW_OFF;
    }

    /**
     * 刪除產品
     */
    public function deleteproduct()
    {
        session_start();
        $id = $this->_request->getPost('id');
        $this->_model->deleteproduct($id);
        unlink(ROOT . '/public/upload/product/' . $id . '.jpg');
        redirect('productmanager');
        return self::AUTO_SHOWVIEW_OFF;
    }

    /**
     * 加料管理
     */
    public function productspecialorder()
    {
        session_start();
        $this->_opdata['active'] = "加料管理";
        $this->_opdata['pso'] = $this->_model->getproductspecialorder();
    }

    /**
     * 新增加料
     */
    public function insertproductspecialorder()
    {
        session_start();
        $name = $this->_request->getPost('name');
        $price = $this->_request->getPost('price');

        $data = array(
            'name' => $name,
            'price' => $price
        );

        $this->_model->addproductspecialorder($data);

        redirect('productspecialorder');

        return self::AUTO_SHOWVIEW_OFF;
    }

    /**
     * 編輯加料
     */
    public function editproductspecialorder()
    {
        session_start();
        $id = $this->_request->getPost('id');
        $name = $this->_request->getPost('name');
        $price = $this->_request->getPost('price');

        $data = array(
            'id' => $id,
            'name' => $name,
            'price' => $price
        );

        $this->_model->editproductspecialorder($data);

        redirect('productspecialorder');

        return self::AUTO_SHOWVIEW_OFF;
    }

    /**
     * 刪除加料
     */
    public function deleteproductspecialorder()
    {
        session_start();

        $id = $this->_request->getPost('id');
        $this->_model->deleteproductspecialorder($id);

        return self::AUTO_SHOWVIEW_OFF;
    }

    /**
     * 訂單管理
     */
    public function ordermanager()
    {
        session_start();
        $this->_opdata['order'] = $this->_model->getallorder();
    }

    public function productlistapi()
    {
        api();
        api_json();
        $data['result'] = $this->_model->getproduct();
        if (!empty($_REQUEST['id'])) {
            $d['result'] = array();
            foreach ($data['result'] as $value) {
                if ($value['id'] == $_REQUEST['id']) {
                    $d['result'][0] = $value;
                    break;
                }
            }
            echo json_encode($d);
        } else {
            echo json_encode($data);
        }

        return self::AUTO_SHOWVIEW_OFF;
    }

    public function specialorderlistapi()
    {
        api();
        api_json();
        $data['result'] = $this->_model->getproductspecialorder();
        echo json_encode($data);

        return self::AUTO_SHOWVIEW_OFF;
    }

    public function storelistapi()
    {
        api();
        api_json();
        $data['result'] = $this->_model->getstore();
        echo json_encode($data);

        return self::AUTO_SHOWVIEW_OFF;
    }

    public function checkapi()
    {
        api();
        $sessionid = $_REQUEST['sessionid'];
        if (isset($sessionid))
            session_id($sessionid);
        session_start();

        $type = $_REQUEST['type'];
        if (empty($type)) {
            echo "error:" . "incorrect_request";
            die();
        }

        switch ($type) {
            case "isloggedin":
                $isloggedin = $_SESSION['user_is_loggedin'];
                if (!empty($isloggedin))
                    echo 1;
                else
                    echo 0;
                break;
            default:
                echo "error:" . "incorrect_request";
                die();
        }
        session_write_close();

        return self::AUTO_SHOWVIEW_OFF;
    }


    public function logoutapi()
    {
        api();
        $sessionid = $_REQUEST['sessionid'];
        if (isset($sessionid))
            session_id($sessionid);
// Inialize session
        session_start();

        $isloggedin = $_SESSION['user_is_loggedin'];
        if (empty($isloggedin)) {
            echo "failed:user_is_not_loggedin";
            die();
        }

        session_destroy();
        echo "success:" . time();

        session_write_close();
        return self::AUTO_SHOWVIEW_OFF;
    }

    public function registerapi()
    {
        api();
        $sessionid = $_REQUEST['sessionid'];
        if (isset($sessionid))
            session_id($sessionid);
        // Inialize session
        session_start();

        $isloggedin = $_SESSION['user_is_loggedin'];
        if (!empty($isloggedin)) {
            echo "failed:user_is_loggedin";
            die();
        }

        $name = $_POST['name'];
        $birthday = $_POST['birthday'];
        $career = $_POST['career'];
        $phone = $_POST['phone'];
        $gender = $_POST['gender'];
        $address = $_POST['address'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $password_confirmation = $_POST['password_confirmation'];

        if (empty($name) || empty($email) || empty($password) || empty($password_confirmation)) {
            echo "failed:incorrect_request";
            die();
        }

        if ($password != $password_confirmation) {
            echo "failed:password_confirmation_failed";
            die();
        }

        $hash = Cryptography::blowfish_password($password);

        if ($this->_model->checkmember($email) > 0) // account must not found.
        {
            echo "failed:account_name_overlap";
            die();
        }

        $jointime = date("Y-m-d", time());

        $data = array(
            'account' => $email,
            'password' => $hash,
            'mem_name' => $name,
            'mem_address' => $address,
            'career' => $career,
            'mem_birthday' => $birthday,
            'mem_phone' => $phone,
            'mem_gender' => $gender
        );
        $result = $this->_model->addmember($data);

        if ($result) {
            echo "success:" . time();
        } else {
            echo "failed:insert_failed";
            die();
        }
        session_write_close();
        return self::AUTO_SHOWVIEW_OFF;
    }

    public function loginapi()
    {
        api();
        $sessionid = $_REQUEST['sessionid'];
        if (isset($sessionid))
            session_id($sessionid);
        session_start();


        $username = $_POST['username'];
        $password = $_POST['password'];

        if (empty($username) || empty($password)) {
            echo "failed:incorrect_request";
            die();
        }

        $result = $this->_model->loginmember($username);


        if (empty($result)) // User not found. So, redirect to login_form again.
        {
            //header('Location: login.html');
            echo "failed:user_not_found";
            die();
        }


        if (!Cryptography::blowfish_verify($password, $result['password'])) // Incorrect password. So, redirect to login_form again.
        {
            //header('Location: login.html');
            echo "failed:wrong_password";
            die();
        } else { // Redirect to home page after successful login.
            $_SESSION['user_is_loggedin'] = 1;
            $_SESSION['user_id'] = $result['mem_id'];
            $_SESSION['user_info'] = $result;
            session_regenerate_id();
            //header('Location: home.php');
            echo "success:" . session_id();
        }
        session_write_close();
        return self::AUTO_SHOWVIEW_OFF;
    }


    public function getapi()
    {
        api();
        $sessionid = $_REQUEST['sessionid'];
        if (isset($sessionid))
            session_id($sessionid);
        session_start();

        $type = $_REQUEST['type'];
        if (empty($type)) {
            echo "failed:" . "incorrect_request";
            die();
        }

        $isloggedin = $_SESSION['user_is_loggedin'];
        $user_id = $_SESSION['user_id'];

        if (!empty($isloggedin)) {
            if (!empty($user_id)) {
            } else {
                echo "failed:" . "user_id_not_found";
                die();
            }
        } else {
            echo "failed:" . "user_not_loggedin";
            die();
        }


        switch ($type) {
            case "id":
                echo "success:" . $_SESSION['user_info']['mem_id'];
                break;
            case "name":
                echo "success:" . $_SESSION['user_info']['mem_name'];
                break;
            case "phone":
                echo "success:" . $_SESSION['user_info']['mem_phone'];
                break;
            case "address":
                echo "success:" . $_SESSION['user_info']['mem_address'];
                break;
            default:
                echo "failed:" . "incorrect_request";
                die();
        }
        session_write_close();
        return self::AUTO_SHOWVIEW_OFF;
    }


    public function shopcartapi()
    {
        api();

        $sessionid = $_REQUEST['sessionid'];
        if (isset($sessionid))
            session_id($sessionid);
        session_start();


        $p_id = $_POST['p_id'];
        $so_id = $_POST['so_id'];
        $size = $_POST['size'];
        $temp = $_POST['temp'];
        $sweet = $_POST['sweet'];
        $count = $_POST['count'];

        $type = $_REQUEST['type'];
        if (empty($type)) {
            echo "failed:" . "incorrect_request";
            die();
        }

        $temp_order = $_SESSION['temp_order'];
        if (!isset($temp_order))
            $temp_order = array();

        switch ($type) {
            case "get_temp_order":
                $index = $_REQUEST['index'];
                api_json();
                if (empty($index))
                    echo json_encode($temp_order);
                else
                    echo json_encode($temp_order[$index - 1]);
                break;
            case "get_temp_order_count":
                echo "success:" . count($temp_order);
                break;
            case "add_temp_order":
                if (empty($p_id) || empty($size) || empty($temp) || empty($sweet) || empty($count)) {
                    echo "failed:" . "incorrect_request";
                    die();
                }
                $order = array(
                    "p_id" => $p_id,
                    "so_id" => $so_id,
                    "size" => $size,
                    "temp" => $temp,
                    "sweet" => $sweet,
                    "count" => $count
                );
                array_push($temp_order, $order);
                echo "success:" . session_id();
                break;
            case "remove_temp_order":
                $index = $_REQUEST['index'];
                if (empty($index))
                    unset($temp_order);
                else
                    unset($temp_order[$index - 1]);
                $temp_order = array_values($temp_order);
                echo "success:" . session_id();
                break;
            default:
                echo "failed:" . "incorrect_request";
                die();
        }
        $_SESSION['temp_order'] = $temp_order;
        session_write_close();
        return self::AUTO_SHOWVIEW_OFF;
    }

    public function orderapi()
    {
        api();
        $sessionid = $_REQUEST['sessionid'];
        if (isset($sessionid))
            session_id($sessionid);
        session_start();


        $member_id = $this->_request->getPost('mem_id');
        $price = $this->_request->getPost('totalprice');
        $address = $this->_request->getPost('address');
        $uid = $this->_request->getPost('u_id');
        $phone = $this->_request->getPost('phone');
        $order = $_POST['order'];

        if ($price == "") {
            echo "fail";
            session_write_close();
            return self::AUTO_SHOWVIEW_OFF;
        }

        if ($member_id == "") {
            echo "fail";
            session_write_close();
            return self::AUTO_SHOWVIEW_OFF;
        }


        $data = array(
            "price" => $price,
            "order_address" => $address,
            "phone" => $phone,
            "deliver_request" => false,
            "state" => 0,
            "member_id" => $member_id,

        );

        if ($uid == "") {
            //$data['unit_id'] = 135; //外送專用
            $data['deliver_request'] = true;
        } else {
            $data['unit_id'] = $uid;
        }

        $order_id = $this->_model->addorder($data);

        $data = array(
            'id' => $order_id,
            'code' => Cryptography::blowfish_password($order_id)
        );
        $this->_model->editorder($data);

        foreach ($order as $o) {
            $data = array(
                "order_id" => $order_id,
                "product_id" => $o['p_id'],
                "count" => $o['count'],
                "size" => $o['size'],
                "temp" => $o['temp'],
                "sweet" => $o['sweet']
            );

            $od_id = $this->_model->addorderdetail($data);

            $spo = explode(",", $o['so_id']);
            if(count($spo)>0){
                foreach ($spo as $v) {
                    if($v != 0){
                        $data = array(
                            "order_detail_id" => $od_id,
                            "product_special_order_id" => $v
                        );
                        $this->_model->addorderdetailspo($data);
                    }

                }
            }
        }
        echo "success:" . $sessionid;
        session_write_close();
        return self::AUTO_SHOWVIEW_OFF;
    }



    public function getorderapi()
    {
        api();
        utf8();
        /*$sessionid = $_REQUEST['sessionid'];
        if(isset($sessionid))
            session_id($sessionid);
        session_start();
        */
        //$member_id = _SESSION['user_id'];
        $member_id = $_REQUEST['mid'];

        $data = $this->_model->getorderbymember($member_id);

        for ($i = 0; $i < count($data); $i++) {
            $data[$i]['order'] = $this->_model->getorderdetails($data[$i]['id']);

            for ($j = 0; $j < count($data[$i]['order']); $j++) {

                $spoid = $data[$i]['order'][$j]['id'];

                $spo = $this->_model->getorderdetailspo($spoid);

                //$data[$i]['order'][$j]['specialorder'] = $spo;

                if (count($spo) < 2) {
                    $data[$i]['order'][$j]['specialorder'] = $spo[0]['product_special_order_id'];
                } else {
                    $spos = "";
                    for ($k = 0; $k < count($spo); $k++) {
                        if ($spos == "") {
                            $spos = $spo[$k]['product_special_order_id'];
                        } else {
                            $spos = $spos . "," . $spo[$k]['product_special_order_id'];
                        }
                    }
                    $data[$i]['order'][$j]['specialorder'] = $spos;
                }

            }


        }

        api_json();
        echo json_encode($data, 0);
        //debug_show($data);

        //session_write_close();
        return self::AUTO_SHOWVIEW_OFF;
    }

    public function getorderdetailapi(){
        api();
        utf8();
        $orderid = $this->_request->getPost('id');
        $product = $this->_model->getproduct();
        $so = $this->_model->getproductspecialorder();
        $data = $this->_model->getorderdetails($orderid);
        for($i = 0 ; $i < count($data) ; $i++){
            foreach($product as $value){
                if($value['id'] == $data[$i]['product_id']){
                    $data[$i]['product_name'] = $value['name'];
                }
            }
            $data[$i]['so'] = $this->_model->getorderdetailspo($data[$i]['id']);
            for($j = 0 ; $j < count($data[$i]['so']);$j++){
                foreach($so as $sov){
                    if($sov['id'] == $data[$i]['so'][$j]['product_special_order_id']){
                        $data[$i]['so'][$j]['product_special_name'] = $sov['name'];
                    }
                }
            }
        }
        echo json_encode($data);
        return self::AUTO_SHOWVIEW_OFF;
    }

    public function authapi()
    {
        api();

        $username = $this->_request->getPost('username');
        $password = $this->_request->getPost('password');

        $result = $this->_model->login($username, $password);

        api_json();
        echo json_encode($result);

        return self::AUTO_SHOWVIEW_OFF;
    }

    public function getdeliverorder()
    {
        api();
        api_json();
        echo json_encode($this->_model->getdeliverorder());
        return self::AUTO_SHOWVIEW_OFF;
    }

    public function changeorderstateapi()
    {
        api();
        $id = $this->_request->getPost('id');
        $newstate = $this->_request->getPost('state');

        $data = array(
            'id' => $id,
            'state' => $newstate
        );

        $this->_model->updateorder($data);
        echo "success";
        return self::AUTO_SHOWVIEW_OFF;
    }

    public function verifycode()
    {
        api();

        $id = $this->_request->getPost('id');
        $code = $this->_request->getPost('code');

        $r = $this->_model->getorder($id);
        Log::write(serialize($r));
        if ($r['code'] == $code) {
            echo "true";
        } else {
            echo "false";
        }

        return self::AUTO_SHOWVIEW_OFF;
    }


}