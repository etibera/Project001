<?php

function redirect($location){
   return header("Location: ".$location. ".php");
}