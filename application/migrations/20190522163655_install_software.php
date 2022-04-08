<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Install_software extends CI_Migration {

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
            'promotion_id' => $_data_int_field,
            'display_order' => $_data_float_field,
            'incharge_id' => $_data_varchar_field,
            'title' => $_data_varchar_field,
            'fee' => $_data_float_field,
            'date' => $_data_varchar_field,
            'jd' => $_data_int_field,
        ));
        $this->dbforge->add_key($_primary_key, TRUE);
        $this->dbforge->create_table('classes',TRUE,$_table_attributes);

    	/////create table
        $this->dbforge->add_field(array(
            $_primary_key => $_data_primary_field,
            'campus_id' => $_data_int_field,
            'class_id' => $_data_int_field,
            'display_order' => $_data_float_field,
            'name' => $_data_text_field,
            'code' => $_data_text_field,
            'description' => $_data_text_field,
            'passing_percentage' => $_data_tinyint_field,
            'chapters' => $_data_tinyint_field,
        ));
        $this->dbforge->add_key($_primary_key, TRUE);
        $this->dbforge->create_table('class_subjects',TRUE,$_table_attributes);

    	/////create table
        $this->dbforge->add_field(array(
            $_primary_key => $_data_primary_field,
            'campus_id' => $_data_int_field,
            'subject_id' => $_data_int_field,
            'staff_id' => $_data_int_field,
        ));
        $this->dbforge->add_key($_primary_key, TRUE);
        $this->dbforge->create_table('class_subject_faculty',TRUE,$_table_attributes);

    	/////create table
        $this->dbforge->add_field(array(
            $_primary_key => $_data_primary_field,
            'campus_id' => $_data_int_field,
            'subject_id' => $_data_int_field,
            'display_order' => $_data_float_field,
            'chapter_number' => $_data_tinyint_field,
            'name' => $_data_text_field,
            'description' => $_data_text_field,
        ));
        $this->dbforge->add_key($_primary_key, TRUE);
        $this->dbforge->create_table('class_subject_lessons',TRUE,$_table_attributes);

    	/////create table
        $this->dbforge->add_field(array(
            $_primary_key => $_data_primary_field,
            'campus_id' => $_data_int_field,
            'session_id' => $_data_int_field,
            'subject_id' => $_data_int_field,
            'lesson_id' => $_data_int_field,
            'chapter' => $_data_tinyint_field,
            'status' => $_data_varchar_field,
            'start_date' => $_data_varchar_field,
            'complete_date' => $_data_varchar_field,
        ));
        $this->dbforge->add_key($_primary_key, TRUE);
        $this->dbforge->create_table('class_subject_progress',TRUE,$_table_attributes);

    	/////create table
        $this->dbforge->add_field(array(
            $_primary_key => $_data_primary_field,
            'campus_id' => $_data_int_field,
            'subject_id' => $_data_int_field,
            'lesson_id' => $_data_int_field,
            'chapter' => $_data_tinyint_field,
            'question' => $_data_text_field,
            'detail' => $_data_text_field,
            'type' => $_data_varchar_field,
            'option1' => $_data_text_field,
            'option2' => $_data_text_field,
            'option3' => $_data_text_field,
            'option4' => $_data_text_field,
            'answer' => $_data_varchar_field,
            'marks' => $_data_float_field,
            'day' => $_data_smallint_field,
            'month' => $_data_smallint_field,
            'year' => $_data_smallint_field,
        ));
        $this->dbforge->add_key($_primary_key, TRUE);
        $this->dbforge->create_table('class_subject_questionbank',TRUE,$_table_attributes);

    	/////create table
        $this->dbforge->add_field(array(
            $_primary_key => $_data_primary_field,
            'campus_id' => $_data_int_field,
            'session_id' => $_data_int_field,
            'class_id' => $_data_int_field,
            'subject_id' => $_data_int_field,
            'lesson_id' => $_data_int_field,
            'chapter' => $_data_text_field,
            'total_marks' => $_data_int_field,
            'title' => $_data_text_field,
            'description' => $_data_text_field,
            'date' => $_data_varchar_field,
            'day' => $_data_smallint_field,
            'month' => $_data_smallint_field,
            'year' => $_data_smallint_field,
        ));
        $this->dbforge->add_key($_primary_key, TRUE);
        $this->dbforge->create_table('class_subject_tests',TRUE,$_table_attributes);

    	/////create table
        $this->dbforge->add_field(array(
            $_primary_key => $_data_primary_field,
            'campus_id' => $_data_int_field,
            'class_id' => $_data_int_field,
            'subject_id' => $_data_int_field,
            'teacher_id' => $_data_int_field,
            'period_id' => $_data_int_field,
            'day' => $_data_varchar_field,
        ));
        $this->dbforge->add_key($_primary_key, TRUE);
        $this->dbforge->create_table('class_timetable',TRUE,$_table_attributes);

    	/////create table
        $this->dbforge->add_field(array(
            $_primary_key => $_data_primary_field,
            'campus_id' => $_data_int_field,
            'number' => $_data_tinyint_field,
            'name' => $_data_text_field,
            'from_time' => $_data_text_field,
            'to_time' => $_data_text_field,
            'total_time' => $_data_smallint_field,
            'sort_order' => $_data_float_field,
            'type' => $_data_text_field,
            'note' => $_data_text_field,
        ));
        $this->dbforge->add_key($_primary_key, TRUE);
        $this->dbforge->create_table('class_timetable_periods',TRUE,$_table_attributes);

    	/////create table
        $this->dbforge->add_field(array(
            $_primary_key => $_data_primary_field,
            'campus_id' => $_data_int_field,
            'session_id' => $_data_int_field,
            'name' => $_data_text_field,
            'description' => $_data_text_field,
            'start_date' => $_data_text_field,
            'end_date' => $_data_text_field,
            'date' => $_data_varchar_field,
        ));
        $this->dbforge->add_key($_primary_key, TRUE);
        $this->dbforge->create_table('exam_terms',TRUE,$_table_attributes);

    	/////create table
        $this->dbforge->add_field(array(
            $_primary_key => $_data_primary_field,
            'campus_id' => $_data_int_field,
            'title' => $_data_text_field,
            'balance' => $_data_float_field,
            'type' => $_data_varchar_field,
            'is_default' => $_data_tinyint_field,
            'date' => $_data_varchar_field,
        ));
        $this->dbforge->add_key($_primary_key, TRUE);
        $this->dbforge->create_table('finance_accounts',TRUE,$_table_attributes);

    	/////create table
        $this->dbforge->add_field(array(
            $_primary_key => $_data_primary_field,
            'campus_id' => $_data_int_field,
            'title' => $_data_text_field,
            'debit_account_id' => $_data_int_field,
            'debit_account_balance' => $_data_float_field,
            'debit_amount' => $_data_float_field,
            'debit_reference' => $_data_text_field,
            'credit_account_id' => $_data_int_field,
            'credit_account_balance' => $_data_float_field,
            'credit_amount' => $_data_float_field,
            'credit_reference' => $_data_text_field,
            'is_manual' => $_data_tinyint_field,
            'date' => $_data_varchar_field,
            'jd' => $_data_int_field,
            'day' => $_data_smallint_field,
            'month' => $_data_smallint_field,
            'year' => $_data_smallint_field,
        ));
        $this->dbforge->add_key($_primary_key, TRUE);
        $this->dbforge->create_table('finance_accounts_ledger',TRUE,$_table_attributes);

    	/////create table
        $this->dbforge->add_field(array(
            $_primary_key => $_data_primary_field,
            'campus_id' => $_data_int_field,
            'title' => $_data_text_field,
            'description' => $_data_text_field,
            'amount' => $_data_float_field,
            'type' => $_data_text_field,
            'ledger_id' => $_data_int_field,
            'account_id' => $_data_int_field,
            'date' => $_data_varchar_field,
            'jd' => $_data_int_field,
            'day' => $_data_smallint_field,
            'month' => $_data_smallint_field,
            'year' => $_data_smallint_field,
        ));
        $this->dbforge->add_key($_primary_key, TRUE);
        $this->dbforge->create_table('finance_expenses',TRUE,$_table_attributes);

    	/////create table
        $this->dbforge->add_field(array(
            $_primary_key => $_data_primary_field,
            'campus_id' => $_data_int_field,
            'title' => $_data_text_field,
            'description' => $_data_text_field,
            'amount' => $_data_float_field,
            'type' => $_data_text_field,
            'ledger_id' => $_data_int_field,
            'account_id' => $_data_int_field,
            'date' => $_data_varchar_field,
            'jd' => $_data_int_field,
            'day' => $_data_smallint_field,
            'month' => $_data_smallint_field,
            'year' => $_data_smallint_field,
        ));
        $this->dbforge->add_key($_primary_key, TRUE);
        $this->dbforge->create_table('finance_income',TRUE,$_table_attributes);

    	/////create table
        $this->dbforge->add_field(array(
            $_primary_key => $_data_primary_field,
            'campus_id' => $_data_int_field,
            'catagory' => $_data_text_field,
            'name' => $_data_text_field,
            'sub_title' => $_data_text_field,
            'author' => $_data_text_field,
            'sub_author' => $_data_text_field,
            'statement' => $_data_text_field,
            'publisher' => $_data_text_field,
            'isbn' => $_data_text_field,
            'placement_number' => $_data_text_field,
            'accession_number' => $_data_text_field,
            'ddc_number' => $_data_text_field,
            'place' => $_data_text_field,
            'volume' => $_data_text_field,
            'binding' => $_data_text_field,
            'year' => $_data_text_field,
            'pages' => $_data_int_field,
            'stock' => $_data_int_field,
            'price' => $_data_float_field,
            'date' => $_data_varchar_field,
            'jd' => $_data_int_field,
            'day' => $_data_smallint_field,
            'month' => $_data_smallint_field,
            'cyear' => $_data_smallint_field,
        ));
        $this->dbforge->add_key($_primary_key, TRUE);
        $this->dbforge->create_table('lib_books',TRUE,$_table_attributes);

    	/////create table
        $this->dbforge->add_field(array(
            $_primary_key => $_data_primary_field,
            'campus_id' => $_data_int_field,
            'book_id' => $_data_int_field,
            'user_id' => $_data_int_field,
            'book' => $_data_text_field,
            'catagory' => $_data_text_field,
            'user' => $_data_text_field,
            'due_date' => $_data_varchar_field,
            'due_jd' => $_data_int_field,
            'date' => $_data_varchar_field,
            'jd' => $_data_int_field,
            'user_type' => $_data_varchar_field,
        ));
        $this->dbforge->add_key($_primary_key, TRUE);
        $this->dbforge->create_table('lib_issued_books',TRUE,$_table_attributes);

    	/////create table
        $this->dbforge->add_field(array(
            $_primary_key => $_data_primary_field,
            'ipaddress' => $_data_varchar_field,
            'time' => $_data_bigint_field,
            'date' => $_data_varchar_field,
            'jd' => $_data_int_field,
        ));
        $this->dbforge->add_key($_primary_key, TRUE);
        $this->dbforge->create_table('login_session',TRUE,$_table_attributes);

    	/////create table
        $this->dbforge->add_field(array(
            $_primary_key => $_data_primary_field,
            'campus_id' => $_data_int_field,
            'title' => $_data_text_field,
            'detail' => $_data_text_field,
            'type' => $_data_text_field,
            'target' => $_data_text_field,
            'date' => $_data_varchar_field,
        ));
        $this->dbforge->add_key($_primary_key, TRUE);
        $this->dbforge->create_table('noteboard',TRUE,$_table_attributes);


    	/////create table
        $this->dbforge->add_field(array(
            $_primary_key => $_data_primary_field,
            'title' => $_data_text_field,
            'template' => $_data_text_field,
            'date' => $_data_varchar_field,
            'day' => $_data_smallint_field,
            'month' => $_data_smallint_field,
            'year' => $_data_smallint_field,
        ));
        $this->dbforge->add_key($_primary_key, TRUE);
        $this->dbforge->create_table('awards',TRUE,$_table_attributes);


    	/////create table
        $this->dbforge->add_field(array(
            $_primary_key => $_data_primary_field,
            'name' => $_data_text_field,
            'contact_number' => $_data_text_field,
            'address' => $_data_text_field,
            'date' => $_data_varchar_field,
            'month' => $_data_smallint_field,
            'year' => $_data_smallint_field,
        ));
        $this->dbforge->add_key($_primary_key, TRUE);
        $this->dbforge->create_table('campus',TRUE,$_table_attributes);


    	/////create table
        $this->dbforge->add_field(array(
            $_primary_key => $_data_primary_field,
            'campus_id' => $_data_int_field,
            'class_id' => $_data_int_field,
            'incharge_id' => $_data_int_field,
            'name' => $_data_text_field,
        ));
        $this->dbforge->add_key($_primary_key, TRUE);
        $this->dbforge->create_table('class_sections',TRUE,$_table_attributes);


    	/////create table
        $this->dbforge->add_field(array(
            $_primary_key => $_data_primary_field,
            'title' => $_data_text_field,
            'template' => $_data_text_field,
            'date' => $_data_varchar_field,
            'jd' => $_data_int_field,
        ));
        $this->dbforge->add_key($_primary_key, TRUE);
        $this->dbforge->create_table('certificates',TRUE,$_table_attributes);


    	/////create table
        $this->dbforge->add_field(array(
            $_primary_key => $_data_primary_field,
            'campus_id' => $_data_int_field,
            'session_id' => $_data_int_field,
            'title' => $_data_text_field,
            'name' => $_data_text_field,
            'status' => $_data_text_field,
            'date' => $_data_varchar_field,
            'jd' => $_data_int_field,
        ));
        $this->dbforge->add_key($_primary_key, TRUE);
        $this->dbforge->create_table('system_files',TRUE,$_table_attributes);


    	/////create table
        $this->dbforge->add_field(array(
            $_primary_key => $_data_primary_field,
            'name' => $_data_text_field,
            'general_name' => $_data_text_field,
            'status' => $_data_text_field,
        ));
        $this->dbforge->add_key($_primary_key, TRUE);
        $this->dbforge->create_table('system_modules',TRUE,$_table_attributes);


    	/////create table
        $this->dbforge->add_field(array(
            $_primary_key => $_data_primary_field,
            'campus_id' => $_data_int_field,
            'title' => $_data_text_field,
            'detail' => $_data_text_field,
            'type' => $_data_text_field,
            'status' => $_data_text_field,
            'date' => $_data_varchar_field,
            'jd' => $_data_int_field,
        ));
        $this->dbforge->add_key($_primary_key, TRUE);
        $this->dbforge->create_table('notifications',TRUE,$_table_attributes);


    	/////create table
        $this->dbforge->add_field(array(
            $_primary_key => $_data_primary_field,
            'campus_id' => $_data_int_field,
            'parent_id' => $_data_varchar_field,
            'name' => $_data_text_field,
            'mobile' => $_data_text_field,
            'password' => $_data_text_field,
            'cnic' => $_data_text_field,
            'image' => $_data_text_field,
            'detail' => $_data_text_field,
            'type' => $_data_text_field,
            'status' => $_data_text_field,
            'date' => $_data_varchar_field,
            'day' => $_data_int_field,
            'month' => $_data_int_field,
            'year' => $_data_int_field,
        ));
        $this->dbforge->add_key($_primary_key, TRUE);
        $this->dbforge->create_table('parents',TRUE,$_table_attributes);


    	/////create table
        $this->dbforge->add_field(array(
            $_primary_key => $_data_primary_field,
            'campus_id' => $_data_int_field,
            'parent_id' => $_data_int_field,
            'title' => $_data_text_field,
            'parent_status' => $_data_tinyint_field,
            'org_status' => $_data_tinyint_field,
            'date' => $_data_varchar_field,
            'day' => $_data_smallint_field,
            'month' => $_data_smallint_field,
            'year' => $_data_smallint_field,
        ));
        $this->dbforge->add_key($_primary_key, TRUE);
        $this->dbforge->create_table('parents_chat',TRUE,$_table_attributes);


    	/////create table
        $this->dbforge->add_field(array(
            $_primary_key => $_data_primary_field,
            'campus_id' => $_data_int_field,
            'chat_id' => $_data_int_field,
            'user_id' => $_data_int_field,
            'message' => $_data_text_field,
            'date' => $_data_varchar_field,
            'day' => $_data_smallint_field,
            'month' => $_data_smallint_field,
            'year' => $_data_smallint_field,
        ));
        $this->dbforge->add_key($_primary_key, TRUE);
        $this->dbforge->create_table('parents_chat_detail',TRUE,$_table_attributes);

    	/////create table
        $this->dbforge->add_field(array(
            $_primary_key => $_data_primary_field,
            'title' => $_data_text_field,
            'template' => $_data_text_field,
            'date' => $_data_varchar_field,
        ));
        $this->dbforge->add_key($_primary_key, TRUE);
        $this->dbforge->create_table('punishments',TRUE,$_table_attributes);

    	/////create table
        $this->dbforge->add_field(array(
            $_primary_key => $_data_primary_field,
            'title' => $_data_text_field,
            'status' => $_data_text_field,
        ));
        $this->dbforge->add_key($_primary_key, TRUE);
        $this->dbforge->create_table('stf_roles',TRUE,$_table_attributes);

    	/////create table
        $this->dbforge->add_field(array(
            $_primary_key => $_data_primary_field,
            'title' => $_data_text_field,
            'status' => $_data_text_field,
            'start_date' => $_data_text_field,
            'end_date' => $_data_text_field,
            'date' => $_data_text_field,
            'year' => $_data_int_field,
        ));
        $this->dbforge->add_key($_primary_key, TRUE);
        $this->dbforge->create_table('sessions',TRUE,$_table_attributes);

    	/////create table
        $this->dbforge->add_field(array(
            $_primary_key => $_data_primary_field,
            'campus_id' => $_data_int_field,
            'mobile' => $_data_varchar_field,
            'message' => $_data_text_field,
            'priority' => $_data_tinyint_field,
            'status' => $_data_text_field,
            'response' => $_data_text_field,
            'type' => $_data_text_field,
            'date' => $_data_text_field,
            'day' => $_data_smallint_field,
            'month' => $_data_smallint_field,
            'year' => $_data_smallint_field,
            'jd' => $_data_int_field,
        ));
        $this->dbforge->add_key($_primary_key, TRUE);
        $this->dbforge->create_table('sms_history',TRUE,$_table_attributes);

    	/////create table
        $this->dbforge->add_field(array(
            $_primary_key => $_data_primary_field,
            'campus_id' => $_data_int_field,
            'hook' => $_data_varchar_field,
            'template' => $_data_text_field,
            'event' => $_data_text_field,
            'target' => $_data_text_field,
        ));
        $this->dbforge->add_key($_primary_key, TRUE);
        $this->dbforge->create_table('sms_hook',TRUE,$_table_attributes);

    	/////create table
        $this->dbforge->add_field(array(
            'smsid' => $_data_int_field,
            'time' => $_data_text_field,
            'jd' => $_data_int_field,
        ));
        $this->dbforge->add_key($_primary_key, TRUE);
        $this->dbforge->create_table('sms_sent',TRUE,$_table_attributes);

        /////////////////////////////////////////////////////////////////////////
        /////////////////////////////////////////////////////////////////////////


    	/////create table
        $this->dbforge->add_field(array(
            $_primary_key => $_data_primary_field,
            'campus_id' => $_data_int_field,
            'staff_id' => $_data_varchar_field,
            'role_id' => $_data_int_field,
            'name' => $_data_text_field,
            'password' => $_data_text_field,
            'guardian_name' => $_data_text_field,
            'guardian_mobile' => $_data_text_field,
            'gender' => $_data_text_field,
            'blood_group' => $_data_text_field,
            'cnic' => $_data_text_field,
            'email' => $_data_text_field,
            'image' => $_data_text_field,
            'image_address' => $_data_text_field,
            'home_address' => $_data_text_field,
            'qualification' => $_data_text_field,
            'professional_qualification' => $_data_text_field,
            'contract' => $_data_text_field,
            'experience' => $_data_text_field,
            'favourite_subject' => $_data_text_field,
            'mobile' => $_data_varchar_field,
            'home_phone' => $_data_varchar_field,
            'salary' => $_data_float_field,
            'transport_fee' => $_data_float_field,
            'advance_amount' => $_data_float_field,
            'anual_increment' => $_data_int_field,
            'status' => $_data_varchar_field,
            'visibility' => $_data_varchar_field,
            'type' => $_data_text_field,
            'date' => $_data_text_field,
            'month' => $_data_smallint_field,
            'year' => $_data_smallint_field,
            'jd' => $_data_int_field,
        ));
        $this->dbforge->add_key($_primary_key, TRUE);
        $this->dbforge->create_table('staff',TRUE,$_table_attributes);

    	/////create table
        $this->dbforge->add_field(array(
            $_primary_key => $_data_primary_field,
            'campus_id' => $_data_int_field,
            'student_id' => $_data_int_field,
            'title' => $_data_text_field,
            'remarks' => $_data_text_field,
            'image' => $_data_text_field,
            'date' => $_data_text_field,
            'jd' => $_data_int_field,
        ));
        $this->dbforge->add_key($_primary_key, TRUE);
        $this->dbforge->create_table('std_acheivements',TRUE,$_table_attributes);

    	/////create table
        $this->dbforge->add_field(array(
            $_primary_key => $_data_primary_field,
            'campus_id' => $_data_int_field,
            'student_id' => $_data_int_field,
            'title' => $_data_text_field,
            'amount' => $_data_float_field,
            'date' => $_data_text_field,
        ));
        $this->dbforge->add_key($_primary_key, TRUE);
        $this->dbforge->create_table('std_advance',TRUE,$_table_attributes);

    	/////create table
        $this->dbforge->add_field(array(
            $_primary_key => $_data_primary_field,
            'campus_id' => $_data_int_field,
            'student_id' => $_data_int_field,
            'session_id' => $_data_int_field,
            'year' => $_data_int_field,
            'month' => $_data_int_field,
            'd1' => $_data_varchar_field,
            'd2' => $_data_varchar_field,
            'd3' => $_data_varchar_field,
            'd4' => $_data_varchar_field,
            'd5' => $_data_varchar_field,
            'd6' => $_data_varchar_field,
            'd7' => $_data_varchar_field,
            'd8' => $_data_varchar_field,
            'd9' => $_data_varchar_field,
            'd10' => $_data_varchar_field,
            'd11' => $_data_varchar_field,
            'd12' => $_data_varchar_field,
            'd13' => $_data_varchar_field,
            'd14' => $_data_varchar_field,
            'd15' => $_data_varchar_field,
            'd16' => $_data_varchar_field,
            'd17' => $_data_varchar_field,
            'd18' => $_data_varchar_field,
            'd19' => $_data_varchar_field,
            'd20' => $_data_varchar_field,
            'd21' => $_data_varchar_field,
            'd22' => $_data_varchar_field,
            'd23' => $_data_varchar_field,
            'd24' => $_data_varchar_field,
            'd25' => $_data_varchar_field,
            'd26' => $_data_varchar_field,
            'd27' => $_data_varchar_field,
            'd28' => $_data_varchar_field,
            'd29' => $_data_varchar_field,
            'd30' => $_data_varchar_field,
            'd31' => $_data_varchar_field,
        ));
        $this->dbforge->add_key($_primary_key, TRUE);
        $this->dbforge->create_table('std_attendance',TRUE,$_table_attributes);

    	/////create table
        $this->dbforge->add_field(array(
            $_primary_key => $_data_primary_field,
            'campus_id' => $_data_int_field,
            'student_id' => $_data_int_field,
            'award_id' => $_data_int_field,
            'title' => $_data_text_field,
            'remarks' => $_data_text_field,
            'image' => $_data_text_field,
            'given_by' => $_data_text_field,
            'date' => $_data_text_field,
            'jd' => $_data_int_field,
        ));
        $this->dbforge->add_key($_primary_key, TRUE);
        $this->dbforge->create_table('std_awards',TRUE,$_table_attributes);

    	/////create table
        $this->dbforge->add_field(array(
            $_primary_key => $_data_primary_field,
            'campus_id' => $_data_int_field,
            'student_id' => $_data_int_field,
            'ledger_id' => $_data_int_field,
            'voucher_id' => $_data_varchar_field,
            'remarks' => $_data_text_field,
            'amount' => $_data_float_field,
            'operation' => $_data_varchar_field,
            'type' => $_data_varchar_field,
            'date' => $_data_text_field,
            'day' => $_data_smallint_field,
            'month' => $_data_smallint_field,
            'year' => $_data_smallint_field,
            'month_number' => $_data_int_field,
            'jd' => $_data_int_field,
        ));
        $this->dbforge->add_key($_primary_key, TRUE);
        $this->dbforge->create_table('std_fee_entries',TRUE,$_table_attributes);

    	/////create table
        $this->dbforge->add_field(array(
            $_primary_key => $_data_primary_field,
            'campus_id' => $_data_int_field,
            'student_id' => $_data_int_field,
            'voucher_id' => $_data_int_field,
            'title' => $_data_text_field,
            'amount' => $_data_float_field,
            'date' => $_data_text_field,
            'month' => $_data_smallint_field,
            'year' => $_data_smallint_field,
            'month_number' => $_data_int_field,
            'jd' => $_data_int_field,
        ));
        $this->dbforge->add_key($_primary_key, TRUE);
        $this->dbforge->create_table('std_fee_history',TRUE,$_table_attributes);

    	/////create table
        $this->dbforge->add_field(array(
            $_primary_key => $_data_primary_field,
            'campus_id' => $_data_int_field,
            'session_id' => $_data_int_field,
            'student_id' => $_data_int_field,
            'class_id' => $_data_int_field,
            'roll_no' => $_data_varchar_field,
            'student_name' => $_data_varchar_field,
            'std_id' => $_data_varchar_field,
            'voucher_id' => $_data_varchar_field,
            'title' => $_data_text_field,
            'amount' => $_data_float_field,
            'amount_paid' => $_data_float_field,
            'type' => $_data_varchar_field,
            'due_date' => $_data_text_field,
            'status' => $_data_varchar_field,
            'date_paid' => $_data_varchar_field,
            'date' => $_data_text_field,
            'month' => $_data_smallint_field,
            'year' => $_data_smallint_field,
            'month_number' => $_data_int_field,
            'jd' => $_data_int_field,
        ));
        $this->dbforge->add_key($_primary_key, TRUE);
        $this->dbforge->create_table('std_fee_voucher',TRUE,$_table_attributes);

    	/////create table
        $this->dbforge->add_field(array(
            $_primary_key => $_data_primary_field,
            'campus_id' => $_data_int_field,
            'student_id' => $_data_int_field,
            'title' => $_data_text_field,
            'description' => $_data_text_field,
            'date' => $_data_text_field,
            'jd' => $_data_int_field,
        ));
        $this->dbforge->add_key($_primary_key, TRUE);
        $this->dbforge->create_table('std_history',TRUE,$_table_attributes);

    	/////create table
        $this->dbforge->add_field(array(
            $_primary_key => $_data_primary_field,
            'campus_id' => $_data_int_field,
            'doc_id' => $_data_int_field,
            'student_id' => $_data_int_field,
            'title' => $_data_text_field,
            'remarks' => $_data_text_field,
            'image' => $_data_text_field,
            'given_by' => $_data_text_field,
            'date' => $_data_text_field,
            'jd' => $_data_int_field,
        ));
        $this->dbforge->add_key($_primary_key, TRUE);
        $this->dbforge->create_table('std_punishment',TRUE,$_table_attributes);

    	/////create table
        $this->dbforge->add_field(array(
            $_primary_key => $_data_primary_field,
            'campus_id' => $_data_int_field,
            'student_id' => $_data_int_field,
            'session' => $_data_text_field,
            'class' => $_data_text_field,
            'roll_number' => $_data_text_field,
            'obtained_marks' => $_data_text_field,
            'total_marks' => $_data_text_field,
            'status' => $_data_text_field,
            'date' => $_data_text_field,
        ));
        $this->dbforge->add_key($_primary_key, TRUE);
        $this->dbforge->create_table('std_qualification',TRUE,$_table_attributes);

    	/////create table
        $this->dbforge->add_field(array(
            $_primary_key => $_data_primary_field,
            'campus_id' => $_data_int_field,
            'student_id' => $_data_int_field,
            'session_id' => $_data_int_field,
            'class_id' => $_data_int_field,
            'session' => $_data_text_field,
            'class' => $_data_text_field,
            'obt_marks' => $_data_text_field,
            'total_marks' => $_data_text_field,
            'status' => $_data_text_field,
        ));
        $this->dbforge->add_key($_primary_key, TRUE);
        $this->dbforge->create_table('std_results',TRUE,$_table_attributes);

    	/////create table
        $this->dbforge->add_field(array(
            $_primary_key => $_data_primary_field,
            'campus_id' => $_data_int_field,
            'student_id' => $_data_int_field,
            'session_id' => $_data_int_field,
            'class_id' => $_data_int_field,
            'subject_id' => $_data_int_field,
            'class_title' => $_data_text_field,
            'subject_title' => $_data_text_field,
            'grade' => $_data_text_field,
            'remarks' => $_data_text_field,
            'status' => $_data_text_field,
            'percentage' => $_data_float_field,
            'obt_marks' => $_data_smallint_field,
            'total_marks' => $_data_smallint_field,
        ));
        $this->dbforge->add_key($_primary_key, TRUE);
        $this->dbforge->create_table('std_subject_final_result',TRUE,$_table_attributes);

    	/////create table
        $this->dbforge->add_field(array(
            $_primary_key => $_data_primary_field,
            'campus_id' => $_data_int_field,
            'student_id' => $_data_int_field,
            'session_id' => $_data_int_field,
            'class_id' => $_data_int_field,
            'subject_id' => $_data_int_field,
            'homework' => $_data_text_field,
            'date' => $_data_varchar_field,
            'jd' => $_data_int_field,
        ));
        $this->dbforge->add_key($_primary_key, TRUE);
        $this->dbforge->create_table('std_subject_homework',TRUE,$_table_attributes);

    	/////create table
        $this->dbforge->add_field(array(
            $_primary_key => $_data_primary_field,
            'campus_id' => $_data_int_field,
            'student_id' => $_data_int_field,
            'session_id' => $_data_int_field,
            'class_id' => $_data_int_field,
            'subject_id' => $_data_int_field,
            'test_id' => $_data_int_field,
            'test' => $_data_text_field,
            'total_marks' => $_data_smallint_field,
            'obt_marks' => $_data_smallint_field,
            'status' => $_data_varchar_field,
            'date' => $_data_varchar_field,
            'month' => $_data_tinyint_field,
        ));
        $this->dbforge->add_key($_primary_key, TRUE);
        $this->dbforge->create_table('std_subject_test_results',TRUE,$_table_attributes);

    	/////create table
        $this->dbforge->add_field(array(
            $_primary_key => $_data_primary_field,
            'campus_id' => $_data_int_field,
            'student_id' => $_data_int_field,
            'session_id' => $_data_int_field,
            'term_id' => $_data_int_field,
            'class_id' => $_data_int_field,
            'subject_id' => $_data_int_field,
            'class_title' => $_data_text_field,
            'subject_title' => $_data_text_field,
            'total_marks' => $_data_smallint_field,
            'obt_marks' => $_data_smallint_field,
            'grade' => $_data_varchar_field,
            'remarks' => $_data_text_field,
            'status' => $_data_varchar_field,
            'percentage' => $_data_float_field,
        ));
        $this->dbforge->add_key($_primary_key, TRUE);
        $this->dbforge->create_table('std_term_result',TRUE,$_table_attributes);

    	/////create table
        $this->dbforge->add_field(array(
            $_primary_key => $_data_primary_field,
            'campus_id' => $_data_int_field,
            'staff_id' => $_data_int_field,
            'title' => $_data_text_field,
            'remarks' => $_data_text_field,
            'image' => $_data_text_field,
            'date' => $_data_varchar_field,
        ));
        $this->dbforge->add_key($_primary_key, TRUE);
        $this->dbforge->create_table('stf_acheivements',TRUE,$_table_attributes);

    	/////create table
        $this->dbforge->add_field(array(
            $_primary_key => $_data_primary_field,
            'campus_id' => $_data_int_field,
            'staff_id' => $_data_int_field,
            'title' => $_data_text_field,
            'amount' => $_data_float_field,
            'date' => $_data_varchar_field,
        ));
        $this->dbforge->add_key($_primary_key, TRUE);
        $this->dbforge->create_table('stf_advance',TRUE,$_table_attributes);

    	/////create table
        $this->dbforge->add_field(array(
            $_primary_key => $_data_primary_field,
            'campus_id' => $_data_int_field,
            'staff_id' => $_data_int_field,
            'title' => $_data_text_field,
            'description' => $_data_text_field,
            'amount' => $_data_float_field,
            'date' => $_data_varchar_field,
        ));
        $this->dbforge->add_key($_primary_key, TRUE);
        $this->dbforge->create_table('stf_allownces',TRUE,$_table_attributes);

    	/////create table
        $this->dbforge->add_field(array(
            $_primary_key => $_data_primary_field,
            'campus_id' => $_data_int_field,
            'staff_id' => $_data_int_field,
            'session_id' => $_data_int_field,
            'year' => $_data_int_field,
            'month' => $_data_int_field,
            'd1' => $_data_varchar_field,
            'd2' => $_data_varchar_field,
            'd3' => $_data_varchar_field,
            'd4' => $_data_varchar_field,
            'd5' => $_data_varchar_field,
            'd6' => $_data_varchar_field,
            'd7' => $_data_varchar_field,
            'd8' => $_data_varchar_field,
            'd9' => $_data_varchar_field,
            'd10' => $_data_varchar_field,
            'd11' => $_data_varchar_field,
            'd12' => $_data_varchar_field,
            'd13' => $_data_varchar_field,
            'd14' => $_data_varchar_field,
            'd15' => $_data_varchar_field,
            'd16' => $_data_varchar_field,
            'd17' => $_data_varchar_field,
            'd18' => $_data_varchar_field,
            'd19' => $_data_varchar_field,
            'd20' => $_data_varchar_field,
            'd21' => $_data_varchar_field,
            'd22' => $_data_varchar_field,
            'd23' => $_data_varchar_field,
            'd24' => $_data_varchar_field,
            'd25' => $_data_varchar_field,
            'd26' => $_data_varchar_field,
            'd27' => $_data_varchar_field,
            'd28' => $_data_varchar_field,
            'd29' => $_data_varchar_field,
            'd30' => $_data_varchar_field,
            'd31' => $_data_varchar_field,
        ));
        $this->dbforge->add_key($_primary_key, TRUE);
        $this->dbforge->create_table('stf_attendance',TRUE,$_table_attributes);

    	/////create table
        $this->dbforge->add_field(array(
            $_primary_key => $_data_primary_field,
            'campus_id' => $_data_int_field,
            'staff_id' => $_data_int_field,
            'award_id' => $_data_int_field,
            'title' => $_data_text_field,
            'remarks' => $_data_text_field,
            'given_by' => $_data_text_field,
            'date' => $_data_varchar_field,
        ));
        $this->dbforge->add_key($_primary_key, TRUE);
        $this->dbforge->create_table('stf_awards',TRUE,$_table_attributes);

    	/////create table
        $this->dbforge->add_field(array(
            $_primary_key => $_data_primary_field,
            'campus_id' => $_data_int_field,
            'staff_id' => $_data_int_field,
            'title' => $_data_text_field,
            'amount' => $_data_float_field,
            'date' => $_data_varchar_field,
            'month' => $_data_smallint_field,
            'year' => $_data_smallint_field,
        ));
        $this->dbforge->add_key($_primary_key, TRUE);
        $this->dbforge->create_table('stf_deductions',TRUE,$_table_attributes);

    	/////create table
        $this->dbforge->add_field(array(
            $_primary_key => $_data_primary_field,
            'campus_id' => $_data_int_field,
            'staff_id' => $_data_int_field,
            'title' => $_data_text_field,
            'description' => $_data_text_field,
            'date' => $_data_varchar_field,
        ));
        $this->dbforge->add_key($_primary_key, TRUE);
        $this->dbforge->create_table('stf_history',TRUE,$_table_attributes);

    	/////create table
        $this->dbforge->add_field(array(
            $_primary_key => $_data_primary_field,
            'campus_id' => $_data_int_field,
            'ledger_id' => $_data_int_field,
            'staff_id' => $_data_int_field,
            'voucher_id' => $_data_varchar_field,
            'remarks' => $_data_text_field,
            'amount' => $_data_float_field,
            'operation' => $_data_varchar_field,
            'type' => $_data_varchar_field,
            'date' => $_data_varchar_field,
            'day' => $_data_smallint_field,
            'month' => $_data_smallint_field,
            'year' => $_data_smallint_field,
            'month_number' => $_data_int_field,
            'jd' => $_data_int_field,
        ));
        $this->dbforge->add_key($_primary_key, TRUE);
        $this->dbforge->create_table('stf_pay_entries',TRUE,$_table_attributes);

    	/////create table
        $this->dbforge->add_field(array(
            $_primary_key => $_data_primary_field,
            'campus_id' => $_data_int_field,
            'staff_id' => $_data_int_field,
            'voucher_id' => $_data_varchar_field,
            'stf_id' => $_data_varchar_field,
            'staff_name' => $_data_text_field,
            'title' => $_data_text_field,
            'amount' => $_data_float_field,
            'type' => $_data_varchar_field,
            'status' => $_data_varchar_field,
            'due_date' => $_data_varchar_field,
            'date_paid' => $_data_varchar_field,
            'date' => $_data_varchar_field,
            'month' => $_data_smallint_field,
            'year' => $_data_smallint_field,
            'month_number' => $_data_int_field,
            'jd' => $_data_int_field,
        ));
        $this->dbforge->add_key($_primary_key, TRUE);
        $this->dbforge->create_table('stf_pay_voucher',TRUE,$_table_attributes);

    	/////create table
        $this->dbforge->add_field(array(
            $_primary_key => $_data_primary_field,
            'campus_id' => $_data_int_field,
            'staff_id' => $_data_int_field,
            'voucher_id' => $_data_int_field,
            'title' => $_data_text_field,
            'amount' => $_data_float_field,
            'date' => $_data_varchar_field,
            'month' => $_data_smallint_field,
            'year' => $_data_smallint_field,
            'month_number' => $_data_int_field,
            'jd' => $_data_int_field,
        ));
        $this->dbforge->add_key($_primary_key, TRUE);
        $this->dbforge->create_table('stf_pay_history',TRUE,$_table_attributes);

    	/////create table
        $this->dbforge->add_field(array(
            $_primary_key => $_data_primary_field,
            'campus_id' => $_data_int_field,
            'doc_id' => $_data_int_field,
            'staff_id' => $_data_int_field,
            'title' => $_data_text_field,
            'remarks' => $_data_text_field,
            'image' => $_data_text_field,
            'given_by' => $_data_text_field,
            'date' => $_data_varchar_field,
        ));
        $this->dbforge->add_key($_primary_key, TRUE);
        $this->dbforge->create_table('stf_punishment',TRUE,$_table_attributes);

    	/////create table
        $this->dbforge->add_field(array(
            $_primary_key => $_data_primary_field,
            'campus_id' => $_data_int_field,
            'staff_id' => $_data_int_field,
            'qualification' => $_data_text_field,
            'year' => $_data_varchar_field,
            'roll_number' => $_data_varchar_field,
            'registration_no' => $_data_varchar_field,
            'program' => $_data_varchar_field,
            'institute' => $_data_varchar_field,
            'date' => $_data_varchar_field,
        ));
        $this->dbforge->add_key($_primary_key, TRUE);
        $this->dbforge->create_table('stf_qualification',TRUE,$_table_attributes);

        ///////////////////////////////////////////////////////////////////////////////////
        ///////////////////////////////////////////////////////////////////////////////////
    	/////create table
        $this->dbforge->add_field(array(
            $_primary_key => $_data_primary_field,
            'campus_id' => $_data_int_field,
            'description' => $_data_text_field,
            'type' => $_data_varchar_field,
            'qty' => $_data_int_field,
            'item_price' => $_data_float_field,
            'history_type' => $_data_varchar_field,
            'date' => $_data_varchar_field,
            'day' => $_data_smallint_field,
            'month' => $_data_smallint_field,
            'year' => $_data_smallint_field,
        ));
        $this->dbforge->add_key($_primary_key, TRUE);
        $this->dbforge->create_table('stnry_history',TRUE,$_table_attributes);

    	/////create table
        $this->dbforge->add_field(array(
            $_primary_key => $_data_primary_field,
            'campus_id' => $_data_int_field,
            'item' => $_data_text_field,
            'description' => $_data_text_field,
            'type' => $_data_varchar_field,
            'qty' => $_data_int_field,
            'item_price' => $_data_float_field,
        ));
        $this->dbforge->add_key($_primary_key, TRUE);
        $this->dbforge->create_table('stnry_items',TRUE,$_table_attributes);

    	/////create table
        $this->dbforge->add_field(array(
            $_primary_key => $_data_primary_field,
            'campus_id' => $_data_int_field,
            'student_id' => $_data_varchar_field,
            'password' => $_data_text_field,
            'admission_no' => $_data_varchar_field,
            'computer_number' => $_data_int_field,
            'family_number' => $_data_int_field,
            'name' => $_data_text_field,
            'date_of_birth' => $_data_text_field,
            'nic' => $_data_text_field,
            'gender' => $_data_text_field,
            'blood_group' => $_data_text_field,
            'medical_problem' => $_data_text_field,
            'emergency_contact' => $_data_text_field,
            'mobile' => $_data_text_field,
            'guardian_name' => $_data_text_field,
            'guardian_mobile' => $_data_text_field,
            'father_name' => $_data_text_field,
            'father_nic' => $_data_text_field,
            'father_occupation' => $_data_text_field,
            'mother_name' => $_data_text_field,
            'mother_nic' => $_data_text_field,
            'address' => $_data_text_field,
            'image' => $_data_text_field,
            'religion' => $_data_text_field,
            'cast' => $_data_text_field,
            'class_id' => $_data_smallint_field,
            'session_id' => $_data_smallint_field,
            'section_id' => $_data_smallint_field,
            'roll_no' => $_data_varchar_field,
            'section' => $_data_varchar_field,
            'fee_type' => $_data_varchar_field,
            'fee' => $_data_float_field,
            'transport_fee' => $_data_float_field,
            'advance_amount' => $_data_float_field,
            'status' => $_data_varchar_field,
            'other_info' => $_data_text_field,
            'date' => $_data_varchar_field,
            'jd' => $_data_int_field,
            'month' => $_data_smallint_field,
            'year' => $_data_smallint_field,
            'month_number' => $_data_int_field,
            'admission_class' => $_data_varchar_field,
            'admission_session_id' => $_data_int_field,
            'promotion_status' => $_data_text_field,
        ));
        $this->dbforge->add_key($_primary_key, TRUE);
        $this->dbforge->create_table('student',TRUE,$_table_attributes);

    	/////create table
        $this->dbforge->add_field(array(
            $_primary_key => $_data_primary_field,
            'campus_id' => $_data_int_field,
            'message' => $_data_text_field,
            'date' => $_data_varchar_field,
            'jd' => $_data_int_field,
            'day' => $_data_smallint_field,
            'month' => $_data_smallint_field,
            'year' => $_data_smallint_field,
        ));
        $this->dbforge->add_key($_primary_key, TRUE);
        $this->dbforge->create_table('system_history',TRUE,$_table_attributes);

    	/////create table
        $this->dbforge->add_field(array(
            $_primary_key => $_data_primary_field,
            'campus_id' => $_data_int_field,
            'detail' => $_data_text_field,
            'type' => $_data_text_field,
            'date' => $_data_varchar_field,
            'stop_jd' => $_data_int_field,
        ));
        $this->dbforge->add_key($_primary_key, TRUE);
        $this->dbforge->create_table('system_notifications',TRUE,$_table_attributes);

    	/////create table
        $this->dbforge->add_field(array(
            // $_primary_key => $_data_primary_field,
            'name' => $_data_text_field,
            'value' => $_data_text_field,
        ));
        // $this->dbforge->add_key($_primary_key, TRUE);
        $this->dbforge->create_table('system_settings',TRUE,$_table_attributes);

        /////create table
        $this->dbforge->add_field(array(
            $_primary_key => $_data_primary_field,
            'campus_id' => $_data_int_field,
            'name' => $_data_text_field,
            'value' => $_data_text_field,
        ));
        $this->dbforge->add_key($_primary_key, TRUE);
        $this->dbforge->create_table('campus_settings',TRUE,$_table_attributes);

    	/////create table
        $this->dbforge->add_field(array(
            $_primary_key => $_data_primary_field,
            'campus_id' => $_data_int_field,
            'vehicle_id' => $_data_int_field,
            'route_id' => $_data_int_field,
            'passenger_id' => $_data_int_field,
            'type' => $_data_text_field,
            'passenger_name' => $_data_text_field,
            'date' => $_data_text_field,
        ));
        $this->dbforge->add_key($_primary_key, TRUE);
        $this->dbforge->create_table('transport_passenger',TRUE,$_table_attributes);

        /////create table
        $this->dbforge->add_field(array(
            $_primary_key => $_data_primary_field,
            'campus_id' => $_data_int_field,
            'name' => $_data_text_field,
        ));
        $this->dbforge->add_key($_primary_key, TRUE);
        $this->dbforge->create_table('transport_routes',TRUE,$_table_attributes);

    	/////create table
        $this->dbforge->add_field(array(
            $_primary_key => $_data_primary_field,
            'campus_id' => $_data_int_field,
            'route_id' => $_data_int_field,
            'vehicle_id' => $_data_int_field,
        ));
        $this->dbforge->add_key($_primary_key, TRUE);
        $this->dbforge->create_table('transport_route_vehicles',TRUE,$_table_attributes);

    	/////create table
        $this->dbforge->add_field(array(
            $_primary_key => $_data_primary_field,
            'campus_id' => $_data_int_field,
            'reg_no' => $_data_text_field,
            'driver' => $_data_text_field,
            'owner' => $_data_text_field,
            'contract' => $_data_text_field,
            'capacity' => $_data_smallint_field,
            'amount' => $_data_float_field,
        ));
        $this->dbforge->add_key($_primary_key, TRUE);
        $this->dbforge->create_table('transport_vehicle',TRUE,$_table_attributes);

    	/////create table
        $this->dbforge->add_field(array(
            $_primary_key => $_data_primary_field,
            'campus_id' => $_data_int_field,
            'user_id' => $_data_text_field,
            'name' => $_data_text_field,
            'email' => $_data_text_field,
            'mobile' => $_data_text_field,
            'image' => $_data_text_field,
            'password' => $_data_text_field,
            'reset_code' => $_data_text_field,
            'status' => $_data_varchar_field,
            'type' => $_data_varchar_field,
            'date' => $_data_varchar_field,
            'prm_std_info' => $_data_tinyint_field,
            'prm_stf_info' => $_data_tinyint_field,
            'prm_finance' => $_data_tinyint_field,
            'prm_class' => $_data_tinyint_field,
            'prm_library' => $_data_tinyint_field,
            'prm_stationary' => $_data_tinyint_field,
            'prm_transport' => $_data_tinyint_field,
            'prm_parents' => $_data_tinyint_field,
        ));
        $this->dbforge->add_key($_primary_key, TRUE);
        $this->dbforge->create_table('users',TRUE,$_table_attributes);

        /////create table
        $this->dbforge->add_field(array(
            $_primary_key => $_data_primary_field,
            'name' => $_data_text_field,
            'status' => $_data_varchar_field,
        ));
        $this->dbforge->add_key($_primary_key, TRUE);
        $this->dbforge->create_table('modules',TRUE,$_table_attributes);


        ////////////////////////////////////////////////////////////////////////
        ////////////////////ADD FOREIGN KEYS ///////////////////////////////////
        ////////////////////////////////////////////////////////////////////////
        $on_mod=' ON UPDATE CASCADE ';
        $on_del=' ON DELETE CASCADE ';
        $on_delmod=' ON DELETE CASCADE ON UPDATE CASCADE ';
        //////////////////////////////////////////////////////////////
        $_campus_tables=array('campus_settings','classes','exam_terms','finance_accounts','finance_accounts_ledger','finance_expenses','finance_income','lib_books','lib_issued_books','notifications','parents','parents_chat','parents_chat_detail','sms_history','sms_hook','staff','stnry_history','stnry_items','student','transport_passenger','transport_routes','transport_route_vehicles','transport_vehicle','class_sections','class_subjects','class_subject_faculty','class_subject_lessons','class_subject_progress','class_subject_questionbank','class_subject_tests','class_timetable','class_timetable_periods','std_acheivements','std_advance','std_attendance','std_awards','std_fee_entries','std_fee_history','std_fee_voucher','std_history','std_punishment','std_qualification','std_results','std_subject_final_result','std_subject_homework','std_subject_test_results','std_term_result','stf_acheivements','stf_advance','stf_allownces','stf_attendance','stf_awards','stf_deductions','stf_history','stf_pay_entries','stf_pay_history','stf_pay_voucher','stf_punishment','stf_qualification');
        $_campus_cls_tables=array('class_sections','class_subjects','class_timetable');
        $_campus_subj_tables=array('class_subject_faculty','class_subject_lessons','class_subject_progress','class_subject_questionbank','class_subject_tests');
        $_campus_std_tables=array('std_acheivements','std_advance','std_attendance','std_awards','std_fee_entries','std_fee_history','std_fee_voucher','std_history','std_punishment','std_qualification','std_results','std_subject_final_result','std_subject_homework','std_subject_test_results','std_term_result');
        $_campus_stf_tables=array('stf_acheivements','stf_advance','stf_allownces','stf_attendance','stf_awards','stf_deductions','stf_history','stf_pay_entries','stf_pay_history','stf_pay_voucher','stf_punishment','stf_qualification');
        //////////////////////////////////////////////////////////////
        $ref=' campus(mid) ';
        $fk=' campus_id ';
        foreach($_campus_tables as $tbl){
            $this->dbforge->add_column($tbl,["CONSTRAINT FOREIGN KEY($fk) REFERENCES $ref $on_delmod",]);
        }
        //////////////////////////////////////////////////////////////
        $ref=' classes(mid) ';
        $fk=' class_id ';
        foreach($_campus_cls_tables as $tbl){
            $this->dbforge->add_column($tbl,["CONSTRAINT FOREIGN KEY($fk) REFERENCES $ref $on_delmod",]);
        }
        //////////////////////////////////////////////////////////////
        $ref=' class_subjects(mid) ';
        $fk=' subject_id ';
        foreach($_campus_subj_tables as $tbl){
            $this->dbforge->add_column($tbl,["CONSTRAINT FOREIGN KEY($fk) REFERENCES $ref $on_delmod",]);
        }
        //////////////////////////////////////////////////////////////
        $ref=' student(mid) ';
        $fk=' student_id ';
        foreach($_campus_std_tables as $tbl){
            $this->dbforge->add_column($tbl,["CONSTRAINT FOREIGN KEY($fk) REFERENCES $ref $on_delmod",]);
        }
        //////////////////////////////////////////////////////////////
        $ref=' staff(mid) ';
        $fk=' staff_id ';
        foreach($_campus_stf_tables as $tbl){
            $this->dbforge->add_column($tbl,["CONSTRAINT FOREIGN KEY($fk) REFERENCES $ref $on_delmod",]);
        }



    }

    public function down(){
        $this->dbforge->drop_table('classes',TRUE);
        $this->dbforge->drop_table('class_subjects',TRUE);
        $this->dbforge->drop_table('class_subject_faculty',TRUE);
        $this->dbforge->drop_table('class_subject_lessons',TRUE);
        $this->dbforge->drop_table('class_subject_progress',TRUE);
        $this->dbforge->drop_table('class_subject_questionbank',TRUE);
        $this->dbforge->drop_table('class_subject_tests',TRUE);
        $this->dbforge->drop_table('class_timetable',TRUE);
        $this->dbforge->drop_table('class_timetable_periods',TRUE);
        $this->dbforge->drop_table('exam_terms',TRUE);
        $this->dbforge->drop_table('finance_accounts',TRUE);
        $this->dbforge->drop_table('finance_accounts_ledger',TRUE);
        $this->dbforge->drop_table('finance_expenses',TRUE);
        $this->dbforge->drop_table('finance_income',TRUE);
        $this->dbforge->drop_table('lib_books',TRUE);
        $this->dbforge->drop_table('lib_issued_books',TRUE);
        $this->dbforge->drop_table('login_session',TRUE);
        $this->dbforge->drop_table('noteboard',TRUE);
        $this->dbforge->drop_table('awards',TRUE);
        $this->dbforge->drop_table('campus',TRUE);
        $this->dbforge->drop_table('class_sections',TRUE);
        $this->dbforge->drop_table('certificates',TRUE);
        $this->dbforge->drop_table('system_files',TRUE);
        $this->dbforge->drop_table('system_modules',TRUE);
        $this->dbforge->drop_table('notifications',TRUE);
        $this->dbforge->drop_table('parents',TRUE);
        $this->dbforge->drop_table('parents_chat',TRUE);
        $this->dbforge->drop_table('parents_chat_detail',TRUE);
        $this->dbforge->drop_table('punishments',TRUE);
        $this->dbforge->drop_table('stf_roles',TRUE);
        $this->dbforge->drop_table('sessions',TRUE);
        $this->dbforge->drop_table('sms_history',TRUE);
        $this->dbforge->drop_table('sms_hook',TRUE);
        $this->dbforge->drop_table('sms_sent',TRUE);
        $this->dbforge->drop_table('staff',TRUE);
        $this->dbforge->drop_table('std_acheivements',TRUE);
        $this->dbforge->drop_table('std_advance',TRUE);
        $this->dbforge->drop_table('std_attendance',TRUE);
        $this->dbforge->drop_table('std_awards',TRUE);
        $this->dbforge->drop_table('std_fee_entries',TRUE);
        $this->dbforge->drop_table('std_fee_history',TRUE);
        $this->dbforge->drop_table('std_fee_voucher',TRUE);
        $this->dbforge->drop_table('std_history',TRUE);
        $this->dbforge->drop_table('std_punishment',TRUE);
        $this->dbforge->drop_table('std_qualification',TRUE);
        $this->dbforge->drop_table('std_results',TRUE);
        $this->dbforge->drop_table('std_subject_final_result',TRUE);
        $this->dbforge->drop_table('std_subject_homework',TRUE);
        $this->dbforge->drop_table('std_subject_test_results',TRUE);
        $this->dbforge->drop_table('std_term_result',TRUE);
        $this->dbforge->drop_table('stf_acheivements',TRUE);
        $this->dbforge->drop_table('stf_advance',TRUE);
        $this->dbforge->drop_table('stf_advance',TRUE);
        $this->dbforge->drop_table('stf_allownces',TRUE);
        $this->dbforge->drop_table('stf_attendance',TRUE);
        $this->dbforge->drop_table('stf_awards',TRUE);
        $this->dbforge->drop_table('stf_deductions',TRUE);
        $this->dbforge->drop_table('stf_history',TRUE);
        $this->dbforge->drop_table('stf_pay_entries',TRUE);
        $this->dbforge->drop_table('stf_pay_voucher',TRUE);
        $this->dbforge->drop_table('stf_pay_history',TRUE);
        $this->dbforge->drop_table('stf_punishment',TRUE);
        $this->dbforge->drop_table('stf_qualification',TRUE);
        $this->dbforge->drop_table('stnry_history',TRUE);
        $this->dbforge->drop_table('stnry_items',TRUE);
        $this->dbforge->drop_table('student',TRUE);
        $this->dbforge->drop_table('system_history',TRUE);
        $this->dbforge->drop_table('system_notifications',TRUE);
        $this->dbforge->drop_table('system_settings',TRUE);
        $this->dbforge->drop_table('campus_settings',TRUE);
        $this->dbforge->drop_table('transport_routes',TRUE);
        $this->dbforge->drop_table('transport_route_vehicles',TRUE);
        $this->dbforge->drop_table('transport_vehicle',TRUE);
        $this->dbforge->drop_table('users',TRUE);
        $this->dbforge->drop_table('modules',TRUE);
        $this->dbforge->drop_table('migrations',TRUE);
    }
}