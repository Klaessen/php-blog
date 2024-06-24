<?php
Class ConfigService
{
    public function getConfig(): array
    {
        $config = require("../config/config.inc.php");
        return $config;
    }

    //TODO: implement cahcing
}