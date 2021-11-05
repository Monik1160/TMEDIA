<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

trait CampaignStatus
{
    protected function status($)
    {


        return response()->json(['error' => $message, 'code' => $code], $code);
    }
}
