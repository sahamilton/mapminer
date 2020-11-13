<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

interface DashboardInterface
{
   public function getDashBoardData();

   public function getView();

   public function isValid(Person $person);
}
