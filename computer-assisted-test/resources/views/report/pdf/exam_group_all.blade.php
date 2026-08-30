<html>
<head>
    <title>{{ $examGroup->lessonCategory->name }} - {{ $examGroup->title }}</title>
    <style>
        table {
            border-collapse: collapse;
            border:1px solid #333;
            font-size:11px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
    
        table th {
            padding:5px;
            border:1px solid #333;
            text-align: center;
        }
    
        table td {
            padding:5px;
            border:1px solid #333;
        }
    
        .title {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
    
        @media print {
            .new-page {
                page-break-before: always;
            }
        }      
    </style>
</head>
<body>
    @if($setting)
        <br>
        <img src="{{ $setting->app_url }}/storage/upload_files/settings/{{ $setting->logo }}" alt="" height="50px" style="position: absolute; margin-top:-40px; margin-bottom:20px;">
    @endif
    <center>
        <div class="title">
            <h3>REKAPITULASI NILAI {{ strtoupper($examGroup->title) }}</h3>
        </div>
    </center>

    <table width="100%" border="1" style="font-size:8;">
    <thead>
        <tr>
            <th align="center" rowspan="2">No</th>
            @if($fields->has('code'))
                <th align="center" rowspan="2">{{ $fields['code']['translate'] ?? 'Kode' }}</th>
            @endif
            @if($fields->has('name'))
                <th align="center" rowspan="2">{{ $fields['name']['translate'] ?? 'Nama Lengkap' }}</th>
            @endif
            @if($fields->has('gender'))
                <th align="center" rowspan="2">{{ $fields['gender']['translate'] ?? 'Jenis Kelamin' }}</th>
            @endif
            @if($fields->has('province_id'))
                <th align="center" rowspan="2">{{ $fields['province_id']['translate'] ?? 'Provinsi' }}</th>
            @endif
            @if($fields->has('city_id'))
                <th align="center" rowspan="2">{{ $fields['city_id']['translate'] ?? 'Kota/Kab' }}</th>
            @endif
            <th align="center" colspan="{{ $examGroup->exam->count() }}">Detail Nilai</th>
            <th align="center" rowspan="2">Nilai Akhir</th>
            <th align="center" rowspan="2">{{ $examGroup->minimal_grade_type == 0 ? 'Peringkat' : 'Keterangan' }}</th>
        </tr>
        <tr>
            @foreach($examGroup->exam as $exam)
                <th align="center">{{ $exam->lesson->short_name ? $exam->lesson->short_name : $exam->lesson->name }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach($examGroupUsers as $index => $examGroupUser)
            <tr>
                <td align="center">{{ $loop->iteration }}</td>
                @if($fields->has('code'))
                    <td>{{ $examGroupUser->user->code ?? '-' }}</td>
                @endif
                @if($fields->has('name'))
                    <td>{{ $examGroupUser->user->name ?? '-' }}</td>
                @endif
                @if($fields->has('gender'))
                    <td>{{ $examGroupUser->user->student ? ($examGroupUser->user->student->gender == "F" ? "Perempuan" : "Laki-laki") : '-'}}</td>
                @endif
                @if($fields->has('province_id'))
                    <td>{{ $examGroupUser->user->student ? ($examGroupUser->user->student->province->name ?? '-') : '-' }}</td>
                @endif
                @if($fields->has('city_id'))
                    <td>{{ $examGroupUser->user->student ? ($examGroupUser->user->student->city->name ?? '-') : '-' }}</td>
                @endif
                @foreach($examGroup->exam as $exam)
                    @php
                        $gradeCollection = $grades->get($examGroupUser->user_id . '-' . $exam->id);
                        $gradeDetail = $gradeCollection ? $gradeCollection->first() : null;
                    @endphp
                    <td align="center" width="20">
                        {{ $gradeDetail ? gradeFormat($gradeDetail->grade) : '-' }}
                    </td>
                @endforeach
                <td align="center">{{ gradeFormat($examGroupUser->grade) }}</td>
                <td align="center">
                    {{ $examGroup->minimal_grade_type == 0 
                        ? ($examGroupUser->is_finished ? $index + 1 : '-') 
                        : ($examGroupUser->description ?? 'Belum Menyelesaikan Tes') }}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
</body>
</html>