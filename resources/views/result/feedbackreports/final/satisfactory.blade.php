<head>
    <style>
        footer .pagenum:before {
            content: counter(page);
        }
    </style>
</head>
<table class="table table-bordered" cellspacing="0" style="font-size:15px">
    <tr><td colspan="5"><center><img src="img/coa.png"></center></td></tr>
    <tr style="text-align:center"><td colspan="5"><b>MINISTRY OF HEALTH</b></td></tr>
    <tr style="text-align:center"><td colspan="5"><b>NATIONAL PUBLIC HEALTH LABORATORY (NPHL)</b></td></tr>
    <tr style="text-align:center"><td colspan="5"><b>NPHL-CENTRE OF EXCELLENCE FOR QUALITY ASSUARANCE</b></td></tr>
    <tr style="text-align:center"><td colspan="5"><b>P.O Box 20750 - 00202, NAIROBI, nphlpt@nphls.or.ke</b></td></tr>
    <tr style="text-align:center"><td colspan="5"><b>NATIONAL HIV SEROLOGY PROFICIENCY TESTING SCHEME</b></td></tr>
    <tr> <td colspan="5"> &nbsp;</td> </tr>
    <tr style="text-align:center"><td colspan="5">
        <?php
            if(count($data['amendments']) > 0){
                foreach ($data['amendments'] as $amendment) {
                    if($amendment['status'] == 1){//Active
                        $amended = $amendment->toArray();
                        $amended['feedback'] = $amended['feedback'] == 1?'Unsatisfactory':'Satisfactory';
                    }
                }
        ?>
            <b>Amended Report</b>
        <?php 
            }else{ 
                $amended['feedback'] = $data['feedback'];
                $amended['incorrect_results'] = $data['incorrect_results'];
                $amended['wrong_algorithm'] = $data['wrong_algorithm'];
                $amended['use_of_expired_kits'] = $data['use_of_expired_kits'];
                $amended['incomplete_kit_data'] = $data['incomplete_kit_data'];
                $amended['incomplete_results'] = $data['incomplete_results'];
                $amended['dev_from_procedure'] = $data['dev_from_procedure'];
                $amended['incomplete_other_information'] = $data['incomplete_other_information'];
        ?>
            <b>Final Report</b>
        <?php } 
        ?>
    </td></tr>
    <tr> <td colspan="5" style="border-top:solid 2px black;"> &nbsp;</td> </tr>
    <tr>
        <td><b>Round</b></td>
        <td>{{$data['round_name']}}</td>
        <td><b>County</b></td>
        <td>{{$data['county']}}</td>
    </tr>
    <tr>
        <td><b>Tester ID</b></td>
        <td>{{$data['tester_id']}}</td>
        <td><b>Sub County</b></td>
        <td>{{$data['sub_county']}}</td>
    </tr>
    <tr>
        <td><b>Tester Name</b></td>
        <td>{{$data['user_name']}}</td>
        <td><b>Facility</b></td>
        <td>{{$data['facility']}}</td>
    </tr>
    <tr>
        <td><b>Program</b></td>
        <td>{{$data['program_name']}}</td>
        <td><b>Facility MFL</b></td>
        <td>{{$data['mfl']}}</td>
    </tr>
    <tr> <td> &nbsp;</td> </tr>
    @if($amended['feedback'] === 'Unsatisfactory')
    <tr class="text-center"> <td colspan="5"><u><b>RE: Proficiency Testing Results</b></u></td></tr>
    <tr class="text-center"> <td colspan="5">NPHL acknowledges receipt of your Proficiency Testing results for Round {{$data['round_name']}}.</td></tr>
    <tr class="text-center"> <td colspan="5">Your overall performance is <b>{{$amended['feedback']}}</b>.</td></tr>
    <tr class="text-center"> <td colspan="5">The reason/s for <b> {{$amended['feedback']}}</b> is/are:</td></tr>    
    <tr >        
        <td style ="border:solid 1px black;"><input type="checkbox" style="display: inline"
        @if($amended['incorrect_results'] == 1)
        checked
        @endif /> Incorrect Result</td>
        <td style ="border:solid 1px black;"><input type="checkbox" style="display: inline"
        @if($amended['wrong_algorithm'] == 1)
         checked
        @endif /> Wrong Algorithm</td>
        <td style ="border:solid 1px black;"><input type="checkbox" style="display: inline"
        @if($amended['use_of_expired_kits'] == 1)
         checked
        @endif /> Use of Expired Kits</td>              
        <td style ="border:solid 1px black;"><input type="checkbox" width="200" style="display: inline"
        @if($amended['incomplete_kit_data'] == 1)
         checked
        @endif /> Incomplete Kit Data</td>
        <td></td>
    </tr>
    <tr >
       <td style ="border:solid 1px black;"><input type="checkbox" style="display: inline"
        @if($amended['incomplete_results'] == 1)
         checked
        @endif />Incomplete Results</td>  
        <td style ="border:solid 1px black;"><input type="checkbox" width="200" style="display: inline"
        @if($amended['dev_from_procedure'] == 1)
         checked
        @endif  /> Deviation From Procedure</td>
        <td colspan="2" style ="border:solid 1px black;"><input type="checkbox" width="200" style="display: inline"
        @if($amended['incomplete_other_information'] == 1)
         checked
        @endif /> Incomplete Other Information</td>
        <td></td>
    </tr>
    @endif
    <tr> <td colspan="5"> &nbsp;</td> </tr>

    <tr class="text-center">
        <td style ="border:solid 1px black;" rowspan="2">PT Sample ID</td>
        <td style ="border:solid 1px black;" colspan="3">Your Results</td>
        <td style ="border:solid 1px black;" rowspan="2">Expected Results</td>
    </tr>
    <tr>
        <th style ="border:solid 1px black;">Determine</th>
        <th style ="border:solid 1px black;">First Response</th>
        <th style ="border:solid 1px black;">Final Result</th>
    </tr>
    <tr>
        <td style ="border:solid 1px black;">{{$data['sample_1']}}</td>
        <td style ="border:solid 1px black;">{{$data['pt_panel_1_kit1_results']}}</td>
        <td style ="border:solid 1px black;">{{$data['pt_panel_1_kit2_results']}}</td>
        <td style ="border:solid 1px black;">{{$data['pt_panel_1_final_results']}}</td>
        <td style ="border:solid 1px black; text-transform: uppercase;">{{$data['expected_result_1']}}</td>
    </tr>
    <tr>
        <td style ="border:solid 1px black;">{{$data['sample_2']}}</td>
        <td style ="border:solid 1px black;">{{$data['pt_panel_2_kit1_results']}}</td>
        <td style ="border:solid 1px black;">{{$data['pt_panel_2_kit2_results']}}</td>
        <td style ="border:solid 1px black;">{{$data['pt_panel_2_final_results']}}</td>
        <td  style ="border:solid 1px black; text-transform: uppercase">{{$data['expected_result_2']}}</td>
    </tr>
    <tr>
        <td style ="border:solid 1px black;">{{$data['sample_3']}}</td>
        <td style ="border:solid 1px black;">{{$data['pt_panel_3_kit1_results']}}</td>
        <td style ="border:solid 1px black;">{{$data['pt_panel_3_kit2_results']}}</td>
        <td style ="border:solid 1px black;">{{$data['pt_panel_3_final_results']}}</td>
        <td style ="border:solid 1px black; text-transform: uppercase;">{{$data['expected_result_3']}}</td>
    </tr>
    <tr>
        <td style ="border:solid 1px black;">{{$data['sample_4']}}</td>
        <td style ="border:solid 1px black;">{{$data['pt_panel_4_kit1_results']}}</td>
        <td style ="border:solid 1px black;">{{$data['pt_panel_4_kit2_results']}}</td>
        <td style ="border:solid 1px black;">{{$data['pt_panel_4_final_results']}}</td>
        <td style ="border:solid 1px black; text-transform: uppercase;">{{$data['expected_result_4']}}</td>
    </tr>
    <tr>
        <td style ="border:solid 1px black;">{{$data['sample_5']}}</td>
        <td style ="border:solid 1px black;">{{$data['pt_panel_5_kit1_results']}}</td>
        <td style ="border:solid 1px black;">{{$data['pt_panel_5_kit2_results']}}</td>
        <td style ="border:solid 1px black;">{{$data['pt_panel_5_final_results']}}</td>
        <td style ="border:solid 1px black; text-transform: uppercase;">{{$data['expected_result_5']}}</td>
    </tr>
    <tr>
        <td style ="border:solid 1px black;">{{$data['sample_6']}}</td>
        <td style ="border:solid 1px black;">{{$data['pt_panel_6_kit1_results']}}</td>
        <td style ="border:solid 1px black;">{{$data['pt_panel_6_kit2_results']}}</td>
        <td style ="border:solid 1px black;">{{$data['pt_panel_6_final_results']}}</td>
        <td style ="border:solid 1px black; text-transform: uppercase;">{{$data['expected_result_6']}}</td>
    </tr>
    <tr><td colspan="5">&nbsp;</td></tr>
    <tr><td colspan="5">&nbsp;</td></tr>
    <tr>
        <td colspan="5"><b>Expert Comment:</b></td>
    </tr>
    <tr>
        <td colspan="5">{{$data['pt_approved_comment']}}</td>
    </tr>
     <tr>
        <td colspan="5"><i>Please institute the necessary corrective measures before the next round of PT.</i></td>
    </tr>
   <tr>
        <td colspan="2">MOH/NPHL/EQA/COE FORM 09 &nbsp;&nbsp;&nbsp;&nbsp;Ver. 1</td>
        <td>
            <footer>
                <div>
                    <div class="pagenum-container">
                        <div>Page <span class="pagenum"></span></div>
                    </div>
                </div>
            </footer>
        </td>
        <td style="text-align:right"><img src="img/ukas.png" alt="" border="1" height="55" width="100" /></td> 
    </tr>    
    <tr><td><div style="page-break-before: always"></div></td></tr>
    <tr><td colspan="5"><b><div style="text-align:center">Testing Scheme Information</div></b></td></tr>
    <tr class="text-center"><td style ="border:solid 2px black;" colspan="5">
           <p>1.  The HIV-PT is a Qualitative scheme.</p>
            <p>2.  The panel samples come as a green pellet in 2 ml vials.</p>
            <p>3.  The PT panel samples have been fully characterized for the assigned HIV Sero-status.</p>
            <p>4.  The panel samples have been tested for stability and are stable.</p>
            <p>5.  Homogeneity was done using systematic random sampling and the results were the same as those of expected results.</p>
            <p>6.  Participant’s performance report is confidential and will ONLY be shared with responsible County Quality officers for purposes of corrective interventions</p>
            <p>7.  Subcontracted services: PT panel distribution, return of results and feedback reports distribution.</p>
            <p>8.  The scheme’s final report with summaries with overall performance analysis will be available on (www.rhtpt.or.ke) within one month of closure of the round.</p></td>
    </tr>
    <tr> <td colspan="5"> &nbsp;</td> </tr>
    <tr style="text-align:center"><td colspan="5"><b>PT Scheme Summary Performance.</b></td></tr>
    <tr style="text-align:center"> <td colspan="5"> (Will be available on your final report)</td> </tr>
    <tr> <td colspan="5"> &nbsp;</td> </tr>
    <tr> <td colspan="5"> &nbsp;</td> </tr>
    <tr> <td colspan="5"> &nbsp;</td> </tr>
    <tr> <td colspan="5"> &nbsp;</td> </tr>
    <tr> <td colspan="5"> &nbsp;</td> </tr>
    <tr> <td colspan="5"> &nbsp;</td> </tr>
    <tr>
        <td style ="border:solid 1px black;"><b>Date Authorized:</b></td>
        <td style ="border:solid 1px black;" colspan="4">{{$data['date_approved']}}</td>
    </tr>
    <tr> <td colspan="5"> &nbsp;</td> </tr>
    <tr> <td colspan="5"> &nbsp;</td> </tr>

    <tr>
        <td colspan="3"><img src="img/sign_kitheka.png" alt="" height="20" width="80" /></td>
        <td colspan="2"><img src="img/sophie.png" alt="" height="20" width="80" /></td>
    </tr>
    <tr>
        <td colspan="3">Franklin Kitheka</td>
        <td colspan="2">Sophie Mwanyumba</td>        
    </tr>
    <tr>
        <td colspan="3">National HIV Proficiency Testing Scheme,</td>
        <td colspan="2">Centre of Excellence for Quality Assurance,</td>        
    </tr>
    <tr>
        <td colspan="3">Coordinator,</td>
        <td colspan="2">Manager,</td>        
    </tr>
    <tr>
        <td colspan="3">Tel: 0722934622.</td>
        <td colspan="2">Tel: 0720203712.</td>        
    </tr>
    <tr> <td colspan="5"> &nbsp;</td> </tr>
    <tr style="text-align:center"><td colspan="5">Thank you for your participation.</td></tr>
    <tr style="text-align:center"><td colspan="5">End of the report.</td></tr>
    <tr>
        <td colspan="2">MOH/NPHL/EQA/COE FORM 09 &nbsp;&nbsp;&nbsp;&nbsp;Ver. 1</td>
        <td>
            <footer>
                <div>
                    <div class="pagenum-container">
                        <div style="text-align:left">Page <span class="pagenum"></span></div>
                    </div>
                </div>
            </footer>
        </td>
        <td style="text-align:right"><img src="img/ukas.png" alt="" border="1" height="55" width="100" /></td> 
    </tr>    
</table>

