<?php

$COMPONENTS=$this->LIB_VIEW_DIR.'components/';

$this->load->view($COMPONENTS.'header');
$this->load->view($this->LIB_VIEW_DIR.$main_content);
$this->load->view($COMPONENTS.'footer');