<?php
require 'config.php';
require 'Slim/Slim.php';
require 'vendor/autoload.php';
// header('Access-Control-Allow-Origin: *');
// header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
// header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');

// header('Access-Control-Allow-Origin:*'); 
// header('Access-Control-Allow-Headers:X-Request-With');

// header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
// header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

//$stripeKey = 'sk_test_BvD8gQeTcrAxmFmVvyieYUNA';
 
\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();

if ($app->request->isOptions()) {
    return true;
    // break;
}
$app->put('/login', 'login'); /* User login */
$app->put('/loginadmin', 'loginadmin'); /* User login */
$app->put('/checkadmin', 'checkadmin'); /* User login */
$app->put('/signup', 'signup'); /* User Signup  */
$app->put('/signupadmin', 'signupadmin'); /* User Signup  */
$app->put('/getslides', 'getslides'); /* User Signup  */
$app->put('/getcat', 'getcat'); /* User Signup  */
$app->put('/getproductofcat', 'getproductofcat'); /* User Signup  */
$app->put('/getproducdetails', 'getproducdetails'); /* User Signup  */
$app->put('/getcart', 'getcart'); /* cart  */

$app->put('/addtocart', 'addtocart'); /* cart  */
$app->put('/addqtytocart', 'addqtytocart'); /* cart  */
$app->put('/removeqtytocart', 'removeqtytocart'); /* cart  */
$app->put('/removecart', 'removecart'); /* cart  */
$app->put('/newarrivals', 'newarrivals'); /* cart  */
$app->put('/specialpieces', 'specialpieces'); /* cart  */
$app->put('/sendEmailto404', 'sendEmailto404'); /* cart  */
$app->put('/searchproduct', 'searchproduct'); /* User Feeds  */
$app->put('/feed', 'feed'); /* User Feeds  */
$app->put('/feedUpdate', 'feedUpdate'); /* User Feeds  */
$app->put('/feedDelete', 'feedDelete'); /* User Feeds  */
$app->put('/getImages', 'getImages');
$app->put('/removeslide', 'removeslide'); /* cart  */
$app->put('/getproducts', 'getproducts'); /* cart  */
$app->put('/removeproduct', 'removeproduct'); /* cart  */
$app->put('/addproduct', 'addproduct');

$app->put('/returnproduct', 'returnproduct');
$app->put('/returndeposit', 'returndeposit');
$app->put('/removecat', 'removecat');
$app->put('/addcat', 'addcat');
$app->put('/confirmcart', 'confirmcart');
$app->put('/addmembership', 'addmembership'); /* add Membership */

$app->put('/stripepay', 'stripepay'); 
$app->put('/getmembership', 'getmembership'); /* get membership  */
$app->put('/updateproduct', 'updateproduct');
$app->put('/addslide', 'addslide');
$app->put('/updateuser', 'updateuser');
$app->put('/getuser', 'getuser');
$app->put('/updatecat', 'updatecat');
$app->put('/addOrderNote', 'addOrderNote');
$app->put('/addWish', 'addWish');
$app->put('/delWish', 'delWish');
$app->put('/getmywish', 'getmywish');
$app->put('/getabout', 'getabout');
$app->put('/setabout', 'setabout');
$app->put('/getspecialnewarrival', 'getspecialnewarrival');
$app->put('/addspecial', 'addspecial');
$app->put('/delspecial', 'delspecial');
$app->put('/addnewarrival', 'addnewarrival');
$app->put('/delnewarrival', 'delnewarrival');
$app->put('/borrowingbooks', 'borrowingbooks');
$app->put('/getborrowedbooksofsingleuser', 'getborrowedbooksofsingleuser');
$app->put('/getallborrowedbooks', 'getallborrowedbooks');


$app->put('/changestatusofborrowedbooks', 'changestatusofborrowedbooks');



$app->run();


/*
    Logic for borrowing the books
     Step 1: user selects a book and sends a request to borrow the books with following parameters.
            1 ) user_id
            2 ) product_id
            3 ) borrowing_date 
     Step 2: The admin accepts or rejects the borrow request.
     Step 3: Now the book is borrowed

*/
function stripepay()
{
    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    if ($data->user_id ) {
        try {
            $amount=$data->amount;
            $token=$data->token;
            $descr=$data->descr;
            $email=$data->email;
$stripe = new \Stripe\StripeClient(
    'sk_test_51JNonFLt0g7AUZkxKvW4QFjyvNhU9V4IDF6LWIKgu52rEdrdl1L4dZ1CrcpSkvrmEjd1awY1uje2YjGN5fxbb3eX001CaFi7OU'
  );
  $message =$stripe->charges->create([
    'amount' => $amount,
    'currency' => 'eur',
    'source' => $token,
    'description' => $descr,
    
  ]);
$payload=[error =>false, message=>$message];
echo json_encode($payload);}
catch(\Exception $e) {
    $payload=[error =>true, message=>$e->getMessage()];
echo json_encode($payload);}
}
}
function borrowingbooks()
{
    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    if ($data->user_id && $data->product_id) {
        try {

            $db = getDB();



            $check_number_of_copies_query = "SELECT product_copies FROM product WHERE product_id=:product_id;";
            $stmt = $db->prepare($check_number_of_copies_query);

            $stmt->bindParam("product_id", $data->product_id, PDO::PARAM_STR);
            $stmt->execute();

            $mainCount = $stmt->rowCount();
            $product_copies_object = $stmt->fetch(PDO::FETCH_OBJ);


            if ($product_copies_object->product_copies) {

                $insert_in_book_borrowings_query = "INSERT INTO 
                        `book_borrowings`
                        ( 
                        `borrowing_date`,
                        `borrowing_expected_return_date`, 
                        borrowing_deposit_tax,
                        `product_id`, 
                        `user_id`, 
                        `product_qty`,
                        pmethod,
                        pay_status,
                        `borrowing_status`) 
                        VALUES (
                            :borrowing_date,
                            :borrowing_expected_return_date,
                            :borrowing_deposit_tax,
                            :product_id,
                            :user_id,
                            :product_qty,
                            :pmethod,
                            :payed,
                            'pending'
                        )";
                $stmt1 = $db->prepare($insert_in_book_borrowings_query);


                $current_date = date($data->fromdate);
               // $borrowingdays= $data->borrowingdays;
                //increment 2 days
               // $mod_date = strtotime($current_date . "+ $borrowingdays days");
                $data->borrowing_date = $current_date;
                $data->borrowing_expected_return_date = date($data->todate);
                $stmt1->bindParam("borrowing_date", $data->borrowing_date, PDO::PARAM_STR);
                $stmt1->bindParam("borrowing_deposit_tax", $data->borrowing_deposit_tax, PDO::PARAM_STR);
                $stmt1->bindParam("borrowing_expected_return_date", $data->borrowing_expected_return_date, PDO::PARAM_STR);
                $stmt1->bindParam("product_id", $data->product_id, PDO::PARAM_STR);
                $stmt1->bindParam("user_id", $data->user_id, PDO::PARAM_STR);
                $stmt1->bindParam("product_qty", $data->product_qty, PDO::PARAM_STR);
                $stmt1->bindParam("pmethod", $data->pmethod, PDO::PARAM_STR);
                $stmt1->bindParam("payed", $data->payed, PDO::PARAM_STR);









                if ($stmt1->execute()) {



                    $new_product_copies = $product_copies_object->product_copies - $data->product_qty;
                    $sql = "UPDATE product SET product_copies={$new_product_copies} WHERE product_id={$data->product_id}";


                    $stmt = $db->prepare($sql);
                    $stmt->execute();

                    $payload = [
                        'success' => true,
                        'message' => "Book Borrow Request sent Successfully!!"
                    ];
                } else {
                    $payload = [
                        'error' => true,
                        'message' => "There was an error while inserting the data."
                    ];
                }
            } else {
                $payload = [
                    'error' => true,
                    'message' => "There are no copies available at this time."
                ];
            }
        } catch (\Exception $e) {
            $payload = ['error' => true, 'message' => $e->getMessage()];
        }
    } else {
        $payload = ['error' => true, 'message' => 'An error occurred during process. Please try again later.'];
    }

    echo json_encode($payload);
}




function getborrowedbooksofsingleuser()
{
    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    if ($data->user_id) {
        try {

            $db = getDB();



            $total_borrowed_books_query = "SELECT * FROM `book_borrowings` join product on book_borrowings.product_id=product.product_id where book_borrowings.user_id=:user_id;";
            $stmt = $db->prepare($total_borrowed_books_query);

            $stmt->bindParam("user_id", $data->user_id, PDO::PARAM_STR);
            $stmt->execute();

            $mainCount = $stmt->rowCount();
            $product_copies_object = $stmt->fetchAll(PDO::FETCH_OBJ);

            // print_r($product_copies_object);
            // exit;

            if ($product_copies_object) {
                $payload = [
                    'success' => true,
                    'data' => $product_copies_object
                ];
            } else {
                $payload = [
                    'error' => true,
                    'message' => "There are no copies available at this time."
                ];
            }
        } catch (\Exception $e) {
            $payload = ['error' => true, 'message' => $e->getMessage()];
        }
    } else {
        $payload = ['error' => true, 'message' => 'An error occurred during process. Please try again later.'];
    }

    echo json_encode($payload);
}


function getallborrowedbooks()
{
    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());

    try {

        $db = getDB();



        $total_borrowed_books_query = "SELECT * FROM `book_borrowings` join product on book_borrowings.product_id=product.product_id join users on book_borrowings.user_id=users.user_id;";
        $stmt = $db->prepare($total_borrowed_books_query);

        $stmt->execute();

        $mainCount = $stmt->rowCount();
        $product_copies_object = $stmt->fetchAll(PDO::FETCH_OBJ);

        // print_r($product_copies_object);
        // exit;

        if ($product_copies_object) {
            $payload = [
                'success' => true,
                'data' => $product_copies_object
            ];
        } else {
            $payload = [
                'error' => true,
                'message' => "There are no copies available at this time."
            ];
        }
    } catch (\Exception $e) {
        $payload = ['error' => true, 'message' => $e->getMessage()];
    }


    echo json_encode($payload);
}

function changestatusofborrowedbooks()
{
    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    if ($data->borrowing_status && $data->book_borrowing_id) {
        try {

            $db = getDB();







            $update_product_status_query = "UPDATE book_borrowings SET borrowing_status='{$data->borrowing_status}' WHERE book_borrowing_id={$data->book_borrowing_id}";


            $stmt = $db->prepare($update_product_status_query);
            $stmt->execute();

            $payload = [
                'success' => true,
                'message' => "Book Status Changed Successfully!!"
            ];
        } catch (\Exception $e) {
            $payload = ['error' => true, 'message' => $e->getMessage()];
        }
    } else {
        $payload = ['error' => true, 'message' => 'An error occurred during process. Please try again later.'];
    }

    echo json_encode($payload);
}


/************************* USER LOGIN *************************************/
/* ### User login ### */
function login()
{
    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());

    try {
        $db = getDB();
        $userData = '';
        $sql = "SELECT user_id, name, phone, email, address, zip, city, country, code,CONCAT(user_photo,'?', TIMESTAMPDIFF(SECOND,'2020-01-01 12:01:00',now())) as user_photo, user_type FROM users WHERE email=:email and password=:password";
        $stmt = $db->prepare($sql);
        $stmt->bindParam("email", $data->email, PDO::PARAM_STR);
        $password = hash('sha256', $data->password);
        $stmt->bindParam("password", $password, PDO::PARAM_STR);

        $stmt->execute();
        $mainCount = $stmt->rowCount();
        $userData = $stmt->fetch(PDO::FETCH_OBJ);

        $db = null;
        if ($userData) {
            $userData = json_encode($userData);
            echo '{"error":false,"userData": ' . $userData . '}';
        } else {
            echo '{"error":true,"text":"Wrong email or password!"}';
        }
    } catch (PDOException $e) {
        echo '{"error":true,"text":' . $e->getMessage() . '}';
    }
}

/* ### User registration ### */
function signup()
{
    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    $email = $data->email;
    $name = $data->name;
    $phone = $data->phone;
    $password = $data->password;
    $address = $data->address;
    $city = $data->city;
    $zip = $data->zip;
    $country = $data->country;
    $code = $data->code;
    try {
        $email_check = preg_match('~^[a-zA-Z0-9._-]+@[a-zA-Z0-9._-]+\.([a-zA-Z]{2,4})$~i', $email);
        $password_check = preg_match('~^[A-Za-z0-9!@#$%^&*()_]{6,20}$~i', $password);
        //  $phone_check= preg_match('~^[0-9]{3}-[0-9]{4}-[0-9]{4}$~i', $phone);    
        if (strlen(trim($password)) > 0 && strlen(trim($email)) > 0 && $email_check > 0 && $password_check > 0) {
            $db = getDB();
            $userData = '';
            $sql = "SELECT user_id FROM users WHERE email=:email";
            $stmt = $db->prepare($sql);
            $stmt->bindParam("email", $email, PDO::PARAM_STR);
            $stmt->execute();
            $mainCount = $stmt->rowCount();
            $created = time();
            if ($mainCount == 0) {

                /*Inserting user values*/
                $sql1 = "INSERT INTO users(password,email,name,phone,address,zip,city,country,code)VALUES(:password,:email,:name,:phone,:address,:zip,:city,:country,:code)";
                $stmt1 = $db->prepare($sql1);
                $stmt1->bindParam("phone", $phone, PDO::PARAM_STR);
                $password = hash('sha256', $data->password);
                $stmt1->bindParam("password", $password, PDO::PARAM_STR);
                $stmt1->bindParam("email", $email, PDO::PARAM_STR);
                $stmt1->bindParam("name", $name, PDO::PARAM_STR);
                $stmt1->bindParam("address", $address, PDO::PARAM_STR);
                $stmt1->bindParam("zip", $zip, PDO::PARAM_STR);
                $stmt1->bindParam("country", $country, PDO::PARAM_STR);
                $stmt1->bindParam("city", $city, PDO::PARAM_STR);
                $stmt1->bindParam("code", $code, PDO::PARAM_STR);
                $stmt1->execute();

                $userData = internalUserDetails($email);
            }

            $db = null;


            if ($userData) {
                $userData = json_encode($userData);
                echo '{"error": false, "userData": ' . $userData . '}';
            } else {
                echo '{"error": true, "text":"This account already registered!"}';
            }
        } else {
            echo '{"error": true, "text":"Enter valid data!"}';
        }
    } catch (PDOException $e) {
        echo '{"error": true,"text":' . $e->getMessage() . '}';
    }
}

function loginadmin()
{

    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());

    try {

        $db = getDB();
        $userData = '';
        $sql = "SELECT * FROM admin WHERE email=:email and password=:password";
        $stmt = $db->prepare($sql);
        $stmt->bindParam("email", $data->email, PDO::PARAM_STR);
        $password = hash('sha256', $data->password);
        $stmt->bindParam("password", $password, PDO::PARAM_STR);
        $stmt->execute();
        $mainCount = $stmt->rowCount();
        $adminData = $stmt->fetch(PDO::FETCH_OBJ);

        $db = null;
        if ($adminData) {
            $adminData = json_encode($adminData);
            echo '{"error":false,"adminData": ' . $adminData . '}';
        } else {
            echo '{"error":true,"text":"Wrong email or password!"}';
        }
    } catch (PDOException $e) {
        echo '{"error":true,"text":' . $e->getMessage() . '}';
    }
}
function checkadmin()
{
    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());

    try {

        $db = getDB();
        $userData = '';
        $sql = "SELECT * FROM admin WHERE user_id=:user_id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam("user_id", $data->user_id, PDO::PARAM_STR);
        $stmt->execute();
        $mainCount = $stmt->rowCount();
        $db = null;
        if ($mainCount > 0) {
            echo '{"error":false}';
        } else {
            echo '{"error":true,"text":"Not Admin"}';
        }
    } catch (PDOException $e) {
        echo '{"error":true,"text":' . $e->getMessage() . '}';
    }
}

function signupadmin()
{
    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    $email = $data->email;
    $password = $data->password;
    try {

        $email_check = preg_match('~^[a-zA-Z0-9._-]+@[a-zA-Z0-9._-]+\.([a-zA-Z]{2,4})$~i', $email);
        $password_check = preg_match('~^[A-Za-z0-9!@#$%^&*()_]{6,20}$~i', $password);

        if (strlen(trim($password)) > 0 && strlen(trim($email)) > 0 && $email_check > 0 && $password_check > 0) {
            $db = getDB();
            $userData = '';
            $sql = "SELECT admin_id FROM admin WHERE email=:email";
            $stmt = $db->prepare($sql);
            $stmt->bindParam("email", $email, PDO::PARAM_STR);
            $stmt->execute();
            $mainCount = $stmt->rowCount();
            $created = time();
            if ($mainCount == 0) {


                $sql1 = "INSERT INTO admin(password,email)VALUES(:password,:email)";
                $stmt1 = $db->prepare($sql1);
                $password = hash('sha256', $data->password);
                $stmt1->bindParam("password", $password, PDO::PARAM_STR);
                $stmt1->bindParam("email", $email, PDO::PARAM_STR);
                $stmt1->execute();

                $adminData = internalAdminDetails($email);
            }

            $db = null;


            if ($adminData) {
                $adminData = json_encode($adminData);
                echo '{"error": false, "adminData": ' . $adminData . '}';
            } else {
                echo '{"error": true, "text":"This account already registered!"}';
            }
        } else {
            echo '{"error": true, "text":"Enter valid data!"}';
        }
    } catch (PDOException $e) {
        echo '{"error": true,"text":' . $e->getMessage() . '}';
    }
}




function addproduct()
{
    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    $cat_id = $data->cat_id;
    $product_name = $data->product_name;
    //$product_photo=$data->product_photo;
    // $product_image=$data->product_image;
    $product_description = $data->product_description;
    
    $product_price = $data->product_price;
    $borrowing_price = $data->borrowing_price;
    $product_image = "http://localhost/PHP-Slim-Restful/api/productimages/" . $product_name . ".jpg";
    try {
        /*Inserting cart values*/
        $db = getDB();
        $sql1 = "INSERT INTO product(cat_id,
        product_name,
        product_price,
        borrowing_price,
        product_image,
        product_description) VALUES(:cat_id,
        :product_name,
        :product_price,
        :borrowing_price,
        :product_image,
        :product_description)";
        $stmt1 = $db->prepare($sql1);
        $stmt1->bindParam("cat_id", $cat_id, PDO::PARAM_STR);
        $stmt1->bindParam("product_name", $product_name, PDO::PARAM_STR);
        $stmt1->bindParam("product_price", $product_price, PDO::PARAM_STR);
        $stmt1->bindParam("borrowing_price", $borrowing_price, PDO::PARAM_STR);
        $stmt1->bindParam("product_image", $product_image, PDO::PARAM_STR);
        $stmt1->bindParam("product_description", $product_description, PDO::PARAM_STR);
              $stmt1->execute();

        // $userData=internalUserDetails($product_id);
        $db = null;
        echo '{"error":false}';
    } catch (PDOException $e) {
        echo '{"error": true,"text":' . $e->getMessage() . '}';
    }
}

function updateproduct()
{
    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    $product_id = $data->product_id;
    $cat_id = $data->cat_id;
    $product_name = $data->product_name;
    $product_price = $data->product_price;
    $product_image = "http://localhost/PHP-Slim-Restful/api/productimages/" . $product_name . ".jpg";
    $product_colors = $data->product_colors;
    $product_description = $data->product_description;
    $product_material = $data->product_material;
    $product_sizes = $data->product_sizes;

    try {
        $products = '';
        $db = getDB();
        $sql = "UPDATE product SET cat_id=:cat_id,
        product_name=:product_name,
        product_price=:product_price,
        product_image=:product_image,
        product_description=:product_description 
        WHERE product_id=:product_id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam("product_id", $product_id, PDO::PARAM_STR);
        $stmt->bindParam("cat_id", $cat_id, PDO::PARAM_STR);
        $stmt->bindParam("product_name", $product_name, PDO::PARAM_STR);
        $stmt->bindParam("product_price", $product_price, PDO::PARAM_STR);
        $stmt->bindParam("product_image", $product_image, PDO::PARAM_STR);
           $stmt->bindParam("product_description", $product_description, PDO::PARAM_STR);
         $stmt->execute();
        $db = null;

        echo '{"error":false}';
    } catch (PDOException $e) {
        echo '{"error":true,"text":' . $e->getMessage() . '}';
    }
}
function returnproduct()
{
    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    $book_borrowing_id = $data->book_borrowing_id;
    $product_copies = $data->product_qty;
    $status='returned';
     $borrowing_return_date=date('Y-m-d');
 
    try {
        
        $db = getDB();
        $sql = "UPDATE book_borrowings SET borrowing_status=:borrowing_status,borrowing_return_date=:borrowing_return_date WHERE book_borrowing_id=:book_borrowing_id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam("book_borrowing_id", $book_borrowing_id, PDO::PARAM_STR);
        $stmt->bindParam("borrowing_status", $status, PDO::PARAM_STR);
        $stmt->bindParam("borrowing_return_date",   $borrowing_return_date, PDO::PARAM_STR);
         
        $stmt->execute();
       
       
        $sql1 = "UPDATE product set product_copies=product_copies + :product_copies where product_id in (select product_id from book_borrowings WHERE book_borrowing_id=:book_borrowing_id  ) ";
        $stmt1 = $db->prepare($sql1);
        $stmt1->bindParam("book_borrowing_id", $book_borrowing_id, PDO::PARAM_STR);
        $stmt1->bindParam("product_copies", $product_copies, PDO::PARAM_STR);
        $stmt1->execute();
         
        $db=null;
        echo '{"error":false}';
    } catch (Exception $e) {
        echo '{"error":true,"text":' . $e->getMessage() . '}';
    } 
}
function returndeposit()
{
    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    $book_borrowing_id = $data->book_borrowing_id;
    $borrowing_deposit_tax = $data->borrowing_deposit_tax;
    $status='finished';
     //$borrowing_return_date=date('Y-m-d');
 
    try {
        
        $db = getDB();
        $sql = "UPDATE book_borrowings SET borrowing_status=:borrowing_status,borrowing_deposit_tax=borrowing_deposit_tax - :borrowing_deposit_tax WHERE book_borrowing_id=:book_borrowing_id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam("book_borrowing_id", $book_borrowing_id, PDO::PARAM_STR);
        $stmt->bindParam("borrowing_deposit_tax",   $borrowing_deposit_tax, PDO::PARAM_STR);
        $stmt->bindParam("borrowing_status", $status, PDO::PARAM_STR);
      
        $stmt->execute();
         
        $db=null;
        echo '{"error":false}';
    } catch (Exception $e) {
        echo '{"error":true,"text":' . $e->getMessage() . '}';
    } 
}
function updatecat()
{
    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    $cat_id = $data->cat_id;
    $cat_name = $data->cat_name;
    $cat_image = "localhost/PHP-/api/catimages/" . $cat_name . ".jpg";
    try {
        $categories = '';
        $db = getDB();
        $sql = "UPDATE category SET cat_id=:cat_id,cat_name=:cat_name,cat_image=:cat_image WHERE cat_id=:cat_id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam("cat_id", $cat_id, PDO::PARAM_STR);
        $stmt->bindParam("cat_name", $cat_name, PDO::PARAM_STR);
        $stmt->bindParam("cat_image", $cat_image, PDO::PARAM_STR);

        $stmt->execute();
        $db = null;

        echo '{"error":false}';
    } catch (PDOException $e) {
        echo '{"error":true,"text":' . $e->getMessage() . '}';
    }
}
function confirmcart()
{
    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    $user_id = $data->user_id;
    $note = $data->note;
     
    try {
        $categories = '';
        $db = getDB();
        $sql = "UPDATE cart SET status=1, note=:note where user_id=:user_id and status=0 ";
        $stmt = $db->prepare($sql);
        $stmt->bindParam("user_id", $user_id, PDO::PARAM_STR);
        $stmt->bindParam("note", $note, PDO::PARAM_STR);
        
        $stmt->execute();
        $db = null;

        echo '{"error":false}';
    } catch (PDOException $e) {
        echo '{"error":true,"text":' . $e->getMessage() . '}';
    }
}





function updateuser()
{
    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    $user_id = $data->user_id;
    $name = $data->name;
    $phone = $data->phone;
    $address = $data->address;
    $city = $data->city;
    $zip = $data->zip;
    $country = $data->country;
    $code = $data->code;
    $user_photo = "http://localhost/PHP-Slim-Restful/api/usersimages/" . $data->email . '.jpg';

    try {
        $users = '';
        $db = getDB();
        $sql = "UPDATE users SET name=:name,phone=:phone,address=:address,zip=:zip,code=:code,country=:country,city=:city,user_photo=:user_photo WHERE user_id=:user_id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam("user_id", $user_id, PDO::PARAM_STR);
        $stmt->bindParam("name", $name, PDO::PARAM_STR);
        $stmt->bindParam("address", $address, PDO::PARAM_STR);
        $stmt->bindParam("phone", $phone, PDO::PARAM_STR);
        $stmt->bindParam("zip", $zip, PDO::PARAM_STR);
        $stmt->bindParam("country", $country, PDO::PARAM_STR);
        $stmt->bindParam("city", $city, PDO::PARAM_STR);
        $stmt->bindParam("code", $code, PDO::PARAM_STR);
        $stmt->bindParam("user_photo", $user_photo, PDO::PARAM_STR);
        $stmt->execute();
        $db = null;
        echo '{"error":false}';
    } catch (PDOException $e) {
        echo '{"error":true,"text":' . $e->getMessage() . '}';
    }
}




function addcat()
{
    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());

    $cat_name = $data->cat_name;
    $cat_image = "http://localhost/PHP-Slim-Restful/api/catimages/" . $cat_name . ".jpg";
    try {
        /*Inserting new cat values*/
        $db = getDB();
        $sql1 = "INSERT INTO category(cat_name,cat_image) VALUES(:cat_name,:cat_image)";
        $stmt1 = $db->prepare($sql1);
        $stmt1->bindParam("cat_name", $cat_name, PDO::PARAM_STR);
        $stmt1->bindParam("cat_image", $cat_image, PDO::PARAM_STR);
        $stmt1->execute();


        $db = null;
        echo '{"error":false}';
    } catch (PDOException $e) {
        echo '{"error": true,"text":' . $e->getMessage() . '}';
    }
}
function addmembership()
{
    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
$user_id=$data->user_id;
   // $cat_name = $data->cat_name;
   // $cat_image = "http://localhost/PHP-Slim-Restful/api/catimages/" . $cat_name . ".jpg";
    try {
        /*Inserting new cat values*/
        $db = getDB();
        $sql1 = "INSERT INTO member(user_id) VALUES(:user_id)";
        $stmt1 = $db->prepare($sql1);
        $stmt1->bindParam("user_id", $user_id, PDO::PARAM_INT);
         $stmt1->execute();


        $db = null;
        echo '{"error":false}';
    } catch (PDOException $e) {
        echo '{"error": true,"text":' . $e->getMessage() . '}';
    }
}

function addslide()
{
    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    $name = $data->name;
    $imagepath = "http://localhost/PHP-Slim-Restful/api/productimages/" . $name . ".jpg";
    try {
        /*Inserting new cat values*/
        $db = getDB();
        $sql1 = "INSERT INTO slides(name,imagepath) VALUES(:name,:imagepath)";
        $stmt1 = $db->prepare($sql1);
        $stmt1->bindParam("name", $name, PDO::PARAM_STR);
        $stmt1->bindParam("imagepath", $imagepath, PDO::PARAM_STR);
        $stmt1->execute();


        $db = null;
        echo '{"error":false}';
    } catch (PDOException $e) {
        echo '{"error": true,"text":' . $e->getMessage() . '}';
    }
}




/*function email() {
    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    $email=$data->email;

    try {
       
        $email_check = preg_match('~^[a-zA-Z0-9._-]+@[a-zA-Z0-9._-]+\.([a-zA-Z]{2,4})$~i', $email);
       
        if (strlen(trim($email))>0 && $email_check>0)
        {
            $db = getDB();
            $userData = '';
            $sql = "SELECT user_id FROM emailUsers WHERE email=:email";
            $stmt = $db->prepare($sql);
            $stmt->bindParam("email", $email,PDO::PARAM_STR);
            $stmt->execute();
            $mainCount=$stmt->rowCount();
            $created=time();
            if($mainCount==0)
            {
                
                
                $sql1="INSERT INTO emailUsers(email)VALUES(:email)";
                $stmt1 = $db->prepare($sql1);
                $stmt1->bindParam("email", $email,PDO::PARAM_STR);
                $stmt1->execute();
                
                
            }
            $userData=internalEmailDetails($email);
            $db = null;
            if($userData){
               $userData = json_encode($userData);
                echo '{"userData": ' .$userData . '}';
            } else {
               echo '{"error":{"text":"Enter valid dataaaa"}}';
            }
        }
        else{
            echo '{"error":{"text":"Enter valid data"}}';
        }
    }
    
    catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}*/


/* ### internal Username Details ### */
function internalUserDetails($input)
{

    try {
        $db = getDB();
        $sql = "SELECT * FROM users WHERE email=:input";
        $stmt = $db->prepare($sql);
        $stmt->bindParam("input", $input, PDO::PARAM_STR);
        $stmt->execute();
        $usernameDetails = $stmt->fetch(PDO::FETCH_OBJ);
        $db = null;
        return $usernameDetails;
    } catch (PDOException $e) {
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }
}



function internalAdminDetails($input)
{

    try {
        $db = getDB();
        $sql = "SELECT * FROM admin WHERE email=:input";
        $stmt = $db->prepare($sql);
        $stmt->bindParam("input", $input, PDO::PARAM_STR);
        $stmt->execute();
        $adminnameDetails = $stmt->fetch(PDO::FETCH_OBJ);
        $db = null;
        return $adminnameDetails;
    } catch (PDOException $e) {
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }
}


function getuser()
{

    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());

    try {

        $db = getDB();
        $userData = '';
        $sql = "SELECT user_id, name, phone, email, address, zip, city, country, code, CONCAT(user_photo,'?', TIMESTAMPDIFF(SECOND,'2020-01-01 12:01:00',now())) as user_photo FROM users WHERE user_id=:user_id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam("user_id", $data->user_id, PDO::PARAM_STR);
        $stmt->execute();
        $mainCount = $stmt->rowCount();
        $userData = $stmt->fetch(PDO::FETCH_OBJ);

        $db = null;
        if ($userData) {
            $userData = json_encode($userData);
            echo '{"error":false,"userData": ' . $userData . '}';
        } else {
            echo '{"error":true,"text":"Wrong email or password!"}';
        }
    } catch (PDOException $e) {
        echo '{"error":true,"text":' . $e->getMessage() . '}';
    }
}



function getslides()
{
    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());

    try {
        $slides = '';
        $db = getDB();
        $sql = "SELECT *,CONCAT(imagepath,'?', TIMESTAMPDIFF(SECOND,'2020-01-01 12:01:00',now())) as imagepath FROM slides";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $slides = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        if ($slides) {
            echo '{"error":false,"slides": ' . json_encode($slides) . '}';
            exit();
        } else {
            echo '{"error":true,"slides": ""}';
            exit();
        }
    } catch (PDOException $e) {
        echo '{"error":true,"text":' . $e->getMessage() . '}';
    }
}
function getcat()
{
    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());

    try {
        $category = '';
        $db = getDB();
        $sql = "SELECT *,CONCAT(cat_image,'?', TIMESTAMPDIFF(SECOND,'2020-01-01 12:01:00',now())) as cat_image FROM category";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $category = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        if ($category)
            echo '{"error":false,"category": ' . json_encode($category) . '}';
        else
            echo '{"error":true,"category": ""}';
    } catch (PDOException $e) {
        echo '{"error":true,"text":' . $e->getMessage() . '}';
    }
}


function getproducts()
{
    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());

    try {
        $products = '';
        $db = getDB();
        $sql = "SELECT *,CONCAT(product_image,'?', TIMESTAMPDIFF(SECOND,'2020-01-01 12:01:00',now())) as product_image FROM product";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $products = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        if ($products)
            echo '{"error":false,"products": ' . json_encode($products) . '}';
        else
            echo '{"error":true,"products": ""}';
    } catch (PDOException $e) {
        echo '{"error":true,"text":' . $e->getMessage() . '}';
    }
}



function getproductofcat()
{
    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    $cat_id = $data->cat_id;
    $user_id = $data->user_id;

    try {
        $products = '';
        $db = getDB();
        $sql = "SELECT B.product_id,B.cat_id,B.product_name,B.product_price,CONCAT(B.product_image,'?', TIMESTAMPDIFF(SECOND,'2020-01-01 12:01:00',now())) as product_image, IF(A.user_id =:user_id, true, false) as wishflag FROM product B LEFT JOIN wishlist A ON A.product_id=B.product_id AND A.user_id =:user_id WHERE B.cat_id=:cat_id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam("cat_id", $cat_id, PDO::PARAM_STR);
        $stmt->bindParam("user_id", $user_id, PDO::PARAM_STR);
        $stmt->execute();
        $products = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        if ($products)
            echo '{"error":false,"products": ' . json_encode($products) . '}';
        else
            echo '{"error":true,"products": ""}';
    } catch (PDOException $e) {
        echo '{"error":true,"text":' . $e->getMessage() . '}';
    }
}


function getproducdetails()
{
    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    $product_id = $data->product_id;
    try {
        $products = '';
        $db = getDB();
        $sql = "SELECT *,CONCAT(product_image,'?', TIMESTAMPDIFF(SECOND,'2020-01-01 12:01:00',now())) as product_image FROM product WHERE product_id=:product_id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam("product_id", $product_id, PDO::PARAM_STR);
        $stmt->execute();
        $products = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        if ($products)
            echo '{"error":false,"products": ' . json_encode($products) . '}';
        else
            echo '{"error":true,"products": ""}';
    } catch (PDOException $e) {
        echo '{"error":true,"text":' . $e->getMessage() . '}';
    }
}
function addOrderNote()
{
    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    $user_id = $data->user_id;
    $ordernote = $data->ordernote;
    try {
        $carts = '';
        $db = getDB();
        $sql = "UPDATE cart SET note=:ordernote WHERE user_id=:user_id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam("user_id", $user_id, PDO::PARAM_STR);
        $stmt->bindParam("ordernote", $ordernote, PDO::PARAM_STR);
        $stmt->execute();
        $db = null;

        echo '{"error":false}';
    } catch (PDOException $e) {
        echo '{"error":true,"text":' . $e->getMessage() . '}';
    }
}
function getcart()
{
    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    $user_id = $data->user_id;
    try {
        $carts = '';
        $db = getDB();
        $sql = "SELECT *, product.product_price*cart.product_qty AS price_all_qty FROM cart LEFT JOIN product ON cart.product_id=product.product_id WHERE cart.user_id=:user_id and status=0";
        $stmt = $db->prepare($sql);
        $stmt->bindParam("user_id", $user_id, PDO::PARAM_STR);
        $stmt->execute();
        $carts = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        if ($carts)
            echo '{"error":false,"carts": ' . json_encode($carts) . '}';
        else
            echo '{"error":true,"carts": ""}';
    } catch (PDOException $e) {
        echo '{"error":true,"text":' . $e->getMessage() . '}';
    }
}
function getcartbyproduct()
{
    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    $user_id = $data->user_id;
    $product_id = $data->product_id;
    try {
        $carts = '';
        $db = getDB();
        $sql = "SELECT * FROM cart  WHERE cart.user_id=:user_id and product_id=:product_id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam("user_id", $user_id, PDO::PARAM_STR);
        $stmt->bindParam("product_id", $product_id, PDO::PARAM_STR);
        $stmt->execute();
        $carts = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        if ($carts)
            echo '{"error":false,"carts": ' . json_encode($carts) . '}';
        else
            echo '{"error":true,"carts": ""}';
    } catch (PDOException $e) {
        echo '{"error":true,"text":' . $e->getMessage() . '}';
    }
}
function getmembership()
{
    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    $user_id = $data->user_id;
    try {
        $carts = '';
        $db = getDB();
        $sql = "SELECT *  FROM member WHERE user_id=:user_id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam("user_id", $user_id, PDO::PARAM_STR);
        $stmt->execute();
        $carts = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        if ($carts)
            echo '{"error":false,"member": ' . json_encode($carts) . '}';
        else
            echo '{"error":true,"member": ""}';
    } catch (PDOException $e) {
        echo '{"error":true,"text":' . $e->getMessage() . '}';
    }
}
function addtocart()
{
    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    $user_id = $data->user_id;
    $product_id = $data->product_id;
    $product_color = $data->product_color;
    $product_size = $data->product_size;
    $product_qty = $data->product_qty;
    try { 
        $db = getDB();
        $sql = "SELECT * FROM cart  WHERE cart.user_id=:user_id and product_id=:product_id and status=0 ";
        $stmt = $db->prepare($sql);
        $stmt->bindParam("user_id", $user_id, PDO::PARAM_STR);
        $stmt->bindParam("product_id", $product_id, PDO::PARAM_STR);
        $stmt->execute();
        $carts = $stmt->fetchAll(PDO::FETCH_OBJ);
       
        if ($carts){
            
        /*Inserting cart values*/
       
        $sql1 = "UPDATE cart set product_qty=product_qty + :product_qty where product_id=:product_id and user_id=:user_id and status=0 ";
        $stmt1 = $db->prepare($sql1);
        $stmt1->bindParam("user_id", $user_id, PDO::PARAM_STR);
        $stmt1->bindParam("product_id", $product_id, PDO::PARAM_STR);
        $stmt1->bindParam("product_qty", $product_qty, PDO::PARAM_STR);
        $stmt1->execute();

        
        // $userData=internalUserDetails($product_id);
         echo '{"error":false}';
        } else {
        /*Inserting cart values*/
       
        $sql1 = "INSERT INTO cart(user_id,product_id,product_qty) VALUES(:user_id,:product_id,:product_qty)";
        $stmt1 = $db->prepare($sql1);
        $stmt1->bindParam("user_id", $user_id, PDO::PARAM_STR);
        $stmt1->bindParam("product_id", $product_id, PDO::PARAM_STR);
          $stmt1->bindParam("product_qty", $product_qty, PDO::PARAM_STR);
        $stmt1->execute();

        // $userData=internalUserDetails($product_id);
       
        echo '{"error":false}';}
        $sql2 = "UPDATE product set product_copies=product_copies - :product_qty where product_id=:product_id and product_copies > :product_qty";
        $stmt2 = $db->prepare($sql2);
        // $stmt2->bindParam("user_id", $user_id, PDO::PARAM_STR);
        $stmt2->bindParam("product_id", $product_id, PDO::PARAM_STR);
        $stmt2->bindParam("product_qty", $product_qty, PDO::PARAM_STR);
        $stmt2->execute();
        $db=null;
    } catch (PDOException $e) {
        echo '{"error": true,"text":' . $e->getMessage() . '}';
    }
}

function addqtytocart()
{
    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    $cart_id = $data->cart_id;
    $product_id = $data->product_id;
    try {
        $carts = '';
        $db = getDB();
        $sql = "UPDATE cart SET product_qty = product_qty + 1 WHERE cart_id=:cart_id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam("cart_id", $cart_id, PDO::PARAM_STR);
        $stmt->execute();
        $sql2 = "UPDATE product set product_copies=product_copies - 1 where product_id=:product_id and product_copies>0";
        $stmt2 = $db->prepare($sql2);
        // $stmt2->bindParam("user_id", $user_id, PDO::PARAM_STR);
        $stmt2->bindParam("product_id", $product_id, PDO::PARAM_STR);
       // $stmt2->bindParam("product_qty", $product_qty, PDO::PARAM_STR);
        $stmt2->execute();
        $db=null;

        echo '{"error":false}';
    } catch (PDOException $e) {
        echo '{"error":true,"text":' . $e->getMessage() . '}';
    }
}


function removeqtytocart()
{
    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    $cart_id = $data->cart_id;
    $product_id = $data->product_id;
    try {
        $carts = '';
        $db = getDB();
        $sql = "UPDATE cart SET product_qty = product_qty - 1 WHERE cart_id=:cart_id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam("cart_id", $cart_id, PDO::PARAM_STR);
        $stmt->execute();

        $sql2 = "UPDATE product set product_copies=product_copies + 1 where product_id=:product_id";
        $stmt2 = $db->prepare($sql2);
        // $stmt2->bindParam("user_id", $user_id, PDO::PARAM_STR);
        $stmt2->bindParam("product_id", $product_id, PDO::PARAM_STR);
       // $stmt2->bindParam("product_qty", $product_qty, PDO::PARAM_STR);
        $stmt2->execute();
        $db=null;

        echo '{"error":false}';
    } catch (PDOException $e) {
        echo '{"error":true,"text":' . $e->getMessage() . '}';
    }
}


function removecart()
{
    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    $cart_id = $data->cart_id;
    try {
        $carts = '';
        $db = getDB();
        $sql = "DELETE FROM cart  WHERE cart_id=:cart_id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam("cart_id", $cart_id, PDO::PARAM_STR);
        $stmt->execute();
        //$carts = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        //if($carts)
        echo '{"error":false}';
        //else
        //echo '{"error":true,"carts": ""}';

    } catch (PDOException $e) {
        echo '{"error":true,"text":' . $e->getMessage() . '}';
    }
}

function removeslide()
{
    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    $id = $data->id;
    try {
        $slides = '';
        $db = getDB();
        $sql = "DELETE FROM slides  WHERE id=:id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam("id", $id, PDO::PARAM_STR);
        $stmt->execute();
        $db = null;
        echo '{"error":false}';
    } catch (PDOException $e) {
        echo '{"error":true,"text":' . $e->getMessage() . '}';
    }
}
function removeproduct()
{
    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    $product_id = $data->product_id;
    try {
        $products = '';
        $db = getDB();
        $sql = "DELETE FROM cart WHERE product_id=:product_id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam("product_id", $product_id, PDO::PARAM_STR);
        $stmt->execute();
        $sql = "DELETE FROM wishlist WHERE product_id=:product_id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam("product_id", $product_id, PDO::PARAM_STR);
        $stmt->execute();
        $sql = "DELETE FROM newarrivals WHERE product_id=:product_id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam("product_id", $product_id, PDO::PARAM_STR);
        $stmt->execute();
        $sql = "DELETE FROM specialpieces WHERE product_id=:product_id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam("product_id", $product_id, PDO::PARAM_STR);
        $stmt->execute();
        $sql = "DELETE FROM product WHERE product_id=:product_id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam("product_id", $product_id, PDO::PARAM_STR);
        $stmt->execute();
        $db = null;
        echo '{"error":false}';
    } catch (PDOException $e) {
        echo '{"error":true,"text":' . $e->getMessage() . '}';
    }
}
function removecat()
{
    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    $cat_id = $data->cat_id;
    try {
        $categories = '';
        $db = getDB();
        $sql = "DELETE FROM category  WHERE cat_id=:cat_id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam("cat_id", $cat_id, PDO::PARAM_STR);
        $stmt->execute();
        $db = null;
        echo '{"error":false}';
    } catch (PDOException $e) {
        echo '{"error":true,"text":"this category has products"}';
    }
}


function sendEmailto404()
{
    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody(), true);
    $data = $data["order"];
    $user_id = $data[0]['user_id'];
    $ordernote = $data[0]['note'];
    try {

        $db = getDB();
        $userData = '';
        $sql = "SELECT user_id, name, phone, email, address, zip, city, country, code, user_photo FROM users WHERE user_id=:user_id LIMIT 1";
        $stmt = $db->prepare($sql);
        $stmt->bindParam("user_id", $user_id, PDO::PARAM_STR);
        $stmt->execute();
        $userData = $stmt->fetch(PDO::FETCH_OBJ);
        $db = null;
        // echo '{"error":true,'. $userData->name.'}';
    } catch (PDOException $e) {
        echo '{"error":true,"text":' . $e->getMessage() . '}';
    }
    $message = '<html><body> <center>';
    $message .= '<h2>404Gallery Order</h2>';
    $message .= '<img src="https://msi-cs.com/404gallery/api/404images/404gallery.png" width="200px" />';
    $message .= '<h3>User Details:</h3>';
    $message .= '<p>Name: ' . $userData->name . '</p>';
    $message .= '<p>Email: ' . $userData->email . '		PhoneNo: ' . $userData->code . $userData->phone . '</p>';
    $message .= '<p>Address: ' . $userData->address . '</p>';
    $message .= '<p>Zip Code: ' . $userData->zip . ',		City:' . $userData->city . ',		Country:' . $userData->country . '</p>';
    $message .= '<h3>Order Details</h3>';
    $message .= '<table rules="all" style="border-color: #666;" cellpadding="10">';
    $message .= "<tr style='background: #FF326A; color:#ffffff'><th>Product name</th><th>Product Color</th></th><th>Product Quantity</th></th><th>Product Size</th></th><th>Product Price</th></tr>";
    //echo '{"error":foalse'. $data[0]['user_id'] .'}';
    $total = 0;
    foreach ($data as $item) {
        $db = getDB();
        $sql = "DELETE FROM cart  WHERE cart_id=:cart_id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam("cart_id", $item['cart_id'], PDO::PARAM_STR);
        $stmt->execute();
        $total += (int)$item['price_all_qty'];
        $message .= "<tr><td>" . $item['product_name'] . "</td><td>" . $item['product_color'] . "</td></td><td>" . $item['product_qty'] . "</td></td><td>" . $item['product_size'] . "</td></td><td>" . $item['product_price'] . " x " . $item['product_qty'] . " = " . $item['price_all_qty'] . "</td></tr>";
        //echo "I'd like ".$item['user_id']." waffles";
    }
    $message .= "</table>";
    $message .= "<h4 style='width: fit-content; padding: 5px 15px;background: #FF326A; color:#ffffff; border-radius: 20px;'> Total: EGP " . $total . " </h4><h4> Order Note: " . $ordernote . "</h4>";

    $message .= "</center></body></html>";
    try {

        $db = getDB();
        $adminData = '';
        $sql = "SELECT admin_email FROM aboutus LIMIT 1";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $adminData = $stmt->fetch(PDO::FETCH_OBJ);
        $db = null;
        // echo '{"error":true,'. $userData->name.'}';
    } catch (PDOException $e) {
        echo '{"error":true,"text":' . $e->getMessage() . '}';
    }

    /*try {
            $carts = '';
            $db = getDB();
            $sql = "INSERT INTO confirmedorder (order_json)VALUES (:order);";
            $stmt = $db->prepare($sql);  
            $stmt->bindParam("order", json_encode($data),PDO::PARAM_STR);
            $stmt->execute();
            //$carts = $stmt->fetchAll(PDO::FETCH_OBJ);
            $db = null;
            //if($carts)
            echo '{"error":false}';
            //else
            //echo '{"error":true,"carts": ""}';
       
    } catch(PDOException $e) {
        echo '{"error":true,"text":'. $e->getMessage() .'}';
    }*/
    $to = $adminData->admin_email . "," . $userData->email;
    //$to = "ibrahimsoliman97@gmail.com";
    $subject = "404 Gallery Order " . $userData->name;
    $headers = "MIME-Version: 1.0" . "\r\n" . "From: decorationgallery404@gmail.com" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    mail($to, $subject, $message, $headers);
    echo '{"error":false}';
}

function searchproduct()
{
    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    $product_name = $data->product_name;
    try {
        $products = '';
        $db = getDB();
        $sql = "SELECT product_id, product_name FROM product WHERE product_name LIKE '%{$product_name}%'";
        $stmt = $db->prepare($sql);
        //$stmt->bindParam("?",  '%' . $product_name . '%');			
        $stmt->execute();
        $products = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        if ($products)
            echo '{"error":false,"products": ' . json_encode($products) . '}';
        else
            echo '{"error":true,"products": ""}';
    } catch (PDOException $e) {
        echo '{"error":true,"text":' . $e->getMessage() . '}';
    }
}


function addWish()
{
    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    $product_id = $data->product_id;
    $user_id = $data->user_id;
    try {
        $products = '';
        $db = getDB();
        $sql = "INSERT INTO wishlist(user_id,product_id) VALUES(:user_id,:product_id)";
        $stmt = $db->prepare($sql);
        $stmt->bindParam("user_id", $user_id, PDO::PARAM_STR);
        $stmt->bindParam("product_id", $product_id, PDO::PARAM_STR);
        $stmt->execute();
        $db = null;
        echo '{"error":false}';
    } catch (PDOException $e) {
        echo '{"error":true,"text":' . $e->getMessage() . '}';
    }
}
function delWish()
{
    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    $product_id = $data->product_id;
    $user_id = $data->user_id;
    try {
        $products = '';
        $db = getDB();
        $sql = "DELETE FROM wishlist WHERE user_id=:user_id AND product_id=:product_id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam("user_id", $user_id, PDO::PARAM_STR);
        $stmt->bindParam("product_id", $product_id, PDO::PARAM_STR);
        $stmt->execute();
        $db = null;
        echo '{"error":false}';
    } catch (PDOException $e) {
        echo '{"error":true,"text":' . $e->getMessage() . '}';
    }
}
function newarrivals()
{
    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    $user_id = $data->user_id;

    try {
        $carts = '';
        $db = getDB();
        $sql = "SELECT product.product_id,product.cat_id,product.product_name,product.product_price,CONCAT(product.product_image,'?', TIMESTAMPDIFF(SECOND,'2020-01-01 12:01:00',now())) as product_image, IF(wishlist.user_id =:user_id, true, false) as wishflag FROM newarrivals LEFT JOIN product ON newarrivals.product_id=product.product_id LEFT JOIN wishlist ON wishlist.product_id=product.product_id AND wishlist.user_id =:user_id ";
        $stmt = $db->prepare($sql);
        $stmt->bindParam("user_id", $user_id, PDO::PARAM_STR);
        $stmt->execute();
        $carts = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        if ($carts)
            echo '{"error":false,"carts": ' . json_encode($carts) . '}';
        else
            echo '{"error":true,"carts": ""}';
    } catch (PDOException $e) {
        echo '{"error":true,"text":' . $e->getMessage() . '}';
    }
}

function specialpieces()
{
    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    $product_id = $data->product_id;
    $user_id = $data->user_id;
    try {
        $carts = '';
        $db = getDB();
        $sql = "SELECT product.product_id,product.cat_id, product.product_name,product.product_price,CONCAT(product.product_image,'?', TIMESTAMPDIFF(SECOND,'2020-01-01 12:01:00',now())) as product_image , IF(wishlist.user_id =:user_id, true, false) as wishflag FROM specialpieces LEFT JOIN product ON specialpieces.product_id=product.product_id LEFT JOIN wishlist ON wishlist.product_id=product.product_id AND wishlist.user_id =:user_id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam("product_id", $product_id, PDO::PARAM_STR);
        $stmt->bindParam("user_id", $user_id, PDO::PARAM_STR);
        $stmt->execute();
        $carts = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        if ($carts)
            echo '{"error":false,"carts": ' . json_encode($carts) . '}';
        else
            echo '{"error":true,"carts": ""}';
    } catch (PDOException $e) {
        echo '{"error":true,"text":' . $e->getMessage() . '}';
    }
}


function getmywish()
{
    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    $user_id = $data->user_id;
    try {
        $products = '';
        $db = getDB();
        $sql = "SELECT product.product_id,product.cat_id, product.product_name,product.product_price,CONCAT(product.product_image,'?', TIMESTAMPDIFF(SECOND,'2020-01-01 12:01:00',now())) as product_image, true as wishflag FROM wishlist LEFT JOIN product ON wishlist.product_id=product.product_id WHERE wishlist.user_id=:user_id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam("user_id", $user_id, PDO::PARAM_STR);
        $stmt->execute();
        $products = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        if ($products)
            echo '{"error":false,"products": ' . json_encode($products) . '}';
        else
            echo '{"error":true,"products": ""}';
    } catch (PDOException $e) {
        echo '{"error":true,"text":' . $e->getMessage() . '}';
    }
}

function getabout()
{
    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    try {
        $products = '';
        $db = getDB();
        $sql = "SELECT * FROM aboutus LIMIT  1";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $products = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        if ($products)
            echo '{"error":false,"about": ' . json_encode($products) . '}';
        else
            echo '{"error":true,"products": ""}';
    } catch (PDOException $e) {
        echo '{"error":true,"text":' . $e->getMessage() . '}';
    }
}
function setabout()
{
    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    $text = $data->text;
    $phone = $data->phone;
    $insta = $data->insta;
    $face = $data->face;
    $email = $data->email;
    $address = $data->address;
    $admin_email = $data->admin_email;

    try {
        $carts = '';
        $db = getDB();
        $sql = "UPDATE aboutus SET text=:text,phone=:phone,insta=:insta,face=:face,email=:email,address=:address, admin_email=:admin_email WHERE shop_id=1";
        $stmt = $db->prepare($sql);
        $stmt->bindParam("text", $text, PDO::PARAM_STR);
        $stmt->bindParam("phone", $phone, PDO::PARAM_STR);
        $stmt->bindParam("insta", $insta, PDO::PARAM_STR);
        $stmt->bindParam("face", $face, PDO::PARAM_STR);
        $stmt->bindParam("email", $email, PDO::PARAM_STR);
        $stmt->bindParam("address", $address, PDO::PARAM_STR);
        $stmt->bindParam("admin_email", $admin_email, PDO::PARAM_STR);
        $stmt->execute();
        $db = null;

        echo '{"error":false}';
    } catch (PDOException $e) {
        echo '{"error":true,"text":' . $e->getMessage() . '}';
    }
}
function getspecialnewarrival()
{
    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    try {
        $products = '';
        $db = getDB();
        $sql = "SELECT product.product_id,product.cat_id, product.product_name,product.product_price,CONCAT(product.product_image,'?', TIMESTAMPDIFF(SECOND,'2020-01-01 12:01:00',now())) as product_image, IF(specialpieces.product_id=product.product_id, true, false) as specialproduct, IF(newarrivals.product_id =product.product_id, true, false) as newarrvialproduct FROM product LEFT JOIN specialpieces ON specialpieces.product_id=product.product_id LEFT JOIN newarrivals ON newarrivals.product_id=product.product_id;";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $products = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        if ($products)
            echo '{"error":false,"products": ' . json_encode($products) . '}';
        else
            echo '{"error":true,"products": ""}';
    } catch (PDOException $e) {
        echo '{"error":true,"text":' . $e->getMessage() . '}';
    }
}

function addspecial()
{
    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    $product_id = $data->product_id;
    try {
        $products = '';
        $db = getDB();
        $sql = "INSERT INTO specialpieces(product_id) VALUES(:product_id)";
        $stmt = $db->prepare($sql);
        $stmt->bindParam("product_id", $product_id, PDO::PARAM_STR);
        $stmt->execute();
        $db = null;
        echo '{"error":false}';
    } catch (PDOException $e) {
        echo '{"error":true,"text":' . $e->getMessage() . '}';
    }
}
function delspecial()
{
    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    $product_id = $data->product_id;
    try {
        $products = '';
        $db = getDB();
        $sql = "DELETE FROM specialpieces WHERE product_id=:product_id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam("product_id", $product_id, PDO::PARAM_STR);
        $stmt->execute();
        $db = null;
        echo '{"error":false}';
    } catch (PDOException $e) {
        echo '{"error":true,"text":' . $e->getMessage() . '}';
    }
}

function addnewarrival()
{
    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    $product_id = $data->product_id;
    try {
        $products = '';
        $db = getDB();
        $sql = "INSERT INTO newarrivals(product_id) VALUES(:product_id)";
        $stmt = $db->prepare($sql);
        $stmt->bindParam("product_id", $product_id, PDO::PARAM_STR);
        $stmt->execute();
        $db = null;
        echo '{"error":false}';
    } catch (PDOException $e) {
        echo '{"error":true,"text":' . $e->getMessage() . '}';
    }
}
function delnewarrival()
{
    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    $product_id = $data->product_id;
    try {
        $products = '';
        $db = getDB();
        $sql = "DELETE FROM newarrivals WHERE product_id=:product_id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam("product_id", $product_id, PDO::PARAM_STR);
        $stmt->execute();
        $db = null;
        echo '{"error":false}';
    } catch (PDOException $e) {
        echo '{"error":true,"text":' . $e->getMessage() . '}';
    }
}
function feed()
{
    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    $user_id = $data->user_id;
    $token = $data->token;
    $lastCreated = $data->lastCreated;
    $systemToken = apiToken($user_id);

    try {

        if ($systemToken == $token) {
            $feedData = '';
            $db = getDB();
            if ($lastCreated) {
                $sql = "SELECT * FROM feed WHERE user_id_fk=:user_id AND created < :lastCreated ORDER BY feed_id DESC LIMIT 5";
                $stmt = $db->prepare($sql);
                $stmt->bindParam("user_id", $user_id, PDO::PARAM_INT);
                $stmt->bindParam("lastCreated", $lastCreated, PDO::PARAM_STR);
            } else {
                $sql = "SELECT * FROM feed WHERE user_id_fk=:user_id ORDER BY feed_id DESC LIMIT 5";
                $stmt = $db->prepare($sql);
                $stmt->bindParam("user_id", $user_id, PDO::PARAM_INT);
            }
            $stmt->execute();
            $feedData = $stmt->fetchAll(PDO::FETCH_OBJ);

            $db = null;

            if ($feedData)
                echo '{"feedData": ' . json_encode($feedData) . '}';
            else
                echo '{"feedData": ""}';
        } else {
            echo '{"error":{"text":"No access"}}';
        }
    } catch (PDOException $e) {
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }
}

function feedUpdate()
{

    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    $user_id = $data->user_id;
    $token = $data->token;
    $feed = $data->feed;

    $systemToken = apiToken($user_id);

    try {

        if ($systemToken == $token) {


            $feedData = '';
            $db = getDB();
            $sql = "INSERT INTO feed ( feed, created, user_id_fk) VALUES (:feed,:created,:user_id)";
            $stmt = $db->prepare($sql);
            $stmt->bindParam("feed", $feed, PDO::PARAM_STR);
            $stmt->bindParam("user_id", $user_id, PDO::PARAM_INT);
            $created = time();
            $stmt->bindParam("created", $created, PDO::PARAM_INT);
            $stmt->execute();



            $sql1 = "SELECT * FROM feed WHERE user_id_fk=:user_id ORDER BY feed_id DESC LIMIT 1";
            $stmt1 = $db->prepare($sql1);
            $stmt1->bindParam("user_id", $user_id, PDO::PARAM_INT);
            $stmt1->execute();
            $feedData = $stmt1->fetch(PDO::FETCH_OBJ);


            $db = null;
            echo '{"feedData": ' . json_encode($feedData) . '}';
        } else {
            echo '{"error":{"text":"No access"}}';
        }
    } catch (PDOException $e) {
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }
}



function feedDelete()
{
    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    $user_id = $data->user_id;
    $token = $data->token;
    $feed_id = $data->feed_id;

    $systemToken = apiToken($user_id);

    try {

        if ($systemToken == $token) {
            $feedData = '';
            $db = getDB();
            $sql = "Delete FROM feed WHERE user_id_fk=:user_id AND feed_id=:feed_id";
            $stmt = $db->prepare($sql);
            $stmt->bindParam("user_id", $user_id, PDO::PARAM_INT);
            $stmt->bindParam("feed_id", $feed_id, PDO::PARAM_INT);
            $stmt->execute();


            $db = null;
            echo '{"success":{"text":"Feed deleted"}}';
        } else {
            echo '{"error":{"text":"No access"}}';
        }
    } catch (PDOException $e) {
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }
}
/* User Details */
function userImage()
{
    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    $user_id = $data->user_id;
    $token = $data->token;
    $imageB64 = $data->imageB64;
    $systemToken = apiToken($user_id);
    try {
        if (1) {
            $db = getDB();
            $sql = "INSERT INTO imagesData(b64,user_id_fk) VALUES(:b64,:user_id)";
            $stmt = $db->prepare($sql);
            $stmt->bindParam("user_id", $user_id, PDO::PARAM_INT);
            $stmt->bindParam("b64", $imageB64, PDO::PARAM_STR);
            $stmt->execute();
            $db = null;
            echo '{"success":{"status":"uploaded"}}';
        } else {
            echo '{"error":{"text":"No access"}}';
        }
    } catch (PDOException $e) {
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }
}


function getImages()
{
    $request = \Slim\Slim::getInstance()->request();
    $data = json_decode($request->getBody());
    $user_id = $data->user_id;
    $token = $data->token;

    $systemToken = apiToken($user_id);
    try {
        if (1) {
            $db = getDB();
            $sql = "SELECT b64 FROM imagesData";
            $stmt = $db->prepare($sql);

            $stmt->execute();
            $imageData = $stmt->fetchAll(PDO::FETCH_OBJ);
            $db = null;
            echo '{"imageData": ' . json_encode($imageData) . '}';
        } else {
            echo '{"error":{"text":"No access"}}';
        }
    } catch (PDOException $e) {
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }
}
