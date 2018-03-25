<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('winners', function () {
    return true;
});