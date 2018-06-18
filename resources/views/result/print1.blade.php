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
    <tr style="text-align:center"><td colspan="5"><b>NATIONAL PUBLIC HEALTH LABORATORY SERVICES (NPHLS)</b></td></tr>
    <tr style="text-align:center"><td colspan="5"><b>NATIONAL HIV REFERENCE LABORATORY (NHRL)</b></td></tr>
    <tr style="text-align:center"><td colspan="5"><b>P.O Box 20750 - 00202, NAIROBI, nhrlpt@nphls.or.ke</b></td></tr>
    <tr style="text-align:center"><td colspan="5"><b>NATIONAL HIV SEROLOGY PROFICIENCY TESTING SCHEME</b></td></tr>
    <tr style="text-align:center"><td colspan="5"><b>Final Report</b></td></tr>
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
        <td>{{$data['program']}}</td>
        <td><b>Facility MFL</b></td>
        <td>{{$data['mfl']}}</td>
    </tr>
    <tr> <td> &nbsp;</td> </tr>
    <tr class="text-center"> <td colspan="5"><u><b>RE: Proficiency Testing Results</b></u></td></tr>
    <tr class="text-center"> <td colspan="5">NHRL acknowledges receipt of your Proficiency Testing results for Round {{$data['round_name']}}</td></tr>
    <tr class="text-center"> <td colspan="5">Your result is <b> <i>{{$data['feedback']}}</i></b></td></tr>
    <tr> <td colspan="5"> &nbsp;</td> </tr>

    <tr class="text-center"> <td colspan="5">Comments:</td></tr>
    <tr class="text-center">
        <td style ="border:solid 1px black;" rowspan="2">PT Sample ID</td>
        <td style ="border:solid 1px black;" colspan="3">Your Results</td>
        <td style ="border:solid 1px black;">NHRL Results</td>
    </tr>
    <tr>
        <th style ="border:solid 1px black;">Determine</th>
        <th style ="border:solid 1px black;">First Response</th>
        <th style ="border:solid 1px black;">Final Result</th>
        <th style ="border:solid 1px black;">Expected Result</th>
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
    <tr> <td colspan="5"> &nbsp;</td> </tr>
    <tr>
        <td><b>Date Authorized:</b></td>
        <td colspan="4">{{$data['date_approved']}}</td>
    </tr>
    <tr>
        <td colspan="3">Thank you for your participation</td>
        <td><b>Head of Virology:</b></td>
        <td><img src="img/sign_bowen.png" alt="" height="20" width="80" /></td>
    </tr>
    <tr>
        <td colspan="4"></td>
        <td>Nancy Bowen</td>
    </tr>
    <tr>
        <td><img src="img/sign.png" alt="" height="20" width="80" /></td>
    </tr>
    <tr>
        <td>Franklin Kitheka,</td>
    </tr>
    <tr>
        <td>PT Coordinator.</td>
    </tr>

    {{--<tr class="text-center"> <td colspan="5"></td></tr>--}}
    <tr> <td colspan="4" ></td> <td><img src="img/ukas.png" alt="" border="1" height="55" width="100" /></td> </tr>

    <tr>
        <td colspan="2">NHRL Doc N0 105 V:0 </td>
        <td colspan="3">
            <footer>
                <div>
                    <div class="pagenum-container">
                        <div style="text-align:left">Page <span class="pagenum"></span></div>
                    </div>
                </div>
            </footer>
        </td>
    </tr>
    <tr><td><div style="page-break-before: always"></div></td></tr>
    <tr><td colspan="5"><b><div style="text-align:center">Testing Scheme Information</td></tr></div></b>
    <tr class="text-center"><td style ="border:solid 2px black;" colspan="5">
            <p>1.  The HIV-PT is a Qualitative scheme.</p>
            <p> 2. The scheme utilises dried tube plasma color coded in green.</p>
            <p>3.  PT panel samples have been fully characterized for the assigned HIV Sero-status.</p>
            <p>4.  Stability testing was done at 4 oC, 25 oC, 37 oC and 45 oC. The samples were stable for 90 days.</p>
            <p>5.  Homogeneity was established by random sampling of 1% of samples ina batch (1 sample per 100 samples).</p>
            <p>6.  Participants performance report will be treated confidentially and will be shared with Quality officers.</p>
            <p>7.  Subcontracted services: Panle distribution, results submission and feedback distribution.</p>
            <p>8.  A summary report will be shared at the NPHL website (www.nphls.or.ke) at the conclusion of every round.</p></td>
    </tr>
    <footer><div style="text-align:center"><p>End of the report</div></p><br>
        <div class="pagenum-container"><div style="text-align:center">Page <span class="pagenum"></div></span></div></footer>
</table>

