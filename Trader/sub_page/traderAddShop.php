<?php 
include '../../connect.php';
session_start();
if(!isset($_SESSION['role']) || $_SESSION['role']!='Trader')
{
    header("location:../../Session/login.php"); 
}

if($_SESSION['log']==0)
{
header('location:../../Session/signup_extra/resetPassword.php?sess="first"');
}
$trID= $_SESSION['id'];
$succession="";
    if(isset($_GET['set']))
    {
        $succession="Pervious Data Has Been Saved, please continue adding the next shop.";

        $no = $_GET['no'];
    }
    
    $count=0;
    $email_error="";
    $exist_email=false;
    $shopno_error=false;
    $shopno_exist="";
    $shopname_error=false;
    $shopname_exist="";

    $hide="";
      if(isset($_POST['add']))
      {
        $count=1;
    
      }
      elseif(isset($_POST['submit']))
      {
        $count=2;
      }
    
      if($count==1 || $count==2)
      {
        
        $shopname = $_POST['shopname'];
        $shoplocation = $_POST['shoplocation'];
        $shopno = $_POST['shopno'];
        $ptype = $_POST['ptype'];
        $des= $_POST['des'];
    
    
          $query= oci_parse($conn, 'SELECT * FROM REQUEST_SHOP');
          $execute = oci_execute($query);
    
          if($execute)
          {
            while( $row= oci_fetch_row($query))
            {
              if($row['0']==$shopno)
                {
                  $shopno_error=true;
                        break;
                }
            }
            if($shopno_error)
            {
              $shopno_exist="Pan number already exist.";
            }
    
            while( $row= oci_fetch_row($query))
            {
              if($row['1']==$shopname)
                {
                  $shopname_error=true;
                        break;
                }
            }

    
            if($shopno_error)
            {
              $shopname_exist="Shop name already exist.";
            }
          }
          
          $query1= oci_parse($conn, 'SELECT * FROM TRADER_SHOP');
          $execute1 = oci_execute($query);
    
          if($execute1)
          {
            while( $row= oci_fetch_row($query1))
            {
              if($row['0']==$shopno)
                {
                  $shopno_error=true;
                        break;
                }
            }
            if($shopno_error)
            {
              $shopno_exist="Pan number already registered.";
            }
    
            while( $row= oci_fetch_row($query1))
            {
              if($row['1']==$shopname)
                {
                  $shopname_error=true;
                        break;
                }
            }

    
            if($shopno_error)
            {
              $shopname_exist="Shop name already registered.";
            }
          }
          
          if(!($shopno_error&&$shopname_error))
          {
     
            $query= oci_parse($conn, "INSERT INTO REQUEST_SHOP(PAN_NUMBER, SHOP_NAME, SHOP_LOCATION,P_TYPE,DES,REG_ID)
            VALUES($shopno,'$shopname','$shoplocation','$ptype','$des',$trID )");
            
            $execute = oci_execute($query);
    
            if($execute)
            {
              header('location:../tradershop.php?shopAdd="done"');
            }
            else{
                $shopno_exist="Pan number already exist.";
            }
          }
       
        
      }
      
      
    ?>
<!DOCTYPE html>
<html>
<head>
	<title>Signup form</title>
	
	<link href="https://fonts.googleapis.com/css?family=Quicksand&display=swap" rel="stylesheet">
	<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" type="text/css" href="../../css/Trader/trader_signup.css">
  
</head>
<body>
<section id="nav">
	
<form id="msform" action="" method="POST">
  <!-- progressbar -->

  <!-- fieldsets -->
  <fieldset>
    <div class="first-singup-title">
        <h2 class="fs-title">Add Shop</h2>
        <h3 class="fs-subtitle"></h3>
        <?php if(!($succession=="")){?>
                    <div class="danger-error-error" style="padding-top:12px">
                        <p style="background-color:#D4EDDA; text-align:center; border-radius: 20px;max-width: 70%;margin: 0 auto;">
                            <?php echo $succession;?>
                        </p>
                    </div>
                    <?php }?>
   </div>
   
   <div class="f_l_name">
        <div class="fl_name">
            <input type="number" name="shopno" placeholder="*Shop Pan No" required value="<?php if(isset($_POST['shopno'])) { echo htmlentities ($_POST['shopno']); }?>" style="-webkit-appearance: none;-moz-appearance: textfield;"/>
        
            <?php if(!($shopno_exist=="")){?>
              <div class="danger-error-error" style="padding-top:12px">
                <p style="background-color:#F8D7DA; text-align:center; border-radius: 20px;max-width: 50%;margin: 0 auto;">
                  <?php echo $shopno_exist;?>
                </p>
              </div>
            <?php }?>

        </div>
          <div class="fl_name">
            <input type="text" name="shopname" placeholder="*Shop Name" required  value="<?php if(isset($_POST['shopname'])) { echo htmlentities ($_POST['shopname']); }?>"  />
            <?php if(!($shopname_exist=="")){?>
              <div class="danger-error-error" style="padding-top:12px">
                <p style="background-color:#F8D7DA; text-align:center; border-radius: 20px;max-width: 50%;margin: 0 auto;">
                  <?php echo $shopname_exist;?>
                </p>
              </div>
            <?php }?>

            
          </div>
        
   </div>

   <div class="f_l_name">
        <div class="fl_name">
            <input type="text" name="shoplocation" placeholder="*Shop Location" required value="<?php if(isset($_POST['shoplocation'])) { echo htmlentities ($_POST['shoplocation']); }?>" />
        </div>
        <div class="fl_name">
            <select name="ptype" id="ptype" required>
              <option value="" disabled selected hidden>*Product Type</option>
              <option value="Green Groceries" <?php if(isset($_POST['ptype'])) { if(!strcmp($_POST['ptype'],'Green Groceries')){ echo "selected";} }?>>Green Groceries</option>
              <option value="Bakery"   <?php if(isset($_POST['ptype'])) { if(!strcmp($_POST['ptype'],'Bakery')){ echo "selected";} }?>>Bakery</option>
              <option value="Delicatessen"  <?php if(isset($_POST['ptype'])) { if(!strcmp($_POST['ptype'],'Delicatessen')){ echo "selected";} }?>>Delicatessen</option>
              <option value="Meat" <?php if(isset($_POST['ptype'])) { if(!strcmp($_POST['ptype'],'Meat')){ echo "selected";} }?>>Meat</option>
              <option value="Fish" <?php if(isset($_POST['ptype'])) { if(!strcmp($_POST['ptype'],'Fish')){ echo "selected";} }?>> Fish</option>
            </select>
        </div>
   </div>
  
   
  <div class="holderup">
  <textarea name="des"  cols="20" rows="10"  required style="resize:none;" placeholder="*Product Description">
  <?php if(isset($_POST['des'])) { echo htmlentities ($_POST['des']); }?>
  </textarea>
  </div>
   
  <div class="fullfill-link">
    <input type="submit" name="submit" class="next action-button" value="Submit">

  </div>
   
  </div>

  </fieldset>
  
</form>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/5cd602dbbe.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
</body>
</html>