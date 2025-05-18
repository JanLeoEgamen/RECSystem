<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>License Report - {{ now()->format('m/d/Y') }}</title>
    <style>
        body { 
            font-family: 'DejaVu Sans', Arial, sans-serif; 
            margin: 0; 
            padding: 25px; 
            color: #333;
            line-height: 1.5;
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
            grid-template-columns: repeat(3, 1fr); 
            gap: 20px; 
            margin-bottom: 25px;
        }
        
        .subsummary-grid { 
            display: grid; 
            grid-template-columns: repeat(3, 1fr); 
            gap: 20px; 
            margin-bottom: 35px;
        }
        
        .summary-card { 
            background: #fff;
            border-radius: 8px; 
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            text-align: center;
            border-top: 4px solid #3498db;
        }
        
        .summary-card.licensed { border-top-color: #2ecc71; }
        .summary-card.unlicensed { border-top-color: #e74c3c; }
        .summary-card.active { border-top-color: #2ecc71; }
        .summary-card.expired { border-top-color: #f39c12; }
        .summary-card.near-expiry { border-top-color: #f1c40f; }
        
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
        
        .bureau-header { 
            background-color: #f8f9fa; 
            padding: 15px 20px; 
            margin: 40px 0 15px 0;
            font-weight: 600;
            color: #2c3e50;
            border-left: 4px solid #9b59b6;
            border-radius: 4px 0 0 4px;
            font-size: 18px;
        }
        
        .section-header { 
            background-color: #f8f9fa; 
            padding: 12px 20px; 
            margin: 25px 0 15px 0;
            font-weight: 600;
            color: #2c3e50;
            border-left: 4px solid #3498db;
            border-radius: 4px 0 0 4px;
        }
        
        .license-class-header { 
            background-color: #f8f9fa; 
            padding: 10px 20px; 
            margin: 20px 0 10px 0;
            font-weight: 600;
            color: #2c3e50;
            border-left: 4px solid #2ecc71;
            border-radius: 4px 0 0 4px;
        }
        
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-bottom: 25px; 
            font-size: 13px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
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
        
        .badge { 
            display: inline-block;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .badge-active { 
            background-color: #d5f5e3;
            color: #27ae60;
        }
        
        .badge-expired { 
            background-color: #fef3c7;
            color: #b45309;
        }
        
        .badge-near-expiry {
            background-color: #fef3c7;
            color: #b45309;
        }
        
        .unlicensed-list { 
            margin: 15px 0 25px 20px;
            padding-left: 10px;
            border-left: 2px solid #e0e0e0;
        }
        
        .unlicensed-item { 
            margin-bottom: 8px;
            padding: 8px 0;
            border-bottom: 1px dashed #e0e0e0;
        }
        
        .unlicensed-item:last-child {
            border-bottom: none;
        }
        
        .stats-label {
            display: inline-block;
            margin-left: 15px;
            font-size: 13px;
            color: #7f8c8d;
        }
        
        .stats-value {
            font-weight: 600;
            color: #2c3e50;
        }
        
        .footer {
            text-align: center;
            margin-top: 50px;
            color: #7f8c8d;
            font-size: 12px;
            border-top: 1px solid #e0e0e0;
            padding-top: 15px;
        }
        
        .logo {
            height: 60px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="header">
        <!-- Replace with your actual logo path -->
        <!-- <img src="{{ public_path('images/logo.png') }}" class="logo" alt="Organization Logo"> -->
        <h1>License Status Report</h1>
        <p>Generated on {{ now()->format('F j, Y \a\t g:i A') }}</p>
    </div>

    <h2 style="color: #2c3e50; margin-bottom: 20px; font-size: 20px;">License Overview</h2>
    <div class="summary-grid">
        <div class="summary-card">
            <div class="summary-card-title">Total Members</div>
            <div class="summary-card-value">{{ $totalMembers }}</div>
        </div>
        <div class="summary-card licensed">
            <div class="summary-card-title">Licensed Members</div>
            <div class="summary-card-value">{{ $licensedMembers }}</div>
        </div>
        <div class="summary-card unlicensed">
            <div class="summary-card-title">Unlicensed Members</div>
            <div class="summary-card-value">{{ $unlicensedMembers }}</div>
        </div>
    </div>

    <div class="subsummary-grid">
        <div class="summary-card active">
            <div class="summary-card-title">Active Licenses</div>
            <div class="summary-card-value">{{ $activeLicenses }}</div>
        </div>
        <div class="summary-card expired">
            <div class="summary-card-title">Expired Licenses</div>
            <div class="summary-card-value">{{ $expiredLicenses }}</div>
        </div>
        <div class="summary-card near-expiry">
            <div class="summary-card-title">Near Expiry (60 days)</div>
            <div class="summary-card-value">{{ $nearExpiry }}</div>
        </div>
    </div>

    <h2 style="color: #2c3e50; margin-bottom: 20px; font-size: 20px;">License Details by Bureau</h2>
    @foreach($bureaus as $bureau)
    <div class="bureau-header">
        {{ $bureau->bureau_name }}
        <span class="stats-label">
            Members: <span class="stats-value">{{ $bureau->bureau_members_count }}</span> • 
            Licensed: <span class="stats-value">{{ $bureau->bureau_licensed_count }}</span> • 
            Unlicensed: <span class="stats-value">{{ $bureau->bureau_unlicensed_count }}</span>
        </span>
    </div>

    @foreach($bureau->sections as $section)
    <div class="section-header">
        {{ $section->section_name }}
        <span class="stats-label">
            Licensed: <span class="stats-value">{{ $section->licensed_members_count }}</span> • 
            Unlicensed: <span class="stats-value">{{ $section->unlicensed_members_count }}</span>
            @if($section->licensed_members_count > 0)
            <span style="margin-left: 10px;">
                Active: <span class="stats-value">{{ $section->active_licenses_count }}</span> • 
                Expired: <span class="stats-value">{{ $section->expired_licenses_count }}</span> • 
                Near Expiry: <span class="stats-value">{{ $section->near_expiry_count }}</span>
            </span>
            @endif
        </span>
    </div>

    <!-- Licensed Members by Class -->
    @if($section->licensed_members_count > 0)
        @foreach($section->groupedLicensedMembers as $licenseClass => $members)
        <div class="license-class-header">
            License Class: {{ $licenseClass ?? 'N/A' }} 
            <span class="stats-label">Members: <span class="stats-value">{{ $members->count() }}</span></span>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Member</th>
                    <th>Status</th>
                    <th>License #</th>
                    <th>Expiration Date</th>
                    <th>Days Remaining</th>
                </tr>
            </thead>
            <tbody>
                @foreach($members as $member)
                @php
                    $expirationDate = \Carbon\Carbon::parse($member->license_expiration_date);
                    $daysRemaining = now()->diffInDays($expirationDate, false);
                    $statusClass = $daysRemaining > 60 ? 'badge-active' : ($daysRemaining > 0 ? 'badge-near-expiry' : 'badge-expired');
                    $statusText = $daysRemaining > 60 ? 'Active' : ($daysRemaining > 0 ? 'Near Expiry' : 'Expired');
                @endphp
                <tr>
                    <td>{{ $member->last_name }}, {{ $member->first_name }}</td>
                    <td>
                        <span class="badge {{ $statusClass }}">
                            {{ $statusText }}
                        </span>
                    </td>
                    <td>{{ $member->license_number }}</td>
                    <td>{{ $expirationDate->format('m/d/Y') }}</td>
                    <td>
                        @if($daysRemaining > 0)
                            {{ $daysRemaining }} days
                        @else
                            {{ abs($daysRemaining) }} days ago
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endforeach
    @endif

    <!-- Unlicensed Members -->
    @if($section->unlicensed_members_count > 0)
    <div>
        <h4 style="color: #2c3e50; margin: 25px 0 10px 0;">Unlicensed Members ({{ $section->unlicensed_members_count }})</h4>
        <div class="unlicensed-list">
            @foreach($section->members()->whereNull('license_number')->orderBy('last_name')->orderBy('first_name')->get() as $member)
            <div class="unlicensed-item">
                {{ $member->last_name }}, {{ $member->first_name }} <span style="color: #7f8c8d;">({{ $section->section_name }})</span>
            </div>
            @endforeach
        </div>
    </div>
    @endif
    @endforeach
    @endforeach
    
    <div class="footer">
        <p>Confidential Report - Generated by {{ config('app.name') }}</p>
        <p>Page 1 of 1</p>
    </div>
</body>
</html>