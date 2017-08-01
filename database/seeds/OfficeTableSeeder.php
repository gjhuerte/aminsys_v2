<?php

use App\Office;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
class OfficeTableSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
         	//delete users table records
            DB::table('office')->delete();
         	//insert some dummy records
            Office::create(array(
                   'deptcode' => 'OP', 'deptname'=> 'Office of the President'
                   ));
            Office::create(array(
                   'deptcode' => 'OEVP', 'deptname'=>  'Office of the Executive Vice President'
                   ));
            Office::create(array(
                   'deptcode' => 'OVPAA', 'deptname'=> 'Office of the Vice President for Academic Affairs'
                   ));
            Office::create(array(
                   'deptcode' => 'OVPA', 'deptname'=>  'Office of the Vice President for Administration'
                   ));
            Office::create(array(
                   'deptcode' => 'OVPSAS', 'deptname'=>  'Office of the Vice President for Student Affairs and Services'
                   ));
            Office::create(array(
                   'deptcode' => 'OVPRED', 'deptname'=>  'Office of the Vice President for Research, Extension and Development'
                   ));
            Office::create(array(
                   'deptcode' => 'OVPBC', 'deptname'=> 'Office of the Vice President for Branches and Campuses'
                   ));
            Office::create(array(
                   'deptcode' => 'UBS', 'deptname'=> 'Office of the University Board Secretary'
                   ));
            Office::create(array(
                   'deptcode' => 'ULCO', 'deptname'=>  'University Legal Counsel Office'
                   ));
            Office::create(array(
                   'deptcode' => 'ICTO', 'deptname'=>  'Information and Communications Technology Office'
                   ));
            Office::create(array(
                   'deptcode' => 'GS', 'deptname'=>  'Graduate School'
                   ));
            Office::create(array(
                   'deptcode' => 'OU', 'deptname'=>  'Open University'
                   ));
            Office::create(array(
                   'deptcode' => 'CL', 'deptname'=>  'College of Law'
                   ));
            Office::create(array(
                   'deptcode' => 'CAF', 'deptname'=> 'College of Accountancy and Finance'
                   ));
            Office::create(array(
                   'deptcode' => 'CAFA', 'deptname'=>  'College of Architecture and Fine Arts'
                   ));
            Office::create(array(
                   'deptcode' => 'CAL', 'deptname'=> 'College of Arts and Letters'
                   ));
            Office::create(array(
                   'deptcode' => 'CBA', 'deptname'=> 'College of Business Administration'
                   ));
            Office::create(array(
                   'deptcode' => 'COC', 'deptname'=> 'College of Communication'
                   ));
            Office::create(array(
                   'deptcode' => 'CCIS', 'deptname'=>  'College of Computer and Information Sciences'
                   ));
            Office::create(array(
                   'deptcode' => 'COED', 'deptname'=>  'College of Education'
                   ));
            Office::create(array(
                   'deptcode' => 'COE', 'deptname'=> 'College of Engineering'
                   ));
            Office::create(array(
                   'deptcode' => 'CHK', 'deptname'=> 'College of Human Kinetics'
                   ));
            Office::create(array(
                   'deptcode' => 'CPSPA', 'deptname'=> 'College of Political Science and Public Administration'
                   ));
            Office::create(array(
                   'deptcode' => 'CS', 'deptname'=>  'College of Science'
                   ));
            Office::create(array(
                   'deptcode' => 'CSSD', 'deptname'=>  'College of Social Sciences and Development'
                   ));
            Office::create(array(
                   'deptcode' => 'CTHTM', 'deptname'=> 'College of Tourism, Hospitality and Transportation Management'
                   ));
            Office::create(array(
                   'deptcode' => 'ITECH', 'deptname'=> 'Institute of Technology'
                   ));
            Office::create(array(
                   'deptcode' => 'LHS', 'deptname'=> 'Laboratory High School'
                   ));
            Office::create(array(
                   'deptcode' => 'FAMO', 'deptname'=>  'Facilities Management Office'
                   ));
            Office::create(array(
                   'deptcode' => 'HRMD', 'deptname'=>  'Human Resources Management Department'
                   ));
            Office::create(array(
                   'deptcode' => 'MDS', 'deptname'=> 'Medical Services Department'
                   ));
            Office::create(array(
                   'deptcode' => 'BAC', 'deptname'=> 'Bids and Awards Committee'
                   ));
            Office::create(array(
                   'deptcode' => 'GSO', 'deptname'=> 'General Services Office'
                   ));
	}



}
