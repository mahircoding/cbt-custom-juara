<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $questionTitle->name }}</title>
    <script type="text/javascript" id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-wDpOeR/0Ck8EeAEn5tpQWillDZlP++sz7KRGM2fvlrXjHqr8N6nWdWBvSWbIUKOJ" crossorigin="anonymous"></script>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 210mm;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ccc;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .logo {
            height: 50px;
            margin-bottom: 10px;
        }

        .title {
            text-align: center;
            margin-bottom: 10px;
        }

        ol {
            margin-top: 0;
            padding-left: 10px;
        }

        ol li {
            margin-bottom: 10px;
        }

        @media print {
            .container {
                border: none;
                box-shadow: none;
            }

            .new-page {
                page-break-before: always;
            }

            .print-button {
                display: none; /* Hide the print button when printing */
            }

            a[href]::after {
                content: '' !important;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <button class="btn btn-danger print-button" onclick="window.print()">Save ke PDF</button>
    </div>
    <div class="container print-area">
        @if($setting)
            <img src="{{ $setting->app_url }}/storage/upload_files/settings/{{ $setting->logo }}" alt="Logo" class="logo">
        @endif
        <div class="title">
            <h3>{{ $questionTitle->name }}</h3>
        </div>
        <ol>
            @foreach($questions as $index => $question)
                <li>
                    @if($request->show_value_category == 1)
                        <div class="badge badge-primary">{{ $question->valueCategory ? $question->valueCategory->name : '' }}</div>
                    @endif
                    {!! $question->question !!}
                    <ol type="A">
                        @for ($i = 1; $i <= $questionTitle->total_choices; $i++)
                            <li>
                                @if($questionTitle->assessment_type == 4 && $request->show_answer_weight == 1)
                                    <span class="badge badge-danger">Bobot jawaban {{ $question['point_' . $i] }}</span> {!! $question['option_' . $i] !!}
                                @else
                                    {!! $question['option_' . $i] !!}
                                @endif
                            </li>
                        @endfor
                    </ol>
        
                    @if($questionTitle->assessment_type != 4 && $request->show_answer_key == 1)
                        <b>
                            <span class="badge badge-primary">
                                Jawaban :
                                @for ($i = 0; $i < count($question->answer); $i++)
                                    {{ chr(64 + $question->answer[$i]) }}
                                    @if ($i < count($question->answer) - 1),@endif
                                @endfor</span>
                        </b><br>
                    @endif

                    @if($request->show_discussion == 1)
                        Pembahasan : {!! $question->discussion ? $question->discussion : '<i>Tidak ada pembahasan</i>' !!}
                        <hr>
                    @endif
                </li>
            @endforeach
        </ol>
    </div>
</body>
</html>
