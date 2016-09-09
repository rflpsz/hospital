<?php
session_start();
if(!isset($_SESSION['Empresa']))
{
	header("Location: index.php");
}
?>