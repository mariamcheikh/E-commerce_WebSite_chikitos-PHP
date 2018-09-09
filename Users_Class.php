<?php

class User
{
	private $userID;
	private $firstname;
	private $lastname;
	private $email;
	private $birthday;
	private $mobileNum;
	private $telephoneNum;
	private $address;
	private $state;
	private $zipcode;
	private $pwd;
	private $accountstate;
	private $newsletter;
	private $oldpwd;

	function __construct()
	{
	 $userID=0;
	 $firstname="";
	 $lastname="";
	 $email="";
	 $birthday="";
	 $mobileNum=0;
	 $telephoneNum=0;
	 $address="";
	 $state="";
	 $zipcode=0;
	 $pwd="";
	 $accountstate=false;
	 $newsletter=false;
	}

	/****GET/SET Function****/
		function get_userID()
		{
			return $this->userID;
		}
		function set_userID($userID)
	{
		$this->userID=$userID;
	}
		function set_firstname($firstname)
	{
		$this->firstname=$firstname;
	}


		function set_lastname($lastname)
	{
		$this->lastname=$lastname;
	}

		function set_email($email)
	{
		$this->email=$email;
	}


		function set_birthday($birthday)
	{
		$this->birthday=$birthday;
	}


		function set_mobileNum($mobileNum)
	{
		$this->mobileNum=$mobileNum;
	}


		function set_telephoneNum($telephoneNum)
	{
		$this->telephoneNum=$telephoneNum;
	}


		function set_address($address)
	{
		$this->address=$address;
	}

		function set_state($state)
	{
		$this->state=$state;
	}

		function set_zipcode($zipcode)
	{
		$this->zipcode=$zipcode;
	}


		function set_pwd($pwd)
	{
		$this->pwd=$pwd;
	}

		function set_oldpwd($oldpwd)
	{
		$this->oldpwd=$oldpwd;
	}


		function set_accountstate($accountstate)
	{
		$this->accountstate=$accountstate;
	}

		function set_newsletter($newsletter)
	{
		$this->newsletter=$newsletter;
	}
	/******Functions******/
	public function verifyemail($DB)
	{
	   $req=$DB->prepare("SELECT DISTINCT `email` from users where `email`=? ");
	   $req->bindParam(1,$this->email);
	   $req->execute();
	   while ($Row = $req->fetch())
    {
    	if ($Row['email']==$this->email) {
    		return true;
    	}else{return false;}

    }

	}
	//End Function

	public function newuser($DB)
	{
		$req=$DB->prepare("INSERT INTO users (`firstname`, `lastname`, `email`, `password`, `birthday`, `mobilenum`, `telephonenum`, `address`, `state`, `zipcode`,`newsletter`) VALUES (?,?,?,?,?,?,?,?,?,?,?)");
		$req->bindParam(1,$this->firstname);
		$req->bindParam(2,$this->lastname);
		$req->bindParam(3,$this->email);
		$req->bindParam(4,$this->pwd);
		$req->bindParam(5,$this->birthday);
		$req->bindParam(6,$this->mobileNum);
		$req->bindParam(7,$this->telephoneNum);
		$req->bindParam(8,$this->address);
		$req->bindParam(9,$this->state);
		$req->bindParam(10,$this->zipcode);
		$req->bindParam(11,$this->newsletter);
		return($req->execute());
	}
	//End Function


	public function user_login($DB)
	{
	   $req=$DB->prepare("SELECT * from users where `email`=? ");
	   $req->bindParam(1,$this->email);
	   $req->execute();
		 //$_SESSION['Auth'] = $req->fetch();
	   	   while ($Row = $req->fetch())
    {
    	if ($Row['password']==$this->pwd && $Row['accountstate']==true) {
    		$_SESSION['user_session']=$Row['user_id'];
    		$_SESSION['user_name']=$Row['firstname'];
    	//	$_SESSION['cart']=
    		return true;
    	}else{
    		return false;
    	}
    }

	}

	//End Function

	public function user_logout()
	{
		session_start();
 		unset($_SESSION['user_session']);
 		unset($_SESSION['user_name']);

		 if(session_destroy())
		 {
		  header("Location:indextest.php");
		 }
	}

	//End Function

	public function user_display($DB)
	{
	   $req=$DB->prepare("SELECT * from users ");
	   $req->execute();
	   	   while ($Row = $req->fetch())
    {
    	if ($Row["accountstate"]==true) {
    		$acstate="Active";
    		echo '
     		<tr>
     	<td >' .$Row["user_id"].' </td>
     	<td >' .$Row["lastname"].' </td>
     	<td >' .$Row["firstname"].' </td>
     	<td >' .$Row["email"].' </td>
     	<td >' .$Row["signupdate"].' </td>
     	<td >' .$acstate.' </td>
     	<td ><a class="btn btn-danger" href="Updateaccountstate.php?id='.$Row["user_id"].'" role="button">Bloquer</a></td>
     	  	</tr>


     	';
    	}else{
    	    $acstate="Désactive";
    		echo '
     		<tr>
     	<td >' .$Row["user_id"].' </td>
     	<td >' .$Row["lastname"].' </td>
     	<td >' .$Row["firstname"].' </td>
     	<td >' .$Row["email"].' </td>
     	<td >' .$Row["signupdate"].' </td>
     	<td >' .$acstate.' </td>
     	<td ><a class="btn btn-success" href="Updateaccountstate.php?id='.$Row["user_id"].'" role="button">Debloquer</a></td>
     	  	</tr>


     	';
    	}


    }
	}
	//End Function
	public function update_accountstate($DB)
	{
	   $req=$DB->prepare("SELECT * from users where `user_id`=? ");
	   $req->bindParam(1,$this->userID);
	   $req->execute();
	   $Row = $req->fetch();

	    	if ($Row["accountstate"]==true)
	    	{
	    		   $val=false;
				   $req=$DB->prepare("UPDATE users SET accountstate=? where `user_id`=? ");
				   $req->bindParam(1,$val);
				   $req->bindParam(2,$this->userID);
				   $req->execute();
	    	}else{
	    		   $val=true;
				   $req=$DB->prepare("UPDATE users SET accountstate=? where `user_id`=? ");
				   $req->bindParam(1,$val);
				   $req->bindParam(2,$this->userID);
				   $req->execute();
	    	}

	}
	//End Function
	public function display_accountinfo($DB)
	{
		$req=$DB->prepare("SELECT * from users where `user_id`=? ");
	   $req->bindParam(1,$this->userID);
	   $req->execute();
	   	   while ($Row = $req->fetch())
    {
    	echo'
    	<tr>
                        <td>Nom:</td>
                        <td>' .$Row["lastname"].' </td>
                      </tr>
                      <tr>
                        <td>Prénom:</td>
                        <td>' .$Row["firstname"].' </td>
                      </tr>
                      <tr>
                        <td>Date de naissance</td>
                        <td>' .$Row["birthday"].' </td>
                      </tr>
                         <tr>
                             <tr>
                        <td>Mobile</td>
                        <td>' .$Row["mobilenum"].' </td>
                      </tr>
                        <tr>
                        <td>Telephone</td>
                        <td>' .$Row["telephonenum"].' </td>
                      </tr>
                      <tr>
                        <td>Email</td>
                        <td><a href="mailto:' .$Row["email"].' ">' .$Row["email"].' </a></td>
                      </tr>
                        <td>Adresse</td>
                        <td>' .$Row["address"].'</td>

                      </tr>
                      <tr>
                        <td>Etat</td>
                        <td>' .$Row["state"].' </td>
                      </tr>
                      <tr>
                        <td>Code postal</td>
                        <td>' .$Row["zipcode"].' </td>
                      </tr>
    	';

    }

	}
	//End Function

	public function verif_pwd($DB)
	{
	   $req=$DB->prepare("SELECT * from users where `user_id`=? ");
	   $req->bindParam(1,$this->userID);
	   $req->execute();
	   	   while ($Row = $req->fetch())
    {
    	if ($Row['password']==$this->oldpwd ) {
    		return true;
    	}else{
    		return false;
    	}
    }
	}
	//End Function
	public function extractuser($DB)
	{
	   $req=$DB->prepare("SELECT * from users where `user_id`=? ");
	   $req->bindParam(1,$this->userID);
	   $req->execute();
	   return  $req->fetchAll();
	}
	//End Function
	public function updateuser($DB)
	{
		$req=$DB->prepare("UPDATE `users` SET `firstname`=?,`lastname`=?,`birthday`=?,`mobilenum`=?,`telephonenum`=?,`address`=?,`state`=?,`zipcode`=? WHERE `user_id`=? ");
		$req->bindParam(1,$this->firstname);
		$req->bindParam(2,$this->lastname);
		$req->bindParam(3,$this->birthday);
		$req->bindParam(4,$this->mobileNum);
		$req->bindParam(5,$this->telephoneNum);
		$req->bindParam(6,$this->address);
		$req->bindParam(7,$this->state);
		$req->bindParam(8,$this->zipcode);
		$req->bindParam(9,$this->userID);
		return($req->execute());

	}
	//End Function
	public function updateuserPwd($DB)
	{
		$req=$DB->prepare("UPDATE `users` SET `password`=? WHERE `user_id`=? ");
		$req->bindParam(1,$this->pwd);
		$req->bindParam(2,$this->userID);
		return($req->execute());

	}

}


?>
