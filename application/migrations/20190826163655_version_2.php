<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Version_2 extends CI_Migration {

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
        $this->dbforge->add_field(array(
            $_primary_key => $_data_primary_field,
            'campus_id' => $_data_int_field,
            'class_id' => $_data_int_field,
            'name' => $_data_text_field,
            'description' => $_data_text_field,
            'amount' => $_data_float_field,
            'obt_min_percent' => $_data_smallint_field,
            'obt_max_percent' => $_data_smallint_field,
        ));
        $this->dbforge->add_key($_primary_key, TRUE);
        $this->dbforge->create_table('class_fee_packages',TRUE,$_table_attributes);

    	/////create table
        $this->dbforge->add_field(array(
            $_primary_key => $_data_primary_field,
            'campus_id' => $_data_int_field,
            'title' => $_data_text_field,
            'start_date' => $_data_text_field,
            'end_date' => $_data_text_field,
            'start_jd' => $_data_int_field,
            'end_jd' => $_data_int_field,
            'status' => $_data_varchar_field,
        ));
        $this->dbforge->add_key($_primary_key, TRUE);
        $this->dbforge->create_table('finance_accounting_periods',TRUE,$_table_attributes);

        /////create table
        $this->dbforge->add_field(array(
            $_primary_key => $_data_primary_field,
            'title' => $_data_text_field,
        ));
        $this->dbforge->add_key($_primary_key, TRUE);
        $this->dbforge->create_table('std_groups',TRUE,$_table_attributes);

        /////create table
        $this->dbforge->add_field(array(
            $_primary_key => $_data_primary_field,
            'title' => $_data_text_field,
        ));
        $this->dbforge->add_key($_primary_key, TRUE);
        $this->dbforge->create_table('concession_types',TRUE,$_table_attributes);

        /////create table
        $this->dbforge->add_field(array(
            $_primary_key => $_data_primary_field,
            'campus_id' => $_data_int_field,
            'student_id' => $_data_int_field,
            'type_id' => $_data_int_field,
            'amount' => $_data_float_field,
            'type' => $_data_text_field,
            'date' => $_data_text_field,
        ));
        $this->dbforge->add_key($_primary_key, TRUE);
        $this->dbforge->create_table('std_fee_concession',TRUE,$_table_attributes);






    	/////modify table
        $fields=array(
            'period_id' => $_data_int_field,
            'is_current_asset' => $_data_tinyint_field,
        );
        $this->dbforge->add_column('finance_accounts', $fields);

        /////modify table
        $fields=array(
            'period_id' => $_data_int_field,
        );
        $this->dbforge->add_column('finance_accounts_ledger', $fields);

        /////modify table
        $fields=array(
            'fare' => $_data_float_field,
        );
        $this->dbforge->add_column('transport_routes', $fields);

        /////modify table
        $fields=array(
            'group_id' => $_data_int_field,
            'leaving_date' => $_data_text_field,
            'leaving_class' => $_data_text_field,
            'leaving_reason' => $_data_text_field,
            'security_fee' => $_data_float_field,
            'annual_fund' => $_data_float_field,
            'admission_fee' => $_data_float_field,
            'pkg_total_marks' => $_data_smallint_field,
            'pkg_obt_marks' => $_data_smallint_field,
        );
        $this->dbforge->add_column('student', $fields);

        /////modify table
        $fields=array(
            'concession_type' => $_data_int_field,
        );
        $this->dbforge->add_column('std_fee_entries', $fields);

        /////modify table
        $fields=array(
            'logo' => $_data_int_field,
        );
        $this->dbforge->add_column('campus', $fields);


        ////////////////////////////////////////////////////////////////////////
        ////////////////////ADD FOREIGN KEYS ///////////////////////////////////
        ////////////////////////////////////////////////////////////////////////
        $on_mod=' ON UPDATE CASCADE ';
        $on_del=' ON DELETE CASCADE ';
        $on_delmod=' ON DELETE CASCADE ON UPDATE CASCADE ';
        //////////////////////////////////////////////////////////////
        //////////////////////////////////////////////////////////////
        $ref=' classes(mid) ';
        $fk=' class_id ';
        $this->dbforge->add_column('class_fee_packages',["CONSTRAINT FOREIGN KEY($fk) REFERENCES $ref $on_delmod",]);
        //////////////////////////////////////////////////////////////
        $ref=' campus(mid) ';
        $fk=' campus_id ';
        $this->dbforge->add_column('finance_accounting_periods',["CONSTRAINT FOREIGN KEY($fk) REFERENCES $ref $on_delmod",]);
        $this->dbforge->add_column('std_fee_concession',["CONSTRAINT FOREIGN KEY($fk) REFERENCES $ref $on_delmod",]);

        //////////////////////////////////////////////////////////////
        $ref=' student(mid) ';
        $fk=' student_id ';
        $this->dbforge->add_column('std_fee_concession',["CONSTRAINT FOREIGN KEY($fk) REFERENCES $ref $on_delmod",]);

    }

    public function down(){
        $this->dbforge->drop_table('class_fee_packages',TRUE);
        $this->dbforge->drop_table('finance_accounting_periods',TRUE);
    }
}