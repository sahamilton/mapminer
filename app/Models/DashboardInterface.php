<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

interface DashboardInterface
{
   public function getDashBoardData();

   public function getView();

   public function isValid(Person $person);
}
