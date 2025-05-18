<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Membership Report - {{ now()->format('m/d/Y') }}</title>
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
        
        .summary-cards { 
            display: flex; 
            flex-wrap: wrap; 
            gap: 20px; 
            margin-bottom: 30px; 
            justify-content: space-between;
        }
        
        .card { 
            background: #fff;
            border-radius: 8px; 
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            text-align: center;
            flex: 1;
            min-width: 180px;
            border-top: 4px solid #3498db;
        }
        
        .card.total { border-top-color: #3498db; }
        .card.active { border-top-color: #2ecc71; }
        .card.inactive { border-top-color: #e74c3c; }
        .card.bureaus { border-top-color: #9b59b6; }
        .card.sections { border-top-color: #f39c12; }
        
        .card-title { 
            font-size: 14px; 
            color: #7f8c8d; 
            margin-bottom: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .card-value { 
            font-size: 28px; 
            font-weight: bold; 
            color: #2c3e50;
        }
        
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-bottom: 30px; 
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
            margin: 25px 0 10px 0;
            font-weight: 600;
            color: #2c3e50;
            border-left: 4px solid #f39c12;
            border-radius: 4px 0 0 4px;
        }
        
        .active { 
            color: #27ae60;
            font-weight: 600;
        }
        
        .inactive { 
            color: #e74c3c;
            font-weight: 600;
        }
        
        .status-badge {
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
        
        .badge-inactive {
            background-color: #fadbd8;
            color: #e74c3c;
        }
        
        .badge-lifetime {
            background-color: #d6eaf8;
            color: #2980b9;
        }
        
        .logo {
            height: 60px;
            margin-bottom: 15px;
        }
        
        .footer {
            text-align: center;
            margin-top: 50px;
            color: #7f8c8d;
            font-size: 12px;
            border-top: 1px solid #e0e0e0;
            padding-top: 15px;
        }
    </style>
</head>
<body>
    <div class="header">
        <!-- Replace with your actual logo path -->
        <!-- <img src="{{ public_path('images/logo.png') }}" class="logo" alt="Organization Logo"> -->
        <h1>Membership Report</h1>
        <p>Generated on {{ now()->format('F j, Y \a\t g:i A') }}</p>
    </div>

    <h2 style="color: #2c3e50; margin-bottom: 20px; font-size: 20px;">Membership Overview</h2>
    <div class="summary-cards">
        <div class="card total">
            <div class="card-title">Total Members</div>
            <div class="card-value">{{ $totalMembers }}</div>
        </div>
        <div class="card active">
            <div class="card-title">Active Members</div>
            <div class="card-value">{{ $activeMembers }}</div>
        </div>
        <div class="card inactive">
            <div class="card-title">Inactive Members</div>
            <div class="card-value">{{ $inactiveMembers }}</div>
        </div>
        <div class="card bureaus">
            <div class="card-title">Total Bureaus</div>
            <div class="card-value">{{ $totalBureaus }}</div>
        </div>
        <div class="card sections">
            <div class="card-title">Total Sections</div>
            <div class="card-value">{{ $totalSections }}</div>
        </div>
    </div>

    <h2 style="color: #2c3e50; margin-bottom: 20px; font-size: 20px;">Member Directory</h2>
    @foreach($bureaus as $bureau)
    <div class="bureau-header">
        {{ $bureau->bureau_name }} 
        <span style="font-size: 14px; color: #7f8c8d; font-weight: normal;">
            ({{ $bureau->members_count }} members, {{ $bureau->sections_count }} sections)
        </span>
    </div>

    @foreach($bureau->sections as $section)
    <div class="section-header">
        {{ $section->section_name }} 
        <span style="font-size: 13px; color: #7f8c8d; font-weight: normal;">
            ({{ $section->members_count }} members)
        </span>
    </div>

    <table>
        <thead>
            <tr>
                <th>Full Name</th>
                <th>Callsign</th>
                <th>Status</th>
                <th>Validity</th>
                <th>Membership Type</th>
            </tr>
        </thead>
        <tbody>
            @forelse($members->where('section_id', $section->id) as $member)
            <tr>
                <td>{{ $member->last_name }}, {{ $member->first_name }} {{ $member->middle_name ? $member->middle_name[0].'.' : '' }} {{ $member->suffix ?? '' }}</td>
                <td>{{ $member->license_number ?? 'Unlicensed' }}</td>
                <td>
                    <span class="status-badge badge-{{ $member->status === 'active' ? 'active' : 'inactive' }}">
                        {{ ucfirst($member->status) }}
                    </span>
                </td>
                <td>
                    @if($member->is_lifetime_member)
                        <span class="status-badge badge-lifetime">Lifetime</span>
                    @elseif($member->membership_end)
                        {{ \Carbon\Carbon::parse($member->membership_end)->format('m/d/Y') }}
                    @else
                        N/A
                    @endif
                </td>
                <td>{{ $member->membershipType->type_name ?? 'N/A' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align: center; color: #7f8c8d; padding: 20px;">No members in this section</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @endforeach
    @endforeach
    
    <div class="footer">
        <p>Confidential Report - Generated by {{ config('app.name') }}</p>
        <p>Page 1 of 1</p>
    </div>
</body>
</html>