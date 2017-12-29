<?php

namespace App\Http\Controllers;

use Illuminate\Database\DatabaseManager;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Routing\Controller as Controller;
use Illuminate\Http\Request;
use Aws\S3\S3Client;
use DB;
use App;

class TestController extends Controller
{
    // use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function login(Request $request)
    {
        $No_Matrik = $request->input('no_matrik');
        $Password = $request->input('password');
        if($No_Matrik =="")return json_encode("no_matrik is required");
        if($Password =="")return json_encode("password is required");

        $db=app('db');
        $Database = $db->connection('content')->getPdo();

        $stmt = $Database->prepare("select * from user where no_matrik = ?");
        $stmt->execute(array($No_Matrik));
        if($row = $stmt->fetch())
        {
            $stmt1 = $Database->prepare("select password from user where password = ?");
            $stmt1->execute(array(($Password)));
            if($row1 = $stmt1->fetch())
            {            
                // $stmtpicture = $Database->prepare("select * from userprofile where userid = ?");
                // $stmtpicture->execute(array($row['userid']));
                // if($row2 = $stmtpicture->fetch())
                // {
                        $array['Status'] = "True";                      
                        $array['Username'] = $row['username'];
                        $array['Email'] = $row['email'];


                }
                else
                {
                $array['Status'] = "katalaluan";
                }
        }
            
            else
            {
                $array['Status'] = "nomatrik";
            } 

        
       
        return json_encode($array);
    }

public function daftar(Request $request)
    {
        $No_Matrik = $request->input('no_matrik');
        $Fullname = $request->input('name_penuh');
        $Username = $request->input('username');
        $Password = $request->input('password');
        $Email = $request->input('email');
        if($No_Matrik == "")return json_encode("no_matrik is required");
        if($Fullname == "")return json_encode("name_penuh is required");
        if($Username == "")return json_encode("username is required");
        if($Password == "")return json_encode("password is required");
        if($Email == "")return json_encode("email is required");

        $db=app('db');
        $Database = $db->connection('content')->getPdo();

        $stmt = $Database->prepare("select * from user where no_matrik = ?");
        $stmt->execute(array($No_Matrik));
        if($row2 = $stmt->fetch())
        {
            $array['Status'] = 'False';
        }
        else{
              $stmt2 = $Database->prepare("INSERT INTO user  SET no_matrik=? ,username=? , nama_penuh=? , password=? , email=? ");
            $stmt2->execute(array($No_Matrik, $Username, $Fullname, $Password, $Email));
            if($row2 = $stmt2)
            {
                $array['Status'] = 'True';
            }
           

        }

        return json_encode($array);
    }


   public function nota()
    {
       
        $db=app('db');
        $Database = $db->connection('content')->getPdo();

        $stmt = $Database->prepare("select * from nota ");
        $stmt->execute(array());

                while($row1 = $stmt->fetch())
                {
                        $arrayList[] = array("Tajuk" => $row1["tajuk"], "Content" => $row1["content"], "Id" => $row1["id"]);

                }
                if(!empty($arrayList))
                    {
                        $array['Status'] = "True";
                        $array['Nota'] = $arrayList;
                    }
                    else
                    {
                        $array['Status'] = "No Record Found";
                        $array['Nota'] = "-";
                    }
           
        return json_encode($array);
    }

        public function detail_nota(Request $request)
    {

        $id = $request->input('id');
        if($id =="")return json_encode("Insert id");

        $db=app('db');
        $Database = $db->connection('content')->getPdo();

        $stmt = $Database->prepare("select * from nota where id = ?");
        $stmt->execute(array($id));
        if($row2 = $stmt->fetch())
        {
         
            // if ($row2["tajuk"] == NULL) $array['Tajuk'] = "-";

            $stmtdata = $Database->prepare("select * from detail_nota where id = ?");
            $stmtdata->execute(array($row2['id']));
            if($row = $stmtdata->fetch())
            {
            $array['Status'] = 'True';
            $array['Tajuk'] = $row2['tajuk'];
            $array['Id'] = $row2['id'];
            $array['Content'] = $row['content'];
                // if ($row["content"] == NULL) $array['Content'] = "-";
            }
            if(count($array) == 0)
                {
                    $array['Status'] = "False";
                } 
        }
        else
        {
            $array['Status'] = 'False';
        }

        return json_encode($array);
    }


     public function loginarray(Request $request)
    {
        $Username = $request->input('Username');
        $password = $request->input('Password');
        if($Username =="")return json_encode("Username is required");
        if($password =="")return json_encode("Password is required");

        $db=app('db');
        $Database = $db->connection('content')->getPdo();

        $stmt = $Database->prepare("select * from user where Username = ?");
        $stmt->execute(array($Username));
        if($row = $stmt->fetch())
        {
            $stmt1 = $Database->prepare("select Password from user where Password = ?");
            $stmt1->execute(array(($password)));
            if($row1 = $stmt1->fetch())
            {            
                $stmtpicture = $Database->prepare("select * from userprofile where userid = ?");
                $stmtpicture->execute(array($row['userid']));
                while($row2 = $stmtpicture->fetch())
                {
                        $arrayList[] = array("Username: " => $row["Username"], "Umo: " => $row2["umo"], "Name: " => $row2["name"]);

                }
                if(!empty($arrayList))
                    {
                        $array['Status'] = "True";
                        $array['TimesheetAppRecord'] = $arrayList;
                        $array['Total'] = count($arrayList);
                    }
                    else
                    {
                        $array['Status'] = "No Record Found";
                        $array['TimesheetAppRecord'] = "-";
                    }
            }
            else
            {
                $array['Status'] = "Password Wrong";
            } 
        }
        else
        {
            $array['Status'] = "Username Wrong";
        }
        return json_encode($array);
    }


 

//     public function attendance(Request $request)
//     {

//         $username = $request->input('username');
//         if($username =="")return json_encode("username is required");
        
//         $db=app('db');
//         $Database = $db->connection('content')->getPdo();

//         $stmtUserId = $Database->prepare("select * from core_user where username = ?");
//         $stmtUserId->execute(array($username));
        
//         if($row = $stmtUserId->fetch())
//             {
//                 $array = array();
//                 $stmt = $Database->prepare("select * from attendance_report where user_id = ? order by id desc limit 1");
//                 $stmt->execute(array($row['userid']));
//                 while($row2 = $stmt->fetch())
//                 {
//                     $array['Status'] = "Successful";
//                     $array['Name'] = $row['fullname'];
//                     $array['Date'] = date("d-m-Y", strtotime($row2["date"]));
//                     $array['ClockIn'] = $time_in_12_hour_format  = date("g:i A", strtotime($row2["clock_in1"]));
//                     $array['ClockOut'] = $time_in_12_hour_format  = date("g:i A", strtotime($row2["clock_out1"]));
//                     $array['ClockIn2'] = $time_in_12_hour_format  = date("g:i A", strtotime($row2["clock_in2"]));
//                     $array['ClockOut2'] = $time_in_12_hour_format  = date("g:i A", strtotime($row2["clock_out2"]));
//                     $array['TotalHours'] = $row2["total_hours"];
//                     if ($row2["clock_in1"] == NULL) $array['ClockIn'] = "-";
//                     if ($row2["clock_out1"] == NULL) $array['ClockOut'] = "-";
//                     if ($row2["clock_in2"] == NULL) $array['ClockIn2'] = "-";
//                     if ($row2["clock_out2"] == NULL) $array['ClockOut2'] = "-";
//                     // $arrayList['Details'] = array("Date: " => $row2["date"], "ClockIn: " => $row2["clock_in1"], "ClockOut: " => $row2["clock_out1"], "ClockIn2: " => $row2["clock_in2"], "ClockOut2: " => $row2["clock_out2"], "TotalHours: " => $row2["total_hours"]);
//                 }
//                 if(count($arrayList['Details']) == 0)
//                 {
//                     $array['Status'] = "False";
//                 }
    
//                 return json_encode($array);
//             }
//         else
//             {
//                 $array['Status'] = "False";
//             }
        
//         return json_encode($array);
//     }

//     public function AttendanceHistory(Request $request)
//     {

//         $username = $request->input('username');
//         if($username =="")return json_encode("username is required");
        
//         $db=app('db');
//         $Database = $db->connection('content')->getPdo();

//         $stmt = $Database->prepare("SELECT * FROM core_user WHERE username = ?");
//         $stmt->execute(array($username));
//         if($row = $stmt->fetch())
//         {
//             $array['Name'] = $row['fullname'];
//             $array['StaffId'] = (string)$row['staff_id'];
//             $stmt1 = $Database->prepare("SELECT * FROM attendance_report WHERE user_id = ? ORDER BY date DESC ");
//             $stmt1->execute(array($row['userid']));
//             while($row1 = $stmt1->fetch())
//             {
//                 $arrayList[] = array("Date" => $row1["date"], "ClockIn1" => $row1["clock_in1"], "ClockOut1" => $row1["clock_out1"], "ClockIn2" => $row1["clock_in2"], "ClockOut2" => $row1["clock_out2"], "TotalHours" => $row1["total_hours"]) ;
//             }
//             if(count($arrayList) > 0)
//             {
//                 $array['Status'] = "Successful";
//                 $array['List'] = $arrayList;
//             }
//             else
//             {
//                 $array['List'] = "-";
//             }
//         }
//         else
//         {
//             $array['Status'] = "False";
//         }

//         return json_encode($array);
//     }

//     public function news(Request $request)
//     {
        
//         $stmt = Db::table("content")->select("contentid")->where("categoryid", 6)->groupBy("contentid")->get();
//         for ($i=0; $i < count($stmt); $i++) 
//         { 
//             $stmtContent = Db::table("content_info")->where("contentid", $stmt[$i]->contentid)->groupBy("contentrefid")->get();
//             for ($j=0; $j < count($stmtContent); $j++) 
//             {
//                 $arrayList[] = array("Title" => $stmtContent[$j]->title, "ContentRefId" => (string)$stmtContent[$j]->contentrefid, "Abstract" => $stmtContent[$j]->abstract) ;
//             }
//         }
//         if(count($arrayList) > 0)
//         {
//             $array['Status'] = "True";
//             $array['Details'] = $arrayList;
//         }
//         else
//         {
//             $array['Status'] = "False";
//             $array['Details'] = null;
//         }

//         return json_encode($array);
//     }

//     public function data(Request $request)
//     {

//         $contentrefid = $request->input('contentrefid');
//         if($contentrefid =="")return json_encode("Insert Content Reference Id");

//         $db=app('db');
//         $Database = $db->connection('content')->getPdo();

//         $stmt = $Database->prepare("select * from content_info where contentrefid = ?");
//         $stmt->execute(array($contentrefid));
//         if($row2 = $stmt->fetch())
//         {
//             $array['Status'] = 'True';
//             $array['Title'] = $row2['title'];
//             if ($row2["title"] == NULL) $array['Title'] = "-";

//             $stmtdata = $Database->prepare("select * from content_data where contentrefid = ?");
//             $stmtdata->execute(array($contentrefid));
//             while($row = $stmtdata->fetch())
//             {
//                 $array['Content'] = $row['data'];
//                 if ($row["data"] == NULL) $array['Content'] = "-";
//             }
//             if(count($array) == 0)
//                 {
//                     $array['Status'] = "False";
//                 } 
//         }
//         else
//         {
//             $array['Status'] = 'False';
//         }

//         return json_encode($array);
//     }

//     public function leaveapp(Request $request)
//     {

//         $username = $request->input('username');
//         if($username =="")return json_encode("Username required");
              
//         $db=app('db');
//         $Database = $db->connection('content')->getPdo();
       
//         $stmt = $Database->prepare("SELECT * FROM core_user WHERE username = ?");
//         $stmt->execute(array($username));
            
//         if($row = $stmt->fetch())
//             {
//                 if($row['staff_level'] == 1)
//                 {
//                     $stmt2 = $Database->prepare("SELECT * FROM core_user WHERE status='Y' AND (userid IN (SELECT direct_supervisor_userid FROM core_user) OR userid IN (SELECT top_supervisor_userid FROM core_user)) ORDER BY fullname");
//                     $stmt2->execute(array());
//                     while($row2 = $stmt2->fetch())
//                     {
//                         if($row2['userid'] == $row['userid'])
//                         {
//                             $arrayList[] = array("Name" => $row2['fullname']);
//                         }
//                         else
//                         {
//                             $arrayList[] = array("Name" => $row2['fullname']);
//                         }
//                     }
//                 }
//                 else
//                 {
//                     $stmt2 = $Database->prepare("SELECT * FROM core_user WHERE userid=? ORDER BY fullname");
//                     $stmt2->execute(array($row['userid']));
//                     while ($row2 = $stmt2->fetch())
//                     {
//                         if ($row2['userid'] == $row['userid'])
//                         {
//                             $arrayList[] = array("Name" => $row2['fullname']);
//                         }
//                         else
//                         {
//                             $arrayList[] = array("Name" => $row2['fullname']);
//                         }
//                     }
//                 }
//                 if(count($arrayList) > 0)
//                 {
//                     $array['Status'] = "True";
//                     $array['Approver'] = $arrayList;
//                 }
//                 else
//                 {
//                     $array['Status'] = "False";
//                     $array['List'] = null;
//                 }
//             }
//         else
//             {
//                 $array['Status'] = "False";
//             }

//         return json_encode($array);
//     }

//     public function searchapp(Request $request)
//     {

//         $username = $request->input('Username');
//         $type_name = $request->input('LeaveType');
//         $type = $request->input('ApplicationType');
//         $date_from = $request->input('DateFrom');
//         $date_to = $request->input('DateTo');

//         if($username =="")return json_encode("Insert Username");
//         if($type_name =="")return json_encode("Insert LeaveType");
//         if($type =="")return json_encode("Insert ApplicationType");
//         if($date_from =="")return json_encode("Insert DateFrom");
//         if($date_to =="")return json_encode("Insert DateTo");
        
//         $db=app('db');
//         $Database = $db->connection('content')->getPdo();

//         $array = array();
//         $arrayList = [];

//         $stmt1 = $Database->prepare("SELECT * FROM core_user WHERE username=?");
//         $stmt1->execute(array($username));
//         if($row1 = $stmt1->fetch())
//         {
//             $stmt7 = $Database->prepare("SELECT * FROM lms_type WHERE type_name = ? ");
//             $stmt7->execute(array($type_name));
//             $row7 = $stmt7->fetch();
            
//             if ($type_name == "All" && $type == "All")
//             {
//                 $stmt2 = $Database->prepare("SELECT * FROM lms_tran  WHERE date_to <= ? AND date_from >=? AND app_status!='Approved' AND app_status!='Cancelled' AND app_status!='Rejected' ORDER BY date_from DESC");
//                 $stmt2->execute(array($date_to, $date_from));
//             } 
//             elseif ($type_name == "All" && $type != "All")
//             {
//                 $stmt2 = $Database->prepare("SELECT * FROM lms_tran  WHERE type = ? AND date_to <= ? AND date_from >=? AND app_status!='Approved' AND app_status!='Cancelled' AND app_status!='Rejected' ORDER BY date_from DESC");
//                 $stmt2->execute(array($type, $date_to, $date_from));
//             } 
//             elseif ($type_name != "All" && $type == "All")
//             {
//                 $stmt2 = $Database->prepare("SELECT * FROM lms_tran  WHERE type_id = ? AND date_to <= ? AND date_from >=? AND app_status!='Approved' AND app_status!='Cancelled' AND app_status!='Rejected' ORDER BY date_from DESC");
//                 $stmt2->execute(array($row7['type_id'], $date_to, $date_from));
//             }  
//             else
//             {
//                 $stmt2 = $Database->prepare("SELECT * FROM lms_tran  WHERE type = ? AND type_id = ? AND  date_to <= ? AND date_from >=? AND app_status!='Approved' AND app_status!='Cancelled' AND app_status!='Rejected' ORDER BY date_from DESC");
//                 $stmt2->execute(array($type, $row7['type_id'], $date_to, $date_from));
//             }
            
//             while($row2 = $stmt2->fetch())
//             {

//                 $stmt3 = $Database->prepare("SELECT a.shortname,b.type_name FROM core_user a INNER JOIN lms_type b WHERE userid=? AND type_id = ? ");
//                 $stmt3->execute(array($row2['relief_staff'],$row2['type_id']));
//                 if($row3 = $stmt3->fetch())
//                 {
//                     $reliefStaff = $row3['shortname'];
//                     $typename = $row3['type_name'];
//                 }
//                 else
//                 {
//                     $reliefStaff = "-";
//                 }
                    
//                     $stmt4 = $Database->prepare("SELECT * FROM core_user WHERE userid=?");
//                     $stmt4->execute(array($row2['userid']));
//                     if($row4 = $stmt4->fetch())
//                     {           
//                         if($row4['top_supervisor_userid'] == $row1['userid'])
//                         {
//                             if($row2['app_status'] == 'Endorsed')
//                             {
//                                 $arrayList[] = array("SVName" => $row1["username"], "RefNo" => $row2["id"], "Staff" => $row4["fullname"], "AppliedDate" => date("d-m-Y", strtotime($row2["add_date"])), "LeaveType" => $typename, "Type" => $row2["type"], "ReliefStaff" => $reliefStaff,  "DateFrom" => $row2["date_from"], "DateTo" => $row2["date_to"], "Days" => $row2["days"], "ApproverRemarks" => $row2["approver_remarks"], "UserRemarks" => $row2["user_remarks"], "ApprovalStatus" => $row2["app_status"]);              $array['Status4'] ='Endorsed';
//                                     $array['Status1'] ='Approved';
//                                     $array['Status3'] ='Rejected';
//                             }
//                         }

//                         elseif($row4['direct_supervisor_userid'] == $row1['userid'])
//                         {
//                             if($row2['app_status'] == 'Pending')
//                             {
//                                 $arrayList[] = array("SVName" => $row1["username"], "RefNo" => $row2["id"], "Staff" => $row4["fullname"], "AppliedDate" => date("d-m-Y", strtotime($row2["add_date"])), "LeaveType" => $typename, "Type" => $row2["type"], "ReliefStaff" => $reliefStaff,  "DateFrom" => date("d-m-Y", strtotime($row2["date_from"])), "DateTo" => date("d-m-Y", strtotime($row2["date_to"])), "Days" => $row2["days"], "ApproverRemarks" => $row2["approver_remarks"], "UserRemarks" => $row2["user_remarks"], "ApprovalStatus" => $row2["app_status"]);         $array['Status4'] ='Pending';                                   
//                                     $array['Status1'] ='Endorsed';
//                                     $array['Status3'] ='Rejected';                      
//                             }
//                         }

//                     }                            
//             }
//             if(count($arrayList) > 0 )
//             {
//                 $array['Status'] = "True";
//                 $array['Records'] = $arrayList;
//                 $array['Total'] = count($arrayList);
//             }
//             else
//             {
//                 $array['Status'] = "No Record Found";
//                 $array['Records'] = "-";
//             }
//         }
//         else
//         {
//             $array['Status'] = "No Record Found";
//         }

//         return json_encode($array);
//     }

//     public function updatestatus(Request $request)
//     {

//         $username = $request->input('Username');
//         $id = $request->input('RefNo');
//         $app_status = $request->input('UpdateAppStatus');
//         $approver_remarks = $request->input('ApproverRemarks');

//         if($username =="")return json_encode("Insert Username");
//         if($id =="")return json_encode("Insert RefNo");
//         if($app_status =="")return json_encode("Insert UpdateAppStatus");
//         if($approver_remarks =="") $approver_remarks = 'N';

//         $db=app('db');
//         $Database = $db->connection('content')->getPdo();

//         $stmt = $Database->prepare("SELECT * FROM core_user WHERE username=?");
//         $stmt->execute(array($username));
//         if($row = $stmt->fetch())
//         {
//             $stmt2 = $Database->prepare("SELECT DISTINCT a.*,b.* FROM lms_tran a INNER JOIN lms_type b WHERE id=?");
//             $stmt2->execute(array($id));
//             if($row2 = $stmt2->fetch())
//             {
//                 $stmt3 = $Database->prepare("SELECT * FROM core_user WHERE userid=?");
//                 $stmt3->execute(array($row2['userid']));
//                 if($row3 = $stmt3->fetch())
//                 {
//                     $stmt4 = $Database->prepare("UPDATE lms_tran SET app_status=? WHERE id=?");
//                     $stmt4->execute(array($app_status, $id));
//                     ($row4 = $stmt4->fetch());
//                     {
//                         $stmt5 = $Database->prepare("UPDATE lms_tran SET approver_remarks=? WHERE id=?");
//                         $stmt5->execute(array($approver_remarks, $id));
//                         ($row5 = $stmt5->fetch());
//                         {
//                             $array['Status'] = 'Data Updated';
//                         }
//                     }                        
//                 }
//             }
//             else
//             {
//                 $arrayList = "No Record Found";
//             }
//         }

//         return json_encode($array);
//     }

//     public function lmscalendar(Request $request)
//     {
//         $date_from = $request->input('Date');
//         if($date_from =="")return json_encode("Insert Date");

//         $db=app('db');
//         $Database = $db->connection('content')->getPdo();

//         $stmt1 = $Database->prepare("SELECT DISTINCT * FROM lms_tran WHERE date_from=? AND app_status!='Cancelled'");
//         $stmt1->execute(array($date_from));
//         while($row1 = $stmt1->fetch())
//         {
//             $stmt2 = $Database->prepare("SELECT * FROM core_user WHERE userid=?");
//             $stmt2->execute(array($row1['userid']));
//             if($row2 = $stmt2->fetch())
//             {
//                 $stmt3 = $Database->prepare("SELECT * FROM lms_type WHERE type_id=?");
//                 $stmt3->execute(array($row1['type_id']));
//                 if($row3 = $stmt3->fetch())
//                 {
//                     $stmt4 = $Database->prepare("SELECT shortname FROM core_user WHERE userid=?");
//                     $stmt4->execute(array($row1['relief_staff']));
//                     if($row4 = $stmt4->fetch())
//                     {
//                         if($row1['emergency_leave'] == 'Y')
//                         {
//                             if((($row3['type_id'] == 1) || ($row3['type_id'] == 2) || ($row3['type_id'] == 6)))
//                             {
//                                 if($row1['day_type'] == 1)                      
//                                 {
//                                     $arraystatus[] = array("Date" => $row1["date_from"], "Status" => $row1["app_status"], "Name" => $row2["shortname"], "Type" => "EmergencyLeave", "LeaveType" => $row3["type_name"], "ReliefStaff" => $row4["shortname"]);
//                                 }
//                                 elseif($row1['day_type'] == 2)
//                                 {
//                                     $arraystatus[] = array("Date" => $row1["date_from"], "Status" => $row1["app_status"], "Name" => $row2["shortname"], "Type" => "EmergencyLeave", "LeaveType" => $row3["type_name"], "ReliefStaff" => $row4["shortname"], "DayType" => "AM");
//                                 }
//                                 elseif($row1['day_type'] == 3)
//                                 {
//                                     $arraystatus[] = array("Date" => $row1["date_from"], "Status" => $row1["app_status"], "Name" => $row2["shortname"], "Type" => "EmergencyLeave", "LeaveType" => $row3["type_name"], "ReliefStaff" => $row4["shortname"], "DayType" => "PM");
//                                 }
//                             }
//                         }
//                         elseif($row1['emergency_leave'] == 'N')
//                         {
//                             if((($row3['type_id'] == 1) || ($row3['type_id'] == 2) || ($row3['type_id'] == 6)))
//                             {
//                                 if($row1['day_type'] == 1)
//                                 {
//                                     $arraystatus[] = array("Date" => $row1["date_from"], "Status" => $row1["app_status"], "Name" => $row2["shortname"], "LeaveType" => $row3["type_name"], "ReliefStaff" => $row4["shortname"]);
//                                 }
//                                 elseif($row1['day_type'] == 2)
//                                 {
//                                     $arraystatus[] = array("Date" => $row1["date_from"], "Status" => $row1["app_status"], "Name" => $row2["shortname"], "LeaveType" => $row3["type_name"], "ReliefStaff" => $row4["shortname"], "DayType" => "AM");
//                                 }
//                                 elseif($row1['day_type'] == 3)
//                                 {
//                                     $arraystatus[] = array("Date" => $row1["date_from"], "Status" => $row1["app_status"], "Name" => $row2["shortname"], "LeaveType" => $row3["type_name"], "ReliefStaff" => $row4["shortname"], "DayType" => "PM");
//                                 }
//                             }
//                         }
//                     }
//                 }
//             }
//         }
//         if(!empty($arraystatus))
//         {
//             $array['Status'] = "True";
//             $array['Data'] = $arraystatus;
//             $array['Total'] = count($arraystatus);
//         }
//         else
//         {
//             $stmt5 = $Database->prepare("SELECT * FROM timesheet_ph WHERE phdate=?");
//             $stmt5->execute(array($date_from));
//             if($row5 = $stmt5->fetch())
//             {
//                 $arrayph[] = array("Holiday" => $row5["phname"]);
//             }
//             if(!empty($arrayph))
//             {
//                 $array['Status'] = "True";
//                 $array['Data'] = $arrayph;
//             }
//             else
//             {
//                 $array['Data'] = "No Record";
//             }
//         }

//         return json_encode($array);
//     }

//     public function lmslist(Request $request)
//     {

//         $username = $request->input('Username');
//         if($username =="")return json_encode("Insert Username");
        
//         $db=app('db');
//         $Database = $db->connection('content')->getPdo();

//         $array = array();
//         $arrayList = [];

//         $stmt1 = $Database->prepare("SELECT * FROM core_user WHERE username=?");
//         $stmt1->execute(array($username));
//         if($row1 = $stmt1->fetch())
//         {
//             $stmt2 = $Database->prepare("SELECT * FROM lms_tran ORDER BY date_from DESC");
//             $stmt2->execute(array());     
//             while($row2 = $stmt2->fetch())
//             {

//                 $stmt3 = $Database->prepare("SELECT a.shortname,b.type_name FROM core_user a INNER JOIN lms_type b WHERE userid=? AND type_id = ? ");
//                 $stmt3->execute(array($row2['relief_staff'],$row2['type_id']));
//                 if($row3 = $stmt3->fetch())
//                 {
//                     $reliefStaff = $row3['shortname'];
//                     $typename = $row3['type_name'];
//                 }
//                 else
//                 {
//                     $reliefStaff = "-";
//                 }
                    
//                     $stmt4 = $Database->prepare("SELECT * FROM core_user WHERE userid=? and status='Y'");
//                     $stmt4->execute(array($row2['userid']));
//                     if($row4 = $stmt4->fetch())
//                     {           
//                         if($row4['top_supervisor_userid'] == $row1['userid'])
//                         {
//                             if($row2['app_status'] == 'Endorsed')
//                             {
//                                 $arrayList[] = array("SVName" => $row1["username"], "RefNo" => (string)$row2["id"], "Staff" => $row4["fullname"], "AppliedDate" => date("d-m-Y", strtotime($row2["add_date"])), "LeaveType" => $typename, "Type" => $row2["type"], "ReliefStaff" => $reliefStaff,  "DateFrom" => $row2["date_from"], "DateTo" => $row2["date_to"], "Days" => (string)$row2["days"], "ApproverRemarks" => $row2["approver_remarks"], "UserRemarks" => $row2["user_remarks"], "ApprovalStatus" => $row2["app_status"]);
//                                 $array['Status4'] ='Endorsed';                                   
//                                 $array['Status1'] ='Approved';
//                                 $array['Status3'] ='Rejected';
//                             }
//                         }

//                         elseif($row4['direct_supervisor_userid'] == $row1['userid'])
//                         {
//                             if($row2['app_status'] == 'Pending')
//                             {
//                                 $arrayList[] = array("SVName" => $row1["username"], "RefNo" => (string)$row2["id"], "Staff" => $row4["fullname"], "AppliedDate" => date("d-m-Y", strtotime($row2["add_date"])), "LeaveType" => $typename, "Type" => $row2["type"], "ReliefStaff" => $reliefStaff,  "DateFrom" => date("d-m-Y", strtotime($row2["date_from"])), "DateTo" => date("d-m-Y", strtotime($row2["date_to"])), "Days" => (string)$row2["days"], "ApproverRemarks" => $row2["approver_remarks"], "UserRemarks" => $row2["user_remarks"], "ApprovalStatus" => $row2["app_status"]);       
//                                     $array['Status4'] ='Pending';                                   
//                                     $array['Status1'] ='Endorsed';
//                                                 $array['Status3'] ='Rejected';             
//                             }
//                         }

//                     }                            
//             }
//             if(count($arrayList) > 0 )
//             {
//                 $array['Status'] = "True";
//                 $array['RecordList'] = $arrayList;
//                 $array['Total'] = count($arrayList);
//             }
//             else
//             {
//                 $array['Status'] = "No Record Found";
//                 $array['RecordList'] = "-";
//             }
//         }
//         else
//         {
//             $array['Status'] = "No Record Found";
//         }

//         return json_encode($array);
//     }

//     public function myleave(Request $request)
//     {
//         $username = $request->input('Username');
//         $year = $request->input('Year');
//         if($username =="")return json_encode("Insert Username");
//         if($year =="")return json_encode("Insert Year");

//         $db=app('db');
//         $Database = $db->connection('content')->getPdo();
        
//         $stmt1 = $Database->prepare("SELECT * FROM core_user WHERE username = ?");
//         $stmt1->execute(array($username));
//         if($row1 = $stmt1->fetch())
//         {
//                 $array['Status'] = 'True';
//                 $array['StaffName'] = $row1['fullname'];
//                 $array['Company'] = 'ME-Tech Solution Sdn Bhd';
//                 $array['StartDate'] = $row1['start_date'];
//                 $array['ConfirmDate'] = $row1['confirm_date'];
//                 if ($row1["confirm_date"] == NULL) $array['ConfirmDate'] = "-";
//                 if ($row1["start_date"] == NULL) $array['StartDate'] = "-";
//                 $date2 = date("Y-m-d");
//                 $date1 = $row1['start_date'];
//                 $diff = abs(strtotime($date2) - strtotime($date1));
//                 $years = $diff / (365*60*60*24);
//                 $array['YearsOfEmployement']= round($years, 1);

//                 $stmt2 = $Database->prepare("SELECT DISTINCT * FROM lms_type WHERE type_id IN (SELECT typeid FROM lms_type_to_group WHERE groupid IN (SELECT groupid FROM core_usergroup WHERE userid=?))");
//                 $stmt2->execute(array($row1['userid']));
//                 while ($row2 = $stmt2->fetch())
//                 {
//                     $brought_forward = 0;
//                     $annual_entitlement = 0;
//                     $adhoc_entitlement = 0;
//                     $total = 0;
//                     $leave_taken = 0;
//                     $credit_approved = 0;
//                     $balance = 0;
//                     $stmt3 = $Database->prepare("SELECT DISTINCT * FROM lms_balance WHERE userid=? AND year=? AND leave_type_id=?");
//                     $stmt3->execute(array($row1['userid'], $year, $row2['type_id']));
//                     if ($row3 = $stmt3->fetch())
//                     {
//                         $stmt4 = $Database->prepare("SELECT SUM(days) AS days FROM lms_tran WHERE type='allocation' AND userid=? AND type_id=? AND app_status IN ('Approved') AND YEAR(add_date)=?");
//                         $stmt4->execute(array($row1['userid'], $row2['type_id'], $row3['year']));
//                         if ($row4 = $stmt4->fetch())
//                         {
//                             $date_from = date("Y-m-d", mktime(0,0,0,1,1,$year));
//                             $date_to = date("Y-m-d", mktime(0,0,0,12,31,$year));
//                             $stmt5 = $Database->prepare("SELECT SUM(days) AS taken FROM lms_tran WHERE type='leave' AND userid=? AND type_id=? AND app_status IN ('Approved','Endorsed','Pending') AND date_from BETWEEN ? AND ? AND date_to BETWEEN ? AND ?");
//                             $stmt5->execute(array($row1['userid'], $row2['type_id'], $date_from, $date_to, $date_from, $date_to));
//                             if ($row5 = $stmt5->fetch())
//                             {
//                                 $stmt6 = $Database->prepare("SELECT SUM(days) AS credit FROM lms_tran WHERE type='credit' AND userid=? AND type_id=? AND app_status IN ('Approved') AND add_date BETWEEN ? AND ?");
//                                 $stmt6->execute(array($row1['userid'], $row2['type_id'], $date_from, $date_to));
//                                 if ($row6 = $stmt6->fetch())
//                                 {
//                                    c"Year" => $row3["year"], "BroughtForward" => $row3["brought_forward"], "AnnualEntitlement" => $row3["allocated"], "AdhocEntitlement" => $row4["days"], "Total" => $row3['brought_forward'] + $row3['allocated'] + $row4['days'], "LeaveTaken" => $row5["taken"], "CreditApproved" => $row6["credit"], "Balance" => $row3['brought_forward'] + $row3['allocated'] + $row4['days'] - $row5['taken'] + $row6['credit']);
//                                 }
//  $arraystatusfalse[] = array("Status" => "false", 
//                             }
//                         }
//                     }
//                     if(!empty($arraystatus))
//                     {
//                         $array['LeaveType'] = $arraystatus;
//                     }
//                     else
//                     {
//                         $array['LeaveType'] = $arraystatusfalse;
//                     }

//                         $stmt9 = $Database->prepare("SELECT DISTINCT a.*, b.* FROM lms_tran a INNER JOIN lms_type b WHERE a.userid=? AND a.type_id='1' AND b.type_name='Annual Leave'");
//                         $stmt9->execute(array($row1['userid']));
//                         while ($row9 = $stmt9->fetch())
//                         {
//                             $stmt7 = $Database->prepare("SELECT shortname FROM core_user WHERE userid=?");
//                             $stmt7->execute(array($row9['relief_staff']));
//                             if($row7 = $stmt7->fetch())
//                             {
//                                 $reliefStaff = $row7['shortname'];
//                             }
//                             else
//                             {
//                                 $reliefStaff = "-";
//                             }
//                             $arrayAL[] = array("RefNo" => $row9["id"], "AppliedDate" => $row9["add_date"], "LeaveType" => $row9["type_id"], "DateFrom" => $row9["date_from"], "DateTo" => $row9["date_to"], "Days" => $row9["days"], "ReliefStaff" => $reliefStaff, "Status" => $row9["app_status"], "StaffRemarks" => $row9["user_remarks"], "ApproverRemarks" => $row9["approver_remarks"]);
//                         }
//                         if(!empty($arrayAL))
//                         {
//                             $array['AnnualLeave'] = $arrayAL;
//                         }
//                         else
//                         {
//                             $array['AnnualLeave'] = "No Record2";
//                         }
//                         $stmt9 = $Database->prepare("SELECT DISTINCT a.*, b.* FROM lms_tran a INNER JOIN lms_type b WHERE a.userid=? AND a.type_id='2' AND b.type_name='Medical Leave'");
//                         $stmt9->execute(array($row1['userid']));
//                         while ($row9 = $stmt9->fetch())
//                         {
//                             $stmt7 = $Database->prepare("SELECT shortname FROM core_user WHERE userid=?");
//                             $stmt7->execute(array($row9['relief_staff']));
//                             if($row7 = $stmt7->fetch())
//                             {
//                                 $reliefStaff = $row7['shortname'];
//                             }
//                             else
//                             {
//                                 $reliefStaff = "-";
//                             }
//                             $arrayML[] = array("RefNo" => $row9["id"], "AppliedDate" => $row9["add_date"], "LeaveType" => $row9["type_id"], "DateFrom" => $row9["date_from"], "DateTo" => $row9["date_to"], "Days" => $row9["days"], "ReliefStaff" => $reliefStaff, "Status" => $row9["app_status"], "StaffRemarks" => $row9["user_remarks"], "ApproverRemarks" => $row9["approver_remarks"]);
//                         }
//                         if(!empty($arrayML))
//                         {
//                             $array['MedicalLeave'] = $arrayML;
//                         }
//                         else
//                         {
//                             $array['MedicalLeave'] = "No Record";
//                         }
//                         $stmt9 = $Database->prepare("SELECT DISTINCT a.*, b.* FROM lms_tran a INNER JOIN lms_type b WHERE a.userid=? AND a.type_id='6' AND b.type_name='Unpaid Leave'");
//                         $stmt9->execute(array($row1['userid']));
//                         while ($row9 = $stmt9->fetch())
//                         {
//                             $stmt7 = $Database->prepare("SELECT shortname FROM core_user WHERE userid=?");
//                             $stmt7->execute(array($row9['relief_staff']));
//                             if($row7 = $stmt7->fetch())
//                             {
//                                 $reliefStaff = $row7['shortname'];
//                             }
//                             else
//                             {
//                                 $reliefStaff = "-";
//                             }
//                             $arrayUL[] = array("RefNo" => $row9["id"], "AppliedDate" => $row9["add_date"], "LeaveType" => $row9["type_id"], "DateFrom" => $row9["date_from"], "DateTo" => $row9["date_to"], "Days" => $row9["days"], "ReliefStaff" => $reliefStaff, "Status" => $row9["app_status"], "StaffRemarks" => $row9["user_remarks"], "ApproverRemarks" => $row9["approver_remarks"]);
//                         }
//                         if(!empty($arrayUL))
//                         {
//                             $array['UnpaidLeave'] = $arrayUL;
//                         }
//                         else
//                         {
//                             $array['UnpaidLeave'] = "No Record3";
//                         }
//                 }
//         }
//         else
//         {
//             $array['Status0'] = 'False';
//         }

//         return json_encode($array);
//     }

//     public function leaveapplist(Request $request)   
//     {

//         $username = $request->input('Username');
//         $type = $request->input('ApplicationType');
//         $app_status = $request->input('ApplicationStatus');
//         $date_from = $request->input('DateFrom');
//         $date_to = $request->input('DateTo');

//         if($username =="")return json_encode("Insert Username");
//         if($type =="")return json_encode("Insert ApplicationType");
//         if($app_status =="")return json_encode("Insert ApplicationStatus");
//         if($date_from =="")return json_encode("Insert DateFrom");
//         if($date_to =="")return json_encode("Insert DateTo");
        
//         $db=app('db');
//         $Database = $db->connection('content')->getPdo();

//         $stmt1 = $Database->prepare("SELECT * FROM core_user WHERE username=?");
//         $stmt1->execute(array($username));
//         if($row1 = $stmt1->fetch())
//         {
//             $array['Status'] = 'True';
//             if ($type == "All" && $app_status == "All")
//             {
//                 $stmt2 = $Database->prepare("SELECT DISTINCT a.*,b.* FROM lms_tran a INNER JOIN lms_type b WHERE a.userid=? AND a.date_to <=? AND a.date_from >=? AND a.type_id='1' AND b.type_name='Annual Leave'");
//                 $stmt2->execute(array($row1['userid'], $date_to, $date_from));
//             } 
//             elseif ($type == "All" && $app_status != "All")
//             {
//                 $stmt2 = $Database->prepare("SELECT DISTINCT a.*,b.* FROM lms_tran a INNER JOIN lms_type b WHERE a.app_status=? AND a.userid=? AND a.date_to <=? AND a.date_from >=? AND a.type_id='1' AND b.type_name='Annual Leave'");
//                 $stmt2->execute(array($app_status, $row1['userid'], $date_to, $date_from));
//             } 
//             elseif ($type != "All" && $app_status == "All")
//             {
//                 $stmt2 = $Database->prepare("SELECT DISTINCT a.*,b.* FROM lms_tran a INNER JOIN lms_type b WHERE a.type=? AND a.userid=? AND a.date_to <=? AND a.date_from >=? AND a.type_id='1' AND b.type_name='Annual Leave'");
//                 $stmt2->execute(array($type, $row1['userid'], $date_to, $date_from));
//             }  
//             else
//             {
//                 $stmt2 = $Database->prepare("SELECT DISTINCT a.*,b.* FROM lms_tran a INNER JOIN lms_type b WHERE a.type=? a.app_status=? AND a.userid=? AND a.date_to <=? AND a.date_from >=? AND a.type_id='1' AND b.type_name='Annual Leave'");
//                 $stmt2->execute(array($type, $app_status, $row1['userid'], $date_to, $date_from));
//             }
//             while($row2 = $stmt2->fetch())
//             {
//                 $stmt7 = $Database->prepare("SELECT shortname FROM core_user WHERE userid=?");
//                 $stmt7->execute(array($row2['relief_staff']));
//                 if($row7 = $stmt7->fetch())
//                 {
//                     $reliefStaff = $row7['shortname'];
//                 }
//                 else
//                 {
//                     $reliefStaff = "-";
//                 }
//                 $arrayAL[] = array("RefNo" => $row2["id"], "AppliedDate" => $row2["add_date"], "LeaveType" => $row2["type_name"], "DateFrom" => $row2["date_from"], "DateTo" => $row2["date_to"], "Days" => $row2["days"], "ReliefStaff" => $reliefStaff, "Status" => $row2["app_status"], "StaffRemarks" => $row2["user_remarks"], "ApproverRemarks" => $row2["approver_remarks"], "Type" => $row2["type"]);
//             }
//             if(!empty($arrayAL))
//             {
//                 $array['AnnualLeave'] = $arrayAL;
//                 $array['TotalAL'] = count($arrayAL);
//             }
//             else
//             {
//                 $array['AnnualLeave'] = "No Record";
//             }

//             $array['Status'] = 'True';
//             if ($type == "All" && $app_status == "All")
//             {
//                 $stmt2 = $Database->prepare("SELECT DISTINCT a.*,b.* FROM lms_tran a INNER JOIN lms_type b WHERE a.userid=? AND a.date_to <=? AND a.date_from >=? AND a.type_id='2' AND b.type_name='Medical Leave'");
//                 $stmt2->execute(array($row1['userid'], $date_to, $date_from));
//             } 
//             elseif ($type == "All" && $app_status != "All")
//             {
//                 $stmt2 = $Database->prepare("SELECT DISTINCT a.*,b.* FROM lms_tran a INNER JOIN lms_type b WHERE a.app_status=? AND a.userid=? AND a.date_to <=? AND a.date_from >=? AND a.type_id='2' AND b.type_name='Medical Leave'");
//                 $stmt2->execute(array($app_status, $row1['userid'], $date_to, $date_from));
//             } 
//             elseif ($type != "All" && $app_status == "All")
//             {
//                 $stmt2 = $Database->prepare("SELECT DISTINCT a.*,b.* FROM lms_tran a INNER JOIN lms_type b WHERE a.type=? AND a.userid=? AND a.date_to <=? AND a.date_from >=? AND a.type_id='2' AND b.type_name='Medical Leave'");
//                 $stmt2->execute(array($type, $row1['userid'], $date_to, $date_from));
//             }  
//             else
//             {
//                 $stmt2 = $Database->prepare("SELECT DISTINCT a.*,b.* FROM lms_tran a INNER JOIN lms_type b WHERE a.type=? a.app_status=? AND a.userid=? AND a.date_to <=? AND a.date_from >=? AND a.type_id='2' AND b.type_name='Medical Leave'");
//                 $stmt2->execute(array($type, $app_status, $row1['userid'], $date_to, $date_from));
//             }
//             while($row2 = $stmt2->fetch())
//             {
//                 $stmt7 = $Database->prepare("SELECT shortname FROM core_user WHERE userid=?");
//                 $stmt7->execute(array($row2['relief_staff']));
//                 if($row7 = $stmt7->fetch())
//                 {
//                     $reliefStaff = $row7['shortname'];
//                 }
//                 else
//                 {
//                     $reliefStaff = "-";
//                 }
//                 $arrayML[] = array("RefNo" => $row2["id"], "AppliedDate" => $row2["add_date"], "LeaveType" => $row2["type_name"], "DateFrom" => $row2["date_from"], "DateTo" => $row2["date_to"], "Days" => $row2["days"], "ReliefStaff" => $reliefStaff, "Status" => $row2["app_status"], "StaffRemarks" => $row2["user_remarks"], "ApproverRemarks" => $row2["approver_remarks"], "Type" => $row2["type"]);
//             }
//             if(!empty($arrayML))
//             {
//                 $array['MedicalLeave'] = $arrayML;
//                 $array['TotalML'] = count($arrayML);
//             }
//             else
//             {
//                 $array['MedicalLeave'] = "No Record";
//             }

//             $array['Status'] = 'True';
//             if ($type == "All" && $app_status == "All")
//             {
//                 $stmt2 = $Database->prepare("SELECT DISTINCT a.*,b.* FROM lms_tran a INNER JOIN lms_type b WHERE a.userid=? AND a.date_to <=? AND a.date_from >=? AND a.type_id='6' AND b.type_name='Unpaid Leave'");
//                 $stmt2->execute(array($row1['userid'], $date_to, $date_from));
//             } 
//             elseif ($type == "All" && $app_status != "All")
//             {
//                 $stmt2 = $Database->prepare("SELECT DISTINCT a.*,b.* FROM lms_tran a INNER JOIN lms_type b WHERE a.app_status=? AND a.userid=? AND a.date_to <=? AND a.date_from >=? AND a.type_id='6' AND b.type_name='Unpaid Leave'");
//                 $stmt2->execute(array($app_status, $row1['userid'], $date_to, $date_from));
//             } 
//             elseif ($type != "All" && $app_status == "All")
//             {
//                 $stmt2 = $Database->prepare("SELECT DISTINCT a.*,b.* FROM lms_tran a INNER JOIN lms_type b WHERE a.type=? AND a.userid=? AND a.date_to <=? AND a.date_from >=? AND a.type_id='6' AND b.type_name='Unpaid Leave'");
//                 $stmt2->execute(array($type, $row1['userid'], $date_to, $date_from));
//             }  
//             else
//             {
//                 $stmt2 = $Database->prepare("SELECT DISTINCT a.*,b.* FROM lms_tran a INNER JOIN lms_type b WHERE a.type=? a.app_status=? AND a.userid=? AND a.date_to <=? AND a.date_from >=? AND a.type_id='6' AND b.type_name='Unpaid Leave'");
//                 $stmt2->execute(array($type, $app_status, $row1['userid'], $date_to, $date_from));
//             }
//             while($row2 = $stmt2->fetch())
//             {
//                 $stmt7 = $Database->prepare("SELECT shortname FROM core_user WHERE userid=?");
//                 $stmt7->execute(array($row2['relief_staff']));
//                 if($row7 = $stmt7->fetch())
//                 {
//                     $reliefStaff = $row7['shortname'];
//                 }
//                 else
//                 {
//                     $reliefStaff = "-";
//                 }
//                 $arrayUL[] = array("RefNo" => $row2["id"], "AppliedDate" => $row2["add_date"], "LeaveType" => $row2["type_name"], "DateFrom" => $row2["date_from"], "DateTo" => $row2["date_to"], "Days" => $row2["days"], "ReliefStaff" => $reliefStaff, "Status" => $row2["app_status"], "StaffRemarks" => $row2["user_remarks"], "ApproverRemarks" => $row2["approver_remarks"], "Type" => $row2["type"]);
//             }
//             if(!empty($arrayUL))
//             {
//                 $array['UnpaidLeave'] = $arrayUL;
//                 $array['TotalUL'] = count($arrayUL);
//             }
//             else
//             {
//                 $array['UnpaidLeave'] = "No Record";
//             }
//         }
        
//         else
//         {
//             $array['Status'] = "No Record Found";
//         }

//         return json_encode($array);
//     }

//     public function applyleave(Request $request)
//     {
//         $username = $request->input('Username');
//         $type_name = $request->input('LeaveType');
//         $day_type = $request->input('DayType');
//         $date_from = $request->input('DateFrom');
//         $date_to = $request->input('DateTo');
//         $days = $request->input('Days');
//         $relief_staff = $request->input('ReliefStaff');
//         $user_remarks = $request->input('Remarks');
        
//         if($username =="")return json_encode("Insert Username");
//         if($type_name =="")return json_encode("Insert LeaveType");
//         if($day_type =="")return json_encode("Insert DayType");
//         if($date_from =="")return json_encode("Insert DateFrom");
//         if($date_to =="")return json_encode("Insert DateTo");
//         if($days =="")return json_encode("Insert Days");
//         if($relief_staff =="")return json_encode("Insert ReliefStaff");
//         if($user_remarks =="")return json_encode("Insert Remarks");

//         $db=app('db');
//         $Database = $db->connection('content')->getPdo();

//         $stmt1 = $Database->prepare("SELECT * FROM core_user WHERE username = ?");
//         $stmt1->execute(array($username));
//         if($row1 = $stmt1->fetch())
//         {
//             $array['Status'] = 'True';
//             $stmt2 = $Database->prepare("INSERT INTO lms_tran a INNER JOIN lms_type b SET b.type_name=? a.day_type=?, a.date_from=?, a.date_to=?, a.days=?, a.relief_staff=?, a.user_remarks=?");
//             $stmt2->execute(array($type_name, $day_type, $date_from, $date_to, $days, $relief_staff, $user_remarks));
//             ($row2 = $stmt2->fetch());
//             {
//                 $array['Status'] = 'Data Updated';
//             }
//         }
//         else
//         {
//             $array['Status'] = 'False';
//         }

//         return json_encode($array);
//     }

//     public function applycredit(Request $request)
//     {
//         $username = $request->input('Username');
//         $type_name = $request->input('LeaveType');
//         $day_type = $request->input('DayType');
//         $user_remarks = $request->input('Remarks');

//         if($username =="")return json_encode("Insert Username");
//         if($type_name =="")return json_encode("Insert LeaveType");
//         if($day_type =="")return json_encode("Insert DayType");
//         if($user_remarks =="")return json_encode("Insert Remarks");

//         $db=app('db');
//         $Database = $db->connection('content')->getPdo();

//         $stmt1 = $Database->prepare("SELECT * FROM core_user WHERE username = ?");
//         $stmt1->execute(array($username));
//         if($row1 = $stmt1->fetch())
//         {
//             $array['Status'] = 'True';
//         }
//         else
//         {
//             $array['Status'] = 'False';
//         }

//         return json_encode($array);
//     }

//     public function timesheetapp(Request $request)
//     {
//         $username = $request->input('Username');       
//         if($username =="")return json_encode("Insert Username");

//         $db=app('db');
//         $Database = $db->connection('content')->getPdo();

//         $stmt1 = $Database->prepare("SELECT * FROM core_user WHERE username = ?");
//         $stmt1->execute(array($username));
//         if($row1 = $stmt1->fetch())
//         {
//             $array['Status'] = "True";
//             $stmt2 = $Database->prepare("SELECT fullname FROM core_user WHERE status='Y'");
//             $stmt2->execute(array());
//             while($row2 = $stmt2->fetch())
//             {
//                 $arrayStaff[] = array("Name" => $row2["fullname"]);
//             }
//             if(count($arrayStaff) > 0 )
//             {
//                 $array['Staff'] = $arrayStaff;
//                 $array['Total'] = count($arrayStaff);
//             }
//             else
//             {
//                 $array['Staff'] = "-";
//             }

//             $stmt3 = $Database->prepare("SELECT * FROM core_user WHERE userid = ?");
//             $stmt3->execute(array($row1['userid']));
//             if($row3 = $stmt3->fetch())
//             {
//                 if((($row1['staff_level'] == 1) || ($row1['staff_level'] == 2) || ($row1['staff_level'] == 4)))
//                 {
//                     $array['Approver'] = $row3["fullname"];                   
//                     $stmt4 = $Database->prepare("SELECT * FROM timesheet_project WHERE status='Open'");
//                     $stmt4->execute(array());
//                     while($row4 = $stmt4->fetch())
//                     {
//                         if ($row4['leaderid1'] == $row1['userid'])
//                         {
//                             if((($row1['staff_level'] == 1) || ($row1['staff_level'] == 2) || ($row1['staff_level'] == 4)))
//                             {
//                                 $arrayList[] = array("ProjectName" => $row4["projectname"], "ProjectId" => $row4["projectid"]);
//                             }
//                         }
//                         elseif($row4['leaderid2'] == $row1['userid'])
//                         {
//                             if($row1['staff_level'] == 1)
//                             {
//                                 $arrayList[] = array("ProjectName" => $row4["projectname"], "ProjectId" => $row4["projectid"]);
//                             }
//                         }
//                     }
//                     if(count($arrayList) > 0 )
//                     {
//                         $array['Project'] = $arrayList;
//                     }
//                     else
//                     {
//                         $array['Project'] = "-";
//                     }
//                 }
//                 else
//                 {
//                     $array['Approver'] = $row3["fullname"];
//                 }
//             }
//         }
//         else
//         {
//             $array['Status'] = 'False';
//         }

//         return json_encode($array);
//     }

//     public function timesheettask(Request $request)
//     {
//         $projectname = $request->input('Project');
//         if($projectname =="")return json_encode("Insert Project");

//         $db=app('db');
//         $Database = $db->connection('content')->getPdo();

//         $stmt1 = $Database->prepare("SELECT * FROM timesheet_project WHERE projectname = ?");
//         $stmt1->execute(array($projectname));
//         if($row1 = $stmt1->fetch())
//         {
//             $stmt2 = $Database->prepare("SELECT * FROM timesheet_task WHERE projectid = ?");
//             $stmt2->execute(array($row1['projectid']));
//             while($row2 = $stmt2->fetch())
//             {
//                 $arrayTask[] = array("TaskName" => $row2["taskname"], "TaskId" => $row2["taskid"]);
//             }
//             if(count($arrayTask) > 0 )
//             {
//                 $array['Status'] = "True";
//                 $array['Task'] = $arrayTask;
//             }
//             else
//             {
//                 $array['Task'] = "-";
//             }
//         }

//         return json_encode($array);
//     }

//     public function timesheetsearch(Request $request)
//     {
//         $username = $request->input('Username');
//         $fullname = $request->input('Staff');
//         $status = $request->input('Status');
//         $ot = $request->input('OT');
//         $projectname = $request->input('Project');
//         $taskname = $request->input('Task');
//         if($username =="")return json_encode("Insert Username");
//         if($fullname =="")return json_encode("Insert Staff");
//         if($status =="")return json_encode("Insert Status");
//         if($ot =="")return json_encode("Insert OT");
//         if($projectname =="")return json_encode("Insert Project");
//         if($taskname =="")return json_encode("Insert Task");

//         $db=app('db');
//         $Database = $db->connection('content')->getPdo();

//         $stmt = $Database->prepare("SELECT * FROM core_user WHERE username = ?");
//         $stmt->execute(array($username));
//         if($row = $stmt->fetch())
//         {
//             if ($fullname == "All")
//             {
//                 $stmt1 = $Database->prepare("SELECT DISTINCT * FROM core_user WHERE status = 'Y'");
//                 $stmt1->execute(array());
//             }
//             else
//             {
//                 $stmt1 = $Database->prepare("SELECT * FROM core_user WHERE fullname = ?");
//                 $stmt1->execute(array($fullname));
//             }

//             while($row1 = $stmt1->fetch())
//             {
//                 if ($status == "All" && $ot == "All")
//                 {
//                     $stmt2 = $Database->prepare("SELECT * FROM timesheet_entry WHERE userid = ? ORDER BY entryid DESC");
//                     $stmt2->execute(array($row1['userid']));
//                 }
//                 elseif($status != "All" && $ot == "All")
//                 {
//                     $stmt2 = $Database->prepare("SELECT * FROM timesheet_entry WHERE userid = ? AND status = ? ORDER BY entryid DESC");
//                     $stmt2->execute(array($row1['userid'], $status));
//                 }
//                 elseif($status == "All" && $ot != "All")
//                 {
//                     $stmt2 = $Database->prepare("SELECT * FROM timesheet_entry WHERE userid = ? AND ot = ? ORDER BY entryid DESC");
//                     $stmt2->execute(array($row1['userid'], $ot));
//                 }
//                 else
//                 {
//                     $stmt2 = $Database->prepare("SELECT * FROM timesheet_entry WHERE userid = ? AND status = ? AND ot = ? ORDER BY entryid DESC");
//                     $stmt2->execute(array($row1['userid'], $status, $ot));
//                 }
//                     while($row2 = $stmt2->fetch())
//                     {
//                         if ($projectname == "All")
//                         {
//                             $stmt3 = $Database->prepare("SELECT * FROM timesheet_project WHERE projectid = ?");
//                             $stmt3->execute(array($row2['projectid']));
//                         }
//                         else
//                         {
//                             $stmt3 = $Database->prepare("SELECT * FROM timesheet_project WHERE projectname = ? AND projectid = ?");
//                             $stmt3->execute(array($projectname, $row2['projectid']));
//                         }

//                         if($row3 = $stmt3->fetch())
//                         {
//                             $stmt5 = $Database->prepare("SELECT * FROM timesheet_project_to_group WHERE projectid = ?");
//                             $stmt5->execute(array($row3['projectid']));
//                             if($row5 = $stmt5->fetch())
//                             {
//                                 $stmt6 = $Database->prepare("SELECT * FROM core_usergroup WHERE groupid = ? AND userid =?");
//                                 $stmt6->execute(array($row5['groupid'], $row1['userid']));
//                                 if($row6 = $stmt6->fetch())
//                                 {

//                                     if ($taskname == "All")
//                                     {
//                                         $stmt4 = $Database->prepare("SELECT * FROM timesheet_task WHERE taskid = ? AND projectid = ?");
//                                         $stmt4->execute(array($row2['taskid'],  $row3['projectid']));
//                                     }
//                                     else
//                                     {
//                                         $stmt4 = $Database->prepare("SELECT * FROM timesheet_task WHERE taskname = ? AND taskid = ? AND projectid = ?");
//                                         $stmt4->execute(array($taskname, $row2['taskid'],  $row3['projectid']));
//                                     }
                                    
//                                     if($row4 = $stmt4->fetch())
//                                     {
//                                         if ($row3['projectid'] == $row2['projectid'])
//                                         {
//                                             if(($row3['leaderid2'] == $row['userid']) || ($row3['leaderid1'] == $row['userid']))
//                                             {
//                                                 $arraylist[] = array("Project" => $row3["projectname"], "Task" => $row4["taskname"], "Staff" => $row1["fullname"], "EntryID" => (string)$row2["entryid"], "Date" => date("d-m-Y", strtotime($row2["entrydate"])), "Hours" => (string)$row2["spenttime"], "Notes" => $row2["notes"], "Created" => date("d-m-Y", strtotime($row2["datecreated"])), "Submitted" => date("d-m-Y", strtotime($row2["datesubmitted"])), "OT" => (string)$row2["ot"], "ApproverNotes" => $row2["approver_notes"], "Status" => $row2["status"]);
//                                                 $array['Status4'] ='Submitted';                                   
//                                                 $array['Status1'] ='Approved';
//                                                 $array['Status3'] ='Rejected';
//                                             }
//                                             else
//                                             {
//                                                 $array['Status'] = 'Not Found';
//                                             }
//                                         }
//                                         else
//                                         {
//                                             $array['Statusl'] = 'Not Found';
//                                         }
//                                     }
//                                     else
//                                     {
//                                         $array['Status'] = 'Not Found';
//                                     }
//                                 }
//                             }
//                         }
//                         else
//                         {
//                             $array['Status'] = 'Not Found';
//                         }
//                     }
//             }
//             if(!empty($arraylist))
//             {
//                 $array['Status'] = "True";
//                 $array['TimesheetAppRecord'] = $arraylist;
//                 $array['Total'] = count($arraylist);
//             }
//             else
//             {
//                 $array['Status'] = "No Record Found";
//                 $array['TimesheetAppRecord'] = "-";
//             }
//         }
//         else
//         {
//             $array['status'] = 'No Record Available';
//         }

//         return json_encode($array);

//     }

//     public function test12(Request $request)
//     {
//         $groupid = $request->input('GID');
//         $userid = $request->input('Staff');
//         if($groupid =="")return json_encode("Insert GID");
//         if($userid =="")return json_encode("Insert Staff");

//         $db=app('db');
//         $Database = $db->connection('content')->getPdo();

//         if ($groupid == "All")
//         {
//             $stmt = $Database->prepare("SELECT * FROM core_usergroup");
//             $stmt->execute(array());
//         }
//         else
//         {
//             $stmt = $Database->prepare("SELECT * FROM core_usergroup WHERE groupid = ?");
//             $stmt->execute(array($groupid));
//         }

//         while($row = $stmt->fetch())
//         {
//             if ($userid == "All")
//             {
//                 $stmt1 = $Database->prepare("SELECT * FROM core_user WHERE status='Y'");
//                 $stmt1->execute(array());
//             }
//             else
//             {
//                 $stmt1 = $Database->prepare("SELECT * FROM core_user WHERE userid= ? AND status='Y'");
//                 $stmt1->execute(array($userid));
//             }
//             if($row1 = $stmt1->fetch())
//             {
//                 $arraylist[] = array("GID" => $row["groupid"], "UID" => $row1["userid"]);
//             }
//         }
//         if(!empty($arraylist))
//         {
//             $array['Status'] = "True";
//             $array['Records'] = $arraylist;
//             $array['Total'] = count($arraylist);
//         }
//         else
//         {
//             $array['Status'] = "No Record Found";
//             $array['Records'] = "-";
//         }

//         return json_encode($array);
//     }


//     public function timesheet(Request $request)
//     {
//         $fullname = $request->input('Staff');
//         $groupid = $request->input('GID');
//         if($fullname =="")return json_encode("Insert Staff");
//         if($groupid =="")return json_encode("Insert GID");

//         $db=app('db');
//         $Database = $db->connection('content')->getPdo();

//         if ($fullname == "All")
//         {
//             $stmt = $Database->prepare("SELECT distinct * FROM core_user WHERE status='Y' ORDER BY userid ASC");
//             $stmt->execute(array());
//         }
//         else
//         {
//             $stmt = $Database->prepare("SELECT distinct * FROM core_user WHERE fullname = ?");
//             $stmt->execute(array($fullname));
//         }

//         while($row = $stmt->fetch())
//         {
//             if ($groupid == "All")
//             {
//                 $stmt1 = $Database->prepare("SELECT distinct * FROM core_usergroup WHERE userid =?");
//                 $stmt1->execute(array($row['userid']));
//             }
//             else
//             {
//                 $stmt1 = $Database->prepare("SELECT distinct * FROM core_usergroup WHERE groupid = ? AND userid =?");
//                 $stmt1->execute(array($groupid, $row['userid']));
//             }

//             while($row1 = $stmt1->fetch())
//             {
//                 $arraylist[] = array("Staff" => $row["fullname"], "GUI" => $row1["groupid"]);
//             }
//         }
//         if(!empty($arraylist))
//         {
//             $array['Status'] = "True";
//             $array['Records'] = $arraylist;
//             $array['Total'] = count($arraylist);
//         }
//         else
//         {
//             $array['Status'] = "No Record Found";
//             $array['Records'] = "-";
//         } 

//         return json_encode($array);
//     }

//     public function test3(Request $request)
//     {
//         $username = $request->input('Username');
//         $projectname = $request->input('Project');
//         $fullname = $request->input('Staff');
//         if($username =="")return json_encode("Insert Username");
//         if($projectname =="")return json_encode("Insert Project");
//         if($fullname =="")return json_encode("Insert Staff");

//         $db=app('db');
//         $Database = $db->connection('content')->getPdo();

//         $stmt = $Database->prepare("SELECT * FROM core_user WHERE username = ?");
//         $stmt->execute(array($username));
//         if($row = $stmt->fetch())
//         {
//             if ($fullname == "All")
//             {
//                 $stmt4 = $Database->prepare("SELECT DISTINCT * FROM core_user WHERE status = 'Y' ORDER BY userid ASC");
//                 $stmt4->execute(array());
//             }
//             else
//             {
//                 $stmt4 = $Database->prepare("SELECT * FROM core_user WHERE fullname = ?");
//                 $stmt4->execute(array($fullname));
//             }

//             while($row4 = $stmt4->fetch())
//             {
//                     if((($row['staff_level'] == 1) || ($row['staff_level'] == 2) || ($row['staff_level'] == 3) || ($row['staff_level'] == 4)))
//                     {
//                         if ($projectname == "All")
//                         {
//                             $stmt1 = $Database->prepare("SELECT DISTINCT * FROM timesheet_project WHERE status ='Open'");
//                             $stmt1->execute(array());
//                         }
//                         else
//                         {
//                             $stmt1 = $Database->prepare("SELECT * FROM timesheet_project WHERE projectname =? AND status ='Open'");
//                             $stmt1->execute(array($projectname));
//                         }

//                         if($row1 = $stmt1->fetch())
//                         {
//                             $stmt2 = $Database->prepare("SELECT * FROM timesheet_project_to_group WHERE projectid = ?");
//                             $stmt2->execute(array($row1['projectid']));
//                             if($row2 = $stmt2->fetch())
//                             {
//                                 $stmt3 = $Database->prepare("SELECT * FROM core_usergroup WHERE groupid = ? AND userid =?");
//                                 $stmt3->execute(array($row2['groupid'], $row4['userid']));
//                                 if($row3 = $stmt3->fetch())
//                                 {
//                                                 if($row1['leaderid2'] == $row['userid'])
//                                                 {
//                                                     $arraylist[] = array("Staff" => $row4["fullname"], "ProjectID" => $row2["projectid"], "GroupID" => $row3["groupid"]);
//                                                 }
//                                                 elseif($row1['leaderid1'] == $row['userid'])
//                                                 {
//                                                     $arraylist[] = array("Staff" => $row4["fullname"], "ProjectID" => $row2["projectid"], "GroupID" => $row3["groupid"]);
//                                                 }
//                                                 else
//                                                 {
//                                                     $array['Status'] = 'Not Found';
//                                                 }
//                                 }
//                             }
//                             else
//                             {
//                                 $array['Status'] = 'Not Found';
//                             }
//                         }
//                     }
//                     else
//                     {
//                         $array['Status'] = 'False';
//                     }
//             }
//             if(!empty($arraylist))
//             {
//                 $array['Status'] = "True";
//                 $array['Records'] = $arraylist;
//                 $array['Total'] = count($arraylist);
//             }
//             else
//             {
//                 $array['Status'] = "No Record Found";
//                 $array['Records'] = "-";
//             }
//         }
//         else
//         {
//             $array['Status'] = 'False';
//         }

//         return json_encode($array);
//     }

//     public function test4(Request $request)
//     {
//         $username = $request->input('Username');
//         $projectname = $request->input('Project');
//         if($username =="")return json_encode("Insert Username");
//         if($projectname =="")return json_encode("Insert Project");

//         $db=app('db');
//         $Database = $db->connection('content')->getPdo();

//         $stmt = $Database->prepare("SELECT * FROM core_user WHERE username = ?");
//         $stmt->execute(array($username));
//         if($row = $stmt->fetch())
//         {
//             if((($row['staff_level'] == 1) || ($row['staff_level'] == 2) || ($row['staff_level'] == 3) || ($row['staff_level'] == 4)))
//             {
//                 $array['Status'] = 'Found';
//                 if ($projectname == "All")
//                 {
//                     $stmt1 = $Database->prepare("SELECT * FROM timesheet_project WHERE status ='Open'");
//                     $stmt1->execute(array());
//                 }
//                 else
//                 {
//                     $stmt1 = $Database->prepare("SELECT * FROM timesheet_project WHERE projectname = ? AND status ='Open'");
//                     $stmt1->execute(array($projectname));
//                 }

//                 if($row1 = $stmt1->fetch())
//                 {
//                     $stmt2 = $Database->prepare("SELECT * FROM timesheet_project_to_group WHERE projectid = ?");
//                     $stmt2->execute(array($row1['projectid']));
//                     if($row2 = $stmt2->fetch())
//                     {
//                         $stmt3 = $Database->prepare("SELECT DISTINCT * FROM core_usergroup WHERE groupid = ?");
//                         $stmt3->execute(array($row2['groupid']));
//                         while($row3 = $stmt3->fetch())
//                         {
//                             // $stmt4 = $Database->prepare("SELECT fullname FROM core_user WHERE userid=? ");
//                             // $stmt4->execute(array($row3['userid']));
//                             // if($row4 = $stmt4->fetch())
//                             // {
//                             //     $Staff = $row4['fullname'];
//                             // }
//                             // else
//                             // {
//                             //     $Staff = "-";
//                             // }

//                                     if($row1['leaderid2'] == $row['userid'])
//                                     {
//                                         $arraylist[] = array("GroupID" => $row3["groupid"]);
//                                     }
//                                     elseif($row1['leaderid1'] == $row['userid'])
//                                     {
//                                         $arraylist[] = array("Userid" => $row3["userid"], "Projectid" => $row1["projectid"],);
//                                     }
//                                     else
//                                     {
//                                         $array['Status'] = 'Not Found';
//                                     }
//                         }
//                         if(!empty($arraylist))
//                         {
//                             $array['Status'] = "True";
//                             $array['Records'] = $arraylist;
//                             $array['Total'] = count($arraylist);
//                         }
//                         else
//                         {
//                             $array['Status'] = "No Record Found";
//                             $array['Records'] = "-";
//                         }
//                     }
//                 }
//                 else
//                 {
//                     $array['Status'] = 'Not Found';
//                 }
//             }
//             else
//             {
//                 $array['Status'] = 'Not Found';
//             }
//         }
//         else
//         {
//             $array['Status'] = 'Not Found';
//         }

//         return json_encode($array);
//     }

//     public function updatetimesheetstatus(Request $request)
//     {
//         $username = $request->input('Username');
//         $entryid = $request->input('EntryID');
//         $status = $request->input('Status');
//         $approver_notes = $request->input('ApproverNotes');
//         if($username =="")return json_encode("Insert Username");
//         if($entryid =="")return json_encode("Insert EntryID");
//         if($status =="")return json_encode("Insert Status");
//         if($approver_notes =="") $approver_notes ='N';

//         $db=app('db');
//         $Database = $db->connection('content')->getPdo();

//         $stmt = $Database->prepare("SELECT * FROM core_user WHERE username = ?");
//         $stmt->execute(array($username));
//         if($row = $stmt->fetch())
//         {
//             $stmt1 = $Database->prepare("SELECT * FROM timesheet_project WHERE status ='Open' ");
//             $stmt1->execute(array());
//             if($row1 = $stmt1->fetch())
//             {
//                 if(($row1['leaderid2'] == $row['userid']) || ($row1['leaderid1'] == $row['userid']))
//                 {
//                     $stmt2 = $Database->prepare("UPDATE timesheet_entry SET status = ?, approver_notes = ? WHERE entryid = ?");
//                     $stmt2->execute(array($status, $approver_notes, $entryid));
//                     ($row2 = $stmt2->fetch());
//                     {
//                         $array['Status'] = 'Data Updated';
//                     }
//                 }
//                 else
//                 {
//                     $array['Status'] = "No Record Found";
//                 }
//             }
//             else
//             {
//                 $array['Status'] = "No Record Found";
//             }
//         }
//         else
//         {
//             $array['Status'] = 'False';
//         }

//         return json_encode($array);
//     }

//     public function test1(Request $request)
//     {
//         $username = $request->input('Username');
//         $projectname = $request->input('Project');
//         $taskname = $request->input('Task');
//         if($username =="")return json_encode("Insert Username");
//         if($projectname =="")return json_encode("Insert Project");
//         if($taskname =="") $taskname ='N';

//         $db=app('db');
//         $Database = $db->connection('content')->getPdo();

//         $stmt1 = $Database->prepare("SELECT * FROM core_user WHERE username = ?");
//         $stmt1->execute(array($username));
//         if($row1 = $stmt1->fetch())
//         {
//                 if((($row1['staff_level'] == 1) || ($row1['staff_level'] == 2) || ($row1['staff_level'] == 3) || ($row1['staff_level'] == 4)))
//                 {                  
                    
//                     if ($projectname == "All")
//                     {
//                         $stmt4 = $Database->prepare("SELECT * FROM timesheet_project WHERE status='Open'");
//                         $stmt4->execute(array());
//                     }
//                     else
//                     {
//                         $stmt4 = $Database->prepare("SELECT * FROM timesheet_project WHERE projectname = ? AND status='Open'");
//                         $stmt4->execute(array($projectname));
//                     }
//                     while($row4 = $stmt4->fetch())
//                     {
//                         if ($row4['leaderid1'] == $row1['userid'])
//                         {
//                             if((($row1['staff_level'] == 1) || ($row1['staff_level'] == 2) || ($row1['staff_level'] == 4)))
//                             {
//                                 $arrayList[] = array("ProjectName" => $row4["projectname"], "ProjectId" => $row4["projectid"]);
//                             }
//                         }
//                         elseif($row4['leaderid2'] == $row1['userid'])
//                         {
//                             if($row1['staff_level'] == 1)
//                             {
//                                 $arrayList[] = array("ProjectName" => $row4["projectname"], "ProjectId" => $row4["projectid"]);
//                             }
//                         }
//                     }
//                     if(!empty($arrayList))
//                     {
//                         $array['Status'] = "True";
//                         $array['Project'] = $arrayList;
//                         $array['Totals'] = count($arrayList);
//                     }
//                     else
//                     {
//                         $array['Status'] = "No Record Found";
//                     }

//                         $stmt6 = $Database->prepare("SELECT * FROM timesheet_project WHERE projectname = ?");
//                         $stmt6->execute(array($projectname));
//                         if($row6 = $stmt6->fetch())
//                         {
//                             if ($taskname == "All")
//                             {
//                                 $stmt5 = $Database->prepare("SELECT * FROM timesheet_task WHERE projectid = ?");
//                                 $stmt5->execute(array($row6['projectid']));
//                             }
//                             else
//                             {
//                                 $stmt5 = $Database->prepare("SELECT * FROM timesheet_task WHERE taskname = ? AND projectid = ?");
//                                 $stmt5->execute(array($taskname, $row6['projectid']));
//                             }
//                             while($row5 = $stmt5->fetch())
//                             {
//                                 $arrayTask[] = array("ProjectId" => $row5["projectid"], "TaskName" => $row5["taskname"], "TaskId" => $row5["taskid"]);
//                             }
//                             if(!empty($arrayTask))
//                             {
//                                 $array['Status'] = "True";
//                                 $array['Task'] = $arrayTask;
//                                 $array['Total'] = count($arrayTask);
//                             }
//                             else
//                             {
//                                 $array['Status'] = "No Record Found";
//                             }
//                         }
//                 }
//         }
//         else
//         {
//             $array['Status'] = 'False';
//         }

//         return json_encode($array);
//     }

//     public function test2(Request $request)
//     {
//         $username = $request->input('Username');
//         $projectname = $request->input('Project');
//         $taskname = $request->input('Task');
//         if($username =="")return json_encode("Insert Username");
//         if($projectname =="")return json_encode("Insert Project");
//         if($taskname =="") $taskname='N';

//         $db=app('db');
//         $Database = $db->connection('content')->getPdo();

//         $stmt1 = $Database->prepare("SELECT * FROM core_user WHERE username = ?");
//         $stmt1->execute(array($username));
//         if($row1 = $stmt1->fetch())
//         {
//                 if((($row1['staff_level'] == 1) || ($row1['staff_level'] == 2) || ($row1['staff_level'] == 4)))
//                 {                  
                    
//                     if ($projectname == "All")
//                     {
//                         $stmt4 = $Database->prepare("SELECT * FROM timesheet_project WHERE status='Open'");
//                         $stmt4->execute(array());
//                     }
//                     else
//                     {
//                         $stmt4 = $Database->prepare("SELECT * FROM timesheet_project WHERE projectname = ? AND status='Open'");
//                         $stmt4->execute(array($projectname));
//                     }
//                     while($row4 = $stmt4->fetch())
//                     {
//                         if ($row4['leaderid1'] == $row1['userid'])
//                         {
//                             if((($row1['staff_level'] == 1) || ($row1['staff_level'] == 2) || ($row1['staff_level'] == 4)))
//                             {
//                                 $arrayList[] = array("ProjectName" => $row4["projectname"], "ProjectId" => $row4["projectid"]);
//                             }
//                         }
//                         elseif($row4['leaderid2'] == $row1['userid'])
//                         {
//                             if($row1['staff_level'] == 1)
//                             {
//                                 $arrayList[] = array("ProjectName" => $row4["projectname"], "ProjectId" => $row4["projectid"]);
//                             }
//                         }
//                     }
//                     if(!empty($arrayList))
//                     {
//                         $array['Status'] = "True";
//                         $array['Records'] = $arrayList;
//                         $array['Total'] = count($arrayList);
//                     }
//                     else
//                     {
//                         $array['Status'] = "No Record Found";
//                     }

//                     $stmt2 = $Database->prepare("SELECT * FROM timesheet_task WHERE taskname = ? AND projectid = ?");
//                     $stmt2->execute(array($taskname, $row4['projectid']));
//                     while($row2 = $stmt2->fetch())
//                     {
//                         $arraylist[] = array("Task" => $row2["taskname"], "TaskId" => $row2["taskid"]);
//                     }
//                     if(!empty($arraylist))
//                     {
//                         $array['Statusi'] = "True";
//                         $array['Record'] = $arraylist;
//                         $array['Total'] = count($arraylist);
//                     }
//                     else
//                     {
//                         $array['Statusi'] = "No ";
//                     }
//                 }
//         }
//         else
//         {
//             $array['Status'] = 'False';
//         }

//         return json_encode($array);
//     }

//     // public function test8(Request $request)
//     // {
//     //     $username = $request->input('Username');
//     //     if($username =="")return json_encode("Insert Username");

//     //     $db=app('db');
//     //     $Database = $db->connection('content')->getPdo();

//     //     if ($username == "All")
//     //     {
//     //         $stmt1 = $Database->prepare("SELECT * FROM core_user WHERE status='Y'");
//     //         $stmt1->execute(array());
//     //     }
//     //     else
//     //     {
//     //         $stmt1 = $Database->prepare("SELECT * FROM core_user WHERE username = ? AND status='Y'");
//     //         $stmt1->execute(array($username));
//     //     }

//     //     while($row1 = $stmt1->fetch())
//     //     {
//     //         $arraylist[] = array("Name" => $row1["fullname"]);
//     //     }
//     //     if(!empty($arraylist))
//     //     {
//     //         $array['Statusi'] = "True";
//     //         $array['Record'] = $arraylist;
//     //         $array['Total'] = count($arraylist);
//     //     }
//     //     else
//     //     {
//     //         $array['Statusi'] = "No ";
//     //     }

//     //     return json_encode($array);
//     // }

//     public function test8(Request $request)
//     {
//         $groupid = $request->input('Groupid');
//         $userid = $request->input('Userid');
//         if($groupid =="")return json_encode("Insert Groupid");
//         if($userid =="")return json_encode("Insert Userid");
        
//         $db=app('db');
//         $Database = $db->connection('content')->getPdo();

//         if ($groupid == "All")
//         {
//             $stmt1 = $Database->prepare("SELECT DISTINCT * FROM core_usergroup");
//             $stmt1->execute(array());
//         }
//         else
//         {
//             $stmt1 = $Database->prepare("SELECT DISTINCT * FROM core_usergroup WHERE groupid = ?");
//             $stmt1->execute(array($groupid));
//         }
//         while($row1 = $stmt1->fetch())
//         {
//             if ($userid == "All")
//             {
//                 $stmt2 = $Database->prepare("SELECT * FROM core_user");
//                 $stmt2->execute(array());
//             }
//             else
//             {
//                 $stmt2 = $Database->prepare("SELECT DISTINCT * FROM core_user WHERE userid = ?");
//                 $stmt2->execute(array($userid));
//             }
//             if($row2 = $stmt2->fetch())
//             {
//                 $arraylist[] = array("Userid" => $row1["userid"], "Groupid" => $row1["groupid"]);
//             }
//         }
//         if(!empty($arraylist))
//         {
//             $array['Statusi'] = "True";
//             $array['Record'] = $arraylist;
//             $array['Total'] = count($arraylist);
//         }
//         else
//         {
//             $array['Statusi'] = "No ";
//         }

//         return json_encode($array);
//     }

//     public function test9(Request $request)
//     {
//         $db=app('db');
//         $Database = $db->connection('content')->getPdo();

//         $stmt1 = $Database->prepare("SELECT * FROM lms_tran WHERE date_from >= DATE_ADD(NOW(), INTERVAL -3 MONTH) ORDER BY date_from ASC");
//         $stmt1->execute(array());
//         while($row1 = $stmt1->fetch())
//         {
//             $arraylist[] = array("Date" => $row1["date_from"]);
//         }
//         if(!empty($arraylist))
//         {
//             $array['Statusi'] = "True";
//             $array['Record'] = $arraylist;
//             $array['Total'] = count($arraylist);
//         }
//         else
//         {
//             $array['Statusi'] = "No ";
//         }

//         return json_encode($array);
//     }

//     public function test7(Request $request)
//     {
//         $username = $request->input('Username');
//         if($username =="")return json_encode("Insert Username");

//         $db=app('db');
//         $Database = $db->connection('content')->getPdo();

//         $stmt1 = $Database->prepare("SELECT * FROM core_user WHERE username = ?");
//         $stmt1->execute(array($username));
//         if($row1 = $stmt1->fetch())
//         {
//             if((($row1['staff_level'] == 1) || ($row1['staff_level'] == 2) || ($row1['staff_level'] == 4)))
//             {                  
//                 $stmt4 = $Database->prepare("SELECT * FROM timesheet_project WHERE status='Open'");
//                 $stmt4->execute(array());
//                 while($row4 = $stmt4->fetch())
//                 {
//                     $stmt5 = $Database->prepare("SELECT * FROM timesheet_project WHERE status='Open'");
//                     $stmt5->execute(array());
//                     while($row5 = $stmt5->fetch())
//                     {
//                             if ($row4['leaderid1'] == $row1['userid'])
//                             {
//                                 if((($row1['staff_level'] == 1) || ($row1['staff_level'] == 2) || ($row1['staff_level'] == 4)))
//                                 {
//                                     $arrayList[] = array("ProjectName" => $row4["projectname"]);
//                                 }
//                             }
//                             elseif($row4['leaderid2'] == $row1['userid'])
//                             {
//                                 if($row1['staff_level'] == 1)
//                                 {
//                                     $arrayList[] = array("ProjectName" => $row4["projectname"]);
//                                 }
//                             }
//                     }
//                 }
//                 if(!empty($arrayList))
//                 {
//                     $array['Status'] = "True";
//                     $array['Records'] = $arrayList;
//                     $array['Total'] = count($arrayList);
//                 }
//                  else
//                 {
//                     $array['Status'] = "No Record Found";
//                 }
//             }
//         }
//         else
//         {
//             $array['Status'] = 'False';
//         }

//         return json_encode($array);
//     }

// // function get_top_supervisor_userid($userid)
// // {
// //     global $db;
// //     $stmt = $Database->prepare("SELECT * FROM core_user WHERE userid=?");
// //     $stmt->execute(array($userid));
// //     $row = $stmt->fetch();
// //     return $row['top_supervisor_userid'];
// // }

// // function get_staff_fullname($userid)
// // {
// //     global $db;
// //     $stmt = $Database->prepare("SELECT * FROM core_user WHERE userid=?");
// //     $stmt->execute(array($userid));
// //     $row = $stmt->fetch();
// //     return $row['fullname'];
// // }

// // function get_staff_shortname($userid)
// // {
// //     global $db;
// //     $stmt = $Database->prepare("SELECT * FROM core_user WHERE userid=?");
// //     $stmt->execute(array($userid));
// //     $row = $stmt->fetch();
// //     return $row['shortname'];
// // }

// // function get_staff_email($userid)
// // {
// //     global $db;
// //     $stmt = $Database->prepare("SELECT * FROM core_user WHERE userid=?");
// //     $stmt->execute(array($userid));
// //     $row = $stmt->fetch();
// //     return $row['email'];
// // }

// // function get_leave_type_name($type_id)
// // {
// //     global $db;
// //     $stmt = $Database->prepare("SELECT * FROM lms_type WHERE type_id=?");
// //     $stmt->execute(array($type_id));
// //     $row = $stmt->fetch();
// //     return $row['type_name'];
// // }

// //     public function notificationemail(Request $request)
// //     {
// //         $id = $request->input('RefNo');
// //         if($id =="")return json_encode("Insert RefNo");
        
// //         $db=app('db');
// //         $Database = $db->connection('content')->getPdo();

// //         $stmt = $Database->prepare("SELECT * FROM lms_tran WHERE id = ?");
// //         $stmt->execute(array($id));
// //         if($row = $stmt->fetch())
// //         {
// //             $stmt2 = $Database->prepare("SELECT * FROM core_user WHERE userid = ?");
// //             $stmt2->execute(array($row['userid']));
// //             if($row2 = $stmt2->fetch())
// //             {
// //                 if ($row['app_status'] == 'Endorsed')
// //                 {
// //                     if ($row['type'] == 'leave')
// //                     {
// //                         // send email to top supervisor
// //                         $subject = 'Alert! Received new leave application from '.get_staff_fullname($row['userid']).' ('.$row['date_from'].' to '.$row['date_to'].')';
// //                         $message  = 'Dear '.get_staff_fullname($row2['top_supervisor_userid']).',<br/><br/>';
// //                         $message .= 'Alert! We received new leave application from '.get_staff_fullname($row['userid']).' which need your approval.<br/><br/>';
// //                         $message .= '<table>';
// //                         $message .= '<tr><td>Application Date: </td><td>'.$row['add_date'].'</td></tr>';
// //                         $message .= '<tr><td>Staff: </td><td>'.get_staff_fullname($row['userid']).'</td></tr>';
// //                         $message .= '<tr><td>Leave Type: </td><td>'.get_leave_type_name($row['type_id']).' ';
// //                         if ($row['emergency_leave'] == 'Y') { $message .= '<span style="color:red;font-weight:bold;">(Emergency Leave)</span>'; }
// //                         $message .= '</td></tr>';
// //                         $message .= '<tr><td>Leave Days: </td><td>'.$row['days'].'';
// //                         if ($row['day_type'] == '2') $message .= ' (AM)';
// //                         if ($row['day_type'] == '3') $message .= ' (PM)';
// //                         $message .= '</td></tr>';
// //                         $message .= '<tr><td>Date From: </td><td>'.$row['date_from'].'</td></tr>';
// //                         $message .= '<tr><td>Date To: </td><td>'.$row['date_to'].'</td></tr>';
// //                         $message .= '<tr><td>Relief Staff: </td><td>'.get_staff_fullname($row['relief_staff']).'</td></tr>';
// //                         $message .= '<tr><td>Staff Remarks: </td><td>'.$row['user_remarks'].'</td></tr>';
// //                         $message .= '<tr><td>Approver Remarks: </td><td>'.$row['approver_remarks'].'</td></tr>';
// //                         $message .= '<tr><td>Status: </td><td>'.$row['app_status'].'</td></tr>';
// //                         $message .= '<tr><td>Endorsed Date: </td><td>'.$row['endorsed_datetime'].'</td></tr>';
// //                         $message .= '<tr><td>1st Approver: </td><td>'.get_staff_fullname($row2['direct_supervisor_userid']).'</td></tr>';
// //                         $message .= '<tr><td>2nd Approver: </td><td>'.get_staff_fullname($row2['top_supervisor_userid']).'</td></tr>';
// //                         $message .= '</table>';
// //                         $message .= '<br/>';
// //                         $message .= 'Please review this application at:<br/>';
// //                         $message .= '<a href="http://intranet.me-tech.com.my/index.php?_m=lms&_a=leave_approval">http://intranet.me-tech.com.my/index.php?_m=lms&_a=leave_approval</a>';
// //                         $stmt4 = $Database->prepare("INSERT INTO core_mailoutgoing SET sendername=?, senderemail=?, emailto=?, subject=?, html=?, ishtml=1, datetime=NOW()");
// //                         $stmt4->execute(array("Me-Tech Intranet", "support@me-tech.com.my", get_staff_email($row2['top_supervisor_userid']), $subject, $message));
// //                     }
// //                     if ($row['type'] == 'credit')
// //                     {
// //                         $subject = 'Alert! Received new credit application from '.get_staff_fullname($row['userid']).' ('.$row['date_from'].' to '.$row['date_to'].')';
// //                         $message  = 'Dear '.get_staff_fullname($row2['top_supervisor_userid']).',<br/><br/>';
// //                         $message .= 'Alert! We received new credit application from '.get_staff_fullname($row['userid']).' which need your approval.<br/><br/>';
// //                         $message .= '<table>';
// //                         $message .= '<tr><td>Application Date: </td><td>'.$row['add_date'].'</td></tr>';
// //                         $message .= '<tr><td>Staff: </td><td>'.get_staff_fullname($row['userid']).'</td></tr>';
// //                         $message .= '<tr><td>Leave Type: </td><td>'.get_leave_type_name($row['type_id']).' (Credit)</td></tr>';
// //                         $message .= '<tr><td>Leave Days: </td><td>'.$row['days'].'';
// //                         if ($row['day_type'] == '2') $message .= ' (AM)';
// //                         if ($row['day_type'] == '3') $message .= ' (PM)';
// //                         $message .= '</td></tr>';
// //                         $message .= '<tr><td>Date From: </td><td>'.$row['date_from'].'</td></tr>';
// //                         $message .= '<tr><td>Date To: </td><td>'.$row['date_to'].'</td></tr>';
// //                         $message .= '<tr><td>Staff Remarks: </td><td>'.$row['user_remarks'].'</td></tr>';
// //                         $message .= '<tr><td>Approver Remarks: </td><td>'.$row['approver_remarks'].'</td></tr>';
// //                         $message .= '<tr><td>Status: </td><td>'.$row['app_status'].'</td></tr>';
// //                         $message .= '<tr><td>Endorsed Date: </td><td>'.$row['endorsed_datetime'].'</td></tr>';
// //                         $message .= '<tr><td>1st Approver: </td><td>'.get_staff_fullname($row2['direct_supervisor_userid']).'</td></tr>';
// //                         $message .= '<tr><td>2nd Approver: </td><td>'.get_staff_fullname($row2['top_supervisor_userid']).'</td></tr>';
// //                         $message .= '</table>';
// //                         $message .= '<br/>';

// //                         $message .= 'Attendance Report:<br/>';
// //                         $message .= '<br/>';
// //                         $message .= '<table border="1">';
// //                         $message .= '<tr>';
// //                         $message .= '<td><b>Location</b></td>';
// //                         $message .= '<td><b>Clock In</b></td>';
// //                         $message .= '<td><b>Clock Out</b></td>';
// //                         $message .= '<td><b>Clock In</b></td>';
// //                         $message .= '<td><b>Clock Out</b></td>';
// //                         $message .= '<td><b>Total Hours</b></td>';
// //                         $message .= '</tr>';
// //                         $stmt = $Database->prepare("SELECT * FROM attendance_report WHERE user_id=? AND date=?");
// //                         $stmt->execute(array($row['userid'], $row['date_from']));
// //                         if ($row = $stmt->fetch())
// //                         {
// //                             $total = 0;
// //                             $timestamp1 = $timestamp2 = $timestamp3 = $timestamp4 = 0;
// //                             $time1 = $time2 = $time3 = $time4 = "";
// //                             if ($row['clock_in1'] != "") { $timestamp1 = strtotime($row['date'].' '.$row['clock_in1']); $time1 = date("g:i A", $timestamp1); }
// //                             if ($row['clock_out1'] != "") { $timestamp2 = strtotime($row['date'].' '.$row['clock_out1']); $time2 = date("g:i A", $timestamp2); $total += $timestamp2 - $timestamp1; }
// //                             if ($row['clock_in2'] != "") { $timestamp3 = strtotime($row['date'].' '.$row['clock_in2']); $time3 = date("g:i A", $timestamp3); }
// //                             if ($row['clock_out2'] != "") { $timestamp4 = strtotime($row['date'].' '.$row['clock_out2']); $time4 = date("g:i A", $timestamp4); $total += $timestamp4 - $timestamp3; }
// //                             $message .= '<tr>';
// //                             $message .= '<td>'.$row['location'].'</td>';
// //                             $message .= '<td>'.$time1.'</td>';
// //                             $message .= '<td>'.$time2.'</td>';
// //                             $message .= '<td>'.$time3.'</td>';
// //                             $message .= '<td>'.$time4.'</td>';
// //                             $message .= '<td>'.number_format($total / 3600, 2, '.', '').'</td>';
// //                             $message .= '</tr>';
// //                         }
// //                         else
// //                         {
// //                             $message .= '<tr><td colspan="6" align="center">No Record</td></tr>';
// //                         }
// //                         $message .= '</table>';

// //                         $message .= '<br/>';

// //                         $message .= 'Please review this application at:<br/>';
// //                         $message .= '<a href="http://intranet.me-tech.com.my/index.php?_m=lms&_a=leave_approval">http://intranet.me-tech.com.my/index.php?_m=lms&_a=leave_approval</a>';
// //                         $stmt4 = $Database->prepare("INSERT INTO core_mailoutgoing SET sendername=?, senderemail=?, emailto=?, subject=?, html=?, ishtml=1, datetime=NOW()");
// //                         $stmt4->execute(array("Me-Tech Intranet", "support@me-tech.com.my", get_staff_email($row2['top_supervisor_userid']), $subject, $message));
// //                     }
// //                 }
// //                 if ($row['app_status'] == 'Approved')
// //                 {
// //                     if ($row['type'] == 'leave')
// //                     {
// //                         if ($row['reported_by'] != '')
// //                         {
// //                             // report absent
// //                             $subject = "Report absent - leave application from ".get_staff_fullname($row['userid'])." (".$row['date_from']." to ".$row['date_to'].")";
// //                             $message = '';
// //                             // medical leave
// //                             if ($row['type_id'] == 2)
// //                             {
// //                                 $message .= '<b>Please read the REMINDER at the bottom of this email.</b><br/><br/>';
// //                             }
// //                             if ($row['emergency_leave'] == "Y")
// //                             {
// //                                 $stmt3 = $Database->prepare("SELECT SUM(days) AS days FROM lms_tran WHERE date_from >= ? AND date_to <= ? AND emergency_leave='Y' AND userid=? AND app_status='Approved'");
// //                                 $stmt3->execute(array(date("Y")."-01-01", date("Y")."-12-31", $row['userid']));
// //                                 $row3 = $stmt3->fetch();
// //                                 $days = $row3['days'];
// //                                 $message .= '<div style="color:red;font-weight:bold;">Notice:</div>';
// //                                 $message .= '<div>This leave of absence is regarded emergency leave, and subject to staff justification and management review during periodic performance evaluations.  Emergency leaves are regarded as disruption to company business operations.</div>';
// //                                 $message .= '<br/>';
// //                                 $message .= '<div>Total number of emergency leaves taken by '.get_staff_fullname($row['userid']).' so far for year '.date("Y").': '.$days.'</div>';
// //                                 $message .= '<br/>';
// //                                 $message .= '<div>All staff are required to apply annual leaves 7 days in advance.</div>';
// //                                 $message .= '<br/>';
// //                             }
// //                             $message .= '<table>';
// //                             $message .= '<tr><td>Application Date: </td><td>'.$row['add_date'].'</td></tr>';
// //                             $message .= '<tr><td>Staff: </td><td>'.get_staff_fullname($row['userid']).'</td></tr>';
// //                             $message .= '<tr><td>Leave Type: </td><td>'.get_leave_type_name($row['type_id']).' ';
// //                             if ($row['emergency_leave'] == 'Y') { $message .= '<span style="color:red;font-weight:bold;">(Emergency Leave)</span>'; }
// //                             $message .= '</td></tr>';
// //                             $message .= '<tr><td>Leave Days: </td><td>'.$row['days'].'';
// //                             if ($row['day_type'] == '2') $message .= ' (AM)';
// //                             if ($row['day_type'] == '3') $message .= ' (PM)';
// //                             $message .= '</td></tr>';
// //                             $message .= '<tr><td>Date From: </td><td>'.$row['date_from'].'</td></tr>';
// //                             $message .= '<tr><td>Date To: </td><td>'.$row['date_to'].'</td></tr>';
// //                             $message .= '<tr><td>Relief Staff: </td><td>'.get_staff_fullname($row['relief_staff']).'</td></tr>';
// //                             $message .= '<tr><td>Staff Remarks: </td><td>'.$row['user_remarks'].'</td></tr>';
// //                             $message .= '<tr><td>Approver Remarks: </td><td>'.$row['approver_remarks'].'</td></tr>';
// //                             $message .= '<tr><td>Status: </td><td>'.$row['app_status'].'</td></tr>';
// //                             $message .= '<tr><td>Approved Date: </td><td>'.$row['approved_datetime'].'</td></tr>';
// //                             $message .= "<tr><td>Reported By: </td><td>".get_staff_fullname($row['reported_by'])."</td></tr>";
// //                             $message .= '</table>';
// //                             $message .= '<br/>';
// //                             if ($row['type_id'] == 2)
// //                             {
// //                                 $message .= '<br/>';
// //                                 $message .= '<b>REMINDER</b><br/><br/>';
// //                                 $message .= 'Staff who has taken Medical Leave (MC) must submit the original hardcopy MC/Doctor Certificate to Admin Department immediately after returning to work. Failure to do so will result in the MC converted to Annual Leave or Unpaid Leave (if not enough Annual Leave balance).<br/><br/>';
// //                                 $message .= 'For ease of verification, please write down the Leave Application No at the back of the MC/doctor Certificate.  Leave Application No for this MC is '.$row['id'];
// //                             }
// //                             $stmt4 = $Database->prepare("INSERT INTO core_mailoutgoing SET sendername=?, senderemail=?, emailto=?, ccto=?, subject=?, html=?, ishtml=1, datetime=NOW()");
// //                             $stmt4->execute(array("Me-Tech Intranet", "support@me-tech.com.my", get_staff_email($row['userid']), 'mgmt@me-tech.com.my, hradmin@me-tech.com.my, '.get_staff_email($row['relief_staff']), $subject, $message));
// //                         }
// //                         else
// //                         {
// //                             $subject = 'Alert! Your leave application has been approved';
// //                             $message  = 'Dear '.get_staff_fullname($row['userid']).',<br/><br/>';
// //                             $message .= 'Alert! Your leave application has been approved.<br/><br/>';
// //                             // medical leave
// //                             if ($row['type_id'] == 2)
// //                             {
// //                                 $message .= '<b>Please read the REMINDER at the bottom of this email.</b><br/><br/>';
// //                             }
// //                             if ($row['emergency_leave'] == "Y")
// //                             {
// //                                 $stmt3 = $Database->prepare("SELECT SUM(days) AS days FROM lms_tran WHERE date_from >= ? AND date_to <= ? AND emergency_leave='Y' AND userid=? AND app_status='Approved'");
// //                                 $stmt3->execute(array(date("Y")."-01-01", date("Y")."-12-31", $row['userid']));
// //                                 $row3 = $stmt3->fetch();
// //                                 $days = $row3['days'];
// //                                 $message .= '<div style="color:red;font-weight:bold;">Notice:</div>';
// //                                 $message .= '<div>This leave of absence is regarded emergency leave, and subject to staff justification and management review during periodic performance evaluations.  Emergency leaves are regarded as disruption to company business operations.</div>';
// //                                 $message .= '<br/>';
// //                                 $message .= '<div>Total number of emergency leaves taken by '.get_staff_fullname($row['userid']).' so far for year '.date("Y").': '.$days.'</div>';
// //                                 $message .= '<br/>';
// //                                 $message .= '<div>All staff are required to apply annual leaves 7 days in advance.</div>';
// //                                 $message .= '<br/>';
// //                             }
// //                             $message .= '<table>';
// //                             $message .= '<tr><td>Application Date: </td><td>'.$row['add_date'].'</td></tr>';
// //                             $message .= '<tr><td>Staff: </td><td>'.get_staff_fullname($row['userid']).'</td></tr>';
// //                             $message .= '<tr><td>Leave Type: </td><td>'.get_leave_type_name($row['type_id']).' ';
// //                             if ($row['emergency_leave'] == 'Y') { $message .= '<span style="color:red;font-weight:bold;">(Emergency Leave)</span>'; }
// //                             $message .= '</td></tr>';
// //                             $message .= '<tr><td>Leave Days: </td><td>'.$row['days'].'';
// //                             if ($row['day_type'] == '2') $message .= ' (AM)';
// //                             if ($row['day_type'] == '3') $message .= ' (PM)';
// //                             $message .= '</td></tr>';
// //                             $message .= '<tr><td>Date From: </td><td>'.$row['date_from'].'</td></tr>';
// //                             $message .= '<tr><td>Date To: </td><td>'.$row['date_to'].'</td></tr>';
// //                             $message .= '<tr><td>Relief Staff: </td><td>'.get_staff_fullname($row['relief_staff']).'</td></tr>';
// //                             $message .= '<tr><td>Staff Remarks: </td><td>'.$row['user_remarks'].'</td></tr>';
// //                             $message .= '<tr><td>Approver Remarks: </td><td>'.$row['approver_remarks'].'</td></tr>';
// //                             $message .= '<tr><td>Status: </td><td>'.$row['app_status'].'</td></tr>';
// //                             $message .= '<tr><td>Approved Date: </td><td>'.$row['approved_datetime'].'</td></tr>';
// //                             $message .= '<tr><td>1st Approver: </td><td>'.get_staff_fullname($row2['direct_supervisor_userid']).'</td></tr>';
// //                             $message .= '<tr><td>2nd Approver: </td><td>'.get_staff_fullname($row2['top_supervisor_userid']).'</td></tr>';
// //                             $message .= '</table>';
// //                             $message .= '<br/>';
// //                             if ($row['type_id'] == 2)
// //                             {
// //                                 $message .= '<br/>';
// //                                 $message .= '<b>REMINDER</b><br/><br/>';
// //                                 $message .= 'Staff who has taken Medical Leave (MC) must submit the original hardcopy MC/Doctor Certificate to Admin Department immediately after returning to work. Failure to do so will result in the MC converted to Annual Leave or Unpaid Leave (if not enough Annual Leave balance).<br/><br/>';
// //                                 $message .= 'For ease of verification, please write down the Leave Application No at the back of the MC/doctor Certificate.  Leave Application No for this MC is '.$row['id'];
// //                             }
// //                             $stmt4 = $Database->prepare("INSERT INTO core_mailoutgoing SET sendername=?, senderemail=?, emailto=?, subject=?, html=?, ishtml=1, datetime=NOW()");
// //                             $stmt4->execute(array("Me-Tech Intranet", "support@me-tech.com.my", get_staff_email($row['userid']), $subject, $message));
// //                         }
// //                         $subject = "You have been assigned as relief staff by ".get_staff_fullname($row['userid'])."";
// //                         $message = 'Dear '.get_staff_fullname($row['relief_staff']).',<br/><br/>';
// //                         $message .= 'As the relief staff for '.get_staff_fullname($row['userid']).', you are assigned to temporarily take over his/her responsibilities and tasks during the leave period. If you have issues or inability to performing as required, please talk to your supervisor immediately. Thank you.<br/><br/>';
// //                         $message .= '<table>';
// //                         $message .= '<tr><td>Application Date: </td><td>'.$row['add_date'].'</td></tr>';
// //                         $message .= '<tr><td>Staff: </td><td>'.get_staff_fullname($row['userid']).'</td></tr>';
// //                         $message .= '<tr><td>Leave Type: </td><td>'.get_leave_type_name($row['type_id']).' ';
// //                         if ($row['emergency_leave'] == 'Y') { $message .= '<span style="color:red;font-weight:bold;">(Emergency Leave)</span>'; }
// //                         $message .= '</td></tr>';
// //                         $message .= '<tr><td>Leave Days: </td><td>'.$row['days'].'';
// //                         if ($row['day_type'] == '2') $message .= ' (AM)';
// //                         if ($row['day_type'] == '3') $message .= ' (PM)';
// //                         $message .= '</td></tr>';
// //                         $message .= '<tr><td>Date From: </td><td>'.$row['date_from'].'</td></tr>';
// //                         $message .= '<tr><td>Date To: </td><td>'.$row['date_to'].'</td></tr>';
// //                         $message .= '<tr><td>Relief Staff: </td><td>'.get_staff_fullname($row['relief_staff']).'</td></tr>';
// //                         $message .= '<tr><td>Staff Remarks: </td><td>'.$row['user_remarks'].'</td></tr>';
// //                         $message .= '<tr><td>Approver Remarks: </td><td>'.$row['approver_remarks'].'</td></tr>';
// //                         $message .= '<tr><td>Status: </td><td>'.$row['app_status'].'</td></tr>';
// //                         $message .= '<tr><td>Approved Date: </td><td>'.$row['approved_datetime'].'</td></tr>';
// //                         $message .= '<tr><td>1st Approver: </td><td>'.get_staff_fullname($row2['direct_supervisor_userid']).'</td></tr>';
// //                         $message .= '<tr><td>2nd Approver: </td><td>'.get_staff_fullname($row2['top_supervisor_userid']).'</td></tr>';
// //                         $message .= '</table>';
// //                         $stmt4 = $Database->prepare("INSERT INTO core_mailoutgoing SET sendername=?, senderemail=?, emailto=?, subject=?, html=?, ishtml=1, datetime=NOW()");
// //                         $stmt4->execute(array("Me-Tech Intranet", "support@me-tech.com.my", get_staff_email($row['relief_staff']), $subject, $message));
// //                     }
// //                     if ($row['type'] == 'credit')
// //                     {
// //                         $subject = 'Alert! Your credit application has been approved';
// //                         $message  = 'Dear '.get_staff_fullname($row['userid']).',<br/><br/>';
// //                         $message .= 'Alert! Your credit application has been approved.<br/><br/>';
// //                         $message .= '<table>';
// //                         $message .= '<tr><td>Application Date: </td><td>'.$row['add_date'].'</td></tr>';
// //                         $message .= '<tr><td>Staff: </td><td>'.get_staff_fullname($row['userid']).'</td></tr>';
// //                         $message .= '<tr><td>Leave Type: </td><td>'.get_leave_type_name($row['type_id']).' (Credit)</td></tr>';
// //                         $message .= '<tr><td>Leave Days: </td><td>'.$row['days'].'';
// //                         if ($row['day_type'] == '2') $message .= ' (AM)';
// //                         if ($row['day_type'] == '3') $message .= ' (PM)';
// //                         $message .= '</td></tr>';
// //                         $message .= '<tr><td>Date From: </td><td>'.$row['date_from'].'</td></tr>';
// //                         $message .= '<tr><td>Date To: </td><td>'.$row['date_to'].'</td></tr>';
// //                         $message .= '<tr><td>Staff Remarks: </td><td>'.$row['user_remarks'].'</td></tr>';
// //                         $message .= '<tr><td>Approver Remarks: </td><td>'.$row['approver_remarks'].'</td></tr>';
// //                         $message .= '<tr><td>Status: </td><td>'.$row['app_status'].'</td></tr>';
// //                         $message .= '<tr><td>Approved Date: </td><td>'.$row['approved_datetime'].'</td></tr>';
// //                         $message .= '<tr><td>1st Approver: </td><td>'.get_staff_fullname($row2['direct_supervisor_userid']).'</td></tr>';
// //                         $message .= '<tr><td>2nd Approver: </td><td>'.get_staff_fullname($row2['top_supervisor_userid']).'</td></tr>';
// //                         $message .= '</table>';
// //                         $message .= '<br/>';
// //                         $stmt4 = $Database->prepare("INSERT INTO core_mailoutgoing SET sendername=?, senderemail=?, emailto=?, subject=?, html=?, ishtml=1, datetime=NOW()");
// //                         $stmt4->execute(array("Me-Tech Intranet", "support@me-tech.com.my", get_staff_email($row['userid']), $subject, $message));
// //                     }
// //                 }
// //                 if ($row['app_status'] == 'Rejected')
// //                 {
// //                     if ($row['type'] == 'leave')
// //                     {
// //                         $subject = 'Alert! Your leave application has been rejected';
// //                         $message  = 'Dear '.get_staff_fullname($row['userid']).',<br/><br/>';
// //                         $message .= 'Alert! Your leave application has been rejected.<br/><br/>';
// //                         $message .= '<table>';
// //                         $message .= '<tr><td>Application Date: </td><td>'.$row['add_date'].'</td></tr>';
// //                         $message .= '<tr><td>Staff: </td><td>'.get_staff_fullname($row['userid']).'</td></tr>';
// //                         $message .= '<tr><td>Leave Type: </td><td>'.get_leave_type_name($row['type_id']).' ';
// //                         if ($row['emergency_leave'] == 'Y') { $message .= '<span style="color:red;font-weight:bold;">(Emergency Leave)</span>'; }
// //                         $message .= '</td></tr>';
// //                         $message .= '<tr><td>Leave Days: </td><td>'.$row['days'].'';
// //                         if ($row['day_type'] == '2') $message .= ' (AM)';
// //                         if ($row['day_type'] == '3') $message .= ' (PM)';
// //                         $message .= '</td></tr>';
// //                         $message .= '<tr><td>Date From: </td><td>'.$row['date_from'].'</td></tr>';
// //                         $message .= '<tr><td>Date To: </td><td>'.$row['date_to'].'</td></tr>';
// //                         $message .= '<tr><td>Relief Staff: </td><td>'.get_staff_fullname($row['relief_staff']).'</td></tr>';
// //                         $message .= '<tr><td>Staff Remarks: </td><td>'.$row['user_remarks'].'</td></tr>';
// //                         $message .= '<tr><td>Approver Remarks: </td><td>'.$row['approver_remarks'].'</td></tr>';
// //                         $message .= '<tr><td>Status: </td><td>'.$row['app_status'].'</td></tr>';
// //                         $message .= '<tr><td>1st Approver: </td><td>'.get_staff_fullname($row2['direct_supervisor_userid']).'</td></tr>';
// //                         $message .= '<tr><td>2nd Approver: </td><td>'.get_staff_fullname($row2['top_supervisor_userid']).'</td></tr>';
// //                         $message .= '</table>';
// //                         $message .= '<br/>';
// //                         $stmt4 = $Database->prepare("INSERT INTO core_mailoutgoing SET sendername=?, senderemail=?, emailto=?, subject=?, html=?, ishtml=1, datetime=NOW()");
// //                         $stmt4->execute(array("Me-Tech Intranet", "support@me-tech.com.my", get_staff_email($row2['top_supervisor_userid']), $subject, $message));
// //                     }
// //                     if ($row['type'] == 'credit')
// //                     {
// //                         $subject = 'Alert! Your credit application has been rejected';
// //                         $message  = 'Dear '.get_staff_fullname($row['userid']).',<br/><br/>';
// //                         $message .= 'Alert! Your credit application has been rejected.<br/><br/>';
// //                         $message .= '<table>';
// //                         $message .= '<tr><td>Application Date: </td><td>'.$row['add_date'].'</td></tr>';
// //                         $message .= '<tr><td>Staff: </td><td>'.get_staff_fullname($row['userid']).'</td></tr>';
// //                         $message .= '<tr><td>Leave Type: </td><td>'.get_leave_type_name($row['type_id']).' (Credit)</td></tr>';
// //                         $message .= '<tr><td>Leave Days: </td><td>'.$row['days'].'';
// //                         if ($row['day_type'] == '2') $message .= ' (AM)';
// //                         if ($row['day_type'] == '3') $message .= ' (PM)';
// //                         $message .= '</td></tr>';
// //                         $message .= '<tr><td>Date From: </td><td>'.$row['date_from'].'</td></tr>';
// //                         $message .= '<tr><td>Date To: </td><td>'.$row['date_to'].'</td></tr>';
// //                         $message .= '<tr><td>Staff Remarks: </td><td>'.$row['user_remarks'].'</td></tr>';
// //                         $message .= '<tr><td>Approver Remarks: </td><td>'.$row['approver_remarks'].'</td></tr>';
// //                         $message .= '<tr><td>Status: </td><td>'.$row['app_status'].'</td></tr>';
// //                         $message .= '<tr><td>1st Approver: </td><td>'.get_staff_fullname($row2['direct_supervisor_userid']).'</td></tr>';
// //                         $message .= '<tr><td>2nd Approver: </td><td>'.get_staff_fullname($row2['top_supervisor_userid']).'</td></tr>';
// //                         $message .= '</table>';
// //                         $message .= '<br/>';
// //                         $stmt4 = $Database->prepare("INSERT INTO core_mailoutgoing SET sendername=?, senderemail=?, emailto=?, subject=?, html=?, ishtml=1, datetime=NOW()");
// //                         $stmt4->execute(array("Me-Tech Intranet", "support@me-tech.com.my", get_staff_email($row['userid']), $subject, $message));
// //                     }
// //                 }
// //             }
// //         }
// //         else
// //         {
// //             $array['statuss'] = 'No Record Available';
// //         }

// //         return json_encode($array);
// //     }

//     public function timesheetstatus(Request $request)
//     {
//         $status = $request->input('Status');
//         if($status =="")return json_encode("Insert Status");

//         $db=app('db');
//         $Database = $db->connection('content')->getPdo();

//         $stmt = $Database->prepare("SELECT DISTINCT * FROM timesheet_entry WHERE status =?");
//         $stmt->execute(array($status));
//         if($row = $stmt->fetch())
//         {
//             $array['Status'] = "True";
//             $array['Status4'] ='Submitted';                                   
//             $array['Status1'] ='Approved';
//             $array['Status3'] ='Rejected';
//         }
//         else
//         {
//             $array['Status'] = "No Record Found";
//         }

//         return json_encode($array);
//     }

//     public function test13(Request $request)
//     {
//         $username = $request->input('Username');
//         if($username =="")return json_encode("Insert Username");

//         $db=app('db');
//         $Database = $db->connection('content')->getPdo();

//         $stmt = $Database->prepare("SELECT * FROM core_user WHERE username = ?");
//         $stmt->execute(array($username));
//         if($row = $stmt->fetch())
//         {
//                     $stmt2 = $Database->prepare("SELECT * FROM timesheet_entry WHERE status='Submitted' ORDER BY entryid DESC");
//                     $stmt2->execute(array());
//                     while($row2 = $stmt2->fetch())
//                     {
//                             $stmt3 = $Database->prepare("SELECT * FROM timesheet_project WHERE projectid = ?");
//                             $stmt3->execute(array($row2['projectid']));
//                             if($row3 = $stmt3->fetch())
//                         {
//                             $stmt5 = $Database->prepare("SELECT * FROM timesheet_project_to_group WHERE projectid = ?");
//                             $stmt5->execute(array($row3['projectid']));
//                             if($row5 = $stmt5->fetch())
//                             {
//                                 $stmt6 = $Database->prepare("SELECT * FROM core_usergroup WHERE groupid = ?");
//                                 $stmt6->execute(array($row5['groupid']));
//                                 if($row6 = $stmt6->fetch())
//                                 {
//                                     $stmt4 = $Database->prepare("SELECT * FROM timesheet_task WHERE taskid = ? AND projectid = ?");
//                                     $stmt4->execute(array($row2['taskid'],  $row3['projectid']));                                   
//                                     if($row4 = $stmt4->fetch())
//                                     {
//                                         $stmt7 = $Database->prepare("SELECT * FROM core_user WHERE userid = ?");
//                                         $stmt7->execute(array($row2['userid']));
//                                         if($row7 = $stmt7->fetch())
//                                         {
//                                             if ($row3['projectid'] == $row2['projectid'])
//                                             {
//                                                 if(($row3['leaderid2'] == $row['userid']) || ($row3['leaderid1'] == $row['userid']))
//                                                 {
//                                                     $arraylist[] = array("Staff" => $row7["fullname"], "Userid" => $row2["userid"], "Project" => $row3["projectname"], "Task" => $row4["taskname"], "EntryID" => (string)$row2["entryid"], "Date" => date("d-m-Y", strtotime($row2["entrydate"])), "Hours" => (string)$row2["spenttime"], "Notes" => $row2["notes"], "Created" => date("d-m-Y", strtotime($row2["datecreated"])), "Submitted" => date("d-m-Y", strtotime($row2["datesubmitted"])), "OT" => (string)$row2["ot"], "ApproverNotes" => $row2["approver_notes"], "Status" => $row2["status"]);
//                                                 }
//                                                 else
//                                                 {
//                                                     $array['Status'] = 'Not Found';
//                                                 }
//                                             }
//                                             else
//                                             {
//                                                 $array['Statusl'] = 'Not Found';
//                                             }
//                                         }
//                                     }
//                                     else
//                                     {
//                                         $array['Status'] = 'Not Found';
//                                     }
//                                 }
//                             }
//                         }
//                         else
//                         {
//                             $array['Status'] = 'Not Found';
//                         }
//                     }
//                     if(!empty($arraylist))
//                     {
//                         $array['Status'] = "True";
//                         $array['TimesheetAppRecord'] = $arraylist;
//                         $array['Total'] = count($arraylist);
//                     }
//                     else
//                     {
//                         $array['Status'] = "No Record Found";
//                         $array['TimesheetAppRecord'] = "-";
//                     }
//         }
//         else
//         {
//             $array['status'] = 'No Record Available';
//         }

//         return json_encode($array);

//     }

//     public function test14(Request $request)
//     {
//         $entrydatefrom = $request->input('From');
//         $entrydateto = $request->input('To');
//         if($entrydatefrom =="")return json_encode("Insert From");
//         if($entrydateto =="")return json_encode("Insert To");

//         $db=app('db');
//         $Database = $db->connection('content')->getPdo();

//         $stmt = $Database->prepare("SELECT * FROM timesheet_entry WHERE entrydate = ?");
//         $stmt->execute(array($entrydate));
//         if($row = $stmt->fetch())
//         {
//             $array[''] =;
//         }
//         else
//         {
//             $array['Status'] = "No Record Found";
//         }

//         return json_encode($array);
//     }
}       
?>