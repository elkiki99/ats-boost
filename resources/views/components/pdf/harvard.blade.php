{{-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $name }} - CV</title>

    <style>
        body {
            font-family: "Times New Roman", serif;
            margin: 40px 55px;
            font-size: 12px;
            color: #000;
        }

        h1 {
            font-size: 26px;
            margin: 0 0 8px 0;
        }

        h2 {
            text-transform: uppercase;
            font-size: 16px;
            margin-top: 28px;
            margin-bottom: 6px;
        }

        .contact-line {
            font-size: 11px;
            margin-bottom: 25px;
        }

        .section {
            margin-bottom: 12px;
        }

        .item-title {
            font-weight: bold;
            font-size: 12.5px;
        }

        .item-subtitle {
            font-style: italic;
            font-size: 11.5px;
            margin-bottom: 2px;
        }

        .item-meta {
            font-size: 11.5px;
            margin-bottom: 2px;
        }

        ul {
            margin: 4px 0 8px 18px;
        }

        li {
            margin-bottom: 3px;
            line-height: 1.35;
        }
    </style>
</head>

<body>

    <!-- ================= HEADER ================= -->
    <h1>{{ $name }}</h1>

    <div class="contact-line">
        @if($contact['city']){{ $contact['city'] }}, {{ $contact['country'] }} • @endif
        @if($contact['address']){{ $contact['address'] }} • @endif
        @if($contact['email']){{ $contact['email'] }} • @endif
        @if($contact['phone']){{ $contact['phone'] }}@endif
    </div>


    <!-- ================= EDUCATION ================= -->
    @if(!empty($education))
        <h2>Education</h2>

        @foreach($education as $edu)
            <div class="section">
                <div class="item-title">
                    {{ $edu['school'] ?? '' }}
                    @if(!empty($edu['campus'])) - {{ $edu['campus'] }} @endif
                </div>

                <div class="item-subtitle">
                    {{ $edu['degree'] ?? '' }} 
                    @if(!empty($edu['year'])) • {{ $edu['year'] }} @endif
                </div>

                @if(!empty($edu['courses']))
                    <div class="item-meta"><strong>Relevant Courses:</strong></div>
                    <ul>
                        @foreach($edu['courses'] as $course)
                            <li>{{ $course }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>
        @endforeach
    @endif



    <!-- ================= PROJECTS ================= -->
    @if(!empty($projects))
        <h2>Academic Projects</h2>

        @foreach($projects as $proj)
            <div class="section">
                <div class="item-title">
                    {{ $proj['description'] ?? '' }}
                </div>

                <div class="item-subtitle">
                    {{ $proj['location'] ?? '' }}
                    @if(!empty($proj['year'])) • {{ $proj['year'] }} @endif
                </div>
            </div>
        @endforeach
    @endif



    <!-- ================= EXPERIENCE ================= -->
    @if(!empty($experience))
        <h2>Experience</h2>

        @foreach($experience as $exp)
            <div class="section">

                <div class="item-title">
                    {{ $exp['title'] ?? '' }}
                    @if($exp['role']) - {{ $exp['role'] }} @endif
                </div>

                <div class="item-subtitle">
                    {{ $exp['location'] ?? '' }} 
                    @if(!empty($exp['period'])) • {{ $exp['period'] }} @endif
                </div>

                @if(!empty($exp['tasks']))
                    <ul>
                        @foreach($exp['tasks'] as $task)
                            <li>{{ $task['text'] }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>
        @endforeach
    @endif



    <!-- ================= CERTIFICATIONS ================= -->
    @if(!empty($certifications))
        <h2>Certifications</h2>
        <ul>
            @foreach($certifications as $c)
                <li>{{ $c['text'] }}</li>
            @endforeach
        </ul>
    @endif



    <!-- ================= SKILLS ================= -->
    @if(!empty($skills))
        <h2>Skills</h2>

        <ul>
            @foreach($skills as $skill)
                <li><strong>{{ $skill['label'] }}:</strong> {{ $skill['value'] }}</li>
            @endforeach
        </ul>
    @endif



    <!-- ================= LANGUAGES ================= -->
    @if(!empty($languages))
        <h2>Languages</h2>

        <ul>
            @foreach($languages as $lang)
                <li>{{ $lang }}</li>
            @endforeach
        </ul>
    @endif

</body>
</html> --}}