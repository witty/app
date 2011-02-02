<?php
require '/path/to/modules/witty/witty.php';
Witty::init();

Witty::set_config_dir('config');

Witty::instance('App')->start()->render();
