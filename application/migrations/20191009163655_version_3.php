<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Version_3 extends CI_Migration {

    public function up(){
    	$this->load->dbforge();
    	//configurations for migration class.
    	$_primary_key='mid';
    	$_data_primary_field=array('type' => 'INT','unsigned' => TRUE,'auto_increment' => TRUE);
    	$_data_int_field=array('type' => 'INT','unsigned' => TRUE);
    	$_data_tinyint_field=array('type' => 'TINYINT','unsigned' => TRUE);
    	$_data_smallint_field=array('type' => 'SMALLINT','unsigned' => TRUE);
    	$_data_bigint_field=array('type' => 'BIGINT','unsigned' => TRUE);
    	$_data_float_field=array('type' => 'FLOAT','constraint' => '10,2');
    	$_data_varchar_field=array('type' => 'VARCHAR','constraint' => '100');
    	$_data_text_field=array('type' => 'TEXT','null' => TRUE);
    	$_data_longtext_field=array('type' => 'LONGTEXT','null' => TRUE);
    	$_table_attributes=array('ENGINE' => 'InnoDB');

    	/////////////////////////////////////////////////////////////////
    	/////////////////////////////////////////////////////////////////
    	/////create table
        // $this->dbforge->add_field(array(
        //     $_primary_key => $_data_primary_field,
        //     'campus_id' => $_data_int_field,
        //     'class_id' => $_data_int_field,
        //     'name' => $_data_text_field,
        //     'description' => $_data_text_field,
        //     'amount' => $_data_float_field,
        //     'obt_min_percent' => $_data_smallint_field,
        //     'obt_max_percent' => $_data_smallint_field,
        // ));
        // $this->dbforge->add_key($_primary_key, TRUE);
        // $this->dbforge->create_table('class_fee_packages',TRUE,$_table_attributes);






    	/////////////////////////////////////////////////////////////////////////////
        /////////////////////////////////////////////////////////////////////////////        
        /////modify table
        if ($this->db->field_exists('student_id', 'std_subject_homework')){
            $this->db->query("ALTER TABLE std_subject_homework DROP FOREIGN KEY std_subject_homework_ibfk_2; ");
            $this->dbforge->drop_column('std_subject_homework','student_id');
        }

        /////modify table
        if (!$this->db->field_exists('section_id', 'std_subject_homework')){
            $fields=array(
                'section_id' => $_data_int_field,
            );
            $this->dbforge->add_column('std_subject_homework', $fields);
        }


        /////modify table
        if (!$this->db->field_exists('by_sid', 'std_fee_entries')){
            $fields=array(
                'by_sid' => $_data_int_field,
                'is_admin' => $_data_tinyint_field,
            );
            $this->dbforge->add_column('std_fee_entries', $fields);
        }

        /////modify table
        if (!$this->db->field_exists('balance', 'std_fee_voucher')){
            $fields=array(
                'balance' => $_data_float_field,
            );
            $this->dbforge->add_column('std_fee_voucher', $fields);
        }

        /////modify table
        if (!$this->db->field_exists('section_id', 'std_term_result')){
            $fields=array(
                'section_id' => $_data_int_field,
            );
            $this->dbforge->add_column('std_term_result', $fields);
        }

        /////modify table
        if (!$this->db->field_exists('count', 'sms_history')){
            $fields=array(
                'lang' => $_data_varchar_field,
                'count' => $_data_tinyint_field,
            );
            $this->dbforge->add_column('sms_history', $fields);
        }

        ////////////////////////////////////////////////////////////////////////
        ////////////////////ADD FOREIGN KEYS ///////////////////////////////////
        ////////////////////////////////////////////////////////////////////////
        $on_mod=' ON UPDATE CASCADE ';
        $on_del=' ON DELETE CASCADE ';
        $on_delmod=' ON DELETE CASCADE ON UPDATE CASCADE ';
        //////////////////////////////////////////////////////////////
        //////////////////////////////////////////////////////////////
        // $ref=' classes(mid) ';
        // $fk=' class_id ';
        // $this->dbforge->add_column('class_fee_packages',["CONSTRAINT FOREIGN KEY($fk) REFERENCES $ref $on_delmod",]);
        //////////////////////////////////////////////////////////////
        // $ref=' campus(mid) ';
        // $fk=' campus_id ';
        // $this->dbforge->add_column('finance_accounting_periods',["CONSTRAINT FOREIGN KEY($fk) REFERENCES $ref $on_delmod",]);
        // $this->dbforge->add_column('std_fee_concession',["CONSTRAINT FOREIGN KEY($fk) REFERENCES $ref $on_delmod",]);

    }

    public function down(){
        // $this->dbforge->drop_table('class_fee_packages',TRUE);
        // $this->dbforge->drop_table('finance_accounting_periods',TRUE);
    }
}