<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Applicant Report - {{ now()->format('m/d/Y') }}</title>
    <style>
        body { 
            font-family: 'DejaVu Sans', Arial, sans-serif; 
            margin: 0; 
            padding: 20px; 
            color: #333;
            line-height: 1.4;
        }
        
        .header { 
            text-align: center; 
            margin-bottom: 30px; 
            padding-bottom: 20px; 
            border-bottom: 1px solid #e0e0e0;
        }
        
        .header h1 {
            color: #2c3e50;
            margin-bottom: 5px;
            font-size: 28px;
        }
        
        .header p {
            color: #7f8c8d;
            margin-top: 0;
            font-size: 14px;
        }
        
        .summary-grid { 
            display: grid; 
            grid-template-columns: repeat(4, 1fr); 
            gap: 20px; 
            margin-bottom: 30px; 
        }
        
        .summary-card { 
            background: #fff;
            border-radius: 8px; 
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            text-align: center;
            border-top: 4px solid #3498db;
        }
        
        .summary-card.approved { border-top-color: #2ecc71; }
        .summary-card.pending { border-top-color: #f39c12; }
        .summary-card.rejected { border-top-color: #e74c3c; }
        
        .summary-card-title { 
            font-size: 14px; 
            color: #7f8c8d; 
            margin-bottom: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .summary-card-value { 
            font-size: 28px; 
            font-weight: bold; 
            color: #2c3e50;
        }
        
        .stats-grid { 
            display: grid; 
            grid-template-columns: repeat(3, 1fr); 
            gap: 20px; 
            margin-bottom: 40px; 
        }
        
        .stats-card { 
            background: #fff;
            border-radius: 8px; 
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            text-align: center;
        }
        
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-bottom: 40px; 
            page-break-inside: avoid; 
            font-size: 13px;
        }
        
        th, td { 
            padding: 12px 15px; 
            text-align: left; 
            border-bottom: 1px solid #e0e0e0;
        }
        
        th { 
            background-color: #f8f9fa; 
            color: #2c3e50;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 0.5px;
        }
        
        tr:hover {
            background-color: #f8f9fa;
        }
        
        .section-title { 
            background-color: #f8f9fa; 
            padding: 12px 20px; 
            margin: 30px 0 15px 0; 
            font-weight: 600;
            color: #2c3e50;
            border-left: 4px solid #3498db;
            border-radius: 4px 0 0 4px;
        }
        
        .page-break { 
            page-break-after: always; 
        }
        
        .logo {
            height: 60px;
            margin-bottom: 15px;
        }
        
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
        }
        
        .badge-approved {
            background-color: #d5f5e3;
            color: #27ae60;
        }
        
        .badge-pending {
            background-color: #fdebd0;
            color: #f39c12;
        }
        
        .badge-rejected {
            background-color: #fadbd8;
            color: #e74c3c;
        }
    </style>
</head>
<body>
    <div class="header">
        <!-- Replace with your actual logo path -->
        <!-- <img src="{{ public_path('images/logo.png') }}" class="logo" alt="Organization Logo"> -->
        <h1>Applicant Report</h1>
        <p>Generated on {{ now()->format('F j, Y \a\t g:i A') }}</p>
    </div>

    <!-- Summary Section -->
    <h2 style="color: #2c3e50; margin-bottom: 20px; font-size: 20px;">Summary Overview</h2>
    <div class="summary-grid">
        <div class="summary-card">
            <div class="summary-card-title">Total Applicants</div>
            <div class="summary-card-value">{{ $totalApplicants }}</div>
        </div>
        <div class="summary-card approved">
            <div class="summary-card-title">Approved</div>
            <div class="summary-card-value">{{ $approvedApplicants }}</div>
        </div>
        <div class="summary-card pending">
            <div class="summary-card-title">Pending</div>
            <div class="summary-card-value">{{ $pendingApplicants }}</div>
        </div>
        <div class="summary-card rejected">
            <div class="summary-card-title">Rejected</div>
            <div class="summary-card-value">{{ $rejectedApplicants }}</div>
        </div>
    </div>

    <!-- Demographics Section -->
    <h2 style="color: #2c3e50; margin-bottom: 20px; font-size: 20px;">Demographics</h2>
    
    <h3 style="color: #2c3e50; margin: 25px 0 15px 0; font-size: 16px;">Gender Distribution</h3>
    <div class="stats-grid">
        <div class="stats-card">
            <div class="summary-card-title">Male</div>
            <div class="summary-card-value">{{ $genderCounts['Male'] ?? 0 }}</div>
        </div>
        <div class="stats-card">
            <div class="summary-card-title">Female</div>
            <div class="summary-card-value">{{ $genderCounts['Female'] ?? 0 }}</div>
        </div>
        <div class="stats-card">
            <div class="summary-card-title">Other</div>
            <div class="summary-card-value">{{ $genderCounts['other'] ?? 0 }}</div>
        </div>
    </div>

    <h3 style="color: #2c3e50; margin: 25px 0 15px 0; font-size: 16px;">Age Statistics</h3>
    <div class="stats-grid">
        <div class="stats-card">
            <div class="summary-card-title">Youngest</div>
            <div class="summary-card-value">
                @if($youngest)
                    {{ \Carbon\Carbon::parse($youngest->birthdate)->age }} years
                @else
                    N/A
                @endif
            </div>
        </div>
        <div class="stats-card">
            <div class="summary-card-title">Oldest</div>
            <div class="summary-card-value">
                @if($oldest)
                    {{ \Carbon\Carbon::parse($oldest->birthdate)->age }} years
                @else
                    N/A
                @endif
            </div>
        </div>
        <div class="stats-card">
            <div class="summary-card-title">Average Age</div>
            <div class="summary-card-value">
                @if($averageAge)
                    {{ round($averageAge) }} years
                @else
                    N/A
                @endif
            </div>
        </div>
    </div>

    <!-- Approved Applicants -->
    <div class="section-title">Approved Applicants ({{ $approvedApplicants }})</div>
    <table>
        <thead>
            <tr>
                <th>Full Name</th>
                <th>Sex</th>
                <th>Age</th>
                <th>Date Applied</th>
                <th>Date Approved</th>
                <th>Section</th>
                <th>Bureau</th>
                <th>Membership Type</th>
            </tr>
        </thead>
        <tbody>
            @forelse($approved as $applicant)
            <tr>
                <td>{{ $applicant->last_name }}, {{ $applicant->first_name }} {{ $applicant->middle_name ? $applicant->middle_name[0].'.' : '' }} {{ $applicant->suffix ?? '' }}</td>
                <td>{{ ucfirst($applicant->sex) }}</td>
                <td>
                    @if($applicant->birthdate)
                        {{ \Carbon\Carbon::parse($applicant->birthdate)->age }}
                    @else
                        N/A
                    @endif
                </td>
                <td>{{ $applicant->created_at->format('m/d/Y') }}</td>
                <td>
                    @if($applicant->member && $applicant->member->created_at)
                        {{ $applicant->member->created_at->format('m/d/Y') }}
                    @else
                        N/A
                    @endif
                </td>
                <td>{{ $applicant->member->section->section_name ?? 'N/A' }}</td>
                <td>{{ $applicant->member->section->bureau->bureau_name ?? 'N/A' }}</td>
                <td>{{ $applicant->member->membershipType->type_name ?? 'N/A' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align: center; color: #7f8c8d; padding: 20px;">No approved applicants found</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Pending Applicants -->
    <div class="section-title">Pending Applicants ({{ $pendingApplicants }})</div>
    <table>
        <thead>
            <tr>
                <th>Full Name</th>
                <th>Sex</th>
                <th>Age</th>
                <th>Date Applied</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pending as $applicant)
            <tr>
                <td>{{ $applicant->last_name }}, {{ $applicant->first_name }} {{ $applicant->middle_name ? $applicant->middle_name[0].'.' : '' }} {{ $applicant->suffix ?? '' }}</td>
                <td>{{ ucfirst($applicant->sex) }}</td>
                <td>
                    @if($applicant->birthdate)
                        {{ \Carbon\Carbon::parse($applicant->birthdate)->age }}
                    @else
                        N/A
                    @endif
                </td>
                <td>{{ $applicant->created_at->format('m/d/Y') }}</td>
                <td><span class="badge badge-pending">Pending</span></td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align: center; color: #7f8c8d; padding: 20px;">No pending applicants found</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Rejected Applicants -->
    <div class="section-title">Rejected Applicants ({{ $rejectedApplicants }})</div>
    <table>
        <thead>
            <tr>
                <th>Full Name</th>
                <th>Sex</th>
                <th>Age</th>
                <th>Date Applied</th>
                <th>Date Rejected</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rejected as $applicant)
            <tr>
                <td>{{ $applicant->last_name }}, {{ $applicant->first_name }} {{ $applicant->middle_name ? $applicant->middle_name[0].'.' : '' }} {{ $applicant->suffix ?? '' }}</td>
                <td>{{ ucfirst($applicant->sex) }}</td>
                <td>
                    @if($applicant->birthdate)
                        {{ \Carbon\Carbon::parse($applicant->birthdate)->age }}
                    @else
                        N/A
                    @endif
                </td>
                <td>{{ $applicant->created_at->format('m/d/Y') }}</td>
                <td>{{ $applicant->updated_at->format('m/d/Y') }}</td>
                <td><span class="badge badge-rejected">Rejected</span></td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center; color: #7f8c8d; padding: 20px;">No rejected applicants found</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
    <!-- Footer -->
    <div style="text-align: center; margin-top: 50px; color: #7f8c8d; font-size: 12px; border-top: 1px solid #e0e0e0; padding-top: 15px;">
        <p>Confidential Report - Generated by {{ config('app.name') }}</p>
    </div>
</body>
</html>