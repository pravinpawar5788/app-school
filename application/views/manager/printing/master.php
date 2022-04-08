<?php

$COMP_DIR=$LIB_VIEW_DIR.'printing/components/';

$this->load->view($COMP_DIR.'header');
$this->load->view($LIB_VIEW_DIR.'printing/'.$main_content);
$this->load->view($COMP_DIR.'footer');